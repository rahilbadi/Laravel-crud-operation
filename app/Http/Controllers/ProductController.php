<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use App\Models\Product;

class ProductController extends Controller
{
    //This method will show products page

    public function index(){
        $products = Product::all();
        return view('products.list',[
            'products' => $products
        ]);
    }

    // This method will show create product page

    public function create(){
        return view('products.create');
    }

    // This method will store a product insert data

    public function store(Request $request) {
        $rules = [
            'name' => 'required|min:5',
            'sku' => 'required|min:3',
            'price' => 'required|numeric'
        ];

        if ($request->image != "") {
            $rules['image'] = 'image';
        }

        $validator = Validator::make($request->all(),$rules);

        if ($validator->fails()) {
            return redirect()->route('products.create')->withInput()->withErrors($validator);
        }

        // here we will insert product in db
        $product = new Product();
        $product->name = $request->name;
        $product->sku = $request->sku;
        $product->price = $request->price;
        $product->description = $request->description;
        $product->save();

        if ($request->image != "") {
            // here we will store image
            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imageName = time().'.'.$ext; // Unique image name

            // Save image to products directory
            $image->move(public_path('uploads/products'),$imageName);

            // Save image name in database
            $product->image = $imageName;
            $product->save();
        }

        return redirect()->route('products.index')->with('success','Product added successfully.');
    }

    // Thismethod will show edit product page

    public function edit($id){
        $product = Product::findOrfail($id);
        return view('products.edit',[
            'product' => $product
        ]);
    }

    // This method will update a product

    public function update($id, Request $request){

        $product = Product::findOrfail($id);

        $rules = [
            'name' => 'required|min:5',
            'sku' => 'required|min:3',
            'price' => 'required|numeric'
        ];

        if ($request->image != "") {
            $rules['image'] = 'image';
        }

        $validator = Validator::make($request->all(),$rules);

        if ($validator->fails()) {
            return redirect()->route('products.edit',$product->id)->withInput()->withErrors($validator);
        }

        // here we will update product in db
        $product->name = $request->name;
        $product->sku = $request->sku;
        $product->price = $request->price;
        $product->description = $request->description;
        $product->save();

        if ($request->image != "") {
            // Delete old image

            File::delete(public_path('upload/products'>$product->image));

            // here we will store image
            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imageName = time().'.'.$ext; // Unique image name

            // Save image to products directory
            $image->move(public_path('uploads/products'),$imageName);

            // Save image name in database
            $product->image = $imageName;
            $product->save();
        }

        return redirect()->route('products.index')->with('success','Product updated successfully.');
    }

    // This method will delete a product

    public function destory($id){
        $product = Product::findOrfail($id);

        // Delete image

        File::delete(public_path('upload/products'>$product->image));

        //  delete product from database

        $product->delete();

        return redirect()->route('products.index')->with('success','Product delete successfully.');
    }
}
