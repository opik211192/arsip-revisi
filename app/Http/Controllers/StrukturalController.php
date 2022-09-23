<?php

namespace App\Http\Controllers;

use App\Models\Struktural;
use App\Models\Struktural_detail;
use Illuminate\Http\Request;

class StrukturalController extends Controller
{
   public function index(){
        //$strukturals = Struktural::all();

        //$strukturals = Struktural::with('struktural_detail')->get();
        $details = Struktural_detail::with('struktural')->get();

        return view('setting.struktural.index', compact('details'));
   }

   //ini untuk strukturalnya
   public function create_struktural()
   {
          $strukturals = Struktural::all();
          return view('setting.struktural.create_struktural', compact('strukturals'));  
   }

   public function store_struktural(Request $request)
   {
      
          $validateData = $request->validate([
               'name' => 'required',
          ]);

          Struktural::create($validateData);
          return back()->with('success', 'Data berhasil ditambah');
   }

   public function edit_struktural(Struktural $struktural)
   {
          return view('setting.struktural.edit_struktural', compact('struktural'));
   }

   public function update_struktural(Request $request, Struktural $struktural)
   {
        $validateData = $request->validate([
            'name' => 'required',
        ]);

        $struktural->update($validateData);
        return redirect()->route('struktural_create.create')->with('success', "Data berhasil diubah");
   }

   public function delete_struktural(Struktural $struktural)
    {
        $struktural->delete();
        return redirect()->route('struktural_create.create')->with('success', "Data $struktural->name berhasil dihapus");
    }

   //--------------------------------ini untuk sub nya---------------------------------//
   public function create()
   {
        $strukturals = Struktural::all();
        return view('setting.struktural.create', compact('strukturals'));
   }

   public function store(Request $request)
   {
           $validateData = $request->validate([
               'struktural_id' => 'required',
               'name' => 'required',
          ]);

          Struktural_detail::create($validateData);
          return back()->with('success', 'Data berhasil ditambah');
   }
   
   public function edit(Struktural_detail $struktural_detail)
   {
          $strukturals = Struktural::all();
          return view('setting.struktural.edit', compact('struktural_detail', 'strukturals'));
   }

   public function update(Request $request, Struktural_detail $struktural_detail)
   {
        $validateData = $request->validate([
            'struktural_id' => 'required',
            'name' => 'required',
        ]);

        $struktural_detail->update($validateData);
        return redirect()->route('struktural.index')->with('success', "Data berhasil diubah");
   }

   public function delete(Struktural_detail $struktural_detail)
   {
          $struktural_detail->delete();
          return redirect()->route('struktural.create')->with('success', "Data $struktural_detail->name berhasil dihapus");
   }
}
