<?php echo e(Form::model($role, ['route' => ['roles.update', $role->id], 'method' => 'PUT'])); ?>

<div class="modal-body">
    <div class="row">
        <div class="form-group">
            <?php echo e(Form::label('name', __('Name'), ['class' => 'form-label'])); ?>


                <div class="form-icon-user">
                    <?php echo e(Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('Enter Role Name'),'required' => 'required'])); ?>

                </div>

            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <span class="invalid-name" role="alert">
                    <strong class="text-danger"><?php echo e($message); ?></strong>
                </span>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="form-group">
            <?php if(!empty($permissions)): ?>
                <h6 class="my-3"><?php echo e(__('Assign Permission to Roles')); ?> </h6>
                <table class="table  mb-0" id="dataTable-1">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" class="align-middle checkbox_middle form-check-input"
                                    name="checkall" id="checkall">
                            </th>
                            <th><?php echo e(__('Module')); ?> </th>
                            <th><?php echo e(__('Permissions')); ?> </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        $modules = [
                            'Dashboard',
                            'Role',
                            'User',
                            'Farmers',
                            'Inspections',
                            'Media',
                            'Inspectors',
                            'Suppliers',
                            'Checklist',

                         ];
                            if (Auth::user()->type == 'super admin') {
                                $modules[] = 'Language';
                            }

                        ?>
                        <?php $__currentLoopData = $modules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><input type="checkbox" class="align-middle ischeck form-check-input"
                                    name="checkall" data-id="<?php echo e(str_replace(' ', '', $module)); ?>"></td>
                                <td><label class="ischeck form-label"
                                        data-id="<?php echo e(str_replace(' ', '', $module)); ?>"><?php echo e(ucfirst($module)); ?></label>
                                </td>
                                <td>
                                    <div class="row">
                                        <?php if(in_array('Manage ' . $module, (array) $permissions)): ?>
                                            <?php if($key = array_search('Manage ' . $module, $permissions)): ?>
                                                <div class="col-md-3 custom-control custom-checkbox">
                                                    <?php echo e(Form::checkbox('permissions[]', $key, $role->permission, ['class' => 'form-check-input isscheck isscheck_' . str_replace(' ', '', $module), 'id' => 'permission' . $key])); ?>

                                                    <?php echo e(Form::label('permission' . $key, 'Manage', ['class' => 'form-label font-weight-500'])); ?><br>
                                                </div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <?php if(in_array('Create ' . $module, (array) $permissions)): ?>
                                            <?php if($key = array_search('Create ' . $module, $permissions)): ?>
                                                <div class="col-md-3 custom-control custom-checkbox">
                                                    <?php echo e(Form::checkbox('permissions[]', $key, $role->permission, ['class' => 'form-check-input isscheck isscheck_' . str_replace(' ', '', $module), 'id' => 'permission' . $key])); ?>

                                                    <?php echo e(Form::label('permission' . $key, 'Create', ['class' => 'form-label font-weight-500'])); ?><br>
                                                </div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <?php if(in_array('Edit ' . $module, (array) $permissions)): ?>
                                            <?php if($key = array_search('Edit ' . $module, $permissions)): ?>
                                                <div class="col-md-3 custom-control custom-checkbox">
                                                    <?php echo e(Form::checkbox('permissions[]', $key, $role->permission, ['class' => 'form-check-input isscheck isscheck_' . str_replace(' ', '', $module), 'id' => 'permission' . $key])); ?>

                                                    <?php echo e(Form::label('permission' . $key, 'Edit', ['class' => 'form-label font-weight-500'])); ?><br>
                                                </div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <?php if(in_array('Delete ' . $module, (array) $permissions)): ?>
                                            <?php if($key = array_search('Delete ' . $module, $permissions)): ?>
                                                <div class="col-md-3 custom-control custom-checkbox">
                                                    <?php echo e(Form::checkbox('permissions[]', $key, $role->permission, ['class' => 'form-check-input isscheck isscheck_' . str_replace(' ', '', $module), 'id' => 'permission' . $key])); ?>

                                                    <?php echo e(Form::label('permission' . $key, 'Delete', ['class' => 'form-label font-weight-500'])); ?><br>
                                                </div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <?php if(in_array('Show ' . $module, (array) $permissions)): ?>
                                            <?php if($key = array_search('Show ' . $module, $permissions)): ?>
                                                <div class="col-md-3 custom-control custom-checkbox">
                                                    <?php echo e(Form::checkbox('permissions[]', $key, $role->permission, ['class' => 'form-check-input isscheck isscheck_' . str_replace(' ', '', $module), 'id' => 'permission' . $key])); ?>

                                                    <?php echo e(Form::label('permission' . $key, 'Show', ['class' => 'form-label font-weight-500'])); ?><br>
                                                </div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <?php if(in_array('Upgrade ' . $module, (array) $permissions)): ?>
                                            <?php if($key = array_search('Upgrade ' . $module, $permissions)): ?>
                                                <div class="col-md-3 custom-control custom-checkbox">
                                                    <?php echo e(Form::checkbox('permissions[]', $key, $role->permission, ['class' => 'form-check-input isscheck isscheck_' . str_replace(' ', '', $module), 'id' => 'permission' . $key])); ?>

                                                    <?php echo e(Form::label('permission' . $key, 'Upgrade', ['class' => 'form-label font-weight-500'])); ?><br>
                                                </div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <?php if(in_array($module, (array) $permissions)): ?>
                                            <?php if($key = array_search($module, $permissions)): ?>
                                                <div class="col-md-3 custom-control custom-checkbox">
                                                    <?php echo e(Form::checkbox('permissions[]', $key, $role->permission, ['class' => 'form-check-input isscheck isscheck_' . str_replace(' ', '', $module), 'id' => 'permission' . $key])); ?>

                                                    <?php echo e(Form::label('permission' . $key, 'Reset Password', ['class' => 'form-label font-weight-500'])); ?><br>
                                                </div>
                                            <?php endif; ?>
                                        <?php endif; ?>

                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal"><?php echo e(__('Cancel')); ?></button>
    <input type="submit" value="<?php echo e(__('Update')); ?>" class="btn  btn-primary">
</div>
<?php echo e(Form::close()); ?>



<script>
    $(document).ready(function() {
        $("#checkall").click(function() {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
        $(".ischeck").click(function() {
            var ischeck = $(this).data('id');
            $('.isscheck_' + ischeck).prop('checked', this.checked);
        });
    });
</script>
<?php /**PATH /Users/dismas/Desktop/fe/resources/views/roles/edit.blade.php ENDPATH**/ ?>