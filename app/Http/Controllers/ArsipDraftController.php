<?php

namespace App\Http\Controllers;

use App\Models\Jenis;
use App\Models\ArsipDraft;
use App\Models\JenisArsip;
use App\Models\Struktural;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\ArsipDraftUpload;
use App\Models\Struktural_detail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;

class ArsipDraftController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $isAdmin = $user->roles->pluck('name')->contains(fn($r) => in_array($r, ['super admin', 'admin']));

        if ($request->ajax()) {
            $columns = [
                0 => 'id',
                1 => 'uraian_arsip',
                2 => 'tahun',
                3 => 'no_box',
                4 => 'no_berkas',
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

            $query = ArsipDraft::select([
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

            // 🔹 Filter user jika bukan admin
            if (!$isAdmin) {
                $query->where('id_pencipta_arsip', $user->struktural_detail_id);
            }

            // 🔹 Filter pencarian
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

            // Hitung total dan data filter
            $totalData = ArsipDraft::count();
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

                // 🔹 Tombol aksi (sementara disesuaikan dengan arsip_draft)
                $nestedData['action'] = '
                    <div class="d-flex gap-1">
                        <a href="'.route('arsip_draft.upload', $arsip->id).'" class="btn btn-outline-secondary btn-sm mr-1" title="Upload File">
                            <i class="fa fa-upload"></i>
                        </a>
                        
                        <a href="'.route('arsip_draft.show', $arsip->id).'" class="btn btn-outline-primary btn-sm mr-1" title="Lihat Detail Arsip"><i class="fa fa-eye"></i></a>

                        <a href="'.route('arsip_draft.edit', $arsip->id).'" class="btn btn-outline-secondary btn-sm mr-1" title="Edit Data">
                            <i class="fa fa-edit"></i>
                        </a>

                        <button type="button" class="btn btn-outline-secondary btn-sm btn-delete" 
                            data-id="'.$arsip->id.'" 
                            title="Hapus Arsip">
                            <i class="fa fa-trash"></i>
                        </button>

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

        return view('arsip_draft.index');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
        return view('arsip_draft.create', compact('user', 'datas','jeniss' , 'jenis_arsip', 'models'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'jenis_arsip_id' => 'required',
            'jenis_id' => 'required',
            'id_pencipta_arsip' => 'required',
            'lokasi_arsip' => 'required',
            'no_berkas' => 'required',
            'no_box' => 'required',
            'tahun' => 'required',
            'uraian_arsip' => 'required',
        ]);

        $arsipDraft = ArsipDraft::create([
            'jenis_arsip_id'    => $request->jenis_arsip_id,
            'jenis_id'          => $request->jenis_id,
            'id_pencipta_arsip' => $request->id_pencipta_arsip,
            'lokasi_arsip'      => $request->lokasi_arsip,
            'no_berkas'         => $request->no_berkas,
            'no_box'            => $request->no_box,
            'tahun'             => $request->tahun,
            'uraian_arsip'      => $request->uraian_arsip,
            'user_id'           => Auth::id(),
            'status'            => 0,
        ]);

        // Setelah simpan, arahkan ke halaman upload file untuk arsip ini
        return redirect()
            ->route('arsip_draft.upload', $arsipDraft->id)
            ->with('success', 'Data arsip berhasil disimpan. Silakan upload file arsip.');
    }
    

    public function upload(ArsipDraft $arsipDraft)
    {
        return view('arsip_draft.upload', compact('arsipDraft'));
    }


    public function uploadTmp(Request $request)
    {
        try {
            $receiver = new FileReceiver("file", $request, HandlerFactory::classFromRequest($request));

            if (!$receiver->isUploaded()) {
                return response()->json(['success' => false, 'message' => 'No file uploaded'], 400);
            }

            $save = $receiver->receive();

            if ($save->isFinished()) {
                $file = $save->getFile();

                // 🔹 Gunakan timestamp ringan dan nama yang rapi
                $timestamp = now()->format('YmdHis');
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();

                $namaFile = "{$timestamp}-" . Str::slug($originalName) . ".{$extension}";

                // simpan ke tmp/
                $path = $file->storeAs('tmp', $namaFile);
                @unlink($file->getPathname());

                return response()->json([
                    'success' => true,
                    'tmp_path' => $path,
                    'file_name' => $namaFile
                ]);
            }

            $handler = $save->handler();
            return response()->json(['success' => true, 'done' => $handler->getPercentageDone()]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'line' => $e->getLine()
            ], 500);
        }
    }


    public function deleteTmp(Request $request)
    {
        $path = $request->tmp_path;
        if ($path && Storage::exists($path)) {
            Storage::delete($path);
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }



    public function storeUpload(Request $request, ArsipDraft $arsipDraft)
    {
        $request->validate([
            'tmp_path' => 'required|string',
            'no_item' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        // 🔹 Pastikan file sementara ada
        if (!\Storage::exists($request->tmp_path)) {
            return response()->json(['success' => false, 'message' => 'File temporary tidak ditemukan.']);
        }

        // 🔹 Ambil relasi struktural
        $detail = $arsipDraft->struktural_detail;
        $strukturUtama  = str_replace([' ', '/', '\\'], '-', optional($detail->struktural)->name ?? 'Unknown');
        $strukturDetail = str_replace([' ', '/', '\\'], '-', $detail->name ?? 'Unknown-Detail');
        $tahun          = $arsipDraft->tahun ?? date('Y');
        $jenisName      = str_replace([' ', '/', '\\'], '-', optional($arsipDraft->jenis)->name ?? 'Umum');

        // 🔹 Tentukan folder tujuan
        $basePath = "upload/{$strukturUtama}/{$strukturDetail}/{$tahun}/{$jenisName}";

        // 🔹 Pastikan foldernya ada
        \Storage::makeDirectory($basePath);

        // 🔹 Ambil nama file asli dari tmp_path
        $fileName = basename($request->tmp_path);
        $finalPath = "{$basePath}/{$fileName}";

        // 🔹 Pindahkan dari tmp ke folder final
        \Storage::move($request->tmp_path, $finalPath);

        // 🔹 Simpan ke database
        $upload = ArsipDraftUpload::create([
            'arsip_draft_id' => $arsipDraft->id,
            'file_path'      => $finalPath,
            'no_item'        => $request->no_item,
            'keterangan'     => $request->keterangan,
        ]);

        // 🔹 Balikan respon JSON
        return response()->json([
            'success' => true,
            'message' => '✅ File berhasil disimpan ke folder final!',
            'file' => [
                'id'           => $upload->id,
                'file_name'    => $fileName,
                'download_url' => route('arsip_draft.download', $upload->id),
                'path'         => $finalPath,
            ]
        ]);
    }




    public function getUploads(ArsipDraft $arsipDraft)
    {
        $uploads = $arsipDraft->uploads()->latest()->get()->map(function ($file) {
            return [
                'id'          => $file->id,
                'file_name'   => basename($file->file_path),
                'no_item'     => $file->no_item,
                'keterangan'  => $file->keterangan,
                'uploaded_at' => $file->created_at->format('d-m-Y H:i'),
                'download_url'=> route('arsip_draft.download', $file->id),
            ];
        });

        return response()->json(['files' => $uploads]);
    }


    public function download(ArsipDraftUpload $upload)
    {
        if (!Storage::exists($upload->file_path)) {
            return abort(404, 'File tidak ditemukan.');
        }

        return Storage::download($upload->file_path, basename($upload->file_path));
    }


    // 🗑️ Hapus file upload
    public function deleteUpload(ArsipDraftUpload $upload)
    {
        if (\Storage::exists($upload->file_path)) {
            \Storage::delete($upload->file_path);
        }
        $upload->delete();

        return response()->json([
            'success' => true,
            'message' => 'File berhasil dihapus.'
        ]);
    }

    // ✏️ Update data file upload
    public function updateUpload(Request $request, ArsipDraftUpload $upload)
    {
        $request->validate([
            'no_item' => 'nullable|string|max:50',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $upload->update([
            'no_item' => $request->no_item,
            'keterangan' => $request->keterangan,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data file berhasil diperbarui.'
        ]);
    }



    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ArsipDraft  $arsipDraft
     * @return \Illuminate\Http\Response
     */
    public function show(ArsipDraft $arsip)
    {
        dd($arsip);
        $user = Auth::user();

        // 🔹 Cek role super admin atau admin
        $isAdmin = $user->hasAnyRole(['super admin', 'admin']);

        // 🔒 Jika bukan admin, hanya boleh lihat arsip miliknya
        if (!$isAdmin && $arsip->id_pencipta_arsip !== $user->struktural_detail_id) {
            abort(403, 'Anda tidak memiliki izin untuk melihat arsip ini.');
        }

        // 🔹 Ambil data arsip lengkap dengan relasi
        $data = ArsipDraft::with([
            'user:id,name',
            'jenis:id,name',
            'jenis_arsip:id,name',
            'struktural_detail:id,name,struktural_id',
            'struktural_detail.struktural:id,name',
            'uploads:id,arsip_draft_id,file_name,no_item,keterangan,created_at'
        ])->findOrFail($arsip->id);

        // 🔹 Ambil informasi struktural via relasi
        $struktural = [
            'struktural_detail' => optional($data->struktural_detail)->name,
            'struktural' => optional($data->struktural_detail->struktural)->name,
        ];

        // 🔹 Ambil daftar file upload (relasi `uploads`)
        $uploads = $data->uploads ?? collect();

        return view('arsip_draft.detail', compact('data', 'struktural', 'uploads', 'isAdmin'));
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ArsipDraft  $arsipDraft
     * @return \Illuminate\Http\Response
     */
    public function edit(ArsipDraft $arsipDraft)
    {
        $jeniss = Jenis::all();
        $jenis_arsip = JenisArsip::all();
        $strukturals = Struktural_detail::with('struktural')->get();
        $models = $strukturals->groupBy('struktural.name');

        return view('arsip_draft.edit', compact('arsipDraft', 'jeniss', 'jenis_arsip', 'models'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ArsipDraft  $arsipDraft
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ArsipDraft $arsipDraft)
    {
        $request->validate([
            'jenis_arsip_id'    => 'required|integer',
            'jenis_id'          => 'required|integer',
            'id_pencipta_arsip' => 'required|integer',
            'lokasi_arsip'      => 'required|string|max:255',
            'no_berkas'         => 'required|string|max:50',
            'no_box'            => 'required|string|max:50',
            'tahun'             => 'required|integer',
            'uraian_arsip'      => 'required|string|max:255',
        ]);

        // 🧩 Simpan nilai lama dulu untuk deteksi perubahan
        $oldTahun = $arsipDraft->tahun;
        $oldJenis = optional($arsipDraft->jenis)->name;
        $oldDetail = $arsipDraft->struktural_detail;
        $oldStrukturUtama = str_replace([' ', '/', '\\'], '-', optional($oldDetail->struktural)->name ?? 'Unknown');
        $oldStrukturDetail = str_replace([' ', '/', '\\'], '-', $oldDetail->name ?? 'Unknown-Detail');

        // 🧩 Update data utama arsip draft
        $arsipDraft->update([
            'jenis_arsip_id'    => $request->jenis_arsip_id,
            'jenis_id'          => $request->jenis_id,
            'id_pencipta_arsip' => $request->id_pencipta_arsip,
            'lokasi_arsip'      => $request->lokasi_arsip,
            'no_berkas'         => $request->no_berkas,
            'no_box'            => $request->no_box,
            'tahun'             => $request->tahun,
            'uraian_arsip'      => $request->uraian_arsip,
            'updated_by'        => Auth::id(),
        ]);

        // 🔄 Refresh relasi biar data baru ke-load
        $arsipDraft->refresh();

        // 🔹 Ambil relasi baru setelah refresh
        $newDetail = $arsipDraft->struktural_detail;
        $newStrukturUtama  = str_replace([' ', '/', '\\'], '-', optional($newDetail->struktural)->name ?? 'Unknown');
        $newStrukturDetail = str_replace([' ', '/', '\\'], '-', $newDetail->name ?? 'Unknown-Detail');
        $newTahun          = $arsipDraft->tahun ?? date('Y');
        $newJenisName      = str_replace([' ', '/', '\\'], '-', optional($arsipDraft->jenis)->name ?? 'Umum');

        // 🔹 Tentukan base path baru
        $newBasePath = "upload/{$newStrukturUtama}/{$newStrukturDetail}/{$newTahun}/{$newJenisName}";

        // Pastikan folder baru dibuat di disk default/public
        if (!Storage::exists($newBasePath)) {
            Storage::makeDirectory($newBasePath, 0775, true);
        }

        // 🔹 Jika tahun, jenis, atau pencipta berubah → pindahkan file
        if ($oldTahun != $newTahun || $oldJenis != $newJenisName || $oldStrukturDetail != $newStrukturDetail) {
            foreach ($arsipDraft->uploads as $upload) {
                $oldPath = $upload->file_path;
                if (Storage::exists($oldPath)) {
                    $fileName = basename($oldPath);
                    $newPath = "{$newBasePath}/{$fileName}";

                    // 🚚 Pindahkan file fisik
                    Storage::move($oldPath, $newPath);

                    // 📝 Update path di database
                    $upload->update(['file_path' => $newPath]);
                }
            }
        }

        return redirect()
            ->route('arsip_draft.index')
            ->with('success', '✅ Data arsip berhasil diperbarui dan file berhasil dipindahkan ke folder baru.');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ArsipDraft  $arsipDraft
     * @return \Illuminate\Http\Response
     */
    public function destroy(ArsipDraft $arsipDraft)
    {
        try {
            // 🔹 Ambil semua file upload yang terkait
            $uploads = $arsipDraft->uploads; // pastikan relasi ArsipDraft -> ArsipDraftUpload sudah ada

            foreach ($uploads as $upload) {
                if (\Storage::exists($upload->file_path)) {
                    \Storage::delete($upload->file_path); // hapus file fisik
                }
                $upload->delete(); // hapus dari database
            }

            // 🔹 Hapus arsip utama
            $arsipDraft->delete();

            return response()->json([
                'success' => true,
                'message' => '🗑️ Arsip draft dan seluruh file terkait berhasil dihapus!'
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus arsip: ' . $e->getMessage()
            ], 500);
        }
    }

}
