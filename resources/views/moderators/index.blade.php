@extends('adminlte::page')

@section('title', 'Moderators')

@section('content_header')
    <!-- <h1>employees List</h1> -->
@stop

@section('content')
    
    <div class="tab-content p-1">
        <div class="tab-pane active dx-viewport" id="employees">
           
            <div class="demo-container">
                <div class="top-info">
                  <div class="table-heading-custom"><h4 class="right"><i class="fas fa-user-friends"></i> Moderators </h4></div>
                    @if(isset($permission))
                        @if($permission['add'] === true)
                        <button id="add_employee_btn" class='custom-theme-btn'><i class='fa fa-plus'></i> Create Moderators</button>
                        @endif
                    @endif
              </div>
                <div id="moderators-list-div" style="height:auto"></div>
            </div>
        </div>
    </div>
  
  <!-- Add Modal -->
  <div class="modal fade" id="add-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add Moderators</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form id="add-moderator-frm" method="post">
                <div class="row">
                    <div class="col-lg-4 pt-2">
                        <label for="first_name">User <span class="text-red">*</span></label>
                        <select name="employee_id" id="employee_id" class="form-control select2bs4" data-placeholder="Select User">
                            <option></option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}"> {{ $employee->first_name .' '.$employee->last_name }}</option>

                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-4 pt-2">
                        <label for="last_name">Moderator Type <span class="text-red">*</span></label>
                        <select name="type" id="moderator_type" class="form-control select2bs4" data-placeholder="Select Type">
                            <option></option>
                            <option value="1">SAP Lead</option>
                            <option value="2">Director</option>
                            <option value="3">IT Head</option>
                            <option value="4">Basis</option>
                        </select>
                    </div>
                   
                    <div class="col-lg-4" style="padding-top: 3.3%;">
                        <input type="submit" name="submit" class="btn btn-primary" id="add-moderator">
                    </div>
                </div>
            </form>

            
          
        </div>
     
      </div>
    </div>
  </div>

<!-- Edit Modal -->
  <div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Edit Moderator</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form id="edit-moderator-frm" method="post">
                <input type="hidden" id='id1' name="id">
                <div class="row">
                    <div class="col-lg-4 pt-2">
                        <label for="first_name">User <span class="text-red">*</span></label>
                        <select name="employee_id1" id="employee_id1" class="form-control select2bs4" data-placeholder="Select User">
                            <option></option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}"> {{ $employee->first_name .' '.$employee->last_name }}</option>

                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-4 pt-2">
                        <label for="last_name">Moderator Type <span class="text-red">*</span></label>
                        <select name="type1" id="moderator_type1" class="form-control select2bs4" data-placeholder='Select Type'>
                            <option value=""></option>
                            <option value="1">SAP Lead</option>
                            <option value="2">Director</option>
                            <option value="3">IT Head</option>
                            <option value="4">Basis</option>
                        </select>
                    </div>
                    <div class="col-lg-4" style="padding-top: 3.3%;">
                       
                        @if(isset($permission))
                            @if($permission['edit'] === true)
                            &nbsp;&nbsp;
                            <input type="submit" name="submit" class="btn btn-primary" id="edit-moderator" value="Save Changes">
                            @endif
                        @endif
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
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>

    <script> 
    
    $("#add_employee_btn").click(()=> {

        $("#add-modal").modal('show');
    })

    const EDITABLE = true;
   // console.log('Hi!');
    
        fetch_moderators();

    function fetch_moderators() {
        var url = `{{ route('fetch.moderators',1) }} `;
    $(`#moderators-list-div`).dxDataGrid({
        dataSource: DevExpress.data.AspNet.createStore({
           // key: 'engineer_id',
            loadUrl: url,
        }),
        showBorders: true,
        showRowLines:true,
        rowAlternationEnabled: true,
        allowColumnResizing: true,
        loadPanel: {
           // indicatorSrc: `${ASSET_URL}/assets/images/loader4.gif`,
            text: 'Loading...',
            showPane: true
        },
        selection: {
            mode: "single"
        },
        scrolling: {
            mode: "virtual"
        },
        paging: {
            enabled: true,
            pageSize:10
        },
        rowAlternationEnabled: false,
        wordWrapEnabled: true,
        scrolling: {
            scrollByContent: true,
        },
        export: {
            enabled: true,
            fileName: new Date() + ' user_list'
        },
        columns: [
            {
                dataField: "id",
                caption: 'Id',
                width:95
            },
            {
                caption: 'Name',
                dataField:"employee.first_name",
                cellTemplate:(container, options) => {
                    console.log(options)
                    var name = options.data.employee.first_name+' '+options.data.employee.last_name
                    container.append(name)
                }
               
            },
          
            {
                dataField:"type_id",
                caption:"Moderator Type",
                cellTemplate:(container, options) => {
                    console.log(options)
                    var type = options.data.type_id;
                    var type_name = '';
                    switch(type) {
                        case 1:
                        type_name = 'SAP Lead';
                        break;
                        case 2:
                        type_name = 'Director';
                        break;
                        case 3:
                        type_name = 'IT Head';
                        break;
                        case 4:
                        type_name = 'Basis (Final Approver)';
                        break;
                        default:
                        type_name = 'N/A';
                    }
                   
                    container.append(type_name)
                }
                
            },
            {
                dataField: "created_at",
                caption: 'Created At',
               
            },
           
            {
                dataField: "Action",
                caption: 'Action',
                width: 75,
                visible: EDITABLE,
                alignment: 'left',
                cssClass: '__Action',
                cellTemplate: function (container, options) {
                    var data = options.data;
                   
                        var link = $(`<a class="edit_info" href="javascript:void(0)" title="edit">`).html("<i class='fas fa-cog'></i> Change")
                        .attr("href", "javascript:void(0)")
                    
                    link.on("click", function () {
                        $("#edit-modal").modal('show');
                        $("#id1").val(data.id);
                        $("#employee_id1").val(data.employee_id).trigger('change');
                        $("#moderator_type1").val(data.type_id).trigger('change');
                    })
                    //container.append(html)
                    return link;

                }
            },


        ]
    })
    }
    
    /** Add Employee */
    $(document).on('click','#add-moderator', (e)=> {
        //e.preventDefault();
    
        $("#add-moderator-frm").validate({
            rules:{
               employee_id:{
                   required:true
               },
               type:{
                   required:true
               }

            },
            messages:{

            },
            submitHandler: (r) => {
                console.log('reached submit handler');
                //return false;
                $.ajax({
                    url: "{{  route('add.moderator') }}",
                    headers:{
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    type:"POST",
                    data:$("#add-moderator-frm").serialize(),
                    beforeSend:(r) => {
                        toastr.info('Adding...')
                    },
                    error:(r) => {
                        toastr.error('Server Error '+r.responseText)
                        $("#loaderGif").hide();

                    },
                    success:(r) => {

                        if(r.status == 200) {
                            toastr.success(r.message)
                            $("#loaderGif").hide();
                            $("#add-moderator-frm")[0].reset();
                            $("#add-modal").modal('hide');
                            fetch_moderators();
                            console.log(r)
                        } else {
                            toastr.success(r.message)
                            $("#loaderGif").hide();
                        }
                       

                    }
                })
            }
        })


        
    })
    /** Edit user */
    $(document).on('click','#edit-moderator', (e)=> {
        //e.preventDefault();
    
        $("#edit-moderator-frm").validate({
            rules:{
                employee_id1:{
                   required:true
               },
               type1:{
                   required:true
               }

            },
            messages:{

            },
            submitHandler: (r) => {
                console.log('reached submit handler');
                //return false;
                $.ajax({
                    url: "{{  route('edit.moderator') }}",
                    headers:{
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    type:"POST",
                    data:$("#edit-moderator-frm").serialize(),
                    beforeSend:(r) => {
                        toastr.info('Adding...')
                    },
                    error:(r) => {
                        toastr.error('Server Error '+r.responseText)
                        $("#loaderGif").hide();

                    },
                    success:(r) => {

                        if(r.status == 200) {
                            toastr.success(r.message)
                            $("#loaderGif").hide();
                            $("#edit-moderator-frm")[0].reset();
                            $("#edit-modal").modal('hide');
                            fetch_moderators();
                            console.log(r)
                        } else {
                            toastr.success(r.message)
                            $("#loaderGif").hide();
                        }
                       

                    }
                })
            }
        })   
    });

    /** Create user */
    $(document).on('click','#create-employee', (e)=> {
        //e.preventDefault();
    
        $("#edit-moderator-frm").validate({
            rules:{
                first_name:{
                    required:true
                },
                last_name:{
                    required:true
                },
                email:{
                    required:true,
                    email:true
                },
                contact_no:{
                    required:true,
                    number:true,
                    maxlength:11,
                    minlength:10
                },
                district:{
                    required:true
                },
                state:{
                    required:true
                },
                pincode:{
                    required:true
                },
                company_code:{
                    required:true
                },
                department:{
                    required:true
                },
                reporting_to:{
                    required:true
                }

            },
            messages:{

            },
            submitHandler: (r) => {
                console.log('reached submit handler');
                //return false;
                $.ajax({
                    url: "{{  route('create.employee') }}",
                    headers:{
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    type:"POST",
                    data:$("#edit-moderator-frm").serialize(),
                    beforeSend:(r) => {
                        toastr.info('Adding...')
                    },
                    error:(r) => {
                        toastr.error('Server Error '+r.responseText)
                        $("#loaderGif").hide();

                    },
                    success:(r) => {

                        if(r.status == 200) {
                            toastr.success(r.message)
                            $("#loaderGif").hide();
                            $("#edit-moderator-frm")[0].reset();
                            $("#edit-modal").modal('hide');
                            fetch_moderators();
                            console.log(r)
                        } else {
                            toastr.success(r.message)
                            $("#loaderGif").hide();
                        }
                       

                    }
                })
            }
        })


        
    })

    /** Roles update */
    $(document).on('click','#role-btn', (e)=> {
        e.preventDefault();
        var roles = []
        var ds = $("input[name='roles']:checked").each(function(i){
            roles[i] = this.value;
        });
        var user_id = $("#user_id_r").val();

        $.ajax({
            url: "{{  route('update.user.roles') }}",
            headers:{
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            type:"POST",
            data:{roles:roles,user_id:user_id},
            beforeSend:(r) => {
                toastr.info('Updating...')
            },
            error:(r) => {
                toastr.error('Server Error '+r.responseText)
                $("#loaderGif").hide();

            },
            success:(r) => {
                toastr.success('Settings updated successfully')
                $("#loaderGif").hide();
                $("#settings-modal").modal('hide');
                fetch_moderators();
                console.log(r)

            }
        })

        
    })

    /** Menu update */
    $(document).on('click','#menu-btn', (e)=> {
        e.preventDefault();
        var menus = []
        var ds = $("input[name='menus']:checked").each(function(i){
            menus[i] = this.value;
        });
        var user_id = $("#user_id_m").val();

        $.ajax({
            url: "{{  route('update.user.menus') }}",
            headers:{
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            type:"POST",
            data:{menus:menus,user_id:user_id},
            beforeSend:(r) => {
                toastr.info('Updating...')
            },
            error:(r) => {
                toastr.error('Server Error '+r.responseText)
                $("#loaderGif").hide();

            },
            success:(r) => {
                toastr.success('Settings updated successfully')
                $("#loaderGif").hide();
                $("#settings-modal").modal('hide');
                fetch_moderators();
                console.log(r)

            }
        })

        
    })
   
    
    
    
    
     </script>
@stop
