<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Section;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(){

        $sections = Section::all();
        return view('pages.reports.customers_report',compact('sections'));

    }


    public function Search_customers(Request $request){


// في حالة البحث بدون التاريخ

        if ($request->Section && $request->product && $request->start_at =='' && $request->end_at=='') {


            $invoices = Invoice::where('section_id','=',$request->Section)->where('product_id','=',$request->product)->get();
            $sections = Section::all();
            return view('pages.reports.customers_report',compact('sections'))->withDetails($invoices);


        }


        // في حالة البحث بتاريخ

        else {

            $start_at = date($request->start_at);
            $end_at = date($request->end_at);

            $invoices = Invoice::whereBetween('invoice_date',[$start_at,$end_at])->where('section_id','=',$request->Section)->where('product_id','=',$request->product)->get();
            $sections = Section::all();
            return view('pages.reports.customers_report',compact('sections'))->withDetails($invoices);


        }



    }
}
