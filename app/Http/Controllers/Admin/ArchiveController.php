<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;

class ArchiveController extends Controller
{
    public function index(Request $request){
        try {
            $invoices = Invoice::onlyTrashed()->get();
            return view('pages.invoices.archive-invoices',compact('invoices'));

        }catch (\Exception $ex){
            return view('404');
        }
    }

    public function destroy(Request $request){
        try {
            $invoice = Invoice::withTrashed()->find($request->invoice_id);
            if (!$invoice){
                return redirect()-back()-with('error','this Invoices not exists');
            }
            return redirect()->back()->with('delete',__(('messages.Invoice deleted successfully')));
            $invoice->forceDelete();
        }catch (\Exception $ex){
            return $ex;
            return view('404');
        }
    }
    public function restore(Request $request){
        try {
            $invoice = Invoice::withTrashed()->find($request->invoice_id);
            if (!$invoice){
                return redirect()-back()-with('error','this Invoices not exists');
            }
            $invoice->restore();
            return redirect()->back()->with('delete',__(('messages.Invoice restored successfully')));

        }catch (\Exception $ex){
            return $ex;
            return view('404');
        }
    }
}
