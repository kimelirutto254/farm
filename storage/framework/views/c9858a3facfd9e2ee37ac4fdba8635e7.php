<?php
$logo = \App\Models\Utility::get_file('avatars/');
?>
<link href='https://api.mapbox.com/mapbox-gl-js/v2.10.0/mapbox-gl.css' rel='stylesheet' />

<?php $__env->startSection('content'); ?>

<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_toolbar" class="app-toolbar pt-5">
            <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
                <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                    <div class="page-title d-flex flex-column gap-1 me-3 mb-2">
                        <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bolder fs-2 lh-1">Farmer Inspection Report</h1>
                    </div>
                    <div class="d-flex align-items-center">
                        <a href="<?php echo e(route('no-farm-polygon', \Illuminate\Support\Facades\Crypt::encrypt($farmer->id))); ?>" class="btn btn-sm btn-danger ms-3 px-4 py-3">No Farm Polygon</a>
                        <a href="<?php echo e(route('no-center-coordinate', \Illuminate\Support\Facades\Crypt::encrypt($farmer->id))); ?>" class="btn btn-sm btn-warning ms-3 px-4 py-3">No Center Coordinate</a>
                        <a href="<?php echo e(route('continuous-improvement', \Illuminate\Support\Facades\Crypt::encrypt($farmer->id))); ?>" class="btn btn-sm btn-primary ms-3 px-4 py-3">Continuous Improvement</a>
                        <a href="<?php echo e(route('approve', \Illuminate\Support\Facades\Crypt::encrypt($farmer->id))); ?>" class="btn btn-sm btn-success ms-3 px-4 py-3">Approve</a>
                        <a href="<?php echo e(route('reject', \Illuminate\Support\Facades\Crypt::encrypt($farmer->id))); ?>" class="btn btn-sm btn-danger ms-3 px-4 py-3">Reject</a>
                        <a href="<?php echo e(route('edit-checklist', \Illuminate\Support\Facades\Crypt::encrypt($farmer->id))); ?>" class="btn btn-sm btn-secondary ms-3 px-4 py-3">Edit Checklist</a>
                        <a href="<?php echo e(route('download-report', \Illuminate\Support\Facades\Crypt::encrypt($farmer->id))); ?>" class="btn btn-sm btn-primary ms-3 px-4 py-3">Download Report</a>
                    </div>
                </div>
            </div>
        </div>
        
        
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-fluid">
                <div class="card mb-5 mb-xl-10">
                    <div class="card-body pt-3 pb-0">
                        
                            <div id="kt_app_content" class="app-content flex-column-fluid">
                                <!--begin::Content container-->
                                <div id="kt_app_content_container" class="app-container container-fluid">
                                    <!--begin::Card-->
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
                                                                                <div class="fw-semibold">Conservation Area (Acr) :</div>
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
                                                                                            <?php echo e($farmer->inspector_name); ?></div>
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
                                                                                                    <div class="col-sm-10 col-xxl8">
                                                                                                        <!--begin::Header-->
                                                                                                        <h3 class="card-title align-items-start flex-column ps-5">
                                                                                                            <span class="card-label fw-bold text-gray-800">Sketch Map</span>
                                                                                                        </h3>
                                                                                                        <div id="map" style="width: 100%; height: 550px;"></div>
                                                                                                     
                                                                                                        
                                                                                                        
                                                                                                       
                                                                                                    </div>
                                                                                                    <div class="col-sm-2 col-xxl2">
                                                                                                        <!--begin::Header-->
                                                                                                        <h3 class="card-title align-items-start flex-column ps-5">
                                                                                                            <span class="card-label fw-bold text-gray-800">Legend</span>
                                                                                                        </h3>
  
                                                                                                        <div>
                                                                                                            <div class="label" style="color: #FF0000;">Farm Area</div>
                                                                                                            <div class="label" style="color: #00FF00;">Crop Area</div>
                                                                                                            <div class="label" style="color: #0000FF;">Conservation Area</div>
                                                                                                            <div class="label" style="color: #FFFF00;">Other Crops Area</div>
                                                                                                            <div class="label" style="color: #FFA500;">Residential Area</div>
                                                                                                            <div class="label" style="color: #FF0000;">Location</div>
                                                                                                            <div class="label" style="color: #0000FF;">Map Center</div>
                                                                                                        </div>
                                                                                                                                                                                                             
                                                                                                        
                                                                                                        
                                                                                                       
                                                                                                    </div>
                                                                                                </div>
                                                                                                
                                                                                                
                                                                                              
                                                                                                
                                                                                                
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
                                                                                                <h4 class="fs-1x">FARM INTERNAL CHECKLIST</h4>
                                                                                                <div id="kt_account_settings_notifications" class="collapse show">
                                                                                                    <form class="form">
                                                                                                        <div class="card-body border-top px-9 pt-3 pb-4">
                                                                                                            <div class="table-responsive">
                                                                                                                <table class="table table-row-dashed border-gray-300 align-middle gy-6">
                                                                                                                    <tbody class="fs-6 fw-semibold">
                                                                                                                        <?php $__currentLoopData = $responses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inspection): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                                                            <tr>
                                                                                                                                <td colspan="3" style="color: #008001; font-size: 1.5rem; font-weight: bold;">
                                                                                                                                    <?php echo e($inspection->chapter); ?>

                                                                                                                                </td>
                                                                                                                            </tr>
                                                                                                                            <?php if($inspection->response === 'Yes'): ?>
                                                                                                                                <tr>
                                                                                                                                    <td style="font-size: 1.2rem; font-weight: normal; padding-left: 20px;">
                                                                                                                                        <?php echo e($inspection->subchapter); ?>

                                                                                                                                        <?php echo e($inspection->requirement); ?>


                                                                                                                                    </td>
                                                                                                                                    
                                                                                                                                    <td colspan="2" style="text-align: center; font-size: 1rem;">
                                                                                                                                        <div style="display: flex; flex-direction: column; align-items: center;">
                                                                                                                                            
                                                                                                                                            <div style="font-style: italic;">
                                                                                                                                                <?php echo e($inspection->response); ?>

                                                                                                                                            </div>
                                                                                                                                        </div>
                                                                                                                                    </td>
                                                                                                                                </tr>
                                                                                                                            <?php endif; ?>
                                                                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                                                    </tbody>
                                                                                                                </table>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </form>
                                                                                                </div>
                                                                                                <h4 class="fs-1x">CONTINUOUS IMPROVEMENT PLAN
                                                                                                </h4>

                                                                                                <div class="col-xl-12">
                                                                                                    <!--begin::Table Widget 5-->
                                                                                                    <div class="card card-flush h-xl-100">
                                                                                                   
                                                                                                        <div class="card-body">
                                                                                                            <!--begin::Table-->
                                                                                                            <table class="table align-middle table-row-dashed fs-6 gy-3" id="kt_table_widget_5_table">
                                                                                                                <!--begin::Table head-->
                                                                                                                <thead>
                                                                                                                    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                                                                                                        <th class="min-w-150px">Code</th>
                                                                                                                        <th class="pe-3 min-w-100px">Non-Compliance</th>
                                                                                                                        <th class="pe-3 min-w-150px">Timeframe</th>
                                                                                                                        <th class="pe-3 min-w-100px">Follow Up</th>
                                                                                                                        <th class="pe-3 min-w-100px">Status</th>
                                                                                                                    </tr>
                                                                                                                </thead>
                                                                                                                <tbody class="fw-bold text-black">
                                                                                                                    <?php $__currentLoopData = $nonCompliances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nonCompliance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                                                    <tr>
                                                                                                                        <td><?php echo e($nonCompliance->code); ?></td>
                                                                                                                        <td class=""><?php echo e($nonCompliance->requirement); ?></td>
                                                                                                                        <td class=""><?php echo e($nonCompliance->timeline); ?></td>
                                                                                                                        <td class=""><?php echo e($nonCompliance->followup_date); ?></td>
                                                                                                                        <td class="">
                                                                                                                            <span class="badge py-3 px-4 fs-7 badge-light-primary"><?php echo e($nonCompliance->status); ?></span>
                                                                                                                        </td>
                                                                                                                    </tr>
                                                                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                                                </tbody>
                                                                                                            </table>
                                                                                                            
                                                                                                        </div>
                                                                                                    </div>


                                                                                        
                                                                                        <?php $__env->stopSection(); ?>
                                                                                        <script src='https://api.mapbox.com/mapbox-gl-js/v2.10.0/mapbox-gl.js'></script>
                                                                                        <script>
                                                                                            document.addEventListener('DOMContentLoaded', function() {
                                                                                                // Use the token and style passed from the backend
                                                                                                mapboxgl.accessToken = '<?php echo e($mapboxToken); ?>';
                                                                                                
                                                                                                var map = new mapboxgl.Map({
                                                                                                    container: 'map',
                                                                                                    style: '<?php echo e($mapboxStyle); ?>',
                                                                                                    center: [<?php echo e($longitude); ?>, <?php echo e($latitude); ?>], // [longitude, latitude]
                                                                                                    zoom: 14
                                                                                                });
                                                                                        
                                                                                                // Wait for the map style to be fully loaded
                                                                                                map.on('load', function() {
                                                                                                    // Function to parse polygon data and create coordinates
                                                                                                    function parsePolygon(polygonString) {
                                                                                                        if (!polygonString) {
                                                                                                            console.error('Polygon string is undefined or empty');
                                                                                                            return [];
                                                                                                        }
                                                                                        
                                                                                                        try {
                                                                                                            return polygonString.split('}, {').map(function(point) {
                                                                                                                let coords = point.replace(/{|}/g, '').split(', ');
                                                                                                                return [parseFloat(coords[1].split(': ')[1]), parseFloat(coords[0].split(': ')[1])];
                                                                                                            });
                                                                                                        } catch (error) {
                                                                                                            console.error('Error parsing polygon string:', error);
                                                                                                            return [];
                                                                                                        }
                                                                                                    }
                                                                                        
                                                                                                    // Draw polygons on the map
                                                                                                    var polygons = [
                                                                                                        {
                                                                                                            coordinates: parsePolygon('<?php echo e($farmAreaPolygons); ?>'),
                                                                                                            color: '#FF0000',
                                                                                                            label: 'Farm Area'
                                                                                                        },
                                                                                                        {
                                                                                                            coordinates: parsePolygon('<?php echo e($cropAreaPolygons); ?>'),
                                                                                                            color: '#00FF00',
                                                                                                            label: 'Crop Area'
                                                                                                        },
                                                                                                        {
                                                                                                            coordinates: parsePolygon('<?php echo e($conservationAreaPolygons); ?>'),
                                                                                                            color: '#0000FF',
                                                                                                            label: 'Conservation Area'
                                                                                                        },
                                                                                                        {
                                                                                                            coordinates: parsePolygon('<?php echo e($otherCropsAreaPolygons); ?>'),
                                                                                                            color: '#FFFF00',
                                                                                                            label: 'Other Crops Area'
                                                                                                        },
                                                                                                        {
                                                                                                            coordinates: parsePolygon('<?php echo e($residentialAreaPolygons); ?>'),
                                                                                                            color: '#FFA500',
                                                                                                            label: 'Residential Area'
                                                                                                        }
                                                                                                    ];
                                                                                        
                                                                                                    polygons.forEach(function(polygon) {
                                                                                                        map.addLayer({
                                                                                                            'id': polygon.label,
                                                                                                            'type': 'fill',
                                                                                                            'source': {
                                                                                                                'type': 'geojson',
                                                                                                                'data': {
                                                                                                                    'type': 'Feature',
                                                                                                                    'geometry': {
                                                                                                                        'type': 'Polygon',
                                                                                                                        'coordinates': [polygon.coordinates]
                                                                                                                    }
                                                                                                                }
                                                                                                            },
                                                                                                            'layout': {},
                                                                                                            'paint': {
                                                                                                                'fill-color': polygon.color,
                                                                                                                'fill-opacity': 0.5
                                                                                                            }
                                                                                                        });
                                                                                        
                                                                                                        // Add a label for the polygon
                                                                                                        var center = polygon.coordinates.reduce((acc, coord) => {
                                                                                                            return [acc[0] + coord[0], acc[1] + coord[1]];
                                                                                                        }, [0, 0]).map(val => val / polygon.coordinates.length);
                                                                                        
                                                                                                        new mapboxgl.Marker({ color: polygon.color })
                                                                                                            .setLngLat(center)
                                                                                                            .setPopup(new mapboxgl.Popup().setText(polygon.label))
                                                                                                            .addTo(map);
                                                                                                    });
                                                                                        
                                                                                                    // Add a marker for the specific location
                                                                                                    var locationMarker = new mapboxgl.Marker({ color: '#FF0000' })
                                                                                                        .setLngLat([<?php echo e($longitude); ?>, <?php echo e($latitude); ?>])
                                                                                                        .setPopup(new mapboxgl.Popup().setText('Location'))
                                                                                                        .addTo(map);
                                                                                        
                                                                                                    // Add a marker for the center of the map
                                                                                                    var centerMarker = new mapboxgl.Marker({ color: '#0000FF' })
                                                                                                        .setLngLat([<?php echo e($longitude); ?>, <?php echo e($latitude); ?>])
                                                                                                        .setPopup(new mapboxgl.Popup().setText('Map Center'))
                                                                                                        .addTo(map);
                                                                                        
                                                                                                    // Optional: Fit the map view to include the marker and polygons
                                                                                                    var bounds = new mapboxgl.LngLatBounds();
                                                                                                    polygons.forEach(function(polygon) {
                                                                                                        polygon.coordinates.forEach(function(coord) {
                                                                                                            bounds.extend(coord);
                                                                                                        });
                                                                                                    });
                                                                                                    bounds.extend([<?php echo e($longitude); ?>, <?php echo e($latitude); ?>]);
                                                                                                    map.fitBounds(bounds, { padding: 20 });
                                                                                                });
                                                                                            });
                                                                                            </script>
                                                                                            
                                                                                        
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/dismas/Desktop/fe/resources/views/farmers/show_inspection.blade.php ENDPATH**/ ?>