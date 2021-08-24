@extends('adminlte::page')

@section('title', 'SAP Request')

@section('content_header')
<meta http-equiv="x-ua-compatible" content="ie=edge">
<meta name="csrf-token" content="{{ csrf_token() }}">
  
@stop

@section('content')
<style>




/** Approval Status */
.orderstatus {
  color: #939393;
  display: block;
  padding: 1em 0;
  position: relative;
  text-align: center;
  min-height: 150px;
}

.orderstatus.done:before {
  background: #32841f;
  
}
.orderstatus:before {
  /* content: '';
  height: 100%;
  position: absolute;
  left: 50%;
  width: 2px;
  background: #939393;
  margin: 0 25px; */
    content: '';
    height: 100%;
    position: absolute;
    left: 47%;
    width: 1.5px;
    background: #939393;
    margin: 0 25px;
}

.orderstatus:last-child:before {
  height: 0;
}

.orderstatus.done {
  color: #333;
}

@media only screen and (max-width: 40em) {
  .orderstatus {
    text-align: left;
  }
  .orderstatus:before {
    left: 0;
  }
  .orderstatus .orderstatus-text {
    left: 0;
    width: 100%;
  }
}

.orderstatus-text {
  position: relative;
  width: 100%;
  left: 50%;
  text-align: left;
  padding-left: 60px;
}

@media only screen and (min-width: 40em) {
  .orderstatus:nth-child(2n) .orderstatus-text {
    left: -130px;
    text-align: right;
    padding-right: 20px;
  }
}

.orderstatus-container {
  padding: 2em 0;
}

.orderstatus time {
  display: block;
  font-size: 1em;
  border:1px solid grey;
  padding:5px;
  border-radius: 5px;
  color: #939393;
}

.orderstatus.done time {
  color: #368d22;
}

@media only screen and (max-width: 40em) {
  .orderstatus-container {
    text-align: center;
  }
}

.orderstatus-check {
  /* font-family: "Helvetica", Arial, sans-serif;
  border: 2px solid #939393;
  width: 50px;
  height: 50px;
  display: inline-block;
  text-align: center;
  line-height: 48px;
  border-radius: 50%;
  margin-bottom: 0.5em;
  background: #fff;
  z-index: 2;
  position: absolute;
  color: #939393;
  left: 50%; */
    border: 2px solid #939393;
    width: 35px;
    height: 35px;
    display: inline-block;
    text-align: center;
    line-height: 31px;
    border-radius: 50%;
    margin-bottom: 0.5em;
    background: #fff;
    z-index: 2;
    position: absolute;
    color: #939393;
    left: 50%;
}

.done .orderstatus-check {
  color: #368d22;
  border-color: #368d22;
}

@media only screen and (max-width: 40em) {
  .orderstatus-check {
    left: 0;
  }
}

@keyframes glowing {
  0% { box-shadow: 0 0 -10px #368d22; }
  40% { box-shadow: 0 0 20px #368d22; }
  60% { box-shadow: 0 0 20px #368d22; }
  100% { box-shadow: 0 0 -10px #368d22; }
}

.done .orderstatus-check  {
  animation: glowing 2500ms infinite;
}

.orderstatus-active {
  text-align: center;
  position: relative;
  left: 25px;
  top: 20px;
  color: #939393;
}

@media only screen and (max-width: 40em) {
  .orderstatus-active {
    display: none;
  }
}
</style>
    <div class="tab-content p-1">
        {{-- <div class="loading loadr d-none">Loading&#8230;</div> --}}
        <div class="font-weight-bold m-2 font-italic text-primary"><h4 class="right"><i class="fas fa-copy"></i> Team SAP Requests</h4><br></div>
        <div class="tab-pane active dx-viewport" id="request_block">
            <div class="demo-container p-3">
                <button id="request_btn" class='btn btn-primary p-1 d-none'><i class="fas fa-fist-raised"></i> Raise Request</button>
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

      const IS_MODULEHEAD = false;
      const IS_BASIS = false;


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
           // console.log(loadOptions)
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
                   console.log(res)
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
                    //console.log(options)
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
                    //console.log(options)
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
                    //console.log(options)
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
                    //console.log(options)
                    var business = JSON.parse(options.data.business_area);
                    var html = ``;
                    $.each(business, (i) => {
                        html += `<span>${business[i].business_name} (${business[i].business_code})</span>`;
                    });
                    container.append(html)
                }
            },
            {
                  caption: 'Status',
                  dataField:"status",
                  cellTemplate:(container, options) => {
                      //console.log(options.data.module)
                      var status = JSON.parse(options.data.status);
                      var request_id = options.data.req_int;
                      var status_logs = options.data.req_log;
                      var created_at = options.data.created_at;
                     // console.log(status)
                      var html = ``;
                      html = `<a href='javascript:void(0)' onClick='loadStatusModal(${status}, "${created_at}", ${status_logs}, ${request_id})' class='btn btn-warning p-1' style='font-size:14px'><i class='fas fa-eye'></i> View</a>`;
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
                                    //console.log(options.data.module)
                                    var modules = JSON.parse(options.data.module);
                                    console.log(modules)
                                    var html = ``;
                                    html += `<span class='badge badge-primary'>${modules.name}</span>`;
                                    container.append(html)
                                }
                            },
                            {
                                caption: 'TCode',
                                dataField:"tcode",
                                cellTemplate:(container, options) => {
                                    //console.log(options.data.module)
                                    var tcode = JSON.parse(options.data.tcode);
                                    console.log(tcode)
                                    var html = ``;
                                    $.each(tcode, (i) => {
                                        html += `<span class='badge badge-primary'>${tcode[i].description} (${tcode[i].t_code})</span>`;
                                    })
                                   
                                    container.append(html)
                                }
                            },
                            {
                                caption: 'Actions',
                                dataField:"action",
                                cellTemplate:(container, options) => {
                                    //console.log(options.data.module)
                                    var action = JSON.parse(options.data.action);
                                    console.log(action)
                                    var html = ``;
                                    $.each(action, (i) => {
                                        html += `<span class='badge badge-primary'>${action[i].name}</span> `;
                                    })
                                   
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


function loadStatusModal(status,created_at, logs, request_id) {

  console.log(logs);
  var stages = [0, 1, 2, 3];
  var pointer = 0;
  var html = '<section> <div class="row justify-content-center orderstatus-container">  <div class="medium-12 columns">';
  $.each(stages, (i) => {

    if(stages[i] == 0) {

      html += `<div class="orderstatus done">
                  <div class="orderstatus-check"><span class="orderstatus-number">${stages[i]+1}</span></div>
                  <div class="orderstatus-text">
                    <time>${created_at}</time>
                    <p>Your Request was placed</p>
                  </div>
                </div>`;

    } else {

      let datetime = "N/A";
      let addClass = "";
      let status_text = "Not Approved";
      pointer = i - 1;
      if(logs[i-1] !== undefined) {
        //console.log('log found')
        if(stages[i] == logs[pointer].approval_stage) {
            addClass = `done`;
            datetime = logs[pointer].created_at;
            if(logs[pointer].approval_stage == 1) {
              status_text = `Approved By <br> ${logs[pointer].created_by} (RM)`;
            } else if(logs[pointer].approval_stage == 2) {
              status_text = `Approved By <br> ${logs[pointer].created_by} (Module head)`;
            }
          } else if(logs[pointer].approval_stage == 3) {
              status_text = `Approved By <br> ${logs[pointer].created_by} (BASIS)`;
          }
        } else {

          if(stages[i] == 1) {
              status_text = `Not Approved By RM <br><a href='javascript:void(0)' id='rm_approve_request_btn' onClick='approveRequestByRM(${request_id},1)' class='btn btn-success p-1 text-white'><i class='fas fa-check'></i> Approve</a> &nbsp; <a href='javascript:void(0)' id='rm_reject_request_btn' onClick='approveRequestByRM(${request_id},-1)' class='btn btn-danger p-1 text-white'><i class='fas fa-times'></i> Reject</a>`;
          } else if(stages[i] == 2) {

            status_text = `Not Approved By Module head(s)`;
            if(IS_MODULEHEAD === true) {
               status_text += `<br> <a href='javascript:void(0)' id='mh_approve_request_btn' class='btn btn-success p-1 text-white'><i class='fas fa-check'></i> Approve</a>`;
            }
          } else if(stages[i] == 3) {

            status_text = `Not Approved By BASIS`;
            if(IS_BASIS === true) {
               status_text += `<br> <a href='javascript:void(0)' id='mh_approve_request_btn' class='btn btn-success text-white'><i class='fas fa-check'></i> Approve</a>`;
            }
          }
          
        }

        
      html += `<div class="orderstatus ${addClass}">
                  <div class="orderstatus-check"><span class="orderstatus-number">${stages[i]+1}</span></div>
                  <div class="orderstatus-text">
                    <time>${datetime}</time>
                    <p>${status_text}</p>
                  </div>
                </div>`;
      }



  });
  // 0 -> Request Placed 1 - Approved by Reporting Manager 2 -> Approved By Module Head 3 -> Approved by BASIS
    html += "</div></div></section>";


    $("#statusModal").modal('show');
    $("#drop_status").html(html)



}

function approveRequestByRM(request_id, status = 1) {
  $.ajax({
    url:"{{  route('approve.sap.request.by.rm') }}",
    data:{request_id:request_id},
    type:"POST",
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
    },
    error:(r) => {
      console.log(r)
    },
    success:(r) => {
      console.log(r)
      toastr.success('The status was changed to approved');
      loadStatusModal(1, r.created_at, r.logs);
      console.log(r);
    }
  })
}




       
    
    </script>
@stop