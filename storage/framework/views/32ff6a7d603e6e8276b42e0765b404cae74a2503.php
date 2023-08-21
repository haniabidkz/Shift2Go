<!DOCTYPE html>
<?php
$logo = asset(Storage::url('/'));
$logos=\App\Models\Utility::get_file('uploads/logo/');
$meta_image=\App\Models\Utility::get_file('uploads/meta_image/');
$company_favicon = Utility::getValByName('company_favicon');
$setting = \App\Models\Utility::colorset();
$SITE_RTL= isset($setting['SITE_RTL'])?$setting['SITE_RTL']:'off';
$color = 'theme-3';
if (!empty($setting['color'])) {
    $color = $setting['color'];
}

if (\Auth::user()->type == 'Super Admin') {
    $company_logo = Utility::get_superadmin_logo();
} else {
    $company_logo = Utility::get_company_logo();
}
$darklayout = Utility::getValByName('cust_darklayout');
?>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" dir="<?php echo e($SITE_RTL == 'on' ? 'rtl' : ''); ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>
        <?php echo e(Utility::getValByName('title_text') ? Utility::getValByName('title_text') : config('app.name', 'RotaGo SaaS')); ?>

        - <?php echo $__env->yieldContent('page-title'); ?></title>
    <link rel="icon"
        href="<?php echo e($logo . 'uploads/logo/' . (isset($company_favicon) && !empty($company_favicon) ? $company_favicon : 'favicon.png')); ?>"
        type="image" sizes="16x16">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="keywords" content="HTML, CSS, JavaScript">

    <meta name="url" content="<?php echo e(url('') . '/' . config('chatify.routes.prefix')); ?>"
        data-user="<?php echo e(Auth::user()->id); ?>">


    <meta name="title" content="Meta Tags — Preview, Edit and Generate">
    <meta name="description" content="With Meta Tags you can edit and experiment with your content then preview how your webpage will look on Google, Facebook, Twitter and more!">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo e(env('APP_URL')); ?>">
    <meta property="og:title" content="<?php echo e(!empty($setting['meta_keyword']) ? $setting['meta_keyword'] : ''); ?>">
    <meta property="og:description" content="<?php echo e(!empty($setting['meta_description']) ? $setting['meta_description'] : ''); ?>">
    <meta property="og:image" content="<?php echo e($meta_image.(isset($image) && !empty($image)? $image:'meta_image.png')); ?>">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?php echo e(env('APP_URL')); ?>">
    <meta property="twitter:title" content="<?php echo e(!empty($setting['meta_keyword']) ? $setting['meta_keyword'] : ''); ?>">
    <meta property="twitter:description" content="<?php echo e(!empty($setting['meta_description']) ? $setting['meta_description'] : ''); ?>">
    <meta property="twitter:image" content="<?php echo e($meta_image.(isset($image) && !empty($image)? $image:'meta_image.png')); ?>">

    
    <link rel='stylesheet' href='https://unpkg.com/nprogress@0.2.0/nprogress.css' />
    <link href="<?php echo e(asset('css/chatify/style.css')); ?>" rel="stylesheet" />

    
    <script src="<?php echo e(asset('js/chatify/autosize.js')); ?>"></script>

    
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/plugins/style.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/fonts/tabler-icons.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/fonts/feather.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/fonts/fontawesome.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/fonts/material.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/plugins/main.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/plugins/bootstrap-switch-button.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/plugins/datepicker-bs5.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/plugins/flatpickr.min.css')); ?>">

    <link rel="stylesheet" href="<?php echo e(asset('custom/libs/select2/dist/css/select2.min.css')); ?>">

    <link rel="stylesheet" href="<?php echo e(asset('custom/css/custom.css')); ?>">

    <link rel="stylesheet" href="<?php echo e(asset('assets/css/customizer.css')); ?>">
     <!-- fileupload-custom css -->
    <link rel="stylesheet" href="<?php echo e(asset('custom/libs/dropzone/dist/dropzone.css')); ?>">

    <?php if($SITE_RTL == 'on'): ?>
        <link rel="stylesheet" href="<?php echo e(asset('css/bootstrap-rtl.css')); ?>">
        <link rel="stylesheet" href="<?php echo e(asset('assets/css/style-rtl.css')); ?>">
    <?php endif; ?>

    <?php if($darklayout == 'on'): ?>
        <link rel="stylesheet" href="<?php echo e(asset('assets/css/style-dark.css')); ?>" id="main-style-link">
    <?php else: ?>
        <link rel="stylesheet" href="<?php echo e(asset('assets/css/style.css')); ?>" id="main-style-link">
    <?php endif; ?>

    <link rel="stylesheet" href="<?php echo e(asset('assets/theme/css/custom.css')); ?>">

    <style>
        [dir="rtl"] .dash-sidebar {
            left: auto !important;
        }

        @media (min-width: 1024px)
        {
            [dir="rtl"] .dash-header {
                left: 30px;
                right: 280px;
            }
        }

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


    <?php echo $__env->yieldContent('availabilitylink'); ?>

    <meta name="url" content="<?php echo e(url('') . '/' . config('chatify.path')); ?>" data-user="<?php echo e(Auth::user()->id); ?>">

    <script src='https://unpkg.com/nprogress@0.2.0/nprogress.js'></script>
</head>


<body class="<?php echo e($color); ?>">
    <input type="hidden" id="path_admin" value="<?php echo e(url('/')); ?>">
    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
    <!-- [ Pre-loader ] End -->

    <!-- [ Mobile header ] start -->
    <!-- [ Mobile header ] End -->

    <!-- [ navigation menu ] start -->
    <?php echo $__env->make('partision.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <!-- [ navigation menu ] end -->

    <!-- [ Header ] start -->
    <?php echo $__env->make('partision.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <!-- [ Header ] end -->

    <!-- [ Main Content ] start -->
    <?php echo $__env->yieldContent('content'); ?>
    <?php echo $__env->yieldContent('header-content'); ?>
    <!-- [ Main Content ] end -->

    <?php echo $__env->make('partision.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <?php echo $__env->yieldContent('model'); ?>
    <div class="modal fade" id="commonModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="signature"></p>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="commonModalOver" tabindex="-1" role="dialog" aria-labelledby="commonModalOver" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="commonModalOver"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                </div>
            </div>
        </div>
    </div>

    <?php echo $__env->make('partision.footerlink', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('messenger.layouts.footerLinks', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('partision.sidesetting', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <script>
        $('#commonModal').on('modal-body', function (e) {
          $("#signature").jSignature();
          $("#signature").resize();
        });

        $(document).on('click', '.event-primary', function(e) {
        // if (!$(this).hasClass('project')) {
            e.preventDefault();
            var event = $(this);
            var title = $(this).find('.fc-content .fc-title').html();
            var size = 'md';
            var url = $(this).attr('href');
            $("#commonModal .modal-title").html(title);
            $("#commonModal .modal-dialog").addClass('modal-' + size);
            $.ajax({
                url: url,
                success: function(data) {
                    $('#commonModal .modal-body').html(data);
                    $("#commonModal").modal('show');

                },
                error: function(data) {
                    data = data.responseJSON;
                    toastrs('Error', data.error, 'error')
                }
            });
            // }
        });


        // $(document).ready(function() {
        //     cust_theme_bg();
        //     // cust_darklayout();
        // });
        feather.replace();
        var pctoggle = document.querySelector("#pct-toggler");
        if (pctoggle) {
            pctoggle.addEventListener("click", function() {
                if (
                    !document.querySelector(".pct-customizer").classList.contains("active")
                ) {
                    document.querySelector(".pct-customizer").classList.add("active");
                } else {
                    document.querySelector(".pct-customizer").classList.remove("active");
                }
            });
        }

        var themescolors = document.querySelectorAll(".themes-color > a");
        for (var h = 0; h < themescolors.length; h++) {
            var c = themescolors[h];

            c.addEventListener("click", function(event) {
                var targetElement = event.target;
                if (targetElement.tagName == "SPAN") {
                    targetElement = targetElement.parentNode;
                }
                var temp = targetElement.getAttribute("data-value");
                removeClassByPrefix(document.querySelector("body"), "theme-");
                document.querySelector("body").classList.add(temp);
            });
        }

        var custthemebg = document.querySelector("#cust-theme-bg");
        custthemebg.addEventListener("click", function() {
            if (custthemebg.checked) {
                document.querySelector(".dash-sidebar").classList.add("transprent-bg");
                document
                    .querySelector(".dash-header:not(.dash-mob-header)")
                    .classList.add("transprent-bg");
            } else {
                document.querySelector(".dash-sidebar").classList.remove("transprent-bg");
                document
                    .querySelector(".dash-header:not(.dash-mob-header)")
                    .classList.remove("transprent-bg");
            }
        });

        var custdarklayout = document.querySelector("#cust-darklayout1");
        custdarklayout.addEventListener("click", function () {
            if (custdarklayout.checked) {
                document.querySelector("#main-style-link").setAttribute("href", "<?php echo e(asset('assets/css/style-dark.css')); ?>");
                // document.querySelector(".m-header > .b-brand > .logo-lg").setAttribute("src", "<?php echo e($logos.'/'.(isset($company_logo) && !empty($company_logo)?$company_logo:'logo-light.png')); ?>");
            } else {
                document.querySelector("#main-style-link").setAttribute("href", "<?php echo e(asset('assets/css/style.css')); ?>");
                // document.querySelector(".m-header > .b-brand > .logo-lg").setAttribute("src", "<?php echo e($logos.'/'.(isset($company_logos) && !empty($company_logos)?$company_logos:'logo-dark.png')); ?>");
            }
        });

        function removeClassByPrefix(node, prefix) {
            for (let i = 0; i < node.classList.length; i++) {
                let value = node.classList[i];
                if (value.startsWith(prefix)) {
                    node.classList.remove(value);
                }
            }
        }

        if ($('#useradd-sidenav').length > 0) {
            var scrollSpy = new bootstrap.ScrollSpy(document.body, {
                target: '#useradd-sidenav',
                offset: 300,
            });
        }
        $(function(){
            $(document).on("click",".show_confirm",function(){
                // var element = $(this);
                // var del_id = element.attr("id");
                // console.log(del_id);
                // var info = del_id;
            // $('.show_confirm').click(function(event) {
                var form = $(this).closest("form");
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                })
                swalWithBootstrapButtons.fire({
                    title: 'Are you sure?',
                    text: "This action can not be undone. Do you want to continue?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                })
            });
        });



         // function cust_theme_bg(params) {
        //     var custthemebg = document.querySelector("#cust-theme-bg");
        //     var val = "<?php echo e(Utility::getValByName('cust_theme_bg') == 'on' ? 'checked' : ''); ?>";
        //     if (val) {
        //         document.querySelector(".dash-sidebar").classList.add("transprent-bg");
        //         document
        //             .querySelector(".dash-header:not(.dash-mob-header)")
        //             .classList.add("transprent-bg");
        //     } else {
        //         document.querySelector(".dash-sidebar").classList.remove("transprent-bg");
        //         document
        //             .querySelector(".dash-header:not(.dash-mob-header)")
        //             .classList.remove("transprent-bg");
        //     }
        // }
    </script>

</body>

</html>
<?php /**PATH F:\Laragon\www\shift2go\resources\views/layouts/main.blade.php ENDPATH**/ ?>