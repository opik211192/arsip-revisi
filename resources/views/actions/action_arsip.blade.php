<div class="d-flex">
    <a class="btn btn-sm btn-primary" href="{{ route('arsip.detail', $id) }}">
        Detail</a>&nbsp;
    <a class="btn btn-sm btn-warning" href="{{ route('arsip.edit', $id) }}">
        Edit</a>&nbsp;

    @can('delete arsip')
    <form action="{{ route('arsip.delete', $id) }}" method="post">
        @csrf
        @method('DELETE')
        <button class="btn btn-sm btn-danger" type="submit" onclick="return confirm('Hapus data ini ?')"><i
                class="glyphicon glyphicon-trash"></i>Hapus</button>
    </form>&nbsp;
    @endcan

    <a href="{{ route('arsip.download', $id) }}" class="btn btn-success btn-sm" data-toggle="tooltip"
        data-placement="top" title="Download file"><i class="fa fa-download" aria-hidden="true"></i></a>&nbsp;

    {{-- @if (Auth::user()->roles->pluck('name')->contains('super admin') ||
    Auth::user()->roles->pluck('name')->contains('admin')) --}}
    {{-- <a href="{{ route('arsip.approval', $id) }}">tes</a> --}}

    {{-- @endif --}}
</div>