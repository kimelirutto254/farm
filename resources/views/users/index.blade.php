@extends('layouts.admin')

<title>Users</title>
@section('filter')
@endsection
@php
    $logo = \App\Models\Utility::get_file('uploads/profile/');
@endphp
@section('content')
<div id="kt_app_content" class="app-content flex-column-fluid">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-fluid">
        <!--begin::Row-->

        <!--end::Row-->
        <!--begin::Row-->
<div class="row">
    
<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar pt-5">
								<!--begin::Toolbar container-->
								<div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
									<!--begin::Toolbar wrapper-->
									<div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
										<!--begin::Page title-->
										<div class="page-title d-flex flex-column gap-1 me-3 mb-2">
											<!--begin::Breadcrumb-->
											
											<!--end::Breadcrumb-->
											<!--begin::Title-->
											<h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bolder fs-1 lh-0">All Users</h1>
											<!--end::Title-->
										</div>
										<!--end::Page title-->
										<!--begin::Actions-->
                                        @if(Gate::check('Manage Tenants'))
            <div class="col-auto pe-0">
            <a class="btn btn-sm  text-white  btn-primary me-2" data-url="{{ route('users.create') }}"
        data-title="{{ __('Add User') }}" data-ajax-popup="true" data-bs-toggle="tooltip" data-bs-placement="top"
        title="{{ __('Create') }}">
        Add New User
    </a>
            </div>
        @endif										<!--end::Actions-->
									</div>
									<!--end::Toolbar wrapper-->
								</div>
								<!--end::Toolbar container-->
							</div>
                            
							<!--end::Toolbar-->
    @foreach ($users as $user)
        <div class="col-md-6 col-xl-3">
            <div class="card border-hover-primary">
                <div class="card-header border-0 pt-9">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fs-3 fw-bold text-dark" style="text-transform: uppercase;">
                                {{$user->name}} |
                            </div>
                        </div>
                        
                        <span class="badge text-white fw-bold px-4 py-2
                        @if($user->is_active == 1)
                            bg-success
                        @else
                            bg-danger
                        @endif
                    ">
                            @if($user->is_active == 1)
                                ACTIVE
                            @else
                                Inactive
                            @endif
                        </span>
                    </div>
                    <div class="dropdown">
                            <button class="btn-sm btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Actions
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                                @if (Gate::check('Edit User') || Gate::check('Delete User') || Gate::check('Reset Password'))
                                    @can('Edit User')
                                        <li><a class="dropdown-item" href="{{ route('users.edit', $user->id) }}">Edit</a></li>
                                    @endcan
                                    @can('Reset Password')
                                        <li><a class="dropdown-item"
                                                href="{{ route('users.reset', \Crypt::encrypt($user->id)) }}">Reset Password</a>
                                        </li>
                                    @endcan
                                    @can('Delete User')
                                        <li>
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['users.destroy', $user->id], 'id' => 'delete-form-' . $user->id]) !!}
                                            <a href="#" class="dropdown-item bs-pass-para" data-confirm="{{ __('Are You Sure?') }}"
                                                data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                data-confirm-yes="delete-form-{{ $user->id }}" title="{{ __('Delete') }}"
                                                data-bs-toggle="tooltip" data-bs-placement="top">
                                                Delete
                                            </a>
                                            {!! Form::close() !!}
                                        </li>
                                    @endcan
                                @endif
                                {{-- Additional conditional dropdown items --}}
                                @if ($user->is_enable_login == 1)
                                    <li><a href="{{ route('owner.users.login', \Crypt::encrypt($user->id)) }}"
                                            class="dropdown-item">Login Disable</a></li>
                                @elseif ($user->is_enable_login == 0 && $user->password == null)
                                    <li><a href="#" data-url="{{ route('users.reset', \Crypt::encrypt($user->id)) }}"
                                            data-ajax-popup="true" data-size="md" class="dropdown-item login_enable">Login
                                            Enable</a></li>
                                @else
                                    <li><a href="{{ route('owner.users.login', \Crypt::encrypt($user->id)) }}"
                                            class="dropdown-item">Login Enable</a></li>
                                @endif
                            </ul>
                        </div>
                </div>
                <div class="card-body p-9 text-center">
                    <div class="fs-3 fw-bold text-dark">{{$user->type}}</div>
                    <p class="text-gray-400 fw-semibold fs-5 mt-1 mb-7">{{$user->email}}</p>
                    <div class="fs-6 text-gray-800 fw-bold">
                                {{$user->created_at->format('F j, Y H:i')}}
                            </div>
                </div>
            </div>
        </div>
    @endforeach
    <div class="col-md-6 col-xl-3">
            <div class="card border-hover-primary">
                <div class="card-header border-0 pt-9">
        @can('Create User')
            <a class="btn-addnew-user" data-url="{{ route('users.create') }}" data-title="{{ __('Add User') }}"
                data-ajax-popup="true" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Create') }}"><i
                    class="ti ti-plus text-white"></i>
                <div class="bg-primary proj-add-icon">
                    <i class="ti ti-plus"></i>
                </div>
                <h6 class="mt-4 mb-2">{{ __('New User') }}</h6>
                <p class="text-muted text-center">{{ __('Click here to add New User') }}</p>
            </a>
        @endcan
    </div>
    </div>
    </div>

</div>
@endsection

@push('script-page')
    {{-- Password --}}
    <script>
        $(document).on('change', '#password_switch', function () {
            if ($(this).is(':checked')) {
                $('.ps_div').removeClass('d-none');
                $('#password').attr("required", true);

            } else {
                $('.ps_div').addClass('d-none');
                $('#password').val(null);
                $('#password').removeAttr("required");
            }
        });
        $(document).on('click', '.login_enable', function () {
            setTimeout(function () {
                $('.modal-body').append($('<input>', {
                    type: 'hidden',
                    val: 'true',
                    name: 'login_enable'
                }));
            }, 2000);
        });
    </script>
@endpush