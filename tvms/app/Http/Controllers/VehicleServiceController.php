<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVehicleServiceRequest;
use App\Http\Requests\UpdateVehicleServiceRequest;
use App\Models\Vehicle;
use App\Models\VehicleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VehicleServiceController extends Controller
{
    /**
     * Display a listing of service records for the given vehicle.
     */
    public function index(Vehicle $vehicle): View
    {
        $services = $vehicle->vehicleServices()
            ->orderBy('tanggal_service', 'desc')
            ->paginate(15);

        return view('vehicle-services.index', compact('vehicle', 'services'));
    }

    /**
     * Global summary of all service records across all vehicles (accessible from navbar).
     */
    public function allServices(Request $request): View
    {
        $query = VehicleService::with('vehicle')
            ->orderBy('tanggal_service', 'desc');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('deskripsi', 'like', "%{$search}%")
                  ->orWhereHas('vehicle', fn($v) => $v->where('plat_nomor', 'like', "%{$search}%"));
            });
        }

        if ($platNomor = $request->input('plat_nomor')) {
            $query->whereHas('vehicle', fn($v) => $v->where('plat_nomor', $platNomor));
        }

        $services  = $query->paginate(20)->withQueryString();
        $vehicles  = Vehicle::orderBy('plat_nomor')->pluck('plat_nomor', 'plat_nomor');

        return view('vehicle-services.all', compact('services', 'vehicles'));
    }

    /**
     * Show the form for creating a new service record.
     */
    public function create(Vehicle $vehicle): View
    {
        return view('vehicle-services.create', compact('vehicle'));
    }

    /**
     * Store a newly created service record in storage.
     */
    public function store(Vehicle $vehicle, StoreVehicleServiceRequest $request): RedirectResponse
    {
        $vehicle->vehicleServices()->create($request->validated());

        return redirect()
            ->route('vehicles.services.index', $vehicle)
            ->with('success', 'Data servis berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified service record.
     */
    public function edit(Vehicle $vehicle, VehicleService $service): View
    {
        abort_if($service->vehicle_id !== $vehicle->id, 404);

        return view('vehicle-services.edit', compact('vehicle', 'service'));
    }

    /**
     * Update the specified service record in storage.
     */
    public function update(Vehicle $vehicle, UpdateVehicleServiceRequest $request, VehicleService $service): RedirectResponse
    {
        abort_if($service->vehicle_id !== $vehicle->id, 404);

        $service->update($request->validated());

        return redirect()
            ->route('vehicles.services.index', $vehicle)
            ->with('success', 'Data servis berhasil diperbarui.');
    }

    /**
     * Remove the specified service record from storage.
     */
    public function destroy(Vehicle $vehicle, VehicleService $service): RedirectResponse
    {
        abort_if($service->vehicle_id !== $vehicle->id, 404);

        $service->delete();

        return redirect()
            ->route('vehicles.services.index', $vehicle)
            ->with('success', 'Data servis berhasil dihapus.');
    }
}
