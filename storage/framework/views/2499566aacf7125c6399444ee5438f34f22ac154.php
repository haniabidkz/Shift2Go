<!doctype html>
<?php

$logo=\App\Models\Utility::get_file('uploads/logo/');
$company_favicon = Utility::getValByName('company_favicon');
$SITE_RTL = Utility::getValByName('SITE_RTL');
$setting = App\Models\Utility::colorset();
$setting = \App\Models\Utility::settings();
$color = 'theme-3';
if (!empty($setting['color'])) {
    $color = $setting['color'];
}
$darklayout = Utility::getValByName('cust_darklayout');
?>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" dir="<?php echo e($SITE_RTL == 'on' ? 'rtl' : ''); ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>
        <?php echo e(Utility::getValByName('title_text')? Utility::getValByName('title_text'): config('app.name', 'RotaGo SaaS')); ?>

        - <?php echo $__env->yieldContent('page-title'); ?> </title>
        <link rel="icon" href="<?php echo e($logo . (isset($favicon) && !empty($favicon) ? $favicon : 'favicon.png')); ?>"
        type="image/x-icon" />
    <!-- Scripts -->
    <script src="<?php echo e(asset('js/app.js')); ?>" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <!-- <link rel="stylesheet" href="<?php echo e(asset('assets/css/style.css')); ?>" id="main-style-link"> -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/customizer.css')); ?>">

  <?php if($SITE_RTL == 'on'): ?>
  <link rel="stylesheet" href="<?php echo e(asset('assets/css/style-rtl.css')); ?>">
  <?php endif; ?>

    <?php if($darklayout == 'on'): ?>
        <link rel="stylesheet" href="<?php echo e(asset('assets/css/style-dark.css')); ?>">
    <?php else: ?>
        <link rel="stylesheet" href="<?php echo e(asset('assets/css/style.css')); ?>">
    <?php endif; ?>

    <style>
        [dir="rtl"] .dash-sidebar {
            left: auto !important;
        }

        /* [dir="rtl"] .dash-header {
            left: 0;
            right: 280px;
        } */

        [dir="rtl"] .dash-header:not(.transprent-bg) .header-wrapper {
            padding: 0 0 0 30px;
        }

        [dir="rtl"] .dash-header:not(.transprent-bg):not(.dash-mob-header) ~ .dash-container {
            margin-left: 0px;
        }

        [dir="rtl"] .me-auto.dash-mob-drp {
            margin-right: 10px !important;
        }

        [dir="rtl"] .me-auto {
            margin-left: 10px !important;
        }
    </style>
    <style>
        .language_option_bg option {
            background-color: #fff;
            color: #000;
        }
    </style>
</head>

<body class="<?php echo e($color); ?>">
    <!-- [ auth-signup ] start -->
    <div class="auth-wrapper auth-v3">
        <div class="bg-auth-side bg-primary"></div>
        <div class="auth-content">
            <nav class="navbar navbar-expand-md navbar-light default">
                <div class="container-fluid pe-2">
                    <a class="navbar-brand" href="#">
                        <?php if(!empty($darklayout) && $darklayout == 'on'): ?>
                            <img src="<?php echo e($logo.'/'.(isset($company_logo) && !empty($company_logo)?$company_logo:'logo-light.png')); ?>"
                            class="logo logo-lg" alt="..." >
                        <?php else: ?>
                            <img src="<?php echo e($logo.(isset($company_logos) && !empty($company_logo)?$company_logo:'logo-dark.png')); ?>"
                            class="logo logo-lg" alt="..." >
                        <?php endif; ?>
                    </a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false"
                            aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>

                    <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
                        <ul class="navbar-nav align-items-center ms-auto mb-2 mb-lg-0">
                            <li class="nav-item">
                                <a class="nav-link active" href="#"><?php echo e(__('Support')); ?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#"><?php echo e(__('Terms')); ?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#"><?php echo e(__('Privacy')); ?></a>
                            </li>
                            <li class="nav-item">
								<?php echo $__env->yieldContent('lang-selectbox'); ?>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
			<?php echo $__env->yieldContent('content'); ?>
            <div class="auth-footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-6">
                            <p class="text-body"><?php echo e(__('Copyright')); ?> <?php echo e((Utility::getValByName('footer_text')) ? Utility::getValByName('footer_text') :config('app.name', 'RotaGo')); ?> <?php echo e(date('Y')); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ auth-signup ] end -->

    <!-- Scripts -->
    <script src="<?php echo e(asset('custom/libs/jquery/dist/jquery.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/vendor-all.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/plugins/bootstrap.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/plugins/feather.min.js')); ?>"></script>
    <script>
        feather.replace();
    </script>

    <?php echo $__env->yieldPushContent('custom-scripts'); ?>
    <?php if($setting['cookie_consent'] == 'on'): ?>
        <?php echo $__env->make('layouts.cookie_consent', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>
    <?php echo $__env->yieldPushContent('pagescript'); ?>

    <?php if(\Session::has('success')): ?>
        <script>
            show_toastr('<?php echo e(__('Success')); ?>', '<?php echo session('success'); ?>', 'success');
        </script>
        <?php echo e(Session::forget('success')); ?>

    <?php endif; ?>

    <?php if(Session::has('error')): ?>
        <script>
            show_toastr('<?php echo e(__('Error')); ?>', '<?php echo session('error'); ?>', 'error');
        </script>
        <?php echo e(Session::forget('error')); ?>

    <?php endif; ?>

</body>

</html>
<?php /**PATH F:\Laragon\www\shift2go\resources\views/layouts/auth.blade.php ENDPATH**/ ?>