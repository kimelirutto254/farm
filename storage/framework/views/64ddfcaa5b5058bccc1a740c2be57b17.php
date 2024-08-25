<?php
$logo = \App\Models\Utility::get_file('avatars/');
?>

<?php $__env->startSection('content'); ?>
<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_toolbar" class="app-toolbar pt-5">
            <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
                <div class="app-tooFlbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                    <div class="page-title d-flex flex-column gap-1 me-3 mb-2">
                        <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bolder fs-1 lh-0">All Inspectors</h1>
                    </div>
                    <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                        <button type="button" class="btn btn-light-primary ms-2" data-url="<?php echo e(route('inspectors.importForm')); ?>" data-ajax-popup="true" data-title="<?php echo e(__('Upload Inspectors')); ?>" data-size="md" title="<?php echo e(__('Upload Inspectors')); ?>" data-bs-toggle="tooltip" data-bs-placement="top">
                            <i class="ki-duotone ki-exit-up fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            Import
                        </button>
                    
                        <button type="button" class="btn btn-light-primary ms-2" data-bs-toggle="modal" data-bs-target="#kt_customers_export_modal">
                            <i class="ki-duotone ki-cloud-download fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            Export
                        </button>
                    
                        <button type="button" class="btn btn-primary ms-2" data-url="<?php echo e(route('inspectors.create')); ?>" data-ajax-popup="true" data-title="<?php echo e(__('Add New Inspector')); ?>" data-size="md" title="<?php echo e(__('Add Inspector')); ?>" data-bs-toggle="tooltip" data-bs-placement="top">
                            Add Inspector
                        </button>
                    </div>
                    
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
                                        <th class="min-w-125px">Name</th>
                                        <th class="min-w-125px">Email</th>
                                        <th class="min-w-125px">Status</th>
                                        <th class="min-w-125px">Created At</th>
                                        <?php if(Gate::check('Edit Inspectors') || Gate::check('Delete Inspectors')): ?>
                                        <th class="text-end min-w-70px">Actions</th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody class="fw-semibold text-gray-600">
                                    <?php $__currentLoopData = $inspectors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inspector): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        
                                        <td><?php echo e($inspector->name); ?></td>
                                        <td><?php echo e($inspector->email); ?></td>
                                        
                                         <td>
                                            <?php if($inspector->status == 1): ?>
                                            <div class="status_badge text-capitalize badge bg-info p-2 px-3 rounded">
                                                <?php echo e(__('Active')); ?>

                                            </div>
                                            <?php elseif($inspector->status == 0): ?>
                                            <div class="status_badge text-capitalize badge bg-success p-2 px-3 rounded">
                                                <?php echo e(__('Not Active')); ?>

                                            </div>
                                           
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e(\Auth::user()->dateFormat($inspector->created_at)); ?></td>

                                        <td class="text-end">
                                            <a href="#" class="btn btn-sm btn-primary btn-flex btn-center btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                                <i class="ki-duotone ki-down fs-5 ms-1"></i></a>
                                                <!--begin::Menu-->
                                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                                                    <!--begin::Menu item-->
                                                   
                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage Inspectors')): ?>
                                                    <div class="menu-item px-3">
                                                        <a class="menu-link px-3" href="<?php echo e(route('inspectors.show', \Illuminate\Support\Facades\Crypt::encrypt($inspector->id))); ?>">View</a>
                                                       
                                                    </div>
                                                    <?php endif; ?>
                                                    
                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Edit Inspectors')): ?>
                                                    <div class="menu-item px-3">
                                                        <a class="menu-link px-3" 
                                                           data-url="<?php echo e(route('inspector.edit', \Illuminate\Support\Facades\Crypt::encrypt($inspector->id))); ?>" 
                                                           data-ajax-popup="true" 
                                                           data-title="<?php echo e(__('Edit Inspector')); ?>" 
                                                           data-size="md" 
                                                           title="<?php echo e(__('Edit Inspector')); ?>" 
                                                           data-bs-toggle="tooltip" 
                                                           data-bs-placement="top">
                                                           Edit
                                                        </a>
                                                    </div>
                                                    <?php endif; ?>
                                                    
                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Delete Inspectors')): ?>
                                                    <div class="menu-item px-3">
                                                        <a class="menu-link px-3" 
                                                           data-url="<?php echo e(route('inspector.delete', \Illuminate\Support\Facades\Crypt::encrypt($inspector->id))); ?>" 
                                                           data-ajax-popup="true" 
                                                           data-title="<?php echo e(__('Delete Inspector')); ?>" 
                                                           data-size="md" 
                                                           title="<?php echo e(__('Delete Inspector')); ?>" 
                                                           data-bs-toggle="tooltip" 
                                                           data-bs-placement="top">
                                                           Delete
                                                        </a>
                                                    </div>
                                                    <?php endif; ?>
                                                    
                                                </div>
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
        </div>
    </div>
    <?php $__env->stopSection(); ?>
    
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/dismas/Desktop/fe/resources/views/inspectors/index.blade.php ENDPATH**/ ?>