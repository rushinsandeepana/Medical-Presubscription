<?php

namespace App\Http\Controllers;
use App\Models\Prescription;
use App\Models\DrugDetails;
use App\Models\Drugs;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DrugController extends Controller
{
    public function index($subscriptionId)
    {
        $subscription = Prescription::find($subscriptionId);
        $drugs_details = DrugDetails::all();
        $user = Auth::user();
        $drugs = DB::table('drugs')
            ->join('drug_details', 'drugs.drug_details_id', '=', 'drug_details.id')
            ->where('drugs.user_id', $user->id)
            ->where('drugs.user_id', $subscriptionId)
            ->select('drugs.*', 'drug_details.drug_name as drug_name', 'drug_details.price as price')
            ->get();
// dd($drugs);
            if (!$subscription) {
            return redirect()->back()->with('error', 'Subscription not found.');
        }
    
        $images = json_decode($subscription->images, true);
        
        return view('Pharmacy.prepare_quotations', compact('subscription', 'images', 'drugs_details', 'drugs'));
    }
    
    public function addDrugs(Request $request){
        $user = Auth::user();
        
        $drugDetail = DrugDetails::find($request->drug_name);
        if (!$drugDetail) {
            return back()->with('error', 'Drug detail not found!');
        }

        $amount = $request->quantity * $drugDetail->price;
        Drugs::create([
            'user_id' => $user->id,
            'drug_details_id' => $drugDetail->id,
            'quantity' => $request->quantity,
            'amount' => $amount,
        ]);
    
        return back()->with('success', 'Prescription uploaded successfully!');
    }
}