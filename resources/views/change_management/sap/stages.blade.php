@extends('adminlte::page')

@section('title', 'Module Approval Stages')

@section('content_header')
    <!-- <h1>employees List</h1> -->
@stop

@section('content')
<style>
 #steps {
    width: 100% !important;
    margin: 0 auto;
    margin-left:35px;
}

.step {
  width: 40px;
  height: 40px;
  background-color: white;
  display: inline-block;
  border: 2px solid;
  border-color: transparent;
  border-radius: 50%;
  color: #cdd0da;
  font-weight: 600;
  text-align: center;
  line-height: 35px;
  transition: 0.28s all;
}

.step:first-child {
    line-height: 38px !important;
}

.step:nth-child(n+2) {
  margin: 0 0 0 100px;
  transform: translate(0, -4px);
}

.step:nth-child(n+2):before {
    width: 75px;
    height: 2px;
    display: block;
    background-color: white;
    transform: translate(-89px, 21px);
    content: "";
}

.step:after {
  width: 150px;
  display: block;
  transform: translate(-55px, 3px);
  color: #818698;
  content: attr(data-desc);
  font-weight: 400;
  font-size: 13px;
}

.step:first-child:after {
  transform: translate(-55px, -1px);
}

.step:hover {
    background-color: #254e50;
    color:#fff !important;
    cursor: pointer;
}

.step.active {
  border-color: #254e50;
  color: #588db6;
}

.step.active:before {
  background: linear-gradient(to right, #58bb58 0%, #73b5e8 100%);
}

.step.active:after {
    color: #357477;
    font-weight: 600;
}

.step.done {
  background-color: #58bb58;
  border-color: #58bb58;
  color: white;
}

.step.done:before {
  background-color: #58bb58;
}
</style>
<!-- Settings Modal -->
<div class="modal fade" id="settings-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Module Settings</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
        <div class="modal-body">
            <div class="module_stages_block"></div>
            
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
        </div>
    </div>
    </div>
</div>

<div class="tab-content p-1">
    <div class="tab-pane active dx-viewport" id="module-approval-stages">
        <div class="demo-container">
            <div class="top-info">
                    <div class="table-heading-custom"><h4 class="right"><i class="fas fa-tasks"></i> Module List & Approval Stages </h4></div>
              </div>
            <div id="module-list-div" style="height:auto"></div>
        </div>
    </div>
</div>

@stop

@section('css')
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
  
@stop

@section('js')

<script>
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
           var url = "{{ route('module.approval.stages') }}"
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
   $("#module-list-div").dxDataGrid({
       dataSource: jsonData,
       KeyExpr: "id",
       showBorders: true,
       showRowLines: true,
       columnAutoWidth:true,
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
                dataField:"approval_stages",
                caption:"Approval Stages",
                cellTemplate: (container, options) => {
                    console.log(options.data.module_approval_stages)
                    var approval_stages = options.data.module_approval_stages;
                    var html = `<div id="steps">`;
                    if(approval_stages !== null) {
                        
                        $.each(approval_stages, (i) => {
                            html += `
                            <div class="step active" data-desc="${approval_stages[i].approval_matrix.approval_type}">${i+1}</div>`;
                        });
                        html += `</div>`;
                    } else {
                        approval_stages = 'Not Assigned';
                        html = `<span class='badge badge-danger'>${approval_stages}</span>`;
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
                   var module_stages = options.data.module_approval_stages;
                  
                   var html = `<form id='matrix_frm' method='post'>
                   <input type='hidden' name='module_id' value='${permission_id}'>
                   <div class='row'>
                    <div class='col-lg-12'>
                        <h5 class='shadow p-2 rounded mb-2'>${permission_name} module stages</h5>
                    </div>`;
                 
                    var approval_matrix = {!! $approval_matrix !!};
                    var checked = '';
                        //console.log(approval_matrix);
                    $.each(approval_matrix, (i) => {
                        checked = '';
                       if(module_stages !== null) {
                            $.each(module_stages, (j) => {
                                if(approval_matrix[i].id == module_stages[j].approval_matrix_id) {
                                    checked = 'checked';
                                }
                            });
                       }
                            

                            html += `<div class="col-lg-4">
                                        <label for="status">${approval_matrix[i].approval_type}</label>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" name="stages[]" class="custom-control-input" id="estatus_${i}" onclick='changeStatusLabel(${i})' value="${approval_matrix[i].id}" ${checked}>
                                            <label id="status_text_${i}" class="custom-control-label" for="estatus_${i}"><span class="badge ${ (checked === 'checked') ? 'badge-success' : 'badge-danger' }">${ (checked === 'checked') ? 'Active' : 'InActive' }</span></label>
                                        </div>
                                    </div>`;
                    });

                   html += "<div class='col-lg-12 pt-2'><button type='submit' class='btn btn-primary' id='am_btn' style='padding:5px !important;'>Save Changes</button></div></div></form>";
               
                var link = $(`<a href="javascript:void(0)" class='setings-btn btn-primary' style='font-size:11px' id='link_${permission_id}' title="edit">`).html("<i class='fas fa-user-cog'></i> Settings")
                    .attr("href", "javascript:void(0)")

                link.on("click", function () {



                    $("#settings-modal").modal('show');

                    $(".module_stages_block").html(html);
                   
                })
                
                return link;

               }
           },
       ],
   });
    
}
function changeStatusLabel(position) {
    console.log(position)
    var status = $("#estatus_"+position+':checked').val();
    console.log(status)
    if(!status) {
        $("#status_text_"+position).html(`<span class='badge badge-danger'>InActive</span>`);
        console.log('inactive')
    } else {
        $("#status_text_"+position).html(`<span class='badge badge-success'>Active</span>`);
        console.log('active')
    }
}

/** Approval Stage Submit */

$(document).on('click', '#am_btn', (e) => {

    e.preventDefault();
    
    $.ajax({
        url:"{{ route('change.module.approval.stage') }}",
        data:$("#matrix_frm").serialize(),
        type:"POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
        error:(r) => {
            console.log(r)
        },
        success:(r) => {
            console.log(r);
            if(r.status == 200 ) {
                fetch_data();
                $("#settings-modal").modal('hide');
                toastr.success('Module Approval Stages updated successfully');
            } else {
               
                toastr.error('Something went wrong');
                console.log(r)
            }
        }
    })
});
</script>
@stop
