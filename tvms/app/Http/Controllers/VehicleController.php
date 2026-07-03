<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVehicleRequest;
use App\Http\Requests\UpdateVehicleRequest;
use App\Models\Vehicle;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class VehicleController extends Controller
{
    /**
     * Display a paginated list of vehicles.
     */
    public function index(): View
    {
        $vehicles = Vehicle::latest()->paginate(15);

        return view('vehicles.index', compact('vehicles'));
    }

    /**
     * Show the form for creating a new vehicle.
     */
    public function create(): View
    {
        return view('vehicles.create');
    }

    /**
     * Store a newly created vehicle in storage.
     */
    public function store(StoreVehicleRequest $request): RedirectResponse
    {
        Vehicle::create($request->validated());

        return redirect()
            ->route('vehicles.index')
            ->with('success', 'Data kendaraan berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified vehicle.
     */
    public function edit(string $id): View
    {
        $vehicle = Vehicle::findOrFail($id);

        return view('vehicles.edit', compact('vehicle'));
    }

    /**
     * Update the specified vehicle in storage.
     */
    public function update(UpdateVehicleRequest $request, string $id): RedirectResponse
    {
        $vehicle = Vehicle::findOrFail($id);
        $vehicle->update($request->validated());

        return redirect()
            ->route('vehicles.index')
            ->with('success', 'Data kendaraan berhasil diperbarui.');
    }

    /**
     * Remove the specified vehicle from storage.
     * Confirmation dialog is handled in the view via JavaScript confirm().
     */
    public function destroy(string $id): RedirectResponse
    {
        $vehicle = Vehicle::findOrFail($id);
        $vehicle->delete();

        return redirect()
            ->route('vehicles.index')
            ->with('success', 'Data kendaraan berhasil dihapus.');
    }
}
