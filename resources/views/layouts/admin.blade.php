@php
    $logo = asset(Storage::url('uploads/logo/'));
    $users = \Auth::user();
    $currantLang = $users->currentLanguages();
    $languages = \App\Models\Utility::languages();
    $settings = Utility::settings();
    $footer_text = !empty($settings['footer_text']) ? $settings['footer_text'] : '';
    $setting = App\Models\Utility::colorset();
    $SITE_RTL = $settings['SITE_RTL'];



    if (\Auth::user()->type == 'Super Admin') {
        $company_logo = Utility::get_superadmin_logo();
    } else {
        $company_logo = Utility::get_company_logo();
    }
    $plan = \Auth::user()->currentPlan;
@endphp
<!DOCTYPE html>
<html lang="en" dir="{{ $SITE_RTL == 'on' ? 'rtl' : '' }}">
@include('partials.admin.head')

<body id="kt_app_body" data-kt-app-page-loading-enabled="true" data-kt-app-page-loading="on"
    data-kt-app-header-fixed="true" data-kt-app-header-fixed-mobile="true" data-kt-app-sidebar-enabled="true"
    data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-toolbar="true"
    data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" class="app-default">
    <!-- [ Pre-loader ] start -->
    <!-- [ Pre-loader ] start -->

    <script>
	var defaultThemeMode = "light";
	var themeMode;
	if ( document.documentElement ) {
		if ( document.documentElement.hasAttribute("data-bs-theme-mode")) {
			themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
		} else {
			if ( localStorage.getItem("data-bs-theme") !== null ) {
				themeMode = localStorage.getItem("data-bs-theme");
			} else {
				themeMode = defaultThemeMode;
			}
		}
		if (themeMode === "system") {
			themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
		}
		document.documentElement.setAttribute("data-bs-theme", themeMode);
	}
</script>
    <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
        <i class="ki-solid ki-arrow-up"></i>
    </div>

    <div class="page-loader flex-column">
        <img alt="Logo" class="theme-light-show max-h-10px" src="{{asset('landing/logo.png')}}" />
        <img alt="Logo" class="theme-dark-show max-h-10px" src="{{asset('landing/logo.png')}}" />
        <div class="d-flex align-items-center mt-5">
            <span class="spinner-border text-primary" role="status"></span>
            <span class="text-muted fs-6 fw-semibold ms-5">Loading...</span>
        </div>
    </div>
    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
        <div class="app-page  flex-column flex-column-fluid " id="kt_app_page">
            @include('partials.admin.header')
            
        </div>

        <div class="app-wrapper  flex-column flex-row-fluid " id="kt_app_wrapper">


            @include('partials.admin.menu')



            <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
                <div class="d-flex flex-column flex-column-fluid">





                    @include('partials.admin.content')
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="commonModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title"></h3>

                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                        aria-label="Close">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                </div>
               
                <div class="body py-lg-4 px-lg-4">

                </div>

            </div>

            @include('partials.admin.footer')
            @stack('script-page')

         






</body>

</html>