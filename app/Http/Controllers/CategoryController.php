<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    public function index(){
        return view('index');
    }

    public function getCategories(Request $request){

        $categories = Category::all();
        if($request->ajax()){
            return DataTables::of($categories)
                ->addColumn('action', function($category){
                    return '<a href="" class="btn btn-info">Edit</a>';
                })
                // ->rowColumn(['action'])
                ->make(true);
        }
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
