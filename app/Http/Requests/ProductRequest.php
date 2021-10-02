<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_name' => 'required|max:255|unique:products,product_name,'.$this->id,
            'section_id'=>'required_without:without|exists:sections,id',
            'Description' => 'max:255'
        ];
    }


    public function messages()
    {
        return [
            'product_name.required'=>__('messages.The product name is required'),
            'product_name.unique'=>__('messages.This product name already exists'),
            'product_name.max'=>__('messages.The product name is too long'),
            'section_id.required'=>__('messages.The section  is required'),
            'section_id.in'=>__('messages.The section not correct'),
            'Description.max'=>__('messages.The Description is too long'),
        ];
    }
}
