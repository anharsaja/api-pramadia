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
        $bookings = Booking::all();
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

        $validatedData['user_id'] = Auth::user()->id;
        $booking = Booking::create($validatedData);
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
        $booking = Booking::findOrFail($id);
        $booking->delete();
        return response()->json(null, 204);
    }
}
