@extends('adminlte::page') @section('title', 'Users Permission')
@section('content_header')
<!-- <h1>Users List</h1> -->
@stop @section('content')
<style>
  .menu_bar {
    list-style-type: none !important;
    display: inline-block !important;
  }
  /* ul, li {
            list-style:none;
        }
        ul li {
            list-style:none;
        } */

  .checkboxes-wrapper label {
    position: relative;
    bottom: 4px;
  }
  .card-header {
    background-color: #255e61;
    color: #fff;
    box-shadow: 0 0 5px rgb(0 0 0 / 33%);
  }

  #roles-block,
  #permissions-block,
  #menus-block {
    border: 1px solid #5b8284;
    padding: 5px;
    margin-bottom: 5px;
    box-shadow: 0 0 5px rgb(0 0 0 / 33%);
  }
  #roles-block h5 {
    background-color: #255e61;
    padding: 5px !important;
    box-shadow: 0 0 5px rgb(0 0 0 / 33%);
    color: #fff;
  }
  #permissions-block h5 {
    background-color: #255e61;
    padding: 5px !important;
    box-shadow: 0 0 5px rgb(0 0 0 / 33%);
    color: #fff;
  }
  #menus-block h5 {
    padding: 5px !important;
    background-color: #255e61;
    box-shadow: 0 0 5px rgb(0 0 0 / 33%);
    color: #fff;
  }
  /* .wrapper p{
            margin:0;
            padding:0;
        } */

/* .accordion-toggle:after {
  font-family: 'FontAwesome';
  content: "\f078";    
  float: right;
} */

/* .accordion {
    background-color: antiquewhite !important;
    padding:5px;
    box-shadow: 0 0 2px 3px rgba(0,0,0,0.19);
}
.accordion-heading {
    background-color: cadetblue !important;
    padding: 5px;
}
.accordion-heading a {
    font-weight: 500 !important;
    color:cornsilk !important;
}
.accordion-body {
    background-color: cornsilk !important;
    padding: 5px;
}
.accordion-opened .accordion-toggle:after {    
    content: "\f054";    
} */
    .acc-container {
        width: 100%;
        max-width: 1000px;
        margin: 30px auto;
        background: #ffffff;
        border-radius: 1rem;
    }
    .accordion-heading {
    background-color: #fff;
    color: #000000;
    cursor: pointer;
    padding: 10px 15px 10px 52px;
    text-align: left;
    outline: none;
    font-size: 14px;
    transition: all 0.4s ease-out;
    box-shadow: 0px 0px 34px -8px rgb(0 0 0 / 75%);
    display: block;
}
    .heading-sec-wrap > input {
    position: absolute;
    top: 10px;
    left: 10px;
}
.heading-sec-wrap {
    position: relative;
}
    .accordion-body:first-child .accordion-heading {
      border-radius: 1rem 1rem 0 0;
    }
    .accordion-body:last-child .accordion-heading {
      border-radius: 0 0 1rem 1rem;
    }
    .accordion-heading.active,
    .accordion-heading:hover {
      background-color: #255e61;
      color: #fff;
    }
    .accordion-heading.has-child:after {
      content: "\002B";
      color: #6e6e6e;
      font-weight: bold;
      float: right;
      margin-left: 0.5rem;
      font-size: 18px;
    }
    .accordion-heading.has-child.active:after {
      content: "\2212";
      color: #fff;
    }
    .accordion-content {
      padding: 0 1rem;
      background-color: #fff;
      color: #6c6c6c;
      max-height: 0;
      overflow: hidden;
      transition: max-height 0.2s ease-out;
    }
    .accordion-content ul {
      list-style-type: none;
      margin: 1rem 0;
      padding: 0.2rem;
    }
    .accordion-content ul li input {
    margin-right: 10px;
}

.accordion-content ul li {
    padding: 0.2rem 0;
    display: flex;
    font-size: 14px;
}
    span.accordion-heading.has-child:hover:after {
      color: #fff;
    }
</style>
<div class="tab-content p-1">
  <div class="tab-pane active dx-viewport" id="users">
    <div class="demo-container">
      <div class="top-info">
        <div class="table-heading-custom">
          <h4 class="right"><i class="fas fa-key"></i> User Permissions</h4>
        </div>
      </div>
      <div id="user-list-div" style="height: 600px"></div>
    </div>
  </div>
</div>

<!-- Modal -->
<div
  class="modal fade"
  data-backdrop="static"
  data-keyboard="false"
  id="settings-modal"
  tabindex="-1"
  role="dialog"
  aria-labelledby="exampleModalLabel"
  aria-hidden="true"
>
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Settings</h5>
        <button
          type="button"
          class="close"
          data-dismiss="modal"
          aria-label="Close"
        >
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="user-details-block"></div>
        <div id="roles-block"></div>
        <div id="permissions-block"></div>
        <div id="menus-block" @if(\Auth::user()->
          id !== 1 ) class="d-none" @endif>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
          Close
        </button>
        {{--
        <button type="button" class="btn btn-primary">Save changes</button> --}}
      </div>
    </div>
  </div>
</div>

<!-- Menu Modal -->
<div
  class="modal fade"
  id="showmenus"
  tabindex="-1"
  aria-labelledby="exampleModalLabel"
  aria-hidden="true"
>
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">
          Access to pages <span id="for"></span>
        </h5>
        <button
          type="button"
          class="close"
          data-dismiss="modal"
          aria-label="Close"
        >
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="render-menus"></div>
    </div>
  </div>
</div>

<!-- Permissions Modal -->
<div
  class="modal fade"
  id="showpermissions"
  tabindex="-1"
  aria-labelledby="exampleModalLabel"
  aria-hidden="true"
>
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">
          Module Permissions <span id="for"></span>
        </h5>
        <button
          type="button"
          class="close"
          data-dismiss="modal"
          aria-label="Close"
        >
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="render-permissions"></div>
    </div>
  </div>
</div>

@stop @section('css') {{--
<link rel="stylesheet" href="/css/admin_custom.css" /> --}} @stop @section('js')
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>

<script>
  const USERID = '{{ Auth::user()->id }}';
  fetch_users();

  function fetch_users() {
    var url = `{{ route('users.list',1) }} `;
    $(`#user-list-div`).dxDataGrid({
      dataSource: DevExpress.data.AspNet.createStore({
        // key: 'engineer_id',
        loadUrl: url,
      }),
      showBorders: true,
      showRowLines: true,
      allowColumnResizing: false,
      columnAutoWidth: true,
      columnHidingEnabled: false,
      rowAlternationEnabled: true,
      filterRow: {
        visible: true,
      },
      loadPanel: {
        // indicatorSrc: `${ASSET_URL}/assets/images/loader4.gif`,
        text: 'Loading...',
        showPane: true,
      },
      selection: {
        mode: 'single',
      },
      scrolling: {
        mode: 'virtual',
      },
      paging: {
        enabled: false,
        pageSize: 10,
      },
      //columnHidingEnabled: true,
      rowAlternationEnabled: true,
      wordWrapEnabled: true,
      scrolling: {
        scrollByContent: true,
      },
      export: {
        enabled: true,
        fileName: new Date() + ' user_list',
      },
      columns: [
        {
          dataField: 'id',
          visible: false,
          caption: 'User Id',
        },
        {
          dataField: 'name',
          caption: 'Name',
        },
        {
          dataField: 'email',
          caption: 'Email',
          visible: false,
        },
        {
          dataField: 'roles',
          caption: 'Role',
        },
        {
          dataField: 'permissions',
          caption: 'Module Permissions',
          allowFiltering: false,
          cellTemplate: function (container, options) {
            var data = options.data;
            var permissions = [];
            var permissions = JSON.parse(data.his_permissions);
            var html = '';
            var forName = data.name;

            var html = `<a class='badge badge-primary text-white' onclick='loadPermissions(${JSON.stringify(
              permissions
            )}, ${JSON.stringify(forName)})'>View</a>`;

            container.append(html);
          },
        },
        {
          dataField: 'his_menu',
          caption: 'Access',
          allowFiltering: false,
          visible: USERID == 1 ? true : false,
          cellTemplate: function (container, options) {
            var data = options.data;
            var menus = [];
            var menus = JSON.parse(data.his_menus);
            var html = '';
            var forName = data.name;
            console.log(forName);
            // html =  html.str.replace(/,\s*$/, "");
            var html = `<a class='badge badge-primary text-white' onclick='loadMenus(${JSON.stringify(
              menus
            )}, ${JSON.stringify(forName)})'>View</a>`;

            container.append(html);
          },
        },
        {
          dataField: 'created',
          width: 100,
          caption: 'Created At',
          dataType: 'date',
        },
        {
          dataField: 'Action',
          caption: 'Action',
          visible: true,
          allowFiltering: false,
          width: 85,
          alignment: 'left',
          cssClass: '__Action',
          cellTemplate: function(container, options) {
        var data = options.data;

        var link = $(`<a class="" href="javascript:void(0)" title="edit">`)
            .html("<i class='fas fa-cog'></i> Action")
            .attr('href', 'javascript:void(0)');

        link.on('click', function() {
                   
                    $('#settings-modal').modal('show');
                    $('#user-details-block').html(
                        `<h5>User Name: ${data.name}</h5><hr>`
                    );
                    var permissions = data.all_permissions;
                    var his_permissions = JSON.parse(data.his_permissions);
                    var all_menus = data.all_menus;
                    var his_menus = JSON.parse(data.his_menus);
                    var his_roles = JSON.parse(data.his_roles);
                    var roles = data.all_roles;
                    // console.log(his_permissions)
                    var html_form = `<h5><strong>User Roles</strong></h5><form method='post' id='user-role-frm' method='post'>
                        @csrf
                        <select name='roles' id='roles_1' class='form-control select2bs4'>
                        `;
                    var is_checked;
                    var flag = false;
                    $.each(roles, (i) => {
                        is_checked = '';
                        is_checked = '';
                        flag = his_roles.findIndex(function(his_roles) {
                            return his_roles.id === roles[i].id;
                        });
                        if (flag != -1) {
                            is_checked = 'selected';
                        }
                        flag = false;
                        html_form += `<option value='${roles[i].id}' ${is_checked}>${roles[i].name}</option>`;
                    });

                    html_form += `</select><hr><input type='hidden' id='user_id_r' name='user_id_p' value="${data.id}">
                        <p align='right'><button type='submit' id='role-btn' class='btn btn-primary'>Update Roles</button></p></form><br>`;
                    $('#roles-block').html(html_form);

                    // permissions with parent system module info
                    var html_form = `<h5><strong>User Permissions</strong></h5><form method='post' id='user-permission-frm' method='post'>
                              <div class='wrapper'> 
                        @csrf
                        `;
                    var is_checked;
                    var flag = false;
                    $.each(permissions, (j) => {
                        html_form += `<div class="card-header p-1 mb-2 mt-2">
                                              <label>${permissions[j].system_type}</label>
                                            </div><div class='checkboxes-wrapper row shadow p-1 mt-1 mb-1'>`;
                        let pp = permissions[j].permissions;
                        // permissions
                        $.each(pp, (i) => {
                            is_checked = '';
                            flag = his_permissions.findIndex(function(his_permissions) {
                                return his_permissions.id === pp[i].id;
                            });
                            if (flag != -1) {
                                is_checked = 'checked';
                            }
                            flag = false;
                            html_form += `<div class='col-lg-3'><input type='checkbox' name='permissions' ${is_checked} value='${pp[i].id}'> <label>${pp[i].name}</label>&nbsp; </div>`;
                        });

                        html_form += `</div>`;
                    });

                    html_form += `</div><hr><input type='hidden' id='user_id_p' name='user_id_p' value="${data.id}">
                          <p align='right'><button type='submit' id='permission-btn' class='btn btn-primary'>Update Permissions</button></p></form><br>`;
                    $('#permissions-block').html(html_form);

                    html_form = `<h5><strong>User Menus</strong></h5>
                                <form id='user-menu-mapping-frm' method='post'>
                                          @csrf
                  <div class="acc-container">`;

                    $.each(all_menus, (i) => {
                        is_checked = '';
                        flag = his_menus.findIndex(function(his_menus) {
                            return his_menus.menu_id === all_menus[i].id;
                        });
                        if (flag != -1) {
                            is_checked = 'checked';
                        }
                        flag = false;
                        html_form += `<div class="accordion-body">
                        <div class="heading-sec-wrap">
                        <input type='checkbox' name='menus' ${is_checked} value='${all_menus[i].id}'>
                        <span class="accordion-heading">
                                      


                    ${all_menus[i].menu_name} &nbsp;</span>`;

                        if (all_menus[i].children.length > 0) {
                            html_form += `
                              <div class="accordion-content"><ul>`;
                            $.each(all_menus[i].children, (j) => {
                                html_form += ` <li><input type='checkbox' name='menus' ${is_checked} value='${all_menus[i].children[j].id}'> ${all_menus[i].children[j].menu_name} &nbsp;</li>`;
                            });
                            html_form += `</ul></div>`;

                        }

                        html_form += "</div></div>";
                    });


                    html_form += `</div>`;
                    html_form += `<hr><input type='hidden' id='user_id_m' name='user_id_m' value="${data.id}">
                          <p align='right'><button type='submit' id='menu-btn' class='btn btn-primary'>Update Menu Access</button></p></form><hr>`;
                    $('#menus-block').html(html_form);
                    accordion()
                   
                }) // link
               
                return link;
            }, // cell template
          },
      ],
    });
  }

  /** Permissions update */
  $(document).on('click', '#permission-btn', (e) => {
    e.preventDefault();
    var permissions = [];
    var ds = $("input[name='permissions']:checked").each(function (i) {
      permissions[i] = this.value;
    });
    var user_id = $('#user_id_p').val();

    $.ajax({
      url: "{{  route('update.user.permissions') }}",
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
      },
      type: 'POST',
      data: { permissions: permissions, user_id: user_id },
      beforeSend: (r) => {
        toastr.info('Updating...');
      },
      error: (r) => {
        toastr.error('Server Error ' + r.responseText);
        $('#loaderGif').hide();
      },
      success: (r) => {
        toastr.success('Settings updated successfully');
        $('#loaderGif').hide();
        $('#settings-modal').modal('hide');
        fetch_users();
        console.log(r);
      },
    });
  });

  /** Roles update */
  $(document).on('click', '#role-btn', (e) => {
    e.preventDefault();
    var roles = [];
    // var ds = $("input[name='roles']:selected").each(function(i){
    //     roles[i] = this.value;
    // });
    var ds = $('#roles_1').val();
    roles[0] = ds;
    var user_id = $('#user_id_r').val();
    console.log(roles);
    $.ajax({
      url: "{{  route('update.user.roles') }}",
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
      },
      type: 'POST',
      data: { roles: roles, user_id: user_id },
      beforeSend: (r) => {
        toastr.info('Updating...');
      },
      error: (r) => {
        toastr.error('Server Error ' + r.responseText);
        $('#loaderGif').hide();
      },
      success: (r) => {
        toastr.success('Settings updated successfully');
        $('#loaderGif').hide();
        $('#settings-modal').modal('hide');
        fetch_users();
        console.log(r);
      },
    });
  });

  /** Menu update */
  $(document).on('click', '#menu-btn', (e) => {
    e.preventDefault();
    var menus = [];
    var ds = $("input[name='menus']:checked").each(function (i) {
      menus[i] = this.value;
    });
    var user_id = $('#user_id_m').val();

    $.ajax({
      url: "{{  route('update.user.menus') }}",
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
      },
      type: 'POST',
      data: { menus: menus, user_id: user_id },
      beforeSend: (r) => {
        toastr.info('Updating...');
      },
      error: (r) => {
        toastr.error('Server Error ' + r.responseText);
        $('#loaderGif').hide();
      },
      success: (r) => {
        toastr.success('Settings updated successfully');
        $('#loaderGif').hide();
        $('#settings-modal').modal('hide');
        fetch_users();
        console.log(r);
      },
    });
  });

  function loadMenus(menus, forName) {
    var html = `No menus set`;
    if (menus.length > 0) {
      html = `<div class='row'>`;
      $.each(menus, (i) => {
        html += `<div class='col-lg-4 text-center mb-2'><span class='badge badge-primary' style='min-width:150px !important;'> ${menus[i].menu.menu_name}</span></div>`;
      });

      html += `</div>`;
    }
    $('#for').html(' for ' + forName);
    $('#render-menus').html(html);

    $('#showmenus').modal('show');
  }

  function loadPermissions(permissions, forName) {
    var html = `No permissions set`;
    if (permissions.length > 0) {
      html = `<div class='row'><div class='card-header col-lg-12 mb-2 p-1'>SAP</div>`;
      $.each(permissions, (i) => {
        html += `<div class='col-lg-4 text-center mb-2'><span class='badge badge-warning' style='min-width:150px !important;'> ${permissions[i].name}</span></div>`;
      });

      html += `<div class='card-header col-lg-12 mb-2 p-1'>CRM</div>
            <div class='card-header col-lg-12 mb-2 p-1'>Email</div>
            <div class='card-header col-lg-12 mb-2 p-1'>System</div></div>`;
    }
    $('#for').html(' for ' + forName);
    $('#render-permissions').html(html);

    $('#showpermissions').modal('show');
  }

  /** Accordian Code */
function accordion() {
  let acc = document.querySelectorAll(".accordion-heading");
  for (let i = 0; i < acc.length; i++) {
      console.log('innnnnn')
    let panelChild = acc[i].nextElementSibling;
    if(panelChild){
      acc[i].classList.add('has-child');
    }
    acc[i].addEventListener("click", function () {
    this.classList.toggle("active");
    let panel = this.nextElementSibling;
    if(panel){
      if (panel.style.maxHeight) {
          panel.style.maxHeight = null;
      } else {
          panel.style.maxHeight = panel.scrollHeight + "px";
      }   
    }
    });
  }
}



</script>
@stop
