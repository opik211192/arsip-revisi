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
use Illuminate\Support\Str;
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
            'jenis_arsip_id'   => 'required',
            'lokasi_arsip'     => 'required',
            'jenis_id'         => 'required',
            'no_berkas'        => 'required',
            'no_box'           => 'required',
            'no_item'          => 'required',
            'tahun'            => 'required',
            'id_pencipta_arsip'=> 'required',
            'uraian_arsip'     => 'required',
            'user_id'          => 'required',
            'file_arsip'       => 'required',
            'file_arsip.*'     => 'mimes:pdf,doc,docx,xls,xlsx,zip,rar|max:51200',
        ]);

        $file = $request->file('file_arsip')[0] ?? null; // ambil file pertama
        if ($file) {
            // Ambil data terkait relasi
            $strukturalDetail = \App\Models\Struktural_detail::find($request->id_pencipta_arsip);
            $struktural = $strukturalDetail->struktural->name ?? 'Tanpa-Struktural';
            $detail = $strukturalDetail->name ?? 'Tanpa-Detail';
            $jenis = \App\Models\Jenis::find($request->jenis_id)->name ?? 'Tanpa-Jenis';
            $tahun = $request->tahun;

            // 🔹 Ganti spasi jadi "-", hapus karakter aneh biar aman di nama folder
            $clean = function ($text) {
                return preg_replace('/[^A-Za-z0-9\-\s]/', '', str_replace(' ', '-', trim($text)));
            };

            $struktural = $clean($struktural);
            $detail = $clean($detail);
            $jenis = $clean($jenis);

            // Tentukan path penyimpanan
            $path = "public/upload/{$struktural}/{$detail}/{$jenis}/{$tahun}";

            // Buat nama file unik
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $ext = $file->getClientOriginalExtension();
            $namaFile = now()->format('YmdHis') . '-' . str_replace(' ', '-', $originalName) . '.' . $ext;

            // Simpan file
            $file->storeAs($path, $namaFile);

            // Simpan path relatif ke DB (tanpa "public/")
            $validateData['file_arsip'] = "{$struktural}/{$detail}/{$jenis}/{$tahun}/{$namaFile}";
        }

        // Simpan data arsip
        $arsip = \App\Models\Arsip::create($validateData);

        // Log aktivitas
        \App\Models\ArsipLog::create([
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
                    <div class="d-flex gap-2">
                        

                        <a href="'.route('arsip.detail', $arsip->id).'" class="btn btn-primary btn-sm mr-1" title="Detail">
                            <i class="fa fa-info-circle"></i>
                        </a>

                        <a href="'.route('arsip.edit', $arsip->id).'" class="btn btn-warning btn-sm mr-1" title="Edit">
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
        $user = Auth::user();
        $roles = $user->roles->pluck('name')->toArray();

        // 🔹 Jika super admin atau admin
        if (in_array('super admin', $roles) || in_array('admin', $roles)) {
            $data = Arsip::with(['user', 'jenis', 'jenis_arsip'])->findOrFail($arsip->id);
        } else {
            // 🔒 Cegah akses arsip milik struktural lain
            if ($user->struktural_detail_id != $arsip->id_pencipta_arsip) {
                abort(404);
            }

            $data = Arsip::with(['user', 'jenis'])->findOrFail($arsip->id);
        }

        // 🔹 Ambil data struktural & detailnya (lebih bersih dari query builder)
        $struktural = \App\Models\Struktural_detail::with('struktural')
            ->select('id', 'name', 'struktural_id')
            ->find($arsip->id_pencipta_arsip);

        // buat array siap dikirim ke view
        $strukturInfo = [
            'struktural_detail' => $struktural->name ?? '-',
            'struktural'        => $struktural->struktural->name ?? '-',
        ];

        return view('arsip.detail', compact('data', 'strukturInfo'));
    }


    public function download(Arsip $arsip)
    {
        $data = Arsip::with(['user', 'jenis'])->findOrFail($arsip->id);

        // 🔹 Ambil info folder lengkap berdasarkan struktur terbaru
        $detail = \App\Models\Struktural_detail::with('struktural')->find($arsip->id_pencipta_arsip);
        $struktural = $detail->struktural->name ?? 'Tanpa-Struktural';
        $detailName = $detail->name ?? 'Tanpa-Detail';
        $jenis = $data->jenis->name ?? 'Tanpa-Jenis';
        $tahun = $arsip->tahun;
        $namaFile = basename($arsip->file_arsip);

        // 🔹 Sama seperti di store/update → ganti spasi jadi tanda "-"
        $clean = fn($text) => preg_replace('/[^A-Za-z0-9\-\s]/', '', str_replace(' ', '-', trim($text)));
        $struktural = $clean($struktural);
        $detailName = $clean($detailName);
        $jenis = $clean($jenis);

        // 🔹 Bangun path file berdasarkan struktur folder terbaru
        $filePath = storage_path("app/public/upload/{$struktural}/{$detailName}/{$jenis}/{$tahun}/{$namaFile}");

        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan di penyimpanan.');
        }

        // 🔹 Catat log download
        \App\Models\ArsipLog::create([
            'arsip_id'   => $arsip->id,
            'user_id'    => auth()->id(),
            'aksi'       => 'download',
            'keterangan' => 'Mengunduh arsip: ' . $arsip->uraian_arsip,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // 🔹 Download file
        return response()->download($filePath);
    }


    public function update(Request $request, Arsip $arsip)
    {
        $validateData = $request->validate([
            'jenis_arsip_id'   => 'required',
            'lokasi_arsip'     => 'required',
            'jenis_id'         => 'required',
            'no_berkas'        => 'required',
            'no_box'           => 'required',
            'tahun'            => 'required',
            'id_pencipta_arsip'=> 'required',
            'uraian_arsip'     => 'required',
            'user_id'          => 'required',
        ]);

        // Ambil model dan info lama
        $oldFilePath = $arsip->file_arsip; // misal: "Bappeda/Bidang-Tata-Ruang/Klasifikasi-Keuangan/2025/namafile.pdf"
        $fileNameOnly = basename($oldFilePath);

        // Ambil data relasi baru
        $detail = \App\Models\Struktural_detail::find($request->id_pencipta_arsip);
        $struktural = $detail->struktural->name ?? 'Tanpa-Struktural';
        $detailName = $detail->name ?? 'Tanpa-Detail';
        $jenis = \App\Models\Jenis::find($request->jenis_id)->name ?? 'Tanpa-Jenis';
        $tahun = $request->tahun;

        // Fungsi helper sederhana untuk ganti spasi ke "-"
        $clean = fn($text) => preg_replace('/[^A-Za-z0-9\-\s]/', '', str_replace(' ', '-', trim($text)));

        $struktural = $clean($struktural);
        $detailName = $clean($detailName);
        $jenis = $clean($jenis);

        // Tentukan path baru
        $newFolder = "public/upload/{$struktural}/{$detailName}/{$jenis}/{$tahun}";
        $newRelativePath = "{$struktural}/{$detailName}/{$jenis}/{$tahun}/{$fileNameOnly}";

        // Kalau user upload file baru
        if ($request->hasFile('file_arsip')) {
            $file = $request->file('file_arsip');

            // Hapus file lama kalau ada
            if ($oldFilePath && \Storage::exists("public/upload/{$oldFilePath}")) {
                \Storage::delete("public/upload/{$oldFilePath}");
            }

            // Simpan file baru ke folder baru
            $newFileName = now()->format('YmdHis') . '-' . str_replace(' ', '-', $file->getClientOriginalName());
            $file->storeAs($newFolder, $newFileName);

            $validateData['file_arsip'] = "{$struktural}/{$detailName}/{$jenis}/{$tahun}/{$newFileName}";
        }
        // Kalau tidak upload file baru tapi struktur berubah
        else {
            if (
                $request->jenis_id != $arsip->jenis_id ||
                $request->tahun != $arsip->tahun ||
                $request->id_pencipta_arsip != $arsip->id_pencipta_arsip
            ) {
                $oldFullPath = storage_path("app/public/upload/{$oldFilePath}");
                $newFullPath = storage_path("app/{$newFolder}/{$fileNameOnly}");

                // Pastikan folder baru ada
                if (!\File::exists(dirname($newFullPath))) {
                    \File::makeDirectory(dirname($newFullPath), 0777, true, true);
                }

                // Pindahkan file ke folder baru
                if (\File::exists($oldFullPath)) {
                    \File::move($oldFullPath, $newFullPath);
                }

                $validateData['file_arsip'] = $newRelativePath;
            }
        }

        // Update data arsip
        $arsip->update($validateData);

        // Log aktivitas
        \App\Models\ArsipLog::create([
            'arsip_id'   => $arsip->id,
            'user_id'    => auth()->id(),
            'aksi'       => 'update',
            'keterangan' => 'Mengubah arsip: ' . $arsip->uraian_arsip,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('arsip.data')->with('success', 'Data berhasil diubah');
    }


    public function destroy(Arsip $arsip)
    {
        // 🔹 Ambil info file & relasi
        $filePathDB = $arsip->file_arsip; // contoh: Bappeda/Bidang-Tata-Ruang/Klasifikasi-Keuangan/2025/file.pdf
        $fileName = basename($filePathDB);

        // 🔹 Ambil info struktural, detail, jenis, tahun (biar konsisten)
        $detail = \App\Models\Struktural_detail::with('struktural')->find($arsip->id_pencipta_arsip);
        $struktural = $detail->struktural->name ?? 'Tanpa-Struktural';
        $detailName = $detail->name ?? 'Tanpa-Detail';
        $jenis = \App\Models\Jenis::find($arsip->jenis_id)->name ?? 'Tanpa-Jenis';
        $tahun = $arsip->tahun;

        // 🔹 Normalisasi nama folder (spasi → "-")
        $clean = fn($text) => preg_replace('/[^A-Za-z0-9\-\s]/', '', str_replace(' ', '-', trim($text)));
        $struktural = $clean($struktural);
        $detailName = $clean($detailName);
        $jenis = $clean($jenis);

        // 🔹 Bangun path file penuh
        $filePath = storage_path("app/public/upload/{$struktural}/{$detailName}/{$jenis}/{$tahun}/{$fileName}");

        // 🔹 Catat log dulu sebelum delete
        \App\Models\ArsipLog::create([
            'arsip_id'   => $arsip->id,
            'user_id'    => auth()->id(),
            'aksi'       => 'hapus',
            'keterangan' => 'Menghapus arsip: ' . $arsip->uraian_arsip,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // 🔹 Hapus file dari storage kalau ada
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // 🔹 Hapus record dari database
        $arsip->delete();

        return redirect()->route('arsip.data')->with('success', "Arsip '{$arsip->uraian_arsip}' berhasil dihapus.");
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
