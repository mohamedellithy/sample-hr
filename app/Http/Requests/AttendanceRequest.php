<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceRequest extends FormRequest
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
            'employee_id'        => 'required|numeric',
            'attendance_date'    => 'required|date|unique:employee_attendances,attendance_date',
            'clock_in' => 'required',
            'clock_out' => 'required',

        ];
    }

    public function messages()
    {
        return [
            'unique'=>'هذا التاريخ موجود من قبل',
            'required' => 'هذا الحقل مطلوب',
            'numeric' => 'يرجى ادخال رقم',
            'date' => 'يجب ادخال تاريخ',
            'date_format' => 'يجب ادخال وقت',

        ];
    }


}
