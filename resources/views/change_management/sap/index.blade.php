@extends('adminlte::page')

@section('title', 'SAP Change Management')

@section('content_header')
<meta http-equiv="x-ua-compatible" content="ie=edge">
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css">
  
@stop

@section('content')
<style>
    /*-----------------MAIN SECTION---------------------*/

.kanban-board-wrap main .main__header {
  display: flex;
  flex-direction: row;
  align-items: center;
  justify-content: space-between;
  padding: 0px 25px;
}
.kanban-board-wrap main .main__header > div {
  color: grey;
}
.kanban-board-wrap main .main__header > div > * {
  margin-left: 20px;
  cursor: pointer;
}
.kanban-board-wrap main .main__header > div .fa-bell {
  position: relative;
}

.kanban-board-wrap main .main__kanban__info {
  background: #ccc;
  padding: 0px 25px;
  display: flex;
  flex-direction: row;
  align-items: center;
}
.kanban-board-wrap main .main__kanban__info > img {
  border-radius: 50%;
  margin-right: 5px;
}
.kanban-board-wrap main .main__kanban__info > button {
  color: #2e2e2e;
  border: 0px;
  outline: 0px;
  cursor: pointer;
  background: #f1f1f1;
  padding: 6px 15px;
  border-radius: 18px;
  border: 1px solid #f6f6f6;
  transition: all 0.3s ease-in-out;
}
.kanban-board-wrap main .main__kanban__info > button:first-of-type {
  margin-right: auto;
  margin-left: 10px;
}
.kanban-board-wrap main .main__kanban__info > button:hover {
  background: #fff;
  border-color: #2e2e2e;
}
.kanban-board-wrap main .main__kanban {
  padding: 0px 25px;
  height: 100%;
  display: flex;
  flex-direction: row;
  align-items: flex-start;
  justify-content: flex-start;
}
.kanban-board-wrap main .main__kanban::after {
  content: "";
  padding-right: 25px;
  height: 100%;
}
.kanban-board-wrap main .main__kanban .board {
    flex: 0 0 20%;
    margin-right: 0;
    height: 100%;
    padding: 10px;
    
}
.kanban-board-wrap main .main__kanban .board__conatiner {
  min-height: calc(100% - 120px);
  max-height: calc(100% - 120px);
  scrollbar-width: none;
  box-shadow: 0 0 7px #ccc;
  border-radius: 15px;
  
}
.kanban-board-wrap main .main__kanban .board__conatiner::-webkit-scrollbar {
  width: 0px;
  /* Remove scrollbar space */
  background: transparent;
  /* Optional: just make scrollbar invisible */
}
.kanban-board-wrap main .main__kanban .board__header {
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
  margin-bottom: 30px;
  padding: 0px 15px;
  height: 50px;
  display: flex;
  flex-direction: row;
  align-items: center;
  background: #255e61;
}
.kanban-board-wrap main .main__kanban .board__header > span {
  margin-right: auto;
  margin-left: 10px;
  color: #ffffff;
  font-weight: 600;
}
.kanban-board-wrap main .main__kanban .board__header > i {
  font-size: 13px;
  color: #e4ff55;
}
.kanban-board-wrap main .main__kanban .board__header .fa-ellipsis-h {
  cursor: pointer;
}
.kanban-board-wrap main .main__kanban .board__boxes {
  -webkit-user-select: none;
     -moz-user-select: none;
      -ms-user-select: none;
          user-select: none;
  margin-bottom: 15px;
  cursor: move;
  padding: 20px;
}
.kanban-board-wrap main .main__kanban .board__boxes > .image-wrapper {
  margin: -20px -20px 10px -20px;
}
.kanban-board-wrap main .main__kanban .board__boxes p {
  font-size: 16px;
  color: #2e2e2e;
  margin-bottom: 10px;
  line-height: 28px;
}
.kanban-board-wrap main .main__kanban .board__boxes__sections {
  list-style-type: none;
  display: flex;
  flex-direction: row;
  align-items: center;
  margin-bottom: 20px;
}
.kanban-board-wrap main .main__kanban .board__boxes__sections > * {
  height: 4px;
  width: 20px;
  border-radius: 10px;
  margin-right: 4px;
}
.kanban-board-wrap main .main__kanban .board__boxes__info {
  display: flex;
  cursor: pointer;

  flex-direction: row;
  align-items: center;
}
.kanban-board-wrap main .main__kanban .board__boxes__info i {
    border: 1px solid #ccc;
    padding: 5px;
    color: #e4ff55 !important;
    background: #255e61;
}
.kanban-board-wrap main .main__kanban .board__boxes__info i,
.kanban-board-wrap main .main__kanban .board__boxes__info span {
  color: #2e2e2e;
  
  font-size: 12px;
}
.kanban-board-wrap main .main__kanban .board__boxes__info span {
  margin-left: 10px;
  margin-right: auto;
  
}
.kanban-board-wrap main .main__kanban .board__boxes__info img {
  border-radius: 50%;
  margin-left: 5px;
}
.kanban-board-wrap main .main__kanban .board .add__card {
    background: #80c4ff;
    cursor: pointer;
    border: 0;
    outline: 0;
    color: #2e2e2e;
    margin-top: 10px;
    padding: 10px 15px 9px;
    border-radius: 6px;
    line-height: 1;
    margin-bottom: 15px;
}
.board__style {
    background: #fffcf3;
    width: 100%;
    overflow: hidden;
    border-radius: 15px;
    box-shadow: 0 0 7px #ccc;
}


.dragging {
  border: 2px solid #454545;
}
	</style>
</style>
<div class="tab-content p-1">
    <div class="tab-pane active dx-viewport" id="users">
        <div class="demo-container">
          <div class="top-info">
            <div class="table-heading-custom"><h4 class="right"><i class="fas fa-book"></i> Development Change Logs </h4></div>
            <button id="add_btn" class='custom-theme-btn'><i class='fa fa-plus'></i> Add Request</button>
          </div>

          <div class="kanban-board-wrap">

<!--  MAIN SECTION  -->
<main>
<div class="temp-heading ml-2 p-2">
        <p>Developer:  @if($moderators['isDeveloper']==true) <span class='badge badge-success'> Yes </span> @else <span class='badge badge-danger'> No </span>  @endif &nbsp; 
        Approver/Module Head:  @if($moderators['isModuleHead']==true) <span class='badge badge-success'> Yes </span> @else <span class='badge badge-danger'> No </span>  @endif &nbsp;
        BASIS:  @if($moderators['isBasis']==true) <span class='badge badge-success'> Yes </span> @else <span class='badge badge-danger'> No </span>  @endif </p> &nbsp;
</div>
  <!--   KANBAN BOARD STARTS   -->
  <div class="main__kanban">
      
    <!--     1st board   -->
    @foreach($stages as $each)
    <div class="board">
      <div class="board__header board__style">
        <i class="fas fa-dot-circle"></i>
        <span>{{ $each['name'] }}</span>
        <i class="fas fa-ellipsis-h"></i>
      </div>
      <div class="board__conatiner" style="min-height:400px; padding:5px;" ondrop="return false">
          @if(!empty($each['requests']))
                <input type="hidden" id="o__{{ $each['name'] }}" value="{{ $each['id'] }}">
                @foreach(json_decode($each['requests']) as $eachRequest)
                    <div class="board__boxes board__style" draggable="{{ ($each['isDraggable'] == true) ? 'true' : 'false' }}">
                        <input type="hidden" id="state__{{ $eachRequest->id }}" value="{{ ($each['isDropable'] == true) ? 'true' : 'false' }}">
                        <input type="hidden" id="req_id" value="{{ $eachRequest->id }}">
                        <input type="hidden" id="stage_id" value="{{ $each['id'] }}">
                        <p>Description: <strong>{{ $eachRequest->description }}</strong></p>
                        <p>Module: <strong>{{ $eachRequest->permission->name }}</strong></p>
                        <p>TCode: <strong>{{ $eachRequest->tcode->t_code }}</strong></p>
                        <div class="board__boxes__info">
                            <i class="fas fa-paperclip"></i>
                            <span>0</span>
                            &nbsp;
                            @php($logs = json_encode($eachRequest->logs))
                            <i class="fas fa-book" onClick="viewLogs({{ $logs }})" title="View Change Logs"></i>
                            &nbsp;
                            <i class="fas fa-plus" onClick="addTask({{$eachRequest->id}})" title="Add New Task"></i>
                            
                        </div>
                    </div>
                @endforeach
        @endif
      </div>
      <button class="add__card d-none"> <i class="fas fa-plus"></i> Add Card</button>
    </div>
    @endforeach
    
  </div>

</main>

</div>
        </div>
    </div>
</div>

    <!-- Add Modal -->
    <div class="modal fade" id="add-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Development Request</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                <form id="add-frm" method="post">
                    <div class="row">
                        <div class="col-lg-4 pt-2">
                            <select name="module_id" id="module_id" class="form-control select2bs4" data-placeholder='Select Module'>
                                <option value=""></option>   
                            @foreach($modules as $each)
                                    <option value="{{ $each['id'] }}">{{ $each['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4 pt-2">
                            <select name="tcode_id" id="tcode_id" class="form-control select2bs4" placeholder='Select TCode'>
                                <option value="">--SELECT MODULE FIRST--</option>
                            </select>
                        </div>
                        <div class="col-lg-4 pt-2">
                            <textarea name="description" id="description" class="form-control" placeholder="Description about the requirement"></textarea>
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

    <!-- View Logs -->
    <div class="modal fade" id="view-logs-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Task Logs</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                  <div id="logs"></div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
                </div>
            </div>
            </div>
    </div>

    <!-- Add Task -->
    <div class="modal fade" id="add-task-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Task</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                <form id="add-task-frm" method="post">
                    <div class="row">
                        <div class="col-lg-4 pt-2">
                          <input type="hidden" id="treq_id" name="treq_id">
                           <textarea name="task_description" class="form-control" placeholder="Add Task Description"></textarea>
                        </div>
                        <div class="col-lg-4 pt-2">
                            <input type="text" id="task_due_date" name="task_due_date" class="form-control" placeholder="Task Due Date">
                        </div>
                        <div class="col-lg-4 pt-2">
                        <select name='assigned_to' id='assgined' class='form-control select2bs4' data-placeholder='Assign To'>
                        </select>
                        </div>
                                      
                        <div class="col-lg-4 pt-2">
                            <button class='btn btn-primary' type="submit" id="add-task-btn" name='add-task-btn'><i class='fas fa-plus'></i> Add</button>
                        </div>

                        <div class="col-lg-12 pt-2" id="renderTasks">
                            
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
$("#task_due_date").datepicker({minDate:0, maxDate: '+1m', changeMonth:true, dateFormat: 'yy-mm-dd'});
$("#module_id").on('change', (e) => {
    var module_id = $("#module_id").val();
    var url = route('get.allowed.tcodes');
    $.ajax({
            url:url,
            data:{module_id},  
            type:"POST",
            headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            beforeSend:(r) => {
              
            },
            error:(r) => {
               console.log(r)
                toastr.error('Something went wrong');
            },
            success:(r) => {
                var html = ``;
                if(r.data !== undefined) {

                    $.each(r.data, (i) => {
                        html += `<option value='${r.data[i].id}'>${r.data[i].name}</option>`;
                    })
                    $("#tcode_id").html(html);
                } else {
                    toastr.error('No Tcode assigned for this module yet');
                }
                
               
            }

        })
    
})

$(document).on('click','#add_btn', ()=> {
   // //'inn')
    $("#add-modal").modal('show');
});


$(document).on('click','#add-btn', ()=> {

$("#add-frm").validate({
    rules:{
       
    },
    submitHandler:(r) => {
        //'next')
        var url = "{{  route('add.dev.sap.request') }}"
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
                $("#add-btn").prop('disabled',false);
                toastr.error('Something went wrong');
            },
            success:(r) => {
                $("#add-btn").prop('disabled',false);
                toastr.success(r.message);
                $("#add-modal").modal('hide');
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
                
                fetch_data();
            }

        })
    }
});
})

$(document).on('click','#add-task-btn', (e)=> {

$("#add-task-frm").validate({
    rules:{
       
    },
    submitHandler:(r) => {
        //'next')
        var url = "{{  route('add.dev.sap.request.task') }}"
        $.ajax({
            url:url,
            data:$("#add-task-frm").serialize(),  
            type:"POST",
            headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            beforeSend:(r) => {
                $("#add-task-btn").prop('disabled',true);
            },
            error:(r) => {
                $("#add-task-btn").prop('disabled',false);
                toastr.error('Something went wrong');
            },
            success:(r) => {
                $("#add-task-btn").prop('disabled',false);
                toastr.success(r.message);
                $("#add-task-modal").modal('hide');
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
                
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
    <script type="text/javascript">
	const draggableElements = document.querySelectorAll(".board__boxes");
    const droppableElements = document.querySelectorAll(".board__conatiner");

    draggableElements.forEach(draggable => {
        draggable.addEventListener("dragstart", (e) => {
            console.log(e)
            var dropable = e.target.childNodes[1].value;
            console.log('is dropable? '+ dropable)
            if(dropable==false) {
                draggable.classList.add("dragstart");
            } else {
                draggable.classList.add("dragging");
            }
            
        });

        draggable.addEventListener("dragend", (e) => {
            var to_stage = $(e.target).parent().find("input").val();
           
            if(confirm('Are you sure about the change?')) {
                var req_id = e.srcElement.childNodes[3].value;
                var stage_id = e.srcElement.childNodes[5].value;
                    console.log('req_id '+req_id+ ' from stage id '+stage_id+' to stage '+to_stage);
                var dragIt = $("state__"+req_id).val();
                changeStage(req_id, stage_id, to_stage);
                
            } else {
                window.location.reload();
            }
           
                
            
        });
    });

droppableElements.forEach(droppable => {
  console.log('dropping');
 
  droppable.addEventListener("dropend", (e) => {
    console.log('drop ended!!!')
    console.log(e) 
            
  });
  droppable.addEventListener("dragover", e => {
   // e.preventDefault();
    const nearestElement = getNearestElement(droppable, e.clientY);
    const draggable = document.querySelector(".dragging");
   

    if (nearestElement == null) {
        
      droppable.appendChild(draggable);
    } else {
      droppable.insertBefore(draggable, nearestElement);
    }
  });
});

function getNearestElement(container, y) {
  const draggableElements = [
  ...container.querySelectorAll(".board__boxes:not(.dragging)")];


  return draggableElements.reduce(
  (closest, draggable) => {
    const box = draggable.getBoundingClientRect();
    const offset = y - box.top - box.height / 2;
    if (offset < 0 && offset > closest.offset) {
      return { offset: offset, element: draggable };
    } else {
      return closest;
    }
  },
  { offset: Number.NEGATIVE_INFINITY }).
  element;
}

function changeStage(req_id, stage_id, to_stage) {

    var url = "{{ route('dev.stage.change') }}";
    $.ajax({
               url: url,
               type: 'POST',
               headers: {
                   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
               },
               dataType: "json",
               data: {req_id, stage_id, to_stage},
               complete: function (result) {

                if(result.responseJSON) {
                    var status = result.responseJSON.status;
                    if(status == 200) {

                        toastr.success('Stage of the REQ ID '+req_id+ ' shifted to stage: '+to_stage+' successfully! Please wait...');

                    } else if(status == 400) {
                        toastr.error('Error in dropping: Reason: Unauthorized Stage');
                        setTimeout(()=> {
                            window.location.reload(); 
                        },2000);
                    } else if(status == 401) {
                        toastr.error('Operation not permitted!');
                        setTimeout(()=> {
                            window.location.reload(); 
                        },2000);
                    } else {
                        toastr.error('Error 404, try again');
                        setTimeout(()=> {
                            window.location.reload(); 
                        },2000);
                    }
                } 
                  
                   
               },
               error: function (e) {
                   toastr.error(e.getMessage);
               },
               //timeout: 2000000
    });
}


function viewLogs(logs) {

  var html = ``;

  $.each(logs, (i) => {
    html += `<span><strong>${logs[i].creator.first_name}</strong> moved this task from <strong>${logs[i].from_stage.name}</strong> to <strong>${logs[i].to_stage.name}</strong> on <strong>${logs[i].created_at}</strong></span> <hr>`
  });

  $("#view-logs-modal").modal('show');
  $("#logs").html(html)

}

function addTask(req_id) {


  var url = "{{ route('get.module.resp.employees') }}";
    $.ajax({
               url: url,
               type: 'GET',
               headers: {
                   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
               },
               dataType: "json",
               data: {req_id},
               complete: function (result) {

                if(result.responseJSON) {
                    var data = result.responseJSON.data;
                    var tasks = result.responseJSON.existingTasks;
                   
                    var html_table = `<table class='table table-bordered mt-2'><thead><th>Sl No</th>
                    <th>Assigned To</th>
                    <th>Description</th>
                    <th>Due Date</th>
                    <th>Current Status</th>
                    <th>Created On</th>
                    </thead>`;
                    
                    if(tasks.length>0) {
                      html_table += `<tbody>`;
                      $.each(tasks, (i) => {
                          html_table += `<tr>
                            <td>${i+1}</td>
                            <td>${tasks[i].assigned.first_name+ ' ' +tasks[i].assigned.last_name}</td>
                            <td>${tasks[i].description}</td>
                            <td>${tasks[i].due_date}</td>
                            <td>${(tasks[i].status == 1) ? '<span class="badge badge-success">Completed</span>' : '<span class="badge badge-warning">Pending</span>'}</td>
                            <td>${tasks[i].created_at}</td>
                          </tr>`
                      })

                      html_table += `</tbody></table>`;
                    } else {
                      html_table = ``;
                    }
                    
                    console.log(html_table)
                    var html = ` <option value=''></option>`;
                      $.each(data, (i) => {
                          html += `<option value='${data[i].id}'>${data[i].name}</option>`;
                      })
                    html += `</select>`;
                    $("#assgined").html(html)
                    $("#treq_id").val(req_id);
                    $("#renderTasks").html(html_table);
                    $("#add-task-modal").modal('show');
                      
                } else {
                  toasr.error('Something went wrong');
                }
                  
                   
               },
               error: function (e) {
                   toastr.error(e.getMessage);
               },
               //timeout: 2000000
    });
}
</script>
@stop
