<div class="page-title">
    <div class="row">
        <div class="col-sm-6">
            <h3>{{ $active }}</h3>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb">
                @foreach($menus as $menu)
                    <li class="breadcrumb-item {{ $active == $menu ? 'active' : '' }}">
                        {{ $menu }}
                    </li>
                @endforeach
            </ol>
        </div>
    </div>
</div>
