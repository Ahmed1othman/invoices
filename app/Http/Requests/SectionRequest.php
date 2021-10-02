<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SectionRequest extends FormRequest
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
            'section_name' => 'required|max:255|unique:sections,section_name,'.$this->id,
            'Description' => 'max:255'
        ];
    }

    public function messages()
    {
        return [
            'required'=>__('messages.The section name is required'),
            'section_name.unique'=>__('messages.This section name already exists'),
            'section_name.max'=>__('messages.The section name is too long'),
            'Description.max'=>__('messages.The Description is too long'),
        ];
    }
}
