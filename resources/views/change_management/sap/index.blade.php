@extends('adminlte::page')

@section('title', 'SAP Change Management')

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
            <div class="table-heading-custom"><h4 class="right"><i class="fas fa-building"></i> List </h4></div>
            <button id="add_btn" class='custom-theme-btn'><i class='fa fa-plus'></i> Add Request</button>
          </div>
            
            <!-- <div id="cm-list-div" style="height:600px"></div> -->
        </div>
    </div>
</div>

    <!-- Add Modal -->
    <div class="modal fade" id="add-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Company</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
        
                <form id="add-frm" method="post">
                    <div class="row">
                        <div class="col-lg-4 pt-2">
                            <input type="text" name="company_name" id="company_name" class="form-control" placeholder="Enter name">
                        </div>
                        <div class="col-lg-4 pt-2">
                          
                            <input type="number" name="company_code" id="company_code" class="form-control">
                        </div>                       
                        <div class="col-lg-4 pt-2">
                            <button class='btn btn-primary' type="submit" id="add-btn" name='add-btn'><i class='fas fa-plus'></i> Add</button>
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
        
      

$(document).on('click','#add_btn', ()=> {
   // //'inn')
    $("#add-modal").modal('show');
});


$(document).on('click','#update-btn', ()=> {

$("#update-frm").validate({
    rules:{
        ecompany_name:{
            required:true
        },
        ecompany_code:{
            required:true
        }
    },
    submitHandler:(r) => {
        //'next')
        var url = "{{  route('update.company') }}"
        $.ajax({
            url:url,
            data:$("#update-frm").serialize(),  
            type:"POST",
            headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            beforeSend:(r) => {
                $("#update-btn").prop('disabled',true);
            },
            error:(r) => {
                $("#update-btn").prop('disabled',false);
                toastr.error('Something went wrong');
            },
            success:(r) => {
                $("#update-btn").prop('disabled',false);
                toastr.success(r.message);
                $("#edit-modal").modal('hide');
                fetch_data();
            }

        })
    }
});
})
$(document).on('click','#add-btn', ()=> {
$("#add-frm").validate({
    rules:{
        Company_name:{
            required:true
        },
    },
    submitHandler:(r) => {
        var url = "{{  route('add.company') }}"
        $.ajax({
            url:url,
            data:$("#add-frm").serialize(),
            type:"POST",
            headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            beforeSend:(r) => {
                $("#add-btn").prop('disabled',true);
            },
            error:(r) => {
                //r.responseJSON)
                $("#add-btn").prop('disabled',false);
                toastr.error(r.responseJSON.message);
            },
            success:(r) => {
                $("#add-btn").prop('disabled',false);
                toastr.success(r.message);
                $("#add-modal").modal('hide');
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
               "sort",
               "filter",
           ].forEach(function (i) {
               if (i in loadOptions && isNotEmpty(loadOptions[i]))
                   args[i] = JSON.stringify(loadOptions[i]);
           })

           let take = loadOptions.take
           let skip = loadOptions.skip
           var dataSet = []
           var url = "{{ route('get.company') }}"
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
                   var tempData = [{
                        'req_id' : 'CM/SAP/05/21/1',
                        'tr_id' : 'A001',
                        'stages' : 'Current Stage: DEV'
                   }];
                   deferred.resolve(data, {
                       totalCount: 2,
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
   $("#cm-list-div").dxDataGrid({
       dataSource: jsonData,
       KeyExpr: "id",
       showBorders: true,
       filterRow: { 
         visible: true
       },
       showRowLines: true,
       rowAlternationEnabled: true,
       allowColumnResizing: true,
       sorting: false,
       loadPanel: {
        //indicatorSrc: `${ASSET_URL}/assets/images/loader4.gif`,
        text: "Loading...",
        showPane: true,
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
               dataField: "company_name",
               caption: "Company Name",
           },
           {
                dataField:"company_code",
           },
           {
               dataField: "Action",
               caption: "Action",
               allowFiltering: false,
               width:100,
               cellTemplate: function (container, options) {

                   var Company_id = options.data.id;
                   var Company_name = options.data.company_name;
                   var Company_code = options.data.company_code;
                 
                   var markup = ``;
                   var actions = '';
                   var action_markup = ``;
                   var checked = '';
                 
                   var link = $(`<a href="javascript:void(0)" id='link_${Company_id}' title="edit">`).html("<i class='fa fa-edit'></i> Edit")
                        .attr("href", "javascript:void(0)")

                    link.on("click", function () {
                
                        $("#edit-modal").modal('show');
                        $("#ecompany_name").val(Company_name);
                        $("#ecompany_id").val(Company_id);
                        $("#ecompany_code").val(Company_code);        
                    
                    })
                
                return link;

               }
           },
       ],
   }).dxDataGrid("instance");
    
}


    

    
    </script>
@stop
