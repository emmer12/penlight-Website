<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
    
        $products=Product::all();

      return response()->json(['products'=>$products],200);
    }

    public function show($slug)
    {
      $product=Product::where('slug',$slug)->with('user')->first();

      if ($product) {
        return response()->json(['product'=>$product],200);
      }else{
          return response()->json(['product'=>$product],404);
      }

    }

}
