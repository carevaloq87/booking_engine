<!DOCTYPE html>
<html lang="en">

	<!-- begin::Head -->
	<head>
		<meta charset="utf-8" />
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

	<!-- end::Head -->

	<!-- begin::Body -->
	<body class="m-page--wide  m-footer--push m-aside--offcanvas-default">

		<!-- begin:: Page -->
		<div class="m-grid m-grid--hor m-grid--root m-page">

        	@include ('layouts.booking_engine.partials.header')

			<!-- begin::Body -->
			<div class="m-grid__item m-grid__item--fluid  m-grid m-grid--ver-desktop m-grid--desktop 	m-container m-container--responsive m-container--xxl m-page__container m-body">
				<div class="m-grid__item m-grid__item--fluid m-wrapper">

					@include ('layouts.booking_engine.partials.sub_header_alerts')
					@include ('layouts.booking_engine.partials.sub_header')

					<div class="m-content">
        				@yield('content')
					</div>
				</div>
			</div>

			<!-- end::Body -->

        	@include ('layouts.booking_engine.partials.footer')

		</div>

		<!-- end:: Page -->

		<!-- begin::Scroll Top -->
		<div id="m_scroll_top" class="m-scroll-top">
			<i class="la la-arrow-up"></i>
		</div>

		<!-- end::Scroll Top -->

		<!--begin::Base Scripts -->

		<!--end::Base Scripts -->

		<!--begin::Page Vendors Scripts -->
		<!-- script src="/assets/vendors/custom/fullcalendar/fullcalendar.bundle.js" type="text/javascript"></script -->

		<!--end::Page Vendors Scripts -->

		<!--begin::Page Snippets -->

		<!--end::Page Snippets -->

		<!--begin::Select users service provider if do not belongs to one -->
		@include('service_providers.modal.select')
		<!--end::Select users service provider if do not belongs to one -->

		@include('layouts.loader')


		<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

		<script src="/assets/demo/demo5/base/scripts.bundle.js" type="text/javascript"></script>
		<!-- Latest compiled and minified JavaScript -->
		<script src="/js/modal-hierarchy.js"></script>
		<script src="/js/new_user.js"></script>

		@yield('scripts')
		@yield('extra-scripts')
	    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

	</body>

    @if( Auth::check() && empty(getUserServiceProviderId()) &&  getUserRoleName() !== 'Super Admin')
    <script>
        $('#set_sp').modal('show');
    </script>
    @endif
	<!-- end::Body -->
</html>