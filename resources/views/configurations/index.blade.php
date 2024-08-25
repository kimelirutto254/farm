@extends('layouts.admin')
<title>Manage Checklist</title>
@php
$logo = \App\Models\Utility::get_file('avatars/');
@endphp

@section('content')
<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_toolbar" class="app-toolbar pt-5">
            <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
                <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                    <div class="page-title d-flex flex-column gap-1 me-3 mb-2">
                        <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bolder fs-1 lh-0">All Checklist</h1>
                    </div>
                    <button type="button" class="btn btn-primary"data-url="{{ route('chapters.create') }}" data-ajax-popup="true" data-title="{{__('Add New Chapter')}}" data-size="sm" title="{{__('Add Chapter')}}" data-bs-toggle="tooltip" data-bs-placement="top">Add Checklist</button>
                    
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
                                        <th>{{__('Chapter')}}</th>
                                        <th>{{__('Subchapter')}}</th>
                                        <th>{{__('Requirement')}}</th>

                                        <th>{{__('Action')}}</th>
                                        
                                    </tr>
                                </thead>
                                <tbody class="fw-semibold text-gray-600">
                                    @foreach ($checklists as $checklist)
                                    <tr>
                                        <td>{{ $checklist->chapter }}</td>

                                    <td>{{ $checklist->subchapter }}</td>
                                    <td>{{ $checklist->requirement }}</td>

                                        <td>
                                            <a href="#" class="btn btn-sm btn-primary btn-flex btn-center btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                                <i class="ki-duotone ki-down fs-5 ms-1"></i></a>
                                                <!--begin::Menu-->
                                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                                                    @if (Gate::check('Edit Checklist') || Gate::check('Delete Checklist'))
                                                    
                                                    @can('Edit Checklist')
                                                    <div class="menu-item px-3">
                                                        
                                                    </div>
                                                    @endcan
                                                    

                                                    <div class="menu-item px-3">
                                                        @can('Delete Checklist')
                                                            {!! Form::open(['method' => 'DELETE', 'route' => ['chapters.destroy', $checklist->id], 'id' => 'delete-form-' . $checklist->id]) !!}
                                                            <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                                data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                                                                aria-label="Delete">Delete</a>
                                                            </form>
                                                        </div>
                                                    @endcan                                                    
                                                    @endif
                                                    
                                              
                                                </div>
                                            </td>                                        
                                        </tr>
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
