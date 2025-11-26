@extends("layouts.main")

<x-sweet-alert2.required/>

@section('scripts-css')
@endsection

@section('content')
    @include('components.content-title', ['active' => 'Students', 'menus' => ['Syncs', 'Students']])
    <div class="modal fade"
         id="modal-create"
         tabindex="-1"
         role="dialog"
         aria-labelledby="modal-create"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Synchronizing Students</h5>
                    <button class="btn-close"
                            type="button"
                            data-bs-dismiss="modal"
                            aria-label="Close">
                    </button>
                </div>
                <form action="{{ route('sync-students.store') }}" method="POST">
                    <div class="modal-body">
                        @csrf
                        <div class="row custom-input">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <input type="hidden" name="admin"
                                           value="{{ auth()->user()->getAuthIdentifier() }}"/>
                                    <label class="form-label">Admin</label>
                                    <input class="form-control"
                                           type="text"
                                           value="{{ auth()->user()->name }}"
                                           disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary"
                                type="button"
                                data-bs-dismiss="modal">
                            <i class="fa fa-close"></i>
                            Close
                        </button>
                        <button class="btn btn-success btn-submit"
                                type="submit">
                            <i class="fa fa-save"></i>
                            Sync Now
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header card-no-border pb-0">
                    <button class="btn btn-primary"
                            style="float:right;"
                            type="button"
                            data-bs-toggle="modal"
                            data-bs-target="#modal-create">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             width="16" height="16" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-plus-circle" style="width: 16px;vertical-align: middle;">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="16"></line>
                            <line x1="8" y1="12" x2="16" y2="12"></line>
                        </svg>
                        Synchronizing
                    </button>
                    <h4 class="card-title mb-0">
                        Student Synchronize Histories
                    </h4>
                </div>
                <div class="card-body pt-2">
                    <div class="table-responsive add-project custom-scrollbar">
                        <table class="table card-table table-bordered table-striped">
                            <thead>
                            <tr class="table-success">
                                <th class="text-center" style="width: 10px;">No</th>
                                <th>User</th>
                                <th>New</th>
                                <th>Skipped</th>
                                <th>Updated</th>
                                <th>Waktu</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($histories as $index => $history)
                                <tr>
                                    <td><b>{{ $index+=1 }}</b></td>
                                    <td>{{ $history->user ? $history->user->name : 'User Deleted' }}</td>
                                    <td>{{ $history->new }}</td>
                                    <td>{{ $history->skipped }}</td>
                                    <td>{{ $history->updated }}</td>
                                    <td>{{ $history->created_at }}</td>
                                </tr>
                            @endforeach
                            @if(count($histories) == 0)
                                <tr>
                                    <td class="text-center" colspan="6">
                                        :: tidak ada data ::
                                    </td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('yield-js')
    <x-sweet-alert2.handler/>
    <x-prevent-force-submit/>
@endsection
