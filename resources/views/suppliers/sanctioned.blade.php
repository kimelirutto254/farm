
@extends('layouts.admin')

@section('page-title')
   {{ __('Sanctioned Farmers ') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Non Compliant Farmers') }}</li>
@endsection


@section('content')
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header card-body table-border-style">
                {{-- <h5></h5> --}}
                <div class="table-responsive">
                    <table class="table" id="pc-dt-simple">
                        <thead>
                            <tr>
                                <th>{{ __('Grower No.') }}</th>
                                <th>{{ __('Year') }}</th>
                                <th>{{ __('First Name') }}</th>
                                <th>{{ __('Last Name') }}</th>
                                <th>{{ __('Route') }}</th>
                                <th>{{ __('Production Area') }}</th>
                                <th>{{ __('Inspector') }}</th>

                                <th>{{ __('Sanctioned Status') }}</th>
                                @if (Gate::check('Edit Inspections') || Gate::check('Delete Inspections'))
                                    <th width="200px">{{ __('Action') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($farmers as $farmer)
                                <tr>
                                    <td>
                                        @can('View Inspections Reports')
                                            <a class="btn btn-outline-primary"
                                                href="{{ route('inspections.reports', \Illuminate\Support\Facades\Crypt::encrypt($farmer->id)) }}">{{ $farmer->grower_id }}</a>
                                        @else
                                            <a href="#" class="btn btn-outline-primary">{{ $farmer->grower_id }}</a>
                                        @endcan
                                    </td>
                                    <td>{{ $farmer->year }}</td>
                                    <td>{{ $farmer->first_name }}</td>
                                    <td>{{ $farmer->last_name }}</td>
                                    <td>{{ $farmer->route }}</td>
                                    <td>{{ $farmer->production_area }}</td>
                                    <td>{{ $farmer->inspector }}</td>

                                    <td>
                                        @if ($farmer->sanctioned_status == 1)
                                            <span class="badge bg-success">{{ __('Sanctioned') }}</span>
                                        @else
                                            <span class="badge bg-danger">{{ __('Not Sanctioned') }}</span>
                                        @endif
                                    </td>                                  
                    



                                    
                                  
                                    @if (Gate::check('Edit Inspections') || Gate::check('Delete Inspections'))
                                        <td class="Action">
                                                <span>
                                                    @can('Edit Inspections')
                                                        <div class="action-btn bg-info ms-2">
                                                            <a href="{{ route('inspections.show', \Illuminate\Support\Facades\Crypt::encrypt($farmer->id)) }}"
                                                                class="mx-3 btn btn-sm  align-items-center"
                                                                data-bs-toggle="tooltip" title=""
                                                                data-bs-original-title="{{ __('View Report') }}">
                                                                <i class="ti ti-pencil text-white"></i>
                                                            </a>
                                                        </div>
                                                    @endcan
                                                    @can('Reject Inspections')
                                                    <div class="action-btn bg-info ms-2">
                                                        <a href="{{ route('inspections.reject', \Illuminate\Support\Facades\Crypt::encrypt($farmer->id)) }}"
                                                            class="mx-3 btn btn-sm  align-items-center"
                                                            data-bs-toggle="tooltip" title=""
                                                            data-bs-original-title="{{ __('Activate') }}">
                                                            <i class="ti ti-pencil text-white"></i>
                                                        </a>
                                                    </div>
                                                @endcan
                                                @can('Approve Inspections')
                                                <div class="action-btn bg-info ms-2">
                                                    <a href="{{ route('inspections.approve', \Illuminate\Support\Facades\Crypt::encrypt($farmer->id)) }}"
                                                        class="mx-3 btn btn-sm  align-items-center"
                                                        data-bs-toggle="tooltip" title=""
                                                        data-bs-original-title="{{ __('Deactivate') }}">
                                                        <i class="ti ti-pencil text-white"></i>
                                                    </a>
                                                </div>
                                            @endcan

                                                   
                                                </span>
                                            @else
                                                <i class="ti ti-lock"></i>
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


    {{-- <div class="row"> --}}
        {{-- <div class="col-12">
            <div class="card">
                <div class="card-header card-body table-border-style">
                    <h5></h5>
                    <div class="table-responsive">
                        <table class="table" id="pc-dt-simple">
                            <thead>
                                <tr>
                                    <th>{{ __('Employee ID') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Email') }}</th>
                                    <th>{{ __('Branch') }}</th>
                                    <th>{{ __('Department') }}</th>
                                    <th>{{ __('Designation') }}</th>
                                    <th>{{ __('Date Of Joining') }}</th>
                                    @if (Gate::check('Edit Employee') || Gate::check('Delete Employee'))
                                        <th>{{ __('Action') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($employees as $employee)
                                    <tr>
                                        <td>
                                            @can('Show Employee')
                                                <a
                                                    href="{{ route('employee.show', \Illuminate\Support\Facades\Crypt::encrypt($employee->id)) }}">{{ \Auth::user()->employeeIdFormat($employee->employee_id) }}</a>
                                            @else
                                                <a href="#">{{ \Auth::user()->employeeIdFormat($employee->employee_id) }}</a>
                                            @endcan
                                        </td>
                                        <td>{{ $employee->name }}</td>
                                        <td>{{ $employee->email }}</td>
                                        <td>
                                            {{ !empty(\Auth::user()->getBranch($employee->branch_id)) ? \Auth::user()->getBranch($employee->branch_id)->name : '' }}
                                        </td>
                                        <td>
                                            {{ !empty(\Auth::user()->getDepartment($employee->department_id)) ? \Auth::user()->getDepartment($employee->department_id)->name : '' }}
                                        </td>
                                        <td>
                                            {{ !empty(\Auth::user()->getDesignation($employee->designation_id)) ? \Auth::user()->getDesignation($employee->designation_id)->name : '' }}
                                        </td>
                                        <td>
                                            {{ \Auth::user()->dateFormat($employee->company_doj) }}</td>
                                        @if (Gate::check('Edit Employee') || Gate::check('Delete Employee'))
                                            <td class="d-flex">
                                                @if ($employee->is_active == 1)
                                                    @can('Edit Employee')
                                                        <a href="{{ route('employee.edit', \Illuminate\Support\Facades\Crypt::encrypt($employee->id)) }}"
                                                            class="action-btn btn-primary me-1 btn btn-sm d-inline-flex align-items-center"
                                                            data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                            title="{{ __('Edit') }}"><i class="ti ti-pencil"></i></a>
                                                    @endcan
                                                    @can('Delete Employee')
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['employee.destroy', $employee->id], 'id' => 'delete-form-' . $employee->id]) !!}
                                                        <a href="#!"
                                                            class="action-btn btn-danger me-1 btn btn-sm d-inline-flex align-items-center show_confirm"
                                                            data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                            title="{{ __('Delete') }}">
                                                            <i class="ti ti-trash"></i></a>
                                                        {!! Form::close() !!}
                                                    @endcan
                                                @else
                                                    <i class="fas fa-lock"></i>
                                                @endif
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
    </div> --}}
@endsection
