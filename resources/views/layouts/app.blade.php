<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Booking Engine') }}</title>

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/vue-multiselect@2.1.0/dist/vue-multiselect.min.css">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <style>
        body {
            padding-top: 65px;
            padding-bottom: 20px;
        }

        /* Set padding to keep content from hitting the edges */
        .body-content {
            padding-left: 15px;
            padding-right: 15px;
        }

        /* Override the default bootstrap behavior where horizontal description lists
        will truncate terms that are too long to fit in the left column.
        Also, add a 8pm to the bottom margin
        */
        .dl-horizontal dt {
            white-space: normal;
            margin-bottom: 8px;
        }

        /* Set width on the form input elements since they're 100% wide by default */
        input,
        select,
        textarea,
        .uploaded-file-group,
        .input-width-input {
            max-width: 380px;
        }

        .input-delete-container {
            width: 46px !important;
        }

        /* Vertically align the table cells inside body-panel */
        .panel-body .table > tr > td
        {
            vertical-align: middle;
        }

        .panel-body-with-table
        {
            padding: 0;
        }

        .mt-5 {
            margin-top: 5px !important;
        }

        .mb-5 {
            margin-bottom: 5px !important;
        }
    </style>

</head>

<body>

    <nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar">A</span>
            <span class="icon-bar">B</span>
            <span class="icon-bar">C</span>
        </button>
        <a href="#" class="navbar-brand">{{ config('app.name', 'booking-engine') }}</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
            <!-- Authentication Links -->
            <ul class="nav navbar-nav">
            @guest
                <li class="{{ Request::is('login','login/*') ? 'active' : null }}"><a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a></li>
                <li class="{{ Request::is('register','register/*') ? 'active' : null }}"><a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a></li>
            @else

                <li><a class="nav-link" href="{{ env('ORBIT_URL') }}">Return to dashboard</a></li>
                <li class="{{ Request::is('services','services/*') ? 'active' : null }}"><a class="nav-link" href="{{ route('services.service.index') }}">Services</a></li>
                <li class="{{ Request::is('resources','resources/*') ? 'active' : null }}"><a class="nav-link" href="{{ route('resources.resource.index') }}">Resources</a></li>
                <li class="{{ Request::is('bookings','bookings/*') ? 'active' : null }}"><a class="nav-link" href="{{ route('bookings.booking.index') }}">New Booking</a></li>

                @if (Auth::user()->isAdmin())
                    <li class="{{ Request::is('users','users/*') ? 'active' : null }}"><a class="nav-link" href="{{ route('users.index') }}">Users</a></li>
                    <li class="{{ Request::is('roles','roles/*') ? 'active' : null }}"><a class="nav-link" href="{{ route('roles.index') }}">Role</a></li>
                    <li class="{{ Request::is('service_providers','service_providers/*') ? 'active' : null }}"><a class="nav-link" href="{{ route('service_providers.service_provider.index') }}">Service Providers</a></li>
                @endif
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        {{ Auth::user()->name }} <span class="caret"></span>
                    </a>

                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown" role="menu">
                        <li>
                            <a class="dropdown-item" href="{{ route('logout') }}">
                                {{ __('Logout') }}
                            </a>
                        </li>
                    </ul>
                </li>
            @endguest
            </ul>
        </div><!--/.nav-collapse -->
    </div>
    </nav>

    <div class="container body-content">
        @yield('content')
    </div>
    @include('layouts.footer')

    @include('service_providers.modal.select')

    @include('layouts.loader')

    <!-- Scripts -->

    <script src="https://code.jquery.com/jquery-1.12.4.min.js"
        integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ="
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.15.0/jquery.validate.min.js"></script>

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="/js/modal-hierarchy.js"></script>
    <script src="/js/new_user.js"></script>

    @yield('scripts')
    <script type="text/javascript">
        $(function(){

            // sends the uploaded file file to the fielselect event
            $(document).on('change', ':file', function() {
                var input = $(this);
                var label = input.val().replace(/\\/g, '/').replace(/.*\//, '');

                input.trigger('fileselect', [label]);
            });

            // Set the label of the uploaded file
            $(':file').on('fileselect', function(event, label) {
                $(this).closest('.uploaded-file-group').find('.uploaded-file-name').val(label);
            });

            // Deals with the upload file in edit mode
            $('.custom-delete-file:checkbox').change(function(e){
                var self = $(this);
                var container = self.closest('.input-width-input');
                var display = container.find('.custom-delete-file-name');

                if (self.is(':checked')) {
                    display.wrapInner('<del></del>');
                } else {
                    var del = display.find('del').first();
                    if (del.is('del')) {
                        del.contents().unwrap();
                    }
                }
            }).change();

            // Sets the validator defaults
            $.validator.setDefaults({
                errorElement: "span",
                errorClass: "help-block",
                highlight: function (element, errorClass, validClass) {
                    $(element).closest('.form-group').addClass('has-error');
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).closest('.form-group').removeClass('has-error');
                },
                errorPlacement: function (error, element) {
                    if (element.parent('.input-group').length) {
                        error.insertAfter(element.parent());
                    } else if(element.prop('type') === 'checkbox' || element.prop('type') === 'radio') {
                        error.appendTo(element.closest(':not(input, label, .checkbox, .radio)').first());
                    } else {
                        error.insertAfter(element);
                    }
                }
            });

            // Makes sure any input with the required class is actually required
            $('form').each(function(index, item){
                var form = $(item);
                form.validate();

                form.find(':input.required').each(function(i, input){
                    $(input).attr('required', true);
                });
            });

        });
    </script>

    @if( Auth::check() && empty(getUserServiceProviderId()) &&  getUserRoleName() !== 'Super Admin')
    <script>
        $('#set_sp').modal('show');
    </script>
    @endif

</body>
</html>
