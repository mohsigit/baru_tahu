<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Http\JsonResponse;


class PostController extends Controller
{
    public function index()
    {
        return Inertia::render('Crud');
    }
    public function getTable(Request $request): JsonResponse {

        try {
            $perPage = $request->get('perPage') ?? 10;
            $page = $request->get('page') ?? 1;
            $field = $request->get('sortField') ?? 'created_at';
            $type = $request->get('sortType') ?? 'desc';
            if($type === 'none'){
                $field = 'created_at';
                $type = 'desc';
            }
            $search_item = $request->get('searchQuery') ?? '';
            $posts = Post::query()
            ->where('title', 'like', '%' . $search_item. '%')
            ->orWhere('description', 'like', '%' . $search_item. '%');
            $posts = $posts->orderBy($field, $type);
            return response()->json([
                'status' => true,
                'statusCode' => 200,
                'message' => 'success',
                'data' => $posts->paginate($perPage, ['*'], '', $page),
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
            $post = Post::findOrFail($id);
            $post->delete();
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
            $check = Post::query()->where('id', $request->get('id'));
            $data = $request->get('id') !== null ? $check->first() : new Post();
            $data->setAttribute('title', $request->get('title'));
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
