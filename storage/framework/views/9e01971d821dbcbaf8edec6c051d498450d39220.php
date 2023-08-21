<?php
$unseenCounter = App\Models\ChMessage::where('to_id', Auth::user()->id)->where('seen', 0)->count();
// $profile_pic = asset(Storage::url(Auth::user()->getUserInfo->DefaultProfilePic()));
$profile_pic=\App\Models\Utility::get_file(Auth::user()->getUserInfo->DefaultProfilePic());
// dd($profile_pic);
$name = !empty(Auth::user()->first_name.' '.Auth::user()->last_name) ? Auth::user()->first_name.' '. Auth::user()->last_name : Auth::user()->company_name;
$users = \Auth::user();
$currantLang = $users->currentLanguage();
if (empty($currantLang)) {
    $currantLang = 'en';
}
$languages = \App\Models\Utility::languages();
$footer_text = isset(\App\Models\Utility::settings()['footer_text']) ? \App\Models\Utility::settings()['footer_text'] : '';
$setting = \App\Models\Utility::colorset();
$SITE_RTL= isset($setting['SITE_RTL'])?$setting['SITE_RTL']:'off';
?>
<?php if(isset($setting['cust_theme_bg']) && $setting['cust_theme_bg'] == 'on' ): ?>

<header class="dash-header transprent-bg">
<?php else: ?>
<header class="dash-header">
<?php endif; ?>
    <div class="header-wrapper">
        <div class="me-auto dash-mob-drp">
            <ul class="list-unstyled">
                <li class="dash-h-item mob-hamburger">
                    <a href="#!" class="dash-head-link" id="mobile-collapse">
                        <div class="hamburger hamburger--arrowturn">
                            <div class="hamburger-box">
                                <div class="hamburger-inner"></div>
                            </div>
                        </div>
                    </a>
                </li>
                <li class="dropdown dash-h-item drp-company">
                    <a class="dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                        role="button" aria-haspopup="false" aria-expanded="false">

                        <img class="theme-avtar" <?php if(!empty($profile_pic)): ?> src="<?php echo e($profile_pic); ?>" <?php else: ?>  avatar="<?php echo e($name); ?>" <?php endif; ?>>

                        
                        </span>
                        <span class="hide-mob ms-2"><?php echo e(__('Hi,')); ?> <?php echo e($name); ?>!</span>
                        <i class="ti ti-chevron-down drp-arrow nocolor hide-mob"></i>
                    </a>
                    <div class="dropdown-menu dash-h-dropdown">
                        <a href="<?php echo e(url('profile/' . Crypt::encrypt(Auth::id()))); ?>" class="dropdown-item">
                            <i class="ti ti-user"></i>
                            <span><?php echo e(__('Profile')); ?></span>
                        </a>
                        <a href="#!" class="dropdown-item"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="ti ti-power"></i>
                            <span><?php echo e(__('Logout')); ?></span>
                        </a>
                        <?php echo Form::open(['method' => 'POST', 'route' => ['logout'], 'id' => 'logout-form', 'style' => 'display: none;']); ?>

                        <?php echo Form::close(); ?>

                    </div>
                </li>
            </ul>
        </div>
        <div class="ms-auto">
            <ul class="list-unstyled">
                <?php if(\Auth::user()->type != 'super admin'): ?>
                    <li class="dash-h-item">
                        <a class="dash-head-link me-0" href="<?php echo e(url('chats')); ?>">
                            <i class="ti ti-message-circle"></i>
                            <span class="bg-danger dash-h-badge message-counter custom_messanger_counter"><?php echo e($unseenCounter); ?>

                                <span class="sr-only"></span>
                            </span>
                        </a>
                    </li>
                <?php endif; ?>
                <li class="dropdown dash-h-item drp-language">
                    <a class="dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                        role="button" aria-haspopup="false" aria-expanded="false">
                        <i class="ti ti-world nocolor"></i>
                        <span class="drp-text hide-mob text-uppercase"><?php echo e($currantLang); ?></span>
                        <i class="ti ti-chevron-down drp-arrow nocolor"></i>
                    </a>
                    <div class="dropdown-menu dash-h-dropdown dropdown-menu-end">
                        <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e(route('change.language', $language)); ?>" class="dropdown-item <?php if($language==$currantLang): ?> active-language <?php endif; ?>">
                                <span> <?php echo e(Str::upper($language)); ?></span>
                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php if(Auth::user()->type == 'super admin'): ?>
                            <div class="dropdown-divider m-0"></div>
                            <a href="<?php echo e(url('manage-language', Auth::user()->lang)); ?>"
                                class="dropdown-item text-primary"><?php echo e(__('Manage Language')); ?></a>
                        <?php endif; ?>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</header>
<?php /**PATH F:\Laragon\www\shift2go\resources\views/partision/header.blade.php ENDPATH**/ ?>