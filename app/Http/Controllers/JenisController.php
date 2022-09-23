<?php

namespace App\Http\Controllers;

use App\Models\Jenis;
use Illuminate\Http\Request;

class JenisController extends Controller
{
    public function index()
    {
        $datas = Jenis::all();
        
        return view('setting.jenis.index', compact('datas'));
    }

    public function store(Request $request)
    {
       $validateData = $request->validate([
            'name' => 'required',
            'description' => 'required',
        ]);

        Jenis::create($validateData);
        return back()->with('success', 'Data berhasil ditambah');
    }

    public function edit(Jenis $jenis)
    {
        return view('setting.jenis.edit', compact('jenis'));
    }

    public function update(Request $request, Jenis $jenis)
    {
        $validateData = $request->validate([
            'name' => 'required',
            'description'  => 'required',
        ]);

        $jenis->update($validateData);
        return redirect()->route('jenis.index')->with('success', "Data berhasil diubah");
    }

    public function delete(Jenis $jenis)
    {
        $jenis->delete();
        return redirect()->route('jenis.index')->with('success', "Data $jenis->name berhasil dihapus");
    }
}
