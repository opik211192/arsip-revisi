<div>
    <div class="mb-3 mt-3">
        <div class="list-group">
            <a href="{{ route('home') }}" class="list-group-item list-group-item-action"> <i
                    class="fa fa-home mr-1"></i>Home</a>
            <a href="{{ route('dashboard') }}" class="list-group-item list-group-item-action"><i
                    class="fa fa-dashboard mr-1"></i>Dashboard</a>
        </div>
    </div>

    @can('create arsip')
    <div class="mb-3">
        <small class="d-block text-white mb-2 text-uppercase"><b>Arsip</b></small>
        <div class="list-group">
            <a href="{{ route('arsip.index') }}" class="list-group-item list-group-item-action">
                <i class="fa fa-book mr-1"></i>
                Create Arsip
            </a>
            <a href="{{ route('arsip.data') }}" class="list-group-item list-group-item-action"> <i
                    class="fa fa-table mr-1"></i>Data Table Arsip</a>
        </div>
    </div>
    @endcan

    {{-- @can('create berita')
    <div class="mb-5">
        <small class="d-block text-white mb-2 text-uppercase"><b>Berita</b></small>
        <div class="list-group">
            <a href="#" class="list-group-item list-group-item-action ">
                Create Berita
            </a>
            <a href="#" class="list-group-item list-group-item-action">Data Table Berita</a>
        </div>
    </div>
    @endcan --}}

    @can('show users')
    <div class="mb-3">
        <small class="d-block text-white mb-2 text-uppercase"><b>Users</b></small>
        <div class="list-group">
            <a href="{{ route('register') }}" class="list-group-item list-group-item-action ">
                <i class="fa fa-user mr-1"></i>
                Create User
            </a>
            <a href="{{ route('register.index') }}" class="list-group-item list-group-item-action"> <i
                    class="fa fa-users mr-1"></i>Data Table User</a>
        </div>
    </div>
    @endcan

    @can('assign permission')
    <div class="mb-4">
        <small class="d-block text-white mb-2 text-uppercase"><b>Role & Permission</b></small>
        <div class="list-group">
            <a href="{{ route('roles.index') }}" class="list-group-item list-group-item-action "> <i
                    class="fa fa-gears mr-1"></i>Roles</a>
            <a href="{{ route('permissions.index') }}" class="list-group-item list-group-item-action "> <i
                    class="fa fa-file-circle-check mr-1"></i>Permission</a>
            <a href="{{ route('assign.create') }}" class="list-group-item list-group-item-action"><i
                    class="fas fa-bullseye mr-1"></i>Assign Permission</a>
            <a href="{{ route('assign.user.create') }}" class="list-group-item list-group-item-action"><i
                    class="fa-solid fa-arrows-down-to-people mr-1"></i>Permission to Users</a>

        </div>
    </div>
    @endcan

    @can('setting')
    <div class="panel-group mb-4">
        <div class="panel panel-default">
            <small class="d-block text-white mb-2 text-uppercase"> <a class="text-white" data-toggle="collapse"
                    href="#collapse1"><b>Setting</b></a></small>
            <div id="collapse1" class="panel-collapse collapse show">
                <ul class="list-group">
                    <a class="list-group-item list-group-item-action" href="{{ route('jenis.index') }}"><i
                            class="fa fa-plus-circle mr-1" aria-hidden="true"></i>Jenis Arsip</a>
                    <a class="list-group-item list-group-item-action" href="{{ route('unit.index') }}"><i
                            class="fa fa-plus-circle mr-1" aria-hidden="true"></i>Unit</a>
                    <a class="list-group-item list-group-item-action" href="{{ route('struktural.index') }}"><i
                            class="fa fa-plus-circle mr-1" aria-hidden="true"></i>Struktural</a>
                </ul>
            </div>
        </div>
    </div>
    @endcan


    <div class="mb-3">
        <small class="d-block text-white mb-2 text-uppercase"><b>Logout</b></small>
        <div class="list-group">
            <a class="list-group-item list-group-item-action" href="{{ route('logout') }}" onclick="event.preventDefault();
                document.getElementById('logout-form').submit();">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                {{ __('Logout') }}
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </div>
</div>