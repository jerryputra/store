<?php

namespace App\Http\Controllers;

use App\Http\Middleware\RedirectIfAuthenticated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Cart;
use App\Models\Transaction;
use App\Models\TransactionDetail; 

use Exception;

use Midtrans\Snap;
use Midtrans\Config;

use midtrans\Notification;
use PhpParser\Node\Stmt\Else_;

class CheckoutController extends Controller
{
    public function process(Request $request){
        //save users data
        $user = Auth::user();
        $user->update($request->except('total_price'));

        //process checkout
        $code = 'STORE-' . mt_rand(00000,99999);
        $carts = Cart::with(['product','user'])
                    ->where('user_id', Auth::user()->id)
                    ->get();
        
        //transaction create
        $transaction = Transaction::create([
            'user_id' => Auth::user()->id,
            'inscurance_price' => 0,
            'shipping_price' => 0,
            'total_price' => $request->total_price,
            'transaction_status' => 'PENDING',
            'code' => $code,
        ]);

        foreach ($carts as $cart) {
            $trx = 'TRX-' . mt_rand(00000,99999);

            TransactionDetail::create([
                'transaction_id' => $transaction->id,
                'products_id' => $cart->product->id,
                'price' => $cart->product->price,
                'shipping_status' =>'PENDING',
                'resi' => '',
                'code' => $trx,
            ]);
            
        }

        //delete cart data
        Cart::where('user_id', Auth::user()->id)->delete();


        //konfigurasi midtrans
        // Set your Merchant Server Key
        Config::$serverKey = config('services.midtrans.serverKey');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        Config::$isProduction = config('services.midtrans.isProduction');
        // Set sanitization on (default)
        Config::$isSanitized = config('services.midtrans.isSanitized');
        // Set 3DS transaction for credit card to true
        Config::$is3ds = config('services.midtrans.is3ds');

        //buat array untuk dikirim ke midtrans
        $midtrans = [
            'transaction_details' =>[
                'order_id' => $code,
                'gross_amount' => (int) $request->total_price,
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email
            ],
            'enabled_payments' => array('gopay', 'bank_transfer'),
            'vtweb' => [

            ]
        ];

        try {
            // Get Snap Payment Page URL
            $paymentUrl = Snap::createTransaction($midtrans)->redirect_url;
            
            // Redirect to Snap Payment Page
            return redirect($paymentUrl);
        }
        
        catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    
    public function callback(Request $request)
    {
        //set konfigurasi midtrans
        config::$serverKey = config('service.midtrans.serverKey');
        config::$isProduction = config('service.midtrans.isProduction');
        config::$isSanitized = config('service.midtrans.isSanitized');
        config::$is3ds = config('service.midtrans.is3ds');

        //instance midtrans notification
        $notification = new Notification();

        //assign ke variabel untuk memudahkan coding
        $status = $notification->transaction_status;
        $type = $notification->payment_type;
        $fraud = $notification->fraud_status;
        $order_id = $notification->order_id;
        
        //cari transaksi berdasarkan id
        $transaction = Transaction::findOrFail($order_id);

        //handle notification status
        if($status == 'capture') {
            if($type == 'credit_card') {
                if($fraud == 'challenge') {
                    $transaction->status = 'PENDING';
                }
                else {
                    $transaction->status = 'SUCCESS';
                }
            }
        }

        elseif($status == 'settlement') {
            $transaction->status = 'SUCCESS';
        }

        elseif($status == 'pending') {
            $transaction->status = 'PENDING';
        }

        elseif($status == 'deny') {
            $transaction->status = 'CANCELLED';
        }

        elseif($status == 'expire') {
            $transaction->status = 'CANCELLED';
        }

        elseif($status == 'cancel') {
            $transaction->status = 'CANCELLED';
        }

        //simpan transakasi
        $transaction->save();
    }
}
