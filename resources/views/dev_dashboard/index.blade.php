@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    {{-- <h1>Dashboard</h1> --}}
@stop

@section('content')
<style type="text/css">

.fa-spin-fast {
  -webkit-animation: fa-spin-fast 0.2s infinite linear;
  animation: fa-spin-fast 0.2s infinite linear;
}
@-webkit-keyframes fa-spin-fast {
  0% {
    -webkit-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(359deg);
    transform: rotate(359deg);
  }
}
@keyframes fa-spin-fast {
  0% {
    -webkit-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(359deg);
    transform: rotate(359deg);
  }
}
.material-card {
  position: relative;
  height: 0;
  padding-bottom: calc(100% - 16px);
  margin-bottom: 6.6em;
}
.material-card h2 {
  position: absolute;
  top: calc(100% - 16px);
  left: 0;
  width: 100%;
  padding: 10px 16px;
  color: #fff;
  font-size: 1.4em;
  line-height: 1.6em;
  margin: 0;
  z-index: 10;
  -webkit-transition: all 0.3s;
  -moz-transition: all 0.3s;
  -ms-transition: all 0.3s;
  -o-transition: all 0.3s;
  transition: all 0.3s;
}
.material-card h2 span {
  display: block;
}
.material-card h2 strong {
  font-weight: 400;
  display: block;
  font-size: 0.8em;
}
.material-card h2:before,
.material-card h2:after {
  content: ' ';
  position: absolute;
  left: 0;
  top: -16px;
  width: 0;
  border: 8px solid;
  -webkit-transition: all 0.3s;
  -moz-transition: all 0.3s;
  -ms-transition: all 0.3s;
  -o-transition: all 0.3s;
  transition: all 0.3s;
}
.material-card h2:after {
  top: auto;
  bottom: 0;
}
@media screen and (max-width: 767px) {
  .material-card.mc-active {
    padding-bottom: 0;
    height: auto;
  }
}
.material-card.mc-active h2 {
  top: 0;
  padding: 10px 16px 10px 90px;
}
.material-card.mc-active h2:before {
  top: 0;
}
.material-card.mc-active h2:after {
  bottom: -16px;
}
.material-card .mc-content {
  position: absolute;
  right: 0;
  top: 0;
  bottom: 16px;
  left: 16px;
  -webkit-transition: all 0.3s;
  -moz-transition: all 0.3s;
  -ms-transition: all 0.3s;
  -o-transition: all 0.3s;
  transition: all 0.3s;
}
.material-card .mc-btn-action {
  position: absolute;
  right: 16px;
  top: 15px;
  -webkit-border-radius: 50%;
  -moz-border-radius: 50%;
  border-radius: 50%;
  border: 5px solid;
  width: 54px;
  height: 54px;
  line-height: 44px;
  text-align: center;
  color: #fff;
  cursor: pointer;
  z-index: 20;
  -webkit-transition: all 0.3s;
  -moz-transition: all 0.3s;
  -ms-transition: all 0.3s;
  -o-transition: all 0.3s;
  transition: all 0.3s;
}
.material-card.mc-active .mc-btn-action {
  top: 62px;
}
.material-card .mc-description {
  position: absolute;
  top: 100%;
  right: 30px;
  left: 30px;
  bottom: 54px;
  overflow: hidden;
  opacity: 0;
  filter: alpha(opacity=0);
  -webkit-transition: all 1.2s;
  -moz-transition: all 1.2s;
  -ms-transition: all 1.2s;
  -o-transition: all 1.2s;
  transition: all 1.2s;
}
.material-card .mc-footer {
  height: 0;
  overflow: hidden;
  -webkit-transition: all 0.3s;
  -moz-transition: all 0.3s;
  -ms-transition: all 0.3s;
  -o-transition: all 0.3s;
  transition: all 0.3s;
}
.material-card .mc-footer h4 {
  position: absolute;
  top: 200px;
  left: 30px;
  padding: 0;
  margin: 0;
  font-size: 16px;
  font-weight: 700;
  -webkit-transition: all 1.4s;
  -moz-transition: all 1.4s;
  -ms-transition: all 1.4s;
  -o-transition: all 1.4s;
  transition: all 1.4s;
}
.material-card .mc-footer a {
  display: block;
  float: left;
  position: relative;
  width: 52px;
  height: 52px;
  margin-left: 5px;
  margin-bottom: 15px;
  font-size: 28px;
  color: #fff;
  line-height: 52px;
  text-decoration: none;
  top: 200px;
}
.material-card .mc-footer a:nth-child(1) {
  -webkit-transition: all 0.5s;
  -moz-transition: all 0.5s;
  -ms-transition: all 0.5s;
  -o-transition: all 0.5s;
  transition: all 0.5s;
}
.material-card .mc-footer a:nth-child(2) {
  -webkit-transition: all 0.6s;
  -moz-transition: all 0.6s;
  -ms-transition: all 0.6s;
  -o-transition: all 0.6s;
  transition: all 0.6s;
}
.material-card .mc-footer a:nth-child(3) {
  -webkit-transition: all 0.7s;
  -moz-transition: all 0.7s;
  -ms-transition: all 0.7s;
  -o-transition: all 0.7s;
  transition: all 0.7s;
}
.material-card .mc-footer a:nth-child(4) {
  -webkit-transition: all 0.8s;
  -moz-transition: all 0.8s;
  -ms-transition: all 0.8s;
  -o-transition: all 0.8s;
  transition: all 0.8s;
}
.material-card .mc-footer a:nth-child(5) {
  -webkit-transition: all 0.9s;
  -moz-transition: all 0.9s;
  -ms-transition: all 0.9s;
  -o-transition: all 0.9s;
  transition: all 0.9s;
}
.material-card .img-container {
  overflow: hidden;
  position: absolute;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  z-index: 3;
  -webkit-transition: all 0.3s;
  -moz-transition: all 0.3s;
  -ms-transition: all 0.3s;
  -o-transition: all 0.3s;
  transition: all 0.3s;
}
.material-card.mc-active .img-container {
  -webkit-border-radius: 50%;
  -moz-border-radius: 50%;
  border-radius: 50%;
  left: 0;
  top: 12px;
  width: 60px;
  height: 60px;
  z-index: 20;
}
.material-card.mc-active .mc-content {
  padding-top: 5.6em;
}
.material-card.mc-active .mc-content .row{
    display:none;
}
.material-card.mc-active .mc-content h1{
    display:none;
}
.material-card.mc-active .mc-content small{
    display:none;
}
@media screen and (max-width: 767px) {
  .material-card.mc-active .mc-content {
    position: relative;
    min-height: none !important;
    overflow:auto !important;
    margin-right: 16px;
  }
}
.material-card.mc-active .mc-description {
    top: 100px;
    width: 90%;
    padding-right: 2em;
    padding-top: 6.6em;
    opacity: 1;
  filter: alpha(opacity=100);
}
@media screen and (max-width: 767px) {
  .material-card.mc-active .mc-description {
    position: relative;
    top: auto;
    right: auto;
    left: auto;
    padding: 50px 30px 70px 30px;
    bottom: 0;
  }
}
.material-card.mc-active .mc-footer {
  overflow: visible;
  position: absolute;
  top: calc(100% - 16px);
  left: 16px;
  right: 0;
  height: 82px;
  padding-top: 15px;
  padding-left: 25px;
}
.material-card.mc-active .mc-footer a {
  top: 0;
}
.material-card.mc-active .mc-footer h4 {
  top: -32px;
}

.material-card.Light-Green h2 {
  background-color: #255e61;
}
.material-card.Light-Green h2:after {
  border-top-color: #255e61;
  border-right-color: #255e61;
  border-bottom-color: transparent;
  border-left-color: transparent;
}
.material-card.Light-Green h2:before {
  border-top-color: transparent;
  border-right-color: #33691E;
  border-bottom-color: #33691E;
  border-left-color: transparent;
}
.material-card.Light-Green h2:before {
    border-top-color: transparent;
    border-right-color: #113435;
    border-bottom-color: #113435;
    border-left-color: transparent;
}
article.material-card.Light-Green.mc-active h2:before {
    display: none;
}
.material-card.Light-Green.mc-active h2:after {
    border-top-color: #123435;
    border-right-color: #123435;
    border-bottom-color: transparent;
    border-left-color: transparent;
}
.material-card.Light-Green .mc-btn-action {
    background-color: #255e61;
    border: 3px solid #e4ff55;
    color: #e4ff55 !important;
}
.mc-content h1 {
    font-size: 25px;
}
.material-card.Light-Green .mc-btn-action:hover {
  background-color: #33691E;
}
.material-card.Light-Green .mc-footer h4 {
  color: #33691E;
}
.material-card.Light-Green .mc-footer a {
  background-color: #33691E;
}
.material-card.Light-Green.mc-active .mc-content {
  background-color: #F1F8E9;
}
.material-card.Light-Green.mc-active .mc-footer {
  background-color: #DCEDC8;
}
.material-card.Light-Green.mc-active .mc-btn-action {
  border-color: #F1F8E9;
}

h1,
h2,
h3 {
  font-weight: 200;
}

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
.inheading{
    background-color:#255e61;
    color:#fff;
    padding:8px;
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
              <div class="bar-filter-section">
                  <form id="bar-chart-filter">
                      <div class="row">
                      <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                          <i class="fa fa-calendar"></i>&nbsp;
                          <span></span> <i class="fa fa-caret-down"></i>
                      </div>
                      </div>  
                  </form>
              </div>
                <div id="bar-1">
                </div>
            </div>  
            <!-- Search at a glance -->
            <div class="col-lg-12">
                <legend>
                    Search
                </legend>
            </div>
            <form method='post' id='srchFrm'>
                <input type="hidden" name="take" id="take">
                <input type="hidden" name="skip" id="skip">
                <input type="hidden" id="position" value="1">
                <input type="hidden" id="noOfPages" value="0">
                  <div class="row">
                      <div class="col-lg-4 mt-2">
                          <input type="text" name="req_id" id="req_id" class="form-control" placeholder="Request ID">
                      </div>
                      <div class="col-lg-4 pt-2">
                          <select name="module_id" id="module_id" class="form-control select2bs4" data-placeholder='Select Module'>
                              <option value=""></option>   
                          @foreach($modules as $each)
                                  <option value="{{ $each['id'] }}">{{ $each['name'] }}</option>
                              @endforeach
                          </select>
                      </div>
                      <div class="col-lg-4 pt-2">
                          <select name="tcode_id" id="tcode_id" class="form-control select2bs4" placeholder='Select TCode'>
                              <option value="">--SELECT MODULE FIRST--</option>
                          </select>
                      </div>
                      <div class="col-lg-4 mt-2">
                          <input type="text" name="user_id" class="form-control" placeholder="User">
                      </div>
                      <div class="col-lg-4 mt-2">
                          <input type="text" name="fromDate" id="from" class="form-control" placeholder="From Date">
                      </div>
                      <div class="col-lg-4 mt-2">
                          <input type="text" name="toDate" id="to" class="form-control" placeholder="To Date">
                      </div>
                      
                      <div class="col-lg-4 mt-2">
                          <button name="search_btn" id="searchBtn" class="btn btn-primary">Search</button>
                          &nbsp;<button name="reset-btn" id="reset-btn" type="button" class="btn btn-primary">Reset</button>
                      </div>
                  </div>
            </form>
        </div> 
       
        <div class="row">
            <div class='col-lg-12'>
                <div class="row active-with-click" id="view-result">
                </div>
               
           </div>
        </div>
       
    @endif
    <!-- Description modal -->
    <div class="modal fade" id="desc-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detailed Description</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                  <div id="desc"></div>
                </div>
               
            </div>
            </div>
    </div>
@stop

@section('css')
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
<script type="text/javascript">
/** Reset Form */
$(document).on('click','#reset-btn', (e) => {
  e.preventDefault();
  $("#srchFrm")[0].reset();
  $("#tcode_id").val('').trigger('change');
  $("#module_id").val('').trigger('change');
  customLoader(0)
  
})

// $('#monthly').datepicker( {
//   changeMonth: true,
//   showButtonPanel: true,
//   dateFormat: 'MM',
//   onClose: function(dateText, inst) { 
//     console.log('closed date')
//     console.log(dateText)
//      // $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
//   }
// });
var start = moment().subtract(29, 'days');
var end = moment();

function cb(start, end) {
    $('#reportrange').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
      var range = $("#reportrange").html();
      loadBarChart2(range)
}
$("#reportrange").daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
}, cb);
cb(start, end)




/** On Load Set Take Skip Initial values */
$("#skip").val(0);
$("#take").val(3);

/** Search Form */
$("#searchBtn").on('click', (e) => {
    e.preventDefault();
      loadRequests();
});
/** Date Picker */
$("#from").datepicker({ maxDate: 0, changeMonth:true, changeYear:true, dateFormat: 'yy-mm-dd'});
$("#to").datepicker({ maxDate: 0, changeMonth:true, changeYear:true, dateFormat: 'yy-mm-dd'});

/** Load Charts and Requests */
loadPieChart1();
loadBarChart2();
loadRequests();


/* Load Stages Bar chart */
function loadBarChart2(range) {

  $.ajax({
      url:route('fetch.stage.bar'),
      data:{range},
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

/** Render Pic Chart */
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

/** Load the dev requests */
function loadRequests() {

    $.ajax({
      url:route('fetch.dev.dashboard.requests'),
      data:$("#srchFrm").serialize(),
      type:"GET",
      error:(r) => {
          toastr.error("Error");
      },
      success: (r) => {
          if(r.data) {
              console.log(r)
              var html = ``;
              const take = $("#take").val();
              html = renderHTML(r.data, r.totalCount, take)
              $("#view-result").html(html)
          } else {
            toastr.error('Something went wrong');
          }
      }
    })
}

function renderHTML(data, totalCount, take) {
  let html = ``;
  let logs = '';

        $.each(data, (i) => {
            $.each(data[i].logs, (j) => {
              logs += `<p class='shadow-sm' style='padding-top:15px; font-size:13px !important;'><i class='fas fa-angle-double-right'></i> <strong>${data[i].logs[j].creator.first_name}</strong> has moved this task from <strong>${data[i].logs[j].from_stage.name}</strong> to <strong>${data[i].logs[j].to_stage.name}</strong> as on <strong>${data[i].logs[j].created_at}</strong></p>`;
            });

            html += `<div class="col-md-4 col-sm-6 col-xs-12 mt-4">
                        <article class="material-card Light-Green">
                            <h2>
                                <span>${data[i].creator.first_name}</span>
                                <strong>
                                    <i class="fas fa-user-tag"></i>
                                    Operations
                                </strong>
                            </h2>
                            <div class="mc-content shadow" style="min-height:auto">
                              <div style='padding: 7px;
                        height: 100px;
                        border-bottom: 1px solid #ccc;
                        background-color: #fff;'><h1>DEV/TEST/${data[i].id}</h1>&nbsp;&nbsp;<small>${data[i].created_at}</small></div>
                      
                                        <div class='row' style='position:relative; top:5%; font-size: 13px;
                        padding: 5px;
                        margin: 0;'>
                        <div class='col-lg-12'>
                          <h6 class='inheading text-bold'>Employee Details</h6></div>
                                <div class='col-lg-4'>
                                    <label> SAP Code </label> <br>
                                    <span class='badge badge-primary'>${data[i].creator.sap_code} </span>
                                </div>
                                <div class='col-lg-4'>
                                    <label> Designation </label> <br>
                                    <span> - </span>
                                </div>
                                <div class='col-lg-4'>
                                    <label> Department </label> <br>
                                    <span> ${(data[i].creator.departments !== undefined) ? data[i].creator.departments.department_name : '-'} </span>
                                </div>
                                <div class='col-lg-12 mt-4'><h6 class='inheading text-bold'>Requirements</h6></div>
                              
                                <div class='col-lg-4'>
                                    <label> Module </label> <br>
                                    <span> ${data[i].permission.name} </span>
                                </div>
                                <div class='col-lg-4'>
                                    <label> Tcode </label> <br>
                                    <span> ${data[i].tcode.t_code} </span>
                                </div>
                                <div class='col-lg-4'>
                                    <label> Request Stage </label> <br>
                                    <span class='badge badge-primary'>${data[i].stage.name} </span>
                                </div>
                                <div class='col-lg-12 mt-2'>
                                    <a href='javascript:void(0)' class='badge badge-primary text-white p-1 m-0' style='padding:4px !important' onclick='viewDescription("${data[i].description}")'> Read More </a>
                                </div>
                            </div>
                              
                                <div class="mc-description">
                              
                                    <h2><i class='fa fa-history'></i> Activity Logs </h2>
                                    <div class='wrapper' style='max-height: 200px; overflow:auto'>
                                        ${logs}
                                    </div>
                                </div>
                            </div>
                            <a class="mc-btn-action" onClick="trig(this)">
                                <i class="fa fa-bars"></i>
                            </a>
                            <div class="mc-footer d-none">
                                
                            </div>
                        </article>
                    </div>`;

                  });

                      html += `<div class='col-lg-12'><nav aria-label="Page navigation">
                          <ul class="pagination">
                            <li class="page-item">
                              <a class="page-link" href='javascript:void(0)' onclick="moveLeft()" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                                <span class="sr-only">Previous</span>
                              </a>
                            </li>
                            
                            ${ generatePagination(totalCount, take) }
                            <li class="page-item">
                              <a class="page-link" href='javascript:void(0)' onclick="moveRight()" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                                <span class="sr-only">Next</span>
                              </a>
                            </li>
                          </ul>
                      </nav></div>`;

                return html;
}

function viewDescription(desc) {

    $("#desc").html(`<h5>${desc}</h5>`)
    $("#desc-modal").modal('show');
}

function generatePagination(count, take) {
  let pages = count / take;
  if(count % take == 0) {
    pages = count / take;
  } else {
    pages = Math.ceil(pages);
  }

  let html = ``;
  for(let i=0; i<pages; i++) {
    html += `<li class="page-item"><a class="page-link" href="javascript:void(0)" onClick="navigate('${i+1}')">${i+1}</a></li>`
  };
  $("#noOfPages").val(pages)
  return html;
}

    function trig(obj) {
   
            var card = $(obj).parent('.material-card');
            var icon = $(obj).children('i');
            icon.addClass('fa-spin-fast');

            if (card.hasClass('mc-active')) {
              
                card.removeClass('mc-active');

                window.setTimeout(function() {
                    icon
                        .removeClass('fa-arrow-left')
                        .removeClass('fa-spin-fast')
                        .addClass('fa-bars');

                }, 800);
            } else {
                
                card.addClass('mc-active');

                window.setTimeout(function() {
                    icon
                        .removeClass('fa-bars')
                        .removeClass('fa-spin-fast')
                        .addClass('fa-arrow-left');

                }, 800);
            }
    };


        function navigate(position) {
            let take = parseInt($("#take").val());
            let newSkip = parseInt($("#skip").val());
            if(position == 1 || position < 0) {
              newSkip = 0
            } else if(position > 1) {
              newSkip = (position-1) * take;
            }
              $("#skip").val(newSkip);
              $("#position").val(position)
              loadRequests();
        }

        function moveRight() {
            var currentPos = parseInt($("#position").val());
            var noOfPages = parseInt($("#noOfPages").val());
            if(currentPos < noOfPages) {
              navigate(++currentPos)
            }
        }

        function moveLeft() {
          var currentPos = parseInt($("#position").val());
          if(currentPos>1){
            navigate(--currentPos)
          }
          
        }




</script>
@stop
