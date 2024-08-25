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
                        <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bolder fs-1 lh-0">UnInspected Farmers</h1>
                    </div>
                    <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                        <button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                            <i class="ki-duotone ki-filter fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>Filter
                        </button>
                        
                        <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true" id="kt-toolbar-filter">
                            <div class="px-7 py-5">
                                <div class="fs-4 text-dark fw-bold">Filter Options</div>
                            </div>
                            <div class="separator border-gray-200"></div>
                            <form id="filter-form">

                            <div class="px-7 py-5">
                            <label class="form-label fs-5 fw-semibold mb-3">Date Range</label>

                                <div class="mb-10">
                                <div class="input-group">
                                <input type="date" id="date-start" class="form-control me-2" name="date_start" placeholder="<?php echo e(__('Start Date')); ?>">
                                <span class="input-group-text"><?php echo e(__('to')); ?></span>
                                <input type="date" id="date-end" class="form-control" name="date_end" placeholder="<?php echo e(__('End Date')); ?>">
                            </div>
                                    <label class="form-label fs-5 fw-semibold mb-3">Select Status</label>
                                    <select class="form-select form-select-solid fw-bold" data-kt-select2="true" data-placeholder="Select option" data-allow-clear="true" data-kt-customer-table-filter="month" data-dropdown-parent="#kt-toolbar-filter">
                                        <option></option>
                                        <option value="pending"><?php echo e(__('Inspected')); ?></option>
                                <option value="completed"><?php echo e(__('Incomplete')); ?></option>
                                    </select>
                                </div>
                                <div class="mb-10">
                                    <label class="form-label fs-5 fw-semibold mb-3">Route:</label>
                                    <div class="d-flex flex-column flex-wrap fw-semibold" data-kt-customer-table-filter="payment_type">
                                        <label class="form-check form-check-sm form-check-custom form-check-solid mb-3 me-5">
                                        <input type="text" class="form-control" id="route" name="route">
                                        </label>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="reset" class="btn btn-light btn-active-light-primary me-2" data-kt-menu-dismiss="true" data-kt-customer-table-filter="reset">Reset</button>
                                    <button type="submit" class="btn btn-primary" data-kt-menu-dismiss="true">Apply</button>
                                </div>
                            </div>
                            </form>
                        </div>
                        <button type="button" class="btn btn-light-primary me-3" data-bs-toggle="modal" data-bs-target="#kt_customers_export_modal">
                            <i class="ki-duotone ki-exit-up fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>Export
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
                                    <th><?php echo e(__('Grower No.')); ?></th>
                                <th><?php echo e(__('ID NO')); ?></th>
                                <th><?php echo e(__('Name')); ?></th>

                                <th><?php echo e(__('Year')); ?></th>
                                <th><?php echo e(__('Phone Number')); ?></th>
                                <th><?php echo e(__('Route')); ?></th>
                                <th><?php echo e(__('Production Area')); ?></th>
                                <th><?php echo e(__('Inspection Status')); ?></th>
                                <th><?php echo e(__('Inspection Date')); ?></th>
                                <?php if(Gate::check('Edit Inspections') || Gate::check('Delete Inspections')): ?>
                                            <th class="text-end min-w-70px">Actions</th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody class="fw-semibold text-black">
                                    <?php $__currentLoopData = $farmers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $farmer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                        <td>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Show Inspections Report')): ?>
                                            <a class="btn btn-outline-primary"
                                                href="<?php echo e(route('inspections.show', \Illuminate\Support\Facades\Crypt::encrypt($farmer->id))); ?>"><?php echo e($farmer->grower_id); ?></a>
                                        <?php else: ?>
                                            <a href="#" class="btn btn-outline-primary"><?php echo e($farmer->grower_id); ?></a>
                                        <?php endif; ?>
                                    </td>
                                    
                                    <td><?php echo e($farmer->id_number); ?></td>

<td>2024</td>

<td><?php echo e($farmer->first_name); ?></td>
<td><?php echo e($farmer->last_name); ?></td>
<td><?php echo e($farmer->phone_number); ?></td>

<td><?php echo e($farmer->route); ?></td>
<td><?php echo e($farmer->production_area); ?></td>
<td><?php echo e($farmer->inspection_status); ?></td>
<td><?php echo e(\Auth::user()->dateFormat($farmer->inspection_date)); ?></td>


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
                                            <?php if(Gate::check('Show Farmers') || Gate::check('Delete Inspections')): ?>

                                            <td class="Action text-end">
                                                <span>
                                                    
                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Show Farmers')): ?>
                                                        <div class="action-btn bg-primary ms-2">
                                                            <a href="<?php echo e(route('farmer.show', \Illuminate\Support\Facades\Crypt::encrypt($farmer->id))); ?>"
                                                                class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip" title="<?php echo e(__('Edit')); ?>">
                                                                <i class="ti ti-pencil text-white"></i>
                                                            </a>
                                                        </div>
                                                    <?php endif; ?>
                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Show Farmers')): ?>
                                                    <a class="btn btn-outline-primary"
                                                    href="<?php echo e(route('inspections.show', \Illuminate\Support\Facades\Crypt::encrypt($farmer->id))); ?>">View Inpsection</a>
                                                <?php endif; ?>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Show Farmers')): ?>
                                                    <a class="btn btn-outline-primary"
                                                    href="<?php echo e(route('farmer.edit', \Illuminate\Support\Facades\Crypt::encrypt($farmer->id))); ?>">Edit Farmer</a>
                                                <?php endif; ?>
                                                </span>
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
                let startDate = $('#date-start').val();
                let endDate = $('#date-end').val();
                let inspectionStatus = $('#inspection-status').val().toLowerCase();
                let route = $('#route').val().toLowerCase();
    
                // Filter table data
                $('table tbody tr').each(function() {
                    let row = $(this);
                    let inspectionDate = row.find('td:nth-child(9)').text();
                    let status = row.find('td:nth-child(10)').text().toLowerCase();
                    let routeValue = row.find('td:nth-child(7)').text().toLowerCase();
    
                    // Apply filters
                    let dateInRange = (startDate === '' || endDate === '' || (inspectionDate >= startDate && inspectionDate <= endDate));
                    let statusMatch = (inspectionStatus === '' || status === inspectionStatus);
                    let routeMatch = (route === '' || routeValue.includes(route));
    
                    // Show or hide rows based on filter criteria
                    if (dateInRange && statusMatch && routeMatch) {
                        row.show();
                    } else {
                        row.hide();
                    }
                });
            });
        });
    </script>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u931094414/domains/royalblue-stinkbug-731974.hostingersite.com/public_html/resources/views/farmers/uninspected.blade.php ENDPATH**/ ?>