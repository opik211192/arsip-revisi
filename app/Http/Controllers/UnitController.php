<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::all();
        return view('setting.unit.index', compact('units'));
    }

    public function store(Request $request)
    {
        $validateData = $request->validate([
            'name' => 'required',
        ]);

        Unit::create($validateData);
        return back()->with('success', 'Data berhasil ditambah');
    }

    public function edit(Unit $unit)
    {
        return view('setting.unit.edit', compact('unit'));
    }

    public function update(Request $request, Unit $unit)
    {
        $validateData = $request->validate([
            'name' => 'required',
        ]);

        $unit->update($validateData);
        return redirect()->route('unit.index')->with('success', "Data berhasil diubah");
    }

    public function delete(Unit $unit)
    {
        $unit->delete();
        return redirect()->route('unit.index')->with('success', "Data $unit->name berhasil dihapus");
    }
}
