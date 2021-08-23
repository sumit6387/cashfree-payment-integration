<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use LoveyCom\CashFree\PaymentGateway\Order;

class OrderController extends Controller
{
    public function order()
    {
        //instantiate the class
        $order = new Order();
//prepare the order details
        //NOTE: Prepare a route for returnUrl and notifyUrl (something like a webhook). However, if you have webhook setup in your cashfree dashboard, no need for notifyUrl. But if notifyUrl is set, it will be called instead.
        $orderid = rand(11111111, 99999999);
        $od["orderId"] = $orderid;
        $od["orderAmount"] = 100;
        $od["orderNote"] = "Subscription";
        $od["customerPhone"] = "9000012345";
        $od["customerName"] = "Test Name";
        $od["customerEmail"] = "test@cashfree.com";
        $od["returnUrl"] = "http://127.0.0.1:8000/order/success/";
        $od["notifyUrl"] = "http://127.0.0.1:8000/order/success";
//call the create method
        $order->create($od);
//get the payment link of this order for your customer
        $link = $order->getLink($od['orderId']);
        // dd($link);
        return Redirect::to('' . $link->paymentLink . '');
//You can now either send this link to your customer through email or redirect to it for them to complete the payment.
        //To confirm the payment,
        //Call either getDetails($orderId) or getStatus($orderId) method
    }

    public function success(Request $request)
    {
        $order = new Order();
        $status = ($order->getDetails($request->orderId));
        if ($status->details->orderStatus == "PAID") {
            return view('success');
        } else {
            return view('error');
        }
    }
}
