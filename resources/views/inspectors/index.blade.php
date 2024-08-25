
@extends('layouts.admin')

@php
$logo = \App\Models\Utility::get_file('avatars/');
@endphp

@section('content')
<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_toolbar" class="app-toolbar pt-5">
            <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
                <div class="app-tooFlbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                    <div class="page-title d-flex flex-column gap-1 me-3 mb-2">
                        <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bolder fs-1 lh-0">All Inspectors</h1>
                    </div>
                    <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                        <button type="button" class="btn btn-light-primary ms-2" data-url="{{ route('inspectors.importForm') }}" data-ajax-popup="true" data-title="{{ __('Upload Inspectors') }}" data-size="md" title="{{ __('Upload Inspectors') }}" data-bs-toggle="tooltip" data-bs-placement="top">
                            <i class="ki-duotone ki-exit-up fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            Import
                        </button>
                    
                        <button type="button" class="btn btn-light-primary ms-2" data-bs-toggle="modal" data-bs-target="#kt_customers_export_modal">
                            <i class="ki-duotone ki-cloud-download fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            Export
                        </button>
                    
                        <button type="button" class="btn btn-primary ms-2" data-url="{{ route('inspectors.create') }}" data-ajax-popup="true" data-title="{{ __('Add New Inspector') }}" data-size="md" title="{{ __('Add Inspector') }}" data-bs-toggle="tooltip" data-bs-placement="top">
                            Add Inspector
                        </button>
                    </div>
                    
                </div>
            </div>
        </div>
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-fluid">
                <div class="col-xl-12">
                    <div class="card-header card-body table-border-style">
                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 gy-5" id="example">
                                <thead>
                                    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                        <th class="min-w-125px">Name</th>
                                        <th class="min-w-125px">Email</th>
                                        <th class="min-w-125px">Phone</th>
                                        <th class="min-w-125px">Created At</th>
                                        @if (Gate::check('Edit Inspectors') || Gate::check('Delete Inspectors'))
                                        <th class="text-end min-w-70px">Actions</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody class="fw-semibold text-gray-600">
                                    @foreach ($inspectors as $inspector)
                                    <tr>
                                        
                                        <td>{{ $inspector->name }}</td>
                                        <td>{{ $inspector->email }}</td>
                                        
                                          <td>{{ $inspector->phone }}</td>
                                        
                                     
                                        <td>{{ \Auth::user()->dateFormat($inspector->created_at) }}</td>

                                        <td class="text-end">
                                            <a href="#" class="btn btn-sm btn-primary btn-flex btn-center btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                                <i class="ki-duotone ki-down fs-5 ms-1"></i></a>
                                                <!--begin::Menu-->
                                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                                                    <!--begin::Menu item-->
                                                   
                                                    @can('Manage Inspectors')
                                                    <div class="menu-item px-3">
                                                        <a class="menu-link px-3" href="{{ route('inspectors.show', \Illuminate\Support\Facades\Crypt::encrypt($inspector->id)) }}">View</a>
                                                       
                                                    </div>
                                                    @endcan
                                                    
                                                    @can('Edit Inspectors')
                                                    <div class="menu-item px-3">
                                                        <a class="menu-link px-3" 
                                                           data-url="{{ route('inspector.edit', \Illuminate\Support\Facades\Crypt::encrypt($inspector->id)) }}" 
                                                           data-ajax-popup="true" 
                                                           data-title="{{__('Edit Inspector')}}" 
                                                           data-size="md" 
                                                           title="{{__('Edit Inspector')}}" 
                                                           data-bs-toggle="tooltip" 
                                                           data-bs-placement="top">
                                                           Edit
                                                        </a>
                                                    </div>
                                                    @endcan
                                                    
                                                    @can('Delete Inspectors')
                                                    <div class="menu-item px-3">
                                                        <a class="menu-link px-3" 
                                                           data-url="{{ route('inspector.delete', \Illuminate\Support\Facades\Crypt::encrypt($inspector->id)) }}" 
                                                           data-ajax-popup="true" 
                                                           data-title="{{__('Delete Inspector')}}" 
                                                           data-size="md" 
                                                           title="{{__('Delete Inspector')}}" 
                                                           data-bs-toggle="tooltip" 
                                                           data-bs-placement="top">
                                                           Delete
                                                        </a>
                                                    </div>
                                                    @endcan
                                                    
                                                </div>
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
        </div>
    </div>
    @endsection
    