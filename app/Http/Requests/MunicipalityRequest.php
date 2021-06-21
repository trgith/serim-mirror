<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MunicipalityRequest extends FormRequest
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
            'state' => 'required',
            'municipality' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'state.required' => 'El estado es requerido',
            'municipality.required' => 'El municipio es requerido',
        ];
    }
}
