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
    // 🔹 Hitung total semua arsip draft
    $allArsip = DB::table('arsip_drafts')->count();

    // 🔹 Ambil data rekap per struktural dan detailnya
    $strukturals = DB::table('strukturals')
        ->leftJoin('struktural_details', 'strukturals.id', '=', 'struktural_details.struktural_id')
        ->leftJoin('arsip_drafts', 'struktural_details.id', '=', 'arsip_drafts.id_pencipta_arsip')
        ->select(
            'strukturals.id as struktural_id',
            'strukturals.name as struktural_name',
            'struktural_details.id as detail_id',
            'struktural_details.name as detail_name',
            DB::raw('COUNT(arsip_drafts.id) as jumlah'),
            DB::raw('MAX(arsip_drafts.created_at) as terakhir_input')
        )
        ->groupBy('strukturals.id', 'strukturals.name', 'struktural_details.id', 'struktural_details.name')
        ->orderBy('strukturals.id')
        ->get()
        ->groupBy('struktural_name');

    // 🔹 Hitung arsip milik user login (untuk writer/user)
    $userArsip = 0;
    if (auth()->check() && auth()->user()->hasAnyRole(['user', 'writer'])) {
        $userArsip = DB::table('arsip_drafts')
            ->where('user_id', auth()->id()) // pastikan kolom user_id ada di tabel arsip_drafts
            ->count();
    }

    return view('home', compact('allArsip', 'strukturals', 'userArsip'));
}


}
