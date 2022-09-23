<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel 5.8 - Individual Column Search in Datatables using Ajax</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>

<body>
    <div class="container">
        <br />
        <h3 align="center">Laravel 5.8 - Custom Search in Datatables using Ajax</h3>
        <br />
        <br />
        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4">
                <div class="form-group">
                    <select name="filter_tahun" id="filter_tahun" class="form-control" required>
                        <option value="">Pilih Tahun</option>
                        @for ($i = 1985; $i <= date("Y"); $i++) <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                    </select>
                </div>
                <div class="form-group">
                    <select name="filter_jenis" id="filter_jenis" class="form-control" required>
                        <option value="">Pilih Jenis</option>
                        @foreach($allArsip as $arsip)

                        <option value="{{ $arsip->jenis->id }}">{{ $arsip->jenis->name }}</option>

                        @endforeach
                    </select>
                </div>

                <div class="form-group" align="center">
                    <button type="button" name="filter" id="filter" class="btn btn-info">Filter</button>

                    <button type="button" name="reset" id="reset" class="btn btn-default">Reset</button>
                </div>
            </div>
            <div class="col-md-4"></div>
        </div>
        <br />
        <div class="table-responsive">
            <table id="customer_data" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No Berkas</th>
                        <th>Lokasi Arsip</th>
                        <th>Tahun</th>
                        <th>Jenis</th>
                        <th>Pencipta</th>
                        <th>Uraian</th>
                    </tr>
                </thead>
            </table>
        </div>
        <br />
        <br />
    </div>
</body>

</html>

<script>
    $(document).ready(function(){

    fill_datatable();

    function fill_datatable(filter_tahun = '', filter_jenis = '')
    {
        var dataTable = $('#customer_data').DataTable({
            processing: true,
            serverSide: true,
            ajax:{
                url: "{{ route('coba.index') }}",
                data:{filter_tahun:filter_tahun, filter_jenis:filter_jenis}
            },
            columns: [
                {
                    data:'no_berkas',
                    name:'no_berkas'
                },
                {
                    data:'lokasi_arsip',
                    name:'lokasi_arsip'
                },
                {
                    data:'tahun',
                    name:'tahun'
                },
                {
                    data:'jenis.name',
                    name:'jenis.name'
                },
                {
                    data:'pencipta_arsip',
                    name:'pencipta_arsip'
                },
                {
                    data:'uraian_arsip',
                    name:'uraian_arsip'
                }
            ]
        });
    }

    $('#filter').click(function(){
        var filter_tahun = $('#filter_tahun').val();
        var filter_jenis = $('#filter_jenis').val();

        if(filter_tahun != '' &&  filter_jenis != '')
        {
            $('#customer_data').DataTable().destroy();
            fill_datatable(filter_tahun, filter_jenis);
        }
        else
        {
            alert('Select Both filter option');
        }
    });

    $('#reset').click(function(){
        $('#filter_tahun').val('');
        $('#filter_jenis').val('');
        $('#customer_data').DataTable().destroy();
        fill_datatable();
    });

});
</script>