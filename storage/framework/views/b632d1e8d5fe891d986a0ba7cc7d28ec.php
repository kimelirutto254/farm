<?php
    $currantLang = $users->currentLanguages();
    $profile = \App\Models\Utility::get_file('uploads/profile/');
    if ($currantLang == null) {
        $currantLang = 'en';
    }

    $current_company = \Auth::user()->current_company;
    $company = \Auth::user()->currentCompany;
   
    // $setting = App\Models\Utility::settings();
?>
<div id="kt_app_header" class="app-header d-flex flex-column flex-stack">
    <!--begin::Header main-->
    <div class="d-flex align-items-center flex-stack flex-grow-1">
        <div class="app-header-logo d-flex align-items-center flex-stack px-lg-11 mb-2" id="kt_app_header_logo">
            <!--begin::Sidebar mobile toggle-->
            <div class="btn btn-icon btn-active-color-primary w-35px h-35px ms-3 me-2 d-flex d-lg-none"
                id="kt_app_sidebar_mobile_toggle">
                <i class="ki-duotone ki-abstract-14 fs-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
            </div>
            <!--end::Sidebar mobile toggle-->
            <!--begin::Logo-->  <a href="<?php echo e(url('')); ?>" class="app-sidebar-logo">
                <img alt="Logo" src="<?php echo e(asset('landing/logo.png')); ?>" class="h-70px bg-white theme-light-show app-sidebar-logo" />
            </a>
          
            <!--end::Logo-->
            <!--begin::Sidebar toggle-->
            <div id="kt_app_sidebar_toggle"
                class="app-sidebar-toggle btn btn-sm btn-icon btn-color-warning me-n2 d-none d-lg-flex"
                data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body"
                data-kt-toggle-name="app-sidebar-minimize">
                <i class="ki-duotone ki-exit-left fs-2x rotate-180">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
            </div>
            <!--end::Sidebar toggle-->
        </div>
        <!--begin::Navbar-->
        <div class="app-navbar flex-grow-1 justify-content-end" id="kt_app_header_navbar">
            <div class="app-navbar-item d-flex align-items-stretch flex-lg-grow-1 me-2 me-lg-0">
                <!--begin::Search-->
                <div id="kt_header_search" class="header-search d-flex align-items-center w-lg-350px"
                    data-kt-search-keypress="true" data-kt-search-min-length="2" data-kt-search-enter="enter"
                    data-kt-search-layout="menu" data-kt-search-responsive="true" data-kt-menu-trigger="auto"
                    data-kt-menu-permanent="true" data-kt-menu-placement="bottom-start">
                    <!--begin::Tablet and mobile search toggle-->
                    <div data-kt-search-element="toggle"
                        class="search-toggle-mobile d-flex d-lg-none align-items-center">
                        <div class="d-flex">
                            <i class="ki-duotone ki-magnifier fs-1 fs-1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </div>
                    </div>
                    <?php if(Auth::user()->type != 'super admin'): ?>
                  
                        
                  
<?php endif; ?>



                    <!--end::Menu-->
                </div>
                <!--end::Search-->
            </div>

        
         
            
          
            <!--begin::User menu-->
            <div class="app-navbar-item ms-3 ms-lg-4 me-lg-2" id="kt_header_user_menu_toggle">
                <!--begin::Menu wrapper-->
                <div class="cursor-pointer symbol symbol-30px symbol-lg-40px"
                    data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent"
                    data-kt-menu-placement="bottom-end">
                    <img src="<?php echo e(!empty($users->avatar) ? $profile . '/' . $users->avatar : $profile . '/avatar.png'); ?>"
                        alt="user" />
                </div>
                <!--begin::User account menu-->
                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px"
                    data-kt-menu="true">
                    <!--begin::Menu item-->
                    <div class="menu-item px-3">
                        <div class="menu-content d-flex align-items-center px-3">
                            <!--begin::Avatar-->
                            <div class="symbol symbol-50px me-5">
                                <img alt="profile"
                                    src="<?php echo e(!empty($users->avatar) ? $profile . '/' . $users->avatar : $profile . '/avatar.png'); ?>" />
                            </div>
                            <!--end::Avatar-->
                            <!--begin::Username-->
                            <div class="d-flex flex-column">
                                <div class="fw-bold d-flex align-items-center fs-5">
                                    <?php echo e(__('Hi,')); ?><?php echo e(Auth::user()->name); ?>!
                                    <span
                                        class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2"><?php echo e(Auth::user()->plan); ?>!</span>
                                </div>
                                <a href="#"
                                    class="fw-semibold text-muted text-hover-primary fs-7"><?php echo e(Auth::user()->email); ?>!</a>
                            </div>
                            <!--end::Username-->
                        </div>
                    </div>
                    <!--end::Menu item-->
                    <!--begin::Menu separator-->
                    <div class="separator my-2"></div>
                    <!--end::Menu separator-->
                    <!--begin::Menu item-->
                    <div class="menu-item px-5">
                        <a href="<?php echo e(route('profile')); ?>" class="menu-link px-5">My Profile</a>
                    </div>

                    
                    <div class="separator my-2"></div>
                    <!--end::Menu separator-->
                    <!--begin::Menu item-->
                    
                    <div class="menu-item px-5 my-1">
                        <a href="<?php echo e(route('settings')); ?>" class="menu-link px-5">Company Settings</a>
                    </div>
                   

                    <div class="menu-item px-5">
                        <a href="<?php echo e(route('logout')); ?>"
                            onclick="event.preventDefault();document.getElementById('frm-logout').submit();"
                            class="menu-link px-5">Sign Out</a>
                        <form id="frm-logout" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                            <?php echo csrf_field(); ?></form>
                        <!--end::Menu item-->
                    </div>
                    <!--end::Menu item-->
                </div>
                <!--end::User account menu-->
                <!--end::Menu wrapper-->
            </div>
            <!--end::User menu-->
            <!--begin::Action-->

            <div class="app-navbar-item ms-3 ms-lg-4 ms-n2 me-3 d-flex d-lg-none">
                <div class="btn btn-icon btn-custom btn-color-gray-600 btn-active-color-primary w-35px h-35px w-md-40px h-md-40px"
                    id="kt_app_aside_mobile_toggle">
                    <i class="ki-duotone ki-burger-menu-2 fs-2">
                        <span class="path1"></span>
                        <span class="path2"></span>

                    </i>
                </div>
            </div>
            <!--end::Header menu toggle-->
        </div>
        <!--end::Navbar-->
    </div>

    <!--end::Header main-->
    <!--begin::Separator-->
    <div class="app-header-separator"></div>


    <!--end::Separator-->
<script>
    document.getElementById('pageSelect').addEventListener('change', function() {
        if (this.value === 'create_new_store') {
            // Trigger the action for creating a new store
            window.location.href = "<?php echo e(route('company-resource.create')); ?>"; // Redirect to create new store URL
        }
    });
</script><?php /**PATH /home/u931094414/domains/royalblue-stinkbug-731974.hostingersite.com/public_html/resources/views/partials/admin/header.blade.php ENDPATH**/ ?>