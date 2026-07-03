<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVehicleRequest extends FormRequest
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
            'plat_nomor'         => ['required', 'string', 'max:20', 'unique:vehicles,plat_nomor'],
            'jenis'              => ['required', 'in:angkutan_orang,angkutan_barang'],
            'status_kepemilikan' => ['required', 'in:milik_sendiri,sewa'],
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
            'plat_nomor.required'         => 'Plat nomor wajib diisi.',
            'plat_nomor.max'              => 'Plat nomor maksimal 20 karakter.',
            'plat_nomor.unique'           => 'Plat nomor sudah digunakan oleh kendaraan lain.',
            'jenis.required'              => 'Jenis kendaraan wajib dipilih.',
            'jenis.in'                    => 'Jenis kendaraan tidak valid.',
            'status_kepemilikan.required' => 'Status kepemilikan wajib dipilih.',
            'status_kepemilikan.in'       => 'Status kepemilikan tidak valid.',
        ];
    }
}
