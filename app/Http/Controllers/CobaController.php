<?php

namespace App\Http\Controllers;

use App\Models\Arsip;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CobaController extends Controller
{
     public function index(Request $request)  
     {
        //ambil id user
        //$user = Auth::user()->id;
        //jika user super admin 
       
                if ($request->ajax()) {
                    if (!empty($request->filter_tahun)) {
                        $arsip = Arsip::with(['user.unit','jenis', 'jenis_arsip'])
                            ->where('tahun', $request->filter_tahun)
                            ->where('jenis_id', $request->filter_jenis)
                            ->get();
                    }else{
                        $arsip = Arsip::with(['user','jenis', 'jenis_arsip'])
                                ->get();
                    }

                    return datatables()->of($arsip)->make(true);
                    
                }

                $allArsip =  $arsip = Arsip::with(['user','jenis', 'jenis_arsip'])
                                    ->get();

                return view('welcome1', compact('allArsip'));

        }
    
}
