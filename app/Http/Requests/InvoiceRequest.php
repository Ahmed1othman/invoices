<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        return [
            'invoice_number'=>'required|max:200|unique:invoices,invoice_number,'.$this->id,
            'invoice_date'=>'required|date',
            'due_date'=>'required|date',
            'product_id'=>'required|exists:products,id',
            'section_id'=>'required|exists:sections,id',
            'discount'=>'required',
            'rate_vat'=>'required',
            'value_vat'=>'required',
            'total'=>'required',
            //'status'=>'required',
            //'value_status'=>'required',
            'amount_commission'=>'required',
            'amount_collection'=>'required',
            'note'=>'required',
            //'payment_date'=>'required',
            //'admin_id'=>'required|exists:admins,id',
        ];
    }
}
