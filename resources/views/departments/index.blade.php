@extends('adminlte::page')

@section('title', 'Departments')

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
            <div class="table-heading-custom"><h4 class="right"><i class="fas fa-bars"></i> Department List </h4></div>
            <button id="add_department" class='custom-theme-btn'><i class='fa fa-plus'></i> Department</button>
          </div>
            
            <div id="department-list-div" style="height:600px"></div>
        </div>
    </div>
</div>

    <!-- Add Modal -->
    <div class="modal fade" id="add-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Department</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
        
                <form id="add-department-frm" method="post">
                    <div class="row">
                        <div class="col-lg-4 pt-2">
                            <input type="text" name="department_name" id="department_name" class="form-control" placeholder="Enter permission name">
                        </div>                       
                        <div class="col-lg-4 pt-2">
                            <button class='btn btn-primary' type="submit" id="add-department-btn" name='add-department-btn'><i class='fas fa-plus'></i> Add</button>
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
        <div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
        
                <form id="department-update-frm" method="post">
                    <div class="row">
                        <div class="col-lg-4 pt-2">
                        <input type="hidden" name="department_id" id="edepartment_id">
                             <label for="permission_name">
                                Department Name
                            </label> 
                           <input type="text" name="edepartment_name" class="form-control">
                        </div>

                        <div class="col-lg-4 pt-2">
                            <button class='btn btn-primary' type="submit" id="update-department-btn" name='update-department-btn'>Update</button>
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
        
      

$(document).on('click','#add_department', ()=> {
   // //'inn')
    $("#add-modal").modal('show');
});


$(document).on('click','#update-department-btn', ()=> {

$("#department-update-frm").validate({
    rules:{
        edepartment_name:{
            required:true
        },
    },
    submitHandler:(r) => {
        //'next')
        var url = "{{  route('update.department') }}"
        $.ajax({
            url:url,
            data:$("#department-update-frm").serialize(),
            type:"POST",
            headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            beforeSend:(r) => {
                $("#update-department-btn").prop('disabled',true);
            },
            error:(r) => {
                $("#update-department-btn").prop('disabled',false);
                toastr.error('Something went wrong');
            },
            success:(r) => {
                $("#update-department-btn").prop('disabled',false);
                toastr.success('Module Updated successfully');
                $("#edit-modal").modal('hide');
                fetch_data();
            }

        })
    }
});
})
$(document).on('click','#add-department-btn', ()=> {
$("#add-department-frm").validate({
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
        var url = "{{  route('add.department') }}"
        $.ajax({
            url:url,
            data:$("#add-department-frm").serialize(),
            type:"POST",
            headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            beforeSend:(r) => {
                $("#add-department-btn").prop('disabled',true);
            },
            error:(r) => {
                //r.responseJSON)
                $("#add-department-btn").prop('disabled',false);
                toastr.error(r.responseJSON.message);
            },
            success:(r) => {
                $("#add-department-btn").prop('disabled',false);
                toastr.success('Department Added successfully');
                $("#add-department-modal").modal('hide');
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
           var url = "{{ route('get.departments') }}"
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
   $("#department-list-div").dxDataGrid({
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
               dataField: "department_name",
               caption: "Department Name",
           },
         
           {
               dataField: "Action",
               caption: "Action",
               width:100,
               cellTemplate: function (container, options) {

                   var department_id = options.data.id;
                   var department_name = options.data.department_name;
                 
                   var markup = ``;
                   var actions = '';
                   var action_markup = ``;
                   var checked = '';
                 
                   var link = $(`<a href="javascript:void(0)" id='link_${department_id}' title="edit">`).html("<i class='fa fa-edit'></i> Edit")
                        .attr("href", "javascript:void(0)")

                    link.on("click", function () {
                
                        $("#edit-modal").modal('show');
                        $("#edepartment_name").val(department_name);
                        $("#edepartment_id").val(department_id);
                                        
                    
                    })
                
                return link;

               }
           },
       ],
   });
    
}


    

    
    </script>
@stop
