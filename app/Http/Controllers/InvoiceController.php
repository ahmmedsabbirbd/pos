<?php

namespace App\Http\Controllers;

use App\Http\Resources\InvoiceDetails\CustomerResource;
use App\Http\Resources\InvoiceDetails\InvoiceProductResource;
use App\Http\Resources\InvoiceDetails\InvoiceResource;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    function InvoicePage():View{
        return view('pages.dashboard.invoice-page');
    }

    function SalePage():View{
        return view('pages.dashboard.sale-page');
    }

    function invoiceCreate(Request $request){

        DB::beginTransaction();

        try {

            $user_id=$request->header('id');
            $total=$request->input('total');
            $discount=$request->input('discount');
            $vat=$request->input('vat');
            $payable=$request->input('payable');

            $customer_id=$request->input('customer_id');

            $invoice= Invoice::create([
                'total'=>$total,
                'discount'=>$discount,
                'vat'=>$vat,
                'payable'=>$payable,
                'user_id'=>$user_id,
                'customer_id'=>$customer_id,
            ]);


            $invoiceID=$invoice->id;

            $products= $request->input('products');

            foreach ($products as $EachProduct) {
                InvoiceProduct::create([
                    'invoice_id' => $invoiceID,
                    'user_id'=>$user_id,
                    'product_id' => $EachProduct['product_id'],
                    'qty' =>  $EachProduct['qty'],
                    'sale_price'=>  $EachProduct['sale_price'],
                ]);
            }

            DB::commit();

            return 1;

        }
        catch (Exception $e) {
            DB::rollBack();
            return 0;
        }

    }

    function invoiceSelect(Request $request){
        $user_id=$request->header('id');
        return Invoice::where('user_id',$user_id)->with('customer')->get();
    }

    function InvoiceDetails(Request $request){

        $user_id=$request->header('id');

        $customerDetails=Customer::where('user_id',$user_id)->where('id',$request->input('cus_id'))->first();

        $invoiceTotal=Invoice::where('user_id',$user_id)->where('id',$request->input('inv_id'))->first();

        $invoiceProduct=InvoiceProduct::where('invoice_id',$request->input('inv_id'))
            ->where('user_id',$user_id)
            ->with('product')
            ->get();

        return array(
            'customer'=>$customerDetails,
            'invoice'=>$invoiceTotal,
            'product'=>$invoiceProduct,
        );

    }

    function InvoiceDetailsWithResource(Request $request){

        $user_id=$request->header('id');

        $customerDetails=Customer::where('user_id',$user_id)->where('id',$request->input('cus_id'))->first();

        $invoiceTotal=Invoice::where('user_id',$user_id)->where('id',$request->input('inv_id'))->first();

        $invoiceProducts=InvoiceProduct::where('invoice_id',$request->input('inv_id'))
            ->where('user_id',$user_id)
            ->get();

        // Transform the data using resource classes
        $customerResource = new CustomerResource($customerDetails);
        $invoiceResource = new InvoiceResource($invoiceTotal);
        $invoiceProductResources = InvoiceProductResource::collection($invoiceProducts);

        return response()->json([
            'customer' => $customerResource,
            'invoice' => $invoiceResource,
            'invoice_products' => $invoiceProductResources,
        ]);

    }

    function invoiceDelete(Request $request){
        DB::beginTransaction();
        try {
            $user_id=$request->header('id');
            InvoiceProduct::where('invoice_id',$request->input('inv_id'))
                ->where('user_id',$user_id)
                ->delete();
            Invoice::where('id',$request->input('inv_id'))->delete();
            DB::commit();
            return 1;
        }
        catch (Exception $e){
            DB::rollBack();
            return 0;
        }
    }
}
