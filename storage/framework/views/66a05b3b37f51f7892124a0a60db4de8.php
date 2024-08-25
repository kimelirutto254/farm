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
                        <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bolder fs-1 lh-0">All Farmers</h1>
                    </div>
                    <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                      
                        <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true" id="kt-toolbar-filter">
                            <div class="px-7 py-5">
                                <div class="fs-4 text-dark fw-bold">Filter Options</div>
                            </div>
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
                                    <label class="form-label fs-5 fw-semibold mb-3">Payment Type:</label>
                                    <div class="d-flex flex-column flex-wrap fw-semibold" data-kt-customer-table-filter="payment_type">
                                        <label class="form-check form-check-sm form-check-custom form-check-solid mb-3 me-5">
                                            <input class="form-check-input" type="radio" name="payment_type" value="all" checked="checked" />
                                            <span class="form-check-label text-gray-600">All</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="reset" class="btn btn-light btn-active-light-primary me-2" data-kt-menu-dismiss="true" data-kt-customer-table-filter="reset">Reset</button>
                                    <button type="submit" class="btn btn-primary" data-kt-menu-dismiss="true" data-kt-customer-table-filter="filter">Apply</button>
                                </div>
                            </div>
                        </div>
                        <a href="<?php echo e(route('farmers.export')); ?>" class="btn btn-light-primary me-3 ">
                            <i class="ki-duotone ki-exit-up fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>Export
                        </a>


                        <button type="button" class="btn btn-primary"data-url="<?php echo e(route('farmers.import')); ?>" data-ajax-popup="true" data-title="<?php echo e(__('Upload Farmers')); ?>" data-size="md" title="<?php echo e(__('Upload Farmer')); ?>" data-bs-toggle="tooltip" data-bs-placement="top"> <i class="ki-duotone ki-exit-up fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i></button>
                        <button type="button" class="btn btn-primary"data-url="<?php echo e(route('farmer.create')); ?>" data-ajax-popup="true" data-title="<?php echo e(__('Add New Farmer')); ?>" data-size="lg" title="<?php echo e(__('Add Farmer')); ?>" data-bs-toggle="tooltip" data-bs-placement="top">Add Farmer</button>


                   
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
                                        <th class="min-w-125px">Name</th>

                                        <th class="min-w-125px">ID NO</th>

                                        <th class="min-w-125px">Year</th>
                                        <th class="min-w-125px">Region</th>
                                        <th class="min-w-125px">Production Area</th>
                                        <th class="min-w-125px">Phone Number</th>
                                        <th class="min-w-125px">Created At</th>
                                        <?php if(Gate::check('Edit Farmers') || Gate::check('Delete Farmers')): ?>
                                        <th class="text-end min-w-70px">Actions</th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody class="">
                                    <?php $__currentLoopData = $farmers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $farmer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($farmer->grower_id); ?></td>
                                        <td><?php echo e($farmer->first_name); ?> <?php echo e($farmer->middle_name); ?> <?php echo e($farmer->last_name); ?></td>

                                        <td><?php echo e($farmer->id_number); ?></td>
                                        <td>2024</td>
                                        <td><?php echo e($farmer->region); ?></td>
                                        <td><?php echo e($farmer->production_area); ?></td>
                                        <td><?php echo e($farmer->phone_number); ?></td>
                                        <td><?php echo e(\Auth::user()->dateFormat($farmer->created_at)); ?></td>
                                        
                                        <!-- <td>
                                            <?php if($farmer->status == 'draft'): ?>
                                            <div class="status_badge text-capitalize badge bg-info p-2 px-3 rounded">
                                                <?php echo e(__('Draft')); ?>

                                            </div>
                                            <?php elseif($farmer->status == 'scheduled'): ?>
                                            <div class="status_badge text-capitalize badge bg-success p-2 px-3 rounded">
                                                <?php echo e(__('Scheduled')); ?>

                                            </div>
                                            <?php elseif($farmer->status == 'published'): ?>
                                            <div class="status_badge text-capitalize badge bg-primary p-2 px-3 rounded">
                                                <?php echo e(__('Published')); ?>

                                            </div>
                                            <?php endif; ?>
                                        </td> -->
                                        <td class="text-end">
                                            <a href="#" class="btn btn-sm btn-primary btn-flex btn-center btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                                <i class="ki-duotone ki-down fs-5 ms-1"></i></a>
                                                <!--begin::Menu-->
                                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                                                    <!--begin::Menu item-->
                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Show Farmers')): ?>
                                                    <div class="menu-item px-3">
                                                        <a href="<?php echo e(route('farmer.show', \Illuminate\Support\Facades\Crypt::encrypt($farmer->id))); ?>" class="menu-link px-3">View Detail</a>
                                                    </div>
                                                    
                                                    <?php endif; ?>
                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage Inspections')): ?>
                                                    <div class="menu-item px-3">
                                                        <a href="<?php echo e(route('inspections.show', \Illuminate\Support\Facades\Crypt::encrypt($farmer->id))); ?>" class="menu-link px-3">View Report</a>
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
    
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u931094414/domains/royalblue-stinkbug-731974.hostingersite.com/public_html/resources/views/farmers/index.blade.php ENDPATH**/ ?>