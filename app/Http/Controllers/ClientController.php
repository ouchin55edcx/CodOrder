<?php
namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use App\Http\Responses\Client\ClientResponse;
use App\Models\Client;
use App\Http\Requests\Client\StoreClientRequest;
use App\Http\Requests\Client\UpdateClientRequest;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ClientsImport;
use App\Models\Admin;

class ClientController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        // Log user authentication status
        Log::info('Index method accessed', [
            'is_authenticated' => Auth::check(),
            'user_id' => Auth::id()
        ]);

        return ClientResponse::collection(Client::paginate(10));
    }

    public function store(StoreClientRequest $request)
    {
        // Debug authentication
        Log::info('Store method accessed', [
            'is_authenticated' => Auth::check(),
            'method' => 'store',
            'controller' => 'ClientController'
        ]);

        // Check if the user is authenticated
        if (!Auth::check()) {
            Log::error('No authenticated user');
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Log the authenticated user's details for debugging
        $user = Auth::user();
        Log::info('User details:', [
            'id' => $user->id,
            'email' => $user->email,
            'has_admin_role' => $user->hasRole('admin'),
            'roles' => $user->getRoleNames()->toArray()
        ]);

        // Ensure the user has the 'admin' role
        if (!$user->hasRole('admin')) {
            Log::error('User does not have admin role', [
                'user_id' => $user->id,
                'roles' => $user->getRoleNames()->toArray()
            ]);
            return response()->json(['message' => 'You do not have permission to perform this action.'], 403);
        }

        // Get the admin profile for the authenticated user
        $admin = $user->admin;
        Log::info('Admin relationship check', [
            'has_admin' => $admin !== null,
            'admin_id' => $admin ? $admin->id : null
        ]);

        // Ensure the admin profile exists
        if (!$admin) {
            Log::error('Admin profile not found', ['user_id' => $user->id]);
            return response()->json(['message' => 'Admin profile not found.'], 404);
        }

        try {
            // Debug authorization
            Log::info('Before policy authorization');
            $this->authorize('create', Client::class);
            Log::info('After policy authorization - passed');
        } catch (\Exception $e) {
            Log::error('Policy authorization failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['message' => 'Authorization error: ' . $e->getMessage()], 403);
        }

        // Check trial limits
        if ($admin->client_count >= 10 && $user->isOnTrial()) {
            Log::info('Trial limit reached', [
                'client_count' => $admin->client_count,
                'is_on_trial' => $user->isOnTrial()
            ]);
            return response()->json([
                'message' => 'Trial limit reached. You can only create 10 clients during the trial period.'
            ], 403);
        }

        try {
            // Create the client
            $client = Client::create($request->validated());
            Log::info('Client created successfully', ['client_id' => $client->id]);

            // Increment the client count for the admin
            $admin->increment('client_count');
            Log::info('Admin client count incremented', [
                'admin_id' => $admin->id,
                'new_client_count' => $admin->client_count
            ]);

            // Return the client response
            return new ClientResponse($client);
        } catch (\Exception $e) {
            Log::error('Error creating client', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['message' => 'Error creating client: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $client = Client::findOrFail($id);
        return new ClientResponse($client);
    }

    public function update(UpdateClientRequest $request, $id)
    {
        try {
            Log::info('Before policy authorization for update');
            $this->authorize('update', Client::class);
            Log::info('After policy authorization for update - passed');
        } catch (\Exception $e) {
            Log::error('Policy authorization for update failed:', [
                'error' => $e->getMessage()
            ]);
            return response()->json(['message' => 'Authorization error: ' . $e->getMessage()], 403);
        }

        $client = Client::findOrFail($id);
        $client->update($request->validated());
        return new ClientResponse($client);
    }

    public function destroy($id)
    {
        try {
            Log::info('Before policy authorization for delete');
            $this->authorize('delete', Client::class);
            Log::info('After policy authorization for delete - passed');
        } catch (\Exception $e) {
            Log::error('Policy authorization for delete failed:', [
                'error' => $e->getMessage()
            ]);
            return response()->json(['message' => 'Authorization error: ' . $e->getMessage()], 403);
        }

        $client = Client::findOrFail($id);
        $client->delete();

        // Decrement count
        Auth::user()->admin->decrement('client_count');

        return response()->json(null, 204);
    }

    public function import(Request $request)
    {
        $admin = Auth::user()->admin;

        // Check trial limits
        if ($admin->client_count >= 10 && $admin->user->isOnTrial()) {
            return response()->json([
                'message' => 'Trial limit reached. You can only create 10 clients during trial period.'
            ], 403);
        }

        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            $import = new ClientsImport();
            Excel::import($import, $request->file('file'));

            // Update client count
            $admin->increment('client_count', $import->getRowCount());

            return response()->json([
                'message' => 'Import completed successfully',
                'total_imported' => $import->getRowCount(),
                'total_errors' => count($import->errors()),
                'errors' => $import->errors()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Import failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
