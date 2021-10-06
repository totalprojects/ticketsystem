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
        <!-- <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}"> -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <!-- <link rel="stylesheet" href="{{ asset('vendor/overlayScrollbars/css/OverlayScrollbars.min.css') }}"> -->

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
    <link rel="stylesheet" href="{{asset('assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/custom/developer.css?v=') }} {{  time() }}">
    <!-- Custom Temp CSS -->
    <style>
       .badge-primary {
           background-color: #326568 !important;
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
        {{-- <link rel="manifest" href="{{ asset('favicons/manifest.json') }}"> --}}
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="{{ asset('favicon/ms-icon-144x144.png') }}">
    @endif
    @routes()
<style>

    .dashboard-icon {
        content:"";
        background-image:url("{{ asset('assets/images/svg/monitor.svg') }}") !important;
        width: 25px;
        height: 25px; 
        display: inline-block;
        background-repeat: no-repeat;
        background-position:center;
        background-size:contain;
        position: relative;
        
    }
    .role-icon {
        content:"";
        background-image:url("{{ asset('assets/images/svg/roles.svg') }}") !important;
        width: 25px;
        height: 25px; 
        display: inline-block;
        background-position:center;
        background-repeat: no-repeat;
        background-size:contain;
        position: relative;
       
    }
    .sap-icon {
        content:"";
        background-image:url("{{ asset('assets/images/svg/sap.svg') }}") !important;
        width: 25px;
        height: 25px; 
        display: inline-block;
        background-position:center;
        background-repeat: no-repeat;
        background-size:contain;
        position: relative;
        
    }
    .manage-users-icon {
        content:"";
        background-image:url("{{ asset('assets/images/svg/users.svg') }}") !important;
        width: 25px;
        height: 25px; 
        display: inline-block;
        background-position:center;
        background-repeat: no-repeat;
        background-size:contain;
        position: relative;
        
    }
    .menu-set-icon {
        content:"";
        background-image:url("{{ asset('assets/images/svg/menu.svg') }}") !important;
        width: 25px;
        height: 25px; 
        display: inline-block;
        background-position:center;
        background-repeat: no-repeat;
        background-size:contain;
        position: relative;
        
    }
    .request-access-icon {
        content:"";
        background-image:url("{{ asset('assets/images/svg/request-access.svg') }}") !important;
        width: 25px;
        height: 25px; 
        display: inline-block;
        background-position:center;
        background-repeat: no-repeat;
        background-size:contain;
        position: relative;
        
    }
    .modules-approval-icon {
        content:"";
        background-image:url("{{ asset('assets/images/svg/modules-approval.svg') }}") !important;
        width: 25px;
        height: 25px; 
        display: inline-block;
        background-position:center;
        background-repeat: no-repeat;
        background-size:contain;
        position: relative;
        
    }
    .app-permission-icon {
        content:"";
        background-image:url("{{ asset('assets/images/svg/app-permission.svg') }}") !important;
        width: 25px;
        height: 25px; 
        display: inline-block;
        background-position:center;
        background-repeat: no-repeat;
        background-size:contain;
        position: relative;
        
    }
    .critical-code-icon {
        content:"";
        background-image:url("{{ asset('assets/images/svg/critical-tcodes.svg') }}") !important;
        width: 25px;
        height: 25px; 
        display: inline-block;
        background-position:center;
        background-repeat: no-repeat;
        background-size:contain;
        position: relative;
        
    }
    .sap-request-icon {
        content:"";
        background-image:url("{{ asset('assets/images/svg/sap-request.svg') }}") !important;
        width: 25px;
        height: 25px; 
        display: inline-block;
        background-position:center;
        background-repeat: no-repeat;
        background-size:contain;
        position: relative;
        
    }
    .team-request-icon {
        content:"";
        background-image:url("{{ asset('assets/images/svg/team-request.svg') }}") !important;
        width: 25px;
        height: 25px; 
        display: inline-block;
        background-position:center;
        background-repeat: no-repeat;
        background-size:contain;
        position: relative;
        
    }
    .user-list-icon {
        content:"";
        background-image:url("{{ asset('assets/images/svg/user-list.svg') }}") !important;
        width: 25px;
        height: 25px; 
        display: inline-block;
        background-position:center;
        background-repeat: no-repeat;
        background-size:contain;
        position: relative;
        
    }
    .user-permission-icon {
        content:"";
        background-image:url("{{ asset('assets/images/svg/user-permission.svg') }}") !important;
        width: 25px;
        height: 25px; 
        display: inline-block;
        background-position:center;
        background-repeat: no-repeat;
        background-size:contain;
        position: relative;
        
    }

    .moderator {
        content:"";
        background-image:url("{{ asset('assets/images/svg/moderator.svg') }}") !important;
        width: 25px;
        height: 25px; 
        display: inline-block;
        background-position:center;
        background-repeat: no-repeat;
        background-size:contain;
        position: relative;
        
    }

    .org-details-icon {
        content:"";
        background-image:url("{{ asset('assets/images/svg/organization.svg') }}") !important;
        width: 25px;
        height: 25px; 
        display: inline-block;
        background-position:center;
        background-repeat: no-repeat;
        background-size:contain;
        position: relative;
        
    }
    .org-departments-icon {
        content:"";
        background-image:url("{{ asset('assets/images/svg/departments.svg') }}") !important;
        width: 25px;
        height: 25px; 
        display: inline-block;
        background-position:center;
        background-repeat: no-repeat;
        background-size:contain;
        position: relative;
        
    }
    .org-companies-icon {
        content:"";
        background-image:url("{{ asset('assets/images/svg/company.svg') }}") !important;
        width: 25px;
        height: 25px; 
        display: inline-block;
        background-position:center;
        background-repeat: no-repeat;
        background-size:contain;
        position: relative;
        
    }
    .org-business-area-icon {
        content:"";
        background-image:url("{{ asset('assets/images/svg/business-area.svg') }}") !important;
        width: 25px;
        height: 25px; 
        display: inline-block;
        background-position:center;
        background-repeat: no-repeat;
        background-size:contain;
        position: relative;
        
    }
    .org-storage-icon {
        content:"";
        background-image:url("{{ asset('assets/images/svg/storage.svg') }}") !important;
        width: 25px;
        height: 25px; 
        display: inline-block;
        background-position:center;
        background-repeat: no-repeat;
        background-size:contain;
        position: relative;
        
    }
    .org-designation-icon {
        content:"";
        background-image:url("{{ asset('assets/images/svg/designation.svg') }}") !important;
        width: 25px;
        height: 25px; 
        display: inline-block;
        background-position:center;
        background-repeat: no-repeat;
        background-size:contain;
        position: relative;
        
    }
    .sales-icon {
        content:"";
        background-image:url("{{ asset('assets/images/svg/Sales.svg') }}") !important;
        width: 25px;
        height: 25px; 
        display: inline-block;
        background-position:center;
        background-repeat: no-repeat;
        background-size:contain;
        position: relative;
        
    }
    .sales-offices-icon {
        content:"";
        background-image:url("{{ asset('assets/images/svg/sales-office.svg') }}") !important;
        width: 25px;
        height: 25px; 
        display: inline-block;
        background-position:center;
        background-repeat: no-repeat;
        background-size:contain;
        position: relative;
        
    }
    .sales-distribution-icon {
        content:"";
        background-image:url("{{ asset('assets/images/svg/sales-distribution.svg') }}") !important;
        width: 25px;
        height: 25px; 
        display: inline-block;
        background-position:center;
        background-repeat: no-repeat;
        background-size:contain;
        position: relative;
        
    }
    .sales-division-icon {
        content:"";
        background-image:url("{{ asset('assets/images/svg/sales-division.svg') }}") !important;
        width: 25px;
        height: 25px; 
        display: inline-block;
        background-position:center;
        background-repeat: no-repeat;
        background-size:contain;
        position: relative;
        
    }
    .purchase-icon {
        content:"";
        background-image:url("{{ asset('assets/images/svg/purchase.svg') }}") !important;
        width: 25px;
        height: 25px; 
        display: inline-block;
        background-position:center;
        background-repeat: no-repeat;
        background-size:contain;
        position: relative;
        
    }
    .po-group-icon {
        content:"";
        background-image:url("{{ asset('assets/images/svg/po-group.svg') }}") !important;
        width: 25px;
        height: 25px; 
        display: inline-block;
        background-position:center;
        background-repeat: no-repeat;
        background-size:contain;
        position: relative;
        
    }
    .po-release-icon {
        content:"";
        background-image:url("{{ asset('assets/images/svg/po-release.svg') }}") !important;
        width: 25px;
        height: 25px; 
        display: inline-block;
        background-position:center;
        background-repeat: no-repeat;
        background-size:contain;
        position: relative;
        
    }
    .logs-icon {
        content:"";
        background-image:url("{{ asset('assets/images/svg/logs.svg') }}") !important;
        width: 25px;
        height: 25px; 
        display: inline-block;
        background-position:center;
        background-repeat: no-repeat;
        background-size:contain;
        position: relative;
        
    }
    .login-icon {
        content:"";
        background-image:url("{{ asset('assets/images/svg/login.svg') }}") !important;
        width: 25px;
        height: 25px; 
        display: inline-block;
        background-position:center;
        background-repeat: no-repeat;
        background-size:contain;
        position: relative;
        
    }
    .page-visit-icon {
        content:"";
        background-image:url("{{ asset('assets/images/svg/page-visit.svg') }}") !important;
        width: 25px;
        height: 25px; 
        display: inline-block;
        background-position:center;
        background-repeat: no-repeat;
        background-size:contain;
        position: relative;
        
    }
    .user-activity-icon {
        content:"";
        background-image:url("{{ asset('assets/images/svg/user-activity.svg') }}") !important;
        width: 25px;
        height: 25px; 
        display: inline-block;
        background-position:center;
        background-repeat: no-repeat;
        background-size:contain;
        position: relative;
        
    }
    .mail-template-icon {
        content:"";
        background-image:url("{{ asset('assets/images/svg/mail-template.svg') }}") !important;
        width: 25px;
        height: 25px; 
        display: inline-block;
        background-position:center;
        background-repeat: no-repeat;
        background-size:contain;
        position: relative;
        
    }
    .critical-tcodes-access-icon {
        content:"";
        background-image:url("{{ asset('assets/images/svg/critical-tcodes-acccess.svg') }}") !important;
        width: 25px;
        height: 25px; 
        display: inline-block;
        background-position:center;
        background-repeat: no-repeat;
        background-size:contain;
        position: relative;
        
    }
    .change-password-icon {
        content:"";
        background-image:url("{{ asset('assets/images/svg/change-password-icon.svg') }}") !important;
        width: 25px;
        height: 25px; 
        display: inline-block;
        background-position:center;
        background-repeat: no-repeat;
        background-size:contain;
        position: relative;
        
    }
    .manage-assets-icon {
        content:"";
        background-image:url("{{ asset('assets/images/svg/manage-assets.svg') }}") !important;
        width: 25px;
        height: 25px; 
        display: inline-block;
        background-position:center;
        background-repeat: no-repeat;
        background-size:contain;
        position: relative;
        
    }
    .sap-access-report-icon {
        content:"";
        background-image:url("{{ asset('assets/images/svg/sap-access-report.svg') }}") !important;
        width: 25px;
        height: 25px; 
        display: inline-block;
        background-position:center;
        background-repeat: no-repeat;
        background-size:contain;
        position: relative;
        
    }
    .sap-development-icon {
        content:"";
        background-image:url("{{ asset('assets/images/svg/sap-development.svg') }}") !important;
        width: 25px;
        height: 25px; 
        display: inline-block;
        background-position:center;
        background-repeat: no-repeat;
        background-size:contain;
        position: relative;
        
    }
    .sap-development-icon {
        content:"";
        background-image:url("{{ asset('assets/images/svg/development-dashboard.svg') }}") !important;
        width: 25px;
        height: 25px; 
        display: inline-block;
        background-position:center;
        background-repeat: no-repeat;
        background-size:contain;
        position: relative;
        
    }
    .load-custom{
          width: 50px;
          height: 50px;
          border-radius: 50%;
          border: 7px solid transparent;
          border-top: 7px solid #e4ff55;
          border-bottom: 7px solid #e4ff55;
          animation: rotate 1.8s linear infinite;
        }
    .custom-loader-wrap {
        height: 100%;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgb(0, 0, 0 , 0.5);
        z-index: 10000 !important;
    }


    @keyframes rotate{
      0%{transform: rotate(0deg);}
      100%{transform: rotate(360deg);}
    }

/**
 * Tooltip Styles
 */

/* Add this attribute to the element that needs a tooltip */
[data-tooltip] {
  position: relative;
  z-index: 2;
  cursor: pointer;
}

/* Hide the tooltip content by default */
[data-tooltip]:before,
[data-tooltip]:after {
  visibility: hidden;
  -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";
  filter: progid: DXImageTransform.Microsoft.Alpha(Opacity=0);
  opacity: 0;
  pointer-events: none;
}

/* Position tooltip above the element */
[data-tooltip]:before {
  position: absolute;
  bottom: 150%;
  left: 50%;
  margin-bottom: 5px;
  margin-left: -80px;
  padding: 7px;
  width: 160px;
  -webkit-border-radius: 3px;
  -moz-border-radius: 3px;
  border-radius: 3px;
  background-color: #000;
  background-color: hsla(0, 0%, 20%, 0.9);
  color: #fff;
  content: attr(data-tooltip);
  text-align: center;
  font-size: 14px;
  line-height: 1.2;
}

/* Triangle hack to make tooltip look like a speech bubble */
[data-tooltip]:after {
  position: absolute;
  bottom: 150%;
  left: 50%;
  margin-left: -5px;
  width: 0;
  border-top: 5px solid #000;
  border-top: 5px solid hsla(0, 0%, 20%, 0.9);
  border-right: 5px solid transparent;
  border-left: 5px solid transparent;
  content: " ";
  font-size: 0;
  line-height: 0;
}

/* Show tooltip content on hover */
[data-tooltip]:hover:before,
[data-tooltip]:hover:after {
  visibility: visible;
  -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=100)";
  filter: progid: DXImageTransform.Microsoft.Alpha(Opacity=100);
  opacity: 1;
}
   
</style>
<script>
    
function customLoader(loadState = 'off') {
    if(loadState == 'on' || loadState == 1) {
        console.log('loaderon')
        $(".custom-loader-wrap").removeClass('d-none');
    } else {
        console.log('loaderoff')
        $(".custom-loader-wrap").addClass('d-none');
    }
}
</script>
</head>

<body class="@yield('classes_body')" @yield('body_data')>
    <div class="custom-loader-wrap d-none">
        <span class="load-custom"></span>
    </div>
    {{-- Body Content --}}
    @yield('body')

    {{-- Base Scripts --}}
    @if(!config('adminlte.enabled_laravel_mix'))
        <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <!-- <script src="{{ asset('vendor/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script> -->
        <script src="{{asset('assets/plugins/select2/js/select2.full.min.js')}}"></script>
        <script src="{{asset('assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>

        {{-- Configured Scripts --}}
        @include('adminlte::plugins', ['type' => 'js'])

        <!-- <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script> -->
        <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
    @else
        <script src="{{ mix(config('adminlte.laravel_mix_js_path', 'js/app.js')) }}"></script>
    @endif
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/data.js"></script>
    <script src="https://code.highcharts.com/modules/drilldown.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    
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
        $("#clear-filter").on('click', (e) => {
            e.preventDefault();
            $("#srch-frm")[0].reset();
            fetch_data();
        });

        $("#date").datepicker({maxDate: '0', dateFormat: 'yy-mm-dd'});
        $("input").prop('autocomplete','off');

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
