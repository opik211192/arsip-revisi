<?php

namespace App\Http\Controllers\Arsip;

//use datatables;
//use App\Models\Unit;
use App\Models\User;
use App\Models\Arsip;
use App\Models\Jenis;
use App\Models\JenisArsip;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Struktural_detail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class ArsipController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $jeniss = Jenis::all();
        $jenis_arsip = JenisArsip::all();
        //$units = Unit::all();
        $strukturals = Struktural_detail::with('struktural')->get();
        $models = $strukturals->groupBy('struktural.name');
        $datas = DB::select('SELECT a.name as struktural_detail,
                        b.name as struktural
                        FROM struktural_details a
                        JOIN strukturals b on a.struktural_id  = b.id ');
        
        //$alluser = User::with('unit')->has('unit')->get();
        //dd($users);
        return view('arsip.index', compact('user', 'datas','jeniss' , 'jenis_arsip', 'models'));
    }
    
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'jenis_arsip_id' => 'required',
            'lokasi_arsip' => 'required',
            'jenis_id' => 'required',
            'no_berkas' => 'required',
            'no_box' => 'required',
            'tahun' => 'required',
            'id_pencipta_arsip' => 'required',
            'uraian_arsip' => 'required',
            'user_id' => 'required',
            'file_arsip' => 'required',
        ]);
        
        //buat nama file
        $datas = Struktural_detail::where('id', $request->id_pencipta_arsip)->first()->name;
        $tahun = $request->tahun;
        $jenis = Jenis::where('id', $request->jenis_id)->first()->name;
        $namaFile = str_replace(' ', '-', $datas).'-'.str_replace(' ', '-',$request->file_arsip->getClientOriginalName());
        $request->file_arsip->move(public_path()."/upload/$tahun/".$jenis, $namaFile);

        // inisialisasi nama file_arsip
        $validateData['file_arsip'] = $namaFile;

        // simpan arsip ke database
        Arsip::create($validateData);

        return redirect()->route('arsip.index')->with('success', 'Data berhasil ditambahkan');
       
    }

    public function data(Request $request)  
    {
        // <a href="{{ route('arsip.download', $data) }}"
        //                         class="btn btn-sm btn-success ml-2" xdata-toggle="tooltip" data-placement="top"
        //                         title="Download"><i class="fa fa-download" aria-hidden="true"></i></a>
        //ambil id user
        $user = Auth::user()->struktural_detail_id;
        //jika user super admin 
        if (Auth::user()->roles->pluck('name')->contains('super admin') || Auth::user()->roles->pluck('name')->contains('admin')) {
                if ($request->ajax()) {
                    $arsip = Arsip::query()->with(['user','jenis', 'jenis_arsip', 'struktural_detail'])->latest();
                    return Datatables::of($arsip)
                        ->addIndexColumn()
                        ->addColumn('action', function($row){
                            return view('actions.action_arsip', ['id' => $row->id]);
                        })
                        ->rawColumns(['action'])
                        ->make(true);
                }

                return view('arsip.data');

        }else{
                if ($request->ajax()) {
                    $arsip = Arsip::query()->with('jenis')->where('id_pencipta_arsip', $user)->latest();
                    return Datatables::of($arsip)
                        ->addIndexColumn()
                        ->addColumn('action', function($row){
                            return view('actions.action_arsip', ['id' => $row->id]);
                        })
                        ->rawColumns(['action'])
                        ->make(true);
                }
                return view('arsip.data');
        }        
    }

    public function edit(Arsip $arsip)
    {   
        $user = Auth::user();

        $jenis = Jenis::all();
        $jenis_arsip = JenisArsip::all();
        $strukturals = Struktural_detail::with('struktural')->get();
        $models = $strukturals->groupBy('struktural.name');
        $datas = DB::select('SELECT a.name as struktural_detail,
                        b.name as struktural
                        FROM struktural_details a
                        JOIN strukturals b on a.struktural_id  = b.id ');

        //$user = $arsip->user->id;
        return view('arsip.edit', compact('arsip', 'user' , 'jenis', 'jenis_arsip', 'models', 'datas'));
    }

    public function detail(Arsip $arsip)
    {
        //user super admin
        if (Auth::user()->roles->pluck('name')->contains('super admin') || Auth::user()->roles->pluck('name')->contains('admin')) {
                $data = Arsip::with(['user', 'jenis', 'jenis_arsip'])->findOrFail($arsip->id);
                //$unit = User::with('unit')->findorFail($arsip->user_id);

                $struktural = DB::select('SELECT a.id_pencipta_arsip, 
                                    b.name as "struktural_detail",
                                    c.name as "struktural"
                                FROM arsips a
                                LEFT OUTER JOIN struktural_details b on a.id_pencipta_arsip = b.id 
                                LEFT OUTER join strukturals c on b.struktural_id = c.id 
                                WHERE a.id ='.$arsip->id);
                //$id_struktural = Struktural_detail::with('struktural')->where('id', $arsip->id_struktural)->first()->name;
                //dd($datas);
                return view('arsip.detail', compact('data', 'struktural'));
                //$data = Arsip::with('jenis_arsip')->findOrFail($arsip->id);
                //dd($data);
            
        }else{
            //user biasa
            //keamanan url
            $id = Auth::user()->struktural_detail_id;
            if ($id != $arsip->id_pencipta_arsip) {
                // /echo "<script>alert('Mau Ngedit Punya Siapa Hayoo ????')</script>";
                abort(404);
                
            }else{
                $data = Arsip::with(['user', 'jenis'])->find($arsip->id);
                //$unit = User::with('unit')->findorFail($arsip->user_id);

                 $struktural = DB::select('SELECT a.id_pencipta_arsip, 
                                    b.name as "struktural_detail",
                                    c.name as "struktural"
                                FROM arsips a
                                LEFT OUTER JOIN struktural_details b on a.id_pencipta_arsip = b.id 
                                LEFT OUTER join strukturals c on b.struktural_id = c.id 
                                WHERE a.id ='.$arsip->id);
                return view('arsip.detail', compact('data', 'struktural'));
                
            }
        }
    }

    public function download(Arsip $arsip)
    {
        $data = Arsip::with(['user', 'jenis'])->find($arsip->id);
        //$unit = User::with('unit')->find($arsip->user_id);
        //$folderUnit = $unit->unit->name;
        $tahun = $arsip->tahun;
        $jenis = $data->jenis->name;
        $namaFile = $data->file_arsip;

        $file_path = public_path()."/upload/$tahun/$jenis/$namaFile";
        return response()->download($file_path);
        //dd($file_path);
    }

    public function update(Request $request, Arsip $arsip)
    {
        $validateData = $request->validate([
            'jenis_arsip_id' => 'required',
            'lokasi_arsip' => 'required',
            'jenis_id' => 'required',
            'no_berkas' => 'required',
            'no_box' => 'required',
            'tahun' => 'required',
            'id_pencipta_arsip' => 'required',
            'uraian_arsip' => 'required',
            'user_id' => 'required',
        ]);

          //super admnin & admin
        if (Auth::user()->roles->pluck('name')->contains('super admin') || Auth::user()->roles->pluck('name')->contains('admin')) {
            if ($request->hasFile('file_arsip')) {
                $namaFileOld = $arsip->file_arsip;
                $tahunOld = $arsip->tahun;
                $jenisOld = $arsip->jenis->name;

                $datas = Struktural_detail::where('id', $arsip->id_pencipta_arsip)->first()->name;
                

                $tahun = $request->tahun;
                $jenis = Jenis::where('id', $request->jenis_id)->first()->name;
                $namaFile = str_replace(' ', '-', $datas).'-'.str_replace(' ', '-',$request->file_arsip->getClientOriginalName());

                $file_path_old = public_path()."/upload/$tahunOld/$jenisOld/$namaFileOld";
                unlink($file_path_old);
                $request->file_arsip->move(public_path()."/upload/$tahun/". $jenis, $namaFile);
                $validateData['file_arsip'] = $namaFile;
                $arsip->update($validateData);
                return redirect()->route('arsip.data')->with('success', 'Data berhasil diubah');
                
            }else{
                //echo "update tidak punya gambar";
                //jika ganti jenis dan tahun
                if ($request->jenis_id != $arsip->jenis_id || $request ->tahun != $arsip->tahun) {
                    $namaFileOld = $arsip->file_arsip;
                    $tahunOld = $arsip->tahun;
                    $jenisOld = $arsip->jenis->name;

                    $tahun = $request->tahun;
                    $jenis = Jenis::where('id', $request->jenis_id)->first()->name;

                    $file_path_old = public_path()."/upload/$tahunOld/$jenisOld/$namaFileOld";
                    $file_path_new = public_path()."/upload/$tahun/$jenis/$namaFileOld";

                    if (!File::exists($file_path_new)) {
                        File::makeDirectory(public_path()."/upload/$tahun/$jenis", 0777, true, true);
                        File::move($file_path_old, public_path()."/upload/$tahun/$jenis/$namaFileOld");
                    }elseif(File::exists($file_path_new)){
                        File::makeDirectory(public_path()."/upload/$tahun/$jenis", 0777, true);
                        File::move($file_path_old, public_path()."/upload/$tahun/$jenis/$namaFileOld");
                    }
                    $arsip->update($validateData);
                    return redirect()->route('arsip.data')->with('success', 'Data berhasil diubah');
                }else{
                    $arsip->update($validateData);
                    return redirect()->route('arsip.data')->with('success', 'Data berhasil diubah');
                }
            }
        }else{
            //keamanan url untuk user agar tidak bisa akses id
            $id = Auth::user()->id;
            if ($id != $arsip->user_id) {
                    abort(404);
            }else{
                //echo "proses user disini";
                if ($request->hasFile('file_arsip')) {
                    $namaFileOld = $arsip->file_arsip;
                    $tahunOld = $arsip->tahun;
                    $jenisOld = $arsip->jenis->name;

                    $datas = Struktural_detail::where('id', $request->id_pencipta_arsip)->first()->name;
                   

                    $tahun = $request->tahun;
                    $jenis = Jenis::where('id', $request->jenis_id)->first()->name;
                    $namaFile = str_replace(' ', '-', $datas).'-'.str_replace(' ', '-',$request->file_arsip->getClientOriginalName());

                    $file_path_old = public_path()."/upload/$tahunOld/$jenisOld/$namaFileOld";
                    unlink($file_path_old);
                    $request->file_arsip->move(public_path()."/upload/$tahun/". $jenis, $namaFile);
                    $validateData['file_arsip'] = $namaFile;
                    $arsip->update($validateData);
                    return redirect()->route('arsip.data')->with('success', 'Data berhasil diubah');
                }else {
                    if ($request->jenis_id != $arsip->jenis_id || $request ->tahun != $arsip->tahun) {
                        $namaFileOld = $arsip->file_arsip;
                        $tahunOld = $arsip->tahun;
                        $jenisOld = $arsip->jenis->name;

                        $tahun = $request->tahun;
                        $jenis = Jenis::where('id', $request->jenis_id)->first()->name;

                        $file_path_old = public_path()."/upload/$tahunOld/$jenisOld/$namaFileOld";
                        $file_path_new = public_path()."/upload/$tahun/$jenis/$namaFileOld";

                        if (!File::exists($file_path_new)) {
                            File::makeDirectory(public_path()."/upload/$tahun/$jenis", 0777, true, true);
                            File::move($file_path_old, public_path()."/upload/$tahun/$jenis/$namaFileOld");
                        }elseif(File::exists($file_path_new)){
                            File::makeDirectory(public_path()."/upload/$tahun/$jenis", 0777, true);
                            File::move($file_path_old, public_path()."/upload/$tahun/$jenis/$namaFileOld");
                        }
                        $arsip->update($validateData);
                        return redirect()->route('arsip.data')->with('success', 'Data berhasil diubah');
                    }else{
                        $arsip->update($validateData);
                        return redirect()->route('arsip.data')->with('success', 'Data berhasil diubah');
                    }
                }
            
            }
        }
    
    }

    public function destroy(Arsip $arsip)
    {
        $namaFile = $arsip->file_arsip;
        $tahun = $arsip->tahun;
        //ambil jenis arsip
        $jenis =Jenis::where('id', $arsip->jenis_id)->first()->name;
        //dd(public_path()."/upload/$folderUser/$tahun/$jenis/$namaFile");
        $arsip->delete();
        unlink(public_path()."/upload/$tahun/$jenis/$namaFile");
        return redirect()->route('arsip.data')->with('pesan', "Hapus $arsip->nama berhasil");
    }
}
