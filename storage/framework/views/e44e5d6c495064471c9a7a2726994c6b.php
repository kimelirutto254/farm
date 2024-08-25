 
 <?php $__env->startSection('title'); ?>
     <?php echo e(__('Register')); ?>

 <?php $__env->stopSection(); ?>

 <?php
    $settings = App\Models\Utility::settings();

 
 ?>

 <?php $__env->startSection('language-bar'); ?>
     <?php
         $languages = App\Models\Utility::languages();
     ?>
     <div class="lang-dropdown-only-desk">
         <li class="dropdown dash-h-item drp-language">
             <a class="dash-head-link dropdown-toggle btn" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                 <span class="drp-text"> <?php echo e(ucFirst($languages[$lang])); ?>

                 </span>
             </a>
             <div class="dropdown-menu dash-h-dropdown dropdown-menu-end">
                 <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                     <a href="<?php echo e(route('register', [$ref, $code])); ?>" tabindex="0"
                         class="dropdown-item <?php echo e($code == $lang ? 'active' : ''); ?>">
                         <span><?php echo e(ucFirst($language)); ?></span>
                     </a>
                 <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
             </div>
         </li>
     </div>
 <?php $__env->stopSection(); ?>

 <?php $__env->startSection('content'); ?>
     <div class="card-body">
         <div>
             <h2 class="mb-3 f-w-600"><?php echo e(__('Register')); ?></h2>
         </div>
         <?php if(session('status')): ?>
             <div class="alert alert-danger">
                 <?php echo e(session('status')); ?>

             </div>
         <?php endif; ?>
         <form method="POST" id="registerForm" action="<?php echo e(route('register')); ?>" class="needs-validation" novalidate="">
             <?php echo csrf_field(); ?>
             <div class="">
                 <div class="form-group mb-3">
                     <label class="form-label"><?php echo e(__('Name')); ?></label>
                     <input id="name" type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                         name="name" value="<?php echo e(old('name')); ?>" required autocomplete="name" autofocus
                         placeholder="<?php echo e(__('Enter your name')); ?>">
                     <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                         <span class="error invalid-name text-danger" role="alert">
                             <strong><?php echo e($message); ?></strong>
                         </span>
                     <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                 </div>

                 <div class="form-group mb-3">
                     <label class="form-label"><?php echo e(__('Company Name')); ?></label>
                     <input id="company_name" type="text" class="form-control <?php $__errorArgs = ['company_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                         name="company_name" value="<?php echo e(old('company_name')); ?>" required autocomplete="company_name"
                         placeholder="<?php echo e(__('Enter your Company name')); ?>">
                     <?php $__errorArgs = ['company_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                         <span class="error invalid-name text-danger" role="alert">
                             <strong><?php echo e($message); ?></strong>
                         </span>
                     <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                 </div>

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
                         name="email" value="<?php echo e(old('email')); ?>" required placeholder="<?php echo e(__('Enter your email')); ?>">
                     <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                         <span class="error invalid-email text-danger" role="alert">
                             <strong><?php echo e($message); ?></strong>
                         </span>
                     <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                 </div>

                 <div class="form-group mb-3">
                     <label class="form-label"><?php echo e(__('Password')); ?></label>
                     <input id="password" type="password" class="form-control  <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                         name="password" required autocomplete="new-password"
                         placeholder="<?php echo e(__('Enter your password')); ?>">
                     <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                         <span class="error invalid-password text-danger" role="alert">
                             <strong><?php echo e($message); ?></strong>
                         </span>
                     <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                 </div>

                 <div class="form-group">
                     <label class="form-label"><?php echo e(__('Confirm password')); ?></label>
                     <input id="password-confirm" type="password"
                         class="form-control <?php $__errorArgs = ['password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                         name="password_confirmation" required autocomplete="new-password"
                         placeholder="<?php echo e(__('Enter confirm password')); ?>">
                     <?php $__errorArgs = ['password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                         <span class="error invalid-password_confirmation text-danger" role="alert">
                             <strong><?php echo e($message); ?></strong>
                         </span>
                     <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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

                    <div class="form-check custom-checkbox">
                        <input type="checkbox"
                            class="form-check-input <?php $__errorArgs = ['terms_condition_check'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            id="termsCheckbox" name="terms_condition_check">
                        <input type="hidden" name="terms_condition" id="terms_condition" value="off">

                        <label class="text-sm" for="terms_condition_check"><?php echo e(__('I agree to the ')); ?>

                         
                          
                        </label>
                    </div>
                    <?php $__errorArgs = ['terms_condition_check'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="error invalid-terms_condition_check text-danger" role="alert">
                            <strong><?php echo e(__('Please check this box if you want to proceed.')); ?></strong>
                        </span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                 <div class="d-grid">
                     <input type="hidden" name="ref_code" value="<?php echo e($ref); ?>">
                     <button class="btn btn-primary btn-block mt-2" type="submit"><?php echo e(__('Register')); ?></button>
                 </div>

                 <div class="my-4 text-xs text-center">
                     <p>
                         <?php echo e(__('Already have an account?')); ?> <a
                             href="<?php echo e(route('login', $lang)); ?>"><?php echo e(__('Login')); ?></a>
                     </p>
                 </div>
             </div>
         </form>
     </div>
 <?php $__env->stopSection(); ?>
 <?php $__env->startPush('custom-scripts'); ?>
    <?php if(isset($settings['recaptcha_module']) && $settings['recaptcha_module'] == 'yes'): ?>
        <?php if(isset($settings['google_recaptcha_version']) && $settings['google_recaptcha_version'] == 'v2'): ?>
            <?php echo NoCaptcha::renderJs(); ?>

        <?php else: ?>
            <script src="https://www.google.com/recaptcha/api.js?render=<?php echo e($settings['google_recaptcha_key']); ?>"></script>
            <script>
                $(document).ready(function() {
                    grecaptcha.ready(function() {
                        grecaptcha.execute('<?php echo e($settings['google_recaptcha_key']); ?>', {
                            action: 'submit'
                        }).then(function(token) {
                            $('#g-recaptcha-response').val(token);
                        });
                    });
                });
            </script>
        <?php endif; ?>
    <?php endif; ?>

 <?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.guest', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/dismas/Desktop/fe/resources/views/auth/register.blade.php ENDPATH**/ ?>