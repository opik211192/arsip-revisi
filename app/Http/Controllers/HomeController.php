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
        return view('home');
    }
}
