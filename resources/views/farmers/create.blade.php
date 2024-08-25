
<style>
    .dropzone .avatar img {
        width: 80px; /* Set the desired width */
        height: auto; /* Maintain aspect ratio */
        max-height: 80px; /* Optionally limit the height */
        object-fit: cover; /* Ensure image fits well */
    }
</style>
<form action="{{ route('farmer.create') }}" method="POST" id="kt_modal_create_app_form">
    @csrf
    <div class="" id="kt_modal_create_app">
        
        <div class="stepper stepper-pills stepper-column d-flex flex-column flex-xl-row flex-row-fluid" id="kt_modal_create_app_stepper">
            <div class="d-flex justify-content-center justify-content-xl-start flex-row-auto w-100 w-xl-300px">
                <div class="stepper-nav ps-lg-10">
                    <div class="stepper-item current" data-kt-stepper-element="nav">
                        <div class="stepper-wrapper">
                            <div class="stepper-icon w-40px h-40px">
                                <i class="ki-duotone ki-check stepper-check fs-2"></i>
                                <span class="stepper-number">1</span>
                            </div>
                            <div class="stepper-label">
                                <h3 class="stepper-title">Basic Information</h3>
                                <div class="stepper-desc"></div>
                            </div>
                        </div>
                        <div class="stepper-line h-40px"></div>
                    </div>
                    
                    <div class="stepper-item" data-kt-stepper-element="nav">
                        <div class="stepper-wrapper">
                            <div class="stepper-icon w-40px h-40px">
                                <i class="ki-duotone ki-check stepper-check fs-2"></i>
                                <span class="stepper-number">2</span>
                            </div>
                            
                            <div class="stepper-label">
                                <h3 class="stepper-title">More Details</h3>
                                <div class="stepper-desc"></div>
                            </div>
                        </div>
                        <div class="stepper-line h-40px"></div>
                    </div>
                   
                    <div class="stepper-item" data-kt-stepper-element="nav">
                        <div class="stepper-wrapper">
                            <div class="stepper-icon w-40px h-40px">
                                <i class="ki-duotone ki-check stepper-check fs-2"></i>
                                <span class="stepper-number">3</span>
                            </div>
                            <div class="stepper-label">
                                <h3 class="stepper-title">Crop Details</h3>
                                <div class="stepper-desc"></div>
                            </div>
                        </div>
                        
                        <div class="stepper-line h-40px"></div>
                    </div>
                    
                    <div class="stepper-item mark-completed" data-kt-stepper-element="nav">
                        <div class="stepper-wrapper">
                            <div class="stepper-icon w-40px h-40px">
                                <i class="ki-duotone ki-check stepper-check fs-2"></i>
                                <span class="stepper-number">4</span>
                            </div>
                            
                            <div class="stepper-label">
                                <h3 class="stepper-title">Complete</h3>
                                <div class="stepper-desc">Review and Submit</div>
                            </div>
                            <!--end::Label-->
                        </div>
                        <!--end::Wrapper-->
                    </div>
                    <!--end::Step 5-->
                </div>
                <!--end::Nav-->
            </div>
            
            <div class="flex-row-fluid py-lg-5 px-lg-15">
                <!--begin::Form-->
                <div class="current" data-kt-stepper-element="content">
                    <div class="w-100">
                        
                        <!--begin::Input group-->
                        <div class="fv-row mb-10">
                            <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                                <span class="required">Grower ID </span>
                            </label>
                            <input type="number" class="form-control form-control-lg form-control-solid" name="grower_id" placeholder="Enter Grower ID" required value="" />
                        </div>
                        <!--end::Input group-->
                          <!--begin::Input group-->
                          <div class="fv-row mb-10">
                            <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                                <span class="required">Name</span>
                            </label>
                            <input type="text" class="form-control form-control-lg form-control-solid" name="name" placeholder="Enter Farmer Name" required value="" />
                        </div>
                        <!--end::Input group-->
                        <div class="fv-row mb-10">
                            <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                                <span class="required">Gender </span>
                            </label>
                            <input type="text" class="form-control form-control-lg form-control-solid" name="gender" placeholder="Enter Gender" required value="" />
                        </div>
                    

                         <!--begin::Input group-->
                         <div class="fv-row mb-10">
                            <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                                <span class="required">Region </span>
                            </label>
                            <input type="text" class="form-control form-control-lg form-control-solid" name="region" placeholder="Enter Region" required value="" />
                        </div>
                        <!--end::Input group-->
                     
                    </div>
                </div>
                <!--end::Step 1-->
                
                <!--begin::Step 4 (with tabs) initially hidden-->
                <div id="step-with-tabs" data-kt-stepper-element="content" >
                    <div class="w-100">
                        <!--begin::Input group-->
                        <div class="fv-row mb-10">
                            <div class="fv-row mb-10">
                                <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                                    <span class="required">Town </span>
                                </label>
                                <input type="text" class="form-control form-control-lg form-control-solid" name="town" placeholder="Town" required value="" />
                            </div>
                            <!--end::Input group-->
    
                             <!--begin::Input group-->
                             <div class="fv-row mb-10">
                                <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                                    <span class="required">Route </span>
                                </label>
                                <input type="text" class="form-control form-control-lg form-control-solid" name="route" placeholder="Enter Route" required value="" />
                            </div>
                          
    
                             <!--begin::Input group-->
                             <div class="fv-row mb-10">
                                <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                                    <span class="required">Collection Center </span>
                                </label>
                                <input type="text" class="form-control form-control-lg form-control-solid" name="collection_center" placeholder="Enter Collection Center" required value="" />
                            </div>
                         
                             <!--begin::Input group-->
                             <div class="fv-row mb-10">
                                <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                                    <span class="required">Nearest Center </span>
                                </label>
                                <input type="text" class="form-control form-control-lg form-control-solid" name="nearest_center" placeholder="Enter Nearest Center" required value="" />
                            </div>
                           
                        </div>
                        <!--end::Input group-->
                       
                    </div>
                </div>
                <!--end::Step 4-->
                
            <!--begin::Step 4-->
<div data-kt-stepper-element="content">
    <div class="w-100">
        <!--begin::Input group-->
        <div id="crop-details-container">
            <div class="crop-row mb-3">
                <div class="mb-3">
                    <label for="crop">Crop</label>
                    <input type="text" class="form-control" name="crops[]" placeholder="Enter Crop">
                </div>
                <div class="mb-3">
                    <label for="population">Crop Population</label>
                    <input type="number" class="form-control" name="populations[]" placeholder="Enter Population">
                </div>
                <div class="mb-3">
                    <label for="age">Crop Age</label>
                    <input type="text" class="form-control" name="ages[]" placeholder="Enter Age">
                </div>
                <div class="mb-3">
                    <label for="variety">Crop Variety</label>
                    <input type="text" class="form-control" name="varieties[]" placeholder="Enter Variety">
                </div>
                <div class="mb-3">
                    <button type="button" class="btn btn-sm btn-danger remove-crop-row">Remove</button>
                </div>
            </div>
        </div>
        <!--end::Input group-->

        <!--begin::Buttons-->
        <div class="mt-3">
            <button type="button" class="btn btn-sm btn-primary" id="add-crop-row">Add Row</button>
        </div>
        <!--end::Buttons-->
    </div>
</div>
<!--end::Step 4-->

                
                <!--begin::Step 5-->
                <div data-kt-stepper-element="content">
                    <div class="w-100">
                        <div class="pb-4 border-bottom">
                            <label class="form-check form-switch form-switch-sm form-check-custom form-check-solid flex-stack">
                                <span class="form-check-label text-gray-700 fs-6 fw-semibold ms-0 me-2">Automatically Publish Post</span>
                                <input class="form-check-input" type="checkbox"  value="1" checked="checked" />
                            </label>
                        </div>
                        
                        <div class="pb-4 border-bottom">
                            <label class="form-check form-switch form-switch-sm form-check-custom form-check-solid flex-stack">
                                <span class="form-check-label text-gray-700 fs-6 fw-semibold ms-0 me-2">Save it as draft</span>
                                <input class="form-check-input" type="checkbox" value="1" checked="checked" />
                            </label>
                        </div>
                        
                        <!--begin::Review & Submit-->
                        <div class="fv-row mb-10">
                            <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                                <span class="required">Review Your Information</span>
                            </label>
                            <div>
                                <!-- Display the review of the entered information here -->
                            </div>
                        </div>
                        
                        <!--end::Review & Submit-->
                    </div>
                </div>
                
                <!--end::Step 5-->
                <!--begin::Actions-->
                <div class="d-flex flex-stack pt-10">
                    <!--begin::Wrapper-->
                    <div class="me-2">
                        <button type="button" class="btn btn-lg btn-light-primary me-3" data-kt-stepper-action="previous">
                            <i class="ki-duotone ki-arrow-left fs-3 me-1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>Back</button>
                        </div>
                        <!--end::Wrapper-->
                        <!--begin::Wrapper-->
                        <div class="mt-5">
                            <button type="button" class="btn btn-lg btn-primary" type="submit" data-kt-stepper-action="submit">
                                <span class="indicator-label">Submit
                                    <i class="ki-duotone ki-arrow-right fs-3 ms-2 me-0">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </span>
                                <span class="indicator-progress">Please wait...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>
                            <button type="button" class="btn btn-lg btn-primary" data-kt-stepper-action="next">Continue
                                <i class="ki-duotone ki-arrow-right fs-3 ms-1 me-0">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i> 
                            </button>
                        </div>
                        <!--end::Wrapper-->
                    </div>
                    <!--end::Actions-->
                </form>
                <!--end::Form-->
            </div>
            <!--end::Content-->
        </div>
        <!--end::Step 5-->
    </div>
</div>
</div>

<script>
    "use strict";
    
    // Class definition
    var KTCreateApp = function () {
        // Elements
        var modal;	
        var modalEl;
        
        var stepper;
        var form;
        var formSubmitButton;
        var formContinueButton;
        
        // Variables
        var stepperObj;
        var validations = [];
        
        // Private Functions
        var initStepper = function () {
            // Initialize Stepper
            stepperObj = new KTStepper(stepper);
            
            // Stepper change event(handle hiding submit button for the last step)
            stepperObj.on('kt.stepper.changed', function (stepper) {
                if (stepperObj.getCurrentStepIndex() === 5) {
                    formSubmitButton.classList.remove('d-none');
                    formSubmitButton.classList.add('d-inline-block');
                    formContinueButton.classList.add('d-none');
                } else if (stepperObj.getCurrentStepIndex() === 6) {
                    formSubmitButton.classList.add('d-none');
                    formContinueButton.classList.add('d-none');
                } else {
                    formSubmitButton.classList.remove('d-inline-block');
                    formSubmitButton.classList.remove('d-none');
                    formContinueButton.classList.remove('d-none');
                }
            });
            
            // Validation before going to next page
            stepperObj.on('kt.stepper.next', function (stepper) {
                console.log('stepper.next');
                
                // Validate form before change stepper step
                var validator = validations[stepper.getCurrentStepIndex() - 1]; // get validator for currnt step
                
                if (validator) {
                    validator.validate().then(function (status) {
                        console.log('validated!');
                        
                        if (status == 'Valid') {
                            stepper.goNext();
                            
                            //KTUtil.scrollTop();
                        } else {
                            // Show error message popup. For more info check the plugin's official documentation: https://sweetalert2.github.io/
                            Swal.fire({
                                text: "Sorry, looks like there are some errors detected, please try again.",
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn btn-light"
                                }
                            }).then(function () {
                                //KTUtil.scrollTop();
                            });
                        }
                    });
                } else {
                    stepper.goNext();
                    
                    KTUtil.scrollTop();
                }
            });
            
            // Prev event
            stepperObj.on('kt.stepper.previous', function (stepper) {
                console.log('stepper.previous');
                
                stepper.goPrevious();
                KTUtil.scrollTop();
            });
            
            formSubmitButton.addEventListener('click', function (e) {
                // Prevent default button action
                e.preventDefault();
                
                // Validate form before change stepper step
                var validator = validations[3]; // get validator for last form
                
                validator.validate().then(function (status) {
                    console.log('validated!');
                    
                    if (status == 'Valid') {
                        // Disable button to avoid multiple click 
                        formSubmitButton.disabled = true;
                        
                        // Show loading indication
                        formSubmitButton.setAttribute('data-kt-indicator', 'on');
                        
                        // Simulate form submission
                        setTimeout(function() {
                            // Hide loading indication
                            formSubmitButton.removeAttribute('data-kt-indicator');
                            
                            // Enable button
                            formSubmitButton.disabled = false;
                            
                            // Submit the form using AJAX
                            $.ajax({
                                url: form.action,
                                method: form.method,
                                data: $(form).serialize(), // Serialize form data
                                success: function(response) {
                                    // Check if the response indicates success
                                    if (response.flag === 'success') {
                                        // If success, show success message
                                        Swal.fire({
                                            text: "Farmer was created successfully!",
                                            icon: "success",
                                            buttonsStyling: false,
                                            confirmButtonText: "Ok",
                                            customClass: {
                                                confirmButton: "btn btn-primary"
                                            }
                                        }).then(function () {
                                            // Redirect to properties route
                                            window.location.href = "{{ url('farmer') }}";
                                        });
                                    } else {
                                        // If response indicates error, show error message
                                        Swal.fire({
                                            text: response.msg || "An error occurred while adding the property.",
                                            icon: "error",
                                            buttonsStyling: false,
                                            confirmButtonText: "Ok",
                                            customClass: {
                                                confirmButton: "btn btn-light"
                                            }
                                        }).then(function () {
                                            KTUtil.scrollTop();
                                        });
                                    }
                                },
                                error: function(xhr, status, error) {
                                    // If AJAX request fails, show generic error message
                                    Swal.fire({
                                        text: "An error occurred while adding the property. Please try again later.",
                                        icon: "error",
                                        buttonsStyling: false,
                                        confirmButtonText: "Ok",
                                        customClass: {
                                            confirmButton: "btn btn-light"
                                        }
                                    }).then(function () {
                                        KTUtil.scrollTop();
                                    });
                                }
                            });
                        }, 2000);
                        
                    } else {
                        // Show SweetAlert message for validation errors
                        Swal.fire({
                            text: "Sorry, looks like there are some errors detected, please try again.",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn btn-light"
                            }
                        }).then(function () {
                            KTUtil.scrollTop();
                        });
                    }
                });
            });
        }
        
        // Init form inputs
        
        
        var initValidation = function () {
            // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
            // Step 1
            validations.push(FormValidation.formValidation(
            form,
            {
                fields: {
                    
                    name: {
                        validators: {
                            notEmpty: {
                                message: 'Farmer Name  is required'
                            }
                        }
                    },
                 
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: '.fv-row',
                        eleInvalidClass: '',
                        eleValidClass: ''
                    })
                }
            }
            ));
            
            // Step 2
            validations.push(FormValidation.formValidation(
            form,
            {
                
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    // Bootstrap Framework Integration
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: '.fv-row',
                        eleInvalidClass: '',
                        eleValidClass: ''
                    })
                }
            }
            ));
            
            // Step 3
            validations.push(FormValidation.formValidation(
            form,
            {
                fields: {
                    
                    
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    // Bootstrap Framework Integration
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: '.fv-row',
                        eleInvalidClass: '',
                        eleValidClass: ''
                    })
                }
            }
            ));
            
            // Step 4
            validations.push(FormValidation.formValidation(
            form,
            {
                
                
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    // Bootstrap Framework Integration
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: '.fv-row',
                        eleInvalidClass: '',
                        eleValidClass: ''
                    })
                }
            }
            ));
        }
        
        return {
            // Public Functions
            init: function () {
                // Elements
                modalEl = document.querySelector('#kt_modal_create_app');
                
                if (!modalEl) {
                    return;
                }
                
                modal = new bootstrap.Modal(modalEl);
                
                stepper = document.querySelector('#kt_modal_create_app_stepper');
                form = document.querySelector('#kt_modal_create_app_form');
                formSubmitButton = stepper.querySelector('[data-kt-stepper-action="submit"]');
                formContinueButton = stepper.querySelector('[data-kt-stepper-action="next"]');
                
                initStepper();
                initValidation();
            }
        };
    }();
    
    // On document ready
    KTUtil.onDOMContentLoaded(function() {
        KTCreateApp.init();
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Add new row
    document.getElementById('add-crop-row').addEventListener('click', function() {
        let container = document.getElementById('crop-details-container');
        let newRow = document.createElement('div');
        newRow.className = 'crop-row mb-3';
        newRow.innerHTML = `
            <div class="mb-3">
                <label for="crop">Crop</label>
                <input type="text" class="form-control" name="crops[]" placeholder="Enter Crop">
            </div>
            <div class="mb-3">
                <label for="population">Crop Population</label>
                <input type="number" class="form-control" name="populations[]" placeholder="Enter Population">
            </div>
            <div class="mb-3">
                <label for="age">Crop Age</label>
                <input type="text" class="form-control" name="ages[]" placeholder="Enter Age">
            </div>
            <div class="mb-3">
                <label for="variety">Crop Variety</label>
                <input type="text" class="form-control" name="varieties[]" placeholder="Enter Variety">
            </div>
            <div class="mb-3">
                <button type="button" class="btn btn-sm btn-danger remove-crop-row">Remove</button>
            </div>
        `;
        container.appendChild(newRow);
    });

    // Remove row
    document.getElementById('crop-details-container').addEventListener('click', function(event) {
        if (event.target.classList.contains('remove-crop-row')) {
            event.target.closest('.crop-row').remove();
        }
    });
});

</script>
