@extends('adminlte::page')

@section('title', 'Change Password')

@section('content_header')
<meta http-equiv="x-ua-compatible" content="ie=edge">
<meta name="csrf-token" content="{{ csrf_token() }}">
 <style>
#div_tree ul ul ul li { 

    display: inline-block !important;
}     

</style> 
@stop
@section('content')
    <div class="tab-content p-1">
        {{-- <div class="loading loadr d-none">Loading&#8230;</div> --}}
        <div class="tab-pane active dx-viewport" id="request_block">
            <div class="demo-container">
                <div class="top-info">
                    <div class="table-heading-custom"><h4 class="right"><i class="fas fa-key"></i> Change Password</h4></div>
               
                    
                </div>
                <div class="search-form-wrap p-2">
                    <form id="change-password-frm" method="post">
                        <div class="row wrapper">
                            <div class="col-lg-3">

                                <input type="password" id="current_password" name="current_password" class="form-control" placeholder="Enter Current Password">
                            </div>
                            <div class="col-lg-3">
 
                                <input type="password" id="new_password" name="new_password" class="form-control" placeholder="Enter New Password">
                            </div>
                            <div class="col-lg-3">
                       
                                <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Confirm New Password">
                            </div>
                            <div class="col-lg-3">
                                <button id="change-password-btn" class="btn btn-primary"><i class='fas fa-sync'></i> Change</button>
                            </div>
                        </div>
                    </form>  
                </div>
                
            </div>
        </div>
    </div>

    @stop

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/custom/main.css') }}">
@stop

@section('js')

<script>
    $("#change-password-btn").on('click', (e) => {
        e.preventDefault();
        var current_password = $("#current_password").val();
        var new_password = $("#new_password").val();
        var confirm_new_password = $("#confirm_password").val();

        if(new_password != confirm_new_password)  {
            toastr.error('New password must match with confirm password, try again');
            return false;
        }

        if(new_password.length < 8) {
            toastr.error('New password length must be atleast 8 characters');
            return false;
        }
        
        if(new_password == current_password) {
            toastr.error('New password cant be same as current');
            return false;
        }

        $.ajax({
            url: "{{  route('change.password') }}",
            headers:{
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            type:"POST",
            data:{current_password, new_password},
            beforeSend:(r) => {
                //toastr.info('Verifying.')
                customLoader(1)
            },
            error:(r) => {
                toastr.error('Server Error '+r.responseText)
                customLoader(0)
                console.log(r)

            },
            success:(r) => {
                customLoader(0)
                if(r.status == 200) {
                    toastr.success(r.message)
                    
                    $("#change-password-frm")[0].reset();
                    console.log(r)
                } else {
                    toastr.info(r.message)
                    
                }
            }
        })
    })
    


</script>

@stop