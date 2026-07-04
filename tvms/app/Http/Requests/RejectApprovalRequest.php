<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RejectApprovalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'catatan' => ['required', 'string', 'min:1', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'catatan.required' => 'Catatan penolakan wajib diisi.',
            'catatan.min'      => 'Catatan penolakan minimal 1 karakter.',
            'catatan.max'      => 'Catatan penolakan maksimal 500 karakter.',
        ];
    }
}
