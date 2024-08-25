<?php
$logo = \App\Models\Utility::get_file('uploads/logo/');
$company_logo = \App\Models\Utility::GetLogo();

?>

<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar"
data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="250px"
data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
<!--begin::Main-->
<div class="d-flex flex-column justify-content-between h-100 hover-scroll-overlay-y my-2 d-flex flex-column"
id="kt_app_sidebar_main" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto"
data-kt-scroll-dependencies="#kt_app_header" data-kt-scroll-wrappers="#kt_app_main" data-kt-scroll-offset="5px">
<!--begin::Sidebar menu-->
<div id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false"
class="flex-column-fluid menu menu-sub-indention menu-column menu-rounded menu-active-bg mb-7">
<?php if(Auth::user()->type == 'super admin'): ?>
<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage Dashboard')): ?>
<div class="menu-item">
    <!--begin:Menu link-->
    <a class="menu-link" href="<?php echo e(route('dashboard')); ?>">
        <span class="menu-icon">
            <i class="ki-duotone ki-element-5 fs-2x">
                <span class="path1"></span>
                <span class="path2"></span>
                <span class="path3"></span>
                <span class="path4"></span>
            </i>
        </span>
        <span class="menu-title text-black">Dashboard</span>
    </a>
</span>
</div>

<?php endif; ?>

<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage Coupans')): ?>
<div class="menu-item">
    <!--begin:Menu link-->
    <a class="menu-link" href="<?php echo e(route('coupons.index')); ?>">
        <span class="menu-icon">
            <i class="ki-duotone ki-element-11 fs-1">
                <span class="path1"></span>
                <span class="path2"></span>
                <span class="path3"></span>
                <span class="path4"></span>
            </i>
        </span>
        <span class="menu-title">Coupons</span>
    </a>
</span>
</div>

<?php endif; ?>

<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage Plans')): ?>
<div class="menu-item">
    <!--begin:Menu link-->
    <a class="menu-link" href="<?php echo e(route('plans.index')); ?>">
        <span class="menu-icon">
            <i class="ki-duotone ki-element-11 fs-1">
                <span class="path1"></span>
                <span class="path2"></span>
                <span class="path3"></span>
                <span class="path4"></span>
            </i>
        </span>
        <span class="menu-title">Plans</span>
    </a>
</span>
</div>

<?php endif; ?>

<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage Plan Request')): ?>
<div class="menu-item">
    <!--begin:Menu link-->
    <a class="menu-link" href="<?php echo e(route('plan_request.index')); ?>">
        <span class="menu-icon">
            <i class="ki-duotone ki-element-11 fs-1">
                <span class="path1"></span>
                <span class="path2"></span>
                <span class="path3"></span>
                <span class="path4"></span>
            </i>
        </span>
        <span class="menu-title">Plan Requests</span>
    </a>
</span>
</div>

<?php endif; ?>



<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage Email Template')): ?>
<li
class="dash-item dash-hasmenu <?php echo e(Request::route()->getName() == 'manage.email.language' || Request::route()->getName() == 'manage.email.language' ? ' active dash-trigger' : 'collapsed'); ?>">
<a href="<?php echo e(route('manage.email.language', \Auth::user()->lang)); ?>"
    class="dash-link <?php echo e(request()->is('email_template') ? 'active' : ''); ?>">
    <span class="dash-micon">
        <i class="ti ti-mail"></i>
    </span>
    <span class="dash-mtext"><?php echo e(__('Email Templates')); ?></span>
</a>
</li>
<?php endif; ?>

<?php if(Auth::user()->type == 'super admin'): ?>
<?php endif; ?>
<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage Settings')): ?>
<li
class="dash-item dash-hasmenu <?php echo e(Request::segment(1) == 'settings' || Request::route()->getName() == 'store.editproducts' ? ' active dash-trigger' : 'collapsed'); ?>">
<a href="<?php echo e(route('settings')); ?>"
class="dash-link <?php echo e(request()->is('settings') ? 'active' : ''); ?>">
<span class="dash-micon"> <i data-feather="settings"></i>
</span>
<span class="dash-mtext">
    <?php if(Auth::user()->type == 'super admin'): ?>
    <?php echo e(__('Settings')); ?>

    <?php endif; ?>
</span>
</a>
</li>
<?php endif; ?>
<?php else: ?>
<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage Dashboard')): ?>

<div class="menu-item">
    <a class="menu-link" href="<?php echo e(route('dashboard')); ?>">
        <span class="menu-icon text-black">
            <i class="ki-duotone ki-element-11 fs-2x">
                <span class="path1"></span>
                <span class="path2"></span>
                <span class="path3"></span>
                <span class="path4"></span>
            </i>
        </span>
        <span class="menu-title text-black">Dashboard</span>
    </a>
</span>
</div>
<?php endif; ?>


<div data-kt-menu-trigger="click" class="menu-item menu-accordion">
    <!--begin:Menu link-->
    <span class="menu-link">
        <span class="menu-icon">
            <i class="ki-duotone ki-profile-user fs-2x">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
        </span>
        <span class="menu-title">Farmers</span>
        <span class="menu-arrow"></span>
    </span>
    <!--end:Menu link-->
    <!--begin:Menu sub-->
    <div class="menu-sub menu-sub-accordion">
        <!--begin:General-->
        <div class="menu-item">
            <a class="menu-link" href="<?php echo e(route('farmers.index')); ?>">
                <span class="menu-bullet">
                    <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">All Farmers</span>
            </a>
        </div>
    </div>
    <!--end:General-->
    <div class="menu-sub menu-sub-accordion">
        <!--begin:Menu item-->
        <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
            <!--begin:Menu link-->
            <span class="menu-link">
                <span class="menu-bullet">
                    <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Inspection Status</span>
                <span class="menu-arrow"></span>
            </span>
            <!--end:Menu link-->
            <!--begin:Menu sub-->
            <div class="menu-sub menu-sub-accordion">
                <!--begin:Menu item-->
                <div class="menu-item">
                    <!--begin:Menu link-->
                    <a class="menu-link" href="<?php echo e(route('farmers.inspected')); ?>">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span class="menu-title">Inspected</span>
                    </a>
                    <!--end:Menu link-->
                </div>
                <div class="menu-item">
                    <a class="menu-link" href="<?php echo e(route('farmers.uninspected')); ?>">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span class="menu-title">Not Inspected</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="menu-sub menu-sub-accordion">
        <!--begin:Menu item-->
        <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
            <!--begin:Menu link-->
            <span class="menu-link">
                <span class="menu-bullet">
                    <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Compliance Status</span>
                <span class="menu-arrow"></span>
            </span>
            <!--end:Menu link-->
            <!--begin:Menu sub-->
            <div class="menu-sub menu-sub-accordion">
                <!--begin:Menu item-->
                <div class="menu-item">
                    <!--begin:Menu link-->
                    <a class="menu-link" href="<?php echo e(route('farmers.compliant')); ?>">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span class="menu-title">Compliant</span>
                    </a>
                    <!--end:Menu link-->
                </div>
                <div class="menu-item">
                    <a class="menu-link" href="<?php echo e(route('farmers.noncompliant')); ?>">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span class="menu-title">Continous Improvements</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>





<div data-kt-menu-trigger="click" class="menu-item menu-accordion">
    <!--begin:Menu link-->
    <span class="menu-link">
        <span class="menu-icon">
            <i class="ki-duotone ki-notepad-bookmark fs-2x">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
        </span>
        <span class="menu-title">Suppliers</span>
        <span class="menu-arrow"></span>
    </span>
    <!--end:Menu link-->
    <!--begin:Menu sub-->
    <div class="menu-sub menu-sub-accordion">
        <!--begin:General-->
        <div class="menu-item">
            <a class="menu-link" href="<?php echo e(route('suppliers.index')); ?>">
                <span class="menu-bullet">
                    <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">All Suppliers</span>
            </a>
        </div>
    </div>
    <div class="menu-sub menu-sub-accordion">
        <!--begin:General-->
        <div class="menu-item">
            <a class="menu-link" href="<?php echo e(route('suppliers.categories')); ?>">
                <span class="menu-bullet">
                    <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Supplier Categories</span>
            </a>
        </div>
    </div>
    <!--end:General-->
    <div class="menu-sub menu-sub-accordion">
        <!--begin:Menu item-->
        <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
            <!--begin:Menu link-->
            <span class="menu-link">
                <span class="menu-bullet">
                    <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Inspection Status</span>
                <span class="menu-arrow"></span>
            </span>
            <!--end:Menu link-->
            <!--begin:Menu sub-->
            <div class="menu-sub menu-sub-accordion">
                <!--begin:Menu item-->
                <div class="menu-item">
                    <!--begin:Menu link-->
                    <a class="menu-link" href="<?php echo e(route('suppliers.inspected')); ?>">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span class="menu-title">Inspected</span>
                    </a>
                    <!--end:Menu link-->
                </div>
                <div class="menu-item">
                    <!--begin:Menu link-->
                    <a class="menu-link" href="<?php echo e(route('suppliers.uninspected')); ?>">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span class="menu-title">Not Inspected</span>
                    </a>
                    <!--end:Menu link-->
                </div>
            </div>
        </div> 
        
    </div>
    <div class="menu-sub menu-sub-accordion">
        <!--begin:Menu item-->
        <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
            <!--begin:Menu link-->
            <span class="menu-link">
                <span class="menu-bullet">
                    <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Compliance Status</span>
                <span class="menu-arrow"></span>
            </span>
            <!--end:Menu link-->
            <!--begin:Menu sub-->
            <div class="menu-sub menu-sub-accordion">
                <!--begin:Menu item-->
                <div class="menu-item">
                    <!--begin:Menu link-->
                    <a class="menu-link" href="<?php echo e(route('suppliers.compliant')); ?>">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span class="menu-title">Compliant</span>
                    </a>
                    <!--end:Menu link-->
                </div>
                <div class="menu-item">
                    <a class="menu-link" href="<?php echo e(route('suppliers.noncompliant')); ?>">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span class="menu-title">Not Compliant</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>








<div data-kt-menu-trigger="click" class="menu-item menu-accordion">
    <!--begin:Menu link-->
    <span class="menu-link">
        <span class="menu-icon">
            <i class="ki-duotone ki-setting-2 fs-2x">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
        </span>
        <span class="menu-title">Configurations</span>
        <span class="menu-arrow"></span>
    </span>
    <!--end:Menu link-->
    <!--begin:Menu sub-->
    <div class="menu-sub menu-sub-accordion">
        <!--begin:General-->
        <div class="menu-item">
            <a class="menu-link" href="<?php echo e(route('checklist.index')); ?>">
                <span class="menu-bullet">
                    <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Checklist</span>
            </a>
        </div>
        <!--end:General-->
        
        <!--end:Compliance-->
    </div>
    <!--end:Menu sub-->
</div>


<div data-kt-menu-trigger="click" class="menu-item menu-accordion">
    <!--begin:Menu link-->
    <span class="menu-link">
        <span class="menu-icon">
            <i class="ki-duotone ki-user-tick fs-2x ">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
        </span>
        <span class="menu-title">Staff</span>
        <span class="menu-arrow"></span>
    </span>
    <!--end:Menu link-->
    <!--begin:Menu sub-->
    <div class="menu-sub menu-sub-accordion">
        <!--begin:Menu item-->
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage Role')): ?>
        
        <div class="menu-item">
            <!--begin:Menu link-->
            <a class="menu-link" href="<?php echo e(route('roles.index')); ?>">
                <span class="menu-bullet">
                    <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Roles</span>
            </a>
            <!--end:Menu link-->
        </div>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage User')): ?>
        <div class="menu-item">
            <!--begin:Menu link-->
            <a class="menu-link" href="<?php echo e(route('users.index')); ?>">
                <span class="menu-bullet">
                    <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Users</span>
            </a>
            <!--end:Menu link-->
        </div>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage Inspectors')): ?>
        <div class="menu-item">
            <!--begin:Menu link-->
            <a class="menu-link" href="<?php echo e(route('inspectors.index')); ?>">
                <span class="menu-bullet">
                    <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Inspectors</span>
            </a>
            <!--end:Menu link-->
        </div>
        <?php endif; ?>
       
    </div>
</div>






<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage Media')): ?>

<div class="menu-item">
    <a class="menu-link" href="<?php echo e(route('media-upload.index')); ?>">
        <span class="menu-icon">
            <i class="ki-duotone ki-file-up fs-2x">
                <span class="path1"></span>
                <span class="path2"></span>
                <span class="path3"></span>
                <span class="path4"></span>
            </i>
        </span>
        <span class="menu-title">Documents</span>
    </a>
</span>
</div>
<?php endif; ?>


<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage Settings')): ?>
<div class="menu-item">
    <a class="menu-link" href="<?php echo e(route('settings')); ?>">
        <span class="menu-icon">
            <i class="ki-duotone ki-file-up fs-2x">
                <span class="path1"></span>
                <span class="path2"></span>
                <span class="path3"></span>
                <span class="path4"></span>
            </i>
        </span>
        <span class="menu-title">Settings</span>
    </a>
</span>
</div>
<?php endif; ?>

<?php endif; ?>




</div>
</div>

</div>



<?php /**PATH /home/u931094414/domains/royalblue-stinkbug-731974.hostingersite.com/public_html/resources/views/partials/admin/menu.blade.php ENDPATH**/ ?>