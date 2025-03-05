<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prescription;
use App\Models\User;

class PrescriptionController extends Controller
{
    // public function uploadPrescription(){

    //     return view('User.upload_prescription');
    // }

    public function index(){
        
        $startTime = strtotime('8:00 AM'); // First delivery slot
        $endTime = strtotime('8:00 PM');   // Last delivery slot
        $interval = 2 * 60 * 60;           // 2 hours in seconds

        $timeSlots = [];
        
        for ($time = $startTime; $time < $endTime; $time += $interval) {
            $nextTime = $time + $interval;
            $timeSlots[] = date('gA', $time) . ' - ' . date('gA', $nextTime);
        }

        return view('user.upload_prescription', compact('timeSlots'));

    }

    public function store(Request $request)
    {dd($request);
        $request->validate([
            'prescription_images' => 'required|array|max:5',
            'prescription_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'note' => 'nullable|string',
            'delivery_address' => 'required|string',
            'delivery_time' => 'required|string',
        ]);
        $user = User::where('name', $request->user_name)->first();

        if (!$user) {
            return back()->with('error', 'User not found.');
        }
    
        $imagePaths = [];
    
        if ($request->hasFile('prescription_images')) {
            foreach ($request->file('prescription_images') as $file) {
                $path = $file->store('prescriptions', 'public');
                $imagePaths[] = $path;
            }
        }
    
         Prescription::create([
            'user_id' => $user->id,
            'images' => json_encode($imagePaths),
            'note' => $request->note,
            'delivery_address' => $request->delivery_address,
            'delivery_time' => $request->delivery_time,
        ]);
    
        return back()->with('success', 'Prescription uploaded successfully!');
    }

    public function view(){

        $prescriptions = Prescription::all();

        return view('Pharmacy.view_prescriptions', compact('prescriptions'));
    }
}