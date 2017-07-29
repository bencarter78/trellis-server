<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MilestoneRequest extends FormRequest
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
            'name' => 'required',
            'description' => 'required',
            'due_on' => 'required|date_format:d/m/Y'
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'Please enter the name of the milestone',
            'description.required' => 'Please describe the milestone',
            'due_on.required' => 'Please choose when you want to hit this milestone',
            'due_on.date' => 'Please enter a valid date',
        ];
    }
}
