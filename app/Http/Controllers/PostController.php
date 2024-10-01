<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::all();
        return Inertia::render('Crud', [
            'posts' => $posts
        ]);
    }
    public function destroy(Post $post){
        $post->delete();
        return redirect()->route('posts.index')->with('success', 'Telah dihapus dari kenyataan');
    }
    //Untuk Tambah
    public function store(Request $request){
        $request->validate([
            'title' => 'required|string|max:255',
        ]);
        Post::create([
            'title' => $request->title,
        ]);
        return redirect()->route('posts.index')->with('success', 'Post created successfully!');
    }
}
