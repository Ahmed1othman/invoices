<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\InvoiceAttachmentRequest;
use App\Http\Requests\InvoiceRequest;
use App\Models\Invoice;
use App\Models\InvoiceAttachment;
use App\Models\InvoiceLog;
use App\Models\Product;
use App\Models\Section;
use App\Notifications\AddInvoiceNotification;
use App\Notifications\AddInvoiceNotification2;
use App\Providers\AuthServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\In;
use SebastianBergmann\Environment\Console;

class InvoiceController extends Controller
{

    public function index()
    {
        $invoices = Invoice::with('product','section','createdBy','attachments')->get();
        return view('pages.invoices.index',compact('invoices'));
    }


    public function create()
    {
        $sections = Section::all();
        return view('pages.invoices.create',compact('sections'));
    }


        public function store(InvoiceRequest $request)
    {
        try {

            $request->request->add([
                'status'=>'unpaid',
                'value_status'=>'0',
                'payment_date'=>$request->due_date
            ]);

            DB::beginTransaction();
            $invoice = $request->only([
                'invoice_number',
                'invoice_date',
                'due_date',
                'product_id',
                'section_id',
                'discount',
                'rate_vat',
                'value_vat',
                'total',
                'status',
                'value_status',
                'note',
                'amount_commission',
                'amount_collection',
                'admin_id',
                'payment_date',
            ]);


            $invoiceState = Invoice::create($invoice);

            $request->request->add([
                'invoice_id'=>$invoiceState->id,
            ]);
            $logState = InvoiceLog::create($request->only([
                'invoice_id',
                'product_id',
                'section_id',
                'status',
                'value_status',
                'admin_id',
                'note',
            ]));

            if ($request->has('pic')){
                $file = $request->file('pic');
                $file_name = $file->getClientOriginalName();

                $attachments = new InvoiceAttachment();
                $attachments->file_name = $file_name ;
                $attachments->invoice_id= $invoiceState->id;
                $attachments->admin_id = $request->admin_id;
                $attachmentState=$attachments->save();
                if ($attachmentState){
                    uploadFile($file,public_path('Attachments/'.$request->invoice_number),$file_name);
                }

            }

            if($invoiceState && $logState ){
                $admins = Auth::guard('admin')->user();
                Notification::send($admins, new AddInvoiceNotification($invoiceState->id));
                Notification::send($admins, new AddInvoiceNotification2($invoiceState));
                DB::commit();
                return redirect()->route('invoices.create')->with('Add',__(('messages.invoice has been added successfully')));
            }

            return redirect()->route('products.index')->with('Error',__(('messages.invoice has not been added')));
        }catch (\Exception $ex){
            DB::rollBack();
            return $ex;
            return redirect()->route('invoices.create')->with('Error',__(('messages.An error occurred, please try again later')));
        }
    }


    public function show(Invoice $invoice)
    {
        //
    }

    public function edit($id)
    {
        try {
            $invoices = Invoice::with('product')->find($id);
            if (!$invoices){
                return view('404');
            }
            $sections = Section::all();
            return view('pages.invoices.edit',compact('sections','invoices'));
        }catch (\Exception $ex){
            return redirect()->route('invoices.index')->with('Error',__(('messages.An error occurred, please try again later')));
        }
    }


    public function update(InvoiceRequest $request,$id)
    {


        try {
            $invoices = Invoice::with('product')->find($id);
            if (!$invoices){
                return view('404');
            }
            DB::beginTransaction();
            $oldName = $invoices->invoice_number;
            $invoices->update($request->only([
                'invoice_number',
                'invoice_date',
                'due_date',
                'product_id',
                'section_id',
                'discount',
                'rate_vat',
                'value_vat',
                'total',
                'note',
                'amount_commission',
                'amount_collection',
                'admin_id',
            ]));
            if ($request->invoice_number != $oldName){
                rename(public_path('Attachments/'.$oldName), public_path('Attachments/'.$request->invoice_number));
            }
            DB::commit();
            return redirect()->back()->with('edit',__(('messages.Invoice Updated successfully')));
        }catch (\Exception $ex){
            DB::rollBack();
            return redirect()->back()->with('Error',__(('messages.An error occurred, please try again later')));
        }
    }

    public function forceDeleted(Request $request)
    {
        try {
            $invoices = Invoice::find($request->invoice_id);
            if (!$invoices){
                return view('404');
            }
            DB::beginTransaction();
            $tate = $invoices->forceDelete();
            DB::commit();
            return redirect()->back()->with('delete',__(('messages.Invoice deleted successfully')));
        }catch (\Exception $ex){
            return $ex;
            DB::rollBack();
            return redirect()->back()->with('Error',__(('messages.An error occurred, please try again later')));
        }
    }

    public function softDelete(Request $request){
        try {
            $invoices = Invoice::find($request->invoice_id);
            if (!$invoices){
                return view('404');
            }
            DB::beginTransaction();
            $invoices->delete();
            DB::commit();
            return redirect()->back()->with('edit',__(('messages.Invoice archived successfully')));
        }catch (\Exception $ex){
            DB::rollBack();
            return redirect()->back()->with('Error',__(('messages.An error occurred, please try again later')));
        }
    }


    public function getproducts($id)
    {
        return json_encode(Product::where('section_id',$id)->pluck('product_name','id'));
    }

    public function getDetails($id)
    {
        $invoice = Invoice::with('product','section','createdBy','attachments','logs')->find($id);
        if ($invoice){
            return view('pages.invoices.details', compact('invoice'));
        }
        return view('404');
    }

    public function editStatus($id){
        try {
            $invoice = Invoice::with('product','section','createdBy')->find($id);
            if (!$invoice)
                return view('404');
            return view('pages.invoices.update-status',compact('invoice'));
        }catch (\Exception $ex){
            return redirect()->back()->with('Error',__(('messages.An error occurred, please try again later')));
        }

    }
    public function updateStatus(Request $request , $id){
        try {
            $invoice =Invoice::find($id);
            if (!$invoice)
                return view('404');
            if ($request->value_status==1)
            {
                $request->request->add([
                    'status'=>'paid',
                ]);
            }else if ($request->value_status==3){
                $request->request->add([
                    'status'=>'partially paid',
                ]);
            }
            DB::beginTransaction();
            $invoice->update([
                'status'=>$request->status,
                'value_status'=>$request->value_status,
                'payment_date'=>$request->payment_date,
            ]);

            $invoiceLog = InvoiceLog::create([
                    'invoice_id'=>$request->invoice_id,
                    'product_id'=>$request->product_id,
                    'section_id'=>$request->section_id,
                    'status'=>$request->status,
                    'value_status'=>$request->value_status,
                    'admin_id'=>Auth::guard('admin')->user()->id,
                    'note'=>$request->note,


                ]);
            DB::commit();
            return redirect()->back()->with('edit',__(('messages.Invoice status changed successfully')));
        }catch (\Exception $ex){
            return redirect()->back()->with('Error',__(('messages.An error occurred, please try again later')));
        }
    }

    public function paidInvoices(){
        try {
            $invoices = Invoice::with('product','section','createdBy','attachments')->where('value_status','1')->get();
            return view('pages.invoices.paid-invoices',compact('invoices'));
        }catch (\Exception $ex){
            return view('404');
        }

    }
    public function unpaidInvoices(){
        try {
            $invoices = Invoice::with('product','section','createdBy','attachments')->where('value_status','0')->get();
            return view('pages.invoices.unpaid-invoices',compact('invoices'));
        }catch (\Exception $ex){
            return view('404');
        }
    }
    public function partialInvoices(){
        try {
            $invoices = Invoice::with('product','section','createdBy','attachments')->where('value_status','3')->get();
            return view('pages.invoices.partial-invoices',compact('invoices'));
        }catch (\Exception $ex){
            return view('404');
        }
    }


    public function printInvoices($id){
        try {
            $invoices = Invoice::with('product','section','createdBy','attachments')->find($id);
            return view('pages.invoices.print-invoice',compact('invoices'));
        }catch (\Exception $ex){
            return view('404');
        }
    }

    public function markAllAsRead(){
        $unreaded = auth()->guard('admin')->user()->unreadNotifications;
        if ($unreaded){
            $unreaded->markAsRead();
            return back();
        }
    }

}


