<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Edit Farmer')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>"><?php echo e(__('Home')); ?></a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(url('employee')); ?>"><?php echo e(__('Farmer')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('Edit Farmer')); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <style>
        .cursor-pointer {
            cursor: pointer;
        }
    </style>

    <div class="">
        <div class="">
            <div class="row">

            </div>
            <?php echo e(Form::model($farmer, ['route' => ['farmer.update', $farmer->id], 'method' => 'put', 'enctype' => 'multipart/form-data'])); ?>

            <div class="row">
                <div class="col-md-6">
                    <div class="card em-card">
                        <div class="card-header">
                            <h5><?php echo e(__('Personal Detail')); ?></h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <?php echo Form::label('grower_id', __('Grower ID'), ['class' => 'form-label']); ?><span class="text-danger pl-1">*</span>
                                    <?php echo Form::text('grower_id', null, [
                                        'class' => 'form-control',
                                        'required' => 'required',
                                        'placeholder' => 'Enter Grower ID',
                                    ]); ?>

                                </div>

                                <div class="form-group col-md-6">
                                    <?php echo Form::label('id_number', __('Id Number'), ['class' => 'form-label']); ?><span class="text-danger pl-1">*</span>
                                    <?php echo Form::text('id_number', null, [
                                        'class' => 'form-control',
                                        'required' => 'required',
                                        'placeholder' => 'Enter id  number',
                                    ]); ?>

                                </div>

                                <div class="form-group col-md-6">
                                    <?php echo Form::label('first_name', __('First Name'), ['class' => 'form-label']); ?><span class="text-danger pl-1">*</span>
                                    <?php echo Form::text('first_name', null, [
                                        'class' => 'form-control',
                                        'required' => 'required',
                                        'placeholder' => 'Enter first name',
                                    ]); ?>

                                </div>
                                <div class="form-group col-md-6">
                                    <?php echo Form::label('middle_name', __('Middle Name'), ['class' => 'form-label']); ?><span class="text-danger pl-1">*</span>
                                    <?php echo Form::text('middle_name', null, [
                                        'class' => 'form-control',
                                        'required' => 'required',
                                        'placeholder' => 'Enter middle name',
                                    ]); ?>

                                </div>
                                <div class="form-group col-md-6">
                                    <?php echo Form::label('last_name', __('Last Name'), ['class' => 'form-label']); ?><span class="text-danger pl-1">*</span>
                                    <?php echo Form::text('last_name', null, [
                                        'class' => 'form-control',
                                        'required' => 'required',
                                        'placeholder' => 'Enter last name',
                                    ]); ?>

                                </div>

                                <div class="form-group col-md-6">
                                    <?php echo Form::label('phone_number', __('Phone Number'), ['class' => 'form-label']); ?><span class="text-danger pl-1">*</span>
                                    <?php echo Form::text('phone_number', null, [
                                        'class' => 'form-control',
                                        'required' => 'required',
                                        'placeholder' => 'Enter Phone Number',
                                    ]); ?>

                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?php echo Form::label('gender', __('Gender'), ['class' => 'form-label']); ?><span class="text-danger pl-1">*</span>
                                        <div class="d-flex radio-check">
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <?php echo Form::radio('gender', 'Male', null, ['id' => 'g_male', 'class' => 'form-check-input']); ?>

                                                <label class="form-check-label " for="g_male"><?php echo e(__('Male')); ?></label>
                                            </div>
                                            <div class="custom-control custom-radio ms-1 custom-control-inline">
                                                <?php echo Form::radio('gender', 'Female', null, ['id' => 'g_female', 'class' => 'form-check-input']); ?>

                                                <label class="form-check-label " for="g_female"><?php echo e(__('Female')); ?></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card em-card">
                        <div class="card-header">
                            <h5><?php echo e(__('Other Details')); ?></h5>
                        </div>
                        <div class="card-body employee-detail-create-body">
                            <div class="row">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PUT'); ?>
                                <div class="form-group col-md-6">
                                    <?php echo Form::label('region', __('Region'), ['class' => 'form-label']); ?>

                                    <?php echo Form::text('region', null, ['class' => 'form-control']); ?>

                                </div>
                                <div class="form-group col-md-6">
                                    <?php echo Form::label('town', __('Town'), ['class' => 'form-label']); ?>

                                    <?php echo Form::text('town', null, ['class' => 'form-control']); ?>

                                </div>

                                <div class="form-group col-md-6">
                                    <?php echo Form::label('route', __('Route'), ['class' => 'form-label']); ?><span class="text-danger pl-1">*</span>
                                    <?php echo Form::text('route', null, [
                                        'class' => 'form-control',
                                        'required' => 'required',
                                        'placeholder' => 'Enter Route',
                                    ]); ?>

                                </div>
                                <div class="form-group col-md-6">
                                    <?php echo Form::label('collection_center', __('Collection Center'), ['class' => 'form-label']); ?><span class="text-danger pl-1">*</span>
                                    <?php echo Form::text('collection_center', null, [
                                        'class' => 'form-control',
                                        'required' => 'required',
                                        'placeholder' => 'Enter collection center',
                                    ]); ?>

                                </div>
                                <div class="form-group col-md-6">
                                    <?php echo Form::label('nearest_center', __('Nearest Centre'), ['class' => 'form-label']); ?><span class="text-danger pl-1">*</span>
                                    <?php echo Form::text('nearest_center', null, [
                                        'class' => 'form-control',
                                        'required' => 'required',
                                        'placeholder' => 'Enter nearest center',
                                    ]); ?>

                                </div>
                                <div class="form-group col-md-6">
                                    <?php echo Form::label('leased_land', __('Leased Land in Acres'), ['class' => 'form-label']); ?><span class="text-danger pl-1">*</span>
                                    <?php echo Form::text('leased_land', null, [
                                        'class' => 'form-control',
                                        'required' => 'required',
                                        'placeholder' => 'Enter Leased Land in Acres',
                                    ]); ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="float-end">
            <button type="submit" class="btn  btn-primary"><?php echo e(__('Update')); ?></button>
        </div>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script-page'); ?>
    <script>
        $('input[type="file"]').change(function(e) {
            var file = e.target.files[0].name;
            var file_name = $(this).attr('data-filename');
            $('.' + file_name).append(file);
        });
    </script>

    <script>
        $(document).ready(function() {
            var now = new Date();
            var month = (now.getMonth() + 1);
            var day = now.getDate();
            if (month < 10) month = "0" + month;
            if (day < 10) day = "0" + day;
            var today = now.getFullYear() + '-' + month + '-' + day;
            $('.current_date').val(today);
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/dismas/Desktop/fe/resources/views/farmers/edit.blade.php ENDPATH**/ ?>