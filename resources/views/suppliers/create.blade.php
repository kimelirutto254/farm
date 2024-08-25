<form action="{{ route('suppliers.store') }}" method="POST">
@csrf
<div class="modal-body">
    <div class="scroll-y me-n7 pe-7" id="kt_modal_add_customer_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_customer_header" data-kt-scroll-wrappers="#kt_modal_add_customer_scroll" data-kt-scroll-offset="300px">
    
        <!-- Name Field -->
        <div class="fv-row mb-7">
            <label class="required fs-6 fw-semibold mb-2">Name</label>
            <input type="text" class="form-control form-control-solid" placeholder="" name="name" />
        </div>

        <!-- Email Field -->
        <div class="fv-row mb-7">
            <label class="fs-6 fw-semibold mb-2">
                <span class="">Email</span>
               
            </label>
            <input type="email" class="form-control form-control-solid" placeholder="" name="email" />
        </div>

        <!-- Phone Field -->
        <div class="fv-row mb-7">
            <label class="required fs-6 fw-semibold mb-2">Phone</label>
            <input type="number" class="form-control form-control-solid" placeholder="" name="phone" />
        </div>

        <!-- Location Field -->
        <div class="fv-row mb-7">
            <label class="required fs-6 fw-semibold mb-2">Location</label>
            <input type="text" class="form-control form-control-solid" placeholder="" name="location" />
        </div>
        <label class="required fs-6 fw-semibold mb-2">Category</label>

        <select name="category_id"data-control="select2" required data-placeholder="Select Category" data-hide-search="true" name="page_id[]" class="form-select form-select-solid">
            @if($categories)
                @foreach($categories as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            @else
                <option value="">No categories available</option>
            @endif
        </select>
       
        
    </div>
    <div class="modal-footer">
        <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{ __('Create') }}" class="btn btn-primary">
    </div>
</div>

</form>
