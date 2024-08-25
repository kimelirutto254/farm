@php
    // $logo=asset(Storage::url('uploads/logo/'));
    // $favicon=\App\Models\Utility::getValByName('company_favicon');
    $company_favicon = \App\Models\Utility::getValByName('company_favicon');
    $logo = \App\Models\Utility::get_file('uploads/logo/');
    $company_logo = \App\Models\Utility::GetLogo();
    $settings = Utility::settings();

@endphp
<html data-bs-theme-mode="light">

<head>
    <meta charset="utf-8" dir="{{ $settings['SITE_RTL'] == 'on' ? 'rtl' : '' }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=  ">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="{{ env('APP_NAME') }} - Property Management System">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        {{ \App\Models\Utility::getValByName('header_text') ? \App\Models\Utility::getValByName('header_text') : config('app.name', 'Farm Exceed') }}
        - @yield('page-title')</title>
    <link rel="icon"
        href="{{asset('landing/logo.png')}}"
        type="image" sizes="16x16">
    <link rel="shortcut icon" href="{{asset('landing/logo.png')}}"/>
    <!--begin::Fonts(mandatory for all pages)-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700"/>        <!--end::Fonts-->
                <!--begin::Vendor Stylesheets(used for this page only)-->
                        <link href="{{ asset('landing/plugins/custom/fullcalendar/fullcalendar.bundle.css')}}" rel="stylesheet" type="text/css"/>
                    <!--end::Vendor Stylesheets-->
                <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
                        <link href="{{ asset('landing/plugins/global/plugins.bundle.css')}}" rel="stylesheet" type="text/css"/>
                        <link href="{{ asset('landing/css/style.bundle.css')}}" rel="stylesheet" type="text/css"/>
                    <!--end::Global Stylesheets Bundle-->
                    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet">
                    <link href="{{ asset('landing//plugins/custom/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css" />

    <script>
        if (window.top != window.self) {
            window.top.location.replace(window.self.location.href);
        }
    </script>
</head>
