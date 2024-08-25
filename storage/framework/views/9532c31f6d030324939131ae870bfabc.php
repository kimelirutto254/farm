<?php
    $settings = App\Models\Utility::settings();
?>
<?php $__env->startSection('title'); ?>
    <?php echo e(__('Login')); ?>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
    <div class="card-body">
        <div>
            <h2 class="mb-3 f-w-600"><?php echo e(__('Login')); ?></h2>
        </div>
        <?php if(session('status')): ?>
            <div class="alert alert-danger">
                <?php echo e(session('status')); ?>

            </div>
        <?php endif; ?>
        <form method="POST" action="<?php echo e(route('login')); ?>" id="form_data" class="needs-validation" novalidate="">
            <?php echo csrf_field(); ?>
                <div class="form-group mb-3">
                    <label class="form-label"><?php echo e(__('Email')); ?></label>
                    <input id="email" type="email" class="form-control  <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        name="email" placeholder="<?php echo e(__('Enter your email')); ?>"
                        required autofocus>
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="error invalid-email text-danger" role="alert">
                            <small><?php echo e($message); ?></small>
                        </span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="form-group mb-3 pss-field">
                    <label class="form-label"><?php echo e(__('Password')); ?></label>
                    <input id="password" type="password" class="form-control  <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="password" placeholder="<?php echo e(__('Password')); ?>" required>
                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="error invalid-password text-danger" role="alert">
                            <small><?php echo e($message); ?></small>
                        </span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="form-group mb-4">
                    <div class="d-flex flex-wrap align-items-center justify-content-between">
                        <?php if(Route::has('change.langPass')): ?>
                            <span>
                                <a href="<?php echo e(route('change.langPass', $lang)); ?>" tabindex="0"><?php echo e(__('Forgot Your Password?')); ?></a>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
                <?php if(isset($settings['recaptcha_module']) && $settings['recaptcha_module'] == 'yes'): ?>
                    <?php if(isset($settings['google_recaptcha_version']) && $settings['google_recaptcha_version'] == 'v2'): ?>
                        <div class="form-group mb-3">
                            <?php echo NoCaptcha::display(); ?>

                            <?php $__errorArgs = ['g-recaptcha-response'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="small text-danger" role="alert">
                                    <strong><?php echo e($message); ?></strong>
                                </span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    <?php else: ?>
                        <div class="form-group col-lg-12 col-md-12 mt-3">
                            <input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response" class="form-control">
                            <?php $__errorArgs = ['g-recaptcha-response'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="error small text-danger" role="alert">
                                    <strong><?php echo e($message); ?></strong>
                                </span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <div class="d-grid">
                    <button type="submit" class="btn btn-secondary bg-black btn-block mt-2"
                        id="login_button"><?php echo e(__('Login')); ?></button>
                </div>
                <?php if((!empty($settings['signup_button']) ? $settings['signup_button'] : '') == 'on'): ?>
                    <div class="my-4 text-xs text-center">
                        <p>
                            <?php echo e(__("Don't have an account?")); ?>

                            <a href="<?php echo e(route('register')); ?>"><?php echo e(__('Register')); ?></a>
                        </p>
                    </div>
                <?php endif; ?>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guest', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/dismas/Desktop/fe/resources/views/auth/login.blade.php ENDPATH**/ ?>