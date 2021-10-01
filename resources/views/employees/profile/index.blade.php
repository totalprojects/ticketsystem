@extends('adminlte::page')

@section('title', 'Employee Profile')

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
            .profile-image-sec {
    border-radius: 50%;
    border: 3px solid #fff;
    margin: 0 auto;
    position: relative;
}

.profile-image-sec img {
    width: 140px;
    border-radius: 50%;
    overflow: hidden;
    border: 3px solid #fff;
    box-shadow: 0 0 10px #ccc;
    margin: 0 auto;
}
.info-sec span {
    display: block;
    line-height: 1;
    margin-bottom: 5px;
    font-weight: 600;
}


.info-sec span.name {
    font-size: 25px;
    font-weight: 700;
}

.info-sec i {
    font-size: 13px;
    display: inline-block;
    vertical-align: inherit;
    width: 24px;
    height: 25px;
    text-align: center;
    border: 1px solid #c9c9c9;
    line-height: 25px;
    color: #c9c9c9;
    border-radius: 5px;
    margin-left: 10px;
    cursor: pointer;
}
.info-sec span.badge:hover {
    opacity: 0.8;
}

.info-sec span.badge {
    display: inline-block;
    font-weight: 400;
    line-height: 1;
    cursor: pointer;
    padding: 3px 5px 5px 5px;
    background: #e4ff55;
    color: #000;
    font-size: 12px;
    margin-bottom: 0;
}
.image-edit {
    position: absolute;
    bottom: 6px;
    z-index: 99;
    right: 35px;
    background: #255e61;
    height: 30px;
    width: 30px;
    text-align: center;
    line-height: 30px;
    border-radius: 50%;
    color: #fff;
    cursor: pointer;
}
.info-sec small {
    font-weight: 600;
}
.container.profile-sec {
    background: #f5f5f5;
    padding: 35px;
}
.edit-info-profile {
    position: absolute;
    top: 0;
    right: 20px;
}

.edit-info-profile span {
    font-weight: 600;
    color: #255e61;
    margin-right: 5px;
}

.edit-info-profile i {
    color: #e4ff55;
    background: #255e61;
    height: 27px;
    width: 27px;
    text-align: center;
    line-height: 27px;
    border-radius: 50%;
}

.edit-info-profile {
    display: flex;
    align-items: center;
    cursor: pointer;
}
.profile-details {
    padding: 30px 0;
}
.details-sec ul {
    padding: 0;
    list-style: none;
    display: flex;
    flex-wrap: wrap;
    margin-bottom: 0;
}

.details-sec ul li {
    flex: 0 0 25%;
    max-width: 25%;
    margin-bottom: 5px;
}

.details-sec ul li h6 {
    margin-bottom: 0;
    font-weight: 600;
    font-size: 16px;
    margin-right: 10px;
}
.profile-details .nav-tabs a.active {
    border: 1px solid #ccc;
    background: #255e61;
    color: #e4ff55 !important;
    border-radius: 20px;
    margin-right: 10px;
    margin-bottom: 10px;
}
.profile-details .nav-tabs a {
    border: 1px solid #e4ff55;
    background: #e4ff55;
    color: #255e61 !important;
    border-radius: 20px;
    margin-right: 10px;
    margin-bottom: 10px;
}
.profile-details .nav-tabs {
    border-bottom: none;
}
.details-sec {
    padding: 15px;
    box-shadow: 0 0 5px #ccc;
    border-radius: 10px;
}
.edit-info-details span {
    font-weight: 600;
    color: #255e61;
    margin-right: 5px;
}

.edit-info-details i {
    color: #e4ff55;
    background: #255e61;
    height: 27px;
    width: 27px;
    text-align: center;
    line-height: 27px;
    border-radius: 50%;
}
.info-sec span:hover i {
    background: #255e61;
    color: #e4ff55;
}
.edit-info-details {
    display: flex;
    align-items: center;
    cursor: pointer;
    justify-content: flex-end;
    margin-bottom: 10px;
}
.profile-wrap {
    padding: 25px;
}

    </style>
    <div class="tab-content p-1">
        <div class="tab-pane active dx-viewport" id="employees">
           
            <div class="demo-container">
                <div class="top-info">
                  <div class="table-heading-custom"><h4 class="right"><i class="fas fa-user"></i> Employee Profile </h4></div>
    
                </div>
                <div id="employee-list-div" style="height:auto"></div>
            </div>

            <div class="profile-wrap">
      <div class="profile-sec">
         <div class="row">
            <div class="col-md-2">
              <div class="profile-image-sec">
                 <img src="{{ asset('assets/images/163104-200-grey.png') }}">
                 <div class="image-edit">
                    <i class="fa fa-camera" aria-hidden="true"></i>
                 </div>
              </div> 
            </div>
            <div class="col-md-10">
              <div class="info-sec">
    
                     <span class="name">Suman Pan <small>(Employee code: 4578987)</small> <i class="fa fa-clone"></i></span>
                     <span class="company">IT Department</span>
                    <span class="designation">Business Analyst</span>
                    <span class="desk-extn">033 2345 12345 <small>(Ofiice desk)</small> <i class="fa fa-clone"></i></span>
                     <span class="mobile">9090909090 <small>(Mobile)</small> <i class="fa fa-clone"></i></span>
                     <span class="email">suman.pan@shyamsteel.com <i class="fa fa-clone"></i></span>
                     
                
                 
                 
                 
                 
                 
                 
              </div> 
              <div class="edit-info-profile">
                 <span>Edit </span><i class="fa fa-pen"></i>
              </div>
            </div>
         </div>
      </div>
      <div class="profile-details">
         <nav>
           <div class="nav nav-tabs" id="nav-tab" role="tablist">
             <a class="nav-item nav-link active" id="nav-Hardware-tab" data-toggle="tab" href="#nav-Hardware" role="tab" aria-controls="nav-Hardware" aria-selected="true">Hardware Details Assests </a>
             <a class="nav-item nav-link" id="nav-Email-tab" data-toggle="tab" href="#nav-Email" role="tab" aria-controls="nav-Email" aria-selected="false">Email Details</a>
             <a class="nav-item nav-link" id="nav-Solution-tab" data-toggle="tab" href="#nav-Solution" role="tab" aria-controls="nav-Solution" aria-selected="false">Software Solution Access</a>
           </div>
         </nav>
         <div class="tab-content" id="nav-tabContent">
           <div class="tab-pane fade show active" id="nav-Hardware" role="tabpanel" aria-labelledby="nav-home-tab">
              <div class="details-sec">
               <div class="edit-info-details">
                 <span>Edit </span><i class="fa fa-pen"></i>
              </div>
                   <ul>
                       <li>
                           <h6>Assests Type: </h6>
                           <p>Mobile</p>
                       </li>
                       <li>
                           <h6>Serial Number: </h6>
                           <p>LKZ88954792110</p>
                       </li>
                       <li>
                           <h6>Issue Date:</h6>
                           <p>12-10-2020</p>
                       </li>
                       <li>
                           <h6>Warrenty Period:</h6>
                           <p>30.09.2023</p>
                       </li>
                       <li>
                           <h6>SIM:</h6>
                           <p>Vodafone</p>
                       </li>
                       <li>
                           <h6>Number:</h6>
                           <p>90909090</p>
                       </li>
                       <li>
                           <h6>IMEI Number:</h6>
                           <p>054545454f5454</p>
                       </li>
                       <li>
                           <h6>Head Phone:</h6>
                           <p>No</p>
                       </li>

                   </ul>
              </div>
           </div>
           <div class="tab-pane fade" id="nav-Email" role="tabpanel" aria-labelledby="nav-Email-tab">
               <div class="details-sec">
                  <div class="edit-info-details">
                    <span>Edit </span><i class="fa fa-pen"></i>
                 </div>
                   <ul>
                       <li>
                           <h6>Email Type: </h6>
                           <p>O365</p>
                       </li>
                       <li>
                           <h6>Standard/Business: </h6>
                           <p>Standard</p>
                       </li>
                       <li>
                           <h6>Exclusion Type:</h6>
                           <p>Default/E1/E2/E3</p>
                       </li>
                       <li>
                           <h6>Mail On Mobile:</h6>
                           <p>Yes</p>
                       </li>
                   </ul>
              </div>
           </div>
           <div class="tab-pane fade" id="nav-Solution" role="tabpanel" aria-labelledby="nav-contact-tab">
               <div class="details-sec">
                  <div class="edit-info-details">
                       <span>Edit </span><i class="fa fa-pen"></i>
                    </div>
                   <ul>
                       <li>
                           <h6>AD User: </h6>
                           <p>No</p>
                       </li>
                       <li>
                           <h6>ADDetails: </h6>
                           <p>--</p>
                       </li>
                       <li>
                           <h6>NAS Drive Access:</h6>
                           <p>--</p>
                       </li>
                       <li>
                           <h6>Access Folder List:</h6>
                           <p>Yes</p>
                       </li>
                       <li>
                           <h6>Folder Access Rights:</h6>
                           <p>Read/Write/Delete</p>
                       </li>
                       <li>
                           <h6>VPN Access:</h6>
                           <p>Yes</p>
                       </li>
                       <li>
                           <h6>SSL VPN/IPSEC VPN:</h6>
                           <p>Yes</p>
                       </li>
                       <li>
                           <h6>Active/Inactive VPN:</h6>
                           <p>Active</p>
                       </li>
                   </ul>
              </div>
           </div>
         </div>
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

</script>
@stop
