 

<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Dashbord')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="dash-container">
        <div class="dash-content">
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="page-header-title">
                                <h4 class="m-b-10"><?php echo e(__('User')); ?></h4>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="<?php echo e(url('dashboard')); ?>"><?php echo e(__('Home')); ?></a></li>
                                <li class="breadcrumb-item"><?php echo e(__('User')); ?></li>
                            </ul>
                        </div>

                        <div class="col-md-6 d-flex justify-content-end text-right">
                            <div class="btn btn-sm btn-primary btn-icon m-1">
                                <a href="#" data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="<?php echo e(__('Create User')); ?>" data-url="<?php echo e(route('user.create')); ?>" data-size="md"
                                    data-ajax-popup="true" data-title="<?php echo e(__('Create New User')); ?>">
                                    <i class="ti ti-plus text-white"></i>
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->
            <!-- [ Main Content ] start -->
            <div class="row">
                <?php if(!empty($users)): ?>
                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-md-3 col-sm-6 col-md-3">
                            <div class="card text-white text-center">
                                <div class="card-header border-0 pb-0">
                                    <div class="card-header-right">
                                        <div class="btn-group card-option">
                                            <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false">
                                                <i class="feather icon-more-vertical"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end" style="">
                                                <a href="#" class="dropdown-item" data-ajax-popup="true" data-size="md"
                                                    data-url="<?php echo e(route('user.edit', $user->id)); ?>" data-title="Edit User"
                                                    data-toggle="tooltip">
                                                    <i class="ti ti-pencil"></i> <span><?php echo e(__('Edit')); ?></span>
                                                </a>

                                                <a href="#" data-size="lg"
                                                    data-url="<?php echo e(route('plan.upgrade', $user->id)); ?>"
                                                    data-ajax-popup="true" data-toggle="tooltip"
                                                    data-title="<?php echo e(__('Upgrade Plan')); ?>" class="dropdown-item">
                                                    <i class="ti ti-award"></i> <span><?php echo e(__('Upgrade Plan')); ?></span>
                                                </a>

                                                <a href="#" data-size="md"
                                                    data-url="<?php echo e(route('user.reset', \Crypt::encrypt($user->id))); ?>"
                                                    data-ajax-popup="true" data-title="<?php echo e(__('Reset Password')); ?>"
                                                    class="dropdown-item">
                                                    <i class="ti ti-key"></i>
                                                    <span><?php echo e(__('Forgot Password')); ?></span>
                                                </a>

                                                <?php echo Form::open(['method' => 'DELETE', 'route' => ['user.destroy', $user->id]]); ?>

                                                <a href="#!" class="mx-3 btn btn-sm align-items-center show_confirm">
                                                    <i class="ti ti-trash"></i>
                                                    <span><?php echo e(__('Delete')); ?></span>
                                                </a>
                                                <?php echo Form::close(); ?>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body">
                                    

                                    <?php
                                        $name = !empty($user->first_name) ? $user->first_name : $user->type->company_name;
                                        $profile_pic=\App\Models\Utility::get_file($user->getUserInfo->DefaultProfilePic());
                                    ?>
                                    <img <?php if(!empty($profile_pic)): ?> src="<?php echo e($profile_pic); ?>" <?php else: ?>  avatar="<?php echo e($name); ?>" <?php endif; ?> alt="user-image" class="img-fluid rounded-circle" style="height100px;width:100px;">

                                    <h4 class="text-primary mt-2"><?php echo e($user->company_name); ?></h4>
                                    <h6 class="office-time mb-0 mb-4"><?php echo e($user->email); ?></h6>

                                    <div class="col-12">
                                        <hr class="my-3">
                                    </div>

                                    <div class="row g-2">
                                        <div class="col-6 col-sm-4">
                                            <div class="d-grid">
                                                <span
                                                    class="d-block  font-weight-bold mb-0 text-dark"><?php echo e(!empty($user->currentPlan) ? $user->currentPlan->name : __('Free')); ?></span>
                                                <span class="d-block text-muted"><?php echo e(__('Plan')); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-6 col-sm-4">
                                            <div class="d-grid">
                                                <span
                                                    class="d-block font-weight-bold mb-0 text-dark"><?php echo e(!empty($user->plan_expire_date) ? \Auth::user()->dateFormat($user->plan_expire_date) : 'Lifetime'); ?></span>
                                                <span class="d-block text-muted"><?php echo e(__('Plan Expired')); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-4">
                                            <div class="d-grid">
                                                <span
                                                    class="d-block font-weight-bold mb-0 text-dark"><?php echo e($user->countEmployees($user->id)); ?></span>
                                                <span class="d-block text-muted"><?php echo e(__('Employees')); ?></span>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
                <div class="col-md-3">
                    <a href="#" class="btn-addnew-project py-5" data-bs-toggle="tooltip" data-bs-placement="top"
                        title="<?php echo e(__('Create User')); ?>" data-url="<?php echo e(route('user.create')); ?>" data-size="md"
                        data-ajax-popup="true" data-title="<?php echo e(__('Create New User')); ?>">
                        <div class="bg-primary proj-add-icon py-2 mt-5">
                            <i class="ti ti-plus"></i>
                        </div>
                        <h6 class="mt-4 mb-2"><?php echo e(__('New User')); ?></h6>
                        <p class="text-muted text-center mb-5"><?php echo e(__('Click here to add new user')); ?></p>
                    </a>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\Laragon\www\shift2go\resources\views/user/index.blade.php ENDPATH**/ ?>