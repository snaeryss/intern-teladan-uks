@extends("layouts.main")

<x-sweet-alert2.required/>

@section('content')
    @include('components.content-title', ['active' => 'Artisan', 'menus' => ['Artisan']])
    <div class="row">
        <div class="col-lg-12">
            <div class="card m-b-30">
                <div class="card-body">
                    <h4 class="mt-0 header-title">
                        Request Artisan Command
                    </h4>
                    <form action="{{ route('artisan.do-command') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Choose Command</label>
                                <select name="command" class="form-control">
                                    <option value="cache:clear">
                                        Clear App Cache
                                    </option>
                                    <option value="permission:cache-reset">
                                        Clear Spatie Permission
                                    </option>
                                    <option value="optimize:clear">
                                        Optimize And Clear
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary waves-effect waves-light">
                                <i class="fa fa-terminal"></i> Do Command
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('yield-js')
    <x-sweet-alert2.handler/>
@endsection