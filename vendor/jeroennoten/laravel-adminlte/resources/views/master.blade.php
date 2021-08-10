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
    <!-- Custom Temp CSS -->
    <style>
        label.error{
            color:rgb(192, 11, 11);
            font-size: 11px;
        }
        .btn-primary, .modal-header {
            /* background-color:#25396f !important; */
            background-color:#571b4a !important;
            border:none !important;
            padding:0.44rem !important;
            color:cornsilk !important;
            box-shadow: 0 0 1px 1px rgba(0,0,0,0.18);
        }
        .modal-header span {
            color:cornsilk !important;
        }
        a{
            color:#25396f !important;
            font-weight: 650 !important;
        }
        body{
            /* font-family: 'Nunito', sans-serif; */
            font-family: 'Inter', sans-serif;
            font-weight: 400;
            font-size: 14px !important;
        }
        .dx-datagrid-content {
            font-family: 'Inter', sans-serif;
            font-weight:400 !important;
        }
        .dx-datagrid-rowsview td {
            font-weight: bold !important;
        }
        .brand-link .brand-image {
            margin-left:0 !important;
            margin-right:0 !important;
        }
        .sidebar-dark-primary .nav-sidebar>.nav-item>.nav-link.active, .sidebar-light-primary .nav-sidebar>.nav-item>.nav-link.active{
            background-color:#435ebe !important;
            color:cornsilk !important;
            border-radius: .2rem
        }
        button {
            transition: 0.5s all;
        }
        i {
            transition: 0.5s all;
        }
        button i {
            background-color: #435ebe;
            padding: 0.22em;
            color:cornsilk;
        }
        .btn-primary:hover{
            background-color: #435ebe !important;
        }
        .bg-primary {
            background-color: #435ebe !important;
            border:0.5px dotted #dee6f5 !important;
            color:cornsilk !important;
        }
        .bg-secondary {
            background-color: #0a1847 !important;
            border:0.5px dotted #dee6f5 !important;
            color:cornsilk !important;
        }

        input[type='checkbox'] {
            -webkit-appearance: none;
            width: 25px;
            height:22px;
            background-color: rgb(245, 250, 234) !important;
            border: none;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05), inset 0px -15px 10px -12px rgba(0,0,0,0.05);
            padding: 6px;
            border-radius: none;
            display: inline-block;
            position: relative;
        }

        input[type='checkbox']:checked {
            background-color: #505c64;
            border: 1px solid #1c311d;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05), inset 0px -15px 10px -12px rgba(0,0,0,0.05), inset 15px 10px -12px rgba(255,255,255,0.1);
            color: #99a1a7;
        }

        input[type='checkbox']:checked:after {
            content: '\2714';
            font-size: 20px;
            position: absolute;
            top: -3px;
            left: 3px;
            color: #171d1a;
        }
        

        .btn-primary:hover i{
            background-color: #25396f !important;
        }
        .badge-primary {
            background-color: #435ebe;
            padding: 0.44em;
            font-weight: 400;
        }
        .small-box{
            background-color: #435ebe !important;
            padding: 0.22em;
            color:cornsilk !important;
        }
        .small-box-footer{
            color:cornsilk !important;
        }
        .dx-datagrid{
            padding:8px !important;
            margin:8px !important;
        }
        .active{
            background-color: #dee6f5 !important;
        }
        .dx-datagrid-headers {
            background-color: #25396f;
            color:cornsilk;
            font-weight: 400;
        }
        .tab-content .right {
            background-color:#435ebe !important;
            color:cornsilk;
            padding:0.22em;
            box-shadow: 0 2px 2px rgba(0,0,0,.25),0 4px 4px rgba(0,0,0,.22) !important;
            border-radius: .2rem;
            font-style: normal;
        }
        .dx-data-row{
            background-color: rgb(255, 255, 255);
            transition:0.33s all;
            font-weight: 550;
        }
        .dx-data-row:hover {
            background-color: rgb(231, 225, 225);
            cursor: pointer;
        }
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
        <link rel="manifest" href="{{ asset('favicons/manifest.json') }}">
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
