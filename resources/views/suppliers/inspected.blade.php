
@extends('layouts.admin')

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
                        <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bolder fs-1 lh-0">Inspected Suppliers</h1>
                    </div>
                    <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                        <button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                            <i class="ki-duotone ki-filter fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>Filter
                        </button>
                        
                        <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true" id="kt-toolbar-filter">
                            <div class="px-7 py-5">
                                <div class="fs-4 text-dark fw-bold">Filter Options</div>
                            </div>
                            <div class="separator border-gray-200"></div>
                            <form id="filter-form">

                            <div class="px-7 py-5">
                            <label class="form-label fs-5 fw-semibold mb-3">Date Range</label>

                                <div class="mb-10">
                                <div class="input-group">
                                <input type="date" id="date-start" class="form-control me-2" name="date_start" placeholder="{{ __('Start Date') }}">
                                <span class="input-group-text">{{ __('to') }}</span>
                                <input type="date" id="date-end" class="form-control" name="date_end" placeholder="{{ __('End Date') }}">
                            </div>
                                    <label class="form-label fs-5 fw-semibold mb-3">Select Status</label>
                                    <select class="form-select form-select-solid fw-bold" data-kt-select2="true" data-placeholder="Select option" data-allow-clear="true" data-kt-customer-table-filter="month" data-dropdown-parent="#kt-toolbar-filter">
                                        <option></option>
                                        <option value="pending">{{ __('Inspected') }}</option>
                                <option value="completed">{{ __('Incomplete') }}</option>
                                    </select>
                                </div>
                                <div class="mb-10">
                                    <label class="form-label fs-5 fw-semibold mb-3">Route:</label>
                                    <div class="d-flex flex-column flex-wrap fw-semibold" data-kt-customer-table-filter="payment_type">
                                        <label class="form-check form-check-sm form-check-custom form-check-solid mb-3 me-5">
                                        <input type="text" class="form-control" id="route" name="route">
                                        </label>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="reset" class="btn btn-light btn-active-light-primary me-2" data-kt-menu-dismiss="true" data-kt-customer-table-filter="reset">Reset</button>
                                    <button type="submit" class="btn btn-primary" data-kt-menu-dismiss="true">Apply</button>
                                </div>
                            </div>
                            </form>
                        </div>
                        <button type="button" class="btn btn-light-primary me-3" data-bs-toggle="modal" data-bs-target="#kt_customers_export_modal">
                            <i class="ki-duotone ki-exit-up fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>Export
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
                            <table class="table table-rounded table-striped border gy-7 gs-10" id="example">
                                <thead>
                                    <tr class="fw-semibold bg-primary  fs-6 text-white border-bottom border-gray-200">
                      
                                <th>{{ __('Supplier No.') }}</th>
                                <th>{{ __('Year') }}</th>
                                <th>{{ __('Company Name') }}</th>
                                <th>{{ __('Category') }}</th>
                                <th>{{ __('Location') }}</th>
                                <th>{{ __('Contact Email') }}</th>
                                <th>{{ __('Contact Person') }}</th>
                                <th>{{ __('Inspected by') }}</th>
                    
                                @if (Gate::check('Edit Inspections') || Gate::check('Delete Inspections'))
                                    <th width="200px">{{ __('Actions') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($suppliers as $supplier)
                                <tr>
                                    <td>
                                        @can('Show Inspections Report')
                                            <a class="btn btn-outline-primary"
                                                href="{{ route('inspections.show', \Illuminate\Support\Facades\Crypt::encrypt($supplier->id)) }}">{{ $supplier->id }}</a>
                                        @else
                                            <a href="#" class="btn btn-outline-primary">{{ $supplier->id }}</a>
                                        @endcan
                                    </td>
                                    <td>2024</td>
                                    <td>{{ $supplier->first_name }}</td>
                                    <td>{{ $supplier->last_name }}</td>
                                    <td>{{ $supplier->route }}</td>
                                    <td>{{ $supplier->total_area }}</td>
                                    <td>{{ $supplier->production_area }}</td>
                                    <td>{{ $supplier->inspection_status }}</td>
                                    <td>{{ \Auth::user()->dateFormat($supplier->inspection_date) }}</td>
                    
                                    @if (Gate::check('Edit Inspections') || Gate::check('Delete Inspections'))
                                        <td class="Action">
                                            <span>
                                                @can('Edit Inspections')
                                                    <div class="action-btn bg-info ms-2">
                                                        <a href="{{ route('inspections.edit', \Illuminate\Support\Facades\Crypt::encrypt($supplier->id)) }}"
                                                            class="mx-3 btn btn-sm  align-items-center"
                                                            data-bs-toggle="tooltip" title=""
                                                            data-bs-original-title="{{ __('Edit Inspection') }}">
                                                            <i class="ti ti-pencil text-white"></i>
                                                        </a>
                                                    </div>
                                                @endcan
                                                @can('Reject Inspections')
                                                <div class="action-btn bg-info ms-2">
                                                    <a href="{{ route('inspections.reject', \Illuminate\Support\Facades\Crypt::encrypt($supplier->id)) }}"
                                                        class="mx-3 btn btn-sm  align-items-center"
                                                        data-bs-toggle="tooltip" title=""
                                                        data-bs-original-title="{{ __('Reject Inspection') }}">
                                                        <i class="ti ti-pencil text-white"></i>
                                                    </a>
                                                </div>
                                                @endcan
                                                @can('Approve Inspections')
                                                <div class="action-btn bg-info ms-2">
                                                    <a href="{{ route('inspections.approve', \Illuminate\Support\Facades\Crypt::encrypt($supplier->id)) }}"
                                                        class="mx-3 btn btn-sm  align-items-center"
                                                        data-bs-toggle="tooltip" title=""
                                                        data-bs-original-title="{{ __('Approve Inspection') }}">
                                                        <i class="ti ti-pencil text-white"></i>
                                                    </a>
                                                </div>
                                                @endcan
                                            </span>
                                        </td>
                                    @endif
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
<script>
        $(document).ready(function() {
            $('#filter-form').submit(function(event) {
                event.preventDefault(); // Prevent form submission
    
                // Retrieve filter values
                let startDate = $('#date-start').val();
                let endDate = $('#date-end').val();
                let inspectionStatus = $('#inspection-status').val().toLowerCase();
                let route = $('#route').val().toLowerCase();
    
                // Filter table data
                $('table tbody tr').each(function() {
                    let row = $(this);
                    let inspectionDate = row.find('td:nth-child(9)').text();
                    let status = row.find('td:nth-child(10)').text().toLowerCase();
                    let routeValue = row.find('td:nth-child(7)').text().toLowerCase();
    
                    // Apply filters
                    let dateInRange = (startDate === '' || endDate === '' || (inspectionDate >= startDate && inspectionDate <= endDate));
                    let statusMatch = (inspectionStatus === '' || status === inspectionStatus);
                    let routeMatch = (route === '' || routeValue.includes(route));
    
                    // Show or hide rows based on filter criteria
                    if (dateInRange && statusMatch && routeMatch) {
                        row.show();
                    } else {
                        row.hide();
                    }
                });
            });
        });
    </script>