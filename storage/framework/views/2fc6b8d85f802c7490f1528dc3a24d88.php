<?php if (isset($component)) { $__componentOriginal951024bfcf58033c82ac11d797616473 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal951024bfcf58033c82ac11d797616473 = $attributes; } ?>
<?php $component = App\View\Components\UserLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('user-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\UserLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('Profile')]); ?>
    <div class="w-full mx-auto p-4 pb-24 md:pb-4 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-all duration-300">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6 border-b border-gray-100 dark:border-gray-700 pb-4">
            <a href="<?php echo e(url()->previous()); ?>" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h1 class="text-xl font-semibold text-center flex-1 text-gray-900 dark:text-white">Security Settings</h1>
            <div class="w-8"></div> <!-- Spacer for centering -->
        </div>

        <!-- Subtitle -->
        <p class="text-center text-gray-500 dark:text-gray-400 mb-6">Change or edit your password and passcode.</p>

        <!-- Success Message -->
        <?php if(session('success')): ?>
            <div class="bg-green-50 dark:bg-green-900/20 border border-green-100 dark:border-green-700 text-green-700 dark:text-green-300 p-4 rounded-xl mb-6 flex items-start">
                <i class="fas fa-check-circle text-green-500 dark:text-green-400 mt-0.5 mr-2"></i>
                <span><?php echo e(session('success')); ?></span>
            </div>
        <?php endif; ?>

        <!-- Error Messages -->
        <?php if($errors->any()): ?>
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-700 text-red-700 dark:text-red-300 p-4 rounded-xl mb-6 flex items-start">
                <i class="fas fa-exclamation-circle text-red-500 dark:text-red-400 mt-0.5 mr-2"></i>
                <div>
                    <ul class="list-disc pl-5">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>

        <!-- Form -->
        <form method="POST" action="<?php echo e(route('profile.update')); ?>" class="max-w-md mx-auto space-y-6">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PATCH'); ?>

            <!-- Six Digit Passcode -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
                <label for="passcode" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Your Six Digit Passcode
                </label>
                <div class="relative">
                    <input 
                        type="password" 
                        id="passcode"
                        name="passcode"
                        maxlength="6"
                        class="w-full bg-gray-50 dark:bg-gray-700 border 
                            <?php $__errorArgs = ['passcode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 dark:border-red-500 <?php else: ?> border-gray-200 dark:border-gray-600 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> 
                            rounded-xl py-3.5 px-4 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 
                            focus:outline-none focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-900/30 focus:border-blue-300 dark:focus:border-blue-500"
                        placeholder="Enter six-digit passcode"
                        value="<?php echo e(old('passcode')); ?>"
                        required
                    >
                    <button 
                        type="button"
                        aria-label="Toggle Passcode Visibility"
                        class="absolute right-3 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-600 toggle-password transition-all"
                    >
                        <i class="fas fa-eye-slash text-sm"></i>
                    </button>
                </div>
                <?php $__errorArgs = ['passcode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-500 dark:text-red-400 text-xs mt-2"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Current Password -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
                <label for="current-password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Current Password
                </label>
                <div class="relative">
                    <input 
                        type="password" 
                        id="current-password"
                        name="current_password"
                        class="w-full bg-gray-50 dark:bg-gray-700 border 
                            <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 dark:border-red-500 <?php else: ?> border-gray-200 dark:border-gray-600 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> 
                            rounded-xl py-3.5 px-4 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 
                            focus:outline-none focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-900/30 focus:border-blue-300 dark:focus:border-blue-500"
                        placeholder="Enter current password"
                        required
                    >
                    <button 
                        type="button"
                        aria-label="Toggle Current Password Visibility"
                        class="absolute right-3 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-600 toggle-password transition-all"
                    >
                        <i class="fas fa-eye-slash text-sm"></i>
                    </button>
                </div>
                <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-500 dark:text-red-400 text-xs mt-2"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- New Password -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
                <label for="new-password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    New Password
                </label>
                <div class="relative">
                    <input 
                        type="password" 
                        id="new-password"
                        name="password"
                        class="w-full bg-gray-50 dark:bg-gray-700 border 
                            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 dark:border-red-500 <?php else: ?> border-gray-200 dark:border-gray-600 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> 
                            rounded-xl py-3.5 px-4 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 
                            focus:outline-none focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-900/30 focus:border-blue-300 dark:focus:border-blue-500"
                        placeholder="Enter new password"
                        required
                    >
                    <button 
                        type="button"
                        aria-label="Toggle New Password Visibility"
                        class="absolute right-3 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-600 toggle-password transition-all"
                    >
                        <i class="fas fa-eye-slash text-sm"></i>
                    </button>
                </div>
                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-500 dark:text-red-400 text-xs mt-2"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Confirm New Password -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
                <label for="confirm-password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Confirm New Password
                </label>
                <div class="relative">
                    <input 
                        type="password" 
                        id="confirm-password"
                        name="password_confirmation"
                        class="w-full bg-gray-50 dark:bg-gray-700 border 
                            <?php $__errorArgs = ['password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 dark:border-red-500 <?php else: ?> border-gray-200 dark:border-gray-600 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> 
                            rounded-xl py-3.5 px-4 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 
                            focus:outline-none focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-900/30 focus:border-blue-300 dark:focus:border-blue-500"
                        placeholder="Confirm new password"
                        required
                    >
                    <button 
                        type="button"
                        aria-label="Toggle Confirm Password Visibility"
                        class="absolute right-3 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-600 toggle-password transition-all"
                    >
                        <i class="fas fa-eye-slash text-sm"></i>
                    </button>
                </div>
                <?php $__errorArgs = ['password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-500 dark:text-red-400 text-xs mt-2"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Password Strength Indicator -->
            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-5 text-sm text-gray-600 dark:text-gray-300">
                <p class="flex items-start mb-3">
                    <i class="fas fa-info-circle text-blue-500 dark:text-blue-400 mr-2 mt-0.5"></i>
                    <span>Strong passwords should contain:</span>
                </p>
                <ul class="pl-8 list-disc space-y-1.5">
                    <li>At least 8 characters</li>
                    <li>Upper and lowercase letters</li>
                    <li>Numbers and special characters</li>
                </ul>
            </div>

            <!-- Submit Button -->
            <button 
                type="submit"
                class="w-full bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white font-medium py-3.5 px-4 rounded-xl transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-blue-400 dark:focus:ring-offset-gray-900"
            >
                Update Security Settings
            </button>
        </form>

            <section class="max-w-md mx-auto mt-10 rounded-xl border border-red-200 dark:border-red-900 p-5">
                <h2 class="text-lg font-semibold text-red-700 dark:text-red-300">
                    <?php echo e(__('Delete account')); ?>

                </h2>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                    <?php echo e(__('Your wallet, profile, and support data will be removed. Financial transaction history will be retained in anonymized form.')); ?>

                </p>
                <form
                    method="POST"
                    action="<?php echo e(route('profile.destroy')); ?>"
                    class="mt-4 space-y-3"
                    onsubmit="return confirm('<?php echo e(__('Are you sure you want to permanently delete your account?')); ?>')"
                >
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <label for="delete-account-password" class="block text-sm font-medium">
                        <?php echo e(__('Confirm your password')); ?>

                    </label>
                    <input
                        id="delete-account-password"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                    >
                    <?php $__errorArgs = ['password', 'userDeletion'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <button
                        type="submit"
                        class="w-full rounded-xl bg-red-600 px-4 py-3 font-medium text-white hover:bg-red-700"
                    >
                        <?php echo e(__('Permanently delete account')); ?>

                    </button>
                </form>
            </section>
    </div>

    <script>
        // Toggle password visibility
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('button.toggle-password').forEach(button => {
                button.addEventListener('click', function() {
                    const input = this.parentElement.querySelector('input');
                    const icon = this.querySelector('i');

                    // Add button press animation
                    this.classList.add('scale-95');
                    setTimeout(() => {
                        this.classList.remove('scale-95');
                    }, 100);

                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    } else {
                        input.type = 'password';
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    }
                });
            });

            // Basic password strength check (optional enhancement)
            const newPasswordInput = document.getElementById('new-password');
            if (newPasswordInput) {
                newPasswordInput.addEventListener('input', function() {
                    const password = this.value;
                    // Password strength logic can be added here
                });
            }
        });
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal951024bfcf58033c82ac11d797616473)): ?>
<?php $attributes = $__attributesOriginal951024bfcf58033c82ac11d797616473; ?>
<?php unset($__attributesOriginal951024bfcf58033c82ac11d797616473); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal951024bfcf58033c82ac11d797616473)): ?>
<?php $component = $__componentOriginal951024bfcf58033c82ac11d797616473; ?>
<?php unset($__componentOriginal951024bfcf58033c82ac11d797616473); ?>
<?php endif; ?><?php /**PATH C:\Tools\covachain.org\resources\views/user/profile/edit.blade.php ENDPATH**/ ?>