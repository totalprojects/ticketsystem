@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    {{-- <h1>Dashboard</h1> --}}
@stop

@section('content')
<style type="text/css">
.approval-time-chart,.request-status-chart,.approval-count-chart {
    background: #fff;
    padding: 15px;
    border-radius: 10px;
    box-shadow: 0 0 10px #e6e6e6;
    margin-bottom: 30px;
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
        <div class="approval-time-chart">
          <div id="chart"></div>
        </div>
      </div>  
      <div class="col-md-4">
        <div class="approval-count-chart">
          <div id="chart2"></div>
        </div>
      </div> 
      <div class="col-md-4">
        <div class="total-approval-sec">
          <!-- place for counter js -->
        </div>
      </div> 
      <div class="col-md-4">
        <div class="request-status-chart">
          <div id="chart3"></div>
        </div>
      </div>  
   </div> 
     

@stop

@section('css')
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
<script type="text/javascript">
loadChart1();


function loadChart1() {

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
loadChart2();
function loadChart2() {
  var options = {
          series: [44, 55, 13, 43, 22],
          chart: {
          width: '100%',
          type: 'pie',
        },
        labels: ['Team A', 'Team B', 'Team C', 'Team D', 'Team E'],
        responsive: [{
          breakpoint: 480,
          options: {
            chart: {
              width: 200
            },
            legend: {
              position: 'bottom'
            }
          }
        }]
        };

        var chart = new ApexCharts(document.querySelector("#chart2"), options);
        chart.render();
        var chart3 = new ApexCharts(document.querySelector("#chart3"), options);
        chart3.render();
}



    </script>
@stop
