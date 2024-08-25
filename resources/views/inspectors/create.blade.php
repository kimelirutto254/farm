<form method="post" action="{{ route('inspectors.store') }}">
    @csrf
    <div class="modal-body">
        <div class="row">
              <div class="form-group col-md-12">
                {{ Form::label('username', __('Username'), ['class' => 'form-label']) }}
                {{ Form::text('username', null, ['class' => 'form-control', 'placeholder' => __('Enter Username'), 'required' => 'required']) }}
            </div>
            <div class="form-group col-md-12">
                {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}
                {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('Enter Name'), 'required' => 'required']) }}
            </div>
             <div class="form-group col-md-12">
                {{ Form::label('id_number', __('ID Number'), ['class' => 'form-label']) }}
                {{ Form::text('id_number', null, ['class' => 'form-control', 'placeholder' => __('Enter ID Number'), 'required' => 'required']) }}
            </div>
            <div class="form-group col-md-12">
                {{ Form::label('email', __('Email'), ['class' => 'form-label']) }}
                {{ Form::email('email', null, ['class' => 'form-control', 'placeholder' => __('Enter Email'), 'required' => 'required']) }}
            </div>
            <div class="form-group col-md-12">
                {{ Form::label('phone', __('Phone'), ['class' => 'form-label']) }}
                {{ Form::text('phone', null, ['class' => 'form-control', 'placeholder' => __('Enter Phone Number'), 'required' => 'required']) }}
            </div>
          
            <div class="form-group col-12 d-flex justify-content-end col-form-label">
                <input type="button" value="{{ __('Cancel') }}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
                <input type="submit" value="{{ __('Save') }}" class="btn btn-primary ms-2">
            </div>
        </div>
    </div>
</form>