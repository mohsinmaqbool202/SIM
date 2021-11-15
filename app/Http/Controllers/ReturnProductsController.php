<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\ReturnProduct;
use App\Models\ProductSizeStock;

class ReturnProductsController extends Controller
{
    public function returnProducts() {
    	return view('return_products.return');
    }

    public function returnProductSubmit(Request $request) {
    	#validate data
        $validate = Validator::make($request->all(), [
            'product_id'  => 'required|numeric',
            'date'  => 'required|string',
            'items'  => 'required',
        ]);

        #Error response
        if($validate->fails()){
            return response()->json([
                'success' => false,
                'errors'  => $validate->errors()
            ], 422);
        }

        #store return product 
        foreach($request->items as $item)
        {
        	if($item['quantity'] && $item['quantity'] > 0) {
	        	$new_item = new ReturnProduct;
	        	$new_item->product_id = $request->product_id;
	        	$new_item->size_id    = $item['size_id'];
	        	$new_item->quantity   = $item['quantity'];
	        	$new_item->date 	  = $request->date;
	        	$new_item->save();

	        	#update product stock size 
	        	$psq = ProductSizeStock::where('product_id', $request->product_id)
	        							->where('size_id', $item['size_id'])
	        							->first();
	        	
	        	$psq->quantity += $item['quantity'];
	        	$psq->save();
	        }

        }

        flash('Return product updated successfully!')->success();

        return response()->json([
            'success' => true,
        ], 200);
    }

    public function history() {
    	$return_products = ReturnProduct::with(['product','size'])->orderBy('id', 'DESC')->get();
    	return view('return_products.history', compact('return_products'));
    }
}
