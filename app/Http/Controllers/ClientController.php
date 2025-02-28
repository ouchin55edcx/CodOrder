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
use App\Models\Company;

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

        // Get the authenticated admin's company
        $company = Auth::user()->admin->company;

        // Fetch clients belonging to the company
        $clients = $company->clients()->paginate(10);

        return ClientResponse::collection($clients);

    }

    public function store(StoreClientRequest $request)
    {
        // Get the authenticated admin's company
        $company = Auth::user()->admin->company;

        // Check if the client already exists in the company
        $client = Client::where('email', $request->email)
            ->orWhere('phone', $request->phone)
            ->first();

        if ($client && $client->companies()->where('company_id', $company->id)->exists()) {
            return response()->json([
                'message' => 'Client already exists in this company.'
            ], 409); // Conflict status code
        }

        // Create the client
        $client = Client::create($request->only([
            'full_name',
            'phone',
            'email',
            'city',
            'address',
        ]));

        // Attach the client to the company
        $client->companies()->attach($company->id);

        return new ClientResponse($client);
    }

    public function show($id)
    {
        // Get the authenticated admin's company
        $company = Auth::user()->admin->company;

        // Fetch the client belonging to the company
        $client = Client::where('company_id', $company->id)->findOrFail($id);

        return new ClientResponse($client);
    }

    public function update(UpdateClientRequest $request, $id)
    {
        // Get the authenticated admin's company
        $company = Auth::user()->admin->company;

        // Fetch the client belonging to the company
        $client = Client::where('company_id', $company->id)->findOrFail($id);

        try {
            Log::info('Before policy authorization for update');
            $this->authorize('update', $client); // Authorize the specific client
            Log::info('After policy authorization for update - passed');
        } catch (\Exception $e) {
            Log::error('Policy authorization for update failed:', [
                'error' => $e->getMessage()
            ]);
            return response()->json(['message' => 'Authorization error: ' . $e->getMessage()], 403);
        }

        // Update the client
        $client->update($request->validated());

        return new ClientResponse($client);
    }

    public function destroy($id)
    {
        // Get the authenticated admin's company
        $company = Auth::user()->admin->company;

        // Fetch the client belonging to the company
        $client = Client::where('company_id', $company->id)->findOrFail($id);

        try {
            Log::info('Before policy authorization for delete');
            $this->authorize('delete', $client); // Authorize the specific client
            Log::info('After policy authorization for delete - passed');
        } catch (\Exception $e) {
            Log::error('Policy authorization for delete failed:', [
                'error' => $e->getMessage()
            ]);
            return response()->json(['message' => 'Authorization error: ' . $e->getMessage()], 403);
        }

        // Delete the client
        $client->delete();

        return response()->json(null, 204);
    }

    public function import(Request $request)
    {
        // Get the authenticated admin's company
        $company = Auth::user()->admin->company;

        // Check trial limits
        if ($company->clients()->count() >= 10 && $company->admin->user->isOnTrial()) {
            return response()->json([
                'message' => 'Trial limit reached. You can only create 10 clients during the trial period.'
            ], 403);
        }

        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            // Pass the company_id to the import class
            $import = new ClientsImport($company->id);
            Excel::import($import, $request->file('file'));

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
