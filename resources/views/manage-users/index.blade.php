@extends("layouts.main")

<x-datatables.required/>

@section('content')
    @include('components.content-title', ['active' => 'Accounts', 'menus' => ['Accounts']])
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header card-no-border">
                    <a class="btn btn-primary"
                       style="float:right;"
                       type="button"
                       href="{{ route('manage-account.create') }}">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             width="16" height="16" viewBox="0 0 24 24"
                             class="icon feather feather-plus-circle" style="width: 16px;vertical-align: middle;">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="16"></line>
                            <line x1="8" y1="12" x2="16" y2="12"></line>
                        </svg>
                        Buat Baru
                    </a>
                    <h4 class="card-title mb-0">Accounts</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive add-project custom-scrollbar">
                        <table class="table card-table table-bordered table-striped" id="datatable">
                            <thead>
                            <tr class="table-success">
                                <th class="text-center" style="width: 10px;">No</th>
                                <th style="width: 120px;">Username</th>
                                <th>Name</th>
                                <th>Roles</th>
                                <th style="width: 80px;">Status</th>
                                <th style="width: 145px;"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $index = 1;
                            @endphp
                            @foreach($accounts as $account)
                                @if($account->hasRole('SuperVisor') && !(auth()->user()->hasRole('SuperVisor')))
                                    @continue
                                @endif
                                <tr>
                                    <td class="text-center"></td>
                                    <td>{{ $account->username }}</td>
                                    <td>{{ $account->name }}</td>
                                    <td>
                                        @foreach($account->roles as $role)
                                            {{ $role->name. ', ' }}
                                        @endforeach
                                    </td>
                                    <td>
                                        @if($account->is_active)
                                            <span class="badge badge-success rounded-pill p-2">Active</span>
                                        @else
                                            <span class="badge badge-danger rounded-pill p-2">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a class="btn btn-primary btn-sm"
                                           href="{{ route('manage-account.detail', $account->id) }}">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 width="16" height="16"
                                                 viewBox="0 0 24 24"
                                                 class="icon feather feather-arrow-right-circle"
                                                 style="width: 16px;vertical-align: middle;">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <polyline points="12 16 16 12 12 8"></polyline>
                                                <line x1="8" y1="12" x2="16" y2="12"></line>
                                            </svg>
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('yield-js')
    <x-datatables.handler/>
@endsection
