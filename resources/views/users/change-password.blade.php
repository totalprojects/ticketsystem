@extends('adminlte::page')

@section('title', 'Change Password')

@section('content_header')
    <h1>Settings</h1>
@stop

@section('content')
    
    <div class="tab-content p-1">
        <div class="font-weight-bold m-2 font-italic text-primary"><h4 class="right">Change Password </h4></div>
            <form method="post" id="change-password-frm">
                <div class="row wrapper">
                    <div class="col-lg-4">
                        <label for="">Current Password</label>
                        <input type="password" id="current_password" name="current_password" class="form-control">
                    </div>
                    <div class="col-lg-4">
                        <label for="">New Password</label>
                        <input type="password" id="new_password" name="new_password" class="form-control">
                    </div>
                    <div class="col-lg-4">
                        <label for="">Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control">
                    </div>
                    <div class="col-lg-12 mt-2">
                        <button id="change-password-btn" class="btn btn-primary">Change</button>
                    </div>
                </div>
            </form>    
        </div>
    </div>
  

@stop

@section('css')
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
  
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>

    <script> 
    
   // console.log('Hi!');
    
        fetch_users();

    function fetch_users() {
        var url = `{{ route('users.list',1) }} `;
    $(`#user-list-div`).dxDataGrid({
        dataSource: DevExpress.data.AspNet.createStore({
           // key: 'engineer_id',
            loadUrl: url,
        }),
        showBorders: true,
        showRowLines:true,
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
                caption: 'User Id',
                width:80
            },
            {
                dataField: "name",
                caption: 'Name',
               
            },
            {
                dataField:"roles",
                caption:"Role"
            },
            {
                dataField:"permissions",
                caption:"Permissions",
                cellTemplate: function (container, options) {
                    var data = options.data;
                    var permissions = [];
                    var permissions = JSON.parse(data.his_permissions);
                    var html = '';
                    if(permissions.length>0) {
                       $.each(permissions, (i) => {
                            html += `<span class='badge badge-primary'>${permissions[i].name}</span>&nbsp;`;
                       })
                    }

                    container.append(html)


                }
            },
            {
                dataField:"his_menu",
                caption:"Menus",
                cellTemplate: function (container, options) {
                    var data = options.data;
                    var menus = [];
                    var menus = JSON.parse(data.his_menus);
                    var html = '';
                    if(menus.length>0) {
                       $.each(menus, (i) => {
                            html += `<span class='badge badge-primary'>${menus[i].menu.menu_name}</span>&nbsp;`;
                       })
                    }

                    container.append(html)


                }
            },
            {
                dataField: "email",
                caption: 'Email',
               
            },
            {
                dataField: "created",
                caption: 'Created At',
               
            },
           
            {
                dataField: "Action",
                caption: 'Action',
                width: 75,
                visible: true,
                alignment: 'left',
                cssClass: '__Action',
                cellTemplate: function (container, options) {
                    var data = options.data;
                    
                    var link = $(`<a class="" href="javascript:void(0)" title="edit">`).html("<i class='fas fa-cog'></i> Action")
                    .attr("href", "javascript:void(0)")

                    link.on("click", function () {
                        $("#settings-modal").modal('show');
                        console.log(data)
                        var permissions = data.all_permissions;
                        var his_permissions = JSON.parse(data.his_permissions);
                        var all_menus = data.all_menus;
                        var his_menus = JSON.parse(data.his_menus);
                        var his_roles = JSON.parse(data.his_roles);
                        var roles = data.all_roles;
                        // console.log(his_permissions)
                        var html_form = `<p><strong>User Roles</strong></p><form method='post' id='user-role-frm' method='post'>
                        @csrf
                        `;
                        var is_checked;
                        var flag = false;
                        $.each(roles, (i) => {
                            is_checked = ''
                            is_checked = ''
                           flag =  his_roles.findIndex(function (his_roles) {
                                return his_roles.id === roles[i].id;
                            });
                            if(flag!= -1) {
                                is_checked = 'checked'
                            }
                            flag = false
                            html_form += `<input type='radio' name='roles' ${is_checked} value='${roles[i].id}'> ${roles[i].name} &nbsp;`;
                        })
                        //console.log(html_form);
                        html_form += `<hr><input type='hidden' id='user_id_r' name='user_id_p' value="${data.id}"><button type='submit' id='role-btn' class='btn btn-primary'>Update</button></form><br>`;
                        $("#roles-block").html(html_form);

                        var html_form = `<p><strong>User Permissions</strong></p><form method='post' id='user-permission-frm' method='post'>
                        @csrf
                        `;
                        var is_checked;
                        var flag = false;
                        $.each(permissions, (i) => {
                            is_checked = ''
                            is_checked = ''
                           flag =  his_permissions.findIndex(function (his_permissions) {
                                return his_permissions.id === permissions[i].id;
                            });
                            if(flag!= -1) {
                                is_checked = 'checked'
                            }
                            flag = false
                            html_form += `<input type='checkbox' name='permissions' ${is_checked} value='${permissions[i].id}'> ${permissions[i].name} &nbsp;`;
                        })
                        //console.log(html_form);
                        html_form += `<hr><input type='hidden' id='user_id_p' name='user_id_p' value="${data.id}"><button type='submit' id='permission-btn' class='btn btn-primary'>Update</button></form><br>`;
                        $("#permissions-block").html(html_form);

                        html_form = `<p><strong>User Menus</strong></p><form id='user-menu-mapping-frm' method='post'>
                        @csrf
                        `;
                        
                        $.each(all_menus, (i) => {
                           is_checked = ''
                           flag =  his_menus.findIndex(function (his_menus) {
                                return his_menus.menu_id === all_menus[i].id;
                            });
                            if(flag!= -1) {
                                is_checked = 'checked'
                            }
                            flag = false

                            html_form += `<input type='checkbox' name='menus' ${is_checked} value='${all_menus[i].id}'> ${all_menus[i].menu_name} &nbsp;`;
                        })
                        //console.log(html_form);
                        html_form += `<hr><input type='hidden' id='user_id_m' name='user_id_m' value="${data.id}"> <button type='submit' id='menu-btn' class='btn btn-primary'>Update</button></form><hr>`;
                        $("#menus-block").html(html_form);
                    })
                    //container.append(html)
                    return link;

                }
            },


        ]
    })
    }
    
    /** Permissions update */
    $(document).on('click','#permission-btn', (e)=> {
        e.preventDefault();
        var permissions = []
        var ds = $("input[name='permissions']:checked").each(function(i){
            permissions[i] = this.value;
        });
        var user_id = $("#user_id_p").val();

        $.ajax({
            url: "{{  route('update.user.permissions') }}",
            headers:{
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            type:"POST",
            data:{permissions:permissions,user_id:user_id},
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
                fetch_users();
                console.log(r)

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
                fetch_users();
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
                fetch_users();
                console.log(r)

            }
        })

        
    })
   
    
    
    
    
     </script>
@stop
