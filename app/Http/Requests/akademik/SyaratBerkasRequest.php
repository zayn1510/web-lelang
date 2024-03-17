<?php

namespace App\Http\Requests\akademik;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class SyaratBerkasRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            "name_berkas" => ["required", "string", "max:100"],
            "title_berkas" => ["required", "string", "max:100"],
            "tipe_berkas" => ["required", "string", "max:30"],
            "periode_kkn" => ["required", "integer"],
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(
            [
                "success" => false,
                "message" => "Validation Errors",
                "data" => $validator->errors(),
            ]
        ));
    }
}
