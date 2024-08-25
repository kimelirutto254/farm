<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="card">
        <div id="kt_app_toolbar" class="app-toolbar pt-5">
            <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
                <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                    <div class="page-title d-flex flex-column gap-1 me-3 mb-2">
                        <h1
                            class="page-heading d-flex flex-column justify-content-center text-dark fw-bolder fs-1 lh-0">
                            All Roles</h1>
                    </div>
                    <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold">
                       
                        <li class="nav-item mt-2">
                            <a class="btn btn-primary text-white me-2" data-url="<?php echo e(route('roles.create')); ?>" data-title="<?php echo e(__('Add Role')); ?>"
                            data-size="lg" data-ajax-popup="true" data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo e(__('Create')); ?>">
                            Create New Role
                        </a>
                        </li>

                    </ul>
                </div>
            </div>
        </div>
    </div>
<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-fluid">

       
                    <div class="table-responsive">
                        <table class="table" id="example">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('Role')); ?></th>
                                    <th><?php echo e(__('Permissions')); ?></th>
                                    <th width="200px"><?php echo e(__('Action')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($role->name); ?></td>
                                        <td style="white-space: inherit">
                                            <?php $__currentLoopData = $role->permissions()->pluck('name'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <span class="badge rounded p-2 m-1 px-3 bg-primary ">
                                                    <a href="#" class="text-white"><?php echo e($permission); ?></a>
                                                </span>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </td>
                                        <td class="Action">
                                            <span>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Edit Role')): ?>
                                                    <div class="action-btn ms-2">
                                                        <a class=" bg-light-secondary me-2"
                                                            data-url="<?php echo e(URL::to('roles/' . $role->id . '/edit')); ?>"
                                                            data-ajax-popup="true" data-size="lg" data-bs-toggle="tooltip"
                                                            title="" data-title="<?php echo e(__('Edit Role')); ?>"
                                                            data-bs-original-title="<?php echo e(__('Edit')); ?>">
                                                           Edit
                                                        </a>
                                                    </div>
                                                <?php endif; ?>

                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Delete Role')): ?>
                                                    <div class="action-btn ms-2">
                                                        <a class="bs-pass-para  bg-light-secondary"
                                                            href="#" data-title="<?php echo e(__('Delete Role')); ?>"
                                                            data-confirm="<?php echo e(__('Are You Sure?')); ?>"
                                                            data-text="<?php echo e(__('This action can not be undone. Do you want to continue?')); ?>"
                                                            data-confirm-yes="delete-form-<?php echo e($role->id); ?>"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="<?php echo e(__('Delete')); ?>">
                                                            Delete
                                                           

                                                        </a>
                                                        <?php echo Form::open([
                                                            'method' => 'DELETE',
                                                            'route' => ['roles.destroy', $role->id],
                                                            'id' => 'delete-form-' . $role->id,
                                                        ]); ?>

                                                        <?php echo Form::close(); ?>

                                                    </div>
                                                <?php endif; ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/dismas/Desktop/fe/resources/views/roles/index.blade.php ENDPATH**/ ?>