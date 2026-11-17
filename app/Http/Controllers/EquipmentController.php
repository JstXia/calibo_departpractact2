<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EquipmentController extends Controller
{
    public function index(Request $request): View
    {
        $equipment = $request->user()->equipment()->latest()->paginate(10)->withQueryString();
        return view('equipment.index', compact('equipment'));
    }

    public function create(): View
    {
        return view('equipment.create', ['equipment' => new Equipment(), 'categories' => Equipment::categories(), 'statuses' => Equipment::statuses()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $request->user()->equipment()->create($data); // The owner is assigned from the authenticated user, never from form input.
        return redirect()->route('equipment.index')->with('success', 'Equipment added successfully.');
    }

    public function show(Request $request, Equipment $equipment): View
    {
        $this->authorizeEquipment($request, $equipment);
        return view('equipment.show', compact('equipment'));
    }

    public function edit(Request $request, Equipment $equipment): View
    {
        $this->authorizeEquipment($request, $equipment);
        return view('equipment.edit', ['equipment' => $equipment, 'categories' => Equipment::categories(), 'statuses' => Equipment::statuses()]);
    }

    public function update(Request $request, Equipment $equipment): RedirectResponse
    {
        $this->authorizeEquipment($request, $equipment);
        $equipment->update($this->validated($request));
        return redirect()->route('equipment.index')->with('success', 'Equipment updated successfully.');
    }

    public function destroy(Request $request, Equipment $equipment): RedirectResponse
    {
        $this->authorizeEquipment($request, $equipment);
        $equipment->delete();
        return redirect()->route('equipment.index')->with('success', 'Equipment deleted successfully.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'equipment_name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'in:'.implode(',', Equipment::categories())],
            'unit_price' => ['required', 'numeric', 'min:0'],
            'description' => ['required', 'string'],
            'availability_status' => ['required', 'in:'.implode(',', Equipment::statuses())],
        ]);
    }

    private function authorizeEquipment(Request $request, Equipment $equipment): void
    {
        abort_unless($equipment->user_id === $request->user()->id, 403);
    }
}
