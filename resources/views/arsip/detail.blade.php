@extends('adminlte::page')

@section('title', 'Detail Arsip')

@section('content')

@section('styles')
<style>
    #t {
        font-weight: bold;
    }
</style>
@endsection
<div class="col-12">
    <div class="card mb-4">
        <div class="card-header bg-dark">Detail </div>
        <div class="card-body">
            <table class="table table-striped">
                <tbody>
                    <tr>
                        <td id="t" class="col-sm-3">Jenis Arsip</td>
                        <td>:</td>
                        <td>{{ $data->jenis_arsip->name }}</td>
                    </tr>
                    {{-- <tr>
                        <td id="t">Judul Arsip</td>
                        <td>:</td>
                        <td>{{ $data->judul_arsip }}</td>
                    </tr> --}}
                    <tr>
                        <td id="t">No. Berkas</td>
                        <td>:</td>
                        <td>{{ $data->no_berkas }}</td>
                    </tr>
                    <tr>
                        <td id="t">No. Box</td>
                        <td>:</td>
                        <td>{{ $data->no_box }}</td>
                    </tr>
                    <tr>
                        <td id="t">Jenis Klasifikasi</td>
                        <td>:</td>
                        <td>{{ $data->jenis->name }}</td>
                    </tr>
                    <tr>
                        <td id="t">Lokasi Arsip</td>
                        <td>:</td>
                        <td>{{ $data->lokasi_arsip }}</td>
                    </tr>
                    <tr>
                        <td id="t">Tahun Arsip</td>
                        <td>:</td>
                        <td>{{ $data->tahun }}</td>
                    </tr>
                    <tr>
                        <td id="t">Pencipta Arsip</td>
                        <td>:</td>
                        <td>{{ $struktural[0]->struktural_detail }}</td>
                    </tr>
                    <tr>
                        <td id="t">Uraian</td>
                        <td>:</td>
                        <td>{{ $data->uraian_arsip }}</td>
                    </tr>
                    <tr>
                        <td id="t">File Unggah</td>
                        <td>:</td>
                        <td>{{ $data->file_arsip }} <a href="{{ route('arsip.download', $data) }}"
                                class="btn btn-sm btn-success ml-2" xdata-toggle="tooltip" data-placement="top"
                                title="Download"><i class="fa fa-download" aria-hidden="true"></i></a></td>
                    </tr>
                    <tr>
                        <td id="t">Struktural</td>
                        <td>:</td>
                        <td>{{ $struktural[0]->struktural.' / '.$struktural[0]->struktural_detail }}</td>
                    </tr>
                    @if (Auth::user()->hasRole('super admin') || Auth::user()->hasRole('admin'))
                    <tr>
                        <td id="t">User Pengunggah</td>
                        <td>:</td>
                        <td>{{ $data->user->name." (".implode($data->user->getRoleNames()->toarray()).")" }}</td>
                    </tr>
                    @endif
                </tbody>
            </table>
            <button class="btn btn-primary btn-sm mt-3" onclick="history.back()">Kembali</button>
        </div>
    </div>
</div>
@endsection