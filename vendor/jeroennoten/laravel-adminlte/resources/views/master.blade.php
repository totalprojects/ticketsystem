<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    {{-- Base Meta Tags --}}
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Custom Meta Tags --}}
    @yield('meta_tags')

    {{-- Title --}}
    <title>
        @yield('title_prefix', config('adminlte.title_prefix', ''))
        @yield('title', config('adminlte.title', 'IT Audit Module'))
        @yield('title_postfix', config('adminlte.title_postfix', ''))
    </title>

    {{-- Custom stylesheets (pre AdminLTE) --}}
    @yield('adminlte_css_pre')

    {{-- Base Stylesheets --}}
    @if(!config('adminlte.enabled_laravel_mix'))
        <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
        <link rel="stylesheet" href="{{ asset('vendor/overlayScrollbars/css/OverlayScrollbars.min.css') }}">

        {{-- Configured Stylesheets --}}
        @include('adminlte::plugins', ['type' => 'css'])

        <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
        {{-- <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet"> --}}
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
    @else
        <link rel="stylesheet" href="{{ mix(config('adminlte.laravel_mix_css_path', 'css/app.css')) }}">
    @endif
    <link rel="stylesheet" href="{{asset('assets/plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugins/pickletree/pickletree.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/custom/developer.css') }}">
    <!-- Custom Temp CSS -->
    <style>
       
    </style>

    {{-- Livewire Styles --}}
    @if(config('adminlte.livewire'))
        @if(app()->version() >= 7)
            @livewireStyles
        @else
            <livewire:styles />
        @endif
    @endif

    {{-- Custom Stylesheets (post AdminLTE) --}}
    @yield('adminlte_css')

    {{-- Favicon --}}
    @if(config('adminlte.use_ico_only'))
        <link rel="shortcut icon" href="{{ asset('favicons/favicon.ico') }}" />
    @elseif(config('adminlte.use_full_favicon'))
        <link rel="shortcut icon" href="{{ asset('favicons/favicon.ico') }}" />
        <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('favicons/apple-icon-57x57.png') }}">
        <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('favicons/apple-icon-60x60.png') }}">
        <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('favicons/apple-icon-72x72.png') }}">
        <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('favicons/apple-icon-76x76.png') }}">
        <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('favicons/apple-icon-114x114.png') }}">
        <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('favicons/apple-icon-120x120.png') }}">
        <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('favicons/apple-icon-144x144.png') }}">
        <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('favicons/apple-icon-152x152.png') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicons/apple-icon-180x180.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicons/favicon-16x16.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicons/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicons/favicon-96x96.png') }}">
        <link rel="icon" type="image/png" sizes="192x192"  href="{{ asset('favicons/android-icon-192x192.png') }}">
        {{-- <link rel="manifest" href="{{ asset('favicons/manifest.json') }}"> --}}
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="{{ asset('favicon/ms-icon-144x144.png') }}">
    @endif

</head>

<body class="@yield('classes_body')" @yield('body_data')>

    {{-- Body Content --}}
    @yield('body')

    {{-- Base Scripts --}}
    @if(!config('adminlte.enabled_laravel_mix'))
        <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('vendor/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
        <script src="{{asset('assets/plugins/select2/js/select2.full.min.js')}}"></script>

        {{-- Configured Scripts --}}
        @include('adminlte::plugins', ['type' => 'js'])

        <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
    @else
        <script src="{{ mix(config('adminlte.laravel_mix_js_path', 'js/app.js')) }}"></script>
    @endif
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- Livewire Script --}}
    @if(config('adminlte.livewire'))
        @if(app()->version() >= 7)
            @livewireScripts
        @else
            <livewire:scripts />
        @endif
    @endif

    {{-- Custom Scripts --}}
    @yield('adminlte_js')
    <script src="{{asset('assets/plugins/pickletree/pickletree.js')}}"></script>
    <script>

        $('.select2bs4').select2({ theme: 'bootstrap4' });
        $("select").on('change', () => {
            $(".loading1").removeClass('d-none');

            setTimeout(() => {
                $(".loading1").addClass('d-none');
            },500);
        })
        loadStates();
        loadDepartments();
        loadRoles();
        loadCompanies();

        function loadStates(){

            $.ajax({
                url:"{{ route('fetch.states') }}",
                data:null,
                type:"GET",
                error: (message) => {
                    toastr.error('Something went wrong loading states');
                },
                success: (response) => {


                    var html = ``;
                    $.each(response.states, (i) => {

                        html += `<option value='${response.states[i].state_id}'>${response.states[i].state_name}</option>`;
                    })

                    $(".states").append(html);
                }
            })
        }

        function loadCompanies(){

            $.ajax({
                url:"{{ route('fetch.companies') }}",
                data:null,
                type:"GET",
                error: (message) => {
                    toastr.error('Something went wrong loading companies');
                },
                success: (response) => {


                    var html = ``;
                    $.each(response.companies, (i) => {

                        html += `<option value='${response.companies[i].id}'>${response.companies[i].company_name}</option>`;
                    })

                    $(".companies").append(html);
                }
            })
        }

        function loadDepartments(){

            $.ajax({
                url:"{{ route('fetch.departments') }}",
                data:null,
                type:"GET",
                error: (message) => {
                    toastr.error('Something went wrong loading departments');
                },
                success: (response) => {


                    var html = ``;
                    $.each(response.departments, (i) => {

                        html += `<option value='${response.departments[i].id}'>${response.departments[i].department_name}</option>`;
                    })

                    $(".departments").append(html);
                }
            })
        }

        function loadRoles(){

            $.ajax({
                url:"{{ route('fetch.roles') }}",
                data:null,
                type:"GET",
                error: (message) => {
                    toastr.error('Something went wrong loading roles');
                },
                success: (response) => {


                    var html = ``;
                    $.each(response.roles, (i) => {

                        html += `<option value='${response.roles[i].id}'>${response.roles[i].name}</option>`;
                    })

                    $(".roles").append(html);
                }
            })
        }

        /* On state select display districts */
        $(".states").on('change', () => {

            var state_id = $("#states").val();
            $("form").append(`<span class='spinner1'><i class='fa fa-spinner fa-spin'></i> Loading... </span>`);
            loadDistricts(state_id);
            
        });

        /* On state select display districts */
        $(".departments").on('change', () => {

            var dept_id = $(".departments").find(':selected').val();
            console.log(dept_id);
            $("form").append(`<span class='spinner1'><i class='fa fa-spinner fa-spin'></i> Loading... </span>`);
            loadReportingTos(dept_id);

        });

        function loadDistricts(state_id){

            $.ajax({
                url:"{{ route('fetch.districts') }}",
                data:{state_id},
                type:"GET",
                error: (message) => {
                    toastr.error('Something went wrong loading states');
                },
                success: (response) => {


                    var html = ``;
                    $.each(response.districts, (i) => {

                        html += `<option value='${response.districts[i].district_id}'>${response.districts[i].district_name}</option>`;
                    })
                    $(".districts").html(``);
                    $(".districts").append(html);
                    setTimeout(()=> {
                        $(".spinner1").hide();
                    },1000);
                }
            })
        }

        function loadReportingTos(dept_id){

            if(dept_id.length==0) {
                toastr.error('Oops! Something was not right, try again selecting the department');
                return;
            }

            $.ajax({
                url:"{{ route('fetch.report_tos') }}",
                data:{dept_id},
                type:"GET",
                error: (message) => {

                    toastr.error('Something went wrong loading states');
                },
                success: (response) => {


                    var html = `<option value=''>--Select Employee--</option>`;
                    $.each(response.report_to, (i) => {

                        html += `<option value='${response.report_to[i].id}'>${response.report_to[i].name}</option>`;
                    })
                    $(".reporting_to").html(``);
                    $(".reporting_to").append(html);
                    setTimeout(()=> {
                        $(".spinner1").hide();
                    },1000);
                }
            })
        }
    </script>
</body>

</html>
