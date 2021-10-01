@extends('adminlte::page')

@section('title', 'Assets')

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
            <div class="table-heading-custom"><h4 class="right"><i class="fas fa-box"></i> Assets List </h4></div>
            <button id="add_btn" class='custom-theme-btn'><i class='fa fa-plus'></i> Asset</button>
          </div>
            
            <div id="assets-list-div" style="height:600px"></div>
        </div>
    </div>
</div>

    <!-- Add Modal -->
    <div class="modal fade" id="add-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add asset</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
        
                <form id="add-frm" method="post">
                    <div class="row">
                        <div class="col-lg-4 pt-2">
                          
                                 <label for="permission_name">
                                    Asset Type
                                </label> 
                              <select name="type" id="type" class="form-control select2bs4">
                                  @foreach($asset_types as $each)
                                        <option value="{{ $each->id }}">{{ $each->asset_type }}</option>
                                  @endforeach
                              </select>
                        </div>
                        <div class="col-lg-4 pt-2">
 
                             <label for="permission_name">
                                Asset Description
                            </label> 
                           <input type="text" name="description" id="description" class="form-control">
                        </div>
                        <div class="col-lg-4 pt-2">
                        
                                 <label for="permission_name">
                                    Asset Company
                                </label> 
                               <input type="text" name="company" id="company" class="form-control">
                        </div>
                        <div class="col-lg-4 pt-2">
                       
                                 <label for="permission_name">
                                    Asset Specification
                                </label> 
                               <input type="text" name="specs" id="specs" class="form-control">
                        </div>
                        <div class="col-lg-4 pt-2">
                          
                                 <label for="permission_name">
                                    Serial Number
                                </label> 
                               <input type="text" name="sl" id="sl" class="form-control">
                        </div>
                        <div class="col-lg-4 pt-2">
                          
                            <label for="permission_name">
                              Issue Date
                           </label> 
                          <input type="date" name="issue_date" id="issue_date" class="form-control">
                        </div>
                        <div class="col-lg-4 pt-2">
                          
                            <label for="permission_name">
                              Quantity
                           </label> 
                          <input type="number" name="qty" id="qty" class="form-control">
                        </div>   
                        <div class="col-lg-4 pt-2">
                          
                            <label for="permission_name">
                              Warrenty Period (In Years)
                           </label> 
                          <input type="number" name="warrenty" id="warrenty" class="form-control">
                        </div>                 
                        <div class="col-lg-4 pt-2 mt-4">
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
        
                <form id="update-frm" method="post">
                    <div class="row">
                        <div class="col-lg-4 pt-2">
                            <input type="hidden" name="eid" id="eid">
                                 <label for="permission_name">
                                    Asset Type
                                </label> 
                              <select name="etype" id="etype" class="form-control select2bs4">
                                  @foreach($asset_types as $each)
                                        <option value="{{ $each->id }}">{{ $each->asset_type }}</option>
                                  @endforeach
                              </select>
                        </div>
                        <div class="col-lg-4 pt-2">
 
                             <label for="permission_name">
                                Asset Description
                            </label> 
                           <input type="text" name="edescription" id="edescription" class="form-control">
                        </div>
                        <div class="col-lg-4 pt-2">
                        
                                 <label for="permission_name">
                                    Asset Company
                                </label> 
                               <input type="text" name="ecompany" id="ecompany" class="form-control">
                        </div>
                        <div class="col-lg-4 pt-2">
                       
                                 <label for="permission_name">
                                    Asset Specification
                                </label> 
                               <input type="text" name="especs" id="especs" class="form-control">
                        </div>
                        <div class="col-lg-4 pt-2">
                          
                                 <label for="permission_name">
                                    Serial Number
                                </label> 
                               <input type="text" name="esl" id="esl" class="form-control">
                        </div>
                        <div class="col-lg-4 pt-2">
                          
                            <label for="permission_name">
                              Issue Date
                           </label> 
                          <input type="date" name="eissue_date" id="eissue_date" class="form-control">
                        </div>
                        <div class="col-lg-4 pt-2">
                          
                            <label for="permission_name">
                              Quantity
                           </label> 
                          <input type="number" name="eqty" id="eqty" class="form-control">
                        </div>
                        <div class="col-lg-4 pt-2">
                          
                            <label for="permission_name">
                              Warrenty Period (In Years)
                           </label> 
                          <input type="number" name="ewarrenty" id="ewarrenty" class="form-control">
                        </div>
                        <div class="col-lg-4 pt-4 mt-3">
                            <button class='btn btn-primary' type="submit" id="update-btn" name='update-btn'>Update</button>
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
        easset_name:{
            required:true
        },
    },
    submitHandler:(r) => {
        //'next')
        var url = "{{  route('update.asset') }}"
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
        asset_name:{
            required:true
        },
    },
    submitHandler:(r) => {
        var url = "{{  route('add.asset') }}"
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
           var url = "{{ route('get.assets') }}"
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
   $("#assets-list-div").dxDataGrid({
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
               dataField: "serial_number",
               caption: "Serial No",
           },
           {
               dataField: "type.asset_type",
               caption: "Type",
           },
           {
               dataField: "description",
               caption: "Description",
           },
          
           {
               dataField: "warrenty_period",
               caption: "Warrenty Perid (In Years)",
           },
           {
                dataField:"specifications",
                caption:"Specifications"
           },
           {
                dataField:"issue_date",
                caption:"Issue Date"
           },
           {
                dataField:"quantity",
           },
           {
               dataField: "Action",
               caption: "Action",
               visible:true,
               width:100,
               cellTemplate: function (container, options) {

                   var asset_id = options.data.id;
                   var asset_name = options.data.description;
                   var company = options.data.company;
                   var type = options.data.type.id;
                   var specs = options.data.specifications;
                   var sl = options.data.serial_number;
                   var issue_date = options.data.issue_date;
                   var warrenty = options.data.warrenty_period;
                   var quantity = options.data.quantity;

                 
                   var markup = ``;
                   var actions = '';
                   var action_markup = ``;
                   var checked = '';
                 
                   var link = $(`<a href="javascript:void(0)" id='link_${asset_id}' title="edit">`).html("<i class='fa fa-edit'></i> Edit")
                        .attr("href", "javascript:void(0)")

                    link.on("click", function () {
                
                        $("#edit-modal").modal('show');
                        $("#edescription").val(asset_name);
                        $("#ecompany").val(company);
                        $("#especs").val(specs);
                        $("#esl").val(sl);
                        $("#ewarrenty").val(warrenty);
                        $("#etype").val(type).trigger('change');
                        $("#eqty").val(quantity);
                        $("#eissue_date").val(issue_date);
                        $("#eid").val(asset_id);
                                        
                    
                    })
                
                return link;

               }
           },
       ],
   });
    
}


    

    
    </script>
@stop
