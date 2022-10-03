<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Arsip;
use App\Models\Struktural;
use App\Models\V_dashboard;
use Illuminate\Http\Request;
use App\Models\Struktural_detail;
use Illuminate\Support\Facades\DB;
use JeroenNoten\LaravelAdminLte\View\Components\Form\Select;

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
        $allArsip = Arsip::all()->count();
       
        // $arsips = DB::select('SELECT a.id,a.name,a.struktural_id,
        //                     c.name as "struktural",
        //                     COUNT(b.id_pencipta_arsip) AS "jml"
        //                     FROM struktural_details a
        //                     LEFT OUTER JOIN arsips b ON a.id = b.id_pencipta_arsip
        //                     LEFT OUTER JOIN strukturals C ON a.struktural_id = c.id
        //                     GROUP BY a.id,a.name,a.struktural_id,b.id_pencipta_arsip,c.name
        //                     ');
        
        // $arsips = DB::table('struktural_details')
        //             ->leftJoin('arsips', 'struktural_details.id', '=', 'arsips.id_pencipta_arsip')
        //             ->leftJoin('strukturals', 'struktural_details.struktural_id', '=', 'strukturals.id')
        //             ->select('struktural_details.id','strukturals.name AS struktural','struktural_details.name AS struktural_detail', DB::raw('count(arsips.id_pencipta_arsip) as jml'))
        //             ->groupBy('struktural_details.id','struktural_details.name', 'strukturals.name', 'arsips.id_pencipta_arsip')
        //             ->paginate(10);
       // dd($arsips);
       $countArsip = DB::table('strukturals')
                        ->leftJoin('struktural_details', 'strukturals.id', '=', 'struktural_details.struktural_id')
                        ->leftJoin('arsips', 'struktural_details.id', '=', 'arsips.id_pencipta_arsip')
                        ->select('strukturals.name AS struktur', DB::raw('COUNT(arsips.id_pencipta_arsip) as jumlah'))
                        ->groupBy('strukturals.name')
                        ->orderBy('strukturals.id')
                        ->get(); 
        //dd($countArsip);
       
        return view('home', compact('allArsip', 'countArsip'));
    }
}
