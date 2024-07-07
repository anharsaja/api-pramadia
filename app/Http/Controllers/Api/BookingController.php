<?php

namespace App\Http\Controllers\Api;

use App\Models\Booking;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BookingController extends Controller
{
    // Get all bookings
    public function index()
    {
        $bookings = Booking::where('user_id', Auth::user()->id)->get();
        return response()->json($bookings);
    }

    // Create a new booking
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'layanan' => 'required|string|max:255',
            'no_medrek' => 'required|string|max:255',
            'tgl_booking' => 'required|date',
            'dokter' => 'required|string|max:255',
            'start_time' => 'required|date_format:Y-m-d H:i:s',
            'end_time' => 'required|date_format:Y-m-d H:i:s',
            'biaya_layanan' => 'required|string|max:255',
            'biaya_admin' => 'required|string|max:255',
        ]);

        \Midtrans\Config::$serverKey = 'SB-Mid-server-RFTZq0fJlqeTuL9mkkB4gc8M';
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = false;
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;
        
        $validatedData['user_id'] = Auth::user()->id;
        $booking = Booking::create($validatedData);

        $params = array(
            'transaction_details' => array(
                'order_id' => 'PRA-00'.$booking->id,
                'gross_amount' => intval($request->biaya_layanan) + intval($request->biaya_admin),
            ),
            'customer_details' => array(
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                
            ),
        );

        $snapToken = \Midtrans\Snap::getSnapToken($params);
        $booking['snapToken'] = $snapToken;
        return response()->json($booking, 201);
    }

    // Get a single booking
    public function show($id)
    {
        try {
            $booking = Booking::findOrFail($id);
            return response()->json($booking);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Booking not found'], 404);
        }
    }

    // Update a booking
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'layanan' => 'required|string|max:255',
            'no_medrek' => 'required|string|max:255',
            'tgl_booking' => 'required|date',
            'dokter' => 'required|string|max:255',
            'start_time' => 'required|date_format:Y-m-d H:i:s',
            'end_time' => 'required|date_format:Y-m-d H:i:s',
            'biaya_layanan' => 'required|string|max:255',
            'biaya_admin' => 'required|string|max:255',
        ]);

        try {
            $booking = Booking::findOrFail($id);
            $booking->update($validatedData);
            return response()->json($booking);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Booking not found'], 404);
        }
    }

    // Delete a booking
    public function destroy($id)
    {
        try {
            $booking = Booking::findOrFail($id);
            $booking->delete();
            return response()->json(['message' => 'delete success'], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'data not defined'], 404);
        }
    }
}


/*Install Midtrans PHP Library (https://github.com/Midtrans/midtrans-php)
composer require midtrans/midtrans-php
                              
Alternatively, if you are not using **Composer**, you can download midtrans-php library 
(https://github.com/Midtrans/midtrans-php/archive/master.zip), and then require 
the file manually.   

require_once dirname(__FILE__) . '/pathofproject/Midtrans.php'; */

//SAMPLE REQUEST START HERE

// Set your Merchant Server Key
// \Midtrans\Config::$serverKey = 'YOUR_SERVER_KEY';
// // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
// \Midtrans\Config::$isProduction = false;
// // Set sanitization on (default)
// \Midtrans\Config::$isSanitized = true;
// // Set 3DS transaction for credit card to true
// \Midtrans\Config::$is3ds = true;

// $params = array(
//     'transaction_details' => array(
//         'order_id' => rand(),
//         'gross_amount' => 10000,
//     ),
//     'customer_details' => array(
//         'first_name' => 'budi',
//         'last_name' => 'pratama',
//         'email' => 'budi.pra@example.com',
//         'phone' => '08111222333',
//     ),
// );

// $snapToken = \Midtrans\Snap::getSnapToken($params);