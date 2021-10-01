@extends('adminlte::page')

@section('title', 'User SAP Access Report')

@section('content_header')
<meta http-equiv="x-ua-compatible" content="ie=edge">
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css">
  
@stop

@section('content')
<style>
    .accordion-toggle:after {
    font-family: 'FontAwesome';
    content: "\f078";    
    float: right;
}

.accordion {
    background-color: antiquewhite !important;
    padding:5px;
    box-shadow: 0 0 2px 3px rgba(0,0,0,0.19);
}
.accordion-heading {
    background-color: cadetblue !important;
    padding: 5px;
}
.accordion-heading a {
    font-weight: 500 !important;
    color:cornsilk !important;
}
.accordion-body {
    background-color: cornsilk !important;
    padding: 5px;
}
.accordion-opened .accordion-toggle:after {    
    content: "\f054";    
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
    <div class="modal fade" id="tcode-list-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel"><div class="loading1 ml-2 mt-1 border border-warning rounded d-none" id="loadr1" style="padding: 1.5px;"><i class='fas fa-spinner fa-spin'></i> Loading&#8230;</div><span class="d-module-name d-none">Tcode list for <span id="module_name"></span></span></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <form method="post" id="tcode_searchFrm">
                <div class="row p-2">
                    <div class="col-lg-4">
                        <input type="hidden" id="s_permission_id">
                        <input type="text" name="s_tcode" id="s_tcode" class="form-control" placeholder="Search by Tcode">
                    </div>
                    <div class="col-lg-4">
                        <input type="text" name="s_desc" id="s_desc" class="form-control" placeholder="Search by Description">
                    </div>
                    <div class="col-lg-4">
                        <button type="submit" id="tcode_searchFilterBtn" class="btn btn-primary">Search</button>
                    </div>
                </div>
                </form>
                <div class="tab-pane active dx-viewport" id="modules">
                    <div class="demo-container p-3" style="max-height: 400px; overflow:auto">
                       
                        <div id="tcodes-list-div" style="height:auto"></div>
                    </div>
                </div>
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
        $("#update-tcode-btn").on('click', (e) => {
            e.preventDefault();
            var tcode = $("#tt_tcode").val();
            var desc = $("#t_description").val();
            var tactions = $("input[name='t_actions[]']").find(":checked").val();
            var permission_id = $("#t_module_id").val();
            var formData = $("#tcode-update-frm").serialize();
            //formData)
            $.ajax({
                url:"{{ route('tcode.update') }}",
                data:formData,
                type:"POST",
                headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                error:(r) => {
                    toasrt.error('Server Error');
                    //r)
                },
                success: (r) => {
                    //r);
                    if(r.status == 200) {
                        toastr.success('Tcode updated successfully');
                        $("#edit-tcode-modal").modal('hide');
                        
                        showTcodes(permission_id);
                        
                    } else {
                        toastr.error('Something went wrong');
                    }
                }
            })

        })
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
    function trashTcode(id,pid) {
       
        const url = "{{ route('trash.tcode') }}"
    
        if(id > 0) {
            $.ajax({
            url:url,
            data:{id},
            type:"GET",
            headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
           
            error:(r) => {
              
                toastr.error('Something went wrong');
                //r)
            },
            success:(r) => {
               
                toastr.success('Tcode Removed successfully');
                setTimeout(() => {
                $("#link_"+pid).trigger('click');
            },1800);
                fetch_data();
            }

        })
        } else {
            toastr.error('Id not found')
        }
    }

    $(document).on('show','.accordion', function (e) {
    //$('.accordion-heading i').toggleClass(' ');
    $(e.target).prev('.accordion-heading').addClass('accordion-opened');
    });

    $(document).on('hide','.accordion', function (e) {
    $(this).find('.accordion-heading').not($(e.target)).removeClass('accordion-opened');
    //$('.accordion-heading i').toggleClass('fa-chevron-right fa-chevron-down');
    });

$(document).on('click','#add_permission', ()=> {
   // //'inn')
    $("#add-permission-modal").modal('show');
});

$(document).on('click','#add_tcodes', ()=> {
  
    $("#add-tcode-modal").modal('show');
});
$(document).on('click','#update-permission-btn', ()=> {

$("#permission-update-frm").validate({
    rules:{
        epermission_name:{
            required:true
        },
        epermission_type:{
            required:true
        },
        epermission_code:{
            required:true
        }
    },
    submitHandler:(r) => {
        //'next')
        var url = "{{  route('update.permission') }}"
        $.ajax({
            url:url,
            data:$("#permission-update-frm").serialize(),
            type:"POST",
            headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            beforeSend:(r) => {
                $("#update-persmission-btn").prop('disabled',true);
            },
            error:(r) => {
                $("#update-persmission-btn").prop('disabled',false);
                toastr.error('Something went wrong');
            },
            success:(r) => {
                $("#update-persmission-btn").prop('disabled',false);
                toastr.success('Module Updated successfully');
                $("#settings-modal").modal('hide');
                fetch_data();
            }

        })
    }
});
})
$(document).on('click','#add-permission-btn', ()=> {
$("#add-permission-frm").validate({
    rules:{
        permission_name:{
            required:true
        },
        permission_type:{
            required:true
        },
        permission_code:{
            required:true
        }
    },
    submitHandler:(r) => {
        var url = "{{  route('add.permission') }}"
        $.ajax({
            url:url,
            data:$("#add-permission-frm").serialize(),
            type:"POST",
            headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            beforeSend:(r) => {
                $("#add-permission-btn").prop('disabled',true);
            },
            error:(r) => {
                //r.responseJSON)
                $("#add-permission-btn").prop('disabled',false);
                toastr.error(r.responseJSON.message);
            },
            success:(r) => {
                $("#add-permission-btn").prop('disabled',false);
                toastr.success('Module Added successfully');
                $("#add-permission-modal").modal('hide');
                fetch_data();
            }

        })
    }
});
})

window.subData = '';
/* Add TCOde */
$(document).on('click','#add-tcode-btn', ()=> {
$("#add-tcode-frm").validate({
    rules:{
        tcode:{
            required:true
        },
        module:{
            required:true
        },
        tcode_desc:{
            required:true
        }
    },
    submitHandler:(r) => {
        var url = "{{  route('add.tcode') }}"
        $.ajax({
            url:url,
            data:$("#add-tcode-frm").serialize(),
            type:"POST",
            headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            beforeSend:(r) => {
                $("#add-tcode-btn").prop('disabled',true);
            },
            error:(r) => {
                $("#add-tcode-btn").prop('disabled',false);
                toastr.error('Something went wrong');
            },
            success:(r) => {
                $("#add-tcode-btn").prop('disabled',false);
                toastr.success('Tcode Added successfully');
                $("#add-tcode-modal").modal('hide');
                fetch_data();
            }

        })
    }
});
})
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
                        },
                        {
                                dataField:"actions",
                                caption:"Actions",
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

    
    </script>
@stop
