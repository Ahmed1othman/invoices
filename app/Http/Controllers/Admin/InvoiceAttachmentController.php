<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\InvoiceAttachmentRequest;
use App\Models\Invoice;
use App\Models\InvoiceAttachment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class InvoiceAttachmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\InvoiceAttachment  $invoiceAttachment
     * @return \Illuminate\Http\Response
     */
    public function show(InvoiceAttachment $invoiceAttachment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\InvoiceAttachment  $invoiceAttachment
     * @return \Illuminate\Http\Response
     */
    public function edit(InvoiceAttachment $invoiceAttachment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\InvoiceAttachment  $invoiceAttachment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InvoiceAttachment $invoiceAttachment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\InvoiceAttachment  $invoiceAttachment
     * @return \Illuminate\Http\Response
     */
    public function destroy(InvoiceAttachment $invoiceAttachment)
    {
        //
    }

    //attachmens
    public function viewAttachment($invoice_number,$file_name){

        $contents = Storage::disk('invoices_attachments')->path($invoice_number.'\\'.$file_name);
        return response()->file($contents);
    }

    public function downloadAttachment($invoice_number,$file_name){
        $contents = Storage::disk('invoices_attachments')->path($invoice_number.'/'.$file_name);
        return response()->download($contents);
    }

    public function deleteAttachment(Request $request){
        try{
            DB::beginTransaction();
            $attachment = InvoiceAttachment::find($request->id_file);
            if (!$attachment){
                return redirect()->back()->with('Error',__(('messages.attachment has not been exists')));
            }else{
                $attachment->delete();
                deleteFile('invoices_attachments',$request->invoice_number.'\\'.$request->file_name);
                DB::commit();
                return redirect()->back()->with('delete',__(('messages.attachment has been deleted successfully')));
            }
        }catch (\Exception $ex){
            DB::rollBack();
            return redirect()->back()->with('Error',__(('messages.An error occurred, please try again later')));
        }

    }

    public function addAttachment(InvoiceAttachmentRequest $request){
        try {
            DB::beginTransaction();
            if (!Invoice::find($request->invoice_id)){
                return redirect()->route('invoices.index')->with('Error',__(('messages.invoice has not been exists')));
            }
            else{

                if ($request->has('file_name')){
                    $file = $request->file('file_name');
                    $file_name = $file->getClientOriginalName();

                    $attachments = new InvoiceAttachment();
                    $attachments->file_name = $file_name ;
                    $attachments->invoice_id= $request->invoice_id;
                    $attachments->admin_id = Auth::guard('admin')->user()->id;
                    $attachmentState=$attachments->save();
                    if ($attachmentState){
                        uploadFile($file,public_path('Attachments/'.$request->invoice_number),$file_name);
                    }
                }
            }
            DB::commit();
            return redirect()->back()->with('Add',__(('messages.attachment has been Added successfully')));
        }
        catch (\Exception $ex){
            DB::rollBack();
            return $ex;
            return redirect()->back()->with('Error',__(('messages.An error occurred, please try again later')));
        }
    }
}
