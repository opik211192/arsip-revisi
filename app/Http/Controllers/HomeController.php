<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Arsip;
use App\Models\Struktural;
use App\Models\V_dashboard;
use Illuminate\Http\Request;
use App\Models\Struktural_detail;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // $tes = DB::table('struktural_details')
        //     ->join('strukturals', 'strukturals.id', '=', 'struktural_details.struktural_id')
        //     ->select('struktural_details.*', 'strukturals.id')
        //     ->get();
        // $strukturals = Struktural_detail::with('struktural')->get();
        // $models = $strukturals->groupBy('struktural.name');
        //dd($tes);
        // $arsips = DB::select('SELECT a.id_pencipta_arsip, 
	    //                     COUNT(a.id_pencipta_arsip) AS "jml",
	    //                     b.name
        //                     FROM arsips a 
        //                     LEFT OUTER JOIN struktural_details b ON a.id_pencipta_arsip = b.id 
        //                     GROUP BY a.id_pencipta_arsip, b.name ');
        $arsips = DB::select('SELECT a.id,a.name,a.struktural_id,
                            c.name as "struktural",
                            COUNT(b.id_pencipta_arsip) AS "jml"
                            FROM struktural_details a
                            LEFT OUTER JOIN arsips b ON a.id = b.id_pencipta_arsip
                            LEFT OUTER JOIN strukturals C ON a.struktural_id = c.id
                            GROUP BY a.id,a.name,a.struktural_id,b.id_pencipta_arsip,c.name
                            ');
        //dd($arsips);
        return view('home', compact('arsips'));
    }
}
