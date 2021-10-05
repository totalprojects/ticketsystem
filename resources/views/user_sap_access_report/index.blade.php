@extends('adminlte::page')

@section('title', 'User SAP Access Report')

@section('content_header')
<meta http-equiv="x-ua-compatible" content="ie=edge">
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css">
  
@stop

@section('content')
<style>
.accordion-forms {
  padding-right: 0 !important;
}
@media (max-width: 800px) {
  .accordion-forms {
    padding: 0 10px !important;
    margin: 25px 0;
  }
}

.accordion-block {
  background-color: #f4f4f4;
  margin-bottom: 20px;
}
.accordion-block:last-of-type {
  margin-bottom: 0;
}

.accordion-block__header {
  color: inherit;
  margin-bottom: 0;
  font-size: 16px;
  line-height: 1.25;
}

.accordion-block__btn {
  display: flex;
  justify-content: space-between;
  align-items: center;
  width: 100%;
  padding: 8px 10px;
  border-color: #000a28 !important;
  background: #000a28 !important;
  color: #ffffff !important;
  border: none;
  margin: 0;
  cursor: pointer;
  transition: background-color 0.25s ease-in-out;
  text-transform: none;
}
.accordion-block__btn:hover, .accordion-block__btn:active, .accordion-block__btn:focus {
  color: #ffffff !important;
  background-color: rgba(0, 10, 40, 0.75) !important;
}
.accordion-block__btn span {
  color: inherit;
  margin-bottom: 0;
  text-align: left;
}
.accordion-block__btn svg {
  margin: auto 0;
  flex-shrink: 0;
}
.accordion-block__btn svg path {
  fill: #ffffff;
}
.accordion-block__btn svg[aria-hidden=true] {
  display: block;
}
.accordion-block__btn svg[aria-hidden=false] {
  display: none;
}

.accordion-block__content {
  display: none;
}

.accordion-block__btn[aria-expanded=false] .js-accordion__icon-plus {
  display: inline-block;
}
.accordion-block__btn[aria-expanded=false] .js-accordion__icon-minus {
  display: none;
}

.accordion-block__btn[aria-expanded=true] .js-accordion__icon-plus {
  display: none;
}
.accordion-block__btn[aria-expanded=true] .js-accordion__icon-minus {
  display: inline-block;
}
</style>
    <div class="tab-content p-1">
        <div class="tab-pane active dx-viewport" id="users">
            <div class="demo-container">
                <div class="top-info">
                    <div class="table-heading-custom"><h4 class="right"><i class="fas fa-user-lock"></i> Employee SAP Access Report </h4></div>
                    <div class="multibtn-sec">
                    </div>
                </div>
            {{-- <button id="add_permission" class='btn btn-primary p-1'><i class='fa fa-plus'></i> Module</button>
            &nbsp;
            <button id="add_tcodes" class='btn btn-primary p-1'><i class='fa fa-plus'></i> T-CODES</button> --}}
                <div id="sap-report-list-div" style="height:600px"></div>
            </div>
        </div>
    </div>

    <!-- Tcode List -->
    <div class="modal fade" id="tcode-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel"><div class="loading1 ml-2 mt-1 border border-warning rounded d-none" id="loadr1" style="padding: 1.5px;"><i class='fas fa-spinner fa-spin'></i> Loading&#8230;</div><span class="d-module-name d-none">Tcode list for <span id="module_name"></span></span></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <div id="vtcode"></div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
            </div>
        </div>
        </div>
</div>
@stop

@section('css')
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
               
    <script>

        $("#tcode_searchFilterBtn").on('click', (e) => {
            e.preventDefault();
            var tcode = $("#s_tcode").val();
            var desc = $("#s_desc").val();
            var permission_id = $("#s_permission_id").val();
            //permission_id)
            if(tcode.length == 0 && desc.length == 0) {
                toastr.error('You must provide atleast one input');
                return false;
            }
            showTcodes(permission_id, tcode, desc);
        });
        
        $(document).ready(() => {
            // setTimeout(() => {
            //     $("#link_2").trigger('click');
            // },3000);
          
        })



window.subData = '';

fetch_data();
function fetch_data(){
    function isNotEmpty(value) {
        return value !== undefined && value !== null && value !== "";
    }
   var jsonData = new DevExpress.data.CustomStore({
       key: "id",
       load: function (loadOptions) {
           // //loadOptions)
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
           var url = "{{ route('show.sap.report') }}"
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
                   //res)
                   deferred.resolve(data, {
                       totalCount: res.totalCount,
                   });
                   window.subData = res.subData;
                   console.log(window.subData);
                   deferred.resolve(window.subData);
                   
               },
               error: function () {
                   deferred.reject("Data Loading Error");
               },
               //timeout: 2000000
           });
           return deferred.promise();
       }
   });
   $("#sap-report-list-div").dxDataGrid({
       dataSource: jsonData,
       KeyExpr: "id",
       showBorders: true,
       showRowLines: true,
       rowAlternationEnabled: true,
       allowColumnResizing: true,
       sorting: false,
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
           enabled: false,
           pageSize: 10
       },
       columnChooser: {
           enabled: true,
           mode: "select" // or "dragAndDrop"
       },
       scrolling: {
           scrollByContent: true,
       },
       wordWrapEnabled: true,
       columns: [
           {
               dataField: "name",
               caption: "Employee Name",
               cellTemplate: (container, options) => {
                    var code = options.data.first_name+' '+options.data.last_name;
                    var html = '';
                        html = `${code}`;
                
                        container.append(html);
               }
           },
           {
               dataField: "sap_code",
               caption: "SAP Code",
           },
           {
                dataField:"department",
                caption:"Department",
           },
           {
                dataField:"company",
                caption:"Company",
           },
           {
                dataField:"designation",
                caption:"Designation",
           },
           {
                dataField:"report_to",
                caption:"Report To",
           },
        
       ],

       masterDetail: {
            enabled: true,
            template: function(container, options) { 
                var currentEmployeeData = options.data;

                $("<div>")
                    .addClass("master-detail-caption")
                    .text(currentEmployeeData.first_name + " " + currentEmployeeData.last_name + "'s Access Report:")
                    .appendTo(container);

                $("<div>")
                    .dxDataGrid({
                        columnAutoWidth: true,
                        showBorders: true,
                        columns: [    
                        {
                            dataField: "module",
                            caption: "Module Name",
                        },
                        {
                                dataField:"module_head",
                                caption:"Module Owner",
                                visible:true,
                                cellTemplate: (container, options) => {
                                    var module_head = options.data.module_head;
                                    var html = '';
                                    if(module_head !== null) {
                                        module_head = module_head.user_details.name
                                        html = `<span class='badge badge-primary'>${module_head}</span>`;
                                    } else {
                                        module_head = 'Not Assigned';
                                        html = `<span class='badge badge-danger'>${module_head}</span>`;
                                    }
                                
                                        
                                
                                        container.append(html);
                                }
                        },
                        {
                                dataField:"tcodes",
                                caption:"Tcode",
                                cellTemplate: (container, options) => {
                                    var tcodes = JSON.stringify(options.data.tcodes);   
                                    console.log(tcodes);                                   
                                    var html = `<a href='javascript:void(0)' onClick='viewTcodes(${tcodes})' class='badge badge-primary text-white'><i class='fas fa-eye'></i> View</a>`;
                                    container.append(html);
                                }
                        },
                       
                       ],
                        dataSource: new DevExpress.data.DataSource({
                            store: new DevExpress.data.ArrayStore({
                                    key: "id",
                                    data: window.subData
                            }),
                            filter: ["id", "=", options.key]
                        })
                    }).appendTo(container);
            }
        }
   });
    
}

function showTcodes(permission_id, tcode = '', desc = '') {
    $(".d-module-name").addClass('d-none');
    $(".loading1").removeClass('d-none');
    function isNotEmpty(value) {
        return value !== undefined && value !== null && value !== "";
    }
   
    var jsonData = new DevExpress.data.CustomStore({
       key: "id",
       load: function (loadOptions) {
           
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
           var url = "{{ route('fetch.critical.tcodes') }}"
           $.ajax({
               url: url,
               type: 'GET',
               headers: {
                   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
               },
               beforeSend:() => {
                $("#tcode-list-modal").modal('show');
               },
               dataType: "json",
               data: '&take=' + take + '&skip=' + skip + '&permission_id=' + permission_id+'&tcode=' + tcode+ '&description=' + desc,
               complete: function (result) {

                   var res = result.responseJSON;
                   var data = res.data;
                   
                   var module_name = res.module_name;
                   $("#module_name").html(module_name);
                   ////res)
                   $(".loading1").addClass('d-none');
                   $(".d-module-name").removeClass('d-none');
                   $("#s_permission_id").val(permission_id)
                  
                   deferred.resolve(data, {
                       totalCount: res.totalCount,
                   });
                   
               },
               error: function () {
                   deferred.reject("Data Loading Error");
               },
               //timeout: 2000000
           });
           return deferred.promise();
       }
   });
               // var jsonData = r.data;

                $("#tcodes-list-div").dxDataGrid({
                    dataSource: jsonData,
                    KeyExpr: "id",
                    showBorders: true,
                    showRowLines: true,
                    rowAlternationEnabled: true,
                    allowColumnResizing: true,
                    sorting: false,
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
                    
                    pager: {
                        showInfo: true
                    },
                    paging: {
                        enabled: true,
                        pageSize: 25
                    },
                    columnChooser: {
                        enabled: true,
                        mode: "select" // or "dragAndDrop"
                    },
                    headerFilter: {
                        //visible: true
                    },
                    scrolling: {
                        scrollByContent: true,
                    },
                    wordWrapEnabled: true,
                    columns: [
                    {
                        dataField: "name",
                        caption: "Module Name",
                        cellTemplate: (container, options) => {
                            var type = options.data.tcodes.permission.name                   
                            container.append(type);
                        }
                    },
                    {
                        dataField:"code",
                        caption:"Module Code",
                        cellTemplate: (container, options) => {
                            var type = options.data.tcodes.permission.code                   
                            container.append(type);
                        }
                    },
                    {
                        dataField:"tcodes",
                        caption:"Tcodes",
                        cellTemplate: (container, options) => {
                            var type = options.data.tcodes.description+' ('+options.data.tcodes.t_code+')';                 
                            container.append(type);
                        }
                    },
                        {
                                dataField:"edit",
                                caption:"Edit",
                                visible:false,
                                cellTemplate: (container, options) => {
                                    //var id = options.data.id;
                                    var html = `<a href='javascript:void(0)' onClick='editTcode(${JSON.stringify(options.data)})'>Edit</a>`;
                                    
                                    
                                
                                        container.append(html);
                                }
                        },
                        
                    ],
                });
            }   

            function editTcode(data) {
                //data);
                $("#edit-tcode-modal").modal('show');
                $("#tt_code").val(data.t_code);
                $("#t_id").val(data.id);
                $("#t_description").val(data.description)
                $("#t_module_id").val(data.permission_id).trigger('change')
                $("#t_status").val(data.status).trigger('change');
                var actions = data.action_details;
               



                var action_markup = '';
            
                    $.each(all_actions, (i) => {
                        checked = '';
                        $.each(actions, (j) => {
                            if(all_actions[i].id == actions[j].id) {
                                checked = `checked='checked'`;
                            }
                        });
                        
                    action_markup += `<input type='checkbox' name='t_actions[]' value='${all_actions[i].id}' ${checked}> ${all_actions[i].name} &nbsp;`;
                
                })
                $("#t_actions").html(action_markup);
            }


function viewTcodes(tcodes) {
    
    var html = ``
    console.log('innn')
   

    var acc = `<div class="events-right accordion-forms">
  <div class="contact-text accordion-forms__container">`;

    $.each(tcodes, (i) => {

        let actions = '';
        $.each(tcodes[i].actions, (j) => {
            actions += `<span class='badge badge-primary mr-2 mt-2'>${tcodes[i].actions[j]}</span>`
        });

        html += `<div class="accordion-block js-accordion">
      <h3 class="accordion-block__header">
        <button class="accordion-block__btn js-accordion__trigger" aria-expanded="false">
          <span> ${tcodes[i].tcode}</span>
          <svg class="js-accordion__icon-plus" aria-hidden="true" width="24" height="24" fill-rule="evenodd" clip-rule="evenodd">
            <path d="M11.5 0c6.347 0 11.5 5.153 11.5 11.5s-5.153 11.5-11.5 11.5-11.5-5.153-11.5-11.5 5.153-11.5 11.5-11.5zm0 1c5.795 0 10.5 4.705 10.5 10.5s-4.705 10.5-10.5 10.5-10.5-4.705-10.5-10.5 4.705-10.5 10.5-10.5zm.5 10h6v1h-6v6h-1v-6h-6v-1h6v-6h1v6z" />
          </svg>
          <svg class="js-accordion__icon-minus" aria-hidden="false" width="24" height="24" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd">
            <path d="M11.5 0c6.347 0 11.5 5.153 11.5 11.5s-5.153 11.5-11.5 11.5-11.5-5.153-11.5-11.5 5.153-11.5 11.5-11.5zm0 1c5.795 0 10.5 4.705 10.5 10.5s-4.705 10.5-10.5 10.5-10.5-4.705-10.5-10.5 4.705-10.5 10.5-10.5zm-6.5 10h13v1h-13v-1z" />
          </svg>
        </button>
      </h3>
      <div class="accordion-block__content js-accordion__content" aria-hidden="true">
        ${actions}
      </div>
    </div>`;
    });
    html += ' </div></div>';
    $("#vtcode").html(html);
    $("#tcode-modal").modal('show');
}

/* accordion */

  $(document).on("click", ".js-accordion__trigger", (e) => {
      console.log('triggered');
    let target = $(e.currentTarget);
    let expanded = target.attr("aria-expanded") === "true" || false;
    let targetContent = target
      .closest(".js-accordion")
      .find(".js-accordion__content");

    /* collapse all accordion contents */
    $(".js-accordion__trigger").attr("aria-expanded", "false");
    $(".js-accordion__content").attr("aria-hidden", "true").slideUp(700);

    /* toggle the target accordion block */
    target.attr("aria-expanded", !expanded);
    targetContent.attr("aria-hidden", expanded);

    let targetContentShown =
      targetContent.attr("aria-hidden") === "true" || false;

    targetContentShown
      ? targetContent.slideUp(700)
      : targetContent.slideDown(700);
  });


/* init accordion logic if it exists on the page */
//$(".js-accordion") ? triggerAccordion() : false;


    
    </script>
    
@stop
