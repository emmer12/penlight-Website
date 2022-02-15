<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
      if (auth()->user()->isAdmin) {
      $orders=Order::with(['product','user'])->get();      
      }else{
        $orders=Order::where('user_id',auth()->id())->with(['product'])->get();
      }


      return response()->json(['orders'=>$orders],200);
    }

}
