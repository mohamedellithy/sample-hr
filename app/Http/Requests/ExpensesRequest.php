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
            'service'        => 'required',
            'amount' => 'required|numeric',
            'expense_date'      => 'required|date',
            'attachment'        => 'mimes:jpeg,png,jpg,gif',

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
