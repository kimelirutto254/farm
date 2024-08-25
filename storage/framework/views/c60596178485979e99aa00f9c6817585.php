<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="card">
        <div id="kt_app_toolbar" class="app-toolbar pt-5">
            <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
                <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                    <div class="page-title d-flex flex-column gap-1 me-3 mb-2">
                        <h1
                            class="page-heading d-flex flex-column justify-content-center text-dark fw-bolder fs-1 lh-0">
                            All Documents</h1>
                    </div>
                    <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold">
                       
                        <li class="nav-item mt-2">
                        <a href="#" data-url="<?php echo e(route('media-upload.create')); ?>" data-ajax-popup="true"
            data-title="<?php echo e(__('Upload New')); ?>" data-size="lg" data-bs-toggle="tooltip" title=""
            class="btn btn-sm btn-primary" data-bs-original-title="<?php echo e(__('Upload')); ?>">
            Upload New
        </a>
                        </li>

                    </ul>
                </div>
            </div>
        </div>
    </div>
<div id="kt_app_content" class="app-content flex-column-fluid">
								<!--begin::Content container-->
								<div id="kt_app_content_container" class="app-container container-fluid">
									<!--begin::Stats-->
<div class="row">

    <div class="col-xl-12">

                <div class="card-header card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table table-rounded table-striped border gy-7 gs-10" id="example">
                            <thead>
                                <tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
                                    <th class="min-w-125px">Name</th>
                                    <th class="min-w-125px">Document</th>
                                    <th class="min-w-125px">Role</th>
                                    <th class="min-w-125px">Description</th>
                                
                                    <?php if(Gate::check('Edit Media') || Gate::check('Delete Media')): ?>
                                    <th class="text-end min-w-70px">Actions</th>
                                 
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody class="">
                                <?php $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($document->name); ?></td>
                                    <td>
                                        <?php
                                            $documentPath = \App\Models\Utility::get_file('uploads/documentUpload');
                                            $roles = \Spatie\Permission\Models\Role::find($document->role);
                                        ?>
                                        <?php if(!empty($document->document)): ?>
                                            <div class="action-btn bg-primary-outlined ms-2">
                                                <a class="mx-3 btn btn-sm align-items-center"
                                                    href="<?php echo e($documentPath . '/' . $document->document); ?>" download>
                                                    <i class="ki ki-download text-white"></i>
                                                </a>
                                            </div>
                                            <div class="action-btn bg-secondary-outlined ms-2">
                                                <a class="mx-3 btn btn-sm align-items-center"
                                                    href="<?php echo e($documentPath . '/' . $document->document); ?>"
                                                    target="_blank">
                                                    <i class="fa fa-eye text-white" data-bs-toggle="tooltip"
                                                        data-bs-original-title="<?php echo e(__('Preview')); ?>"></i>
                                                </a>
                                            </div>
                                        <?php else: ?>
                                            <p>-</p>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e(!empty($roles) ? $roles->name : 'All'); ?></td>
                                    <td>
                                        <p
                                            style="white-space: nowrap;
                                        width: 200px;
                                        overflow: hidden;
                                        text-overflow: ellipsis;">
                                            <?php echo e($document->description); ?>

                                        </p>
                                    </td>
                                   
                                 
                                    <td class="text-end">
                                        <a href="#" class="btn btn-sm btn-primary btn-flex btn-center btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                            <i class="ki-duotone ki-down fs-5 ms-1"></i></a>
                                            <!--begin::Menu-->
                                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                                                <!--begin::Menu item-->
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Edit Media')): ?>
                                                        <a href="#" class="mx-3 btn btn-sm  align-items-center"
                                                            data-url="<?php echo e(route('media-upload.edit', $document->id)); ?>"
                                                            data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                            title="" data-title="<?php echo e(__('Edit Media')); ?>"
                                                            data-bs-original-title="<?php echo e(__('Edit')); ?>">
                                                            Edit
                                                        </a>
                                                
                                                <?php endif; ?>

                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Delete Media')): ?>
                                                        <?php echo Form::open([
                                                            'method' => 'DELETE',
                                                            'route' => ['media-upload.destroy', $document->id],
                                                            'id' => 'delete-form-' . $document->id,
                                                        ]); ?>

                                                        <a href="#" data-size="lg"
                                                            class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                            data-bs-toggle="tooltip" title=""
                                                            data-bs-original-title="Delete" aria-label="Delete">Delete </a>
                                                        </form>
                                                 
                                                <?php endif; ?>
                                                
                                              
                                            </div>
                                        </td>
                                       
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u931094414/domains/royalblue-stinkbug-731974.hostingersite.com/public_html/resources/views/documentUpload/index.blade.php ENDPATH**/ ?>