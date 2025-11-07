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

        // 🔹 Dapatkan 5 arsip terbaru (untuk tabel kecil di dashboard misalnya)
        $latestDrafts = DB::table('arsip_drafts')
            ->leftJoin('jenis', 'arsip_drafts.jenis_id', '=', 'jenis.id')
            ->leftJoin('struktural_details', 'arsip_drafts.id_pencipta_arsip', '=', 'struktural_details.id')
            ->select(
                'arsip_drafts.id',
                'arsip_drafts.uraian_arsip',
                'arsip_drafts.tahun',
                'arsip_drafts.no_box',
                'arsip_drafts.no_berkas',
                'jenis.name as jenis_name',
                'struktural_details.name as pencipta_name',
                'arsip_drafts.created_at'
            )
            ->latest('arsip_drafts.created_at')
            ->limit(5)
            ->get();

        return view('home', compact('allArsip', 'strukturals', 'latestDrafts'));
    }


}
