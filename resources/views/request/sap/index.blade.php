@extends('adminlte::page')

@section('title', 'SAP Request')

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
        <div class="font-weight-bold m-2 font-italic text-primary"><h4 class="right"><i class="fas fa-copy"></i> SAP Requests</h4><br></div>
        <div class="tab-pane active dx-viewport" id="request_block">
            <div class="demo-container p-3">
                <button id="request_btn" class='btn btn-primary p-1'><i class="fas fa-fist-raised"></i> Raise Request</button>
                <div id="request-list-div" style="height:600px"></div>
            </div>
        </div>
    </div>
    <!-- Request Modal -->
    <div class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" id="requestModal" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">SAP Requests</h5>
              <div class="loading1 ml-2 mt-1 border border-warning rounded d-none" style="padding: 1.5px;"><i class='fas fa-spinner fa-spin'></i> Loading&#8230;</div>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
               
                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-12 col-xl-12 text-center p-2 mb-2 sap-req-form">
                            <div class="card">
                                <h2 id="heading">SAP Request Form</h2>
                                <p>Fill all form field to go to next step</p>
                                <form id="msform" method="post">
                                    <!-- progressbar -->
                                    <ul id="progressbar">
                                        <li class="active" id="account"><i class="fas fa-user"></i><strong>Step 1</strong></li>
                                        <li id="personal"><i class="far fa-address-card"></i><strong>Step 2</strong></li>
                                        <li id="payment"><i class="fas fa-store-alt"></i><strong>Step 3</strong></li>
                                        <li id="confirm"><i class="fas fa-building"></i><strong>Step 4</strong></li>
                                        <li id="confirm2"><i class="fas fa-tasks"></i><strong>Step 5</strong></li>
                                    </ul>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div> <br> <!-- fieldsets -->
                                    
                                    <fieldset>
                                        <div class="form-card">
                                            <div class="row">
                                                <div class="col-7">
                                                    <h2 class="fs-title">Basic Information:</h2>
                                                </div>
                                                <div class="col-5">
                                                    <h2 class="steps">Step 1 - 5</h2>
                                                </div>

                                            </div> 
                                            <div class="row">
                                                <div class="col-lg-3 pt-2">
                                                    <label for="company_name">Company Name</label>
                                                        <select name="company_name[]" id="company_name" placeholder="Select Companies" class="form-control select2bs4" multiple>
                                                            <option value="">--SELECT COMPANY--</option>
                                                            @foreach($companies as $company)
                                                                <option value="{{ $company->company_code }}">{{ $company->company_name }} ({{ $company->company_code }})</option>
                                                            @endforeach
                                                        </select>
                                                   
                                                </div>
                                                <div class="col-lg-3 pt-2">
                                                    <label for="plant_name">Plant Name </label>
                                                        <select name="plant_name[]" id="plant_id" data-placeholder="Select Plant Name" class="form-control select2bs4" multiple>
                                                            <option value="">--SELECT PLANT--</option>
                                                        </select>
                                                </div>
                                                <div class="col-lg-3 pt-2">
                                                    <label for="storage_location">Storage Location </label>
                                                        <select name="storage_location[]"  id="storage_id" data-placeholder="Select Storage Location" class="form-control select2bs4" multiple>
                                                            <option value=""></option>
                                                        </select>
                                                </div>
                                                <div class="col-lg-3 pt-2">
                                                    <label for="business_area"> Business Area </label>
                                                        <select name="business_area[]"  id="business_location" data-placeholder="Select Business Area" class="form-control select2bs4" multiple>
                                                            <option value=""></option>
                                                            @foreach($business as $b)
                                                                <option value="{{ $b->business_code }}">{{ $b->business_name }} </option>
                                                            @endforeach
                                                        </select>
                                                </div>
                                            </div>
                                        </div> 
                                        <input type="button" name="next" class="next action-button" value="Next" />
                                    </fieldset>
                                    <!-- Select Role -->
                                    <fieldset>
                                        <div class="form-card">
                                            <div class="row">
                                                {{-- <div class="col-7">
                                                    <h2 class="fs-title">Select Role:</h2>
                                                </div> --}}
                                                <div class="col-5">
                                                    <h2 class="steps">Step 2 - 5</h2>
                                                </div>
                                            </div>
                                            <div class="row justify-content-center">
                                               
                                                <div class="col-lg-6 pt-2">
                                                    
                                                    <label for="sales_org">Role </label>
                                                    <span><small>(This option is not mandatory)</small></span>
                                                        <select name="role"  id="role" data-placeholder="Select Role" class="form-control select2bs4">
                                                            <option value=""></option>
                                                            @foreach($roles as $role)
                                                                <option value="{{ $role->id }}"> {{  $role->name }}</option>
                                                            @endforeach
                                                        </select>
                                                </div>
                                               
                                            </div> 
                                             
                                        </div> <input type="button" name="next" class="next action-button" value="Next" /> <input type="button" name="previous" class="previous action-button-previous" value="Previous" />
                                    </fieldset>
                                    <fieldset>
                                        <div class="form-card">
                                            <div class="row">
                                                <div class="col-7">
                                                    <h2 class="fs-title">Select Type:</h2>
                                                </div>
                                                <div class="col-5">
                                                    <h2 class="steps">Step 3 - 5</h2>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <h5>Select any one option or both to continue</h5>
                                                </div>
                                                <div class="col-lg-4 pt-2">
                                                    <label for="sales_org">Sales Organization </label>
                                                        <select name="sales_org[]"  id="sales_org" data-placeholder="Select Sales Organization" class="form-control select2bs4" multiple>
                                                            <option value=""></option>
                                                        </select>
                                                </div>
                                                <div class="col-lg-4 pt-2">
                                                    <label for="purchase_org"> Purchase Organization </label>
                                                        <select name="purchase_org[]"  id="purchase_org" data-placeholder="Select Purchase Organization" class="form-control select2bs4" multiple>
                                                            <option value=""></option>
                                                            @foreach($po as $p)
                                                                <option value="{{ $p->id }}">{{ $p->po_name }} ({{ $p->po_code }}) </option>
                                                            @endforeach
                                                        </select>
                                                </div>  
                                                <div class="col-lg-4 pt-2">
                                                    <label for="action_type">Action</label>
                                                    <br>
                                                   <input type="radio" name="action_type" value="cr1"> Create &amp; 1<sup>st</sup> Release &nbsp;
                                                   <input type="radio" name="action_type" value="c"> Create &nbsp;
                                                   <input type="radio" name="action_type" value="r"> Release &nbsp;
                                                </div>   
                                                   
                                            </div> 
                                             
                                        </div> <input type="button" name="next" class="next action-button" value="Next" /> <input type="button" name="previous" class="previous action-button-previous" value="Previous" />
                                    </fieldset>
                                   
                                    <fieldset>
                                        <div class="form-card">
                                            <div class="row">
                                                <div class="col-7">
                                                    <h2 class="fs-title">Other Information:</h2>
                                                </div>
                                                <div class="col-5">
                                                    <h2 class="steps">Step 4 - 5</h2>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-4 pt-2">
                                                    <label for="division">Division </label>
                                                        <select name="division[]"  id="division" data-placeholder="Select Division" class="form-control select2bs4" multiple>
                                                            <option value=""></option>
                                                            @foreach($divisions as $division)
                                                                <option value="{{ $division->division_code }}">{{ $division->division_description }} </option>
                                                            @endforeach
                                                        </select>
                                                </div>
                                                <div class="col-lg-4 pt-2">
                                                    <label for="distribution_channel">Distribution Channel </label>
                                                        <select name="distribution_channel[]"  id="distribution_channel" data-placeholder="Select Distribution Channel" class="form-control select2bs4" multiple>
                                                            <option value=""></option>
                                                            @foreach($distributors as $distributor)
                                                                <option value="{{ $distributor->distribution_channel_code }}">{{ $distributor->distribution_channel_description }} </option>
                                                            @endforeach
                                                        </select>
                                                </div>
                                                <div class="col-lg-4 pt-2">
                                                    <label for="sales_office"> Sales Office </label>
                                                        <select name="sales_office[]"  id="sales_office" data-placeholder="Select Sales Office" class="form-control select2bs4" multiple>
                                                            <option value=""></option>
                                                        </select>
                                                </div>
                                                <div class="col-lg-4 pt-2">
                                                    <label for="purchase_group"> Purchase Group </label>
                                                        <select name="purchase_group[]"  id="purchase_group" data-placeholder="Select Purchase Group" class="form-control select2bs4" multiple>
                                                            <option value=""></option>
                                                            @foreach($pg as $p)
                                                                <option value="{{ $p->id }}">{{ $p->pg_description }} ({{ $p->pg_code }}) </option>
                                                            @endforeach
                                                        </select>
                                                </div> 
                                                <div class="col-lg-4 pt-2">
                                                    <label for="po_release"> PO Release </label>
                                                        <select name="po_release[]"  id="po_release" data-placeholder="Select PO Release" class="form-control select2bs4" multiple>
                                                            <option value=""></option>
                                                            @foreach($po_release as $p)
                                                                <option value="{{ $p->id }}">{{ $p->rel_description }} ({{ $p->rel_code }})</option>
                                                            @endforeach
                                                        </select>
                                                </div>

                                            </div>
                                        </div> 
                                        <input type="button" name="next" class="next action-button" value="Next" /> <input type="button" name="previous" class="previous action-button-previous" value="Previous" />
                                    </fieldset>
                                   
                                    <fieldset>
                                        <div class="form-card">
                                            <div class="row">
                                                <div class="col-7">
                                                    <h2 class="fs-title">Select T-Codes:</h2>
                                                </div>
                                                <div class="col-5">
                                                    <h2 class="steps">Step 5 - 5</h2>
                                                </div>
                                               
                                            </div> 
                                            <h2 class="purple-text text-center"><strong>Final Step</strong></h2> <br>
                                            <div class="row">
                                                <div class="col-lg-12" id="modules_tcodes_block">
                                                    <div id="div_tree"></div>
                                                </div>
                                            </div> 
                                            
                                        </div>
                                        <input type="button" name="next" class="next action-button" value="Next" /> 
                                        <input type="button" name="previous" class="previous action-button-previous" value="Previous" />
                                    </fieldset>
                                    <fieldset>
                                        <div class="form-card">
                                            <div class="row">
                                                <div class="col-12">
                                                    <h2 class="fs-title text-center">You may review all your selections</h2>
                                                </div>
                
                                            </div> <br><br>
                                            <h2 class="purple-text text-center"><strong>Your selections below</strong></h2> <br>
                                            <div class="row justify-content-center">
                                                <div class="col-lg-12">
                                                   <div id="review_selections"></div>
                                                </div>
                                            </div> 
                                            
                                        </div>
                                        <input type="button" name="next" id="finalSubmit" class="next action-button" value="Submit" /> 
                                        <input type="button" name="previous" class="previous action-button-previous" value="Previous" />
                                       
                                    </fieldset>
                                    <fieldset>
                                        <div class="form-card">
                                            <div class="row">
                                                <div class="col-12">
                                                    <h2 class="fs-title text-center">All the steps are complete!</h2>
                                                </div>
                
                                            </div> <br><br>
                                            <h2 class="purple-text text-center"><strong>Your request has been generated</strong></h2> <br>
                                            <div class="row justify-content-center">
                                                <div class="col-lg-12">
                                                    <h5 class="text-center">Please wait for the approval from concerned team</h5>
                                                </div>
                                            </div> 
                                            
                                        </div>
                                        <input type="button" name="close-modal" class="btn btn-secondary" value="Close" onclick="$('#requestModal').modal('hide')" />
                                    </fieldset>
                                </form>
                            </div>
                        </div>
                    </div>
               
            </div>
          </div>
        </div>
    </div>

    <!-- Request Status Modal -->
    <div class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" id="statusModal" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Request Status</h5>
              <div class="loading1 ml-2 mt-1 border border-warning rounded d-none" style="padding: 1.5px;"><i class='fas fa-spinner fa-spin'></i> Loading&#8230;</div>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
               
                    <div class="row justify-content-center">
                        <div class="col-12 col-md-12 col-lg-12 col-xl-12 text-center p-2 mb-2">
                            <div class="card">
                                {{-- <h2 id="heading">Request Status</h2> --}}
                                <div id="drop_status"></div>
                               
                            </div>
                        </div>
                    </div>
               
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

        $("input[name='action_type']").prop('disabled',true);

        $("#purchase_org").on('change', (e) => {
            var value = $("#purchase_org").val();

            if(value.length>0) {
                $("input[name='action_type']").prop('disabled',false);
            } else {
                $("input[name='action_type']").prop('disabled',true);
            }
        });

        var tree;

        function pickerTreeRender(data) {
            tree = new PickleTree({
                    switchCallback: (node) => {

                        //console.log(node)
                        if(node.title  == 'Create') {
                            if(node.checkStatus == true) {
                                //console.log('create')
                                let our_node = tree.getNode(node.value+4);
                                // console.log('release check')
                                // console.log(our_node)
                                if(our_node){
                                    if(our_node.title == 'Release') {
                                        our_node.toggleCheck(false);
                                    }
                                }
                                
                            }
                        }

                        if(node.title  == 'Release') {
                            if(node.checkStatus == true) {
                                //console.log('Release')
                                let our_node = {}
                                for(let i=4; i>=1; i--) {
                                    our_node = tree.getNode(node.value-i);
                                    if(our_node){
                                        our_node.toggleCheck(false);
                                    }
                                }   
                            }
                        }
                    },
                    c_target: 'div_tree',
                    c_config: {
                        logMode: false,
                        switchMode: true,
                        autoChild: true,
                        autoParent: true,
                        foldedIcon: 'fa fa-plus',
                        unFoldedIcon: 'fa fa-minus',
                        menuIcon: ['fa', 'fa-list-ul'],
                        foldedStatus: true,
                        drag: false
                    },
                    c_data: data
                });

                $(".ldr_tc").hide();
        }


       //loadTcodes();
        function loadTcodes(dependencies = []){
            pickerTreeRender([]);
            $("#modules_tcodes_block").append("<h3 class='ldr_tc badge badge-warning p-1 m-1'><i class='fas fa-spinner fa-spin'></i> Loading...</h3>")
           
            var role_id = $("#role").val();
            $.ajax({
                url:"{{ route('tcodes.for.user') }}",
                type:"GET",
                data:{role_id:role_id},
                error:(r) => {
                    toastr.error('Something went wrong');
                    console.log(r);
                },
                success:(r) => {
                    if(r) {
                        //toastr.success('Tcodes found');
                        console.log(r)
                        pickerTreeRender(r.data)
                    }
                }
            })
        }

        function populateStep3(sales, purhcase) {
            if(sales.length > 0) {
                $("#division").parent().removeClass('d-none');
                $("#distribution_channel").parent().removeClass('d-none');
                $("#sales_office").parent().removeClass('d-none');
            } else {
                $("#division").parent().addClass('d-none');
                $("#distribution_channel").parent().addClass('d-none');
                $("#sales_office").parent().addClass('d-none');
            }
            
            if(purhcase.length > 0) {
                var action = $("input[name='action_type']:checked").val();
                if(!action) {
                    toastr.error('Provide the actions for PO to continue');
                    flag = false
                }
                console.log(action)
                if(action === 'cr1' || action === 'r') {
                    $("#po_release").parent().removeClass('d-none');
                } else {
                    console.log('donone')
                    $("#po_release").parent().addClass('d-none');
                }
                //$("#po_release").parent().removeClass('d-none');
                $("#purchase_group").parent().removeClass('d-none');
            } else {
                $("#po_release").parent().addClass('d-none');
                $("#purchase_group").parent().addClass('d-none');
            }
        }

    $(document).ready(function(){

        var current_fs, next_fs, previous_fs; //fieldsets
        var opacity;
        var current = 1;
        var steps = $("fieldset").length;

        setProgressBar(current);

        $(".next").click(function(){

            current_fs = $(this).parent();
            next_fs = $(this).parent().next();
            
            /** Data validation for all steps */
            if(!stepValidation(current)) {
                return false;
            }

            if(current==6) {
                Swal.fire({
                    title: 'Do you want to submit the request?',
                    showDenyButton: true,
                    showCancelButton: true,
                    confirmButtonText: `Save`,
                    denyButtonText: `Don't save`,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            var data = tree.getSelected();
                            var formData = $("#msform").serializeArray();
                            var finalArray = [];
                            $.each(data, (i) => {
                                finalArray[i] = {
                                    moduleset : data[i].addional
                                }
                            });
                            formData.push({name:'module', value: JSON.stringify(finalArray)});

                            finalCall(formData);
                            
                            //Swal.fire('Saved!', '', 'success')
                            $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

                    //show the next fieldset
                    next_fs.show();
                    //hide the current fieldset with style
                    current_fs.animate({opacity: 0}, {
                    step: function(now) {
                    // for making fielset appear animation
                    opacity = 1 - now;

                    current_fs.css({
                    'display': 'none',
                    'position': 'relative'
                    });
                    next_fs.css({'opacity': opacity});
                    },
                    duration: 500
                    });
                    setProgressBar(++current);
                                } else if (result.isDenied) {
                                    Swal.fire('Changes are not saved', '', 'info')
                                    return false;
                                }
                            });
            } else {
                    $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

                //show the next fieldset
                next_fs.show();
                //hide the current fieldset with style
                current_fs.animate({opacity: 0}, {
                step: function(now) {
                // for making fielset appear animation
                opacity = 1 - now;

                current_fs.css({
                'display': 'none',
                'position': 'relative'
                });
                next_fs.css({'opacity': opacity});
                },
                duration: 500
                });
                setProgressBar(++current);
            }
          

            //Add Class Active
            
        });

        $(".previous").click(function(){

        current_fs = $(this).parent();
        previous_fs = $(this).parent().prev();

        //Remove class active
        $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

        //show the previous fieldset
        previous_fs.show();

        //hide the current fieldset with style
        current_fs.animate({opacity: 0}, {
        step: function(now) {
        // for making fielset appear animation
            opacity = 1 - now;

            current_fs.css({
            'display': 'none',
            'position': 'relative'
            });
            previous_fs.css({'opacity': opacity});
            },
            duration: 500
        });
        setProgressBar(--current);
        });

        function setProgressBar(curStep){
        var percent = parseFloat(100 / steps) * curStep;
        percent = percent.toFixed();
        $(".progress-bar")
        .css("width",percent+"%")
        }

        $(".submit").click(function(){
        return false;
        })

    });
    /** multi step form ends here */

        $("#request_btn").on('click', () => {

            $("#requestModal").modal('show');
        })
      $("#company_name").on('change', () => {
          
        var company_id = $("#company_name").val();
        $.ajax({
            url: "{{ route('get.plants') }}",
            data: {company_id},
            type:"GET",
            error: (response) => {
                console.log(response)
                toastr.error('Something went wrong [plantjax]');
            },
            success: (response) => {
                
                var plants = response.data;
                var html = `<option value=''>--SELECT--</option>`;
                $.each(plants, (i) => {
                    html += `<option value='${plants[i].plant_code}'> ${plants[i].plant_name} (${plants[i].plant_code}) </option>`;
                });
                $("#plant_id").html(html);

                var so = response.so;
                var html2 = `<option value=''>--SELECT--</option>`;
                $.each(so, (i) => {
                    html2 += `<option value='${so[i].id}'> ${so[i].so_description} (${so[i].so_code})  </option>`;
                });
                $("#sales_org").html(html2);

                $(".loading").addClass('d-none');
            }
        })
      });

      $("#plant_id").on('change', () => {
        var plant_id = $("#plant_id").val();
        $.ajax({
            url: "{{ route('get.storages') }}",
            data: {plant_id},
            type:"GET",
            error: (response) => {
                console.log(response)
                toastr.error('Something went wrong [plantjax]');
            },
            success: (response) => {
                
                var storages = response.data;
                var html = `<option value=''>--SELECT--</option>`;
                $.each(storages, (i) => {
                    html += `<option value='${storages[i].id}'> ${storages[i].storage_description} (${storages[i].storage_code}) </option>`;
                });
                $("#storage_id").html(html);
                $(".loading").addClass('d-none');
            }
        })
      });

      $("#storage_id").on('change', () => {
         
        $(".loading").addClass('d-none');
      });

      $("#distribution_channel").on('change', () => {
            var division_code = $("#division").val();
            var distribution = $("#distribution_channel").val();
            var so = $("#sales_org").val();

            $.ajax({
                url: "{{ route('get.sales_office') }}",
                data: {division_code, distribution, so},
                type:"GET",
                error: (response) => {
                    console.log(response)
                    toastr.error('Something went wrong [sojax]');
                },
                success: (response) => {
                    
                    var storages = response.data;
                    var html = `<option value=''>--SELECT--</option>`;
                    $.each(storages, (i) => {
                        html += `<option value='${storages[i].id}'> ${storages[i].sales_office.sales_office_name} (${storages[i].sales_office_code}) </option>`;
                    });
                    $("#sales_office").html(html);
                    $(".loading").addClass('d-none');
                }
            })
      });

      /** Fetch SAP Requests */
      fetch_data();
      function fetch_data(){
    function isNotEmpty(value) {
        return value !== undefined && value !== null && value !== "";
    }
   // var jsonData = [];
   var jsonData = new DevExpress.data.CustomStore({
       key: "request_id",
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
           var url = "{{ route('fetch.self.request') }}"
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
                   window.subData = res.subArray;
                   console.log(res)
                   deferred.resolve(data, {
                       totalCount: res.totalCount,
                   });
                   deferred.resolve(window.subData)
                   
               },
               error: function () {
                   deferred.reject("Data Loading Error");
               },
               //timeout: 2000000
           });
           return deferred.promise();
       }
  });
  $("#request-list-div").dxDataGrid({
       dataSource: jsonData,
       KeyExpr: "request_id",
       showBorders: true,
       showRowLines: true,
       rowAlternationEnabled: true,
       allowColumnResizing: true,
       columnAutoWidth:true,
       columnHidingEnabled:false,
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
       sorting: {
            mode: "none"
        },
       wordWrapEnabled: false,
       columns: [
           {
            caption:"Req No.",
            dataField:"request_id",
           },
           {
            caption:"User Name",
            dataField:"user_name",
           },
           {
                caption: 'Company Names',
                dataField:"company_name",
                cellTemplate:(container, options) => {
                    //console.log(options)
                    var company_names = JSON.parse(options.data.company_name);
                    var html = ``;
                    $.each(company_names, (i) => {
                        html += `<span>${company_names[i].company_name} (${company_names[i].company_code})</span>`;
                    });
                    container.append(html)
                }
            },
            {
                caption: 'Plant Names',
                dataField:"plant_name",
                cellTemplate:(container, options) => {
                    //console.log(options)
                    var plant = JSON.parse(options.data.plant_name);
                    var html = ``;
                    $.each(plant, (i) => {
                        html += `<span>${plant[i].plant_name} (${plant[i].plant_code})</span>`;
                    });
                    container.append(html)
                }
            },
            {
                caption: 'Storage Location',
                dataField:"storage_location",
                cellTemplate:(container, options) => {
                    //console.log(options)
                    var storage = JSON.parse(options.data.storage_location);
                    var html = ``;
                    $.each(storage, (i) => {
                        html += `<span>${storage[i].storage_description} (${storage[i].storage_code})</span>`;
                    });
                    container.append(html)
                }
            },
            {
                caption: 'Business Area',
                dataField:"business_area",
                cellTemplate:(container, options) => {
                    //console.log(options)
                    var business = JSON.parse(options.data.business_area);
                    var html = ``;
                    $.each(business, (i) => {
                        html += `<span>${business[i].business_name} (${business[i].business_code})</span>`;
                    });
                    container.append(html)
                }
            },
            {
                  caption: 'Status',
                  dataField:"status",
                  cellTemplate:(container, options) => {
                      //console.log(options.data.module)
                      var status = JSON.parse(options.data.status);
                      var status_logs = options.data.req_log;
                      var created_at = options.data.created_at;
                      var req_id = options.data.req_int;

                      var html = ``;
                      html = `<a href='javascript:void(0)' onClick='loadStatusModal(${status}, "${created_at}", ${status_logs}, ${req_id})' class='btn btn-warning p-1' style='font-size:14px'><i class='fas fa-eye'></i> View</a>`;
                      container.append(html)
                  }
              },
                           
            ],
            masterDetail: {
                enabled: true,
                template: function(container, options) {
                    $("<div>")
                        .dxDataGrid({
                            showBorders: true,
                            allowColumnResizing: true,
                            paging: false,
                            // filterRow: {
                            //     visible: true,
                            //     applyFilter: "auto"
                            // },
                            scrolling: {
                                mode: "virtual"
                            },
                            columnChooser: {
                                enabled: true,
                                mode: "select" // or "select"
                            },
                            columns: [
                                {
                                caption: 'Module',
                                dataField:"module",
                                cellTemplate:(container, options) => {
                                    //console.log(options.data.module)
                                    var modules = JSON.parse(options.data.module);
                                    console.log(modules)
                                    var html = ``;
                                    html += `<span class='badge badge-primary'>${modules.name}</span>`;
                                    container.append(html)
                                }
                            },
                            {
                                caption: 'TCode',
                                dataField:"tcode",
                                cellTemplate:(container, options) => {
                                    //console.log(options.data.module)
                                    var tcode = JSON.parse(options.data.tcode);
                                    console.log(tcode)
                                    var html = ``;
                                    $.each(tcode, (i) => {
                                        html += `<span class='badge badge-primary'>${tcode[i].description} (${tcode[i].t_code})</span>`;
                                    })
                                   
                                    container.append(html)
                                }
                            },
                            {
                                caption: 'Actions',
                                dataField:"action",
                                cellTemplate:(container, options) => {
                                    //console.log(options.data.module)
                                    var action = JSON.parse(options.data.action);
                                    console.log(action)
                                    var html = ``;
                                    $.each(action, (i) => {
                                        html += `<span class='badge badge-primary'>${action[i].name}</span> `;
                                    })
                                   
                                    container.append(html)
                                }
                            },
                            ],
                            dataSource: new DevExpress.data.DataSource({
                                store: new DevExpress.data.ArrayStore({
                                    key: "request_id",
                                    data: window.subData
                                }),
                                 filter: ["request_id", "=", options.key]
                            })
                        }).appendTo(container);
                }
            }
  });
    
}

       
function stepValidation(step){
            let flag = true
            switch(step) {
                case 1:
                var company = $("#company_name").val();
                var plant = $("#plant_id").val();
                var storage = $("#storage_id").val();
                var business = $("#business_location").val();

                if(company.length == 0) {
                    toastr.error('Company Name is mandatory');
                    flag = false
                }
                break;
                case 2:
                    var role = $("#role").val();
                    // if(role.length == 0) {
                    //     toastr.error('Role must be selected');
                    //     flag = false
                    // }
                break;
                case 3:
                var sales = $("#sales_org").val();
                var purchase = $("#purchase_org").val();
                if(purchase.length == 0 && sales.length == 0) {
                    toastr.error('Either purchase / sales input must be filled to continue');
                    flag = false
                }
                if(purchase.length>0) {
                    var action = $("input[name='action_type']:checked").val();
                    if(!action) {
                        toastr.error('Provide the actions for PO to continue');
                        flag = false
                    }
                    console.log(action)
                    if(action === 'cr1' || action === 'r') {
                        $("#po_release").parent().removeClass('d-none');
                    } else {
                        console.log('donone')
                        $("#po_release").parent().addClass('d-none');
                    }
                }
                populateStep3(sales,purchase);
                break;
                case 4:
                var sales = $("#sales_org").val();
                var purchase = $("#purchase_org").val();
                if(sales.length>0) {
                    var division = $("#division").val();
                    var distribution = $("#distibution_channel").val();
                    var so = $("#sales_office").val();
                    if(division.length == 0 && distribution.length == 0 && so.length == 0) {
                        toastr.error('Either one of all inputs must be filled to continue');
                        flag = false
                    }
                }
                if(purchase.length>0) {

                    var po = $("#po_release").val();
                    var action = $("input[name='action_type']:checked").val();
                    if(action) {
                        if(action == 'cr1' || action == 'r') {
                            if(po.length == 0) {
                                toastr.error('Po Release must be filled');
                                flag = false
                            }
                        }
                    }
                    
                }
                
                loadTcodes();
                break;
                case 5:
                loadReviewSection();
                break;
                case 6:

                break;

            }

            return flag;
            
        }

function loadReviewSection(){

    var data = tree.getSelected();
    var formData = $("#msform").serializeArray();
    var finalArray = [];
    $.each(data, (i) => {
        finalArray[i] = {
            moduleset : data[i].addional
        }
    });
    formData.push({name:'module', value: JSON.stringify(finalArray)});
    console.log(formData)
    reviewCall(formData)
}

/** final submit of the form with tcodes */
$("#finalSubmit1").on('click', (e) => {

e.preventDefault();
//console.log(tree.getSelected());
var data = tree.getSelected();
var formData = $("#msform").serializeArray();
var finalArray = [];
$.each(data, (i) => {
    finalArray[i] = {
        moduleset : data[i].addional
    }
});
formData.push({name:'module', value: JSON.stringify(finalArray)});

Swal.fire({
    title: 'Do you want to submit the request?',
    showDenyButton: true,
    showCancelButton: true,
    confirmButtonText: `Save`,
    denyButtonText: `Don't save`,
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Saved!', '', 'success')
            finalCall(formData);
            return true;
        } else if (result.isDenied) {
            Swal.fire('Changes are not saved', '', 'info')
            return false;
        }
});
        

});

function finalCall(fdata) {
$.ajax({
                url:"{{ route('save.sap.request') }}",
                type:"POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                data:fdata,
                error:(r) => {
                    console.log('error');
                    toastr.error('Something went wrong');
                    console.log(r);
                },
                success: (r) => {

                    if(r.message == 'success') {
                        toastr.success('Your Request has been saved successfully');
                        $("#msform")[0].reset();
                        $("#requestModal").modal('hide');
                        fetch_data();
                        //console.log(r);
                    } else {
                        toastr.error('Something went wrong');
                    }
                    
                }
            })
}

function reviewCall(fdata) {
$.ajax({
                url:"{{ route('review.sap.request') }}",
                type:"GET",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                data:fdata,
                error:(r) => {
                    console.log('error');
                    toastr.error('Something went wrong');
                    console.log(r);
                   
                },
                success: (r) => {
                   console.log('got data');
                   console.log(r);
                   $("#review_selections").html(r.data);
                    
                }
            })
}











function fetchStages(request_id, logs, created_at) {

var url = "{{ route('fetch.stages') }}";
// initial stage
var stages = [0]

$.ajax({
    url: url,
    type: 'GET',
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
    },
    dataType: "json",
    data: {request_id},
    complete: function (result) {
        var res = result.responseJSON;
        var result = res.data;
        stage = result;
        /* Reporting Manager */
        const IS_RM = res.IS_RM;
        /* Module Head */
        const IS_MH = res.IS_MH;
    
        $.each(stage, (i) => {
          stages.push(stage[i]);
        })

        renderApprovalStages(stages, logs, created_at, request_id, IS_RM, IS_MH)

    },
    error: function (e) {
      //console.log(e)
      toastr.error('Something went wrong')
    },
  });

  return true
}
function loadStatusModal(status,created_at, logs, request_id) {

fetchStages(request_id, logs, created_at, status);

}


function renderApprovalStages(stages, logs, created_at, request_id, IS_RM, IS_MH) {
var pointer = 0;
var html = '<section> <div class="row justify-content-center orderstatus-container">  <div class="medium-12 columns">';
//console.log(stages)
$.each(stages, (i) => {

  if(stages[i] == 0) {

    html += `<div class="orderstatus done">
                <div class="orderstatus-check"><span class="orderstatus-number">${i+1}</span></div>
                <div class="orderstatus-text">
                  <time>${created_at}</time>
                  <p>Your Request was placed</p>
                </div>
              </div>`;

  } else {

    let approval_stages = {!!  json_encode($approval_stages) !!}

    let datetime = "N/A";

    let addClass = "";

    let status_text = "Not Approved";

    pointer = i - 1;

    if(logs[i-1] !== undefined) {

      //console.log('log found')
      if(stages[i] == logs[pointer].approval_stage) {

        //console.log('log found approval stage')
          addClass = `done`;

          datetime = logs[pointer].created_at;

          $.each(approval_stages, (x) => {
            if(approval_stages[x] !== undefined) {
              if(logs[pointer].approval_stage == approval_stages[x].id) {
                console.log(logs[pointer])
                if(logs[pointer].status == 1) {
                  status_text = `Approved By <br> ${logs[pointer].created_by} (${approval_stages[x].approval_type})`;
                } else {
                  status_text = `Rejected By <br> ${logs[pointer].created_by} (${approval_stages[x].approval_type})`;
                }

                status_text += `<br>Remarks: `+logs[pointer].remarks;
                
              }
            }
          });
      } 

      } 
      else {

              if(stages[i] == approval_stages[stages[i] - 1].id) {

                status_text = `Not Approved By <br> (${approval_stages[stages[i] - 1].approval_type})`;          
                 
              }

    }

    html += `<div class="orderstatus ${addClass}">
                  <div class="orderstatus-check"><span class="orderstatus-number">${i+1}</span></div>
                  <div class="orderstatus-text">
                    <time>${datetime}</time>
                    <p>${status_text}</p>
                  </div>
                </div>`;

  }

});

  html += "</div></div></section>";


  $("#statusModal").modal('show');
  $("#drop_status").html(html)


}



       
    
    </script>
@stop