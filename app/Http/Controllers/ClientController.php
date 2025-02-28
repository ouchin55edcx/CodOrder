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
        return ClientResponse::collection(Client::paginate(10));
    }

    public function store(StoreClientRequest $request)
    {
        // Check if the user is authenticated
        if (!Auth::check()) {
            Log::info('No authenticated user');
            return response()->json(['message' => 'Unauthorized'], 401);
        }
    
        // Log the authenticated user's roles for debugging
        $user = Auth::user();
        Log::info('User roles:', ['roles' => $user->getRoleNames()->toArray()]);
    
        // Ensure the user has the 'admin' role
        if (!$user->hasRole('admin')) {
            return response()->json(['message' => 'You do not have permission to perform this action.'], 403);
        }
    
        // Get the admin profile for the authenticated user
        $admin = $user->admin;
    
        // Ensure the admin profile exists
        if (!$admin) {
            return response()->json(['message' => 'Admin profile not found.'], 404);
        }
    
        // Authorize the action (if using policies)
        $this->authorize('create', Client::class);
    
        // Check trial limits
        if ($admin->client_count >= 10 && $user->isOnTrial()) {
            return response()->json([
                'message' => 'Trial limit reached. You can only create 10 clients during the trial period.'
            ], 403);
        }
    
        // Create the client
        $client = Client::create($request->validated());
    
        // Increment the client count for the admin
        $admin->increment('client_count');
    
        // Return the client response
        return new ClientResponse($client);
    }

    public function show($id)
    {
        $client = Client::findOrFail($id);
        return new ClientResponse($client);
    }

    public function update(UpdateClientRequest $request, $id)
    {
        $client = Client::findOrFail($id);
        $client->update($request->validated());
        return new ClientResponse($client);
    }

    public function destroy($id)
    {
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