<?php

namespace App\Http\Controllers;

use App\Models\Jenis;
use App\Models\ArsipDraft;
use App\Models\JenisArsip;
use App\Models\Struktural;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\ArsipDraftUpload;
use App\Models\Struktural_detail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

            $query = ArsipDraft::select([
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

            // 🔹 Filter user jika bukan admin
            if (!$isAdmin) {
                $query->where('id_pencipta_arsip', $user->struktural_detail_id);
            }

            // 🔹 Filter pencarian
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
                $nestedData['no_berkas'] = $arsip->no_berkas ?? '-';
                $nestedData['no_box'] = $arsip->no_box ?? '-';
                $nestedData['jenis'] = $arsip->jenis->name ?? '-';
                $nestedData['jenis_arsip'] = $arsip->jenis_arsip->name ?? '-';
                $nestedData['pencipta'] = $arsip->struktural_detail->name ?? '-';
                $nestedData['created_by'] = $arsip->createdBy->name ?? '-';
                $nestedData['updated_by'] = $arsip->updatedBy->name ?? '-';
                $nestedData['updated_at'] = $arsip->updated_at ? $arsip->updated_at->format('Y-m-d H:i') : '-';
                $nestedData['status'] = $arsip->status == 0 ? '<span class="badge bg-secondary">Draft</span>' : '<span class="badge bg-success">Final</span>';

                // 🔹 Tombol aksi (sementara disesuaikan dengan arsip_draft)
                $nestedData['action'] = '
                    <div class="d-flex gap-1">
                        <a href="'.route('arsip_draft.upload', $arsip->id).'" class="btn btn-success btn-sm" title="Upload File">
                            <i class="fa fa-upload"></i>
                        </a>

                        <a href="'.route('arsip_draft.edit', $arsip->id).'" class="btn btn-warning btn-sm" title="Edit Data">
                            <i class="fa fa-edit"></i>
                        </a>

                        <form action="'.route('arsip_draft.destroy', $arsip->id).'" method="POST" 
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

    // public function uploadTmp(Request $request)
    // {
    //     $request->validate([
    //         'file_arsip' => 'required|file|max:51200|mimes:pdf,doc,docx,xls,xlsx'
    //     ]);

    //     $file = $request->file('file_arsip');
    //     $filename = now()->format('YmdHis') . '-' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();

    //     // simpan ke tmp dulu
    //     $path = $file->storeAs('tmp', $filename);

    //     return response()->json([
    //         'success' => true,
    //         'tmp_path' => $path,
    //         'file_name' => $filename,
    //     ]);
    // }

    public function storeUpload(Request $request, ArsipDraft $arsipDraft)
    {
        $request->validate([
            'file_arsip' => 'required|file|max:2048000|mimes:pdf,doc,docx,xls,xlsx',
            'no_item'    => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        // 🔹 Struktur folder penyimpanan
        $tahun     = $arsipDraft->tahun;
        $jenisName = str_replace([' ', '/'], '-', optional($arsipDraft->jenis)->name ?? 'Umum');
        $pencipta  = str_replace([' ', '/'], '-', optional($arsipDraft->struktural_detail)->name ?? 'Unknown');
        $basePath  = "upload/{$tahun}/{$jenisName}/{$pencipta}";

        $file = $request->file('file_arsip');
        $now  = now()->format('YmdHis');
        $namaFile = "{$now}-{$pencipta}-" . str_replace(' ', '-', $file->getClientOriginalName());

        // ✅ Simpan langsung ke storage/app/upload/{tahun}/{jenis}/{pencipta}
        $path = $file->storeAs($basePath, $namaFile);

        // 🔹 Simpan ke database
        $upload = ArsipDraftUpload::create([
            'arsip_draft_id' => $arsipDraft->id,
            'file_path'      => $path,
            'no_item'        => $request->no_item,
            'keterangan'     => $request->keterangan,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'File berhasil diupload!',
            'file' => [
                'id'          => $upload->id,
                'file_name'   => $namaFile,
                'no_item'     => $upload->no_item,
                'keterangan'  => $upload->keterangan,
                'uploaded_at' => $upload->created_at->format('d-m-Y H:i'),
                'download_url'=> route('arsip_draft.download', $upload->id),
            ],
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
    public function show(ArsipDraft $arsipDraft)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ArsipDraft  $arsipDraft
     * @return \Illuminate\Http\Response
     */
    public function edit(ArsipDraft $arsipDraft)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ArsipDraft  $arsipDraft
     * @return \Illuminate\Http\Response
     */
    public function destroy(ArsipDraft $arsipDraft)
    {
        //
    }
}
