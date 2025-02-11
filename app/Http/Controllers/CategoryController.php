<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(){
        return view('index');
    }

    public function store(Request $request){
        // dd($request->all());

        $request->validate([
            'name' => 'required|string',
        ]);

        Category::create([
            'name' => $request->name,
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'Category Saved Successfully',
        ], 200);
    }
}
