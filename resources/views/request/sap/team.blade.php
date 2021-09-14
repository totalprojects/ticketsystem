@extends('adminlte::page')

@section('title', 'SAP Request')

@section('content_header')
<meta http-equiv="x-ua-compatible" content="ie=edge">
<meta name="csrf-token" content="{{ csrf_token() }}">
  
@stop

@section('content')
    <div class="tab-content p-1">
        <div class="tab-pane active dx-viewport" id="request_block">
            <div class="demo-container">
                <div class="top-info">
                    <div class="table-heading-custom"><h4 class="right"><i class="fas fa-copy"></i> Team SAP Requests</h4></div>
                    <div class="multibtn-sec">
                       <button id="request_btn" class='custom-theme-btn'><i class='fas fa-fist-raised'></i> Raise Request</button>
                    </div>
                </div>
                <div id="request-list-div" style="height:600px"></div>
            </div>
        </div>
    </div>
  

    <!-- Request Status Modal -->
    <div class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" id="statusModal" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Request Status</h5>
              <div class="loading1 ml-2 mt-1 border border-warning rounded d-none" style="padding: 1.5px;"><i class='fas fa-spinner fa-spin'></i> Loading&#8230;</div>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
               
                    <div class="row justify-content-center">
                        <div class="col-12 col-md-12 col-lg-12 col-xl-12 text-center p-2 mb-2">
                            <div class="card">
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

    <script>
      var IS_REPORTING_MANAGER = false
      var IS_MODULE_HEAD = false
      const IS_BASIS = "{{ $moderators['IS_BASIS'] == 1 ? 'true' : 'false' }}"
      const IS_DIRECTOR = "{{ $moderators['IS_DIRECTOR'] == 1 ? 'true' : 'false' }}"
      const IS_IT_HEAD = "{{ $moderators['IS_IT_HEAD'] == 1 ? 'true' : 'false'}}"
      const IS_SAP_LEAD = "{{ $moderators['IS_SAP_LEAD'] == 1 ? 'true' : 'false' }}"
    

    /** Fetch SAP Requests */
    fetch_data();
    function fetch_data(){
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
                  data: '&take=' + take + '&skip=' + skip,
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
              pageSize: 10
          },
          columnChooser: {
              enabled: true,
              mode: "select" // or "dragAndDrop"
          },
          scrolling: {
              scroll:"virtual",
              scrollByContent: true,
          },
          sorting: {
                mode: "none"
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
              },
              {
                    caption: 'Company Names',
                    dataField:"company_name",
                    cellTemplate:(container, options) => {
                        ////console.log(options)
                        var company_names = JSON.parse(options.data.company_name);
                        var html = ``;
                        $.each(company_names, (i) => {
                            html += `<span>${company_names[i].company_name} (${company_names[i].company_code})</span>`;
                        });
                        container.append(html)
                    }
                },
                {
                    caption: 'Plant Names',
                    dataField:"plant_name",
                    cellTemplate:(container, options) => {
                        ////console.log(options)
                        var plant = JSON.parse(options.data.plant_name);
                        var html = ``;
                        $.each(plant, (i) => {
                            html += `<span>${plant[i].plant_name} (${plant[i].plant_code})</span>`;
                        });
                        container.append(html)
                    }
                },
                {
                    caption: 'Storage Location',
                    dataField:"storage_location",
                    cellTemplate:(container, options) => {
                        ////console.log(options)
                        var storage = JSON.parse(options.data.storage_location);
                        var html = ``;
                        $.each(storage, (i) => {
                            html += `<span>${storage[i].storage_description} (${storage[i].storage_code})</span>`;
                        });
                        container.append(html)
                    }
                },
                {
                    caption: 'Business Area',
                    dataField:"business_area",
                    cellTemplate:(container, options) => {
                        ////console.log(options)
                        var business = JSON.parse(options.data.business_area);
                        var html = ``;
                        $.each(business, (i) => {
                            html += `<span>${business[i].business_name} (${business[i].business_code})</span>`;
                        });
                        container.append(html)
                    }
                },

                              
                ],
                masterDetail: {
                    enabled: true,
                    template: function(container, options) {
                        $("<div>")
                            .dxDataGrid({
                                showBorders: true,
                                allowColumnResizing: true,
                                paging: false,
                                // filterRow: {
                                //     visible: true,
                                //     applyFilter: "auto"
                                // },
                                scrolling: {
                                    mode: "virtual"
                                },
                                columnChooser: {
                                    enabled: true,
                                    mode: "select" // or "select"
                                },
                                columns: [
                                    {
                                    caption: 'Module',
                                    dataField:"module",
                                    cellTemplate:(container, options) => {
                                        ////console.log(options.data.module)
                                        var modules = JSON.parse(options.data.module);
                                        //console.log(modules)
                                        var html = ``;
                                        html += `<span class='badge badge-primary'>${modules.name}</span>`;
                                        container.append(html)
                                    }
                                },
                                {
                                    caption: 'TCode',
                                    dataField:"tcode",
                                    cellTemplate:(container, options) => {
                                        ////console.log(options.data.module)
                                        var tcode = JSON.parse(options.data.tcode);
                                        //console.log(tcode)
                                        var html = ``;
                                      //  $.each(tcode, (i) => {
                                            html += `<span class='badge badge-primary'>${tcode.description} (${tcode.t_code})</span>`;
                                       // })
                                      
                                        container.append(html)
                                    }
                                },
                                {
                                    caption: 'Actions',
                                    dataField:"action",
                                    cellTemplate:(container, options) => {
                                        ////console.log(options.data.module)
                                        var action = JSON.parse(options.data.action);
                                        //console.log(action)
                                        var html = ``;
                                        $.each(action, (i) => {
                                            html += `<span class='badge badge-primary'>${action[i].name}</span> `;
                                        })
                                      
                                        container.append(html)
                                    }
                                },
                                {
                                    caption: 'Status',
                                    dataField:"status",
                                    cellTemplate:(container, options) => {
                                        ////console.log(options.data.module)
                                        var status = JSON.parse(options.data.status);
                                        var request_id = options.data.id;
                                        var status_logs = options.data.req_log;
                                        var created_at = options.data.created_at;
                                      // //console.log(status)
                                        var html = ``;
                                        html = `<a href='javascript:void(0)' onClick='loadStatusModal(${status}, "${created_at}", ${status_logs}, ${request_id})' class='btn btn-warning p-1' style='font-size:14px'><i class='fas fa-eye'></i> View</a>`;
                                        container.append(html)
                                    }
                                },
                                ],
                                dataSource: new DevExpress.data.DataSource({
                                    store: new DevExpress.data.ArrayStore({
                                        key: "request_id",
                                        data: window.subData
                                    }),
                                    filter: ["request_id", "=", options.key]
                                })
                            }).appendTo(container);
                    }
                }
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
function loadStatusModal(status,created_at, logs, request_id) {

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
  toastr.info('Processing...');
  var remarks = $("#r_"+request_id).val();
  if(remarks.length == 0) {
    toastr.error('Remarks field is mandatory');
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
    },
    success:(r) => {
      console.log(r)
      $(obj).prop('disabled', false);
      toastr.success('The status was changed to approved');
     loadStatusModal(status, r.created_at, r.logs, request_id);
      //console.log(r);
    }
  })
}




       
    
    </script>
@stop