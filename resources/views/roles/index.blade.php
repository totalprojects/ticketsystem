@extends('adminlte::page')

@section('title', 'Roles')

@section('content_header')
<meta http-equiv="x-ua-compatible" content="ie=edge">
<meta name="csrf-token" content="{{ csrf_token() }}">
  
@stop

@section('content')

    <div class="tab-content p-1">
        <div class="font-weight-bold m-2 font-italic text-primary"><h4 class="right"><i class="fas fa-users-cog"></i> Roles List </h4></div>
        <div class="tab-pane active dx-viewport" id="users">
            <div class="demo-container p-3">
            <button id="add_role" class='btn btn-primary p-1'><i class='fa fa-plus'></i> Create Role</button>
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
                        <div class="col-lg-12 pt-2">
                            <label for="permissions1"> Permissions </label> <br>
                            <div class='row'>
                                <div class='row bg-secondary'>
                                    <div class='col-lg-12 p-2'><h3>User Modules</h3></div>
                            @php($flag=0)
                            @foreach($permissions as $permission)
                                @if($permission->type == 2 && $flag == 0) 

                                    </div><div class='row bg-primary'><div class='col-lg-12 p-2'><h3>SAP Modules</h3></div>
                                    @php($flag=1)
                                @endif
                                <div class="col-lg-3 p-2">
                                    <input type="checkbox" name="permissions1[]" value="{{ $permission->id }}"> {{ $permission->name }} &nbsp;
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
    
@stop

@section('css')
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script>
    $(document).on('click','#add_role', ()=> {
   // console.log('inn')
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
                console.log(r)
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
                   console.log(res)
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
    //    searchPanel: {
    //        visible: true,
    //        width: 240,
    //        placeholder: "Search..."
    //    },
       headerFilter: {
           //visible: true
       },
       scrolling: {
           scrollByContent: true,
       },
       wordWrapEnabled: true,
       columns: [{
               dataField: "id",
               caption: "Role Id",
               width:90,
               visibe: true,
           },
           {
               dataField: "name",
               caption: "Role Name",
           },
           {
                dataField:"his_permissions",
                caption:"Module Permissions",
                cellTemplate: (container, options) => {
                    console.log(options.data.his_permissions)
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

                    var html = `<div class='row'><div class='row bg-secondary'><div class='col-lg-12 p-2'><h3>User Modules</h3></div>`;
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

                            html += `</div><div class='row bg-primary'><div class='col-lg-12 p-2'><h3>SAP Modules</h3></div>`;
                            flag = 1;
                        }

                        html += `<div class='col-lg-3 p-2'><input type='checkbox' name='permissions[]' value="${all_permissions[i].id}" ${checked}> ${all_permissions[i].name} &nbsp; </div>`;
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
    
    </script>
@stop
