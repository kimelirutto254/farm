<title>Users</title>
<?php $__env->startSection('filter'); ?>
<?php $__env->stopSection(); ?>
<?php
    $logo = \App\Models\Utility::get_file('uploads/profile/');
?>
<?php $__env->startSection('content'); ?>
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
                                        <?php if(Gate::check('Manage Tenants')): ?>
            <div class="col-auto pe-0">
            <a class="btn btn-sm  text-white  btn-primary me-2" data-url="<?php echo e(route('users.create')); ?>"
        data-title="<?php echo e(__('Add User')); ?>" data-ajax-popup="true" data-bs-toggle="tooltip" data-bs-placement="top"
        title="<?php echo e(__('Create')); ?>">
        Add New User
    </a>
            </div>
        <?php endif; ?>										<!--end::Actions-->
									</div>
									<!--end::Toolbar wrapper-->
								</div>
								<!--end::Toolbar container-->
							</div>
                            
							<!--end::Toolbar-->
    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-md-6 col-xl-3">
            <div class="card border-hover-primary">
                <div class="card-header border-0 pt-9">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fs-3 fw-bold text-dark" style="text-transform: uppercase;">
                                <?php echo e($user->name); ?> |
                            </div>
                        </div>
                        
                        <span class="badge text-white fw-bold px-4 py-2
                        <?php if($user->is_active == 1): ?>
                            bg-success
                        <?php else: ?>
                            bg-danger
                        <?php endif; ?>
                    ">
                            <?php if($user->is_active == 1): ?>
                                ACTIVE
                            <?php else: ?>
                                Inactive
                            <?php endif; ?>
                        </span>
                    </div>
                    <div class="dropdown">
                            <button class="btn-sm btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Actions
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                                <?php if(Gate::check('Edit User') || Gate::check('Delete User') || Gate::check('Reset Password')): ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Edit User')): ?>
                                        <li><a class="dropdown-item" href="<?php echo e(route('users.edit', $user->id)); ?>">Edit</a></li>
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Reset Password')): ?>
                                        <li><a class="dropdown-item"
                                                href="<?php echo e(route('users.reset', \Crypt::encrypt($user->id))); ?>">Reset Password</a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Delete User')): ?>
                                        <li>
                                            <?php echo Form::open(['method' => 'DELETE', 'route' => ['users.destroy', $user->id], 'id' => 'delete-form-' . $user->id]); ?>

                                            <a href="#" class="dropdown-item bs-pass-para" data-confirm="<?php echo e(__('Are You Sure?')); ?>"
                                                data-text="<?php echo e(__('This action can not be undone. Do you want to continue?')); ?>"
                                                data-confirm-yes="delete-form-<?php echo e($user->id); ?>" title="<?php echo e(__('Delete')); ?>"
                                                data-bs-toggle="tooltip" data-bs-placement="top">
                                                Delete
                                            </a>
                                            <?php echo Form::close(); ?>

                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                
                                <?php if($user->is_enable_login == 1): ?>
                                    <li><a href="<?php echo e(route('owner.users.login', \Crypt::encrypt($user->id))); ?>"
                                            class="dropdown-item">Login Disable</a></li>
                                <?php elseif($user->is_enable_login == 0 && $user->password == null): ?>
                                    <li><a href="#" data-url="<?php echo e(route('users.reset', \Crypt::encrypt($user->id))); ?>"
                                            data-ajax-popup="true" data-size="md" class="dropdown-item login_enable">Login
                                            Enable</a></li>
                                <?php else: ?>
                                    <li><a href="<?php echo e(route('owner.users.login', \Crypt::encrypt($user->id))); ?>"
                                            class="dropdown-item">Login Enable</a></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                </div>
                <div class="card-body p-9 text-center">
                    <div class="fs-3 fw-bold text-dark"><?php echo e($user->type); ?></div>
                    <p class="text-gray-400 fw-semibold fs-5 mt-1 mb-7"><?php echo e($user->email); ?></p>
                    <div class="fs-6 text-gray-800 fw-bold">
                                <?php echo e($user->created_at->format('F j, Y H:i')); ?>

                            </div>
                </div>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <div class="col-md-6 col-xl-3">
            <div class="card border-hover-primary">
                <div class="card-header border-0 pt-9">
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Create User')): ?>
            <a class="btn-addnew-user" data-url="<?php echo e(route('users.create')); ?>" data-title="<?php echo e(__('Add User')); ?>"
                data-ajax-popup="true" data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo e(__('Create')); ?>"><i
                    class="ti ti-plus text-white"></i>
                <div class="bg-primary proj-add-icon">
                    <i class="ti ti-plus"></i>
                </div>
                <h6 class="mt-4 mb-2"><?php echo e(__('New User')); ?></h6>
                <p class="text-muted text-center"><?php echo e(__('Click here to add New User')); ?></p>
            </a>
        <?php endif; ?>
    </div>
    </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script-page'); ?>
    
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
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/dismas/Desktop/fe/resources/views/users/index.blade.php ENDPATH**/ ?>