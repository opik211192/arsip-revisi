<div class="d-flex">
    {{-- Detail --}}
    <a class="btn btn-sm btn-primary" href="{{ route('arsip.detail', $id) }}" data-toggle="tooltip" title="Detail">
        <i class="fa fa-eye"></i>
    </a>&nbsp;

    {{-- Edit --}}
    <a class="btn btn-sm btn-warning" href="{{ route('arsip.edit', $id) }}" data-toggle="tooltip" title="Edit">
        <i class="fa fa-edit"></i>
    </a>&nbsp;

    {{-- Hapus --}}
    @can('delete arsip')
    <form action="{{ route('arsip.delete', $id) }}" method="post" onsubmit="return confirm('Hapus data ini ?')">
        @csrf
        @method('DELETE')
        <button class="btn btn-sm btn-danger" type="submit" data-toggle="tooltip" title="Hapus">
            <i class="fa fa-trash"></i>
        </button>
    </form>&nbsp;
    @endcan

    {{-- Download --}}
    <a href="{{ route('arsip.download', $id) }}" class="btn btn-success btn-sm" data-toggle="tooltip" title="Download">
        <i class="fa fa-download"></i>
    </a>
    {{-- @if (Auth::user()->roles->pluck('name')->contains('super admin') ||
    Auth::user()->roles->pluck('name')->contains('admin')) --}}
    {{-- <a href="{{ route('arsip.approval', $id) }}">tes</a> --}}

    {{-- @endif --}}
</div>