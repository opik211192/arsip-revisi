<?php

namespace App\Http\Controllers;

use App\Models\JenisArsip;
use Illuminate\Http\Request;

class JenisArsipController extends Controller
{
    public function index()
    {
        $datas = JenisArsip::all();

        return view('setting.jenis_arsip.index', compact('datas'));
    }

    public function store(Request $request)
    {
        $validateData = $request->validate([
            'name' => 'required',
        ]);

        JenisArsip::Create($validateData);
        return back()->with('success', 'Data berhasil ditambah');
    }

    public function edit(JenisArsip $jenisArsip)
    {
        return view('setting.jenis_arsip.edit', compact('jenisArsip'));
    }

    public function update(Request $request, JenisArsip $jenisArsip)
    {
        $validateData = $request->validate([
            'name' => 'required',
        ]);

        $jenisArsip->update($validateData);
        return redirect()->route('jenis_arsip.index')->with('success', 'Data berhasil di ubah');
    }

    public function delete(JenisArsip $jenisArsip)
    {
        $jenisArsip->delete();
        return redirect()->route('jenis_arsip.index')->with('success', "Data $jenisArsip->name berhasil dihapus");
    }
}
