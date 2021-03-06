<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<title>Booking Engine</title>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">

		<!--begin::Web font -->
		<script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
		<script>
			WebFont.load({
				google: {
					"families": ["Poppins:300,400,500,600,700", "Roboto:300,400,500,600,700"]
				},
				active: function() {
					sessionStorage.fonts = true;
				}
			});
		</script>

		<!--end::Web font -->

		<!--begin::Page Vendors Styles -->

		<link rel="stylesheet" href="https://unpkg.com/vue-multiselect@2.1.0/dist/vue-multiselect.min.css">

		<!--end::Page Vendors Styles -->

		<!--begin::Base Styles -->
		<link href="{{ asset('assets/vendors/base/vendors.bundle.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('assets/demo/demo5/base/style.bundle.css') }}" rel="stylesheet" type="text/css" />

		<!--end::Base Styles -->
		<link rel="shortcut icon" href="/img/vlalogo.ico" />
    </head>
    <body class="m-page--wide  m-footer--push m-aside--offcanvas-default">
        <div class="m-grid m-grid--hor m-grid--root m-page">
            <header id="m_header" class="m-grid__item		m-header " m-minimize="minimize" m-minimize-offset="200" m-minimize-mobile-offset="200">
                <div class="m-header__bottom">
                    <div class="m-container m-container--responsive m-container--xxl m-container--full-height m-page__container">
                        <div class="m-stack m-stack--ver m-stack--desktop">
                            <div class="m-stack__item m-stack__item--middle m-stack__item--fluid">
                                <div id="m_header_menu" class="m-header-menu m-aside-header-menu-mobile m-aside-header-menu-mobile--offcanvas  m-header-menu--skin-dark m-header-menu--submenu-skin-light m-aside-header-menu-mobile--skin-light m-aside-header-menu-mobile--submenu-skin-light ">    
                                    <ul class="m-menu__nav  m-menu__nav--submenu-arrow ">
                                        @if (Route::has('login'))
                                        @auth
                                            <li class="m-menu__item ">
                                                <a class="m-menu__link" href="/office">
                                                    <span class="m-menu__item-here"></span>
                                                    <span class="m-menu__link-text">Booking Engine</span>
                                                </a>
                                            </li>
                                            <li class="m-menu__item ">
                                                <a class="m-menu__link" href="{{ route('logout') }}">
                                                    <span class="m-menu__item-here"></span>
                                                    <span class="m-menu__link-text">{{ __('Logout') }}</span>
                                                </a>
                                            </li>
                                            @else
                                            <li class="m-menu__item ">
                                                <a class="m-menu__link" href="/">
                                                    <span class="m-menu__item-here"></span>
                                                    <span class="m-menu__link-text">Booking Engine</span>
                                                </a>
                                            </li>
                                            <li class="m-menu__item ">
                                                <a class="m-menu__link" href="{{ route('login') }}">
                                                    <span class="m-menu__item-here"></span>
                                                    <span class="m-menu__link-text">Login</span>
                                                </a>
                                            </li>
                                            @endauth
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
			<div class="m-grid__item m-grid__item--fluid  m-grid m-grid--ver-desktop m-grid--desktop 	m-container m-container--responsive m-container--xxl m-page__container m-body">
				<div class="m-grid__item m-grid__item--fluid m-wrapper">

					<div class="m-content">

					</div>
				</div>
			</div>
        </div>
		<!--begin::Select users service provider if do not belongs to one -->
		@include('service_providers.modal.select')
        <!--end::Select users service provider if do not belongs to one -->
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <script src="/js/new_user.js"></script>
    </body>
    @if( Auth::check() && empty(getUserServiceProviderId()) &&  getUserRoleName() !== 'Super Admin')
    <script>
        $('#set_sp').modal('show');
    </script>
    @endif
</html>