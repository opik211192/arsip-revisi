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
use App\Models\ArsipUpload;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\Struktural_detail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        // ✅ Validasi data utama
        $validated = $request->validate([
            'jenis_arsip_id'    => 'required',
            'lokasi_arsip'      => 'required',
            'jenis_id'          => 'required',
            'no_berkas'         => 'required',
            'no_item'           => 'required',
            'no_box'            => 'required',
            'tahun'             => 'required',
            'id_pencipta_arsip' => 'required',
            'uraian_arsip'      => 'required',
            'user_id'           => 'required',
            'temp_files'        => 'required|array',
        ]);

        unset($validated['temp_files']);

        // Buat arsip utama
        $arsip = Arsip::create($validated);

        // ✅ Ambil data struktural untuk nama folder
        $detail = Struktural_detail::with('struktural')->find($request->id_pencipta_arsip);
        $strukturUtama  = str_replace([' ', '/', '\\'], '-', $detail->struktural->name ?? 'Unknown');
        $strukturDetail = str_replace([' ', '/', '\\'], '-', $detail->name ?? 'Unknown-Detail');
        $tahun = $request->tahun;

        // ✅ Pindahkan setiap file dari temp ke folder final
        foreach ($request->temp_files as $tempFile) {

            // Pastikan path-nya relatif terhadap storage
            $tempPath = str_replace('public/', '', $tempFile);

            $filename = basename($tempPath);
            $targetPath = "public/upload/$strukturUtama/$strukturDetail/$tahun/$filename";

            // Buat folder tujuan jika belum ada
            Storage::makeDirectory(dirname($targetPath));

            // 🔥 Pindahkan file dari temp ke folder final
            $source = storage_path('app/public/' . $tempPath);
            $destination = storage_path('app/upload/' . $strukturUtama . '/' . $strukturDetail . '/' . $tahun . '/' . $filename);

            if (file_exists($source)) {
                // Gunakan Storage agar konsisten di semua OS
                Storage::move('public/' . $tempPath, $targetPath);
            } else {
                Log::warning("File tidak ditemukan: " . $source);
            }

            // Simpan ke tabel arsip_uploads
            ArsipUpload::create([
                'arsip_id'  => $arsip->id,
                'file_path' => $filename,
            ]);
        }

        // ✅ Log aktivitas
        ArsipLog::create([
            'arsip_id'   => $arsip->id,
            'user_id'    => Auth::id(),
            'aksi'       => 'tambah',
            'keterangan' => 'Menambahkan arsip baru: ' . $arsip->uraian_arsip,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'message' => '✅ Data arsip berhasil ditambahkan dan file dipindahkan.',
            'redirect' => route('arsip.data')
        ]);
    }


    public function uploadTemp(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');

            $originalName = $file->getClientOriginalName();

            $newFileName = now()->format('Ymd') . '-' . uniqid() . '-' . $originalName;

            $path = $file->storeAs('public/temp_arsip', $newFileName);

            return response()->json([
                'success' => true,
                'path' => $path,
                'filename' => $newFileName,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Tidak ada file yang diupload.'
        ], 400);
    }


    public function deleteTemp(Request $request)
    {
        $path = $request->input('path');

        if (!$path) {
            return response()->json(['success' => false, 'message' => '❌ Path kosong'], 400);
        }

        // Pastikan path tanpa "public/" di depan
        // karena Storage::delete sudah default ke 'storage/app'
        $cleanPath = str_replace('public/', '', $path);

        $fullPath = storage_path('app/public/' . $cleanPath);

        if (file_exists($fullPath)) {
            unlink($fullPath);
            return response()->json(['success' => true, 'message' => '✅ File berhasil dihapus dari server.']);
        }

        return response()->json(['success' => false, 'message' => '⚠️ File tidak ditemukan.'], 404);
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
                2 => 'tahun',
                3 => 'no_berkas',
                4 => 'no_box',
                5 => 'jenis_id',
                6 => 'jenis_arsip_id',
                7 => 'id_pencipta_arsip',
                8 => 'created_by',
                9 => 'updated_by',
                10 => 'updated_at',
                11 => 'status',
            ];

            $limit = $request->input('length');
            $start = $request->input('start');
            $orderColumn = $columns[$request->input('order.0.column')] ?? 'id';
            $orderDirection = $request->input('order.0.dir') ?? 'desc';
            $search = $request->input('search.value');

            $query = Arsip::query()->select([
                'id', 'uraian_arsip', 'tahun', 'no_berkas', 'no_box', 'jenis_id', 'jenis_arsip_id',
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
                        ->orWhere('tahun', 'like', "%{$search}%")
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
                $nestedData['tahun'] = $arsip->tahun ?? '-';
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
                        <a href="'.route('arsip.detail', $arsip->id).'" class="btn btn-primary btn-sm" title="Detail">
                            <i class="fa fa-info-circle"></i>
                        </a>

                        <a href="'.route('arsip.edit', $arsip->id).'" class="btn btn-warning btn-sm" title="Edit">
                            <i class="fa fa-edit"></i>
                        </a>

                        <form action="'.route('arsip.delete', $arsip->id).'" method="POST" 
                            style="display:inline-block;" 
                            onsubmit="return confirm(\'Yakin ingin menghapus data: '.addslashes($arsip->uraian_arsip).'?\\n\\nTindakan ini juga akan menghapus seluruh file unggahan arsip tersebut secara permanen.\')">
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
        // ✅ 1. Validasi input
        $validated = $request->validate([
            'jenis_arsip_id'    => 'required',
            'lokasi_arsip'      => 'required',
            'jenis_id'          => 'required',
            'no_berkas'         => 'required',
            'no_item'           => 'required',
            'no_box'            => 'required',
            'tahun'             => 'required',
            'id_pencipta_arsip' => 'required',
            'uraian_arsip'      => 'required',
            'user_id'           => 'required',
            'temp_files'        => 'nullable|array', // dari Dropzone
        ]);

        // Hilangkan file sementara dari data utama
        unset($validated['temp_files']);

        // ✅ 2. Simpan data lama untuk pemindahan file
        $tahunLama      = $arsip->tahun;
        $jenisLama      = $arsip->jenis_id;
        $penciptaLama   = $arsip->id_pencipta_arsip;

        // Ambil struktur lama
        $detailLama = Struktural_detail::with('struktural')->find($penciptaLama);
        $strukturUtamaLama  = str_replace([' ', '/', '\\'], '-', $detailLama->struktural->name ?? 'Unknown');
        $strukturDetailLama = str_replace([' ', '/', '\\'], '-', $detailLama->name ?? 'Unknown-Detail');

        // ✅ 3. Update data arsip utama
        $arsip->update($validated);

        // ✅ 4. Jika ada file baru dari Dropzone (temp_files)
        if ($request->filled('temp_files')) {
            $detailBaru = Struktural_detail::with('struktural')->find($request->id_pencipta_arsip);
            $strukturUtamaBaru  = str_replace([' ', '/', '\\'], '-', $detailBaru->struktural->name ?? 'Unknown');
            $strukturDetailBaru = str_replace([' ', '/', '\\'], '-', $detailBaru->name ?? 'Unknown-Detail');
            $tahunBaru = $request->tahun;

            foreach ($request->temp_files as $tempFile) {
                $tempPath = str_replace('public/', '', $tempFile);
                $filename = basename($tempPath);

                $targetPath = "public/upload/$strukturUtamaBaru/$strukturDetailBaru/$tahunBaru/$filename";
                Storage::makeDirectory(dirname($targetPath));

                // Pindahkan file dari temp
                if (Storage::exists($tempFile)) {
                    Storage::move($tempFile, $targetPath);

                    ArsipUpload::create([
                        'arsip_id'  => $arsip->id,
                        'file_path' => $filename,
                    ]);
                }
            }
        }

        // ✅ 5. Jika tahun/jenis/pencipta arsip berubah → pindahkan semua file lama
        $isTahunChanged = $request->tahun != $tahunLama;
        $isJenisChanged = $request->jenis_id != $jenisLama;
        $isPenciptaChanged = $request->id_pencipta_arsip != $penciptaLama;

        if ($isTahunChanged || $isJenisChanged || $isPenciptaChanged) {
            $detailBaru = Struktural_detail::with('struktural')->find($request->id_pencipta_arsip);
            $strukturUtamaBaru  = str_replace([' ', '/', '\\'], '-', $detailBaru->struktural->name ?? 'Unknown');
            $strukturDetailBaru = str_replace([' ', '/', '\\'], '-', $detailBaru->name ?? 'Unknown-Detail');
            $tahunBaru = $request->tahun;

            $uploads = ArsipUpload::where('arsip_id', $arsip->id)->get();

            foreach ($uploads as $upload) {
                $oldPath = "public/upload/$strukturUtamaLama/$strukturDetailLama/$tahunLama/{$upload->file_path}";
                $newPath = "public/upload/$strukturUtamaBaru/$strukturDetailBaru/$tahunBaru/{$upload->file_path}";

                if (Storage::exists($oldPath)) {
                    Storage::makeDirectory(dirname($newPath));
                    Storage::move($oldPath, $newPath);
                }
            }
        }

        // ✅ 6. Log aktivitas
        ArsipLog::create([
            'arsip_id'   => $arsip->id,
            'user_id'    => auth()->id(),
            'aksi'       => 'update',
            'keterangan' => 'Mengubah arsip: ' . $arsip->uraian_arsip,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // ✅ 7. Response JSON agar bisa dipakai di AJAX form edit
        return response()->json([
            'success'  => true,
            'message'  => '✅ Data arsip berhasil diperbarui dan file baru dipindahkan.',
            'redirect' => route('arsip.data'),
        ]);
    }



    public function destroy(Arsip $arsip)
    {
        // ✅ Ambil data file upload dari tabel ArsipUpload
        $uploads = \App\Models\ArsipUpload::where('arsip_id', $arsip->id)->get();

        // ✅ Ambil data struktural untuk menentukan path folder
        $detail = \App\Models\Struktural_detail::with('struktural')
            ->where('id', $arsip->id_pencipta_arsip)
            ->first();

        $strukturUtama = $detail->struktural->name ?? 'Unknown';
        $strukturDetail = $detail->name ?? 'Unknown-Detail';
        $tahun = $arsip->tahun;

        // Bersihkan nama folder dari karakter ilegal
        $folderStruktural = str_replace([' ', '/', '\\'], '-', $strukturUtama);
        $folderStrukturalDtl = str_replace([' ', '/', '\\'], '-', $strukturDetail);

        // ✅ Hapus file fisik satu per satu
        foreach ($uploads as $upload) {
            $filePath = storage_path("app/public/upload/$folderStruktural/$folderStrukturalDtl/$tahun/" . $upload->file_path);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $upload->delete(); // hapus juga record di tabel arsip_uploads
        }

        // ✅ Catat log penghapusan
        \App\Models\ArsipLog::create([
            'arsip_id'   => $arsip->id,
            'user_id'    => auth()->id(),
            'aksi'       => 'hapus',
            'keterangan' => 'Menghapus arsip: ' . $arsip->uraian_arsip,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // ✅ Hapus data arsip utama
        $arsip->delete();

        return redirect()->route('arsip.data')->with('success', "Data \"$arsip->uraian_arsip\" berhasil dihapus.");
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
                            JOIN strukturals b on a.struktural_id = b.id');

        // Ambil semua file yang terhubung
        $arsip->load('uploads');

        return view('arsip.edit', compact('arsip', 'user', 'jenis', 'jenis_arsip', 'models', 'datas'));
    }

    public function viewFile($id)
    {
        $file = ArsipUpload::findOrFail($id);
        $arsip = $file->arsip;

        $detail = Struktural_detail::with('struktural')->find($arsip->id_pencipta_arsip);
        $strukturUtama = $detail->struktural->name ?? 'Unknown';
        $strukturDetail = $detail->name ?? 'Unknown';
        $tahun = $arsip->tahun;

        $folderStruktural = str_replace([' ', '/', '\\'], '-', $strukturUtama);
        $folderDetail = str_replace([' ', '/', '\\'], '-', $strukturDetail);

        $path = storage_path("app/public/upload/$folderStruktural/$folderDetail/$tahun/{$file->file_path}");

        if (!File::exists($path)) {
            abort(404, 'File tidak ditemukan.');
        }

        // Tampilkan langsung di browser
        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . basename($file->file_path) . '"'
        ]);
    }


    //delete file ketika edit
    public function deleteFile($id)
    {
        try {
            $file = ArsipUpload::findOrFail($id);
            $arsip = $file->arsip;

            // Ambil struktur
            $detail = Struktural_detail::with('struktural')->find($arsip->id_pencipta_arsip);
            $strukturUtama = $detail->struktural->name ?? 'Unknown';
            $strukturDetail = $detail->name ?? 'Unknown';
            $tahun = $arsip->tahun;

            // Normalisasi nama folder agar aman
            $folderStruktural = str_replace([' ', '/', '\\'], '-', $strukturUtama);
            $folderDetail = str_replace([' ', '/', '\\'], '-', $strukturDetail);

            // Lokasi file di storage
            $path = storage_path("app/public/upload/$folderStruktural/$folderDetail/$tahun/{$file->file_path}");

            // Hapus file fisik jika ada
            if (File::exists($path)) {
                File::delete($path);
            }

            // Simpan nama file untuk log sebelum dihapus dari DB
            $fileName = $file->file_path;

            // Hapus dari database
            $file->delete();

            // Catat log penghapusan
            ArsipLog::create([
                'arsip_id'   => $arsip->id,
                'user_id'    => auth()->id(),
                'aksi'       => 'hapus file',
                'keterangan' => 'Menghapus file: ' . $fileName,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            // 🔹 Return JSON untuk AJAX
            return response()->json([
                'success' => true,
                'message' => 'File berhasil dihapus.',
            ]);

        } catch (\Exception $e) {
            // Jika ada error, kirimkan JSON error
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus file: ' . $e->getMessage(),
            ], 500);
        }
    }


    public function downloadFile($id)
    {
        $file = ArsipUpload::findOrFail($id);
        $arsip = $file->arsip;

        $detail = Struktural_detail::with('struktural')->find($arsip->id_pencipta_arsip);
        $strukturUtama = $detail->struktural->name ?? 'Unknown';
        $strukturDetail = $detail->name ?? 'Unknown';
        $tahun = $arsip->tahun;

        $folderStruktural = str_replace([' ', '/', '\\'], '-', $strukturUtama);
        $folderDetail = str_replace([' ', '/', '\\'], '-', $strukturDetail);

        $path = storage_path("app/public/upload/$folderStruktural/$folderDetail/$tahun/{$file->file_path}");

        if (!File::exists($path)) {
            abort(404, 'File tidak ditemukan.');
        }

        return response()->download($path);
    }
}
