@extends('adminlte::page')

@section('title', 'Employees')

@section('content_header')
    <!-- <h1>employees List</h1> -->
@stop

@section('content')
    <style>
        .no-assets{
            height: 30vh;
            background-color: #cccc;
            display: flex;
            align-items: center;
            flex: 1;
            justify-content: center;
        }
    </style>
    <div class="tab-content p-1">
        <div class="tab-pane active dx-viewport" id="employees">
           
            <div class="demo-container">
                <div class="top-info">
                  <div class="table-heading-custom"><h4 class="right"><i class="fas fa-user-friends"></i> User List </h4></div>
                    @if(isset($permission))
                        @if($permission['add'] === true)
                        <div class="wrapper">
                            <button id="add_employee_btn" class='custom-theme-btn'><i class='fa fa-plus'></i> Create User</button>
                            <button id="import_employees" class='custom-theme-btn'><i class='fa fa-file-import'></i> Import Users</button>
                        </div>
                        
                        @endif
                    @endif
               </div>
                <div id="employee-list-div" style="height:600px"></div>
            </div>
        </div>
    </div>
  
  <!-- Add Modal -->
  <div class="modal fade" id="add-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add Employee</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form id="add-employee-frm" method="post">
                <div class="row">
                    <div class="col-lg-4 pt-2">
                        <label for="first_name">First Name <span class="text-red">*</span></label>
                        <input type="text" name="first_name" placeholder="Enter First Name" class="form-control">
                    </div>
                    <div class="col-lg-4 pt-2">
                        <label for="last_name">Last Name <span class="text-red">*</span></label>
                        <input type="text" name="last_name" placeholder="Enter Last Name" class="form-control">
                    </div>
                    <div class="col-lg-4 pt-2">
                        <label for="email">Email <span class="text-red">*</span></label>
                        <input type="text" name="email" placeholder="Enter Email Address" class="form-control">
                    </div>
                    <div class="col-lg-4 pt-2">
                        <label for="contact_no">Contact No <span class="text-red">*</span></label>
                        <input type="text" name="contact_no" placeholder="Mobile No" class="form-control">
                    </div>
                    <div class="col-lg-4 pt-2">
                        <label for="contact_no">Sap Code <span class="text-red">*</span></label>
                        <input type="text" name="sap_code" placeholder="SAP Code" class="form-control">
                    </div>
                    <div class="col-lg-4 pt-2">
                        <label for="state">State <span class="text-red">*</span></label>
                        <select name="state" class="states form-control select2bs4" data-placeholder="Select State" id="states">
                            <option value=""></option>
                        </select>
                    </div>
                    <div class="col-lg-4 pt-2">
                        <label for="district">District <span class="text-red">*</span></label>
                        <select name="district" class="districts form-control select2bs4" data-placeholder="Select District" id="districts">
                            <option value=""></option>
                        </select>
                    </div>
                    <div class="col-lg-4 pt-2">
                        <label for="company_code">Company Code <span class="text-red">*</span></label>
                        <select name="company_code" data-placeholder="Select Company" class="companies form-control select2bs4" id="companies">
                            <option value=""></option>
                        </select>
                    </div>
                    <div class="col-lg-4 pt-2">
                        <label for="department">Department <span class="text-red">*</span></label>
                        <select name="department" data-placeholder="Select Department" class="departments form-control select2bs4" id="departments">
                            <option value=""></option>
                        </select>
                    </div>
                    <div class="col-lg-4 pt-2">
                        <label for="department">Designation <span class="text-red">*</span></label>
                        <select name="designation" data-placeholder="Select Designation" class="form-control select2bs4">
                            <option value=""></option>
                            @foreach($designations as $designation)
                                <option value="{{ $designation->id }}">{{ $designation->designation_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-4 pt-2 d-none">
                        <label for="department">Role <span class="text-red">*</span></label>
                        <select name="role" data-placeholder="Select Role" class="roles form-control select2bs4" id="roles">
                            <option value=""></option>
                        </select>
                    </div>
                    <div class="col-lg-4 pt-2">
                        <label for="reporting_to">Reporting To <span class="text-red">*</span></label>
                        <select name="reporting_to" class="reporting_to form-control select2bs4" id="reporting_tos">
                            <option value=""></option>
                        </select>
                    </div>
                    <div class="col-lg-4 pt-2">
                        <label for="pincode">Pincode<span class="text-red">*</span></label>
                        <input type="number" name="pincode" placeholder="Pincode" class="form-control">
                    </div>
                    <div class="col-lg-4 pt-2">
                        <label for="address">Address </label>
                        <textarea name="address" id="address" placeholder="Present Address" class="form-control"></textarea>
                    </div>
                    <div class="col-lg-4 pt-2">
                        <label class="text-red">* Please fill all the mandatory fields before submitting</label>
                        <input type="submit" name="submit" class="btn btn-primary" id="add-employee">
                    </div>
                </div>
            </form>

            
          
        </div>
     
      </div>
    </div>
  </div>

  <!-- Import User -->
  <div class="modal fade" id="import-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Import Bulk Employees</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form id="import-user-frm" method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-lg-4 pt-2">
                        <label for="file">File <span class="text-red">*</span></label>
                        <input type="file" name="file" id="file" class="form-control">
                        <a href="{{ asset('assets/samples/sample.xlsx') }}">Sample Excel (To be followed)</a>
                    </div>
                
                    <div class="col-lg-4 pt-2 mt-4">
                       
                        <input type="submit" name="submit" class="btn btn-primary" id="import-employees">
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
          <h5 class="modal-title" id="exampleModalLabel">Edit Employee</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form id="edit-employee-frm" method="post">
                <div class="row">
                    <div class="col-lg-4 pt-2">
                        <input type="hidden" id="id1" name="id1">
                        <label for="first_name">First Name <span class="text-red">*</span></label>
                        <input type="text" name="first_name1" id="first_name1" placeholder="Enter First Name" class="form-control">
                    </div>
                    <div class="col-lg-4 pt-2">
                        <label for="last_name">Last Name <span class="text-red">*</span></label>
                        <input type="text" name="last_name1" id="last_name1" placeholder="Enter Last Name" class="form-control">
                    </div>
                    <div class="col-lg-4 pt-2">
                        <label for="email">Email <span class="text-red">*</span></label>
                        <input type="email" name="email1" id="email1" placeholder="Enter Email Address" class="form-control">
                    </div>
                    <div class="col-lg-4 pt-2">
                        <label for="contact_no">Contact No <span class="text-red">*</span></label>
                        <input type="text" name="contact_no1" id="contact_no1" placeholder="Mobile No" class="form-control">
                    </div>
                    <div class="col-lg-4 pt-2">
                        <label for="contact_no">SAP Code <span class="text-red">*</span></label>
                        <input type="text" name="sap_code1" id="sap_code1" placeholder="Mobile No" class="form-control">
                    </div>
                    <div class="col-lg-4 pt-2">
                        <label for="state">State <span class="text-red">*</span></label>
                        <select name="state1" class="states form-control select2bs4" data-placeholder="Select State" id="states1">
                            <option value=""></option>
                        </select>
                    </div>
                    <div class="col-lg-4 pt-2">
                        <label for="district">District <span class="text-red">*</span></label>
                        <select name="district1" class="districts form-control select2bs4" data-placeholder="Select District" id="districts1">
                            <option value=""></option>
                        </select>
                    </div>
                    <div class="col-lg-4 pt-2">
                        <label for="company_code">Company Code <span class="text-red">*</span></label>
                        <select name="company_code1" data-placeholder="Select Company" class="companies form-control select2bs4" id="companies1">
                            <option value=""></option>
                        </select>
                    </div>
                    <div class="col-lg-4 pt-2">
                        <label for="department">Department <span class="text-red">*</span></label>
                        <select name="department1" data-placeholder="Select Department" class="departments form-control select2bs4" id="departments1">
                            <option value=""></option>
                        </select>
                    </div>
                    <div class="col-lg-4 pt-2 d-none">
                        <label for="department">Role <span class="text-red">*</span></label>
                        <select name="role1" data-placeholder="Select Role" class="roles form-control select2bs4" id="roles1">
                            <option value=""></option>
                        </select>
                    </div>
                    <div class="col-lg-4 pt-2">
                        <label for="reporting_to">Reporting To <span class="text-red">*</span></label>
                        <select name="reporting_to1" class="reporting_to form-control select2bs4" id="reporting_tos1">
                            <option value=""></option>
                        </select>
                    </div>
                    <div class="col-lg-4 pt-2">
                        <label for="pincode">Pincode<span class="text-red">*</span></label>
                        <input type="number" name="pincode1" id="pincode1" placeholder="Pincode" class="form-control">
                    </div>
                    <div class="col-lg-4 pt-2">
                        <label for="address">Address </label>
                        <textarea name="address1" id="address1" placeholder="Present Address" class="form-control"></textarea>
                    </div>
                    <div class="col-lg-4 pt-2">
                        <label class="text-red">* Please fill all the mandatory fields before submitting</label>
                       
                        @if(isset($permission))
                            @if($permission['edit'] === true)
                            &nbsp;&nbsp;
                            <input type="submit" name="submit" class="btn btn-primary" id="edit-employee" value="Save Changes">
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


  <!-- Assets Modal -->
  <div class="modal fade" id="asset-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Employee Assets</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="assets_block"></div>
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
    $("#import-employees").on('click', (e) => {
        var formData = new FormData();
        formData.append('file', $('#file')[0].files[0]);

        $.ajax({
            url : "{{ route('import.users') }}",
            type : 'POST',
            data : formData,
            processData: false,  // tell jQuery not to process the data
            contentType: false,  // tell jQuery not to set contentType
            success : function(data) {
                console.log(data);
                toastr.success(data)
            }
        });
    });


    $("#date").datepicker();
    $("#add_employee_btn").click(()=> {

        $("#add-modal").modal('show');
    })
    $("#import_employees").click(()=> {

        $("#import-modal").modal('show');
    })

    const EDITABLE = "{{ $permission['edit'] ? true : false }}";
   // console.log('Hi!');
    
        fetch_employees();

    function fetch_employees() {
        var url = `{{ route('fetch.employees',1) }} `;
    $(`#employee-list-div`).dxDataGrid({
        dataSource: DevExpress.data.AspNet.createStore({
           // key: 'engineer_id',
            loadUrl: url,
        }),
        showBorders: true,
        showRowLines:true,
        allowColumnResizing: false,
        columnAutoWidth:true,
        columnHidingEnabled: false,
        rowAlternationEnabled: true,
        filterRow: { 
         visible: true
       },
        loadPanel: {
           // indicatorSrc: `${ASSET_URL}/assets/images/loader4.gif`,
            text: 'Loading...',
            showPane: true
        },
        selection: {
            mode: "single"
        },
        scrolling: {
            mode: "virtual",
            scrollByContent: true,
        },
        paging: {
            enabled: false,
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
                width:65,
                visible:false,
            },
            {
                caption: 'Name',
                fixed:true,
                dataField:"first_name",
                cellTemplate:(container, options) => {
                    console.log(options)
                    var name = options.data.first_name+' '+options.data.last_name;
                    var id = btoa(options.data.id)
                    var html = `<a href='${route('employee.profile', id)}' class="empl-name" target='_blank'>${name}</a>`
                    container.append(html)
                }
               
            },
            {
                dataField: "email",
                caption: 'Email',
               
            },
            {
                dataField:"contact_no",
                caption:"Contact No"
            },
            {
                dataField:"sap_code",
                caption:"SAP Code",
                width:100,
            },
            {
                dataField:"state.state_name",
                caption:"State"
            },
            {
                dataField:"district.district_name",
                caption:"District"
            },
            {
                dataField:"company.company_name",
                caption:"Company"
            },
            {
                dataField:"departments.department_name",
                caption:"Department"
            },
            {
                dataField:"report_to.report_employee.first_name",
                caption:"Report To",
                width:100,
                cellTemplate:(container, options) => {
                    console.log(options)
                    var name = `-`;
                    if(options.data.report_to !== null) {

                        name = options.data.report_to.report_employee.first_name+' '+options.data.report_to.report_employee.last_name
                    }
                   
                    container.append(name)
                }
                
            },
            {
                dataField:"pincode",
                dataType: "number",
            },
            {
                dataField:"address",
            },
            {
                dataField: "created_at",
                caption: 'Created At',
                dataType: "date",
                width:100,
                format: "d/M/yyyy"
               
            },
            {
                allowFiltering: false,
                dataField: "Action",
                fixed:true,
                fixedPosition: "right",
                caption: 'Action',
                visible: EDITABLE,
                alignment: 'left',
                cssClass: '__Action',
                cellTemplate: function (container, options) {
                    var data = options.data;
                   
                        var link = $(`<a class="edit_info" href="javascript:void(0)" title="edit">`).html("<i class='fas fa-edit'></i> Edit")
                        .attr("href", "javascript:void(0)")
                    
                    link.on("click", function () {
                        $("#edit-modal").modal('show');
                        $("#id1").val(data.id);
                        $("#first_name1").val(data.first_name);
                        $("#last_name1").val(data.last_name);
                        $("#email1").val(data.email);
                        $("#contact_no1").val(data.contact_no);
                        $(".states").val(data.state_id).trigger('change');
                        $("#companies1").val(data.company.id).trigger('change');
                        $("#pincode1").val(data.pincode);
                        $("#address1").val(data.address);
                        $("#sap_code1").val(data.sap_code);
                       
                        setTimeout(() => {
                            $("#districts1").val(data.district_id).trigger('change');
                            $(".departments").val(data.departments.id).trigger('change');
                            setTimeout(() => {
                                if(data.report_to.report_employee !== null) {
                                    $("#reporting_tos1").val(data.report_to.report_employee.id).trigger('change');
                                }
                            
                            }, 1000);
                        },1000);
                    
                    })
                    //container.append(html)
                    return link;

                }
            },
            {
                allowFiltering: false,
                dataField: "Action",
                caption: 'Assets',
                fixed:true,
                width:100,
                fixedPosition: "right",
                visible: EDITABLE,
                alignment: 'center',
                cssClass: '__Action',
                cellTemplate: function (container, options) {
                    var data = options.data;
                   
                        var link = $(`<a class="permission_info" href="javascript:void(0)" title="View Assets">`).html("<i class='fas fa-eye'></i> View")
                        .attr("href", "javascript:void(0)")
                    
                    link.on("click", function () {
                        var assetsInfo = options.data.assets;
                        var assetHTML = ``;

                        if(assetsInfo.length === 0) {
                            assetHTML += `<h5 class='no-assets text-center'>No assets found</h5>`
                        } else {

                            assetHTML += `<table class='table table-bordered'><thead>
                                <th>Serial No</th>
                                <th>Asset Type</th>
                                <th>Qty</th>
                                <th>Asset Company</th>
                                <th>Issue Date</th>
                            </thead>
                            <tbody>`;

                            $.each(assetsInfo, (i) => {
                                assetHTML += `<tr>
                                    <td>${assetsInfo[i].asset.serial_number}</td>
                                    <td>${assetsInfo[i].asset.type.asset_type}</td>
                                    <td>1</td>
                                    <td>${assetsInfo[i].asset.company}</td>
                                    <td>${assetsInfo[i].created_at}</td>
                                </tr>`;
                            });

                            assetHTML += `</tbody></table>`;

                        }

                        
                        $("#assets_block").html(assetHTML);
                        $("#asset-modal").modal('show');
                        
                    
                    })
                    //container.append(html)
                    return link;

                }
            },
        ]
    })
    }
    
    /** Add Employee */
    $(document).on('click','#add-employee', (e)=> {
        //e.preventDefault();
    
        $("#add-employee-frm").validate({
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
                // reporting_to:{
                //     required:true
                // }

            },
            messages:{

            },
            submitHandler: (r) => {
                console.log('reached submit handler');
                //return false;
                $.ajax({
                    url: "{{  route('add.employee') }}",
                    headers:{
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    type:"POST",
                    data:$("#add-employee-frm").serialize(),
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
                            $("#add-employee-frm")[0].reset();
                            $("#add-modal").modal('hide');
                            fetch_employees();
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
    $(document).on('click','#edit-employee', (e)=> {
        //e.preventDefault();
    
        $("#edit-employee-frm").validate({
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
                    url: "{{  route('edit.employee') }}",
                    headers:{
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    type:"POST",
                    data:$("#edit-employee-frm").serialize(),
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
                            $("#edit-employee-frm")[0].reset();
                            $("#edit-modal").modal('hide');
                            fetch_employees();
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
    
        $("#edit-employee-frm").validate({
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
                    data:$("#edit-employee-frm").serialize(),
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
                            $("#edit-employee-frm")[0].reset();
                            $("#edit-modal").modal('hide');
                            fetch_employees();
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
                fetch_employees();
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
                fetch_employees();
                console.log(r)

            }
        })

        
    })
   
    
    
    
    
     </script>
@stop
