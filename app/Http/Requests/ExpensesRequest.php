<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExpensesRequest extends FormRequest
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
            'section'     => 'required',
            'sub_service' => 'required',
            'bill_no'     => 'required',
            'supplier'    => 'required',
            'amount'      => 'required|numeric',
            'paid_amount' => 'required|numeric',
            'pending_amount' => 'required|numeric',
            'expense_description' => 'required',
            'expense_date' => 'required|date'
        ];
    }

    public function messages()
    {
        return [
            'required' => 'هذا الحقل مطلوب',
            'numeric' => 'يرجى ادخال رقم',
            'expense_date.date' => 'يجب ادخال تاريخ',
            'attachment.mimes' => 'jpeg, png, jpg, gif المرفق يجب ان يكون',

        ];
    }
}
