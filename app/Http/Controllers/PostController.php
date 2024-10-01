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
        return redirect()->route('crud')->with('success','Telah di hapus dari kenyataan');
    }
}
