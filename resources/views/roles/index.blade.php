@extends('adminlte::page')

@section('title', 'SAP Role List')

@section('content_header')
<meta http-equiv="x-ua-compatible" content="ie=edge">
<meta name="csrf-token" content="{{ csrf_token() }}">
  
@stop

@section('content')
<style>
    .dx-datagrid-rowsview .dx-select-checkboxes-hidden > tbody > tr > td > .dx-select-checkbox {
        display: block !important;
    }
    .dx-texteditor-input {
        border-radius:0 !important;
    }
    .dx-placeholder::before {
        content:'';
    }
</style>
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
                        <div class="col-lg-6">
                            <label for="permissions1"> Role Name </label>
                            <input type="text" name="role_name" id="role_name" class="form-control" placeholder="Enter Role name">
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
                                    <div class="checkbox-group col-lg-3 mb-2 d-flex align-items-center">
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
                        <label for="erole_name">Role / Department Name</label>
                            <input type="text" name="erole_name" id="erole_name" class="form-control" placeholder="Enter Role name">
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

@section('css')
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script>
        $("#submitSelectedTCodes").on('click', (e) => {
            e.preventDefault();
            var pid = $("#selected_permission_id").val();
            var tcodes = $("#selected_tcodes").val();
           //console.log(tcodes);
            //return false;
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
           var url = "{{ route('get.roles.list') }}"
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
                  //console.log(res)
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
        //    {
        //        dataField: "id",
        //        caption: "Role Id",
        //        width:90,
        //        visibe: false,
        //    },
           {
               dataField: "name",
               caption: "Role Name",
           },
           {
                dataField:"his_permissions",
                caption:"Module Permissions",
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
               cellTemplate: function (container, options) {
                   var role_id = options.data.id;
                   var role_name = options.data.name;
                    var permissions = options.data.his_permissions;
                    var all_permissions = options.data.all_permissions;
                
                var link = $(`<a href="javascript:void(0)" title="edit">`).html("<i class='fa fa-edit'></i> Edit")
                    .attr("href", "javascript:void(0)")
                link.on("click", function () {
                    $("#role-edit-modal").modal('show');
                    $("#erole_name").val(role_name);
                    $("#erole_id").val(role_id);
                    $("#selected_role_id").val(role_id);

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

                        html += `<div class='checkbox-group col-lg-3 mb-2 d-flex align-items-center'><input type='checkbox' id='p_${all_permissions[i].id}' name='permissions[]' value="${all_permissions[i].id}" ${checked}> <a href='#' class='module_links ml-2' style=' text-decoration:underline' onclick='standardTCodeList(${all_permissions[i].id})'>${all_permissions[i].name}</a> </div>`;
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
                        selection: {
                            mode: "multiple",
                        },
                        paging: {
                            enabled:false,
                            pageSize: 10
                        },
                        columnChooser: {
                            enabled: true,
                            mode: "select" // or "dragAndDrop"
                        },
                        scrolling: {
                            scrollByContent: true,
                        },
                        onContentReady: function (e) {
                           //console.log(window.currentTCodes)
                            var selectedIds = window.currentTCodes;
                            var selectedRows = []
                            $.each(selectedIds, (p) => {
                                $.each(window.loadData, (i) => {
                                    if(window.loadData[i].id == selectedIds[p]) {
                                       //console.log('matched')
                                        selectedRows.push(i);
                                    }
                                })
                            });

                            e.component.selectRowsByIndexes(selectedRows);
                            
                            
                        },
                        onSelectionChanged: function(selectedItems) {
                            var data = selectedItems.selectedRowsData;
                            var selected_tcodes = [];
                            if(data.length > 0) 
                            {
                                
                                let text = $.map(data, function(value, i) {
                                    let action = $("#t_"+value.id).val();
                                    selected_tcodes.push({'tcode':value.id, 'actions':action});
                                    return value.t_code;
                                }).join(", ");
                               //console.log('selected tcodes spel');
                               //console.log(selected_tcodes)

                                $("#selected_tcodes").val(JSON.stringify(selected_tcodes))
                            }
                        },
                        wordWrapEnabled: true,
                        columns: [{
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
                                cellTemplate: (container, options) => {
                                    
                                   var actions = options.data.action_details
                                   var id = options.data.id;
                                  ////console.log('id is '+id)
                                   var html = `<select name='selected_actions[]' id='t_${id}' data-placeholder="Select Actions" class='form-control select2bs4' multiple>`;
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
                                                                checked = 'selected'
                                                            } 
                                                        })
                                                    }
                                                   
                                                   
                                                })
                                            }   
                                           
                                           
                                            html += `
                                                <option value='${actions[j].id}' ${checked}>${actions[j].name}</option>
                                                `;

                                        });


                                    
                                    

                                    html += `</select>`;

                                    container.append(html);
                                }

                            }
                        ],
                    }).dxDataGrid("instance");

                    // var data = ["MB51", 257];
                    ////console.log(grid)
                    // var employeesToSelect;
                    
                    // employeesToSelect = $.map($.grep(grid.option("dataSource"), function(item) {
                    //     return item.t_code == data[0];
                    // }), function(item) {
                    //     return item.id;
                    // });
                    ////console.log('selected')
                    ////console.log(employeesToSelect)
                    // //grid.selectRows(employeesToSelect);

                       
                   });
                },
                error: function () {
                   //console.log('error 1')
                },
                //timeout: 2000000
            });
   
  

}
    
    </script>
@stop
