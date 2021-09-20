@extends('adminlte::page')

@section('title', 'Menus')

@section('content_header')
<meta http-equiv="x-ua-compatible" content="ie=edge">
<meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- <h1>Menu List</h1> --}}
@stop

@section('content')
<style>
    label.error {
        color:darkred;
        font-size: 12px;
    }
</style>
    <div class="tab-content p-1">
        <div class="tab-pane active dx-viewport" id="users">
            <div class="demo-container">
              <div class="top-info">
                <div class="table-heading-custom"><h4 class="right"><i class="fas fa-bars"></i> Menu List </h4></div>
                <button id="add_menu" class='custom-theme-btn'><i class='fa fa-plus'></i> Menu</button>
              </div>
                
                <div id="menu-list-div" style="height:600px"></div>
            </div>
        </div>
    </div>
    <!-- Edit Modal -->
    <div class="modal fade" id="edit-menu-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Edit Menu</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <form method="post" id="menu-update-frm">
                    <div class="row">
                        <div class="col-lg-4 form-group">
                            <input type="hidden" id="emenu_id" name="emenu_id">
                            <label for="menu_name">Menu Name <span class='man'>*</span></label>
                            <input type="text" name="menu_name" id="emenu_name" placeholder="Enter Menu name" class="form-control">
                        </div>
                        <div class="col-lg-4 form-group">
                            <label for="menu_name">Menu Slug <span class='man'>*</span></label>
                            <input type="text" name="menu_slug" id="emenu_slug" placeholder="Enter Menu slug" class="form-control">
                        </div>
                        <div class="col-lg-4 form-group">
                            <label for="menu_name">Menu Icon Code <span class='man'>*</span></label>
                            <input type="text" name="menu_icon" id="emenu_icon" placeholder="Enter Menu icon" class="form-control">
                        </div>
                        <div class="col-lg-4">
                            
                            <label for="menu_name">Parent </label>
                            <select name="parent_id1" id="emenu_parent_id" class="form-control select2bs4" data-placeholder="Select Parent Menu">
                                <option value=""></option>
                                @foreach($menus as $menu)
                                    <option value="{{ $menu->id }}"> {{ $menu->menu_name }}</option>

                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label for="status">Status</label>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" name="estatus" class="custom-control-input" id="estatus" value="1">
                                <label id="status_text" class="custom-control-label" for="estatus">Active</label>
                            </div>
                        </div>
                        <div class="col-lg-8 pt-2 form-group">
                            <span></span>
                            <input type="submit" name="update-menu-btn" id="update-menu-btn" value="Update" class="btn btn-primary">
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

    <!-- Add Modal -->
    <div class="modal fade" id="add-menu-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Add Menu</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <form method="post" id="menu-frm">
                    <div class="row">
                        <div class="col-lg-4 form-group">
                            <label for="menu_name">Menu Name <span class='man'>*</span></label>
                            <input type="text" name="menu_name" id="menu_name" placeholder="Enter Menu name" class="form-control">
                        </div>
                        <div class="col-lg-4 form-group">
                            <label for="menu_name">Menu Slug <span class='man'>*</span></label>
                            <input type="text" name="menu_slug" id="menu_slug" placeholder="Enter Menu slug" class="form-control">
                        </div>
                        <div class="col-lg-4 form-group">
                            <label for="menu_name">Menu Icon Code <span class='man'>*</span></label>
                            <input type="text" name="menu_icon" id="menu_icon" placeholder="Enter Menu icon" class="form-control">
                        </div>
                        <div class="col-lg-4">
                            <label for="menu_name">Parent </label>
                            <select name="parent_id" class="form-control select2bs4" data-placeholder="Select Parent Menu">
                                <option value=""></option>
                                @foreach($menus as $menu)
                                    <option value="{{ $menu->id }}"> {{ $menu->menu_name }}</option>

                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label for="status">Status</label>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" name="status" class="custom-control-input" id="status" value="1" checked>
                                <label id="add_status_text" class="custom-control-label" for="status"><span class='badge badge-success'>Active</span></label>
                              </div>
                        </div>
                        <div class="col-lg-8 pt-2 form-group">
                            
                            <input type="submit" name="add-menu-btn" id="add-menu-btn" class="btn btn-primary">
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

@section('js')

    <script>
$("#estatus").on('click', () => {

    var status = $("#estatus").prop('checked');
    if(status == true) {
        $("#status_text").html(`<span class='badge badge-success'>Active</span>`);
    } else {
        $("#status_text").html(`<span class='badge badge-danger'>In Active</span>`);
    }
    console.log(status)
})

$("#status").on('click', () => {

var status = $("#status").prop('checked');
if(status == true) {
    $("#add_status_text").html(`<span class='badge badge-success'>Active</span>`);
} else {
    $("#add_status_text").html(`<span class='badge badge-danger'>In Active</span>`);
}
console.log(status)
})

$(document).on('click','#update-menu-btn', (e) => {

    //e.preventDefault();
    $("#menu-update-frm").validate({
        rules:{
            menu_name:{
                required:true
            },
            menu_slug:{
                required:true
               // pattern:
            },
            menu_icon:{
                required:true
            }
        },
        submitHandler:(r) => {
            console.log('next')
            var url = "{{  route('update.menu') }}"
            $.ajax({
                url:url,
                data:$("#menu-update-frm").serialize(),
                type:"POST",
                headers: {
                   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                beforeSend:(r) => {
                    $("#update-menu-btn").prop('disabled',true);
                },
                error:(r) => {
                    $("#update-menu-btn").prop('disabled',false);
                    toastr.error('Something went wrong');
                },
                success:(r) => {
                    $("#update-menu-btn").prop('disabled',false);
                    toastr.success('Menu Updated successfully');
                    $("#edit-menu-modal").modal('hide');
                    fetch_data();
                }

            })
        }
    });
})
$(document).on('click','#add-menu-btn', ()=> {
    $("#menu-frm").validate({
        rules:{
            menu_name:{
                required:true
            },
            menu_icon:{
                required:true
            }
        },
        submitHandler:(r) => {
            var url = "{{  route('add.menu') }}"
            $.ajax({
                url:url,
                data:$("#menu-frm").serialize(),
                type:"POST",
                headers: {
                   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                beforeSend:(r) => {
                    $("#add-menu-btn").prop('disabled',true);
                },
                error:(r) => {
                    $("#add-menu-btn").prop('disabled',false);
                    toastr.error('Something went wrong');
                },
                success:(r) => {
                    $("#add-menu-btn").prop('disabled',false);
                    toastr.success('Menu Added successfully');
                    $("#menu-frm").modal('hide');
                    fetch_data();
                }

            })
        }
    });
})
$(document).on('click','#add_menu', ()=> {
   // console.log('inn')
    $("#add-menu-modal").modal('show');
});
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
           var url = "{{ route('get.menu.list') }}"
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
   $("#menu-list-div").dxDataGrid({
       dataSource: jsonData,
       KeyExpr: "id",
       showBorders: true,
       showRowLines: true,
       rowAlternationEnabled: true,
       allowColumnResizing: true,
       columnHidingEnabled:true,
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
           enabled: false,
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
       sorting: {
            mode: "none"
        },
        rowDragging: {
            allowReordering: true,
                dropFeedbackMode: "push",
                onReorder: function(e) {
                    var visibleRows = e.component.getVisibleRows(),
                        newOrderIndex = visibleRows[e.toIndex].data.id,
                        shiftedItemOrder = visibleRows[e.toIndex].data.menu_order;
                        d = $.Deferred();
                        var draggedItemOrder = e.itemData.menu_order;
                        var draggedItem = e.itemData.id;
                        var shiftedItem = newOrderIndex;
                        var url = "{{  route('menu.reorder') }}"
                        $.ajax({
                            url:url,
                            data:{draggedItem:draggedItem,shiftedItem:shiftedItem,draggedItemOrder:draggedItemOrder,shiftedItemOrder:shiftedItemOrder},
                            type:"POST",
                            headers:{
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            },
                            beforeSend:(r) => {

                            },
                            error:(r)=> {
                                console.log(r.responseText)
                            },
                            success:(r) => {
                                if(r==1) {
                                    toastr.success('Menu Items order updated successfully');
                                    fetch_data();
                                } else {
                                    console.log(r)
                                }
                            }
                        })
                    // tasksStore.update(e.itemData.ID, { OrderIndex: newOrderIndex }).then(function() {
                    //     e.component.refresh().then(d.resolve, d.reject);
                    // }, d.reject);

                   // e.promise = d.promise();
                }
        },
       wordWrapEnabled: true,
       columns: [{
               dataField: "menu_order",
               caption: "Menu Order",
               width:95,
               visibe: true,
           },
           {
               dataField: "menu_name",
               caption: "Menu Name",
           },
           {
               dataField: "menu_slug",
               caption: "Menu Slug",
               visible:false,
           },
           {
                dataField:"icon",
                caption: "Menu Icon",
                cellTemplate: (container, options) => {
                    var fa = options.data.icon;
                    var link = $(`<span class='${fa}'></span>`);

                    return link;
                }
           },
           {
                dataField:"status",
                caption: "Display Status",
                cellTemplate: (container, options) => {
                    var fa = options.data.status;
                    var link = ``;
                    if(fa == 1) {
                       
                        link = $(`<span class='badge badge-success'>Active</span>`)
                    } else {
                       
                        link = $(`<span class='badge badge-danger'>In Active</span>`)
                    }
                    return link;
                }
           },
           {
               dataField: "Action",
               caption: "Action",
               width:100,
               cellTemplate: function (container, options) {
                   var json_string = JSON.stringify(options.data);
                   var data = options.data;
                   console.log(json_string)
                //var href = `<a data-type="edit" data-json=${options.data} data-id=${options.data.gift_id} data-url=${ route('mason.gift.update', options.data.gift_id) } class="edit-icon action_icon" href="javascript:void(0)" title="edit"><i class="fas fa-edit edit_icon"></i></a>`;
                var link = $(`<a href="javascript:void(0)" title="edit">`).html("<i class='fa fa-edit'></i> Edit")
                    .attr("href", "javascript:void(0)")

                link.on("click", function () {
                    $("#edit-menu-modal").modal('show');
                    $("#emenu_name").val(data.menu_name);
                    $("#emenu_slug").val(data.menu_slug);
                    $("#emenu_icon").val(data.icon);
                    $("#emenu_id").val(data.id);
                    $("#emenu_parent_id").val(data.parent_id).trigger('change');

                    if(data.status == 1) {
                        $("#estatus").prop('checked', true);
                        $("#status_text").html(`<span class='badge badge-success'>Active</span>`)
                    } else {
                        $("#estatus").prop('checked', false);
                        $("#status_text").html(`<span class='badge badge-danger'>In Active</span>`)
                    }
                })
                
                return link;

               }
           },
       ],
   });
    
}

    </script>
@stop
