<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Size;
use Illuminate\Http\Request;

class CategoryApiController extends Controller
{
    public function getCategories()
    {
        return response()->json(Category::all());
    }

    public function getSizes()
    {
        return response()->json(Size::all());
    }
}
