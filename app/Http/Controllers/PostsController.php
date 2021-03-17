<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function index()
    {
        //return response()->json(app('db')->select('select * from posts'));
        $posts = Post::where('active', '=', 1)
            ->orderBy('created_at', 'desc')
            ->with(['category:id,name', 'user:id,username'])
            ->paginate(10);
        return $posts;
    }

    public function showPost($id)
    {
        $post = Post::with(['category:id,name', 'user:id,username', 'comments'])->findOrFail($id);
        return $post;
    }

    public function addPost(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string',
            'content' => 'required|string',
            'active' => 'required|boolean',
            'featured' => 'required|boolean',
            'category_id' => 'required|exists:categories,id',
            'cover' => 'required|file'
        ]);
        $post = new Post();
        $post->title = $request->title;
        $post->content = $request->content;
        $post->active = $request->active;
        $post->featured = $request->featured;
        $post->category_id = $request->category_id;
        $post->user_id = $request->user()->id;

        if ($request->hasFile('cover')) {
            $image = $request->file('cover');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = storage_path('app/images/');
            $image->move($destinationPath, $name);
            $post->cover = 'images/' . $name;
            $post->save();
            return $post;
        }
        return response()->json(['message'=>'save faild']);
    }

    public function editPost(Request $request)
    {
        $post = Post::findOrFail($request->post_id);
        if($request->user()->id != $post->user_id){
            return response()->json(['message'=>'Permission denied']);
        }

        //cannot chnge cover or awner user
        $post->title = $request->title;
        $post->content = $request->content;
        $post->active = $request->active;
        $post->featured = $request->featured;
        $post->category_id = $request->category_id;
        $post->save();
        return $post;
    }

    public function deletePost(Request $request)
    {
        $post = Post::findOrFail($request->post_id);
        if($request->user()->id != $post->user_id){
            return response()->json(['message'=>'Permission denied']);
        }
        $post->delete();
        return response()->json(['message'=>'post deleted successfully']);
    }

    public function showAuther($id)
    {
        $user = User::findOrFail($id);
        $posts = $user->posts()->paginate(10);
        return $posts;
    }

    public function showCategoryPosts($id)
    {
        $category = Category::findOrFail($id);
        $posts = $category->posts()->paginate(10);
        return $posts;
    }
}
