<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function getAllCategory()
    {
        $categories = DB::table('categories')->get(['id', 'category_title']);

        return response()->json($categories);
    }
}
