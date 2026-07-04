<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class ExportReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tanggal_mulai'   => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
        ];
    }

    /**
     * Add after-validation check for max 366-day range.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $from = \Carbon\Carbon::parse($this->tanggal_mulai);
            $to   = \Carbon\Carbon::parse($this->tanggal_selesai);

            if ($from->diffInDays($to) > 366) {
                $validator->errors()->add(
                    'tanggal_selesai',
                    'Rentang tanggal maksimal adalah 366 hari.'
                );
            }
        });
    }

    public function messages(): array
    {
        return [
            'tanggal_mulai.required'         => 'Tanggal mulai wajib diisi.',
            'tanggal_mulai.date'             => 'Tanggal mulai harus berupa tanggal yang valid.',
            'tanggal_selesai.required'       => 'Tanggal selesai wajib diisi.',
            'tanggal_selesai.date'           => 'Tanggal selesai harus berupa tanggal yang valid.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus sama dengan atau setelah tanggal mulai.',
        ];
    }
}
