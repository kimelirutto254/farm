{{ Form::model($inspector, ['route' => ['inspectors.resetpassword', $inspector->id], 'method' => 'post']) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-12">
            {{ Form::label('pin', __('PIN'), ['class' => 'form-label']) }}
            <div class="form-icon-user">
                <input id="pin" type="number" class="form-control @error('pin') is-invalid @enderror"
                    name="pin" required min="0" max="9999" maxlength="4" placeholder="Enter PIN">
            </div>
            @error('pin')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="form-group col-md-12">
            {{ Form::label('pin_confirmation', __('Confirm PIN'), ['class' => 'form-label']) }}
            <div class="form-icon-user">
                <input id="pin-confirmation" type="number" class="form-control" name="pin_confirmation" required
                    min="0" max="9999" maxlength="4" placeholder="Confirm PIN">
            </div>
            @error('pin_confirmation')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update') }}" class="btn btn-primary">
</div>
{{ Form::close() }}
