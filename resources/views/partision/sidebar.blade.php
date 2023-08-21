@php
// $logos=asset(Storage::url('uploads/logo/'));
$logos=\App\Models\Utility::get_file('uploads/logo/');
$company_logo = \App\Models\Utility::getValByName('company_logo');
$company_logos = \App\Models\Utility::getValByName('company_dark_logo');
$company_small_logo = Utility::getValByName('company_small_logo');
$setting = \App\Models\Utility::colorset();
$SITE_RTL= isset($setting['SITE_RTL'])?$setting['SITE_RTL']:'off';
@endphp
@if (isset($setting['cust_theme_bg']) && $setting['cust_theme_bg'] == 'on')
    <nav class="dash-sidebar light-sidebar transprent-bg">
@else
    <nav class="dash-sidebar light-sidebar">
@endif
        <div class="navbar-wrapper">
            <div class="m-header main-logo d-flex justify-content-center">
                <a href="{{ url('dashboard') }}" class="b-brand">
                    <!-- ========   change your logo hear   ============ -->
                    @if (!empty($darklayout) && $darklayout == 'on')
                    <img src="{{$logos.'/'.(isset($company_logo) && !empty($company_logo)?$company_logo:'logo-light.png')}}"
                    class="logo logo-lg" alt="..."  style="height=30px !important; width: 135px !important;">
                    @else
                    <img src="{{$logos.(isset($company_logos) && !empty($company_logos)?$company_logos:'logo-dark.png')}}"
                    class="logo logo-lg" alt="..."  style="height=30px !important; width: 135px !important;">
                    @endif
                </a>
            </div>
            <div class="navbar-content">
                <ul class="dash-navbar">
                    <li class="dash-item
                        {{ Request::segment(1) == '' || Request::segment(1) == 'home' ||
                        Request::segment(1) == 'dashboard' || Request::segment(1) == 'day' ||
                        Request::segment(1) == 'user-view' ? 'active' : '' }}">
                        <a href="{{ url('dashboard') }}" class="dash-link">
                            <span class="dash-micon"> <i class="ti ti-home"></i> </span>
                            <span class="dash-mtext">{{ __('Dashboard') }}</span>
                        </a>
                    </li>
                    @if (Auth::user()->type == 'super admin')
                        <li class="dash-item">
                            <a href="{{ url('user') }}" class="dash-link">
                                <span class="dash-micon"> <i class="ti ti-users"></i> </span>
                                <span class="dash-mtext">{{ __('User') }}</span>
                            </a>
                        </li>
                        <li class="dash-item">
                            <a href="{{ url('plan') }}" class="dash-link">
                                <span class="dash-micon"> <i class="ti ti-trophy"></i> </span>
                                <span class="dash-mtext">{{ __('Plan') }}</span>
                            </a>
                        </li>
                        <li class="dash-item">
                            <a href="{{ url('order') }}" class="dash-link">
                                <span class="dash-micon"> <i class="ti ti-report-money"></i> </span>
                                <span class="dash-mtext">{{ __('Order') }}</span>
                            </a>
                        </li>
                        <li class="dash-item">
                            <a href="{{ url('plan_request') }}" class="dash-link">
                                <span class="dash-micon"> <i class="ti ti-git-pull-request"></i> </span>
                                <span class="dash-mtext">{{ __('Plan Request') }}</span>
                            </a>
                        </li>
                        <li class="dash-item {{ Request::segment(1) == 'coupon' ? 'active' : '' }}">
                            <a href="{{ url('coupon') }}" class="dash-link">
                                <span class="dash-micon"> <i class="ti ti-gift"></i> </span>
                                <span class="dash-mtext">{{ __('Coupon') }}</span>
                            </a>
                        </li>
                        <li class="dash-item">
                            <a href="{{ url('setting') }}" class="dash-link">
                                <span class="dash-micon"> <i class="ti ti-settings"></i> </span>
                                <span class="dash-mtext">{{ __('Settings') }}</span>
                            </a>
                        </li>
                    @endif

                    @if (Auth::user()->type != 'super admin')
                        <!-- Rotas  -->
                        <li class="dash-item">
                            <a href="{{ url('rotas') }}" class="dash-link">
                                <span class="dash-micon"> <i class="ti ti-calendar-event"></i> </span>
                                <span class="dash-mtext">{{ __('Roster') }}</span>
                            </a>
                        </li>

                        @if (Auth::user()->acount_type == 1 || Auth::user()->getAddEmployeePermission() == 1)
                            <li
                                class="dash-item dash-hasmenu {{ Request::segment(1) == 'past-employees' ||
                                Request::segment(1) == 'profile' ? 'active dash-trigger' : '' }}">
                                <a href="#!" class="dash-link">
                                    <span class="dash-micon"><i class="ti ti-vocabulary"></i></span>
                                    <span class="dash-mtext">{{ __('Company') }}</span>
                                    <span class="dash-arrow"><i data-feather="chevron-right"></i></span>
                                </a>
                                <ul class="dash-submenu">
                                    @if (Auth::user()->acount_type == 1 || Auth::user()->getAddEmployeePermission() == 1)
                                        <li
                                            class="dash-item {{ Request::segment(1) == 'past-employees' || Request::segment(1) == 'profile' ? 'active' : '' }}">
                                            <a href="{{ url('employees') }}"
                                                class="dash-link">{{ __('Employee') }}</a>
                                        </li>
                                    @endif
                                    @if (Auth::user()->acount_type == 1 || Auth::user()->getAddRolePermission() == 1)
                                        <li class="dash-item">
                                            <a href="{{ url('locations') }}"
                                                class="dash-link">{{ __('Location') }}</a>
                                        </li>
                                    @endif
                                    @if (Auth::user()->acount_type == 1 || Auth::user()->getAddRolePermission() == 1)
                                        <li class="dash-item">
                                            <a href="{{ url('roles') }}" class="dash-link">{{ __('Role') }}</a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @endif

                        {{-- @if (Auth::user()->acount_type == 1 || Auth::user()->getAddEmployeePermission() == 1)
                            <li class="dash-item dash-hasmenu  {{ Request::segment(1) == 'setsalary' ? 'dash-trigger active' : '' }}">
                                <a href="#!" class="dash-link">
                                    <span class="dash-micon">
                                        <i class="ti ti-receipt">
                                        </i>
                                    </span>
                                    <span class="dash-mtext">
                                        {{ __('Payroll') }}
                                    </span>
                                    <span class="dash-arrow"><i data-feather="chevron-right">
                                        </i>
                                    </span>
                                </a>
                                <ul class="dash-submenu ">
                                    <li class="dash-item">
                                        <a class="dash-link" href="{{ route('payslip.index') }}">{{ __('Payslip') }}</a>
                                    </li>

                                </ul>
                            </li>
                        @endif --}}
                        <li class="dash-item">
                            <a href="{{ route('payslip.index') }}" class="dash-link">
                                <span class="dash-micon"> <i class="ti ti-receipt"></i> </span>
                                <span class="dash-mtext">{{ __('Payslip') }}</span>
                            </a>
                        </li>
                        <!-- Leave  -->
                        <li
                            class="dash-item {{ Request::segment(1) == 'holidays' || Request::segment(1) == 'embargoes' || Request::segment(1) == 'leave-request' ? 'active' : '' }}">
                            <a href="{{ url('holidays') }}" class="dash-link">
                                <span class="dash-micon"> <i class="ti ti-user-off"></i> </span>
                                <span class="dash-mtext">{{ __('Leave') }}</span>
                            </a>
                        </li>
                        <!-- Availability  -->
                        @if (Auth::user()->getViewAvailabilities() == 1)
                            <li class="dash-item">
                                <a href="{{ url('availabilities') }}" class="dash-link">
                                    <span class="dash-micon"> <i class="ti ti-antenna-bars-5"></i> </span>
                                    <span class="dash-mtext">{{ __('Availability') }}</span>
                                </a>
                            </li>
                        @endif
                            <li class="dash-item">
                                <a href="{{ url('timesheet') }}" class="dash-link">
                                    <span class="dash-micon"> <i class="ti ti-align-left"></i> </span>
                                    <span class="dash-mtext">{{ __('Timesheet') }}</span>
                                </a>
                            </li>
                            <li class="dash-item dash-hasmenu">
                                <a href="{{ url('attendance   ') }}" class="dash-link">
                                    <span class="dash-micon"><i class="ti ti-clock"></i></span>
                                    <span class="dash-mtext">{{ __('Attendance') }}</span>
                                    <!-- <span class="dash-arrow"><i data-feather="chevron-right"></i></span> -->
                                </a>
                                <ul class="dash-submenu">
                                        <!-- <li
                                            class="dash-item">
                                            <a href="{{ url('attendance   ') }}"
                                                class="dash-link">{{ __('Marked Attendance') }}</a>
                                        </li> -->
                                    <!-- @if (Auth::user()->acount_type == 1)
                                        <li class="dash-item">
                                            <a href="{{ route('attendanceemployee.bulkattendance') }}"
                                                class="dash-link">{{ __('Bulk Attendance') }}</a>
                                        </li>
                                    @endif -->
                                </ul>
                            </li>
                        @endif
                        @if (Auth::user()->type != 'super admin')
                        <!-- Settings  -->
                        <li class="dash-item {{ Request::segment(1) == 'contract' ? 'active' : '' }}">
                            <a href="{{ url('contract') }}" class="dash-link">
                                <span class="dash-micon"> <i class="ti ti-device-floppy"></i> </span>
                                <span class="dash-mtext">{{ __('Contract') }}</span>
                            </a>
                        </li>
                    @endif
                    @if (Auth::user()->type == 'company')
                        <!-- Settings  -->
                        <li class="dash-item">
                            <a href="{{ url('contract_type') }}" class="dash-link">
                                <span class="dash-micon"> <i class="ti ti-clipboard-check"></i> </span>
                                <span class="dash-mtext">{{ __('Contract Type') }}</span>
                            </a>
                        </li>
                    @endif
                    @if (Auth::user()->type == 'company')
                        <!-- Plan  -->
                        <li class="dash-item {{ Request::segment(1) == 'stripe' ? 'active' : '' }}">
                            <a href="{{ url('plan') }}" class="dash-link">
                                <span class="dash-micon"> <i class="ti ti-chart-pie"></i> </span>
                                <span class="dash-mtext">{{ __('Plan') }}</span>
                            </a>
                        </li>
                        <li class="dash-item">
                            <a href="{{ url('order') }}" class="dash-link">
                                <span class="dash-micon"> <i class="ti ti-report-money"></i> </span>
                                <span class="dash-mtext">{{ __('Order') }}</span>
                            </a>
                        </li>
                    @endif
                    <!-- Chat  -->
                    @if (Auth::user()->type != 'super admin')
                    <li class="dash-item">
                        <a href="{{ url('chats') }}" class="dash-link">
                            <span class="dash-micon"> <i class="ti ti-message"></i> </span>
                            <span class="dash-mtext">{{ __('Messenger') }}</span>
                        </a>
                    </li>
                    @endif

                    @if(Auth::user()->type != 'super admin')
                    <!-- Zoom  -->
                    <li class="dash-item {{ Request::segment(1) == 'zoommeeting' ? 'active' : '' }}">
                        <a href="{{ url('zoom-meeting') }}" class="dash-link">
                            <span class="dash-micon"> <i class="ti ti-award"></i> </span>
                            <span class="dash-mtext">{{ __('Zoom Meeting') }}</span>
                        </a>
                    </li>
                    @endif

                    @if (Auth::user()->acount_type == 1 || Auth::user()->getviewRepotsPermission() == 1)
                        <!-- Reports  -->
                        <li class="dash-item">
                            <a href="{{ url('reports') }}" class="dash-link">
                                <span class="dash-micon"> <i class="ti ti-notebook"></i> </span>
                                <span class="dash-mtext">{{ __('Reports') }}</span>
                            </a>
                        </li>
                    @endif
                    <!-- notification  -->
                    @if(Auth::user()->type == 'company')
                    <li class="dash-item">
                        <a href="{{ url('notification-templates') }}" class="dash-link">
                            <span class="dash-micon"> <i class="ti ti-notification"></i> </span>
                            <span class="dash-mtext">{{ __('Notification Template') }}</span>
                        </a>
                    </li>
                    @endif
                    @if (Auth::user()->type == 'company')
                        <!-- Settings  -->
                        <li class="dash-item">
                            <a href="{{ url('setting') }}" class="dash-link">
                                <span class="dash-micon"> <i class="ti ti-settings"></i> </span>
                                <span class="dash-mtext">{{ __('Settings') }}</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
