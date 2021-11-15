<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\ProductStock;
use App\Models\ProductSizeStock;

class StocksController extends Controller
{
    public function stock() {
    	return view('stocks.stock');
    }

    public function stockSubmit(Request $request) {
    	#validate data
        $validate = Validator::make($request->all(), [
            'product_id'  => 'required|numeric',
            'date'  => 'required|string',
            'stock_type'  => 'required|string',
            'items'  => 'required',
        ]);

        #Error response
        if($validate->fails()){
            return response()->json([
                'success' => false,
                'errors'  => $validate->errors()
            ], 422);
        }

        #store product stock
        foreach($request->items as $item)
        {
        	if($item['quantity'] && $item['quantity'] > 0) {
	        	$new_item = new ProductStock;
	        	$new_item->product_id = $request->product_id;
	        	$new_item->size_id    = $item['size_id'];
	        	$new_item->quantity   = $item['quantity'];
	        	$new_item->date 	  = $request->date;
	        	$new_item->status 	  = $request->stock_type;
	        	$new_item->save();

	        	#product stock size update
	        	$psq = ProductSizeStock::where('product_id', $request->product_id)
	        							->where('size_id', $item['size_id'])
	        							->first();
	        	
	        	if($request->stock_type == ProductStock::STOCK_IN) {
	        		#stock in
	        		$psq->quantity += $item['quantity'];
	        	}else {
	        		#stock out
	        		$psq->quantity -= $item['quantity'];
	        	}

	        	$psq->save();
	        }

        }

        flash('Stock  updated successfully!')->success();

        return response()->json([
            'success' => true,
        ], 200);
    }

    public function history() {
    	$stocks = ProductStock::with(['product','size'])->orderBy('id', 'DESC')->get();
    	return view('stocks.history', compact('stocks'));
    }
}
