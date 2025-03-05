<?php

namespace App\Http\Controllers;
use App\Models\Prescription;
use App\Models\Drug;

use Illuminate\Http\Request;

class DrugController extends Controller
{
    public function index($subscriptionId)
    {
        $subscription = Prescription::find($subscriptionId);
        $drugs = Drug::all();
        if (!$subscription) {
            return redirect()->back()->with('error', 'Subscription not found.');
        }
    
        $images = json_decode($subscription->images, true);
        
        return view('Pharmacy.prepare_quotations', compact('subscription', 'images', 'drugs'));
    }
    
    
}