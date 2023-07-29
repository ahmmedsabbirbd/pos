<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    function CustomerPage(){
        return view('pages.dashboard.customer-page');
    }

    public function updateDeviceToken(Request $request)
    {
        $id = $request->header('id');
        return  User::where('id','=', $id)
            ->update([
                'divice_token' => $request->header('deviceToken')
            ]);
    }

    public function sendNotification(Request $request)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';

        $FcmToken = User::whereNotIn('id', [$request->header('id')])
            ->whereNotNull('divice_token')->pluck('divice_token')->all();


        $serverKey = 'AAAAT5t-azE:APA91bFjVojgl47An7soCFOuXWMYg3ZMxjXnwOCqJW6mKPZyyW4EgBRhsNTMe6sqg0aZNLnDoBKDYTxEbpezRPNPdWiD6RHdERxGMiBkr16jzpd4f65da7EVOLiPRCZNKqYqEk83piic';   // ADD SERVER KEY HERE PROVIDED BY FCM

        $data = [
            "registration_ids" => $FcmToken,
            "notification" => [
                "title" => $request->title,
                "body" => $request->body,
                "icon" => "https://sabbir-me.netlify.app/static/media/logo.e4a2208b3fcf2539fb7f9ebffdfa3149.svg",
                "image" => "https://sabbir-me.netlify.app/static/media/logo.e4a2208b3fcf2539fb7f9ebffdfa3149.svg",
                "data" => [ // Additional data that can be used on the client-side when the notification is received.
                    "url" => "https://sabbir-me.netlify.app/", // Replace with the URL you want to open when the user clicks the notification.
                ]
            ]
        ];
        $encodedData = json_encode($data);

        $headers = [
            'Authorization:key=' . $serverKey,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        // Close connection
        curl_close($ch);
        // FCM response
    }

    function CustomerCreate(Request $request){
        $user_id=$request->header('id');

        return Customer::create([
            'name'=>$request->input('name'),
            'email'=>$request->input('email'),
            'mobile'=>$request->input('mobile'),
            'user_id'=>$user_id
        ]);
    }


    function CustomerList(Request $request){
        $user_id=$request->header('id');
        return Customer::where('user_id',$user_id)->get();
    }

    function CustomerByID(Request $request){
        $customer_id=$request->input('id');
        $user_id=$request->header('id');
        return Customer::where('id',$customer_id)->where('user_id',$user_id)->first();
    }

    function CustomerDelete(Request $request){
        $customer_id=$request->input('id');
        $user_id=$request->header('id');
        return Customer::where('id',$customer_id)->where('user_id',$user_id)->delete();
    }


    function CustomerUpdate(Request $request){
        $customer_id=$request->input('id');
        $user_id=$request->header('id');
        return Customer::where('id',$customer_id)->where('user_id',$user_id)->update([
            'name'=>$request->input('name'),
            'email'=>$request->input('email'),
            'mobile'=>$request->input('mobile'),
        ]);
    }
}
