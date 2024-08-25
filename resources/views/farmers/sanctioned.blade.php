
@extends('layouts.admin')

@section('page-title')
   {{ __('Sanctioned Farmers ') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Sanctioned Farmers') }}</li>
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
                                <th>{{ __('ID NO') }}</th>

                                <th>{{ __('Year') }}</th>
                                <th>{{ __('First Name') }}</th>
                                <th>{{ __('Last Name') }}</th>
                                <th>{{ __('Phone Number') }}</th>
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
                                    <td>{{ $farmer->id_number }}</td>

                                    <td>2024</td>
                                    <td>{{ $farmer->first_name }}</td>
                                    <td>{{ $farmer->last_name }}</td>
                                    <td>{{ $farmer->phone_number }}</td>

                                    <td>{{ $farmer->route }}</td>
                                    <td>{{ $farmer->production_area }}</td>
                                    <td>{{ $farmer->inspector }}</td>

                                    <td>
                                        @if ($farmer->sanctioned_status == 1)
                                            <span class="btn btn-danger">{{ __('Sanctioned') }}</span>
                                        @else
                                            <span class="btn btn-danger">{{ __('Not Sanctioned') }}</span>
                                        @endif
                                    </td>                                  
                    



                                    
                                  
                                    @if (Gate::check('Edit Inspections') || Gate::check('Delete Inspections'))
                                        <td class="Action">
                                                <span>
                                                    @can('Edit Inspections')
                                                            <a href="{{ route('inspections.show', \Illuminate\Support\Facades\Crypt::encrypt($farmer->id)) }}"
                                                                class="btn btn-outline-primary">
                                                                View Report
                                                            </a>
                                                    @endcan
                                                   
                                                @can('Approve Inspections')
                                               
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


@endsection
