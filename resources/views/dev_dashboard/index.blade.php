@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    {{-- <h1>Dashboard</h1> --}}
@stop

@section('content')
<style type="text/css">
.total-approval-sec,.approval-time-chart,.request-status-chart,.approval-count-chart {
    background: #fff;
    padding: 15px;
    border-radius: 10px;
    box-shadow: 0 0 10px #e6e6e6;
    height: 100%;
}
.total-approval-sec {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    
}
.approval-time-wrap.row > div {
    margin-bottom: 25px;
}

.legends {
    width: 100% !important;
    font-weight: 700;
    color: #255e61;
    font-size: 18px;
    margin-bottom: 10px;
}
</style>

    @if(\Auth::user()->id == 1)
        <div class="approval-time-wrap row">
            <div class="col-lg-12">
                <h2>SAP Change Request Dashboard</h2>
            </div>
            <div class="col-md-6">
                <div id="pie-1">
                </div>
            </div>
            <div class="col-md-6">
                <div id="bar-1">
                </div>
            </div>
            
            <!-- Search at a glance -->
            <div class="col-lg-12">
                <legend>
                    Search
                </legend>
            </div>
            <div class="col-lg-4">
                <input type="text" name="tcode" class="form-control" placeholder="TCode">
            </div>
            <div class="col-lg-4">
                <input type="text" name="request_id" class="form-control" placeholder="Request ID">
            </div>
            <div class="col-lg-4">
                <input type="text" name="module_id" class="form-control" placeholder="Module Name">
            </div>
            <div class="col-lg-4">
                <input type="text" name="user_id" class="form-control" placeholder="User">
            </div>
            <div class="col-lg-4">
                <input type="text" name="from" id="from" class="form-control" placeholder="From Date">
            </div>
            <div class="col-lg-4">
                <input type="text" name="to" id="to" class="form-control" placeholder="To Date">
            </div>
        </div> 
    @endif

@stop

@section('css')
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
<script type="text/javascript">

loadPieChart1();
loadBarChart2();

/* Load Stages Bar chart */
function loadBarChart2() {

$.ajax({
    url:route('fetch.stage.bar'),
    data:null,
    type:"GET",
    error:(r) => {
        toastr.error("Error");
    },
    success: (r) => {

        if(r.data) {
            console.log(r.data)
            renderStagesBarChart(r.data);
        }
    }
})
}

/* Load Pie 1 */
function loadPieChart1() {

    $.ajax({
        url:route('fetch.dev.sap.modules'),
        data:null,
        type:"GET",
        error:(r) => {
            toastr.error("Error");
        },
        success: (r) => {
            console.log(r.drilleddata);
            if(r.data) {
                renderPieChart(r.data, r.drilleddata);
            }
        }
    })
}


// Create the chart
function renderPieChart(dataset, drilleddata) {
        Highcharts.chart('pie-1', {
                chart: {
                    type: 'pie'
                },
                exporting: {
                    enabled: false
                },
                title: {
                    text: 'Module wise Requests'
                },
                subtitle: {
                    text: 'Viewing Overall'
                },

                accessibility: {
                    announceNewData: {
                    enabled: true
                    },
                    point: {
                    valueSuffix: ''
                    }
                },

                plotOptions: {
                    series: {
                    dataLabels: {
                        enabled: true,
                        format: '{point.name}: {point.y}'
                    }
                    }
                },

                tooltip: {
                    headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                    pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b><br/>'
                },

                series: [
                    {
                    name: "Requests",
                    colorByPoint: true,
                    data: dataset
                    }
                ],
                drilldown: {
                    series: drilleddata

                }
        });
}

/** Load Stages Chart */
function renderStagesBarChart(dataset) {
    Highcharts.chart('bar-1', {
  chart: {
    type: 'column'
  },
  exporting: {
    enabled: false
  },
  title: {
    text: 'Overall Stage wise Request'
  },
  subtitle: {
    text: ''
  },
  xAxis: {
    categories: [
      'REQUIREMENT',
      'APPROVER',
      'DEVELOPMENT',
      'UAT',
      'PRODUCTION'
    ],
    crosshair: true
  },
  yAxis: {
    min: 0,
    title: {
      text: 'Count'
    }
  },
  tooltip: {
    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
      '<td style="padding:0"><b>{point.y}</b></td></tr>',
    footerFormat: '</table>',
    shared: true,
    useHTML: true
  },
  plotOptions: {
    column: {
      pointPadding: 0,
      borderWidth: 1
    }
  },
  series: [{
    name: 'Requests',
    data: dataset

  }]
});
}

</script>
@stop
