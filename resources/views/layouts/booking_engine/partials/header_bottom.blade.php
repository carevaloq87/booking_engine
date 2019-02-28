    <div class="m-header__bottom">
        <div class="m-container m-container--responsive m-container--xxl m-container--full-height m-page__container">
            <div class="m-stack m-stack--ver m-stack--desktop">

                <!-- begin::Horizontal Menu -->
                <div class="m-stack__item m-stack__item--middle m-stack__item--fluid">
                    <button class="m-aside-header-menu-mobile-close  m-aside-header-menu-mobile-close--skin-light " id="m_aside_header_menu_mobile_close_btn">
                        <i class="la la-close"></i>
                    </button>
                    <div id="m_header_menu" class="m-header-menu m-aside-header-menu-mobile m-aside-header-menu-mobile--offcanvas  m-header-menu--skin-dark m-header-menu--submenu-skin-light m-aside-header-menu-mobile--skin-light m-aside-header-menu-mobile--submenu-skin-light ">
                        <ul class="m-menu__nav  m-menu__nav--submenu-arrow ">
                            <li class="m-menu__item  " aria-haspopup="true">
                                <a href="#" class="m-menu__link ">
                                    <span class="m-menu__item-here"></span>
                                    <span class="m-menu__link-text">Booking Engine</span>
                                </a>
                            </li>
                            @guest
                                <li class="m-menu__item {{ Request::is('login','login/*') ? ' m-menu__item--active' : null }}">
                                    <a class="m-menu__link" href="{{ route('login') }}">
                                        <span class="m-menu__item-here"></span>
                                        <span class="m-menu__link-text">{{ __('Login') }}</span>
                                    </a>
                                </li>
                                <li class="m-menu__item {{ Request::is('register','register/*') ? ' m-menu__item--active' : null }}">
                                    <a class="m-menu__link" href="{{ route('register') }}">
                                        <span class="m-menu__item-here"></span>
                                        <span class="m-menu__link-text">{{ __('Register') }}</span>
                                    </a>
                                </li>
                            @else
                                <li class="m-menu__item ">
                                    <a class="m-menu__link" href="{{ env('ORBIT_URL') }}">
                                        <span class="m-menu__item-here"></span>
                                        <span class="m-menu__link-text">Return to dashboard</span>
                                    </a>
                                </li>
                                <li class="m-menu__item {{ Request::is('services','services/*') ? 'm-menu__item--active' : null }}">
                                    <a class="m-menu__link " href="{{ route('services.service.index') }}">
                                        <span class="m-menu__item-here"></span>
                                        <span class="m-menu__link-text">Services</span>
                                    </a>
                                </li>
                                <li class="m-menu__item {{ Request::is('resources','resources/*') ? 'm-menu__item--active' : null }}">
                                    <a class="m-menu__link " href="{{ route('resources.resource.index') }}">
                                        <span class="m-menu__item-here"></span>
                                        <span class="m-menu__link-text">Resources</span>
                                    </a>
                                </li>

                                @if (Auth::user()->isAdmin())
                                    <li class="m-menu__item {{ Request::is('bookings','bookings/*') ? 'm-menu__item--active' : null }}">
                                        <a class="m-menu__link " href="{{ route('bookings.booking.index') }}">
                                            <span class="m-menu__item-here"></span>
                                            <span class="m-menu__link-text">New Booking</span>
                                        </a>
                                    </li>
                                    <li class="m-menu__item {{ Request::is('users','users/*') ? 'm-menu__item--active' : null }}">
                                        <a class="m-menu__link " href="{{ route('users.index') }}">
                                            <span class="m-menu__item-here"></span>
                                            <span class="m-menu__link-text">Users</span>
                                        </a>
                                    </li>
                                    <li class="m-menu__item {{ Request::is('roles','roles/*') ? 'm-menu__item--active' : null }}">
                                        <a class="m-menu__link " href="{{ route('roles.index') }}">
                                            <span class="m-menu__item-here"></span>
                                            <span class="m-menu__link-text">Role</span>
                                        </a>
                                    </li>
                                    <li class="m-menu__item {{ Request::is('service_providers','service_providers/*') ? 'm-menu__item--active' : null }}">
                                        <a class="m-menu__link " href="{{ route('service_providers.service_provider.index') }}">
                                            <span class="m-menu__item-here"></span>
                                            <span class="m-menu__link-text">Service Providers</span>
                                        </a>
                                    </li>
                                    <li class="m-menu__item {{ Request::is('holidays','holidays/*') ? 'm-menu__item--active' : null }}">
                                        <a class="m-menu__link " href="{{ route('holidays.holiday.index') }}">
                                            <span class="m-menu__item-here"></span>
                                            <span class="m-menu__link-text">Holidays</span>
                                        </a>
                                    </li>
                                @endif
                                <li class="m-menu__item">
                                    <a href="#" class="m-menu__link ">
                                        <span class="m-menu__item-here"></span>
                                        <span class="m-menu__link-text">{{ Auth::user()->name }}</span>
                                    </a>
                                </li>
                            @endguest
                        </ul>

                    </div>
                </div>

                <!-- end::Horizontal Menu -->
            </div>
        </div>
    </div>