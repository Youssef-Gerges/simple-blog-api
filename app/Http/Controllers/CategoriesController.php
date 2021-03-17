<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
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

    public function allCategories()
    {
        $categories = Category::all();
        return $categories;
    }

    public function addCategory(Request $request)
    {
        $this->validate($request, [
            'catName' => 'required|string|unique:categories,name'
        ]);

        $category = new Category();
        $category->name = $request->catName;
        $category->save();

        return $category;
    }

    public function deleteCategory(Request $request)
    {
        $this->validate($request, [
            'catId' => 'required|numeric'
        ]);
        $category = Category::findOrFail($request->catId);
        $category->delete();
        return response()->json(['message'=>'Category deleted successfully']);
    }

    public function editCategory(Request $request)
    {
        $this->validate($request, [
            'catId' => 'required|numeric',
            'catName' => 'required|string|unique:categories,name'
        ]);
        $category = Category::findOrFail($request->catId);
        $category->name = $request->catName;
        $category->save();
        return response()->json(['message'=>'Category edited successfully']);
    }
}
