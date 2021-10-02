<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;

class ReportController extends Controller
{
    public function index(){

        return view('pages.reports.invoices_report');

    }

    public function Search_invoices(Request $request){

        $rdio = $request->rdio;

        // في حالة البحث بنوع الفاتورة
        if ($rdio == 1) {

            // في حالة عدم تحديد تاريخ
            if ($request->type && $request->start_at =='' && $request->end_at =='') {

                $invoices = Invoice::where('status',$request->type)->get();
                $type = $request->type;
                return view('pages.reports.invoices_report',compact('type'))->withDetails($invoices);
            }
            // في حالة تحديد تاريخ استحقاق
            else {

                $start_at = date($request->start_at);
                $end_at = date($request->end_at);
                $type = $request->type;

                $invoices = Invoice::whereBetween('invoice_date',[$start_at,$end_at])->where('status',$request->type)->get();
                return view('pages.reports.invoices_report',compact('type','start_at','end_at'))->withDetails($invoices);
            }
        }
        //====================================================================
// في البحث برقم الفاتورة
        else {

            $invoices = Invoice::where('invoice_number','=',$request->invoice_number)->get();
            return view('pages.reports.invoices_report')->withDetails($invoices);

        }
    }
}
