<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDriverRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama_driver' => ['required', 'string', 'max:100', 'unique:drivers,nama_driver'],
        ];
    }

    /**
     * Get custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nama_driver.required' => 'Nama pengemudi wajib diisi.',
            'nama_driver.max'      => 'Nama pengemudi maksimal 100 karakter.',
            'nama_driver.unique'   => 'Nama pengemudi sudah terdaftar.',
        ];
    }
}
