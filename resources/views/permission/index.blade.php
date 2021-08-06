@extends('adminlte::page')

@section('title', 'Modules')

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
        <div class="font-weight-bold m-2 font-italic text-primary"><h4 class="right"><i class="fas fa-user-lock"></i> Module & T Code List </h4></div>
        <div class="tab-pane active dx-viewport" id="users">
            <div class="demo-container p-3">
            <button id="add_permission" class='btn btn-primary p-1'><i class='fa fa-plus'></i> Module</button>
            &nbsp;
            <button id="add_tcodes" class='btn btn-primary p-1'><i class='fa fa-plus'></i> T-CODES</button>
                <div id="roles-list-div" style="height:600px"></div>
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
                        <div class="col-lg-4 pt-2">

                            <select name="permission_type" id="permission_type" data-placeholder="Module Type" class="select2bs4 form-control">
                                <option value=""></option>
                                <option value="1">User</option>
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
                        @foreach($actions as $action)
                            <input type="checkbox" name="actions[]" id="actions" value="{{ $action->id }}"> {{  $action->name }}
                        @endforeach
                    </div>
                    
                    <div class="col-lg-4 pt-2">
                        <button class='btn btn-primary' type="submit" id="add-tcode-btn" name='add-tcode-btn'><i class='fas fa-plus'></i> Add</button>
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
                        <div class="col-lg-4 pt-2">
                            <label for="permission_name">
                                Module Type
                            </label> 
                            <select name="epermission_type" id="epermission_type" data-placeholder="Permission Type" class="select2bs4 form-control">
                                <option value=""></option>
                                <option value="1">User</option>
                                <option value="2">SAP</option>
                            </select>
                        </div>
                        <div class="col-lg-12 pt-2">
                            <div id="tcode_section"></div>
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
    
@stop

@section('css')
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')

    <script>
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
                console.log(r)
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
   // console.log('inn')
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
        console.log('next')
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
                $("#add-permission-btn").prop('disabled',false);
                toastr.error('Something went wrong');
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
           var url = "{{ route('show.permissions') }}"
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
           pageSize: 10
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
       columns: [
        //    {
        //        dataField: "id",
        //        caption: "Id",
        //        width:50,
        //        visibe: true,
        //    },
           {
               dataField: "name",
               caption: "Module Name",
           },
           {
                dataField:"type",
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
                dataField:"module_head",
                caption:"Module Owner",
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
                caption:"Tcodes",
                cellTemplate: (container, options) => {
                    var codes = options.data.tcodes;
                    console.log(codes);
                    var html = '';
                    $.each(codes, (i) => {
                        html += `<span class='badge badge-primary' title='${codes[i].description}'>${codes[i].t_code}</span>&nbsp;`;
                    })
                      //  html = `<span class='badge badge-primary'>${code}</span>`;
                   
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
                //    console.log('Modules:');
                //    console.log(module_head);
                   if(options.data.module_head !== null) {
                       module_head = options.data.module_head.user_id;
                   }
                   var tcodes = options.data.tcodes;
                   var markup = `<h4>T Codes</h4>`;
                   var actions = '';
                   var action_markup = ``;
                   var checked = '';
                   var all_actions = <?php echo $actions ?>    
                    


            
                   $.each(tcodes, (i) => {

                    actions = tcodes[i].action_details;
                    action_markup = '';
                
                        $.each(all_actions, (i) => {
                            checked = '';
                            $.each(actions, (j) => {
                                if(all_actions[i].id == actions[j].id) {
                                    checked = `checked='checked'`;
                                }
                            });
                          
                        action_markup += `<input type='checkbox' name='actions[]' value='${all_actions[i].id}' ${checked}> ${all_actions[i].name} &nbsp;`;
                    
                    })
                   

                       markup += `
                       <div class="accordion" id="accordion2">
                        <div class="accordion-group">
                            <div class="accordion-heading">
                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse${tcodes[i].t_code}">
                            ${tcodes[i].description} (${tcodes[i].t_code})
                            </a>
                            <span class='trash_module' onclick='trashTcode(${tcodes[i].id},${tcodes[i].permission_id})'><i class='p-1 bg-danger fas fa-trash'></i></span>
                            </div>
                        <div id="collapse${tcodes[i].t_code}" class="accordion-body collapse">
                            <div class="accordion-inner">
                                ${action_markup}
                            </div>
                        </div>
                        </div>
                        </div>`;
                   });
               
                var link = $(`<a href="javascript:void(0)" id='link_${permission_id}' title="edit">`).html("<i class='fa fa-edit'></i> Edit")
                    .attr("href", "javascript:void(0)")

                link.on("click", function () {
                    console.log(module_all_heads);
                    // console.log(all_actions)
                    if(tcodes.length == 0) {
                       markup = `<br><span>No T Codes assigned</span>`
                   }
                    $("#settings-modal").modal('show');
                    $("#emodule").val(permission_name).trigger('change');
                    $("#epermission_id").val(permission_id);
                    $("#epermission_code").val(permission_code);
                    $("#epermission_type").val(permission_type).trigger('change');
                  



                    var mheads_markup = ``;
                    console.log('module all head')
                    console.log(module_all_heads)
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
