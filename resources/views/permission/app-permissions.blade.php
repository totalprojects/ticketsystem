@extends('adminlte::page')

@section('title', 'App Permissions')

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
                    <div class="table-heading-custom"><h4 class="right"><i class="fas fa-globe"></i> App Permissions </h4></div>
                    <div class="multibtn-sec">
                       <button id="add_permission" class='custom-theme-btn'><i class='fa fa-plus'></i> App Permission</button>
                    </div>
              </div>
                <div id="roles-list-div" style="height:600px"></div>
            </div>
        </div>
    </div>

    <!-- Tcode List -->
    <!-- Add Modal -->
    <div class="modal fade" id="add-permission-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Permission</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
        
                <form id="add-permission-frm" method="post">
                    <div class="row">
                        <div class="col-lg-4 pt-2">

                            <input type="text" name="permission_name" id="permission_name" class="form-control" placeholder="Enter permission name">
                        </div>
                        <div class="col-lg-4 pt-2">

                            <input type="text" name="permission_code" id="permission_code" class="form-control" placeholder="Enter permission code">
                        </div>
                        <div class="col-lg-4 pt-2 d-none">

                            <select name="permission_type" id="permission_type" data-placeholder="Module Type" class="select2bs4 form-control">
                                {{-- <option value=""></option> --}}
                                <option value="1">Application</option>
                                {{-- <option value="2">SAP</option> --}}
                            </select>
                        </div>
                        <div class="col-lg-4 pt-2 d-none">

                            <select name="module_head" id="module_head" data-placeholder="Module Head" class="select2bs4 form-control">
                                <option value=""></option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                       
                        <div class="col-lg-4 pt-2">
                            <button class='btn btn-primary' type="submit" id="add-permission-btn" name='add-permission-btn'><i class='fas fa-plus'></i> Add</button>
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


    <!-- Edit Modal -->
        <div class="modal fade" id="settings-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
        
                <form id="permission-update-frm" method="post">
                    <div class="row">
                        <div class="col-lg-4 pt-2">
                        <input type="hidden" name="permission_id" id="epermission_id">
                             <label for="permission_name">
                                Permission Name
                            </label> 
                            <select name="emodule" id="emodule" data-placeholder="Select Module" class="select2bs4 form-control">
                                <option value=""></option>
                               @foreach($modules as $module)
                                    <option value="{{ $module->name }}">{{ $module->name }}</option>
                               @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4 pt-2 d-none">
                           
                                 <label for="permission_name">
                                    Assigned Head
                                </label> 
                                <select name="emodule_head" id="emodule_head" data-placeholder="Select Module Head" class="select2bs4 form-control">
                                    <option value=""></option>
                                </select>
                            </div>
                        <div class="col-lg-4 pt-2">
                            <label for="permission_name">
                                Permission Code
                            </label> 
                            <input type="text" name="epermission_code" id="epermission_code" class="form-control" placeholder="Enter permission code">
                        </div>
                        <div class="col-lg-4 pt-2 d-none">
                            <label for="permission_name">
                                Permission Type
                            </label> 
                            <select name="epermission_type" id="epermission_type" data-placeholder="Permission Type" class="select2bs4 form-control">
                                <option value="1">Application</option>
                               
                            </select>
                        </div>
                        <div class="col-lg-12 pt-2">
                            {{-- <div id="tcode_section"></div> --}}
                        </div>
                        <div class="col-lg-4 pt-2">
                            <button class='btn btn-primary' type="submit" id="update-permission-btn" name='update-permission-btn'>Update</button>
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

        <!-- Edit Tcode -->
        <div class="modal fade" id="edit-tcode-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Tcode Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                <form id="tcode-update-frm" method="post">
                    <div class="row">
                        <div class="col-lg-3 pt-2">
                        <input type="hidden" name="t_id" id="t_id">
                             <label for="module">
                                Module Name
                            </label> 
                            <select name="t_module_id" id="t_module_id" data-placeholder="Select Module" class="select2bs4 form-control">
                                <option value=""></option>
                               @foreach($modules as $module)
                                    <option value="{{ $module->id }}">{{ $module->name }}</option>
                               @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 pt-2">
                            <label for="tcode">
                                T code
                            </label> 
                            <input type="text" name="tt_code" id="tt_code" class="form-control">
                        </div>
                        <div class="col-lg-3 pt-2">
                            <label for="tdesc">
                               Description
                            </label> 
                            <input type="text" name="t_description" id="t_description" class="form-control">
                        </div>
                        <div class="col-lg-3 pt-2">
                            <label for="status">
                                Status
                            </label>
                                <select name="t_status" data-placeholder="Status" id="t_status" class="form-control select2bs4">
                                    <option value=""></option>
                                    <option value="1">Active</option>
                                    <option value="0">In-Active</option>
                                </select>
                           
                        </div>
                        <div class="col-lg-12 pt-2">
                            <div id="t_actions"></div>
                        </div>
                        <div class="col-lg-4 pt-2">
                            <button class='btn btn-primary' type="submit" id="update-tcode-btn" name='update-tcode-btn'>Update</button>
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
    
@stop

@section('css')
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')

    <script>
        
      

$(document).on('click','#add_permission', ()=> {
   // //'inn')
    $("#add-permission-modal").modal('show');
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
           var url = "{{ route('show.app.permissions') }}"
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
               caption: "Permission Name",
           },
           {
                dataField:"type",
                caption:"Permission Type",
                visible:false,
                cellTemplate: (container, options) => {
                    var type = options.data.type;
                    var html = '';
                    if(type == 1) {
                        html = "<span class='badge badge-primary'>App</span>";
                    } else if(type == 2) {
                        html = "<span class='badge badge-primary'>SAP</span>";
                    }
                    container.append(html);
                }
           },
           {
                dataField:"code",
                caption:"Permission Code",
                cellTemplate: (container, options) => {
                    var code = options.data.code;
                    var html = '';
                        html = `<span class='badge badge-primary'>${code}</span>`;
                   
                        container.append(html);
                }
           },
           {
                dataField:"module_head",
                caption:"Assigned Head",
                visible:false,
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
               dataField: "Action",
               caption: "Action",
               width:100,
               cellTemplate: function (container, options) {

                   var permission_id = options.data.id;
                   var permission_name = options.data.name;
                   var permission_type = options.data.type;
                   var permission_code = options.data.code;
                   var module_all_heads = options.data.model_permissions;
                  
                   var module_head = options.data.module_head;

                   if(module_head !== null) {
                       module_head = options.data.module_head.user_id;
                   }
                   var tcodes = options.data.tcodes;
                   var markup = ``;
                   var actions = '';
                   var action_markup = ``;
                   var checked = '';
                 
                    
               
                var link = $(`<a href="javascript:void(0)" id='link_${permission_id}' title="edit">`).html("<i class='fa fa-edit'></i> Edit")
                    .attr("href", "javascript:void(0)")

                link.on("click", function () {
               
                    $("#settings-modal").modal('show');
                    $("#emodule").val(permission_name).trigger('change');
                    $("#epermission_id").val(permission_id);
                    $("#epermission_code").val(permission_code);
                    $("#epermission_type").val(permission_type).trigger('change');
                
                    var mheads_markup = ``;
                   $.each(module_all_heads, (i) => {
                       if(module_all_heads[i].users !== null) {
                            mheads_markup += `<option value='${module_all_heads[i].users.id}'>${module_all_heads[i].users.name} </option>`
                       }
                   });

                    $("#emodule_head").html(mheads_markup);

                    setTimeout(() => {
                        $("#emodule_head").val(module_head).trigger('change');
                    },1500);
                    
                    $("#tcode_section").html(markup);
                    
                   
                })
                
                return link;

               }
           },
       ],
   });
    
}


    

    
    </script>
@stop
