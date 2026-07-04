<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDriverRequest;
use App\Http\Requests\UpdateDriverRequest;
use App\Models\Driver;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DriverController extends Controller
{
    /**
     * Display a paginated list of all drivers with optional search and status filter.
     */
    public function index(Request $request): View
    {
        $query = Driver::query();

        if ($search = $request->input('search')) {
            $query->where('nama_driver', 'like', "%{$search}%");
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $drivers = $query->orderBy('nama_driver')->paginate(15)->withQueryString();

        return view('drivers.index', compact('drivers'));
    }

    /**
     * Show the form to create a new driver.
     */
    public function create(): View
    {
        return view('drivers.create');
    }

    /**
     * Store a newly created driver with default status 'tersedia'.
     */
    public function store(StoreDriverRequest $request): RedirectResponse
    {
        Driver::create([
            'nama_driver' => $request->validated()['nama_driver'],
            'status'      => 'tersedia',
        ]);

        return redirect()
            ->route('drivers.index')
            ->with('success', 'Pengemudi berhasil ditambahkan.');
    }

    /**
     * Show the form to edit an existing driver.
     */
    public function edit(Driver $driver): View
    {
        return view('drivers.edit', compact('driver'));
    }

    /**
     * Update an existing driver record.
     */
    public function update(UpdateDriverRequest $request, Driver $driver): RedirectResponse
    {
        $driver->update($request->validated());

        return redirect()
            ->route('drivers.index')
            ->with('success', 'Data pengemudi berhasil diperbarui.');
    }

    /**
     * Delete a driver.
     * Drivers with status 'bertugas' cannot be deleted.
     */
    public function destroy(Driver $driver): RedirectResponse
    {
        if ($driver->status === 'bertugas') {
            return redirect()
                ->back()
                ->with('error', 'Pengemudi sedang bertugas dan tidak dapat dihapus.');
        }

        $driver->delete();

        return redirect()
            ->route('drivers.index')
            ->with('success', 'Pengemudi berhasil dihapus.');
    }
}
