<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index(){
        if (
            Auth::user()->hasPermissionTo('Category List') ||
            Auth::user()->hasPermissionTo('Category Create') ||
            Auth::user()->hasPermissionTo('Category Edit') ||
            Auth::user()->hasPermissionTo('Category Delete')
        ) {
            $categories = Category::all();
            Helper::logSystemActivity('Category', 'Category List');
            return view('materials.category.index', compact('categories'));
        }
        return back()->with('custom_errors', 'You don`t have Right Permission');
    }
    
    public function create(){
        if (!Auth::user()->hasPermissionTo('Category Create')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        Helper::logSystemActivity('Category', 'Category Create');
        return view('materials.category.create');
    }

    public function store(Request $request){

        if (!Auth::user()->hasPermissionTo('Category Create')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        $request->validate([
            'name' => [
                'required',
                Rule::unique('categories', 'name')->whereNull('deleted_at'),
            ],
            'code' => [
                'required',
                Rule::unique('categories', 'code')->whereNull('deleted_at'),
            ],
        ]);

        $category = new Category();
        $category->name = $request->name;
        $category->code = $request->code;
        $category->save();
        Helper::logSystemActivity('Category', 'Category Create');
        return redirect()->route('category.index')->with('custom_success', 'Category has been Succesfully Created!');
    }

    public function edit($id){

        if (!Auth::user()->hasPermissionTo('Category Edit')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        $category = Category::find($id);
        Helper::logSystemActivity('Category', 'Category Edit');
        return view('materials.category.edit', compact("category"));
    }

    public function update(Request $request, $id){

        if (!Auth::user()->hasPermissionTo('Category Edit')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        $request->validate([
            'name' => [
                'required',
                Rule::unique('categories', 'name')->whereNull('deleted_at')->ignore($id),
            ],
            'code' => [
                'required',
                Rule::unique('categories', 'code')->whereNull('deleted_at')->ignore($id),
            ],
        ]);

        $category = Category::find($id);
        $category->name = $request->name;
        $category->code = $request->code;
        $category->save();
        Helper::logSystemActivity('Category', 'Category Update');
        return redirect()->route('category.index')->with('custom_success', 'Category has been Succesfully Updated!');        
    }

    public function  destroy($id){

        if (!Auth::user()->hasPermissionTo('Category Delete')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        $category = Category::find($id);
        $category->delete();
        Helper::logSystemActivity('Category', 'Category Delete');
        return redirect()->route('category.index')->with('custom_success', 'Category has been Succesfully Deleted!');
    }
}
