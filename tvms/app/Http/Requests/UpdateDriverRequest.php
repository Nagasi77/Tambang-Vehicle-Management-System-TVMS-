<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDriverRequest extends FormRequest
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
        // Ignore the unique check for the current driver record being updated
        $driverId = $this->route('driver');

        return [
            'nama_driver' => [
                'required',
                'string',
                'max:100',
                Rule::unique('drivers', 'nama_driver')->ignore($driverId),
            ],
            'status' => ['required', 'in:tersedia,bertugas'],
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
            'status.required'      => 'Status wajib dipilih.',
            'status.in'            => 'Status tidak valid. Pilih tersedia atau bertugas.',
        ];
    }
}
