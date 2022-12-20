<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function category () {
        $categories = Category::get();

        return ResponseHelper::success(CategoryResource::collection($categories));
    }
}
