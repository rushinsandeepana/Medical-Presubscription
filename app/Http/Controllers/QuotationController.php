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
use Illuminate\Support\Facades\Log;

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

    public function sendNotification(Request $request, $subscriptionId)
    {
        $prescription = Prescription::find($subscriptionId);
        $user = User::find($prescription->user_id);
        $latestQuotation = DB::table('quotations')
            ->join('drugs', 'drugs.quotation_id', '=', 'quotations.id')
            ->where('drugs.prescription_id', $subscriptionId)
            ->orderBy('quotations.id', 'desc') // Get the most recent quotation
            ->select('quotations.id')
            ->first();

        $quotationId = $latestQuotation->id;

        $drugs = DB::table('drugs')
           ->join('prescriptions', 'prescriptions.id', '=', 'drugs.prescription_id')
           ->join('drug_details', 'drug_details.id', '=', 'drugs.drug_details_id')
           ->join('users', 'users.id', '=', 'prescriptions.user_id')
           ->join('quotations', 'quotations.id', '=', 'drugs.quotation_id')
           ->where('prescription_id', $subscriptionId)
           ->where('quotations.id', $quotationId)
           ->select('drug_details.drug_name as drug_name', 'drug_details.price as price',
                    'quotations.total_amount as total_amount',
                    'users.name as user_name',
                    'drugs.prescription_id as prescription_id', 'drugs.quantity as quantity', 'drugs.amount as amount',
                    'quotations.id as id')
            
           ->get();

        if (!$user) {
            return response()->json(['message' => 'User not found!'], 404);
        }

        $details = [
            'greeting' => 'Hi ' . $user->name,
            'body' => 'Here are your latest quotation details.',
            'actiontext' => 'Confirm Quotation',
            'actionurl' => url('/quotations/confirm/' . $quotationId),  // Confirm URL
            'rejectText' => 'Reject Quotation',
            'rejectUrl' => url('/quotations/reject/' . $quotationId),  // Reject URL
            'lastline' => 'Thank you for choosing our service.',
            'user' => $user,
            'drugs' => $drugs,
            'quotationId' => $quotationId
        ];

        Notification::send($user, new SendEmailNotification($details));

        return response()->json(['message' => 'Email sent successfully to ' . $user->email]);
    }

    public function ConfirmStatus($id){
        $data = Quotation::find($id);
        $data -> status = 'confirmed';
        $data->save();

        return redirect()->back()->with('message', 'Quotation is confirmed!');
    }

    public function RejectStatus($id){
        $data = Quotation::find($id);
        $data -> status = 'rejected';
        $data->save();

        return redirect()->back()->with('message', 'Quotation is rejected!');
    }

    public function QuotationStatus(Request $request) {
        $perPage = $request->input('per_page', 10); // Default to 10 rows per page
        $search = $request->input('search');
    
        $query = DB::table('quotations')
            ->join('drugs', 'drugs.quotation_id', '=', 'quotations.id')
            ->join('prescriptions', 'prescriptions.id', '=', 'drugs.prescription_id')
            ->join('users', 'users.id', '=', 'prescriptions.user_id')
            ->select(
                'users.name as username',
                'prescriptions.id as prescription_id',
                'quotations.id as quotation_id',
                'quotations.total_amount as total_amount',
                'quotations.status as status'
            );
    
        if (!empty($search)) {
            $query->where('users.name', 'LIKE', "%$search%")
                ->orWhere('prescriptions.id', 'LIKE', "%$search%")
                ->orWhere('quotations.id', 'LIKE', "%$search%")
                ->orWhere('quotations.total_amount', 'LIKE', "%$search%")
                ->orWhere('quotations.status', 'LIKE', "%$search%");
        }
    
        $quotationStatus = $query->paginate($perPage)->appends(['search' => $search, 'per_page' => $perPage]);
    
        return view('Pharmacy.view_quotation_status', compact('quotationStatus', 'perPage', 'search'));
    }     
   
}