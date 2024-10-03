<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Inventory;
use Illuminate\Http\JsonResponse;


class InventoryController extends Controller
{
    public function index()
    {
        return Inertia::render('Inventory');
    }
    public function getTable(Request $request): JsonResponse {

        try {
            $perPage = $request->get('perPage') ?? 10;
            $page = $request->get('page') ?? 1;
            $field = $request->get('field') ?? 'created_at';
            $type = $request->get('type') ?? 'desc';
            $search_item = $request->get('type') ?? '';
            $inv = Inventory::query()
                ->where('name', 'like', '%' . $search_item. '%')
                ->orWhere('qty','like', '%' . $search_item . '%')
                ->orWhere('balance','like', '%' . $search_item . '%')
                ->orWhere('remarks','like', '%' . $search_item . '%')
                ->orWhere('color','like', '%' . $search_item . '%')
                ->orWhere('size','like', '%' . $search_item . '%')
                ->orWhere('description','like', '%' . $search_item . '%');
            $inv = $inv->orderBy($field, $type);
            return response()->json([
                'status' => true,
                'statusCode' => 200,
                'message' => 'success',
                'data' => $inv->paginate($perPage, ['*'], '', $page),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'statusCode' => 500,
                'message' => $e->getMessage(),
                'data' => null,
            ]);
        }

    }
    public function destroy($id): JsonResponse {
        try{
            $inv = Inventory::findOrFail($id);
            $inv->delete();
            return response()->json([
                'status' => true,
                'statusCode' => 200,
                'message' => 'File deleted successfully',
                'data' => null,
            ]);
        } catch(\Exception $e){
            return response()->json([
                'status' => false,
                'statusCode' => 500,
                'message' => $e->getMessage(),
                'data' => null,
            ]);
        }
    }
    public function store(Request $request): JsonResponse
{
    try {
        // Validate the request fields
        $request->validate([
            'name' => 'required|string|max:255',
            'qty' => 'required|integer',
            'balance' => 'required|numeric',
            'remarks' => 'nullable|string',
            'color' => 'nullable|string|max:50',
            'size' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ]);

        // Check if we are updating or creating a new record
        $check = Inventory::query()->where('id', $request->get('id'));
        $data = $request->get('id') !== null ? $check->first() : new Inventory();

        // Set attributes for the inventory item
        $data->setAttribute('name', $request->get('name'));
        $data->setAttribute('qty', $request->get('qty'));
        $data->setAttribute('balance', $request->get('balance'));
        $data->setAttribute('remarks', $request->get('remarks'));
        $data->setAttribute('color', $request->get('color'));
        $data->setAttribute('size', $request->get('size'));
        $data->setAttribute('description', $request->get('description'));

        // Save the inventory item
        $data->save();

        // Return success response
        return response()->json([
            'status' => true,
            'statusCode' => 200,
            'message' => 'Inventory item saved successfully',
            'data' => $data,
        ]);
    } catch (\Exception $e) {
        // Return error response in case of failure
        return response()->json([
            'status' => false,
            'statusCode' => 500,
            'message' => $e->getMessage(),
            'data' => null,
        ]);
    }
}

}
