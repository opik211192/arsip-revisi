<?php

namespace App\Http\Controllers\Arsip;

//use datatables;
//use App\Models\Unit;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Arsip;
use App\Models\Jenis;
use App\Models\ArsipLog;
use App\Models\JenisArsip;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\Struktural_detail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

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
            'file_arsip.*' => 'mimes:pdf,doc,docx,xls,xlsx|max:5024',
        ]);

        $file = $request->file('file_arsip')[0] ?? null; // ambil file pertama
        if ($file) {
            $now = Carbon::now();
            $datas = Struktural_detail::where('id', $request->id_pencipta_arsip)->first()->name;
            $tahun = $request->tahun;
            $jenis = Jenis::where('id', $request->jenis_id)->first()->name;
            $namaFile = $now->format('Ymd') . '-' . str_replace(' ', '-', $datas) . '-' . str_replace(' ', '-', $file->getClientOriginalName());
            $file->storeAs("public/upload/$tahun/$jenis", $namaFile);
            $validateData['file_arsip'] = $namaFile;
        }

        Arsip::create($validateData);

        $arsip = Arsip::latest()->first();

        ArsipLog::create([
            'arsip_id'   => $arsip->id,
            'user_id'    => auth()->id(),
            'aksi'       => 'tambah',
            'keterangan' => 'Menambahkan arsip baru: ' . $arsip->uraian_arsip,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('arsip.data')->with('success', 'Data berhasil ditambahkan');
    }


    // public function data(Request $request)  
    // {
    //                 $arsip = Arsip::query()->with(['user','jenis', 'jenis_arsip', 'struktural_detail', 'created_by', 'updated_by'])->latest();

    //     // <a href="{{ route('arsip.download', $data) }}"
    //     //                         class="btn btn-sm btn-success ml-2" xdata-toggle="tooltip" data-placement="top"
    //     //                         title="Download"><i class="fa fa-download" aria-hidden="true"></i></a>
        
    //     //ambil id user
    //     $user = Auth::user()->struktural_detail_id;
    //     //jika user super admin 
    //     if (Auth::user()->roles->pluck('name')->contains('super admin') || Auth::user()->roles->pluck('name')->contains('admin')) {
    //             if ($request->ajax()) {
    //                 $arsip = Arsip::query()->with(['user','jenis', 'jenis_arsip', 'struktural_detail'])->latest();
    //                 return Datatables::of($arsip)
    //                     ->addIndexColumn()
    //                     ->addColumn('created_by', function ($row) {
    //                         return $row->createdBy->name ?? '-';
    //                     })
    //                     ->addColumn('updated_by', function ($row) {
    //                         return $row->updatedBy->name ?? '-';
    //                     })
    //                     ->addColumn('action', function($row){
    //                         return view('actions.action_arsip', ['id' => $row->id]);
    //                     })
    //                     ->rawColumns(['action'])
    //                     ->make(true);
    //             }

    //             return view('arsip.data');

    //     }else{
    //             if ($request->ajax()) {
    //                 $arsip = Arsip::query()->with('jenis')->where('id_pencipta_arsip', $user)->latest();
    //                 return Datatables::of($arsip)
    //                     ->addIndexColumn()
    //                     ->addColumn('action', function($row){
    //                         return view('actions.action_arsip', ['id' => $row->id]);
    //                     })
    //                     ->rawColumns(['action'])
    //                     ->make(true);
    //             }
    //             return view('arsip.data');
    //     }        
    // }

    public function data(Request $request)
    {
        $user = Auth::user();
        $isAdmin = $user->roles->pluck('name')->contains(fn($r) => in_array($r, ['super admin', 'admin']));

        if ($request->ajax()) {
            $columns = [
                0 => 'id',
                1 => 'uraian_arsip',
                2 => 'no_berkas',
                3 => 'no_box',
                4 => 'jenis_id',
                5 => 'jenis_arsip_id',
                6 => 'id_pencipta_arsip',
                7 => 'created_by',
                8 => 'updated_by',
                9 => 'updated_at',
                10 => 'status',
            ];

            $limit = $request->input('length');
            $start = $request->input('start');
            $orderColumn = $columns[$request->input('order.0.column')] ?? 'id';
            $orderDirection = $request->input('order.0.dir') ?? 'desc';
            $search = $request->input('search.value');

            $query = Arsip::query()->select([
                'id', 'uraian_arsip', 'no_berkas', 'no_box', 'jenis_id', 'jenis_arsip_id',
                'id_pencipta_arsip', 'created_by', 'updated_by', 'updated_at', 'status'
            ])
            ->with([
                'jenis:id,name',
                'jenis_arsip:id,name',
                'struktural_detail:id,name',
                'createdBy:id,name',
                'updatedBy:id,name'
            ]);

            // Filter user jika bukan admin
            if (!$isAdmin) {
                $query->where('id_pencipta_arsip', $user->struktural_detail_id);
            }

            // 🔹 pencarian (semua berdasarkan name & uraian)
            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('uraian_arsip', 'like', "%{$search}%")
                        ->orWhere('no_berkas', 'like', "%{$search}%") 
                        ->orWhere('no_box', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%")
                        ->orWhereHas('jenis', fn($sub) => $sub->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('jenis_arsip', fn($sub) => $sub->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('struktural_detail', fn($sub) => $sub->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('createdBy', fn($sub) => $sub->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('updatedBy', fn($sub) => $sub->where('name', 'like', "%{$search}%"));
                });
            }

            // Hitung total dan filter
            $totalData = Arsip::count();
            $totalFiltered = $query->count();

            // Ambil data sesuai pagination
            $arsips = $query->offset($start)
                ->limit($limit)
                ->orderBy($orderColumn, $orderDirection)
                ->get();

            $data = [];
            foreach ($arsips as $index => $arsip) {
                $nestedData['DT_RowIndex'] = $start + $index + 1;
                $nestedData['uraian_arsip'] = $arsip->uraian_arsip ?? '-';
                $nestedData['no_berkas'] = $arsip->no_berkas ?? '-';
                $nestedData['no_box'] = $arsip->no_box ?? '-';
                $nestedData['jenis'] = $arsip->jenis->name ?? '-';
                $nestedData['jenis_arsip'] = $arsip->jenis_arsip->name ?? '-';
                $nestedData['pencipta'] = $arsip->struktural_detail->name ?? '-';
                $nestedData['created_by'] = $arsip->createdBy->name ?? '-';
                $nestedData['updated_by'] = $arsip->updatedBy->name ?? '-';
                $nestedData['updated_at'] = $arsip->updated_at ? $arsip->updated_at->format('Y-m-d H:i') : '-';
                $nestedData['status'] = $arsip->status;

               // Tombol aksi
                $nestedData['action'] = '
                    <div class="d-flex gap-1">
                        <a href="'.route('arsip.download', $arsip->id).'" class="btn btn-success btn-sm" title="Download">
                            <i class="fa fa-download"></i>
                        </a>

                        <a href="'.route('arsip.detail', $arsip->id).'" class="btn btn-primary btn-sm" title="Detail">
                            <i class="fa fa-info-circle"></i>
                        </a>

                        <a href="'.route('arsip.edit', $arsip->id).'" class="btn btn-warning btn-sm" title="Edit">
                            <i class="fa fa-edit"></i>
                        </a>

                        <form action="'.route('arsip.delete', $arsip->id).'" method="POST" 
                            style="display:inline-block;" 
                            onsubmit="return confirm(\'Yakin ingin menghapus data ini?\')">
                            <input type="hidden" name="_token" value="'.csrf_token().'">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                <i class="fa fa-trash"></i>
                            </button>
                        </form>
                    </div>
                ';


                $data[] = $nestedData;
            }

            $json_data = [
                "draw" => intval($request->input('draw')),
                "recordsTotal" => intval($totalData),
                "recordsFiltered" => intval($totalFiltered),
                "data" => $data,
            ];

            return response()->json($json_data);
        }

        return view('arsip.data');
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

        //$file_path = public_path()."/storage/upload/$tahun/$jenis/$namaFile";

        //$file_path = Storage::path("public/upload/$tahun/$jenis/$namaFile");
        //return response()->download($file_path);
        //return Storage::disk('storage')->download($file_path);
        return  response()->download(storage_path("app/public/upload/$tahun/$jenis/". $namaFile));
        ArsipLog::create([
            'arsip_id'   => $arsip->id,
            'user_id'    => auth()->id(),
            'aksi'       => 'download',
            'keterangan' => 'Mengunduh arsip: ' . $arsip->uraian,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
        //dd(storage_path("app/upload/$tahun"));
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

                $now = Carbon::now();

                $namaFileOld = $arsip->file_arsip;
                $tahunOld = $arsip->tahun;
                $jenisOld = $arsip->jenis->name;

                $datas = Struktural_detail::where('id', $arsip->id_pencipta_arsip)->first()->name;
                

                $tahun = $request->tahun;
                $jenis = Jenis::where('id', $request->jenis_id)->first()->name;
                $namaFile = $now->format('Ymd').'-'.str_replace(' ', '-', $datas).'-'.str_replace(' ', '-',$request->file_arsip->getClientOriginalName());

                //$file_path_old = public_path()."/upload/$tahunOld/$jenisOld/$namaFileOld";
                $file_path_old = storage_path("app/public/upload/$tahunOld/$jenisOld/". $namaFileOld);
                unlink($file_path_old);
                //$request->file_arsip->move(public_path()."/upload/$tahun/". $jenis, $namaFile);
                $request->file_arsip->storeAs("public/upload/$tahun/$jenis", $namaFile);

                $validateData['file_arsip'] = $namaFile;
                $arsip->update($validateData);
                ArsipLog::create([
                    'arsip_id'   => $arsip->id,
                    'user_id'    => auth()->id(),
                    'aksi'       => 'update',
                    'keterangan' => 'Mengubah arsip: ' . $arsip->uraian_arsip,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);
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

                    //$file_path_old = public_path()."/upload/$tahunOld/$jenisOld/$namaFileOld";
                    //$file_path_new = public_path()."/upload/$tahun/$jenis/$namaFileOld";

                    $file_path_old = storage_path("app/public/upload/$tahunOld/$jenisOld/". $namaFileOld);
                    $file_path_new = storage_path("app/public/upload/$tahun/$jenis/". $namaFileOld);


                    // if (!File::exists($file_path_new)) {
                    //     File::makeDirectory(public_path()."/upload/$tahun/$jenis", 0777, true, true);
                    //     File::move($file_path_old, public_path()."/upload/$tahun/$jenis/$namaFileOld");
                    // }elseif(File::exists($file_path_new)){
                    //     File::makeDirectory(public_path()."/upload/$tahun/$jenis", 0777, true);
                    //     File::move($file_path_old, public_path()."/upload/$tahun/$jenis/$namaFileOld");
                    // }

                    if (!File::exists($file_path_new)) {
                        File::makeDirectory(storage_path()."/app/public//upload/$tahun/$jenis", 0777, true, true);
                        File::move($file_path_old, storage_path()."/app/public/upload/$tahun/$jenis/$namaFileOld");
                    }elseif(File::exists($file_path_new)){
                        File::makeDirectory(storage_path()."/app/public/upload/$tahun/$jenis", 0777, true);
                        File::move($file_path_old, storage_path()."app/public/upload/$tahun/$jenis/$namaFileOld");
                    }



                    $arsip->update($validateData);
                    ArsipLog::create([
                        'arsip_id'   => $arsip->id,
                        'user_id'    => auth()->id(),
                        'aksi'       => 'update',
                        'keterangan' => 'Mengubah arsip: ' . $arsip->uraian_arsip,
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                    ]);
                    return redirect()->route('arsip.data')->with('success', 'Data berhasil diubah');
                }else{
                    $arsip->update($validateData);
                    ArsipLog::create([
                        'arsip_id'   => $arsip->id,
                        'user_id'    => auth()->id(),
                        'aksi'       => 'update',
                        'keterangan' => 'Mengubah arsip: ' . $arsip->uraian_arsip,
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                    ]);
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
                    $now = Carbon::now();

                    $namaFileOld = $arsip->file_arsip;
                    $tahunOld = $arsip->tahun;
                    $jenisOld = $arsip->jenis->name;

                    $datas = Struktural_detail::where('id', $request->id_pencipta_arsip)->first()->name;
                   

                    $tahun = $request->tahun;
                    $jenis = Jenis::where('id', $request->jenis_id)->first()->name;
                    $namaFile = $now->format('Ymd').'-'.str_replace(' ', '-', $datas).'-'.str_replace(' ', '-',$request->file_arsip->getClientOriginalName());

                    //$file_path_old = public_path()."/upload/$tahunOld/$jenisOld/$namaFileOld";
                    $file_path_old = storage_path("app/public/upload/$tahun/$jenis/". $namaFile);

                    unlink($file_path_old);
                    //$request->file_arsip->move(public_path()."/upload/$tahun/". $jenis, $namaFile);
                    $request->file_arsip->storeAs("public/upload/$tahun/$jenis", $namaFile);

                    $validateData['file_arsip'] = $namaFile;
                    $arsip->update($validateData);
                    ArsipLog::create([
                        'arsip_id'   => $arsip->id,
                        'user_id'    => auth()->id(),
                        'aksi'       => 'update',
                        'keterangan' => 'Mengubah arsip: ' . $arsip->uraian_arsip,
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                    ]);
                    return redirect()->route('arsip.data')->with('success', 'Data berhasil diubah');
                }else {
                    if ($request->jenis_id != $arsip->jenis_id || $request ->tahun != $arsip->tahun) {
                        $namaFileOld = $arsip->file_arsip;
                        $tahunOld = $arsip->tahun;
                        $jenisOld = $arsip->jenis->name;

                        $tahun = $request->tahun;
                        $jenis = Jenis::where('id', $request->jenis_id)->first()->name;

                        // $file_path_old = public_path()."/upload/$tahunOld/$jenisOld/$namaFileOld";
                        // $file_path_new = public_path()."/upload/$tahun/$jenis/$namaFileOld";

                        $file_path_old = storage_path("app/public/upload/$tahunOld/$jenisOld/". $namaFileOld);
                        $file_path_new = storage_path("app/public/upload/$tahun/$jenis/". $namaFileOld);

                        // if (!File::exists($file_path_new)) {
                        //     File::makeDirectory(public_path()."/upload/$tahun/$jenis", 0777, true, true);
                        //     File::move($file_path_old, public_path()."/upload/$tahun/$jenis/$namaFileOld");
                        // }elseif(File::exists($file_path_new)){
                        //     File::makeDirectory(public_path()."/upload/$tahun/$jenis", 0777, true);
                        //     File::move($file_path_old, public_path()."/upload/$tahun/$jenis/$namaFileOld");
                        // }

                        
                    if (!File::exists($file_path_new)) {
                        File::makeDirectory(storage_path()."/app/public//upload/$tahun/$jenis", 0777, true, true);
                        File::move($file_path_old, storage_path()."/app/public/upload/$tahun/$jenis/$namaFileOld");
                    }elseif(File::exists($file_path_new)){
                        File::makeDirectory(storage_path()."/app/public/upload/$tahun/$jenis", 0777, true);
                        File::move($file_path_old, storage_path()."app/public/upload/$tahun/$jenis/$namaFileOld");
                    }


                        $arsip->update($validateData);
                        ArsipLog::create([
                            'arsip_id'   => $arsip->id,
                            'user_id'    => auth()->id(),
                            'aksi'       => 'update',
                            'keterangan' => 'Mengubah arsip: ' . $arsip->uraian_arsip,
                            'ip_address' => $request->ip(),
                            'user_agent' => $request->userAgent(),
                        ]);
                        return redirect()->route('arsip.data')->with('success', 'Data berhasil diubah');
                    }else{
                        $arsip->update($validateData);
                        ArsipLog::create([
                            'arsip_id'   => $arsip->id,
                            'user_id'    => auth()->id(),
                            'aksi'       => 'update',
                            'keterangan' => 'Mengubah arsip: ' . $arsip->uraian_arsip,
                            'ip_address' => $request->ip(),
                            'user_agent' => $request->userAgent(),
                        ]);
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

        ArsipLog::create([
            'arsip_id'   => $arsip->id,
            'user_id'    => auth()->id(),
            'aksi'       => 'hapus',
            'keterangan' => 'Menghapus arsip: ' . $arsip->uraian_arsip,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        $arsip->delete();

      

        $file_path = storage_path("app/public/upload/$tahun/$jenis/". $namaFile);
        unlink($file_path);


        return redirect()->route('arsip.data')->with('pesan', "Hapus $arsip->nama berhasil");
    }

    //untuk approval
    public function approval($id)
    {
         $data = Arsip::findOrFail($id);
         return response()->json($data);
    }

    public function approvalUpdate(Request $request, Arsip $arsip)
    {
        $validateData = $request->validate([
            'status' => '',
            'keterangan' => '',
        ]);

        $arsip->update($validateData);
        return redirect()->route('arsip.detail', $arsip);
    }
}
