<?php

namespace App\Http\Controllers;

use App\Models\Arsip;
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
        $allArsip = DB::table('arsips')->count();

        $strukturals = DB::table('strukturals')
            ->leftJoin('struktural_details', 'strukturals.id', '=', 'struktural_details.struktural_id')
            ->leftJoin('arsips', 'struktural_details.id', '=', 'arsips.id_pencipta_arsip')
            ->select(
                'strukturals.id as struktural_id',
                'strukturals.name as struktural_name',
                'struktural_details.id as detail_id',
                'struktural_details.name as detail_name',
                DB::raw('COUNT(arsips.id) as jumlah'),
                DB::raw('MAX(arsips.created_at) as terakhir_input')
            )
            ->groupBy('strukturals.id', 'strukturals.name', 'struktural_details.id', 'struktural_details.name')
            ->orderBy('strukturals.id')
            ->get()
            ->groupBy('struktural_name');

        return view('home', compact('allArsip', 'strukturals'));
    }


}
