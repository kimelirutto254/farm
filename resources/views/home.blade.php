@extends('layouts.admin')

@php
$logo = \App\Models\Utility::get_file('avatars/');
@endphp

@section('content')
<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-fluid">
        <div class="row g-6 g-xl-9">
            <div class="col-lg-6 col-xxl-3 d-flex align-items-stretch">
                <div class="card w-100 border-0 shadow-sm d-flex flex-column">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center p-6">
                        <div class="fs-3 fw-bold text-primary">Compliant Farmers</div>
                        <div class="fs-2 fw-semibold text-gray-600 mt-3">{{$compliantfarmers}}</div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-xxl-3 d-flex align-items-stretch">
                <div class="card w-100 border-0 shadow-sm d-flex flex-column">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center p-6">
                        <div class="fs-3 fw-bold text-danger">Non-Compliant Farmers</div>
                        <div class="fs-2 fw-semibold text-gray-600 mt-3">{{$noncompliantfarmers}}</div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-xxl-3 d-flex align-items-stretch">
                <div class="card w-100 border-0 shadow-sm d-flex flex-column">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center p-6">
                        <div class="fs-3 fw-bold text-success">Inspected</div>
                        <div class="fs-2 fw-semibold text-gray-600 mt-3">{{$inspectedfarmers}}</div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-xxl-3 d-flex align-items-stretch">
                <div class="card w-100 border-0 shadow-sm d-flex flex-column">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center p-6">
                        <div class="fs-3 fw-bold text-warning">Not Inspected</div>
                        <div class="fs-2 fw-semibold text-gray-600 mt-3">{{$notinspectedfarmers}}</div>
                    </div>
                </div>
            </div>
        </div>
  


        <div class="row g-12 g-xl-9">
            <div class="col-xl-12">
                <div class="col-xxl-12 mb-5 mb-xl-10">
                    <!--begin::Chart widget 8-->
                    <div class="card card-flush h-xl-100">
                        <!--begin::Header-->
                        <div class="card-header pt-5">
                            <!--begin::Title-->
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold text-dark">Daily Inspection Statistics</span>
                                <span class="text-gray-400 mt-1 fw-semibold fs-6">Inspections Done vs Day
                                </span>
                            </h3>
                            <!--end::Title-->
                            <!--begin::Toolbar-->
                           
                            <!--end::Toolbar-->
                        </div>
                        <!--end::Header-->
                        <!--begin::Body-->
                        <div class="card-body pt-6">
                            <!--begin::Tab content-->
                          
                            <div id="chartdiv" class="ms-n5 min-h-auto" style="height: 275px"></div>

                        </div>
                        <!--end::Body-->
                    </div>
                    <!--end::Chart widget 8-->
                </div>
            </div>
        </div>



        <div class="row g-6 g-xl-9">
            <div class="col-xl-12">
                <div class="card-header card-body table-border-style">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-dark">Recent Inspections
                        </span>
                    </h3>
                    <div class="table-responsive">
                        <table class="table table-rounded table-striped border gy-7 gs-10" id="example">
                            <thead>
                                <tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
                                    <th class="min-w-125px">Grower ID</th>
                                    <th class="min-w-125px">Farmer Name</th>
                                    <th class="min-w-125px">ID Number</th>
                                    <th class="min-w-125px">Location</th>
                                    <th class="min-w-125px">Inspection Date</th>
                                    <th class="min-w-125px">Inspector</th>
                                    @if (Gate::check('Manage Inspections') || Gate::check('Manage Inspections'))
                                    <th class="text-end min-w-70px">Actions</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="">
                                @foreach ($farmers as $farmer)
                                <tr>
                                    <td>{{ $farmer->grower_id }}</td>
                                    
                                    <td>{{ $farmer->first_name }} {{ $farmer->middle_name }} {{ $farmer->last_name }}</td>
                                    <td>{{ $farmer->id_number }}</td>
                                    <td>{{ $farmer->location }}</td>
                                    <td>{{ \Auth::user()->dateFormat($farmer->inspection_date) }}</td>
                                    <td>{{ $farmer->inspector }}</td>
                                    
                                 
                                    <td class="text-end">
                                        <a href="#" class="btn btn-sm btn-primary btn-flex btn-center btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                            <i class="ki-duotone ki-down fs-5 ms-1"></i></a>
                                            <!--begin::Menu-->
                                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                                                <!--begin::Menu item-->
                                                @can('Manage Inspections')
                                                <div class="menu-item px-3">
                                                    <a href="{{ route('farmer.show', \Illuminate\Support\Facades\Crypt::encrypt($farmer->id)) }}" class="menu-link px-3">View Report</a>
                                                </div>
                                                
                                                @endcan
                                                @can('Manage Inspections')
                                                <div class="menu-item px-3">
                                                    <a href="{{ route('inspections.show', \Illuminate\Support\Facades\Crypt::encrypt($farmer->id)) }}" class="menu-link px-3">View Checklist</a>
                                                </div>
                                                
                                                @endcan
                                                
                                              
                                            </div>
                                        </td>
                                       
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>



           
        </div>
        @endsection
        <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
        <script>
            am5.ready(function() {
              // Create root element
              var root = am5.Root.new("chartdiv");
            
              // Set themes
              root.setThemes([
                am5themes_Animated.new(root)
              ]);
            
              // Create chart
              var chart = root.container.children.push(am5xy.XYChart.new(root, {
                panX: false,
                panY: false,
                paddingLeft: 0,
                wheelX: "panX",
                wheelY: "zoomX",
                layout: root.verticalLayout
              }));
            
              // Add legend
              var legend = chart.children.push(
                am5.Legend.new(root, {
                  centerX: am5.p50,
                  x: am5.p50
                })
              );
            
              // Data for today
              var data = [{
                "status": "Inspected",
                "count": @json($todayInspectedFarmers)
              }, {
                "status": "Approved",
                "count": @json($countTodayApproved)
              }, {
                "status": "Rejected",
                "count": @json($todayRejectedFarmers)
              }];
            
              // Create axes
              var xRenderer = am5xy.AxisRendererX.new(root, {
                cellStartLocation: 0.1,
                cellEndLocation: 0.9,
                minorGridEnabled: true
              });
            
              var xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
                categoryField: "status",
                renderer: xRenderer,
                tooltip: am5.Tooltip.new(root, {})
              }));
            
              xRenderer.grid.template.setAll({
                location: 1
              });
            
              xAxis.data.setAll(data);
            
              var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
                renderer: am5xy.AxisRendererY.new(root, {
                  strokeOpacity: 0.1
                })
              }));
            
              // Add series
              function makeSeries(name, fieldName) {
                var series = chart.series.push(am5xy.ColumnSeries.new(root, {
                  name: name,
                  xAxis: xAxis,
                  yAxis: yAxis,
                  valueYField: fieldName,
                  categoryXField: "status"
                }));
            
                series.columns.template.setAll({
                  tooltipText: "{name}, {categoryX}:{valueY}",
                  width: am5.percent(90),
                  tooltipY: 0,
                  strokeOpacity: 0
                });
            
                series.data.setAll(data);
            
                // Make stuff animate on load
                series.appear();
            
                series.bullets.push(function () {
                  return am5.Bullet.new(root, {
                    locationY: 0,
                    sprite: am5.Label.new(root, {
                      text: "{valueY}",
                      fill: root.interfaceColors.get("alternativeText"),
                      centerY: 0,
                      centerX: am5.p50,
                      populateText: true
                    })
                  });
                });
            
                legend.data.push(series);
              }
            
              makeSeries("Farmers Count", "count");
            
              // Make stuff animate on load
              chart.appear(1000, 100);
            });
            </script>