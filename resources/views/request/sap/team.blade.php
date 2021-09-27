@extends('adminlte::page')

@section('title', 'SAP Team Request')

@section('content_header')
<meta http-equiv="x-ua-compatible" content="ie=edge">
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
  #div_tree ul ul ul li { 
  
      display: inline-block !important;
  }     
</style> 
@stop

@section('content')
    <div class="tab-content p-1">
        <div class="tab-pane active dx-viewport" id="request_block">
            <div class="demo-container">
                <div class="top-info">
                    <div class="table-heading-custom"><h4 class="right"><i class="fas fa-copy"></i> Team SAP Requests</h4></div>
                    <div class="multibtn-sec">
                       <button id="request_btn" class='custom-theme-btn'><i class='fas fa-fist-raised'></i> Raise Request for Team Member</button>
                    </div>
                </div>
                <div class="container">
                  <form id="srch-frm">
                    <div class="row">
                        <div class="col-lg-12">
                            <label>Search</label>
                        </div>
                        <div class="col-lg-3">
                            <input type="text" name="requestID" class="form-control" placeholder="Request ID">
                        </div>
                        <div class="col-lg-3">
                          <input type="text" name="username" class="form-control" placeholder="Username">
                        </div>
                        <div
                         class="col-lg-3">
                            <input type="text" name="date" id='date' class="form-control" placeholder="Creation Date">
                        </div>
                        <div class="col-lg-3">
                            <button type="submit" id="srch-btn" class="btn btn-primary"><i class='fa fa-search'></i> Search</button>
                            <button id="clear-filter" class="btn btn-primary"><i class='fa fa-sync'></i> Clear</button>
                        </div>
                    </div>
                </form>
                </div>
                <div id="request-list-div" style="height:600px"></div>
            </div>
        </div>
    </div>
  
    <!-- Team Request Modal -->
        <div class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" id="requestModal" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-xl">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">SAP Team Requests</h5>
                <div class="loading1 ml-2 mt-1 border border-warning rounded d-none" style="padding: 1.5px;"><i class='fas fa-spinner fa-spin'></i> Loading&#8230;</div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                 
                      <div class="row">
                          <div class="col-12 col-md-12 col-lg-12 col-xl-12 text-center p-2 mb-2 sap-req-form">
                              <div class="card">
                                  <h2 id="heading">SAP Request Form</h2>
                                  <p>Fill all form field to go to next step</p>
                                  <form id="msform" method="post">
                                      <!-- progressbar -->
                                      <ul id="progressbar">
                                          <li class="active" id="account"><i class="fas fa-user"></i><strong>Step 1</strong></li>
                                          <li id="personal"><i class="far fa-address-card"></i><strong>Step 2</strong></li>
                                          <li id="payment"><i class="fas fa-store-alt"></i><strong>Step 3</strong></li>
                                          <li id="confirm"><i class="fas fa-building"></i><strong>Step 4</strong></li>
                                          <li id="confirm2"><i class="fas fa-tasks"></i><strong>Step 5</strong></li>
                                      </ul>
                                      <div class="progress">
                                          <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                                      </div> <br> <!-- fieldsets -->
                                      
                                      <fieldset>
                                          <div class="form-card">
                                              <div class="row">
                                                  <div class="col-7">
                                                      <h2 class="fs-title">Basic Information:</h2>
                                                  </div>
                                                  <div class="col-5">
                                                      <h2 class="steps">Step 1 - 5</h2>
                                                  </div>
  
                                              </div> 
                                              <div class="row">
                                                  <div class="col-lg-3 pt-2">
                                                    <label for="company_name">For User</label>
                                                      <input type="hidden" id="isByRM" name="isByRM" value="1">
                                                        <select name="user_id" id="user_id" placeholder="Select Employee" class="form-control select2bs4">
                                                            <option value="">--SELECT Employee--</option>
                                                            @foreach($reporties as $reporty)
                                                                <option value="{{ $reporty['id'] }}">{{ $reporty['name'] }}</option>
                                                            @endforeach
                                                        </select>
                                                  </div>
                                                  <div class="col-lg-3 pt-2">
                                                      <label for="company_name">Company Name</label>
                                                          <select name="company_name[]" id="company_name" placeholder="Select Companies" class="form-control select2bs4" multiple>
                                                              <option value="">--SELECT COMPANY--</option>
                                                              @foreach($companies as $company)
                                                                  <option value="{{ $company->company_code }}">{{ $company->company_name }} ({{ $company->company_code }})</option>
                                                              @endforeach
                                                          </select>
                                                  </div>
                                                  <div class="col-lg-3 pt-2">
                                                      <label for="plant_name">Plant Name </label>
                                                          <select name="plant_name[]" id="plant_id" data-placeholder="Select Plant Name" class="form-control select2bs4" multiple>
                                                              <option value="">--SELECT PLANT--</option>
                                                          </select>
                                                  </div>
                                                  <div class="col-lg-3 pt-2">
                                                      <label for="storage_location">Storage Location </label>
                                                          <select name="storage_location[]"  id="storage_id" data-placeholder="Select Storage Location" class="form-control select2bs4" multiple>
                                                              <option value=""></option>
                                                          </select>
                                                  </div>
                                                  <div class="col-lg-3 pt-2">
                                                      <label for="business_area"> Business Area </label>
                                                          <select name="business_area[]"  id="business_location" data-placeholder="Select Business Area" class="form-control select2bs4" multiple>
                                                              <option value=""></option>
                                                              @foreach($business as $b)
                                                                  <option value="{{ $b->business_code }}">{{ $b->business_name }} </option>
                                                              @endforeach
                                                          </select>
                                                  </div>
                                              </div>
                                          </div> 
                                          <input type="button" name="next" class="next action-button" value="Next" />
                                      </fieldset>
                                      <!-- Select Role -->
                                      <fieldset>
                                          <div class="form-card">
                                              <div class="row">
                                                  {{-- <div class="col-7">
                                                      <h2 class="fs-title">Select Role:</h2>
                                                  </div> --}}
                                                  <div class="col-5">
                                                      <h2 class="steps">Step 2 - 5</h2>
                                                  </div>
                                              </div>
                                              <div class="row justify-content-center">
                                                 
                                                  <div class="col-lg-6 pt-2">
                                                      
                                                      <label for="sales_org">Role </label>
                                                      <span><small>(This option is not mandatory)</small></span>
                                                          <select name="role"  id="role" data-placeholder="Select Role" class="form-control select2bs4">
                                                              <option value=""></option>
                                                              @foreach($roles as $role)
                                                                  <option value="{{ $role->id }}"> {{  $role->name }}</option>
                                                              @endforeach
                                                          </select>
                                                  </div>
                                                 
                                              </div> 
                                               
                                          </div> <input type="button" name="next" class="next action-button" value="Next" /> <input type="button" name="previous" class="previous action-button-previous" value="Previous" />
                                      </fieldset>
                                      <fieldset>
                                          <div class="form-card">
                                              <div class="row">
                                                  <div class="col-7">
                                                      <h2 class="fs-title">Select Type:</h2>
                                                  </div>
                                                  <div class="col-5">
                                                      <h2 class="steps">Step 3 - 5</h2>
                                                  </div>
                                              </div>
                                              <div class="row">
                                                  <div class="col-lg-12">
                                                      <h5>Select any one option or both to continue</h5>
                                                  </div>
                                                  <div class="col-lg-4 pt-2">
                                                      <label for="sales_org">Sales Organization </label>
                                                          <select name="sales_org[]"  id="sales_org" data-placeholder="Select Sales Organization" class="form-control select2bs4" multiple>
                                                              <option value=""></option>
                                                          </select>
                                                  </div>
                                                  <div class="col-lg-4 pt-2">
                                                      <label for="purchase_org"> Purchase Organization </label>
                                                          <select name="purchase_org[]"  id="purchase_org" data-placeholder="Select Purchase Organization" class="form-control select2bs4" multiple>
                                                              <option value=""></option>
                                                              @foreach($po as $p)
                                                                  <option value="{{ $p->id }}">{{ $p->po_name }} ({{ $p->po_code }}) </option>
                                                              @endforeach
                                                          </select>
                                                  </div>  
                                                  <div class="col-lg-4 pt-2">
                                                      <label for="action_type">Action</label>
                                                      <br>
                                                     <input type="radio" name="action_type" value="cr1"> Create &amp; 1<sup>st</sup> Release &nbsp;
                                                     <input type="radio" name="action_type" value="c"> Create &nbsp;
                                                     <input type="radio" name="action_type" value="r"> Release &nbsp;
                                                  </div>   
                                                     
                                              </div> 
                                               
                                          </div> <input type="button" name="next" class="next action-button" value="Next" /> <input type="button" name="previous" class="previous action-button-previous" value="Previous" />
                                      </fieldset>
                                     
                                      <fieldset>
                                          <div class="form-card">
                                              <div class="row">
                                                  <div class="col-7">
                                                      <h2 class="fs-title">Other Information:</h2>
                                                  </div>
                                                  <div class="col-5">
                                                      <h2 class="steps">Step 4 - 5</h2>
                                                  </div>
                                              </div>
                                              <div class="row">
                                                  <div class="col-lg-4 pt-2">
                                                      <label for="division">Division </label>
                                                          <select name="division[]"  id="division" data-placeholder="Select Division" class="form-control select2bs4" multiple>
                                                              <option value=""></option>
                                                              @foreach($divisions as $division)
                                                                  <option value="{{ $division->division_code }}">{{ $division->division_description }} </option>
                                                              @endforeach
                                                          </select>
                                                  </div>
                                                  <div class="col-lg-4 pt-2">
                                                      <label for="distribution_channel">Distribution Channel </label>
                                                          <select name="distribution_channel[]"  id="distribution_channel" data-placeholder="Select Distribution Channel" class="form-control select2bs4" multiple>
                                                              <option value=""></option>
                                                              @foreach($distributors as $distributor)
                                                                  <option value="{{ $distributor->distribution_channel_code }}">{{ $distributor->distribution_channel_description }} </option>
                                                              @endforeach
                                                          </select>
                                                  </div>
                                                  <div class="col-lg-4 pt-2">
                                                      <label for="sales_office"> Sales Office </label>
                                                          <select name="sales_office[]"  id="sales_office" data-placeholder="Select Sales Office" class="form-control select2bs4" multiple>
                                                              <option value=""></option>
                                                          </select>
                                                  </div>
                                                  <div class="col-lg-4 pt-2">
                                                      <label for="purchase_group"> Purchase Group </label>
                                                          <select name="purchase_group[]"  id="purchase_group" data-placeholder="Select Purchase Group" class="form-control select2bs4" multiple>
                                                              <option value=""></option>
                                                              @foreach($pg as $p)
                                                                  <option value="{{ $p->id }}">{{ $p->pg_description }} ({{ $p->pg_code }}) </option>
                                                              @endforeach
                                                          </select>
                                                  </div> 
                                                  <div class="col-lg-4 pt-2">
                                                      <label for="po_release"> PO Release </label>
                                                          <select name="po_release[]"  id="po_release" data-placeholder="Select PO Release" class="form-control select2bs4" multiple>
                                                              <option value=""></option>
                                                              @foreach($po_release as $p)
                                                                  <option value="{{ $p->id }}">{{ $p->rel_description }} ({{ $p->rel_code }})</option>
                                                              @endforeach
                                                          </select>
                                                  </div>
  
                                              </div>
                                          </div> 
                                          <input type="button" name="next" class="next action-button" value="Next" /> <input type="button" name="previous" class="previous action-button-previous" value="Previous" />
                                      </fieldset>
                                     
                                      <fieldset>
                                          <div class="form-card">
                                              <div class="row">
                                                  <div class="col-7">
                                                      <h2 class="fs-title">Select T-Codes:</h2>
                                                  </div>
                                                  <div class="col-5">
                                                      <h2 class="steps">Step 5 - 5</h2>
                                                  </div>
                                                 
                                              </div> 
                                              <h2 class="purple-text text-center"><strong>Final Step</strong></h2> <br>
                                              <div class="row m-2">
                                                  <div class="col-lg-8">
                                                       <label for="searchCustom">
                                                           <small>Did not find the tcode?</small>
                                                       </label><br>
                                                       <input style="width:auto; display:inline-block" type="text" name="ctcode" id='ctcode' placeholder="Enter Exact T Code" class="form-control">
                                                       &nbsp; <a id="search_tcode" class="btn btn-primary">Search</a>
                                                  </div>
                                               </div>
                                              <div class="row">
                                                  <div class="col-lg-12" id="modules_tcodes_block">
                                                      <div id="div_tree"></div>
                                                  </div>
                                              </div> 
                                          </div>
                                          <input type="button" name="next" class="next action-button" value="Next" /> 
                                          <input type="button" name="previous" class="previous action-button-previous" value="Previous" />
                                      </fieldset>
                                      <fieldset>
                                          <div class="form-card">
                                              <div class="row">
                                                  <div class="col-12">
                                                      <h2 class="fs-title text-center">You may review all your selections</h2>
                                                  </div>
                  
                                              </div> <br><br>
                                              <h2 class="purple-text text-center"><strong>Your selections below</strong></h2> <br>
                                              <div class="row justify-content-center">
                                                  <div class="col-lg-12">
                                                     <div id="review_selections" class="scrollable-table" style="overflow-y: auto; cursor: grab;"></div>
                                                  </div>
                                              </div> 
                                              
                                          </div>
                                          <input type="button" name="next" id="finalSubmit" class="next action-button" value="Submit" /> 
                                          <input type="button" name="previous" class="previous action-button-previous" value="Previous" />
                                         
                                      </fieldset>
                                      <fieldset>
                                          <div class="form-card">
                                              <div class="row">
                                                  <div class="col-12">
                                                      <h2 class="fs-title text-center">All the steps are complete!</h2>
                                                  </div>
                  
                                              </div> <br><br>
                                              <h2 class="purple-text text-center"><strong>Your request has been generated</strong></h2> <br>
                                              <div class="row justify-content-center">
                                                  <div class="col-lg-12">
                                                      <h5 class="text-center">Please wait for the approval from concerned team</h5>
                                                  </div>
                                              </div> 
                                              
                                          </div>
                                          <input type="button" name="close-modal" class="btn btn-secondary" value="Close" onclick="$('#requestModal').modal('hide')" />
                                      </fieldset>
                                  </form>
                              </div>
                          </div>
                      </div>
                 
              </div>
            </div>
          </div>
      </div>
  
    <!-- Request Status Modal -->
    <div class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" id="statusModal" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Request Status of <span id="fetchRequestID"></span></h5>
              <div class="loading1 ml-2 mt-1 border border-warning rounded d-none" style="padding: 1.5px;"><i class='fas fa-spinner fa-spin'></i> Loading&#8230;</div>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
               
                    <div class="row justify-content-center">
                        <div class="col-12 col-md-12 col-lg-12 col-xl-12 text-center p-2 mb-2">
                            <div class="card">
                              <div id="renderDetails"></div>
                                {{-- <h2 id="heading">Request Status</h2> --}}
                                <div id="drop_status"></div>
                               
                            </div>
                        </div>
                    </div>
               
            </div>
          </div>
        </div>
    </div>
    @stop

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/custom/main.css') }}">
@stop

@section('js')
    <script src="{{ asset('assets/js/requestform.js') }}"></script>
    <script>
      var IS_REPORTING_MANAGER = false
      var IS_MODULE_HEAD = false
      const IS_BASIS = "{{ $moderators['IS_BASIS'] == 1 ? 'true' : 'false' }}"
      const IS_DIRECTOR = "{{ $moderators['IS_DIRECTOR'] == 1 ? 'true' : 'false' }}"
      const IS_IT_HEAD = "{{ $moderators['IS_IT_HEAD'] == 1 ? 'true' : 'false'}}"
      const IS_SAP_LEAD = "{{ $moderators['IS_SAP_LEAD'] == 1 ? 'true' : 'false' }}"
    
      $("#srch-btn").on('click', (e) => {
            e.preventDefault();
            var requestID = $("input[name='requestID']").val();
            var creationDate = $("input[name='date']").val(); 
            var username = $("input[name='username']").val(); 
            var params = {
                'requestID': requestID,
                'creationDate': creationDate,
                'username' : username
            }
            fetch_data(params);
      })

      
    $("#request_btn").on('click', () => {
      $("#requestModal").modal('show');
    })
    /** Fetch SAP Requests */
    fetch_data();
    function fetch_data(params = []){
      var requestId = params.requestID !== undefined ? params.requestID : '';
      var creationDate = params.creationDate !== undefined ? params.creationDate : '';
      var username = params.username !== undefined ? params.username : '';
      function isNotEmpty(value) {
          return value !== undefined && value !== null && value !== "";
      }
      // var jsonData = [];
      var jsonData = new DevExpress.data.CustomStore({
          key: "request_id",
          load: function (loadOptions) {
              // //console.log(loadOptions)
              var deferred = $.Deferred(),
                  args = {};
              [
                  "skip",
                  "take",
                  "requireTotalCount",
                  "sort",
                  "filter",
              ].forEach(function (i) {
                  if (i in loadOptions && isNotEmpty(loadOptions[i]))
                      args[i] = JSON.stringify(loadOptions[i]);
              })

              let take = loadOptions.take
              let skip = loadOptions.skip
              var dataSet = []
              var url = "{{ route('fetch.team.request') }}"
              $.ajax({
                  url: url,
                  type: 'GET',
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                  },
                  dataType: "json",
                  data: '&username=' + username +'&requestID=' + requestId + '&creationDate=' + creationDate + '&take=' + take + '&skip=' + skip,
                  complete: function (result) {
                      var res = result.responseJSON;
                      var data = res.data;
                      window.subData = res.subArray;
                      //console.log(res)
                      deferred.resolve(data, {
                          totalCount: res.totalCount,
                      });
                      deferred.resolve(window.subData)
                      
                  },
                  error: function () {
                      deferred.reject("Data Loading Error");
                  },
                  //timeout: 2000000
              });
              return deferred.promise();
          }
      });
      $("#request-list-div").dxDataGrid({
          dataSource: jsonData,
          KeyExpr: "request_id",
          showBorders: true,
          showRowLines: true,
          rowAlternationEnabled: true,
          allowColumnResizing: true,
          columnAutoWidth:true,
          columnHidingEnabled:false,
          loadPanel: {
            //indicatorSrc: `${ASSET_URL}/assets/images/loader4.gif`,
            text: "Loading...",
            showPane: true,
          },
          remoteOperations: {
              filtering: true,
              paging: true,
              sorting: true,
              groupPaging: true,
              grouping: true,
              summary: true
          },
          paging: {
              enabled: true,
              pageSize: 25
          },
          columnChooser: {
              enabled: true,
              mode: "select" // or "dragAndDrop"
          },
          scrolling: {
              scroll:"virtual",
              scrollByContent: true,
          },
          wordWrapEnabled: false,
          columns: [
           {
            caption:"Req No.",
            dataField:"request_id",
           },
           {
            caption:"User Name",
            dataField:"user_name",
            visible:true,
           },
           {
                caption: 'Company Names',
                dataField:"company_name",
                cellTemplate:(container, options) => {
                    container.append(options.data.company_name)
                }
           },
            {
                caption: 'Department',
                dataField:"department",
            },
            {
                caption: 'Request Date',
                dataField:"created_at",
            },
            {
                caption:"View Status",
                dataField:"status",
                cellTemplate:(container, options) => {

                    var status = JSON.parse(options.data.status);
                    var status_logs = options.data.req_log;
                    var created_at = options.data.created_at;
                    var req_id = options.data.id;
                    console.log('req ud' +req_id)
                    var html = ``;
                    
                    html = `<a href='javascript:void(0)' onClick='loadStatusModal(${status}, "${created_at}", ${status_logs}, ${req_id}, ${JSON.stringify(options.data)})' class='btn btn-warning p-1' style='font-size:14px'><i class='fas fa-eye'></i> View</a>`;
                    container.append(html)
                }
            },           
       ]

      });
    }


window.data = '';

function fetchStages(request_id, logs, created_at) {

  var url = "{{ route('fetch.stages') }}";
  // initial stage
  var stages = [0]
  
  $.ajax({
      url: url,
      type: 'GET',
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
      },
      dataType: "json",
      data: {request_id},
      complete: function (result) {
          var res = result.responseJSON;
          var result = res.data;
          stage = result;
          /* Reporting Manager */
          const IS_RM = res.IS_RM;
          /* Module Head */
          const IS_MH = res.IS_MH;
      
          $.each(stage, (i) => {
            stages.push(stage[i]);
          })

          renderApprovalStages(stages, logs, created_at, request_id, IS_RM, IS_MH)

      },
      error: function (e) {
        //console.log(e)
        toastr.error('Something went wrong')
      },
    });

    return true
}
function loadStatusModal(status,created_at, logs, request_id, alldata = []) {

var requestID = (alldata.request_id !== undefined) ? alldata.request_id : request_id;

$("#fetchRequestID").text(requestID);
var html_table = `<table class='table table-bordered'>
    <thead>
        <th>Plant Code</th>
        <th>SO</th>
        <th>PO</th>
        <th>Module</th>
        <th>Tcode</th>
        <th>Actions</th>
    </thead>
    <tbody>
    <tr>
        <td>${alldata.plant_name}</td>
        <td>${alldata.sales_org}</td> 
        <td>${alldata.purchase_org}</td>
        <td>${alldata.module}</td>
        <td>${alldata.tcode}</td>
        <td>${alldata.action}</td>  
    </tr>            
`;
html_table += "</tbody></table>";
$("#renderDetails").html(html_table);
fetchStages(request_id, logs, created_at, status);

}


function renderApprovalStages(stages, logs, created_at, request_id, IS_RM, IS_MH) {
  var pointer = 0;
  var html = '<section> <div class="row justify-content-center orderstatus-container">  <div class="medium-12 columns">';
  //console.log(stages)
  $.each(stages, (i) => {

    if(stages[i] == 0) {

      html += `<div class="orderstatus done">
                  <div class="orderstatus-check"><span class="orderstatus-number">${i+1}</span></div>
                  <div class="orderstatus-text">
                    <time>${created_at}</time>
                    <p>Request was placed</p>
                  </div>
                </div>`;

    } else {

      let approval_stages = {!!  json_encode($approval_stages) !!}

      let datetime = "N/A";

      let addClass = "";

      let status_text = "Pending Approval";

      pointer = i - 1;

      if(logs[i-1] !== undefined) {

        if(stages[i] == logs[pointer].approval_stage) {

            addClass = `done`;

            datetime = logs[pointer].created_at;

            $.each(approval_stages, (x) => {
              if(approval_stages[x] !== undefined) {
                if(logs[pointer].approval_stage == approval_stages[x].id) {

                  if(logs[pointer].status == 1) {
                    status_text = `Approved By <br> ${logs[pointer].created_by} (${approval_stages[x].approval_type})`;
                  } else {
                    addClass = `rejected`;
                    status_text = `Rejected By <br> ${logs[pointer].created_by} (${approval_stages[x].approval_type})`;
                  }

                  status_text += `<br>Remarks: `+logs[pointer].remarks;
                  
                }
              }
            });
        } 

        } 
        else {

                if(stages[i] == approval_stages[stages[i] - 1].id) {

                  status_text = `Pending Approval from <br> (${approval_stages[stages[i] - 1].approval_type})`;

                  let type = approval_stages[stages[i] - 1].approval_type.replace(" ", "_").toUpperCase();
                  console.log(IS_RM)
                  switch(type) {

                    case 'REPORTING_MANAGER':
                      (IS_RM === true) ? status_text += `<br> <a href='javascript:void(0)' class='btn btn-success p-1 text-white' onClick='approve(this,${approval_stages[stages[i] - 1].id},${request_id},1)'><i class='fas fa-check'></i> Approve</a>
                      <a href='javascript:void(0)' class='btn btn-danger p-1 text-white' onClick='approve(this,${approval_stages[stages[i] - 1].id},${request_id},0)'><i class='fas fa-times'></i> Reject</a>
                      <br>
                      <textarea id="r_${request_id}" name='approval_remarks' class='form-control pt-2' placeholder='Enter Remarks'></textarea>
                      ` : '';
                      break;
                    case 'MODULE_HEAD':
                      (IS_MH === true && logs[i-2] !== undefined && logs[i-2].status == 1) ? status_text += `<br> <a href='javascript:void(0)' onClick='approve(this,${approval_stages[stages[i] - 1].id},${request_id},1)' class='btn btn-success p-1 text-white'><i class='fas fa-check'></i> Approve</a>
                      <a href='javascript:void(0)' class='btn btn-danger p-1 text-white' onClick='approve(this,${approval_stages[stages[i] - 1].id},${request_id},0)'><i class='fas fa-times'></i> Reject</a>
                      <br>

                      <textarea id="r_${request_id}" name='approval_remarks' class='form-control pt-2' placeholder='Enter Remarks'></textarea>
                      ` : '';
                      break;
                    case 'SAP_LEAD':
                      (IS_SAP_LEAD === 'true' && logs[i-2] !== undefined && logs[i-3] !== undefined && logs[i-2].status == 1 && logs[i-3].status == 1) ? status_text += `<br> <a href='javascript:void(0)' onClick='approve(this,${approval_stages[stages[i] - 1].id},${request_id},1)' class='btn btn-success p-1 text-white'><i class='fas fa-check'></i> Approve</a>
                      <a href='javascript:void(0)' class='btn btn-danger p-1 text-white' onClick='approve(this,${approval_stages[stages[i] - 1].id},${request_id},0)'><i class='fas fa-times'></i> Reject</a>
                      <textarea id="r_${request_id}" name='approval_remarks' class='form-control pt-2' placeholder='Enter Remarks'></textarea>
                      ` : '';
                      break;
                    case 'DIRECTOR':
                      (IS_DIRECTOR === 'true' && logs[i-2] !== undefined && logs[i-3] !== undefined && logs[i-2].status == 1 && logs[i-3].status == 1) ? status_text += `<br> <a href='javascript:void(0)' onClick='approve(this,${approval_stages[stages[i] - 1].id},${request_id},1)' class='btn btn-success p-1 text-white'><i class='fas fa-check'></i> Approve</a>
                      <a href='javascript:void(0)' class='btn btn-danger p-1 text-white' onClick='approve(this,${approval_stages[stages[i] - 1].id},${request_id},0)'><i class='fas fa-times'></i> Reject</a>
                      <br><textarea id="r_${request_id}" name='approval_remarks' class='form-control pt-2' placeholder='Enter Remarks'></textarea>
                      ` : '';
                      break;
                    case 'IT_HEAD':
                      (IS_IT_HEAD === 'true' && logs[i-2] !== undefined && logs[i-3] !== undefined && logs[i-2].status == 1 && logs[i-3].status == 1) ? status_text += `<br> <a href='javascript:void(0)' onClick='approve(this,${approval_stages[stages[i] - 1].id},${request_id},1)' class='btn btn-success p-1 text-white'><i class='fas fa-check'></i> Approve</a>
                      <a href='javascript:void(0)' class='btn btn-danger p-1 text-white' onClick='approve(this,${approval_stages[stages[i] - 1].id},${request_id},0)'><i class='fas fa-times'></i> Reject</a> <br>
                      <textarea id="r_${request_id}" name='approval_remarks' class='form-control pt-2' placeholder='Enter Remarks'></textarea>` : '';
                      break;
                    case 'BASIS':
                      (IS_BASIS === 'true' && logs[i-2] !== undefined && logs[i-3] !== undefined && logs[i-2].status == 1 && logs[i-3].status == 1) ? status_text += `<br> <a href='javascript:void(0)' onClick='approve(this,${approval_stages[stages[i] - 1].id},${request_id},1)' class='btn btn-success p-1 text-white'><i class='fas fa-check'></i> Approve</a>
                      <a href='javascript:void(0)' class='btn btn-danger p-1 text-white' onClick='approve(this,${approval_stages[stages[i] - 1].id},${request_id},0)'><i class='fas fa-times'></i> Reject</a>
                      <br><textarea id="r_${request_id}" name='approval_remarks' class='form-control pt-2' placeholder='Enter Remarks'></textarea>` : '';
                      break;
                     default:
                     status_text += '';
                  
                  }
                   
                }
      }

      html += `<div class="orderstatus ${addClass}">
                    <div class="orderstatus-check"><span class="orderstatus-number">${i+1}</span></div>
                    <div class="orderstatus-text">
                      <time>${datetime}</time>
                      <p>${status_text}</p>
                    </div>
                  </div>`;

    }

  });

    html += "</div></div></section>";


    $("#statusModal").modal('show');
    $("#drop_status").html(html)


}

function approve(obj, approver, request_id, status = 1) {
  $(obj).prop('disabled', true);
  //toastr.info('Processing...');
  customLoader(1);
  var remarks = $("#r_"+request_id).val();
  if(remarks.length == 0) {
    toastr.error('Remarks field is mandatory');
    customLoader(0);
    return false;
  }
  $.ajax({
    url:`{{  route('approve.sap.request') }}`,
    data:{request_id:request_id,approver,remarks,status},
    type:"POST",
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
    },
    error:(r) => {
      //console.log(r)
      customLoader(0);
    },
    success:(r) => {
      customLoader(0);
      console.log(r)
      $(obj).prop('disabled', false);
      toastr.success('The status was changed to approved');
     loadStatusModal(status, r.created_at, r.logs, request_id);
     setTimeout(()=> {
      $("#statusModal").modal('hide');
     },2500);
     fetch_data()
      //console.log(r);
    }
  })
}
  
    
    </script>
@stop