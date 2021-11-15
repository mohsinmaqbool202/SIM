<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\ProductSizeStock;
use Str;
use Arr;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::with(['category', 'brand'])->orderBy('id', 'DESC')->get();
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        #validate data
        $validate = Validator::make($request->all(), [
            'category_id'  => 'required|numeric',
            'brand_id'  => 'required|numeric',
            'name'  => 'required|string|min:2|max:200|unique:products',
            'sku'  => 'required|string|max:100|unique:products',
            'image'  => 'required|image|mimes:jpeg,jpg,png|max:1024',
            'cost_price'  => 'required|numeric',
            'retail_price'  => 'required|numeric',
            'year'  => 'required',
            'description'  => 'required|max:200',
            'status'  => 'required|numeric',

        ]);

        #Error response
        if($validate->fails()){
            return response()->json([
                'success' => false,
                'errors'  => $validate->errors()
            ], 422);
        }

        #store product
        $product = new Product;
        $product->user_id      = Auth::id();
        $product->category_id  = $request->category_id;
        $product->brand_id     = $request->brand_id;
        $product->name         = $request->name;
        $product->sku          = $request->sku;
        $product->cost_price   = $request->cost_price;
        $product->retail_price = $request->retail_price;
        $product->year         = $request->year;
        $product->description  = $request->description;
        $product->status       = $request->status;

        #uplaod image
        if($request->hasFile('image'))
        {
            $image = $request->image;
            $name = Str::random(60). '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/product_images', $name);
            $product->image = $name;
        }

        #save product
        $product->save();

        #save product size vise stock
        if($request->items) {
            foreach (json_decode($request->items) as $item) {
                $stock_size = new ProductSizeStock;
                $stock_size->product_id = $product->id;
                $stock_size->size_id = $item->size_id;
                $stock_size->location = $item->location;
                $stock_size->quantity = $item->quantity;
                $stock_size->save();
            }
        }

        flash('Product created successfully!')->success();

        return response()->json([
            'success' => true,
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::with(['category','brand','product_stock.size'])
                            ->where('id', $id)
                            ->first();

        if(!$product) {
            abort(404);
        }

        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::with(['product_stock'])->where('id', $id)->first();
        return view('products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        #validate data
        $validate = Validator::make($request->all(), [
            'category_id'  => 'required|numeric',
            'brand_id'  => 'required|numeric',
            'name'  => 'required|string|min:2|max:200|unique:products,name,'.$id,
            'sku'  => 'required|string|max:100|unique:products,sku,'.$id ,
            'image'  => 'nullable|image|mimes:jpeg,jpg,png|max:1024',
            'cost_price'  => 'required|numeric',
            'retail_price'  => 'required|numeric',
            'year'  => 'required',
            'description'  => 'required|max:200',
            'status'  => 'required|numeric',
        ]);

        #Error response
        if($validate->fails()){
            return response()->json([
                'success' => false,
                'errors'  => $validate->errors()
            ], 422);
        }

        #update product
        $product = Product::find($id);
        $product->user_id      = Auth::id();
        $product->category_id  = $request->category_id;
        $product->brand_id     = $request->brand_id;
        $product->name         = $request->name;
        $product->sku          = $request->sku;
        $product->cost_price   = $request->cost_price;
        $product->retail_price = $request->retail_price;
        $product->year         = $request->year;
        $product->description  = $request->description;
        $product->status       = $request->status;

        #uplaod image
        if($request->hasFile('image'))
        {
            $image = $request->image;
            $name = Str::random(60). '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/product_images', $name);
            $product->image = $name;
        }

        #save product
        $product->save();

        #delete old stock
        ProductSizeStock::where('product_id', $id)->delete(); 

        #save product size vise stock
        if($request->items) {
            foreach (json_decode($request->items) as $item) {
                $stock_size = new ProductSizeStock;
                $stock_size->product_id = $product->id;
                $stock_size->size_id = $item->size_id;
                $stock_size->location = $item->location;
                $stock_size->quantity = $item->quantity;
                $stock_size->save();
            }
        }

        flash('Product updated successfully!')->success();

        return response()->json([
            'success' => true,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        if(!$product) {
            abort(404);
        }

        $product->delete();
        flash('Product deleted successfully!')->success();
        return back();
    }

    //HANDLE AJAX REQUEST
    public function getProductsJson() {
        $products = Product::with(['product_stock.size'])->get();

        return response()->json([
            'success' => true,
            'data'  => $products,
        ], 200);
    }
}
