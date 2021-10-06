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
.count-wrap .count {
    font-size: 40px;
    font-weight: 800;
    color: #e4ff55;
    height: 85px;
    width: 85px;
    background: #255e61;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    margin-bottom: 15px;
}

.count-wrap p {
    text-align: center;
    font-size: 18px;
    font-weight: 600;
    color: #255e61;
}
.legends {
    width: 100% !important;
    font-weight: 700;
    color: #255e61;
    font-size: 18px;
    margin-bottom: 10px;
}
.lastlogsblock.row {
    max-height: 300px !important;
    overflow: auto;
}

.lastlogsblock .col-sm-3 {
    border: 0.5px solid #ccc;
    padding: 5px;
    text-align: center;
    font-weight: 600;
    background-color: #255e61;
    color: #e4ff55;
    font-size: 12.5px;
}
.lastlogsblock .col-sm-3 p{
   margin-bottom: 0;
}

.lastlogsblock .col-sm-9 {
    border-bottom: 0.5px solid #ccc;
    border-top: 0.5px solid #ccc;
    padding: 5px;
    font-weight: 600;
    font-size: 12.5px;
}
</style>

    @if(\Auth::user()->id == 1)
    <div class="approval-time-wrap row">
        <div class="col-lg-12">
            <h2>SAP Development Dashboard</h2>
        </div>
      <div class="col-md-6">
        <div id="pie-1">
        </div>
      </div>
      <div class="col-md-6">
        <div id="bar-1">
        </div>
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

/* Load Stages Bar chart */
function loadBarChart2() {

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
            renderBarChart(r.data, r.drilleddata);
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
renderStagesBarChart([]);
function renderStagesBarChart(dataset) {
    Highcharts.chart('bar-1', {
  chart: {
    type: 'column'
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
      pointPadding: 0.2,
      borderWidth: 0
    }
  },
  series: [{
    name: 'Requests',
    data: [1, 4, 5, 2, 9]

  }]
});
}

</script>
@stop