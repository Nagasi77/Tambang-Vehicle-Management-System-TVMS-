<?php

namespace App\Http\Requests;

use App\Services\BookingService;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'vehicle_id'           => ['required', 'exists:vehicles,id'],
            'driver_id'            => ['required', 'exists:drivers,id'],
            'approver_level_1_id'  => ['required', 'exists:users,id'],
            'approver_level_2_id'  => ['required', 'exists:users,id', 'different:approver_level_1_id'],
            'tanggal_mulai'        => ['required', 'date'],
            'tanggal_selesai'      => ['required', 'date', 'after:tanggal_mulai'],
            'keperluan'            => ['required', 'string', 'max:255'],
            'konsumsi_bbm'         => ['nullable', 'numeric', 'min:0'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            // Skip conflict checks if basic validation already failed
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $service   = app(BookingService::class);
            $start     = Carbon::parse($this->tanggal_mulai);
            $end       = Carbon::parse($this->tanggal_selesai);

            // Resolve the current booking ID to exclude it from conflict checks.
            // Supports both route model binding (Booking object) and raw ID parameter.
            $routeParam = $this->route('booking');
            $bookingId  = is_object($routeParam) ? (int) $routeParam->id : (int) $routeParam;

            if ($conflict = $service->checkVehicleConflict((int) $this->vehicle_id, $start, $end, $bookingId)) {
                $validator->errors()->add(
                    'vehicle_id',
                    'Kendaraan sudah dibooking pada rentang ' .
                    $conflict->tanggal_mulai->format('d/m/Y') . ' – ' .
                    $conflict->tanggal_selesai->format('d/m/Y') . '.'
                );
            }

            if ($conflict = $service->checkDriverConflict((int) $this->driver_id, $start, $end, $bookingId)) {
                $validator->errors()->add(
                    'driver_id',
                    'Pengemudi sudah ditugaskan pada rentang ' .
                    $conflict->tanggal_mulai->format('d/m/Y') . ' – ' .
                    $conflict->tanggal_selesai->format('d/m/Y') . '.'
                );
            }
        });
    }

    public function messages(): array
    {
        return [
            'vehicle_id.required'           => 'Kendaraan wajib dipilih.',
            'vehicle_id.exists'             => 'Kendaraan tidak ditemukan.',
            'driver_id.required'            => 'Pengemudi wajib dipilih.',
            'driver_id.exists'              => 'Pengemudi tidak ditemukan.',
            'approver_level_1_id.required'  => 'Approver Level 1 wajib dipilih.',
            'approver_level_1_id.exists'    => 'Approver Level 1 tidak ditemukan.',
            'approver_level_2_id.required'  => 'Approver Level 2 wajib dipilih.',
            'approver_level_2_id.exists'    => 'Approver Level 2 tidak ditemukan.',
            'approver_level_2_id.different' => 'Approver Level 1 dan Level 2 tidak boleh orang yang sama.',
            'tanggal_mulai.required'        => 'Tanggal mulai wajib diisi.',
            'tanggal_mulai.date'            => 'Tanggal mulai harus berupa tanggal yang valid.',
            'tanggal_selesai.required'      => 'Tanggal selesai wajib diisi.',
            'tanggal_selesai.date'          => 'Tanggal selesai harus berupa tanggal yang valid.',
            'tanggal_selesai.after'         => 'Tanggal selesai harus setelah tanggal mulai.',
            'keperluan.required'            => 'Keperluan wajib diisi.',
            'keperluan.string'              => 'Keperluan harus berupa teks.',
            'keperluan.max'                 => 'Keperluan maksimal 255 karakter.',
            'konsumsi_bbm.numeric'          => 'Konsumsi BBM harus berupa angka.',
            'konsumsi_bbm.min'              => 'Konsumsi BBM tidak boleh negatif.',
        ];
    }
}
