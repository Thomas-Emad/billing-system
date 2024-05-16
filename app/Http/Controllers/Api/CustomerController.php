<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{

    public function index()
    {

        $customers = Customer::all();
        if ($customers) {

            return response()->json([
                'customers' => $customers
            ], 200);

        } else {

            return response()->json([
                'message' => "No Customer found"
            ], 401);

        }

    }

    public function store(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'name' => ['required', 'min:3', 'max:50', 'string'],
            'is_special' => ['boolean', 'nullable']
        ]);

        if ($validate->fails()) {

            return response()->json([
                'errors' => $validate->errors()
            ], 400);
            
        } else {

            Customer::create([
                'name' => $request->name,
                'is_special' => $request->is_special
            ]);

            return response()->json([
                'message' => 'Customer created successfully'
            ], 201);

        }

    }

    public function update(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'name' => ['required', 'min:3', 'max:50', 'string'],
            'is_special' => ['boolean', 'nullable']
        ]);

        if ($validate->fails()) {

            return response()->json([
                'errors' => $validate->errors()
            ], 400);
            
        } else {

            Customer::findOrFail($id)->update([
                'name' => $request->name,
                'is_special' => $request->is_special
            ]);

            return response()->json([
                'message' => 'Customer Updated successfully'
            ], 201);

        }
    }

    public function destroy($id)
    {
        Customer::findOrFail($id)->delete();
        return response()->json([
            'message' => 'Customer Deleted successfully'
        ], 202);
    }
}
