<form method="post" action="<?php echo e(route('inspectors.update', $inspector->id)); ?>">
    <?php echo csrf_field(); ?>
    <?php echo method_field('PUT'); ?> <!-- Use PATCH if that is your preferred method for updating -->
    <div class="modal-body">
        <div class="row">
            <div class="form-group col-md-12">
                <?php echo e(Form::label('username', __('Username'), ['class' => 'form-label'])); ?>

                <?php echo e(Form::text('username', $inspector->username, ['class' => 'form-control', 'placeholder' => __('Enter Username'), 'required' => 'required'])); ?>

            </div>
            <div class="form-group col-md-12">
                <?php echo e(Form::label('name', __('Name'), ['class' => 'form-label'])); ?>

                <?php echo e(Form::text('name', $inspector->name, ['class' => 'form-control', 'placeholder' => __('Enter Name'), 'required' => 'required'])); ?>

            </div>
            <div class="form-group col-md-12">
                <?php echo e(Form::label('id_number', __('ID Number'), ['class' => 'form-label'])); ?>

                <?php echo e(Form::text('id_number', $inspector->id_number, ['class' => 'form-control', 'placeholder' => __('Enter ID Number'), 'required' => 'required'])); ?>

            </div>
            <div class="form-group col-md-12">
                <?php echo e(Form::label('email', __('Email'), ['class' => 'form-label'])); ?>

                <?php echo e(Form::email('email', $inspector->email, ['class' => 'form-control', 'placeholder' => __('Enter Email'), 'required' => 'required'])); ?>

            </div>
            <div class="form-group col-md-12">
                <?php echo e(Form::label('phone', __('Phone'), ['class' => 'form-label'])); ?>

                <?php echo e(Form::text('phone', $inspector->phone, ['class' => 'form-control', 'placeholder' => __('Enter Phone Number'), 'required' => 'required'])); ?>

            </div>

            <div class="form-group col-12 d-flex justify-content-end col-form-label">
                <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
                <input type="submit" value="<?php echo e(__('Save')); ?>" class="btn btn-primary ms-2">
            </div>
        </div>
    </div>
</form>
<?php /**PATH /home/u931094414/domains/royalblue-stinkbug-731974.hostingersite.com/public_html/resources/views/inspectors/edit.blade.php ENDPATH**/ ?>