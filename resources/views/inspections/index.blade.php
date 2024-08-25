
@extends('layouts.admin')

@section('page-title')
   {{ __('Manage Inspections ') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Inspections') }}</li>
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
                                <th>{{ __('Grower ID') }}</th>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('ID Number') }}</th>
                                <th>{{ __('Town') }}</th>
                                <th>{{ __('Inspected By') }}</th>
                                <th>{{ __('Inspector NO') }}</th>
                                <th>{{ __('Status') }}</th>





                                @if (Gate::check('Edit Inspections') || Gate::check('Delete Inspections'))
                                    <th width="200px">{{ __('Action') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($inspections as $inspection)
                                <tr>
                                    <td>
                                        @can('Show Inspections')
                                            <a class="btn btn-outline-primary"
                                                href="{{ route('inspections.show', \Illuminate\Support\Facades\Crypt::encrypt($farmer->id)) }}">{{ $farmer->grower_id }}</a>
                                        @else
                                            <a href="#" class="btn btn-outline-primary">{{ $inspection->grower_id }}</a>
                                        @endcan
                                    </td>
                                    <td>{{ $inspection->first_name }}</td>
                                    <td>{{ $inspection->id_number }}</td>
                                    <td>{{ $inspection->town }}</td>
                                    <td>{{ $inspection->inspected_by }}</td>

                                    <td>{{ $inspection->inspector_no }}</td>




                                    
                                    <td>
                                        {{ \Auth::user()->dateFormat($inspection->updated_on) }}
                                    </td>
                                    <td>{{ $inspection->status }}</td>

                                    @if (Gate::check('Edit Inspections') || Gate::check('Delete Inspections'))
                                        <td class="Action">
                                                <span>
                                                    @can('Edit Inspections')
                                                        <div class="action-btn bg-info ms-2">
                                                            <a href="{{ route('farmer.edit', \Illuminate\Support\Facades\Crypt::encrypt($farmer->id)) }}"
                                                                class="mx-3 btn btn-sm  align-items-center"
                                                                data-bs-toggle="tooltip" title=""
                                                                data-bs-original-title="{{ __('Edit') }}">
                                                                <i class="ti ti-pencil text-white"></i>
                                                            </a>
                                                        </div>
                                                    @endcan

                                                    @can('Delete Inspections')
                                                        <div class="action-btn bg-danger ms-2">
                                                            {!! Form::open(['method' => 'DELETE', 'route' => ['farmer.destroy', $farmer->id], 'id' => 'delete-form-' . $farmer->id]) !!}
                                                            <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                                data-bs-toggle="tooltip" title=""
                                                                data-bs-original-title="Delete" aria-label="Delete"><i
                                                                    class="ti ti-trash text-white text-white"></i></a>
                                                            </form>
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
@endsection
