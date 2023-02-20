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
                        <td><a href="{{ route('arsip.download', $data) }}" class="btn btn-sm btn-success ml-2"
                                xdata-toggle="tooltip" data-placement="top" title="Download">Download <i
                                    class="fa fa-download" aria-hidden="true"></i></a></td>
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
                    <tr>
                        <td id="t">Status</td>
                        <td>:</td>
                        @if ($data->status == 0)
                        <td>
                            <div class="badge badge-warning">Menunggu Konfirmasi</div>
                        </td>
                        @elseif ($data->status == 1)
                        <td>
                            <div class="badge badge-success">Disetujui</div>
                        </td>
                        @elseif ($data->status == 2)
                        <td>
                            <div class="badge badge-danger">Koreksi</div>
                        </td>
                        @endif
                    </tr>
                    @endif
                    @if (empty($data->keterangan))

                    @else
                    <tr>
                        <td><strong><i>Keterangan</i></strong></td>
                        <td>:</td>
                        <td style="color: red"><strong><i>{{ $data->keterangan }}</i></strong></td>
                    </tr>
                    @endif
                </tbody>
            </table>

            @if (Auth::user()->hasRole('super admin') || Auth::user()->hasRole('admin'))
            <button type="button" id="approval" class="btn btn-secondary btn-sm mt-3" data-toggle="modal"
                data-target="#exampleModal">
                Approval
            </button>
            @endif
            <a href="{{ route('arsip.data') }}" class="btn btn-primary btn-sm mt-3">Kembali</a>
            {{-- <button class="btn btn-primary btn-sm mt-3" onclick="history.back()">Kembali</button> --}}
        </div>
    </div>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form name="frm_edit" id="frm_edit" class="form-horizontal" action="{{ route('arsip.approval', $data) }}"
            method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Form Persetujuan Arsip</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="status1" value="0" {{
                            (old('status') ?? $data->status) == '0' ? 'checked' : '' }}>
                        <label for="status1" class="form-check-label">Menunggu Konfirmasi</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="status2" value="1" {{
                            (old('status') ?? $data->status) == '1' ? 'checked' : '' }}>
                        <label for="status2" class="form-check-label">Disetujui</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="status3" value="2" {{
                            (old('status') ?? $data->status) == '2' ? 'checked' : '' }}>
                        <label for="status3" class="form-check-label">Koreksi</label>
                    </div>
                    <div>
                        <textarea class="form-control mt-2" name="keterangan" id="keterangan" cols="10" rows="2"
                            placeholder="Keterangan Koreksi..."
                            required>{{ old('keterangan') ?? $data->keterangan }}</textarea>
                    </div>
                    <div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button onclick="form_submit()" id="btnSubmit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
        </form>
    </div>
</div>
@endsection
@push('scripts')
<script>
    $(document).ready(function(){
        
       if($('input[name="status"]:checked').val() == '0' || $('input[name="status"]:checked').val() == '1' ) {
    
            $('#keterangan').attr('hidden',true);
        }
        
        $('#approval').on('click', function(){
            if($('input[name="status"]:checked').val() == '0' || $('input[name="status"]:checked').val() == '1' ) {
            
                $('#keterangan').attr('hidden',true);
            }
            // var value = $('input[name="status"]:checked').val();
            // if (value === '2') {
            //     $('#keterangan').attr('hidden',false);
            // }else{
            //      $('#keterangan').attr('hidden',true);
            // }
        });
        
       //$('#keterangan').attr('hidden',true);
        $('input[type=radio][name=status]').change(function(){
            if(this.value == '0'){
                $('#keterangan').attr('hidden',true);
                $('#keterangan').val('');
            }else if (this.value == '1'){
                $('#keterangan').attr('hidden',true);
                $('#keterangan').val('');
            }else if(this.value == '2'){
              
                $('#keterangan').attr('hidden',false); 
            }
        }); 
        
        function form_submit() {
             document.getElementById('frm_edit').submit();
        }

        $('#btnSubmit').on('click', function(){
               if($('input[name="status"]:checked').val() == '2' && $('#keterangan').val() === '' ) {
                   alert('keterangan koreksi harus di isi!');
                   return false
                }

                form_submit();

              
            });
        
       
       
    })
    
</script>
@endpush