@extends('layouts.admin')



@section('content')
<div class="row">
    <div class="card">
        <div id="kt_app_toolbar" class="app-toolbar pt-5">
            <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
                <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                    <div class="page-title d-flex flex-column gap-1 me-3 mb-2">
                        <h1
                            class="page-heading d-flex flex-column justify-content-center text-dark fw-bolder fs-1 lh-0">
                            All Roles</h1>
                    </div>
                    <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold">
                       
                        <li class="nav-item mt-2">
                            <a class="btn btn-primary text-white me-2" data-url="{{ route('roles.create') }}" data-title="{{ __('Add Role') }}"
                            data-size="lg" data-ajax-popup="true" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Create') }}">
                            Create New Role
                        </a>
                        </li>

                    </ul>
                </div>
            </div>
        </div>
    </div>
<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-fluid">

       
                    <div class="table-responsive">
                        <table class="table" id="example">
                            <thead>
                                <tr>
                                    <th>{{ __('Role') }}</th>
                                    <th>{{ __('Permissions') }}</th>
                                    <th width="200px">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($roles as $role)
                                    <tr>
                                        <td>{{ $role->name }}</td>
                                        <td style="white-space: inherit">
                                            @foreach ($role->permissions()->pluck('name') as $permission)
                                                <span class="badge rounded p-2 m-1 px-3 bg-primary ">
                                                    <a href="#" class="text-white">{{ $permission }}</a>
                                                </span>
                                            @endforeach
                                        </td>
                                        <td class="Action">
                                            <span>
                                                @can('Edit Role')
                                                    <div class="action-btn ms-2">
                                                        <a class=" bg-light-secondary me-2"
                                                            data-url="{{ URL::to('roles/' . $role->id . '/edit') }}"
                                                            data-ajax-popup="true" data-size="lg" data-bs-toggle="tooltip"
                                                            title="" data-title="{{ __('Edit Role') }}"
                                                            data-bs-original-title="{{ __('Edit') }}">
                                                           Edit
                                                        </a>
                                                    </div>
                                                @endcan

                                                @can('Delete Role')
                                                    <div class="action-btn ms-2">
                                                        <a class="bs-pass-para  bg-light-secondary"
                                                            href="#" data-title="{{ __('Delete Role') }}"
                                                            data-confirm="{{ __('Are You Sure?') }}"
                                                            data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                            data-confirm-yes="delete-form-{{ $role->id }}"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="{{ __('Delete') }}">
                                                            Delete
                                                           

                                                        </a>
                                                        {!! Form::open([
                                                            'method' => 'DELETE',
                                                            'route' => ['roles.destroy', $role->id],
                                                            'id' => 'delete-form-' . $role->id,
                                                        ]) !!}
                                                        {!! Form::close() !!}
                                                    </div>
                                                @endcan
                                            </span>
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
