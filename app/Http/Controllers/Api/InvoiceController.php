<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use App\Models\Indebtedness;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller
{
    public function index() {

        $invoices = Invoice::all();

        if($invoices) {

            return response()->json([
                'invoices' => $invoices
            ], 200);

        } else {

            return response()->json([
                'Message' => 'Not Invoic Found'
            ], 400);

        }

    }

    public function CretaInvoice(Request $request) {

        $validate = Validator::make($request->all(), [
            'status' => ['in:paid,unpaid,partly_paid'],
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
        ]);

        if ($validate->fails()) {

            return response()->json([
                'errors' => $validate->errors()
            ], 400);

        } else {

            Invoice::craete([
                'status' => $request->status,

                'customer_id' => $request->customer_id
            ]);

            return response()->json([
                'Message' => 'Created Suc'
            ], 200);

        }

    }

    public function AddProductInInvoice($invoice_id) {

        $validate = Validator::make($request->all(), [
            'product_id' => ['required', 'int', 'exists:products,id'],
            'quantity' => ['required', 'int']
        ]);

        if ($validate->fails()) {

            return response()->json([
                'errors' => $validate->errors()
            ], 400);

        } else {

            $product = Product::findOrFail($request->product_id);
            $invoice = Invoice::findOrFail($invoice_id);
            InvoiceProduct::create([
                'invoice_id' => $invoice->id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'price' => $product->price
            ]);

            Invoice::findOrFail($invoice->id)->update([
                'total_price' => $invoice->total_price + $product->price,
            ]);

        }

    }

    public function update(Request $request, $id) {

        $validate = Validator::make($request->all(), [
            'status' => ['in:paid,unpaid,partly_paid'],
            'paid_price' => ['int', 'required']
        ]);

        if ($validate->fails()) {

            return response()->json([
                'errors' => $validate->errors()
            ], 400);

        } else {

            $invoice = Invoice::findOrFail($id)->update([
                'status'     => $request->status,
                'paid_price' => $request->paid_price
            ]);

            if($request->paid_price < $invoice->total_price) {

                Indebtedness::create([
                    'debtor'      => $invoice->total_price - $request->paid_price,
                    'customer_id' => $invoice->customer->id,
                    'invoice_id'  => $invocie->id
                ]);
                
            }

            return response()->json([
                'Message' => 'Updated Suc'
            ], 200);

        }

    }

    public function destroy($id) {

        Invoice::findOrFail($id)->delete();
        return response()->json([
            'Message' => 'Deleted Suc'
        ], 200);

    }

}
