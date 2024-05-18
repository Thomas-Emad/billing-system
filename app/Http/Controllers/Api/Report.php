<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Invoice;

class Report extends Controller
{
    public function products() {

        $count_products = Count(Product::all());
        if($count_products > 0) {

            return response()->json([
                'Count Products' => $count_products
            ], 200);

        } else {

            return response()->json([
                'message' => "No Product found"
            ], 200);

        }

    }

    public function Invoices() {

        $count_invoices = Count(Invoice::all());
        if($count_invoices > 0) {

            return response()->json([
                'Count Invoices' => $count_invoices
            ], 200);

        } else {

            return response()->json([
                'message' => "No Invoices found"
            ], 200);

        }

    }

    public function InvoicesUnPaid() {

        $count_invoices_Unpaid = Count(Invoice::where('status', 'unpaid')->get());
        if($count_invoices_Unpaid > 0) {

            return response()->json([
                'Count Invoices Unpaid' => $count_invoices_Unpaid
            ], 200);

        } else {

            return response()->json([
                'message' => "No Invoices Unpaid found"
            ], 200);

        }

    }
}
