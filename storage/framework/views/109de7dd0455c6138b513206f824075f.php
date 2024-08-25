<title>Manage Checklist</title>
<?php
$logo = \App\Models\Utility::get_file('avatars/');
?>

<?php $__env->startSection('content'); ?>
<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_toolbar" class="app-toolbar pt-5">
            <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
                <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                    <div class="page-title d-flex flex-column gap-1 me-3 mb-2">
                        <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bolder fs-1 lh-0">All Checklist</h1>
                    </div>
                    <button type="button" class="btn btn-primary"data-url="<?php echo e(route('chapters.create')); ?>" data-ajax-popup="true" data-title="<?php echo e(__('Add New Chapter')); ?>" data-size="sm" title="<?php echo e(__('Add Chapter')); ?>" data-bs-toggle="tooltip" data-bs-placement="top">Add Checklist</button>
                    
                </div>
            </div>
        </div>
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-fluid">
                <div class="col-xl-12">
                    <div class="card-header card-body table-border-style">
                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 gy-5" id="example">
                                <thead>
                                    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                        <th><?php echo e(__('Chapter')); ?></th>
                                        <th><?php echo e(__('Subchapter')); ?></th>
                                        <th><?php echo e(__('Requirement')); ?></th>

                                        <th><?php echo e(__('Action')); ?></th>
                                        
                                    </tr>
                                </thead>
                                <tbody class="fw-semibold text-gray-600">
                                    <?php $__currentLoopData = $checklists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $checklist): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($checklist->chapter); ?></td>

                                    <td><?php echo e($checklist->subchapter); ?></td>
                                    <td><?php echo e($checklist->requirement); ?></td>

                                        <td>
                                            <a href="#" class="btn btn-sm btn-primary btn-flex btn-center btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                                <i class="ki-duotone ki-down fs-5 ms-1"></i></a>
                                                <!--begin::Menu-->
                                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                                                    <?php if(Gate::check('Edit Checklist') || Gate::check('Delete Checklist')): ?>
                                                    
                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Edit Checklist')): ?>
                                                    <div class="menu-item px-3">
                                                        
                                                    </div>
                                                    <?php endif; ?>
                                                    

                                                    <div class="menu-item px-3">
                                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Delete Checklist')): ?>
                                                            <?php echo Form::open(['method' => 'DELETE', 'route' => ['chapters.destroy', $checklist->id], 'id' => 'delete-form-' . $checklist->id]); ?>

                                                            <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                                data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                                                                aria-label="Delete">Delete</a>
                                                            </form>
                                                        </div>
                                                    <?php endif; ?>                                                    
                                                    <?php endif; ?>
                                                    
                                              
                                                </div>
                                            </td>                                        
                                        </tr>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/dismas/Desktop/fe/resources/views/configurations/index.blade.php ENDPATH**/ ?>