<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostDetailsResource;
use App\Http\Resources\PostResource;
use App\Models\Category;
use App\Models\Media;
use Illuminate\Support\Facades\Storage;


class PostController extends Controller
{
    // post list
    public function index (Request $request) {
        $query = Post::orderByDesc('created_at');

        if($request->category_id){
            $query->where('category_id' , $request->category_id);
        }

        if($request->search) {
            $query->where(function($qul) use ($request){
                $qul->where('title','like','%'. $request->search .'%')
                       ->orWhere('description','like','%'. $request->serarch .'%');
            });

        }

        $posts = $query->paginate(10);

        return PostResource::collection($posts)->additional(['message' => 'success']);
    }

    // post details
    public function details ($id) {
        $posts = Post::where('id' ,$id)->first0rfail();

        return ResponseHelper::success(new PostDetailsResource($posts));
    }

    // post Create
    public function create (Request $request) {

        Category::get();
        $request->validate([
            "title" => 'required|string',
            "description" => 'required|string',
            'category_id' => 'required',
        ], 
        [
            "category_id.required" => 'The category field is required',
        ]
    );

    DB::beginTransaction();
    try {
        $file_name = null;

        if($request->hasFile('image')){
            $file = $request->file('image');
            $file_name = uniqid() . '-'. date('Y-m-d-H-i-s') . '.' . $file->getClientOriginalExtension();
            Storage::put('media/'.$file_name , file_get_contents($file));
        }

    
        $post = new Post();
        $post->user_id = auth()->user()->id;
        $post->title = $request->title;
        $post->description = $request->description;
        $post->category_id = $request->category_id;
        $post->save();

        $media = new Media();
        $media->file_name = $file_name;
        $media->file_type = 'image';
        $media->model_id = $post->id;
        $media->model_type = Post::class;
        $media->save();
    
        DB::commit();
        return ResponseHelper::success([],'Succefully Uploaded .');
    } catch (Exception $e) {

        DB::rollBack();
       return ResponseHelper::fail($e->getMessage());
    }
   
    }
}
