<?php

namespace App\Http\Controllers;

use App\Http\Responses\Client\ClientResponse;
use App\Models\Client;
use App\Http\Requests\Client\StoreClientRequest;
use App\Http\Requests\Client\UpdateClientRequest;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ClientsImport;

class ClientController extends Controller
{
    public function index()
    {
        return ClientResponse::collection(Client::paginate(10));
    }

    public function store(StoreClientRequest $request)
    {
        $client = Client::create($request->validated());
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
        return response()->json(null, 204);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            $import = new ClientsImport();
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
