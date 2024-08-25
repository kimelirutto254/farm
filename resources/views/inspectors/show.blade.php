@extends('layouts.admin')

@php
$logo = \App\Models\Utility::get_file('avatars/');
@endphp
@php
    use Illuminate\Support\Facades\Crypt;

    // Encrypt the ID
    $encryptedId = Crypt::encrypt($inspector->id);
@endphp

@section('content')
<!--begin::Main-->
<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar pt-5">
            <!--begin::Toolbar container-->
            <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
                <!--begin::Toolbar wrapper-->
                <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                    <!--begin::Page title-->
                    <div class="page-title d-flex flex-column gap-1 me-3 mb-2">
                        <!--begin::Breadcrumb-->
                       
                        <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bolder fs-1 lh-0">Inspector Overview</h1>
                        <!--end::Title-->
                    </div>
                 
                </div>
                <!--end::Toolbar wrapper-->
            </div>
            <!--end::Toolbar container-->
        </div>
        <!--end::Toolbar-->
        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-fluid">
                <!--begin::Navbar-->
                <div class="card mb-5 mb-xl-10">
                    <div class="card-body pt-9 pb-0">
                        <!--begin::Details-->
                        <div class="d-flex flex-wrap flex-sm-nowrap mb-3">
                            <!--begin: Pic-->
                            <div class="me-7 mb-4">
                                <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                                    <img src="assets/media/avatars/300-3.jpg" alt="image" />
                                    <div class="position-absolute translate-middle bottom-0 start-100 mb-6 bg-success rounded-circle border border-4 border-body h-20px w-20px"></div>
                                </div>
                            </div>
                            <!--end::Pic-->
                            <!--begin::Info-->
                            <div class="flex-grow-1">
                                <!--begin::Title-->
                                <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                                    <!--begin::User-->
                                    <div class="d-flex flex-column">
                                        <!--begin::Name-->
                                        <div class="d-flex align-items-center mb-2">
                                            <a href="#" class="text-gray-900 text-hover-primary fs-2 fw-bold me-1">{{$inspector->name}}</a>
                                            <a href="#">
                                             
                                            </a>
                                        </div>
                                        <!--end::Name-->
                                        <!--begin::Info-->
                                        <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                                           
                                         
                                            <a href="#" class="d-flex align-items-center text-black text-hover-primary mb-2">
                                            <i class="ki-duotone ki-sms fs-4 me-1">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>{{$inspector->email}}</a>
                                        </div>
                                        <!--end::Info-->
                                    </div>
                                    <!--end::User-->
                                    <!--begin::Actions-->
                                    <div class="d-flex my-4">
                                        <button type="button" class="btn btn-primary"
                                        data-url="{{ route('inspectors.resetpassword', ['id' => $encryptedId]) }}"
                                        data-ajax-popup="true"
                                        data-title="{{ __('Change Pin') }}"
                                        data-size="lg"
                                        title="{{ __('Change Pin') }}"
                                        data-bs-toggle="tooltip"
                                        data-bs-placement="top">
                                    Change Pin
                                </button>
                                
                                        <!--begin::Menu-->
                                        
                                        <!--end::Menu-->
                                    </div>
                                    <!--end::Actions-->
                                </div>
                                <!--end::Title-->
                                <!--begin::Stats-->
                                <div class="d-flex flex-wrap flex-stack">
                                    <!--begin::Wrapper-->
                                    <div class="d-flex flex-column flex-grow-1 pe-8">
                                        <!--begin::Stats-->
                                        <div class="d-flex flex-wrap">
                                            <!--begin::Stat-->
                                            <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                                <!--begin::Number-->
                                                <div class="d-flex align-items-center">
                                                   
                                                    <div class="fs-2 fw-bold" data-kt-countup="true" data-kt-countup-value="{{$inspected}}" >{{$inspected}}</div>
                                                </div>
                                                <!--end::Number-->
                                                <!--begin::Label-->
                                                <div class="fw-semibold fs-6 text-black">Inspected</div>
                                                <!--end::Label-->
                                            </div>
                                            <!--end::Stat-->
                                            <!--begin::Stat-->
                                            <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                                <!--begin::Number-->
                                                <div class="d-flex align-items-center">
                                                   
                                                    <div class="fs-2 fw-bold" data-kt-countup="true" data-kt-countup-value="{{ $approved}}">{{ $approved}}</div>
                                                </div>
                                                <!--end::Number-->
                                                <!--begin::Label-->
                                                <div class="fw-semibold fs-6 text-black">Approved</div>
                                                <!--end::Label-->
                                            </div>
                                            <!--end::Stat-->
                                            <!--begin::Stat-->
                                            <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                                <!--begin::Number-->
                                                <div class="d-flex align-items-center">
                                                   
                                                    <div class="fs-2 fw-bold" data-kt-countup="true" data-kt-countup-value="{{$pending}}">{{$pending}}</div>
                                                </div>
                                                <!--end::Number-->
                                                <!--begin::Label-->
                                                <div class="fw-semibold fs-6 text-black">Pending</div>
                                                <!--end::Label-->
                                            </div>
                                            <!--end::Stat-->
                                        </div>
                                        <!--end::Stats-->
                                    </div>
                                   
                                    
                                    <!--end::Progress-->
                                </div>
                                <!--end::Stats-->
                            </div>
                            <!--end::Info-->
                        </div>
                        <!--end::Details-->
                        <!--begin::Navs-->
                        <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold">
                            <!--begin::Nav item-->
                            <li class="nav-item mt-2">
                                <a class="nav-link text-active-primary ms-0 me-10 py-5 active" href="../dist/account/overview.html">Overview</a>
                            </li>
                          
                          
                        </ul>
                        <!--begin::Navs-->
                    </div>
                </div>
              
               
             
                <div class="row gy-5 g-xl-10">
                    <!--begin::Col-->
                    
                    <div class="col-xl-12">
                        <!--begin::Table Widget 5-->
                        <div class="card card-flush h-xl-100">
                            <!--begin::Card header-->
                            <div class="card-header pt-7">
                                <!--begin::Title-->
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bold text-dark">Inspections Report</span>
                                </h3>
                                <!--end::Title-->
                                <!--begin::Actions-->
                               
                            </div>
                            <!--end::Card header-->
                            <!--begin::Card body-->
                            <div class="card-body">
                                <!--begin::Table-->
                                <table class="table align-middle table-row-dashed fs-6 gy-3" id="kt_table_widget_5_table">
                                    <!--begin::Table head-->
                                    <thead>
                                        <!--begin::Table row-->
                                        <tr class="text-start text-black fw-bold fs-7 text-uppercase gs-0">
                                            <th class="">Grower Number</th>
                                            <th class="">Farmer</th>
                                            <th class="">Inspection Date</th>
                                            <th class="">Inspection Status</th>
                                            <th class="">Actions</th>
                                        </tr>
                                        
                                        <!--end::Table row-->
                                    </thead>
                                    <!--end::Table head-->
                                    <!--begin::Table body-->
                                    <tbody class="fw-bold text-gray-600">
                                        <!-- Loop through each farmer/inspection record here -->
                                        @foreach($inspections as $inspection)
                                        <tr>
                                            <!-- GROWER NUMBER -->
                                            <td>
                                                <a href="#" class="text-dark text-hover-primary">{{ $inspection->grower_id }}</a>
                                            </td>
                                            <!-- FARMER -->
                                            <td>{{ $inspection->first_name }}{{ $inspection->middle_name }} {{ $inspection->last_name }}</td>
                                            <!-- INSPECTION DATE -->
                                            <td>{{ $inspection->inspection_date }}</td>
                                            <!-- INSPECTION STATUS -->
                                            <td>
                                                <span class="badge py-3 px-4 fs-7 badge-light-{{ $inspection->status_class }}">
                                                    {{ $inspection->inspection_status }}
                                                </span>
                                            </td>
                                            <!-- Actions: View Report and Edit Farmer -->
                                            <td>
                                                <a href="{{ route('farmer.show', \Illuminate\Support\Facades\Crypt::encrypt($inspection->id)) }}" class="btn btn-sm btn-primary">View Report</a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    
                                    <!--end::Table body-->
                                </table>
                                <!--end::Table-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Table Widget 5-->
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Row-->
            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Content wrapper-->
    <!--begin::Footer-->
    <div class="modal fade" id="kt_modal_change_pin" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title">Change PIN</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!-- Modal Body -->
                <div class="modal-body">
                    <form id="change_pin_form" method="POST" action="{{ route('pin.change', ['inspector_id' => \Illuminate\Support\Facades\Crypt::encrypt($inspector->id)]) }}">
                        @csrf <!-- Laravel CSRF token for security -->
                        
                        <div class="mb-3">
                            <label for="new_pin" class="form-label">New PIN</label>
                            <input type="password" class="form-control" id="new_pin" name="new_pin" placeholder="Enter new PIN" required>
                        </div>
                        <!-- Confirm New PIN -->
                        <div class="mb-3">
                            <label for="confirm_new_pin" class="form-label">Confirm New PIN</label>
                            <input type="password" class="form-control" id="confirm_new_pin" name="confirm_new_pin" placeholder="Confirm new PIN" required>
                        </div>
                    </form>
                </div>
                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" form="change_pin_form">Save Changes</button>
                </div>
            </div>
        </div>
    </div>
    <!--end::Footer-->
</div>
@endsection
