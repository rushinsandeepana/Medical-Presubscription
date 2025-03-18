<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quotation;
use App\Models\Drugs;
use App\Models\DrugDetails;
use App\Models\Prescription;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
// use Illuminate\Notifications\Notification;
use App\Notifications\SendEmailNotification;
use Illuminate\Support\Facades\Notification;

class QuotationController extends Controller
{
    public function sendQuotation(Request $request, $subscriptionId)
    {
        $user = Auth::user();
        $drugs = $request->input('drugs');
        $prescription = Prescription::find($subscriptionId);

        $totalAmount = array_sum(array_column($drugs, 'amount'));
        if ($totalAmount == 0) {
            return response()->json(['error' => 'No valid drugs available for quotation.']);
        }
        DB::beginTransaction();
        try {

            $quotation = Quotation::create([
                'user_id' => $user->id,
                'total_amount' => $totalAmount,
                'status' => 'Pending',
            ]);

            foreach ($drugs as $drug) {
                $drugDetails = DrugDetails::where('drug_name', $drug['name'])->first();
                $drugDetailsId = $drugDetails->id;
    
                Drugs::create([
                    'user_id' => $user->id,
                    'drug_details_id' => $drugDetailsId,
                    'quotation_id' => $quotation->id,
                    'prescription_id' => $prescription->id,
                    'quantity' => $drug['quantity'],
                    'amount' => $drug['amount'],
                ]);
            }
    
            DB::commit();
            return response()->json(['success' => 'Quotation sent successfully!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Something went wrong! ' . $e->getMessage()], 500);
        }
    }
    
    public function viewQuotation()
    {
        $user = Auth::user();

        $quotations = DB::table('quotations')
            ->leftJoin('drugs', 'quotations.id', '=', 'drugs.quotation_id')
            ->join('drug_details', 'drug_details.id', '=', 'drugs.drug_details_id')
            ->join('prescriptions', 'prescriptions.id', '=', 'drugs.prescription_id')
            ->select(
                'quotations.id as quotation_id',
                'quotations.*',
                'drugs.*',
                'drug_details.drug_name as drug_name', 'drug_details.price as price',
                'prescriptions.delivery_time as time_slot'
            )
            ->get()
            ->groupBy('quotation_id');
            // dd($quotations);
        return view('User.view_quotations', compact('quotations'));
    }

    public function sendNotification(){
        
        $user=User::all();

        $details = [

            'greeting' =>'Hi Rushin kolla',

            'body' =>'This is the email body',

            'actiontext' =>'i love you',
            
            'actionurl' =>'/',

            'lastline' =>'This is the last line',

        ];
        
        Notification::send($user, new SendEmailNotification($details));

        dd('done');
    }

}