<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name'        => 'required|unique:employees,name',
            'nationality' => 'required',
            'salary'      => 'required|numeric',
            'passport_no' => 'required',
            'birthday'    => 'required|date',
            'passport_expiry' => 'required|date',
            'join_date' => 'required|date',
        ];


    }

    public function messages()
    {
        return [
            'required' => 'هذا الحقل مطلوب',
            'unique'=>'هذا الاسم موجود سابقا',
            'numeric' => 'يرجى ادخال رقم',
            'date' => 'يجب ادخال تاريخ',

        ];
    }
}
