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
            $inv = Inventory::where('name', 'like', '%' . $search_item. '%');
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
    //Untuk Tambah
    public function store(Request $request): JsonResponse {
        try{
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
            ]);
            $check = Inventory::query()->where('name', $request->get('id'));
            $data = $request->get('name') !== null ? $check->first() : new Post();
            $data->setAttribute('name', $request->get('name'));
            $data->setAttribute('description',$request->get('description'));
            $data->save();
            return response()->json([
                'status' => true,
                'statusCode' => 200,
                'message' => 'File saved successfully',
                'data' => $data,
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
}
