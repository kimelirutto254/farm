<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Edit Supplier')); ?>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>

<style>
    .cursor-pointer {
        cursor: pointer;
    }
</style>

    <div class="">
        <div class="">

            <?php echo e(Form::model($supplier, ['route' => ['suppliers.update', $supplier->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data'])); ?>

            <div class="row">
                <div class="col-md-8">
                    <div class="card ">
                        <div class="card-header">
                            <h5><?php echo e(__('Personal Detail')); ?></h5>
                        </div>
                        <div class="card-body">

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <?php echo Form::label('name', __(' Name'), ['class' => 'form-label']); ?>

                                    <?php echo Form::text('name', old('name'), [
                                        'class' => 'form-control',
                                        'required' => 'required',
                                        'placeholder' => 'Enter first name',
                                    ]); ?>

                                </div>
                               
                                <div class="form-group col-md-6">
                                    <?php echo Form::label('name', __(' Email'), ['class' => 'form-label']); ?>

                                    <?php echo Form::text('email', old('email'), [
                                        'class' => 'form-control',
                                        'required' => 'required',
                                        'placeholder' => 'Enter Email',
                                    ]); ?>

                                </div>
                               
                                <div class="form-group col-md-6">
                                    <?php echo Form::label('phone_number', __('Phone Number'), ['class' => 'form-label']); ?>

                                    <?php echo Form::text('phone', old('phone'), [
                                        'class' => 'form-control',
                                        'required' => 'required',
                                        'placeholder' => 'Enter Phone Number',
                                    ]); ?>

                                </div>
                                <div class="form-group col-md-6">
                                    <?php echo Form::label('location', __('Location'), ['class' => 'form-label']); ?>

                                    <?php echo Form::text('location', old('location'), [
                                        'class' => 'form-control',
                                        'required' => 'required',
                                        'placeholder' => 'Enter Location',
                                    ]); ?>

                                </div>
                                <div class="form-group col-md-6">
                                    <?php echo Form::label('status', __('Status'), ['class' => 'form-label']); ?>

                                    <?php echo Form::select('status', ['active' => 'Active', 'inactive' => 'Inactive'], old('status'), [
                                        'class' => 'form-control',
                                        'required' => 'required',
                                        'placeholder' => 'Select Status',
                                    ]); ?>

                                </div>
                                
                                <div class="form-group col-md-6">
                                    <?php echo e(Form::label('user', __('User'),['class'=>'col-form-label'])); ?>

                                    <?php echo Form::select('category_id', $categories, null,array('class' => 'form-select','required'=>'required')); ?>

                                </div>
                        
                        </div>
                    </div>
                </div>
                
                <div class="float-end">
                    <button type="submit" class="btn  btn-primary"><?php echo e('Update'); ?></button>
                </div>
         
            <div class="col-12">
                <?php echo Form::close(); ?>

            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/dismas/Desktop/fe/resources/views/suppliers/edit.blade.php ENDPATH**/ ?>