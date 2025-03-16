<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quotation;
use App\Models\Drugs;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuotationController extends Controller
{
    public function sendQuotation(Request $request)
    {
        $user = Auth::user();
    
        // Check if there are any drugs that are NOT linked to a quotation
        $totalAmount = Drugs::where('user_id', $user->id)
                            // ->whereNull('quotation_id')
                            ->groupBy('prescription_id')
                            ->sum('amount');
    // dd($totalAmount);
        if ($totalAmount == 0) {
            return back()->with('error', 'No drugs available for quotation.');
        }
    
        $existingQuotation = Quotation::where('quotations.user_id', $user->id)
            ->where('quotations.status', 'Pending')
            ->leftJoin('drugs', 'drugs.quotation_id', '=', 'quotations.id')
            ->select('quotations.id', 'quotations.user_id', 'quotations.status', 'drugs.prescription_id')
            ->groupBy('quotations.id', 'quotations.user_id', 'quotations.status', 'drugs.prescription_id');
            // ->first();
    // dd($existingQuotation);
        if ($existingQuotation) {
            return back()->with('error', 'A quotation is already pending for your drugs.');
        }
    
        // Using transactions to prevent duplicate entries
        DB::beginTransaction();
        try {
            // Create a new quotation
            $quotation = Quotation::create([
                'user_id' => $user->id,
                'total_amount' => $totalAmount,
                'status' => 'Pending',
            ]);
    
            // Update all drugs without a quotation_id
            Drugs::where('user_id', $user->id)
                ->whereNull('quotation_id')
                ->update(['quotation_id' => $quotation->id]);
                
            DB::commit();
            return back()->with('success', 'Quotation sent successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong! ' . $e->getMessage());
        }
    }    
}