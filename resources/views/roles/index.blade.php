@extends('adminlte::page')

@section('title', 'SAP Role List')

@section('content_header')
<meta http-equiv="x-ua-compatible" content="ie=edge">
<meta name="csrf-token" content="{{ csrf_token() }}">
  
@stop

@section('content')

    <div class="tab-content p-1">
        <div class="tab-pane active dx-viewport" id="users">
            <div class="demo-container">
              <div class="top-info">
                <div class="table-heading-custom"><h4 class="right"><i class="fas fa-users-cog"></i> Roles List </h4></div>
                <button id="add_role" class='custom-theme-btn'><i class='fa fa-plus'></i> Create Role</button>
              </div>
                <div id="roles-list-div" style="height:600px"></div>
            </div>
        </div>
    </div>

    <!-- Add Role Modal -->
    <div class="modal fade" id="add-role-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Role</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-times"></i></span>
                </button>
                </div>
                <div class="modal-body">
        
                <form id="role-add-frm" method="post">
                    <div class="row">
                        <div class="col-lg-4">
                            <label for="permissions1"> Role Name </label>
                            <input type="text" name="role_name" id="role_name" class="form-control" placeholder="Enter Role name">
                        </div>
                        <div class="col-lg-4">
                            <label for="permissions1"> Role Short Name </label>
                            <input type="text" name="role_short_name" id="role_short_name" class="form-control" placeholder="Enter Role Short Name">
                        </div>
                        <div class="col-lg-4">
                            <label for="pe">Role Status</label>
                            <select name="status" id="status" class="form-control select2bs4" data-placeholder="Enter Role Type">
                                <option value=""></option>
                                <option value="temp">Temporary</option>
                                <option value="normal">Normal</option>
                                <option value="auditor">Auditor</option>
                            </select>
                        </div>
                        <div class="col-lg-12 mt-2">
                            <label for="permissions1"> Permissions </label> <br>
                            <div class='permission-checkbox-sec'>
                                <div class='row'>
                                    <!-- <div class='col-lg-12'><h3>User Modules</h3></div> -->
                            @php($flag=0)
                            @foreach($permissions as $permission)
                                @if($permission->type == 2 && $flag == 0) 

                                    </div>
                                  <div class='row'><!-- <div class='col-lg-12'><h3>SAP Modules</h3></div> -->
                                    @php($flag=1)
                                    @endif
                                    <div class="checkbox-group col-lg-3">
                                      <input type="checkbox" class="mr-2" name="permissions1[]" value="{{ $permission->id }}"> <span>{{ $permission->name }}</span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 pt-2">
                            <button class='btn btn-primary' type="submit" id="add-role-btn" name='add-role-btn'>Add</button>
                        </div>
                    </div>
                
                </form>
        
                
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
                </div>
            </div>
            </div>
        </div>

    <!-- Role Edit Modal -->
    <div class="modal fade" id="role-edit-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Role</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-times"></i></span>
                </button>
                </div>
                <div class="modal-body" style="overflow: auto">
        
                <form id="role-update-frm" method="post">
                    <div class="row">
                        <div class="col-lg-4">
                        <input type="hidden" name="role_id" id="erole_id">
                        <label for="erole_name">Role</label>
                            <input type="text" name="erole_name" id="erole_name" class="form-control" placeholder="Enter Role name">
                            
                        </div>
                        <div class="col-lg-4">
                            <label for="permissions2"> Role Short Name </label>
                            <input type="text" name="erole_short_name" id="erole_short_name" class="form-control" placeholder="Enter Role Short Name">
                        </div>
                        <div class="col-lg-4">
                            <label for="pe">Role Status</label>
                            <select name="estatus" id="estatus" class="form-control select2bs4" data-placeholder="Role Type">
                                <option value=""></option>
                                <option value="temp">Temporary</option>
                                <option value="normal">Normal</option>
                                <option value="auditor">Auditor</option>
                            </select>
                        </div>
                        <div class="col-lg-8">
                            <label for="duplicate_role">Duplicate this?</label> <br>
                            <div class="checkbox-group">
                                <input type="checkbox" name="duplicate_this_role" class="form-control">&nbsp;&nbsp;Yes
                                <input type="hidden" id="dup_permissions">
                            </div>
                            <input type="text" name="duplicate_role_name" id="duplicate_role_name" placeholder="Enter Role Name" disabled>
                            <button disabled id='submit_duplicate_role_btn' style="padding: 2px;
                            margin-top: -5px;" class="btn btn-secondary" type="button">Create</button>
                        </div>
                        <div class="col-lg-12 pt-2">
                            <label for="erole_name">Permissions</label>
                            <div id="permission_box" class="p-1" style="width:100%; height:auto"></div>
                            
                        </div>
                        <div class="col-lg-4 pt-2">
                            <button class='btn btn-primary' type="submit" id="update-role-btn" name='update-role-btn'>Update</button>
                        </div>
                    </div>
                
                </form>
        
                
                </div>
               
            </div>
            </div>
        </div>

        <!-- Role Permission Modal -->
        <div class="modal fade" id="settings-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Permissions for this role</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
        
                
        
                
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
                </div>
            </div>
            </div>
        </div>


        <!-- Role wise Module wise Tcode access -->
        <div class="modal fade" id="tcode-access-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tcode access for role</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <div id='tcode-access-block'>
                        <div class="tab-pane active dx-viewport" id="users">
                            <div class="demo-container p-3">
                           
                                <div id="tcode-access-list-div" style="max-height:600px;"></div>
                                <form method="post" id='selected_tcodes_frm'>
                                    <input type="hidden" id="selected_role_id" name="selected_role_id">
                                    <input type="hidden" id="selected_permission_id" name="selected_permission_id">
                                    <input type="hidden" name="selected_tcodes[]" id="selected_tcodes">
                                    <button type="submit" id='submitSelectedTCodes' class='btn btn-primary pt-2'>
                                        Submit
                                    </button>
                                </form>
                            </div>
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
@section('js')
    <script>
        $("input[name='duplicate_this_role']").on('change', () => {
            var checkedStatus = $("input[name='duplicate_this_role']:checked").val();
            //console.log(checkedStatus)
            if(checkedStatus !== undefined) {

                $("#duplicate_role_name").prop('disabled', false);
            } else {
                $("#duplicate_role_name").prop('disabled', true);
            }
        });

        $("#duplicate_role_name").on('keyup', () => {
            var value = $("#duplicate_role_name").val();
            if(value.length >0) {
                $("#submit_duplicate_role_btn").prop('disabled',false);
            } else {
                $("#submit_duplicate_role_btn").prop('disabled', true);
            }
        });
        $("#submitSelectedTCodes").on('click', (e) => {
            e.preventDefault();
            var pid = $("#selected_permission_id").val();
            var tcodes = $("#selected_tcodes").val();
            var role_id = $("#selected_role_id").val();
            $.ajax({
                url:"{{ route('submit.selected.tcodes') }}",
                data:{pid, tcodes, role_id},
                type:"POST",
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                beforeSend:(r) => {
                    $("#submitSelectedTCodes").prop('disabled',true);
                },
                error:(r) => {
                    $("#submitSelectedTCodes").prop('disabled',false);
                    toastr.error('Something went wrong');
                },
                success:(r) => {
                    $("#submitSelectedTCodes").prop('disabled',false);
                   //console.log(r)
                    toastr.success('Tcodes Updated successfully');
                }
            })
        });

        /** Duplicate Role generate with different name */
        $("#submit_duplicate_role_btn").on('click', (e) => {
            e.preventDefault();
            toastr.info('Creating duplcate role');
            var permissionIds = $("#dup_permissions").val();
            var new_role_name = $("#duplicate_role_name").val();
            var role_id = $("#erole_id").val();
            $.ajax({
                url:"{{ route('create.duplicate.role') }}",
                data:{permissionIds, new_role_name, role_id},
                type:"POST",
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                beforeSend:(r) => {
                    $("#submitSelectedTCodes").prop('disabled',true);
                },
                error:(r) => {
                    $("#submitSelectedTCodes").prop('disabled',false);
                    toastr.error('Something went wrong');
                },
                success:(r) => {
                    
                    //$("#submitSelectedTCodes").prop('disabled',false);
                   console.log(r)
                    toastr.success('Tcodes Updated successfully');
                }
            })

        })
    $(document).on('click','#add_role', ()=> {
   ////console.log('inn')
     $("#add-role-modal").modal('show');
    });
    
    $(document).on('click','#update-role-btn', ()=> {

    $("#role-update-frm").validate({
    rules:{
        erole_name:{
            required:true
        },
        permissions:{
            required:true
        }
    },
    submitHandler:(r) => {
    
        var url = "{{  route('update.role') }}"
        $.ajax({
            url:url,
            data:$("#role-update-frm").serialize(),
            type:"POST",
            headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            beforeSend:(r) => {
                $("#update-role-btn").prop('disabled',true);
            },
            error:(r) => {
                $("#update-role-btn").prop('disabled',false);
                toastr.error('Something went wrong');
            },
            success:(r) => {
                $("#update-role-btn").prop('disabled',false);
                toastr.success('role Updated successfully');
                $("#role-edit-modal").modal('hide');
                fetch_data();
            }
        })
    }
});
});

$(document).on('click','#add-role-btn', ()=> {
$("#role-add-frm").validate({
    rules:{
        role_name:{
            required:true
        },
    },
    submitHandler:(r) => {
        var permissions = $("#permissions1").val();
        
        var url = "{{  route('add.role') }}"
        $.ajax({
            url:url,
            data:$("#role-add-frm").serialize(),
            type:"POST",
            headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            beforeSend:(r) => {
                $("#add-role-btn").prop('disabled',true);
            },
            error:(r) => {
               //console.log(r)
                $("#add-role-btn").prop('disabled',false);
                toastr.error(r.responseJSON.message);
            },
            success:(r) => {
                $("#add-role-btn").prop('disabled',false);
                toastr.success('role Added successfully');
                $("#add-role-modal").modal('hide');
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
           ////console.log(loadOptions)
           var deferred = $.Deferred(),
               args = {};
           [
               "sort",
               "filter",
           ].forEach(function (i) {
               if (i in loadOptions && isNotEmpty(loadOptions[i]))
                   args[i] = JSON.stringify(loadOptions[i]);
           })

        //    let take = loadOptions.take
        //    let skip = loadOptions.skip
           var dataSet = []
           var url = "{{ route('get.roles.list') }}"
           $.ajax({
               url: url,
               type: 'GET',
               headers: {
                   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
               },
               dataType: "json",
               data: null,
               complete: function (result) {
                   var res = result.responseJSON;
                   var data = res.data;
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


   $("#roles-list-div").dxDataGrid({
       dataSource: jsonData,
       KeyExpr: "id",
       showBorders: true,
       showRowLines: true,
       rowAlternationEnabled: true,
       allowColumnResizing: false,
       columnAutoWidth:true,
       columnHidingEnabled: false,
       filterRow: { 
         visible: true
       },
       loadPanel: {
        text: "Loading...",
        showPane: true,
       },
       paging: {
           enabled: false,
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
       hoverStateEnabled: true,
       wordWrapEnabled: false,
       columns: [
           {
               
               dataField: "name",
               caption: "Role Name",
           },
           {
               dataField: "short_name",
               caption: "Role Short Name",
           },
           {
               dataField: "status",
               caption: "Type",
               width:120,
            //    lookup: {
            //         dataSource: ['normal', 'critical', 'auditor'],
            //     }
           },
           {
                dataField:"his_permissions",
                caption:"Module Permissions",
                width:500,
                allowFiltering: false,
                cellTemplate: (container, options) => {
                   //console.log(options.data.his_permissions)
                    var permissions = options.data.his_permissions;
                    var html = '';
                    $.each(permissions, (i) => {
                        if(permissions[i].permission_names !== null) {
                            html += `<span class='badge badge-primary'> ${permissions[i].permission_names.name}</span>&nbsp; &nbsp;`;
                        }
                    });

                    container.append(html);
                }
           },
           {
               dataField: "Action",
               caption: "Action",
               allowFiltering: false,
               cellTemplate: function (container, options) {
                   var role_id = options.data.id;
                   var role_name = options.data.name;
                   var role_short_name = options.data.short_name;
                   var status = options.data.status;
                    var permissions = options.data.his_permissions;
                    var all_permissions = options.data.all_permissions;
                
                var link = $(`<a href="javascript:void(0)" title="edit">`).html("<i class='fa fa-edit'></i> Edit")
                    .attr("href", "javascript:void(0)")
                link.on("click", function () {
                    $("#role-edit-modal").modal('show');
                    $("#erole_name").val(role_name);
                    $("#erole_id").val(role_id);
                    $("#erole_short_name").val(role_short_name);
                    $("#selected_role_id").val(role_id);
                    $("#dup_permissions").val(JSON.stringify(permissions));
                    $("#estatus").val(status).trigger('change');
                    var html = `<div class='row'><div class='row'>`;
                    var checked = ``;
                    var flag = 0;
                    $.each(all_permissions, (i) => {

                        checked = ``;
                        $.each(permissions, (j) => {

                            if(permissions[j].permission_id == all_permissions[i].id) {
                                checked = `checked='checked'`;
                            }
                        });

                        if(all_permissions[i].type == 2 && flag == 0) {

                            html += `</div><div class='row'><div class='col-lg-12 p-2'><h6>SAP Modules</h6></div>`;
                            flag = 1;
                        }

                        html += `<div class='checkbox-group col-lg-3'><input type='checkbox' id='p_${all_permissions[i].id}' name='permissions[]' value="${all_permissions[i].id}" ${checked}> <a href='#' class='module_links ml-2' style=' text-decoration:underline' onclick='standardTCodeList(${all_permissions[i].id})'>${all_permissions[i].name}</a> </div>`;
                    });

                    html += `</div>`;
                    //console.log(html)
                    $("#permission_box").html(html);


                })
                
                return link;

               }
           },
       ],
   });
    
}

function standardTCodeList(permission_id) {
   //console.log(permission_id)
    var state = $("#p_"+permission_id+':checked').val();
    if(state !== undefined) {
        $("#tcode-access-modal").modal('show');
        loadStandardTCodes(permission_id);
    }
}

function getCurrentTCodes(permission_id) {
    var role_id = $("#erole_id").val();

    var url = "{{ route('get.current.tcodes') }}";
    $.ajax({
        url: url,
        type: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
        dataType: "json",
        data: 'permission_id=' +permission_id+'&role_id='+role_id,
        complete: function (result) {
           //console.log(result)
           //console.log(result.responseJSON.data)
            var selected = result.responseJSON.data;
            window.currentTCodes = []
            window.currentActions = []
            $.each(selected, (i) => {
                window.currentTCodes.push(selected[i].tcode_id)
                window.currentActions.push({'tcode_id': selected[i].tcode_id, 'actions': selected[i].actions})
            })
        }
    })
}

function loadStandardTCodes(permission_id){

    getCurrentTCodes(permission_id)
    $("#selected_permission_id").val(permission_id)
    var url = "{{  route('update.role') }}"
    function isNotEmpty(value) {
        return value !== undefined && value !== null && value !== "";
    }
    var url = "{{ route('role.tcode.access') }}"
    var jsonData = [];
           $.ajax({
               url: url,
               type: 'GET',
               headers: {
                   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
               },
               dataType: "json",
               data: 'permission_id=' +permission_id,
               complete: function (result) {
                   var res = result.responseJSON;
                   var data = res.data;
                   window.loadData = data
                   //console.log(res)
                   $(function(){
                    var grid;
                    grid = $("#tcode-access-list-div").dxDataGrid({
                        dataSource: res.data,
                        KeyExpr: "id",
                        filterRow: { 
                            visible: true
                        },
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
                        // selection: {
                        //     mode: "multiple",
                        // },
                        paging: {
                            enabled:false,
                          
                        },
                        columnChooser: {
                            enabled: true,
                            mode: "select" // or "dragAndDrop"
                        },
                        scrolling: {
                            scrollByContent: true,
                        },
                        onToolbarPreparing: function(e) {
                                e.toolbarOptions.items.unshift({
                                location: "before",
                                visible:true,
                                    template: function(){
                                        return $("<div/>")
                                            .addClass("font-weight-bold")
                                            .append($("</p>").addClass("mt-0 mb-0 p-0").append(`<input type='checkbox' name='checkall' id='checkall' onClick='checkAll()'> Check All`))
                                    }
                                })
                        },
                        wordWrapEnabled: true,
                        columns: [
                            {
                                dataField:"checks",
                                caption:"*",
                                cellTemplate: (container, options) => {
                                    var tcode_id = options.data.id;
                                   // console.log(window.currentTCodes)
                                    console.log(window.currentActions)
                                    var checked = '';
                                    $.each(window.currentTCodes, (i) => {
                                        if(tcode_id == window.currentTCodes[i]) {
                                            checked = 'checked'
                                        }
                                    })
                                    var html = `<input type='checkbox' class='selected_row' name='selected_row[]' id="c_${options.data.id}" value='1' onClick='onSelectChange(${options.data.id}, this)' ${checked}>`
                                    container.append(html);
                                }

                            },{
                                dataField: "id",
                                caption: "Tcode Id",
                                width:90,
                                visibe: true,
                            },
                            {
                                dataField: "t_code",
                                caption: "Tcode Name",
                            },
                            {
                                    dataField:"description",
                                    caption:"Description",
                            },
                            {
                                dataField:"actions",
                                caption:"Actions",
                                width:140,
                                cellTemplate: (container, options) => {
                                    
                                   var actions = options.data.action_details
                                   var id = options.data.id;
                                  ////console.log('id is '+id)
                                   var html = `<div id='t_${id}'><div class='row'>
                                   `;
                                   var checked = '';
                        
                                        $.each(actions, (j) => {

                                            if(window.currentActions !== undefined) {
                                               //console.log('matchd log')
                                               //console.log(window.currentActions)

                                                let data = window.currentActions;
                                                
                                                $.each(data, (i) => {
                                                    
                                                    if(data[i].tcode_id == id) {
                                                        checked = '';
                                                        $.each(data[i].actions, (p) => {
                                                        
                                                            if(actions[j].id == data[i].actions[p]) {
                                                                checked = 'checked'
                                                            } 
                                                        })
                                                    }
                                                   
                                                   
                                                })
                                            }   
                                           
                                           
                                            html += `<div class='col-lg-6'>
                                                <input type='checkbox' name='selected_actions[]' onClick="onSelectChange(${id}, this, true)"  class='form-control' value='${actions[j].id}' ${checked}> ${actions[j].name}
                                                    </div>
                                                `;

                                        });
                                    html += `</div></div>`;

                                    container.append(html);
                                }

                            }
                        ],
    
                    }).dxDataGrid("instance");

                

                       
                   });
                },
                error: function () {
                   //console.log('error 1')
                },
                //timeout: 2000000
            });
   
  

}

function checkAll() {
    var value = $("#checkall").is(':checked');
    console.log(value)
 $(".selected_row").trigger('click')
}
   



var selected_rows = [];
    function onSelectChange(id,obj,x=false) {
        //console.log('called');
        let flag = 1
        if(window.currentActions.length>0) {
            $.each(window.currentActions, (j) => {
                if(selected_rows.length>0) {
                    $.each(selected_rows, (i) => {
                        if(selected_rows[i].id == window.currentActions[j].tcode_id) {
                            flag = 0
                        }
                    });
                    
                    if(flag) {
                        selected_rows.push({id: window.currentActions[j].tcode_id, actions: JSON.stringify(window.currentActions[j].actions)})
                    }

                } else {
                    selected_rows.push({id: window.currentActions[j].tcode_id, actions: JSON.stringify(window.currentActions[j].actions)})
                }
               
            })
        }
        var checkedStatus = $(obj).is(":checked");
        var actions = []
        console.log(x)
        //console.log(actions);
        if(checkedStatus) {
            if(x==false) {
                $("#t_"+id).find("input[name='selected_actions[]']").attr('checked','checked');
                
            } else {
                let is_chedked = $("#c_"+id).is('checked');
                if(!is_chedked){
                    $("#c_"+id).attr('checked','checked');
                }
                
            }
            $("#t_"+id).find("input[name='selected_actions[]']:checked").each(function (){
                    actions.push(parseInt($(this).val()));
                });
            

            if(selected_rows.length > 0) {
                let flag = 0
                $.each(selected_rows, (i) => {
                    if(selected_rows[i].id == id) {
                        selected_rows[i].actions = JSON.stringify(actions);
                        flag = 1
                    } 
                })
                if(flag == 0) {
                    selected_rows.push({id: id, actions: JSON.stringify(actions)})
                }
            } else {
                selected_rows.push({id: id, actions: JSON.stringify(actions)})
            }
           
        } else {

            if(x==false) {
                $("#t_"+id).find("input[name='selected_actions[]']").removeAttr('checked');
                // $("#t_"+id).find("input[name='selected_actions[]']:checked").each(function (){
                //     actions.push(parseInt($(this).val()));
                // });
                selected_rows = selected_rows.filter(function( obj ) {
                return obj.id !== id;
                })
            } else {
               
                //$("#c_"+id).removeAttr('checked');
            
                $("#t_"+id).find("input[name='selected_actions[]']:checked").each(function (){
                    actions.push(parseInt($(this).val()));
                });
                if(selected_rows.length > 0) {
                let flag = 0
                $.each(selected_rows, (i) => {
                    if(selected_rows[i].id == id) {
                        selected_rows[i].actions = JSON.stringify(actions);
                        flag = 1
                    } 
                })
                if(flag == 0) {
                    selected_rows.push({id: id, actions: JSON.stringify(actions)})
                }
            } else {
                selected_rows.push({id: id, actions: JSON.stringify(actions)})
            }
            }
            
          
           
        }
       
         console.log('selected rows')
         console.log(selected_rows)
         $("#selected_tcodes").val(JSON.stringify(selected_rows));
        // console.log(id);
    }


    
    </script>
@stop
