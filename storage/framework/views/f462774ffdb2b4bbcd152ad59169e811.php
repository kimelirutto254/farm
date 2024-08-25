Here’s the updated code with the `status` field removed and the size of the phone number increased:

```php


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
                        <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bolder fs-2 lh-1">Farmer Overview</h1>
                    </div>
                </div>
            </div>
        </div>
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-fluid">
                <div class="card mb-5 mb-xl-10">
                    <div class="card-body pt-9 pb-0">
                        <div class="d-flex flex-wrap flex-sm-nowrap mb-3">
                            <div class="flex-grow-1">
                                <div class="row g-6 g-xl-9">
                                    <div class="col-lg-3 col-xxl-3">
                                        <!--begin::Farmer Details-->
                                        <div class="fs-6 mb-4">
                                            <div class="fw-semibold">Name: <?php echo e($farmer->first_name); ?> <?php echo e($farmer->middle_name); ?> <?php echo e($farmer->last_name); ?></div>
                                        </div>
                                        <div class="fs-6 mb-4">
                                            <div class="fw-semibold">Grower No: <?php echo e($farmer->grower_id); ?></div>
                                        </div>
                                        <div class="fs-6 mb-4">
                                            <div class="fw-semibold">Region: <?php echo e($farmer->region); ?></div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-3 col-xxl-3">
                                        <!--begin::Farmer Details-->
                                        <div class="fs-6 mb-4">
                                            <div class="fw-semibold">Gender: <?php echo e($farmer->gender); ?></div>
                                        </div>
                                        <div class="fs-6 mb-4">
                                            <div class="fw-semibold">DOB: <?php echo e($farmer->dob); ?></div>
                                        </div>
                                        <div class="fs-6 mb-4">
                                            <div class="fw-semibold">ID NO: <?php echo e($farmer->id_number); ?></div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-3 col-xxl-3">
                                        <!--begin::Farmer Details-->
                                        <div class="fs-6 mb-4">
                                            <div class="fw-semibold">Village: <?php echo e($farmer->village); ?></div>
                                        </div>
                                        <div class="fs-6 mb-4">
                                            <div class="fw-semibold">Address: <?php echo e($farmer->address); ?></div>
                                        </div>
                                        <div class="fs-6 mb-4">
                                            <div class="fw-semibold">Phone No: <?php echo e($farmer->phone); ?></div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-3 col-xxl-3">
                                        <!--begin::Farmer Details-->
                                        <div class="fs-6 mb-4">
                                            <div class="fw-semibold">City: <?php echo e($farmer->city); ?></div>
                                        </div>
                                        <div class="fs-6 mb-4">
                                            <div class="fw-semibold">Farm Size: <?php echo e($farmer->farm_size); ?> Acres</div>
                                        </div>
                                        <div class="fs-6 mb-4">
                                            <div class="fw-semibold">Estimated Size: <?php echo e($farmer->estimated_size); ?> Acres</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-6 fw-bold">
                                    
                                    <li class="nav-item mt-2">
                                        <a class="nav-link text-active-primary ms-0 me-10 py-5 active">Inspection Report</a>
                                    </li>
                                    <li class="nav-item mt-2">
                                        <a class="nav-link text-active-primary ms-0 me-10 py-5">Sketch Map</a>
                                    </li>
                                    <li class="nav-item mt-2">
                                        <a class="nav-link text-active-primary ms-0 me-10 py-5">Traceability</a>
                                    </li>
                                    <li class="nav-item mt-2">
                                        <a class="nav-link text-active-primary ms-0 me-10 py-5">Green Leaf Agreement</a>
                                    </li>
                                    <li class="nav-item mt-2">
                                        <a class="nav-link text-active-primary ms-0 me-10 py-5">Teaboard Application</a>
                                    </li>
                                    <li class="nav-item mt-2">
                                        <a class="nav-link text-active-primary ms-0 me-10 py-5">Training Report</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card mb-5 mb-xl-10" id="kt_inspection_details_view">
                            <div id="kt_app_content" class="app-content flex-column-fluid">
                                <!--begin::Content container-->
                                <div id="kt_app_content_container" class="app-container container-fluid">
                                    <!--begin::Card-->
                                    <div class="card">
                                        <!--begin::Card body-->
                                        <div class="card-body p-0">
                                            <!--begin::Wrapper-->
                                            <div class="card-px text-center py-1 my-2">
                                                <!--begin::Title-->
                                                <h2 class="fs-2x fw-bold mb-10">Kaptumo Factory</h2>
                                                <h6 class="fs-1x fw-bold mb-10">FARM INTERNAL CHECKLIST - 2020 STANDARD FOR RAINFOREST ALLIANCE CERTIFICATION : LEVEL 1. 
                                                </h6>
                                                <h6 class="fs-1x fw-bold mb-10"> FARM BASELINE INFORMATION </h6>
                                                <div class="row g-6 g-xl-9">
                                                    <div class="col-lg-3 col-xxl-3">
                                                        <!--begin::Budget-->
                                                        
                                                        <div class="fs-6 d-flex justify-content-between mb-4">
                                                            <div class="fw-semibold">Grower : </div>
                                                            <div class="d-flex fw-bold">
                                                                <?php echo e($farmer->first_name); ?> <?php echo e($farmer->middle_name); ?> <?php echo e($farmer->last_name); ?></div>
                                                            </div>
                                                            <div class="separator separator-dashed"></div>
                                                            <div class="fs-6 d-flex justify-content-between my-4">
                                                                <div class="fw-semibold">Farm Size (Acr) :</div>
                                                                <div class="d-flex fw-bold">
                                                                    <?php echo e($farmer->farm_size); ?></div>
                                                                </div>
                                                                
                                                            </div>
                                                            
                                                            <div class="col-lg-3 col-xxl-3">
                                                                <!--begin::Budget-->
                                                                
                                                                <div class="fs-6 d-flex justify-content-between mb-4">
                                                                    <div class="fw-semibold">longitude: </div>
                                                                    <div class="d-flex fw-bold">
                                                                        latitude</div>
                                                                    </div>
                                                                    
                                                                    <div class="separator separator-dashed"></div>
                                                                    <div class="fs-6 d-flex justify-content-between my-4">
                                                                        <div class="fw-semibold">Production Area </div>
                                                                        <div class="d-flex fw-bold">
                                                                            <?php echo e($farmer->production_area); ?></div>
                                                                        </div>
                                                                        
                                                                    </div>
                                                                    
                                                                    <div class="col-lg-3 col-xxl-3">
                                                                        <!--begin::Budget-->
                                                                        
                                                                        <div class="fs-6 d-flex justify-content-between mb-4">
                                                                            <div class="fw-semibold">Household size: </div>
                                                                            <div class="d-flex fw-bold">
                                                                                <?php echo e($farmer->household_size); ?></div>
                                                                            </div>
                                                                            <div class="separator separator-dashed"></div>
                                                                            <div class="fs-6 d-flex justify-content-between my-4">
                                                                                <div class="fw-semibold">Ecosystem (Acr) :</div>
                                                                                <div class="d-flex fw-bold">
                                                                                    <?php echo e($farmer->grower_id); ?></div>
                                                                                </div>
                                                                                
                                                                            </div>
                                                                            
                                                                            <div class="col-lg-3 col-xxl-3">
                                                                                <!--begin::Budget-->
                                                                                
                                                                                <div class="fs-6 d-flex justify-content-between mb-4">
                                                                                    <div class="fw-semibold">Grower No</div>
                                                                                    <div class="d-flex fw-bold">
                                                                                        <?php echo e($farmer->grower_id); ?></div>
                                                                                    </div>
                                                                                    <div class="separator separator-dashed"></div>
                                                                                    <div class="fs-6 d-flex justify-content-between my-4">
                                                                                        <div class="fw-semibold">Inspector: </div>
                                                                                        <div class="d-flex fw-bold">
                                                                                            <?php echo e($farmer->production_area); ?></div>
                                                                                        </div>
                                                                                        
                                                                                    </div>
                                                                                </div>
                                                                                
                                                                                
                                                                                
                                                                                <h4 class="fs-1x">Worker Details</h4>
                                                                                
                                                                                
                                                                                
                                                                                <div class="row g-12 g-xl-9">
                                                                                    <div class="col-sm-6 col-xxl-6">
                                                                                        <!--begin::Header-->
                                                                                        
                                                                                        <h3 class="card-title align-items-start flex-column">
                                                                                            <span class="card-label fw-bold text-gray-800">Crops</span>
                                                                                        </h3>
                                                                                        
                                                                                        
                                                                                        
                                                                                        
                                                                                        <div class="table-responsive">
                                                                                            <table class="table table-row-dashed align-middle gs-0 gy-4 my-0">
                                                                                                <thead>
                                                                                                    <tr class="fs-7 fw-bold text-black-500 border-bottom-0">
                                                                                                        <th class="">Crop</th>
                                                                                                        <th class="">Variety</th>
                                                                                                        <th class="">Age</th>
                                                                                                        <th class="">Population </th>
                                                                                                    </tr>
                                                                                                </thead>
                                                                                                <!--end::Table head-->
                                                                                                <!--begin::Table body-->
                                                                                                <tbody>
                                                                                                    <?php $__currentLoopData = $crops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $crop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                                    <tr>
                                                                                                        <td><?php echo e($crop->crop); ?></td>
                                                                                                        <td><?php echo e($crop->variety); ?></td>
                                                                                                        <td><?php echo e($crop->age); ?></td>
                                                                                                        <td><?php echo e($crop->population); ?></td>
                                                                                                    </tr>
                                                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                                </tbody>
                                                                                            </table>
                                                                                        </div>
                                                                                        
                                                                                        
                                                                                        
                                                                                    </div>
                                                                                    <div class="col-lg-3 col-xxl-3">
                                                                                        <!--begin::Budget-->
                                                                                        <h3 class="card-title align-items-start flex-column">
                                                                                            <span class="card-label fw-bold text-gray-800">Permanent Workers</span>
                                                                                        </h3>
                                                                                        
                                                                                        <div class="fs-6 d-flex justify-content-between mb-4">
                                                                                            <div class="fw-semibold">Male: </div>
                                                                                            <div class="d-flex fw-bold">
                                                                                                <?php echo e($farmer->permanent_male_workers); ?></div>
                                                                                            </div>
                                                                                            <div class="separator separator-dashed"></div>
                                                                                            <div class="fs-6 d-flex justify-content-between my-4">
                                                                                                <div class="fw-semibold">Female :</div>
                                                                                                <div class="d-flex fw-bold">
                                                                                                    <?php echo e($farmer->permanent_female_workers); ?></div>
                                                                                                </div>
                                                                                                
                                                                                            </div>
                                                                                            
                                                                                            
                                                                                            <div class="col-lg-3 col-xxl-3">
                                                                                                <!--begin::Budget-->
                                                                                                <h3 class="card-title align-items-start flex-column">
                                                                                                    <span class="card-label fw-bold text-gray-800">Temporary Workers</span>
                                                                                                </h3>
                                                                                                
                                                                                                <div class="fs-6 d-flex justify-content-between mb-4">
                                                                                                    <div class="fw-semibold">Male</div>
                                                                                                    <div class="d-flex fw-bold">
                                                                                                        <?php echo e($farmer->temporary_male_workers); ?></div>
                                                                                                    </div>
                                                                                                    <div class="separator separator-dashed"></div>
                                                                                                    <div class="fs-6 d-flex justify-content-between my-4">
                                                                                                        <div class="fw-semibold">Female: </div>
                                                                                                        <div class="d-flex fw-bold">
                                                                                                            <?php echo e($farmer->temporary_female_workers); ?></div>
                                                                                                        </div>
                                                                                                        
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="separator separator-solid"></div>

                                                                                              
                                                                         
                                                                      

                                                                                 
                                                                                
                                                                                
                                                                                
                                                                                <div class="row g-12 g-xl-9 py-9">
                                                                                    <div class="col-sm-8 col-xxl-">
                                                                                        <!--begin::Header-->
                                                                                        
                                                                                        <h3 class="card-title align-items-start flex-column ps-5">
                                                                                            <span class="card-label fw-bold text-gray-800">DECLARATION</span>
                                                                                        </h3>

                                                                                        
                                                                                        
                                                                                        
                                                                                        <p>I have understood and am committed to comply with the 2020 RA Sustainable Agriculture Standard, the rules and regulations of the group and my duties to collaborate in all implementation activities of this program. I am aware of sanctioning rules and my right to appeal or resign from the program and willing to comply with the RA standard, agree to share farm data with group and RA, accept internal inspections, external audits and sanctions,submit only the product from its farm as certified.</p>
                                                                                    
                                                                                        
                                                                                        
                                                                                        
                                                                                    </div>
                                                                                    <div class="col-lg-4 col-xxl-3">
                                                                                        <!--begin::Budget-->
                                                                                        <h3 class="card-title align-items-start flex-column">
                                                                                            <span class="card-label fw-bold text-gray-800">Farmer Signature</span>
                                                                                        </h3>

                                                                                        <div class="fs-6 d-flex justify-content-center mb-4">
                                                                                         
                                                                                            
                                                                                                <img src="<?php echo e($farmer->signature); ?>">
                                                                                           
                                                                                            
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <h4 class="fs-1x">FARM INTERNAL CHECKLIST
                                                                                    
                                                                                </h4>

                                                                            </div>
                                                                        </div>
                                                                        
                                                                        <?php $__env->stopSection(); ?>
                                                                        
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u931094414/domains/royalblue-stinkbug-731974.hostingersite.com/public_html/resources/views/farmers/show.blade.php ENDPATH**/ ?>