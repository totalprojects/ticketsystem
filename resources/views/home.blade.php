@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    {{-- <h1>Dashboard</h1> --}}
@stop

@section('content')
<style type="text/css">
  .approval-time-wrap #chart {
    height: 500px !important;
}
</style>
    <div class="row">
          <div class="col-lg-4">
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

          <div class="col-lg-4">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                @if(!empty($login_logs))
                <h5><strong>{{ $login_logs[0]->ip ?? '-' }}</strong></h5>
                @endif
                <p>IP Address</p>
              </div>
              <div class="icon">
                <i class="fas fa-globe"></i>
              </div>
              <a href="#" class="small-box-footer d-none">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <div class="col-lg-4">
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
    <div class="approval-time-wrap row">
      <div class="col-md-12">
        <div id="chart">
      </div>
      </div>  
      
    </div>  

@stop

@section('css')
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
<script type="text/javascript">
loadChart();


function loadChart() {

$.ajax({
  url:"{{ route('approval.analytics') }}",
  type:"GET",
  data:null,
  error:(r) => {
    console.log(r)
  },
  success:(r) => {
    var options = {
  chart: {
    height: 350,
    type: "area"
  },
  dataLabels: {
    enabled: true
  },
  legend: {
    show: true
  },
  series: [
    {
      name: "Time",
      data: r
    }
  ],
  fill: {
    type: "gradient",
    gradient: {
      shadeIntensity: 1,
      opacityFrom: 0.7,
      opacityTo: 0.9,
      stops: [37, 94, 97]
    }
  },
  colors: ['#255e61', '#66DA26'],
  // xaxis: {
  //   categories: [
  //     "01 Jan",
  //     "02 Jan",
  //     "03 Jan",
  //     "04 Jan",
  //     "05 Jan",
  //     "06 Jan",
  //     "07 Jan"
  //   ]
  // }
};

var chart = new ApexCharts(document.querySelector("#chart"), options);

chart.render();

  }
});

}



    </script>
@stop
