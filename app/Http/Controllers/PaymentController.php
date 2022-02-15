<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Notifications\CustomerOrder;
use Facade\FlareClient\Http\Response;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\NewSale;


class PaymentController extends Controller
{
    public function verify(Request $request)
    {

        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://api.paystack.co/transaction/verify/".$request->reference,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer {config('services.paystack.secret')}",
            "Cache-Control: no-cache",
          ),
         ));
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        
        if ($err) {
          echo "cURL Error #:" . $err;
          return response()->json(['success'=>false,'err'=>$err],400);
        } else {
          $res=json_decode($response);

          $this->saveOrder($request);
          
          return response()->json(['success'=>true,'response', $res],200);
        }
    }

    private function saveOrder($request)
    {

      $user=auth()->user();
      $data=$request->all();

      Order::create([
            'transaction_id'=>$request->reference,
            'payment_status'=>'paid',
            'amount'=>$request->cart['price'],
            'user_id'=>auth()->id(),
            'product_id'=>$request->cart['id'],
            'phone'=>$request->phone,
        ]);

      

        $user=new User(['email' =>  $user->email]);
        $admin=new User(['email' =>config('mail.admin_address')]);

        $user->notify(new CustomerOrder());
        $admin->notify(new NewSale($data));

    }

    public function download(Request $request)
    {
        $file= storage_path() . "/app/novels/my_love_quest_modupeore_olayiwola.pdf";


        $headers = array(
                  'Content-Type: application/pdf',
                );
    
       
        return response()->download($file, 'my_love_quest_modupeore_olayiwola.pdf', $headers);
    
    }
}
