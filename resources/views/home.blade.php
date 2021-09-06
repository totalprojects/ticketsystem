@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    {{-- <h1>Dashboard</h1> --}}
@stop

@section('content')
   
    <div class="row">

          <div class="col-lg-4 col-6">
                <!-- small box -->
                <div class="small-box bg-danger">
                  <div class="inner">
                    @if(!empty($login_logs))
                    <h5><strong>{{ date('d-m-y h:i a', strtotime($login_logs[0]->created_at)) }}</strong></h5>
                    @endif
                    <p>Last Login</p>
                  </div>
                  <div class="icon">
                    <i class="fas fa-sign-in-alt"></i>
                  </div>
                  <a href="#" class="small-box-footer d-none">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
          </div>

          <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                @if(!empty($login_logs))
                <h5><strong>{{ $login_logs[0]->ip }}</strong></h5>
                @endif
                <p>IP Address</p>
              </div>
              <div class="icon">
                <i class="fas fa-globe"></i>
              </div>
              <a href="#" class="small-box-footer d-none">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                @if(!empty($login_logs))
                @php($agent = explode(" ", $login_logs[0]->agent))
                <h5><strong>{{ $agent[0] ?? '-' }}</strong></h5>
                @endif
                <p>Browser Agent</p>
              </div>
              <div class="icon">
                <i class="fas fa-window-restore"></i>
              </div>
              <a href="#" class="small-box-footer d-none">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
    </div>


@stop

@section('css')
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop
