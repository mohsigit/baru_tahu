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
    public function update(Request $request, $id){ // $request jadi parameter fungsi dan $id  parameter yang di tarik dari route
        $request->validate([
            'title' => 'required|string|max:255', //buat validasi biar tittlenya gk lebih dari 255 char
        ]);
        $post = Post::findOrFail($id); //buat nagkep get $id nya klo gk ada jadi error

        $post->title = $request->input('title'); // buat nyimpen ke database setelah di rubah
        $post->save();

        return redirect()->back()->with('success', 'Post updated successfully!'); // gak tau kenapa tapi gk pernah muncul
    }
}
