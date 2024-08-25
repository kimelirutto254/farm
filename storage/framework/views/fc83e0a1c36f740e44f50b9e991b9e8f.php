<form method="post" action="<?php echo e(route('inspectors.store')); ?>">
    <?php echo csrf_field(); ?>
    <div class="modal-body">
        <div class="row">
            <div class="form-group col-md-12">
                <?php echo e(Form::label('name', __('Name'), ['class' => 'form-label'])); ?>

                <?php echo e(Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('Enter Name'), 'required' => 'required'])); ?>

            </div>
            <div class="form-group col-md-12">
                <?php echo e(Form::label('email', __('Email'), ['class' => 'form-label'])); ?>

                <?php echo e(Form::email('email', null, ['class' => 'form-control', 'placeholder' => __('Enter Email'), 'required' => 'required'])); ?>

            </div>
            <div class="form-group col-md-12">
                <?php echo e(Form::label('phone', __('Phone'), ['class' => 'form-label'])); ?>

                <?php echo e(Form::text('phone', null, ['class' => 'form-control', 'placeholder' => __('Enter Phone'), 'required' => 'required'])); ?>

            </div>
            <div class="form-group col-md-12">
                <?php echo e(Form::label('pin', __('PIN'), ['class' => 'form-label'])); ?>

                <?php echo e(Form::number('pin', null, ['class' => 'form-control', 'placeholder' => __('Enter PIN'), 'min' => '0', 'max' => '9999', 'maxlength' => '4', 'required' => 'required'])); ?>

            </div>
            <div class="form-group col-12 d-flex justify-content-end col-form-label">
                <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
                <input type="submit" value="<?php echo e(__('Save')); ?>" class="btn btn-primary ms-2">
            </div>
        </div>
    </div>
</form>


<script>
    $(document).on('change', '#password_switch', function () {
        if ($(this).is(':checked')) {
            $('.ps_div').removeClass('d-none');
            $('#password').attr("required", true);
        } else {
            $('.ps_div').addClass('d-none');
            $('#password').val(null);
            $('#password').removeAttr("required");
        }
    });
    $(document).on('click', '.login_enable', function () {
        setTimeout(function () {
            $('.modal-body').append($('<input>', {
                type: 'hidden',
                val: 'true',
                name: 'login_enable'
            }));
        }, 2000);
    });
</script>
<?php /**PATH /Users/dismas/Desktop/fe/resources/views/inspectors/create.blade.php ENDPATH**/ ?>