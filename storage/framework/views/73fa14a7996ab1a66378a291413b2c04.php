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
                        <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bolder fs-1 lh-0">All Categories</h1>
                    </div>
                    <button type="button" class="btn btn-primary"data-url="<?php echo e(route('suppliers.create_category')); ?>" data-ajax-popup="true" data-title="<?php echo e(__('Add New Category')); ?>" data-size="sm" title="<?php echo e(__('Add Category')); ?>" data-bs-toggle="tooltip" data-bs-placement="top">Add Category</button>
                    
                </div>
            </div>
        </div>
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-fluid">
                <div class="col-xl-12">
                    <div class="card-header card-body table-border-style">
                        <div class="table-responsive">
                            <table class="table table-rounded table-striped border gy-7 gs-10" id="example">
                                <thead>
                                    <tr class="fw-semibold bg-primary  fs-6 text-white border-bottom border-gray-200">
                                        <th><?php echo e(__('Category')); ?></th>
                                        <th><?php echo e(__('Action')); ?></th>
                                        
                                    </tr>
                                </thead>
                                <tbody class="fw-semibold text-gray-600">
                                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($category->name); ?></td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-primary btn-flex btn-center btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                                <i class="ki-duotone ki-down fs-5 ms-1"></i></a>
                                                <!--begin::Menu-->
                                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                                                    <?php if(Gate::check('Edit Suppliers') || Gate::check('Delete Suppliers')): ?>
                                                    
                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Edit Suppliers')): ?>                                                   
                                                    <div class="menu-item px-3">
                                                        <a  class="enu-link px-3"data-url="<?php echo e(route('suppliers.edit_supplier_category', \Illuminate\Support\Facades\Crypt::encrypt($category->id))); ?>" data-ajax-popup="true" data-title="<?php echo e(__('Edit Category')); ?>" data-size="md" title="<?php echo e(__('Edit Category')); ?>" data-bs-toggle="tooltip" data-bs-placement="top">Edit</a>

                                                    </div>
                                                    <?php endif; ?>
                                                    

                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Delete Suppliers')): ?>                                                   
                                                    <div class="menu-item px-3">
                                                        <a href="<?php echo e(route('suppliers.destroy_supplier_category', \Illuminate\Support\Facades\Crypt::encrypt($category->id))); ?>" class="menu-link px-3" data-kt-customer-table-filter="delete_row">Delete</a>
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

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/dismas/Desktop/fe/resources/views/suppliers/categories.blade.php ENDPATH**/ ?>