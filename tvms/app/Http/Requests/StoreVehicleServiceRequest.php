<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVehicleServiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tanggal_service' => ['required', 'date'],
            'deskripsi'       => ['required', 'string', 'max:1000'],
        ];
    }

    /**
     * Custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'tanggal_service.required' => 'Tanggal servis wajib diisi.',
            'tanggal_service.date'     => 'Tanggal servis harus berupa tanggal yang valid.',
            'deskripsi.required'       => 'Deskripsi servis wajib diisi.',
            'deskripsi.string'         => 'Deskripsi harus berupa teks.',
            'deskripsi.max'            => 'Deskripsi tidak boleh lebih dari 1000 karakter.',
        ];
    }
}
