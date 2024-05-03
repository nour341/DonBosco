<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateBudgetRequest extends FormRequest
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

            //'BL' => 'required:budgets',
            //'item'=> 'required:budgets',
            //'Unit'=> 'required:budgets',
            //'UnitPrice'=> 'required:budgets',
            //'Quantity'=> 'required:budgets',
            'TotalPrice'=> 'required:budgets',
            'balance'=> 'required:budgets',
            //'start_date'=> 'required:budgets',
            //'end_date'=> 'required:budgets',
            'project_id'=> 'required:budgets',
        ];
    }
}

