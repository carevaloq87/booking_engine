

    @if(Session::has('success_message'))
        <div class="alert alert-success">
            <span class="glyphicon glyphicon-ok"></span>
            {!! session('success_message') !!}

            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>

        </div>
    @endif

    <!-- BEGIN: Subheader -->
    <div class="m-subheader ">
        <div class="d-flex align-items-center">

            <div class="mr-auto">
                <h3 class="m-subheader__title ">@yield('sub_title')</h3>
            </div>

            <div class="pull-right">
                @yield('buttons')
            </div>

        </div>
    </div>
    <!-- END: Subheader -->