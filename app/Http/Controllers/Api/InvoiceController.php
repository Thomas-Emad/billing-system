<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use App\Models\Indebtedness;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller
{
    public function index()
    {

        $invoices = Invoice::all();

        if ($invoices) {

            return response()->json([
                'invoices' => $invoices
            ], 200);
        } else {

            return response()->json([
                'Message' => 'Not Invoic Found'
            ], 400);
        }
    }

    public function CretaInvoice(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'status' => ['in:paid,unpaid,partly_paid'],
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
        ]);

        if ($validate->fails()) {

            return response()->json([
                'errors' => $validate->errors()
            ], 400);
        } else {

            Invoice::create([
                'status'      => $request->status,
                'customer_id' => $request->customer_id
            ]);

            return response()->json([
                'Message' => 'Created Suc'
            ], 200);
        }
    }

    public function AddProductInInvoice(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'invoice_id' => ['required', 'int', 'exists:invoices,id'],
            'product_id' => ['required', 'int', 'exists:products,id'],
            'quantity' => ['required', 'int'],
            'price' => ['required', 'int']
        ]);

        if ($validate->fails()) {

            return response()->json([
                'errors' => $validate->errors()
            ], 400);
        } else {

            // $product = Product::findOrFail($request->product_id);
            $invoice = Invoice::findOrFail($request->invoice_id);
            InvoiceProduct::create([
                'invoice_id' => $invoice->id,
                'product_id' => $request->product_id,
                'quantity'   => $request->quantity,
                'price'      => $request->price
            ]);

            Invoice::findOrFail($invoice->id)->update([
                'total_price' => $invoice->total_price + $request->price,
                // 'profits'     => $product->normal_sale - $product->buy_price
            ]);

            return response()->json([
                'Message' => 'Created Suc'
            ], 200);
        }
    }

    public function update(Request $request, $id)
    {

        $validate = Validator::make($request->all(), [
            'status' => ['in:paid,unpaid,partly_paid'],
            'paid_price' => ['int', 'required']
        ]);

        if ($validate->fails()) {

            return response()->json([
                'errors' => $validate->errors()
            ], 400);
        } else {

            $invoice = Invoice::findOrFail($id);

            $invoice->update([
                'status'     => $request->status,
                'paid_price' => $request->paid_price
            ]);

            if ($request->paid_price < $invoice->total_price) {

                Indebtedness::create([
                    'debtor'      => $invoice->total_price - $request->paid_price,
                    'customer_id' => $invoice->customer->id,
                    'invoice_id'  => $invoice->id
                ]);
            }

            return response()->json([
                'Message' => 'Updated Suc'
            ], 200);
        }
    }

    public function search(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'orderBy' => ['in:total_price_desc,total_price_asc,created_at_desc,created_at_asc', 'nullable'],
            'status' => ['in:paid,unpaid,partly_paid', 'nullable'],
            'from' => ['date', 'date_format:Y-m-d', 'nullable'],
            'to' => ['date', 'date_format:Y-m-d', 'nullable'],
            'page' => ['int', 'min:1', 'nullable'],
        ]);

        if ($validate->fails()) {
            return response()->json([
                'errors' => $validate->errors()
            ], 400);
        } else {
            // Order By Total Price or Created At [desc,asc]
            $order  = 'created_at';
            $orderby = 'desc';

            if ($request->orderBy == 'total_price_desc') :
                $order = 'total_price';
                $orderby = 'desc';
            elseif ($request->orderBy == 'total_price_asc') :
                $order = 'total_price';
                $orderby = 'asc';
            elseif ($request->orderBy == 'created_at_desc') :
                $order = 'created_at';
                $orderby = 'desc';
            elseif ($request->orderBy == 'created_at_asc') :
                $order = 'created_at';
                $orderby = 'asc';
            endif;

            // Get Research Invoices
            $invoices = Invoice::where(function ($q) use ($request) {
                if (isset($request->status)) {
                    $q->where('status', $request->status);
                }

                // Filter Data by Date
                if (isset($request->from) && isset($request->to)) {
                    $q->whereBetween('created_at', [$request->from, $request->to]);
                } elseif (isset($request->from)) {
                    $q->where('created_at', '>=', $request->from);
                } elseif (isset($request->to)) {
                    $q->where('created_at', '<=', $request->to);
                }
            })->orderBy($order, $orderby)->paginate(10);
            return response()->json([
                'invoices' => $invoices
            ], 200);
        }
    }
}
