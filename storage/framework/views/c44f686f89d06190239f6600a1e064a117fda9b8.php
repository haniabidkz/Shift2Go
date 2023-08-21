<?php
// $logos=asset(Storage::url('uploads/logo/'));
$logos=\App\Models\Utility::get_file('uploads/logo/');
$company_logo = \App\Models\Utility::getValByName('company_logo');
$company_logos = \App\Models\Utility::getValByName('company_dark_logo');
$company_small_logo = Utility::getValByName('company_small_logo');
$setting = \App\Models\Utility::colorset();
$SITE_RTL= isset($setting['SITE_RTL'])?$setting['SITE_RTL']:'off';
?>
<?php if(isset($setting['cust_theme_bg']) && $setting['cust_theme_bg'] == 'on'): ?>
    <nav class="dash-sidebar light-sidebar transprent-bg">
<?php else: ?>
    <nav class="dash-sidebar light-sidebar">
<?php endif; ?>
        <div class="navbar-wrapper">
            <div class="m-header main-logo d-flex justify-content-center">
                <a href="<?php echo e(url('dashboard')); ?>" class="b-brand">
                    <!-- ========   change your logo hear   ============ -->
                    <?php if(!empty($darklayout) && $darklayout == 'on'): ?>
                    <img src="<?php echo e($logos.'/'.(isset($company_logo) && !empty($company_logo)?$company_logo:'logo-light.png')); ?>"
                    class="logo logo-lg" alt="..."  style="height=30px !important; width: 135px !important;">
                    <?php else: ?>
                    <img src="<?php echo e($logos.(isset($company_logos) && !empty($company_logos)?$company_logos:'logo-dark.png')); ?>"
                    class="logo logo-lg" alt="..."  style="height=30px !important; width: 135px !important;">
                    <?php endif; ?>
                </a>
            </div>
            <div class="navbar-content">
                <ul class="dash-navbar">
                    <li class="dash-item
                        <?php echo e(Request::segment(1) == '' || Request::segment(1) == 'home' ||
                        Request::segment(1) == 'dashboard' || Request::segment(1) == 'day' ||
                        Request::segment(1) == 'user-view' ? 'active' : ''); ?>">
                        <a href="<?php echo e(url('dashboard')); ?>" class="dash-link">
                            <span class="dash-micon"> <i class="ti ti-home"></i> </span>
                            <span class="dash-mtext"><?php echo e(__('Dashboard')); ?></span>
                        </a>
                    </li>
                    <?php if(Auth::user()->type == 'super admin'): ?>
                        <li class="dash-item">
                            <a href="<?php echo e(url('user')); ?>" class="dash-link">
                                <span class="dash-micon"> <i class="ti ti-users"></i> </span>
                                <span class="dash-mtext"><?php echo e(__('User')); ?></span>
                            </a>
                        </li>
                        <li class="dash-item">
                            <a href="<?php echo e(url('plan')); ?>" class="dash-link">
                                <span class="dash-micon"> <i class="ti ti-trophy"></i> </span>
                                <span class="dash-mtext"><?php echo e(__('Plan')); ?></span>
                            </a>
                        </li>
                        <li class="dash-item">
                            <a href="<?php echo e(url('order')); ?>" class="dash-link">
                                <span class="dash-micon"> <i class="ti ti-report-money"></i> </span>
                                <span class="dash-mtext"><?php echo e(__('Order')); ?></span>
                            </a>
                        </li>
                        <li class="dash-item">
                            <a href="<?php echo e(url('plan_request')); ?>" class="dash-link">
                                <span class="dash-micon"> <i class="ti ti-git-pull-request"></i> </span>
                                <span class="dash-mtext"><?php echo e(__('Plan Request')); ?></span>
                            </a>
                        </li>
                        <li class="dash-item <?php echo e(Request::segment(1) == 'coupon' ? 'active' : ''); ?>">
                            <a href="<?php echo e(url('coupon')); ?>" class="dash-link">
                                <span class="dash-micon"> <i class="ti ti-gift"></i> </span>
                                <span class="dash-mtext"><?php echo e(__('Coupon')); ?></span>
                            </a>
                        </li>
                        <li class="dash-item">
                            <a href="<?php echo e(url('setting')); ?>" class="dash-link">
                                <span class="dash-micon"> <i class="ti ti-settings"></i> </span>
                                <span class="dash-mtext"><?php echo e(__('Settings')); ?></span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if(Auth::user()->type != 'super admin'): ?>
                        <!-- Rotas  -->
                        <li class="dash-item">
                            <a href="<?php echo e(url('rotas')); ?>" class="dash-link">
                                <span class="dash-micon"> <i class="ti ti-calendar-event"></i> </span>
                                <span class="dash-mtext"><?php echo e(__('Roster')); ?></span>
                            </a>
                        </li>

                        <?php if(Auth::user()->acount_type == 1 || Auth::user()->getAddEmployeePermission() == 1): ?>
                            <li
                                class="dash-item dash-hasmenu <?php echo e(Request::segment(1) == 'past-employees' ||
                                Request::segment(1) == 'profile' ? 'active dash-trigger' : ''); ?>">
                                <a href="#!" class="dash-link">
                                    <span class="dash-micon"><i class="ti ti-vocabulary"></i></span>
                                    <span class="dash-mtext"><?php echo e(__('Company')); ?></span>
                                    <span class="dash-arrow"><i data-feather="chevron-right"></i></span>
                                </a>
                                <ul class="dash-submenu">
                                    <?php if(Auth::user()->acount_type == 1 || Auth::user()->getAddEmployeePermission() == 1): ?>
                                        <li
                                            class="dash-item <?php echo e(Request::segment(1) == 'past-employees' || Request::segment(1) == 'profile' ? 'active' : ''); ?>">
                                            <a href="<?php echo e(url('employees')); ?>"
                                                class="dash-link"><?php echo e(__('Employee')); ?></a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if(Auth::user()->acount_type == 1 || Auth::user()->getAddRolePermission() == 1): ?>
                                        <li class="dash-item">
                                            <a href="<?php echo e(url('locations')); ?>"
                                                class="dash-link"><?php echo e(__('Location')); ?></a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if(Auth::user()->acount_type == 1 || Auth::user()->getAddRolePermission() == 1): ?>
                                        <li class="dash-item">
                                            <a href="<?php echo e(url('roles')); ?>" class="dash-link"><?php echo e(__('Role')); ?></a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </li>
                        <?php endif; ?>

                        
                        <li class="dash-item">
                            <a href="<?php echo e(route('payslip.index')); ?>" class="dash-link">
                                <span class="dash-micon"> <i class="ti ti-receipt"></i> </span>
                                <span class="dash-mtext"><?php echo e(__('Payslip')); ?></span>
                            </a>
                        </li>
                        <!-- Leave  -->
                        <li
                            class="dash-item <?php echo e(Request::segment(1) == 'holidays' || Request::segment(1) == 'embargoes' || Request::segment(1) == 'leave-request' ? 'active' : ''); ?>">
                            <a href="<?php echo e(url('holidays')); ?>" class="dash-link">
                                <span class="dash-micon"> <i class="ti ti-user-off"></i> </span>
                                <span class="dash-mtext"><?php echo e(__('Leave')); ?></span>
                            </a>
                        </li>
                        <!-- Availability  -->
                        <?php if(Auth::user()->getViewAvailabilities() == 1): ?>
                            <li class="dash-item">
                                <a href="<?php echo e(url('availabilities')); ?>" class="dash-link">
                                    <span class="dash-micon"> <i class="ti ti-antenna-bars-5"></i> </span>
                                    <span class="dash-mtext"><?php echo e(__('Availability')); ?></span>
                                </a>
                            </li>
                        <?php endif; ?>
                            <li class="dash-item">
                                <a href="<?php echo e(url('timesheet')); ?>" class="dash-link">
                                    <span class="dash-micon"> <i class="ti ti-align-left"></i> </span>
                                    <span class="dash-mtext"><?php echo e(__('Timesheet')); ?></span>
                                </a>
                            </li>
                            <li class="dash-item dash-hasmenu">
                                <a href="<?php echo e(url('attendance   ')); ?>" class="dash-link">
                                    <span class="dash-micon"><i class="ti ti-clock"></i></span>
                                    <span class="dash-mtext"><?php echo e(__('Attendance')); ?></span>
                                    <!-- <span class="dash-arrow"><i data-feather="chevron-right"></i></span> -->
                                </a>
                                <ul class="dash-submenu">
                                        <!-- <li
                                            class="dash-item">
                                            <a href="<?php echo e(url('attendance   ')); ?>"
                                                class="dash-link"><?php echo e(__('Marked Attendance')); ?></a>
                                        </li> -->
                                    <!-- <?php if(Auth::user()->acount_type == 1): ?>
                                        <li class="dash-item">
                                            <a href="<?php echo e(route('attendanceemployee.bulkattendance')); ?>"
                                                class="dash-link"><?php echo e(__('Bulk Attendance')); ?></a>
                                        </li>
                                    <?php endif; ?> -->
                                </ul>
                            </li>
                        <?php endif; ?>
                        <?php if(Auth::user()->type != 'super admin'): ?>
                        <!-- Settings  -->
                        <li class="dash-item <?php echo e(Request::segment(1) == 'contract' ? 'active' : ''); ?>">
                            <a href="<?php echo e(url('contract')); ?>" class="dash-link">
                                <span class="dash-micon"> <i class="ti ti-device-floppy"></i> </span>
                                <span class="dash-mtext"><?php echo e(__('Contract')); ?></span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if(Auth::user()->type == 'company'): ?>
                        <!-- Settings  -->
                        <li class="dash-item">
                            <a href="<?php echo e(url('contract_type')); ?>" class="dash-link">
                                <span class="dash-micon"> <i class="ti ti-clipboard-check"></i> </span>
                                <span class="dash-mtext"><?php echo e(__('Contract Type')); ?></span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if(Auth::user()->type == 'company'): ?>
                        <!-- Plan  -->
                        <li class="dash-item <?php echo e(Request::segment(1) == 'stripe' ? 'active' : ''); ?>">
                            <a href="<?php echo e(url('plan')); ?>" class="dash-link">
                                <span class="dash-micon"> <i class="ti ti-chart-pie"></i> </span>
                                <span class="dash-mtext"><?php echo e(__('Plan')); ?></span>
                            </a>
                        </li>
                        <li class="dash-item">
                            <a href="<?php echo e(url('order')); ?>" class="dash-link">
                                <span class="dash-micon"> <i class="ti ti-report-money"></i> </span>
                                <span class="dash-mtext"><?php echo e(__('Order')); ?></span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <!-- Chat  -->
                    <?php if(Auth::user()->type != 'super admin'): ?>
                    <li class="dash-item">
                        <a href="<?php echo e(url('chats')); ?>" class="dash-link">
                            <span class="dash-micon"> <i class="ti ti-message"></i> </span>
                            <span class="dash-mtext"><?php echo e(__('Messenger')); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>

                    <?php if(Auth::user()->type != 'super admin'): ?>
                    <!-- Zoom  -->
                    <li class="dash-item <?php echo e(Request::segment(1) == 'zoommeeting' ? 'active' : ''); ?>">
                        <a href="<?php echo e(url('zoom-meeting')); ?>" class="dash-link">
                            <span class="dash-micon"> <i class="ti ti-award"></i> </span>
                            <span class="dash-mtext"><?php echo e(__('Zoom Meeting')); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>

                    <?php if(Auth::user()->acount_type == 1 || Auth::user()->getviewRepotsPermission() == 1): ?>
                        <!-- Reports  -->
                        <li class="dash-item">
                            <a href="<?php echo e(url('reports')); ?>" class="dash-link">
                                <span class="dash-micon"> <i class="ti ti-notebook"></i> </span>
                                <span class="dash-mtext"><?php echo e(__('Reports')); ?></span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <!-- notification  -->
                    <?php if(Auth::user()->type == 'company'): ?>
                    <li class="dash-item">
                        <a href="<?php echo e(url('notification-templates')); ?>" class="dash-link">
                            <span class="dash-micon"> <i class="ti ti-notification"></i> </span>
                            <span class="dash-mtext"><?php echo e(__('Notification Template')); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if(Auth::user()->type == 'company'): ?>
                        <!-- Settings  -->
                        <li class="dash-item">
                            <a href="<?php echo e(url('setting')); ?>" class="dash-link">
                                <span class="dash-micon"> <i class="ti ti-settings"></i> </span>
                                <span class="dash-mtext"><?php echo e(__('Settings')); ?></span>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
<?php /**PATH F:\Laragon\www\shift2go\resources\views/partision/sidebar.blade.php ENDPATH**/ ?>