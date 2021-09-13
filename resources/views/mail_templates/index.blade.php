@extends('adminlte::page')

@section('title', 'Mail Templates')

@section('content_header')
<meta http-equiv="x-ua-compatible" content="ie=edge">
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css">
<script src="{{ asset('assets/plugins/ckeditor5-build-classic/ckeditor.js') }}"></script>



@stop

@section('content')
<style>
    .ck-editor__editable {
    min-height: 250px;
    }
</style>
<div class="tab-content p-1">
    <div class="tab-pane active dx-viewport" id="users">
        <div class="demo-container">
          <div class="top-info">
            <div class="table-heading-custom"><h4 class="right"><i class="fas fa-building"></i> Mail Template </h4></div>
            <button id="add_btn" class='custom-theme-btn'><i class='fa fa-plus'></i> Mail Template</button>
          </div>
            
            <div id="mail-template-list-div" style="height:600px"></div>
        </div>
    </div>
</div>

    <!-- Add Modal -->
    <div class="modal fade" id="add-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Mail Template</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
        
                <form id="add-frm" method="post">
                    <div class="row">
                        <div class="col-lg-4 pt-2">
                            <select name="approver_id" id="approver_id" class="form-control select2bs4" data-placeholder="Select Approver">
                                <option></option>
                                @foreach($approvals as $approve)

                                    <option value="{{ $approve->id }}">{{ $approve->approval_type }}</option>

                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4 pt-2">
                            <select name="type_id" id="type_id" class="form-control select2bs4" data-placeholder="Select Type">
                                <option></option>
                                <option value="1">Request</option>
                                <option value="2">Approve</option>
                                <option value="3">Reject</option>
                            </select>
                        </div>  
                        <div class="col-lg-12 pt-2">
                            <div id="templateVariables"></div>
                            <textarea class="form-control" id="editor" ondrop="drop(event)" ondragover="allowDrop(event)"></textarea>
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
                            <select name="eapprover_id" id="eapprover_id" class="form-control select2bs4" data-placeholder="Select Approver">
                                <option></option>
                                @foreach($approvals as $approve)

                                    <option value="{{ $approve->id }}">{{ $approve->approval_type }}</option>

                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4 pt-2">
                            <select name="etype_id" id="etype_id" class="form-control select2bs4" data-placeholder="Select Type">
                                <option></option>
                                <option value="1">Request</option>
                                <option value="2">Approve</option>
                                <option value="3">Reject</option>
                            </select>
                        </div>  
                        <div class="col-lg-12 pt-2">
                            <div id="etemplateVariables"></div>
                            <textarea class="form-control" id="editor1" ondrop="drop(event)" ondragover="allowDrop(event)"></textarea>
                        </div>   
                        <div class="col-lg-4 pt-2">
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


$(document).on('change', '#type_id', (e) => {
    var type_id = $("#type_id").val();
    $.ajax({
        url:"{{ route('generateFields') }}",
        data:{type_id:type_id},
        type:"GET",
        beforeSend:(r) => {
               
            },
            error:(r) => {
                
                toastr.error('Something went wrong');
            },
            success:(r) => {
                console.log(r)
                var data = r.data;
                var html = `<label for="">SAP Template Variables [Drag/Drop]</label> <br>`;
                $.each(data, (i) => {
                    html += `<div class="badge badge-primary p-2 m-2" draggable="true" ondragstart="drag(event)">##${data[i]}##</div>`;
                })

                $("#templateVariables").html(html)
            }
    })
})



function allowDrop(ev) {
  ev.preventDefault();
}

function drag(ev) {
  ev.dataTransfer.setData("text", ev.target.innerHTML);
}

function drop(ev) {
  ev.preventDefault();
  var data = ev.dataTransfer.getData("text");
  ev.target.appendChild(document.getElementById(data));
}

var editorValue = '';
var editorValue1 = '';

ClassicEditor.create( document.querySelector( '#editor' )).then( editor => {
                                        console.log( editor );
                                        editorValue = editor
                                    
                                } )
                                .catch( error => {
                                        console.error( error );
                                } );


/** for update */
ClassicEditor.create( document.querySelector( '#editor1' )).then( editor => {
                                        console.log( editor );
                                        editorValue1 = editor
                                    
                                } )
                                .catch( error => {
                                        console.error( error );
                                } );
      

$(document).on('click','#add_btn', ()=> {

    $("#add-modal").modal('show');
});


$(document).on('click','#update-btn', ()=> {

$("#update-frm").validate({
    rules:{
        edesignation_name:{
            required:true
        },
    },
    submitHandler:(r) => {
        //'next')
        var url = "{{  route('update.mail.template') }}"
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
});


$(document).on('click','#add-btn', (e)=> {
    e.preventDefault();
    var templateValue = editorValue.getData();

    if(templateValue.length<1) {
        toastr.error('You must fill the template to continue');
        return false;
    }
    var type_id = $("#type_id").val();
    var approver_id = $("#approver_id").val();
            var url = "{{ route('create.mail.template') }}"
            $.ajax({
                url:url,
                data:{type_id, approver_id, templateValue},
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
        });



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
           var url = "{{ route('get.mail.templates') }}"
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
   $("#mail-template-list-div").dxDataGrid({
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
               dataField: "approval_matrix.approval_type",
               caption: "Approval Type",
           },
           {
                dataField:"html_template",
                caption:"Template",
                encodeHtml:false
           },
           {
               dataField: "Action",
               caption: "Action",
               allowFiltering: false,
               width:100,
               cellTemplate: function (container, options) {

                  
                   var type_id = options.data.type_id;
                   var eid = options.data.id;
                   var approver_id = options.data.approval_matrix_id;
                   var template_html = options.data.html_template
                   var markup = ``;
                   var actions = '';
                   var action_markup = ``;
                   var checked = '';
                 
                   var link = $(`<a href="javascript:void(0)" title="edit">`).html("<i class='fa fa-edit'></i> Edit")
                        .attr("href", "javascript:void(0)")

                    link.on("click", function () {
                
                        $("#edit-modal").modal('show');
                        $("#eid").val(eid);
                        $("#etype_id").val(type_id).trigger('change')
                        $("#eapprover_id").val(approver_id).trigger('change');
                        editorValue1.setData(template_html)         
                    
                    })
                
                return link;

               }
           },
       ],
   }).dxDataGrid("instance");
    
}


    

    
    </script>
@stop