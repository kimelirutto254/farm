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
                        <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bolder fs-1 lh-0">All Suppliers</h1>
                    </div>
                    <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                    
                        <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true" id="kt-toolbar-filter">
                            <div class="px-7 py-5">
                                <div class="fs-4 text-dark fw-bold">Filter Options</div>
                            </div>
                            <form id="filter-form">
                                
                                <div class="separator border-gray-200"></div>
                                <div class="px-7 py-5">
                                    <div class="mb-10">
                                        <label class="form-label fs-5 fw-semibold mb-3">Month:</label>
                                        <select class="form-select form-select-solid fw-bold" data-kt-select2="true" data-placeholder="Select option" data-allow-clear="true" data-kt-customer-table-filter="month" data-dropdown-parent="#kt-toolbar-filter">
                                            <option></option>
                                            <option value="aug">August</option>
                                            <option value="sep">September</option>
                                            <option value="oct">October</option>
                                            <option value="nov">November</option>
                                            <option value="dec">December</option>
                                        </select>
                                    </div>
                                    
                                    <div class="mb-10">
                                        <label class="form-label fs-5 fw-semibold mb-3">Category:</label>
                                        <div class="d-flex flex-column flex-wrap fw-semibold" data-kt-customer-table-filter="payment_type">
                                            <label class="form-check form-check-sm form-check-custom form-check-solid mb-3 me-5">
                                                <input type="text" class="form-control" id="supplier-name" name="supplier_name">
                                            </label>
                                        </div>
                                    </div>
                                    <div class="mb-10">
                                        <label class="form-label fs-5 fw-semibold mb-3">Supplier Name:</label>
                                        <div class="d-flex flex-column flex-wrap fw-semibold" data-kt-customer-table-filter="payment_type">
                                            <label class="form-check form-check-sm form-check-custom form-check-solid mb-3 me-5">
                                                <input type="text" class="form-control" id="supplier-name" name="supplier_name">
                                            </label>
                                        </div>
                                    </div>
                                    <div class="mb-10">
                                        <label class="form-label fs-5 fw-semibold mb-3">Location:</label>
                                        <div class="d-flex flex-column flex-wrap fw-semibold" data-kt-customer-table-filter="payment_type">
                                            <label class="form-check form-check-sm form-check-custom form-check-solid mb-3 me-5">
                                                <input type="text" class="form-control" id="supplier-name" name="supplier_name">
                                            </label>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <button type="reset" class="btn btn-light btn-active-light-primary me-2" data-kt-menu-dismiss="true" data-kt-customer-table-filter="reset">Reset</button>
                                        <button type="submit" class="btn btn-primary" data-kt-menu-dismiss="true" data-kt-customer-table-filter="filter">Apply</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <a <?php echo e(route('suppliers.export')); ?> class="btn btn-light-primary me-3">
                            <i class="ki-duotone ki-exit-up fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>Export
                        </a>
                        <button type="button" class="btn btn-primary"data-url="<?php echo e(route('suppliers.create')); ?>" data-ajax-popup="true" data-title="<?php echo e(__('Add New Supplier')); ?>" data-size="md" title="<?php echo e(__('Add Supplier')); ?>" data-bs-toggle="tooltip" data-bs-placement="top">Add Supplier</button>
                    </div>
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
                                            <th class="min-w-125px">Grower ID</th>
                                        <th><?php echo e(__('Supplier Name')); ?></th>
                                        <th><?php echo e(__('Location')); ?></th>
                                        <th><?php echo e(__('Registered Date')); ?></th>
                                        <th><?php echo e(__('Status')); ?></th>
                                        
                                        <?php if(Gate::check('Edit Suppliers') || Gate::check('Delete Suppliers')): ?>
                                        <th class="text-end min-w-70px">Actions</th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody class="fw-semibold text-gray-600">
                                    <?php $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supplier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($supplier->id); ?></td>
                                        <td><?php echo e($supplier->name); ?></td>
                                        <td><?php echo e($supplier->location); ?></td>
                                        <td><?php echo e(\Auth::user()->dateFormat($supplier->created_at)); ?></td>
                                        <td><?php echo e($supplier->inspection_status); ?></td>
                                        
                                        
                                        <td class="text-end">
                                            <a href="#" class="btn btn-sm btn-primary btn-flex btn-center btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                                <i class="ki-duotone ki-down fs-5 ms-1"></i></a>
                                                <!--begin::Menu-->
                                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                                                    <?php if(Gate::check('Edit Suppliers') || Gate::check('Delete Suppliers')): ?>
                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Edit Suppliers')): ?>                                                   
                                                    <div class="menu-item px-3">
                                                        <a href="<?php echo e(route('suppliers.edit', \Illuminate\Support\Facades\Crypt::encrypt($supplier->id))); ?>" class="menu-link px-3" data-kt-customer-table-filter="delete_row">Edit</a>
                                                    </div>
                                                    <?php endif; ?>
                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Delete Suppliers')): ?>                                                   
                                                    <div class="menu-item px-3">
                                                        <a href="<?php echo e(route('suppliers.delete', \Illuminate\Support\Facades\Crypt::encrypt($supplier->id))); ?>" class="menu-link px-3" data-kt-customer-table-filter="delete_row">Delete</a>
                                                    </div>
                                                    <?php endif; ?>
                                                </div>
                                            </td>                                        
                                        </tr>
                                        <?php endif; ?>
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
    <script>
        $(document).ready(function() {
            $('#filter-form').submit(function(event) {
                event.preventDefault(); // Prevent form submission
                
                // Retrieve filter values
                let supplierName = $('#supplier-name').val().toLowerCase();
                let category = $('#category').val().toLowerCase();
                let location = $('#location').val().toLowerCase();
                
                // Filter table data
                $('table tbody tr').each(function() {
                    let row = $(this);
                    let name = row.find('td:nth-child(2)').text().toLowerCase();
                    let cat = row.find('td:nth-child(3)').text().toLowerCase();
                    let loc = row.find('td:nth-child(4)').text().toLowerCase();
                    
                    // Apply filters
                    let nameMatch = (supplierName === '' || name.includes(supplierName));
                    let catMatch = (category === '' || cat.includes(category));
                    let locMatch = (location === '' || loc.includes(location));
                    
                    // Show or hide rows based on filter criteria
                    if (nameMatch && catMatch && locMatch) {
                        row.show();
                    } else {
                        row.hide();
                    }
                });
            });
        });
    </script>
    
    
    
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/dismas/Desktop/fe/resources/views/suppliers/index.blade.php ENDPATH**/ ?>