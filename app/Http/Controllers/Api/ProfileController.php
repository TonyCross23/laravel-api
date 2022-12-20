<?php

namespace App\Http\Controllers\Api;


use App\Models\Post;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Http\Resources\ProfileResource;

class ProfileController extends Controller
{
    public function profile () {
        $user = auth()->guard()->user();

        return ResponseHelper::success(new ProfileResource($user));
    }

    // profile posts
    public function profilePost (Request $request) {
        $query = Post::orderByDesc('created_at')->where('user_id',auth()->user()->id);

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
}
