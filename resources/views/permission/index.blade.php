@extends('adminlte::page')

@section('title', 'Modules')

@section('content_header')
<meta http-equiv="x-ua-compatible" content="ie=edge">
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css">
  
@stop

@section('content')

    <div class="tab-content p-1">
         
        <div class="tab-pane active dx-viewport" id="users">
            <div class="demo-container">
                <div class="top-info">
                    <div class="table-heading-custom"><h4 class="right"><i class="fas fa-user-lock"></i> Module & T Code List </h4></div>
                    <div class="multibtn-sec">
                       <button id="add_permission" class='custom-theme-btn'><i class='fa fa-plus'></i> Module</button>
                        <button id="add_tcodes" class='custom-theme-btn'><i class='fa fa-plus'></i> T-CODES</button> 
                    </div>
                    
              </div>
                <div id="roles-list-div" style="height:600px"></div>
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
    <!-- Add Modal -->
    <div class="modal fade" id="add-permission-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Module</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
        
                <form id="add-permission-frm" method="post">
                    <div class="row">
                        <div class="col-lg-4 pt-2">

                            <input type="text" name="permission_name" id="permission_name" class="form-control" placeholder="Enter module name">
                        </div>
                        <div class="col-lg-4 pt-2">

                            <input type="text" name="permission_code" id="permission_code" class="form-control" placeholder="Enter module code">
                        </div>
                        <div class="col-lg-4 pt-2 d-none">

                            <select name="permission_type" id="permission_type" data-placeholder="Module Type" class="select2bs4 form-control">
                                {{-- <option value=""></option>
                                <option value="1">User</option> --}}
                                <option value="2">SAP</option>
                            </select>
                        </div>
                        <div class="col-lg-4 pt-2">

                            <select name="module_head" id="module_head" data-placeholder="Module Head" class="select2bs4 form-control">
                                <option value=""></option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                       
                        <div class="col-lg-4 pt-2">
                            <button class='btn btn-primary' type="submit" id="add-permission-btn" name='add-permission-btn'>Submit</button>
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
    <!-- Add T COdes -->
    <div class="modal fade" id="add-tcode-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add T Code</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
    
            <form id="add-tcode-frm" method="post">
                <div class="row">
                    <div class="col-lg-4 pt-2">

                        <select name="module" id="module" data-placeholder="Select Module" class="select2bs4 form-control">
                            <option value=""></option>
                           @foreach($modules as $module)
                                <option value="{{ $module->id }}">{{ $module->name }}</option>
                           @endforeach
                        </select>
                    </div>
                    <div class="col-lg-4 pt-2">

                        <input type="text" name="tcode" id="tcode" class="form-control" placeholder="Enter T-CODE">
                    </div>
                    <div class="col-lg-4 pt-2">

                        <input type="text" name="tcode_desc" id="tcode_desc" class="form-control" placeholder="Enter Description">
                    </div>
                    <div class="col-lg-12 pt-2">
                        <label for="actions">Actions</label>
                        <div class="row">
                            @foreach($actions as $action)
                            <div class="checkbox-group col-lg-2">
                                <input type="checkbox" name="actions[]" class="mr-2" id="actions" value="{{ $action->id }}"><span>{{  $action->name }}</span> 
                             </div>   
                            @endforeach
                        </div>
                        
                    </div>
                    <div class="col-lg-3 pt-2">
                        <label for="status">
                            Tcode Type
                        </label>
                            <select name="tcode_type" data-placeholder="Tcode Type" id="tcode_type1" class="form-control select2bs4">
                                <option value=""></option>
                                <option value="0">Non Critical</option>
                                <option value="1">Critical</option>
                            </select>
                    </div>
                    <div class="col-lg-4 pt-2 mt-4">
                        <button class='btn btn-primary mt-1' type="submit" id="add-tcode-btn" name='add-tcode-btn'>Submit</button>
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
                                Module Name
                            </label> 
                            <select name="emodule" id="emodule" data-placeholder="Select Module" class="select2bs4 form-control">
                                <option value=""></option>
                               @foreach($modules as $module)
                                    <option value="{{ $module->name }}">{{ $module->name }}</option>
                               @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4 pt-2">
                           
                                 <label for="permission_name">
                                    Module Head
                                </label> 
                                <select name="emodule_head" id="emodule_head" data-placeholder="Select Module Head" class="select2bs4 form-control">
                                    <option value=""></option>
                                </select>
                            </div>
                        <div class="col-lg-4 pt-2">
                            <label for="permission_name">
                                Module Code
                            </label> 
                            <input type="text" name="epermission_code" id="epermission_code" class="form-control" placeholder="Enter permission code">
                        </div>
                        <div class="col-lg-4 pt-2 d-none">
                            <label for="permission_name">
                                Module Type
                            </label> 
                            <select name="epermission_type" id="epermission_type" data-placeholder="Permission Type" class="select2bs4 form-control">
                                {{-- <option value=""></option>
                                <option value="1">User</option> --}}
                                <option value="2">SAP</option>
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
                        <div class="col-lg-3 pt-2">
                            <label for="status">
                                Tcode Type
                            </label>
                                <select name="tcode_type" data-placeholder="Tcode Type" id="tcode_type" class="form-control select2bs4">
                                    <option value=""></option>
                                    <option value="0">Non Critical</option>
                                    <option value="1">Critical</option>
                                </select>
                        </div>
                        <div class="col-lg-12 pt-2">
                            <div id="t_actions" class="row m-0"></div>
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
            //    "skip",
            //    "take",
             //  "requireTotalCount",
               "sort",
               "filter",
           ].forEach(function (i) {
               if (i in loadOptions && isNotEmpty(loadOptions[i]))
                   args[i] = JSON.stringify(loadOptions[i]);
           })

        //    let take = loadOptions.take
        //    let skip = loadOptions.skip
           var dataSet = []
           var url = "{{ route('show.permissions') }}"
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
       allowColumnResizing: false,
       columnAutoWidth:true,
       columnHidingEnabled: false,
       sorting: false,
       loadPanel: {
        //indicatorSrc: `${ASSET_URL}/assets/images/loader4.gif`,
        text: "Loading...",
        showPane: true,
       },
       filterRow: { 
         visible: true
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
           scroll:"virtual",
           scrollByContent: true,
       },
       wordWrapEnabled: true,
       columns: [
           {
               dataField: "name",
               caption: "Module Name",
           },
           {
                dataField:"type",
                visible:false,
                caption:"Module Type",
                cellTemplate: (container, options) => {
                    var type = options.data.type;
                    var html = '';
                    if(type == 1) {
                        html = "<span class='badge badge-primary'>User</span>";
                    } else if(type == 2) {
                        html = "<span class='badge badge-primary'>SAP</span>";
                    }
                    container.append(html);
                }
           },
           {
                dataField:"code",
                caption:"Module Code",
                cellTemplate: (container, options) => {
                    var code = options.data.code;
                    var html = '';
                        html = `<span class='badge badge-primary'>${code}</span>`;
                   
                        container.append(html);
                }
           },
           {
                dataField:"module_head.user_details.name",
                caption:"Module Owner",
                cellTemplate: (container, options) => {
                    var module_head = options.data.module_head;
                    var html = '';
                    if(module_head !== null) {
                        module_head = module_head.user_details.name
                        html = `${module_head}`;
                    } else {
                        module_head = '<span class="not_assigned_text text-red text-bold">Not Assigned</span>';
                        html = `<span class="not_assigned_text text-dark text-bold">${module_head}</span>`;
                    }
                  
                        
                   
                        container.append(html);
                }
           },
           {
                dataField:"tcodes",
                caption:"Tcodes",
                allowFiltering: false,
                cellTemplate: (container, options) => {
                    var permission_id = options.data.id;
                    var html = `<a href='javascript:void(0)' class='custom-theme-btn' onClick='showTcodes(${permission_id})'> Show TCodes</a>`;
                        container.append(html);
                }
           },
           {
               dataField: "Action",
               caption: "Action",
               allowFiltering: false,
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
                   var markup = `<h4>T Codes</h4>`;
                   var actions = '';
                   var action_markup = ``;
                   var checked = '';
                   var all_actions = <?php echo $actions ?>    
                    
               
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
           var url = "{{ route('fetch.module.tcodes') }}"
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
                                dataField:"tcodes",
                                caption:"Tcodes",
                                cellTemplate: (container, options) => {
                                    var tcode = options.data.t_code;
                                    var html = `<a href='javascript:void(0)'>${tcode}</a>`;
                                
                                        container.append(html);
                                }
                        },
                        {
                                dataField:"description",
                                caption:"Description",
                                cellTemplate: (container, options) => {
                                    var desc = options.data.description;
                                        container.append(desc);
                                }
                        },
                        {
                                dataField:"critical_tcode",
                                caption:"Critical Tcode",
                                cellTemplate: (container, options) => {
                                    var critical = options.data.critical_tcodes;
                                    var html = `<span class='badge badge-primary'>No</span>`;
                                    if(critical !== null) {
                                        html = `<span class='badge badge-danger'>Yes</span>`
                                    }
                                        container.append(html);
                                }
                        },
                        {
                                dataField:"action_details",
                                caption:"Actions",
                                cellTemplate: (container, options) => {
                                    var action_details = options.data.action_details;
                                    var html = ``;
                                    $.each(action_details,  (i) => {
                                        html += `<a href='javascript:void(0)' class='badge badge-primary text-white'>${action_details[i].name}</a>&nbsp;`;
                                    })
                                    
                                
                                        container.append(html);
                                }
                        },
                        {
                                dataField:"status",
                                caption:"Status",
                                cellTemplate: (container, options) => {
                                    var status = options.data.status;
                                    var html = ``;
                                    if(status == 1) {
                                        html = `<a href='javascript:void(0)' class='badge badge-success text-white'>Active</a>&nbsp;`;
                                    } else {
                                        html = `<a href='javascript:void(0)' class='badge badge-danger text-white'>Inactive</a>&nbsp;`;
                                    }
                                        
                                    
                                    
                                
                                        container.append(html);
                                }
                        },
                        {
                                dataField:"edit",
                                caption:"Edit",
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
                if(data.critical_tcodes !== null) {
                    $("#tcode_type").val(1).trigger('change');
                } else {
                    $("#tcode_type").val(0).trigger('change');
                }
                
                var actions = data.action_details;
                var all_actions = <?php echo $actions ?>



                var action_markup = '';
            
                    $.each(all_actions, (i) => {
                        checked = '';
                        $.each(actions, (j) => {
                            if(all_actions[i].id == actions[j].id) {
                                checked = `checked='checked'`;
                            }
                        });
                        
                    action_markup += `<div class="checkbox-group mr-2"><input type='checkbox'  name='t_actions[]' value='${all_actions[i].id}' ${checked}> <span class="ml-2">${all_actions[i].name}</span> </div>`;
                
                })
                $("#t_actions").html(action_markup);
            }

    
    </script>
   
@stop
