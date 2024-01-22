<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Product;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index()
    {
        if (
            Auth::user()->hasPermissionTo('Product List') ||
            Auth::user()->hasPermissionTo('Product Create') ||
            Auth::user()->hasPermissionTo('Product Edit') ||
            Auth::user()->hasPermissionTo('Product Delete')
        ) {
            $products = Product::all();
            Helper::logSystemActivity('Product', 'Product List');
            return view('products.product.index', compact('products'));
        }
        return back()->with('custom_errors', 'You don`t have Right Permission');
    }

    public function create()
    {
        if (!Auth::user()->hasPermissionTo('Product Create')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        Helper::logSystemActivity('Product', 'Product Create');
        return view('products.product.create');
    }

    public function store(Request $request)
    {
        if (!Auth::user()->hasPermissionTo('Product Create')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        $request->validate([
            'name' => [
                'required',
                Rule::unique('products', 'name')->whereNull('deleted_at'),
            ],
            'code' => [
                'required',
                Rule::unique('products', 'code')->whereNull('deleted_at'),
            ],
            "company" => "required",
            "dimension" => "required"
        ]);

        $product = new Product();
        $product->name = $request->input('name');
        $product->code = $request->input('code');
        $product->company = $request->input('company');
        $product->dimension = $request->input('dimension'); 
        $product->description = $request->input('description'); 
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move('products/', $filename);
            $product->file = $filename;
        }
        $product->save();
        Helper::logSystemActivity('Product', 'Product Store');
        return redirect()->route('product.index')->with('custom_success', 'Product has been Succesfully Created!');
    }

    public function edit(string $id){
        if (!Auth::user()->hasPermissionTo('Product Edit')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        $product = Product::find($id);
        Helper::logSystemActivity('Product', 'Product Edit');
        return view('products.product.edit', compact('product'));
    }

    public function update(Request $request, $id){

        if (!Auth::user()->hasPermissionTo('Product Edit')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        $request->validate([
            'name' => [
                'required',
                Rule::unique('products', 'name')->whereNull('deleted_at')->ignore($id),
            ],
            'code' => [
                'required',
                Rule::unique('products', 'code')->whereNull('deleted_at')->ignore($id),
            ],
            "company" => "required",
            "dimension" => "required"
        ]);

        $product = Product::find($id);
        $product->name = $request->input('name');
        $product->code = $request->input('code');
        $product->company = $request->input('company');
        $product->dimension = $request->input('dimension'); 
        $product->description = $request->input('description'); 
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move('products/', $filename);
            $product->file = $filename;
        }
        $product->save();
        Helper::logSystemActivity('Product', 'Product Update');
        return  redirect()->route('product.index')->with('custom_success', 'Product has been Succesfully Updated!');
    }

    public function destroy($id){

        if (!Auth::user()->hasPermissionTo('Product Delete')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        $product = Product::find($id);
        $Purchase = PurchaseOrder::where('product_id', '=', $id)->first();
        if($Purchase){
            return back()->with('custom_errors', 'This PRODUCT is used in PURCHASE ORDER!');
        }
        $product->delete();
        Helper::logSystemActivity('Product', 'Product Delete');
        return redirect()->route('product.index')->with('custom_success', 'Product has been Succesfully Deleted!');
    }
}
