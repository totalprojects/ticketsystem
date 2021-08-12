@extends('adminlte::page')

@section('title', 'SAP Request')

@section('content_header')
<meta http-equiv="x-ua-compatible" content="ie=edge">
<meta name="csrf-token" content="{{ csrf_token() }}">
  
@stop

@section('content')
<style>
    #heading {
    text-transform: uppercase;
    color: #25396f;
    font-weight: normal
}

#msform {
    text-align: center;
    position: relative;
    margin-top: 10px;
}

#msform fieldset {
    background: white;
    border: 0 none;
    border-radius: 0.5rem;
    box-sizing: border-box;
    width: 100%;
    margin: 0;
    padding-bottom: 20px;
    position: relative
}

.form-card {
    text-align: left
}

#msform fieldset:not(:first-of-type) {
    display: none
}

#msform textarea {
    padding: 8px 15px 8px 15px;
    border: 1px solid #ccc;
    border-radius: 0px;
    margin-bottom: 25px;
    margin-top: 2px;
    width: 100%;
    box-sizing: border-box;
    color: #2C3E50;
    background-color: #ECEFF1;
    font-size: 16px;
    letter-spacing: 1px
}

#msform input:focus,
#msform textarea:focus {
    -moz-box-shadow: none !important;
    -webkit-box-shadow: none !important;
    box-shadow: none !important;
    border: 1px solid #25396f;
    outline-width: 0
}

#msform .action-button {
    width: 100px;
    background: #25396f;
    font-weight: bold;
    color: white;
    border: 0 none;
    border-radius: 0px;
    cursor: pointer;
    padding: 10px 5px;
    margin: 10px 0px 10px 5px;
    float: right
}

#msform .action-button:hover,
#msform .action-button:focus {
    background-color: #311B92
}

#msform .action-button-previous {
    width: 100px;
    background: #616161;
    font-weight: bold;
    color: white;
    border: 0 none;
    border-radius: 0px;
    cursor: pointer;
    padding: 10px 5px;
    margin: 10px 5px 10px 0px;
    float: right
}

#msform .action-button-previous:hover,
#msform .action-button-previous:focus {
    background-color: #000000
}

.card {
    z-index: 0;
    border: none;
    position: relative
}

.fs-title {
    font-size: 25px;
    color: #25396f;
    margin-bottom: 15px;
    font-weight: normal;
    text-align: left
}

.purple-text {
    color: #25396f;
    font-weight: normal
}

.steps {
    font-size: 25px;
    color: gray;
    margin-bottom: 10px;
    font-weight: normal;
    text-align: right
}

.fieldlabels {
    color: gray;
    text-align: left
}

#progressbar {
    margin-bottom: 30px;
    overflow: hidden;
    color: lightgrey
}

#progressbar .active {
    color: #25396f
}

#progressbar li {
    list-style-type: none;
    font-size: 15px;
    width: 25%;
    float: left;
    position: relative;
    font-weight: 400
}

#progressbar #account:before {
    font-family: FontAwesome;
    content: "\f13e"
}

#progressbar #personal:before {
    font-family: FontAwesome;
    content: "\f007"
}

#progressbar #payment:before {
    font-family: FontAwesome;
    content: "\f030"
}

#progressbar #confirm:before {
    font-family: FontAwesome;
    content: "\f00c"
}

#progressbar li:before {
    width: 50px;
    height: 50px;
    line-height: 45px;
    display: block;
    font-size: 20px;
    color: #ffffff;
    background: lightgray;
    border-radius: 50%;
    margin: 0 auto 10px auto;
    padding: 2px
}

#progressbar li:after {
    content: '';
    width: 100%;
    height: 2px;
    background: lightgray;
    position: absolute;
    left: 0;
    top: 25px;
    z-index: -1
}

#progressbar li.active:before,
#progressbar li.active:after {
    background: #25396f
}

.progress {
    height: 20px
}

.progress-bar {
    background-color: #25396f
}

.fit-image {
    width: 100%;
    object-fit: cover
}
</style>
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
                {{-- <form method="post" id="sap_request_frm">
                    <div class="row">
                        <div class="col-lg-4 pt-2">
                            <label for="company_name">Company Name</label>
                                <select name="company_name[]" id="company_name" placeholder="Select Companies" class="form-control select2bs4" multiple>
                                    <option value=""></option>
                                    @foreach($companies as $company)
                                        <option value="{{ $company->company_code }}">{{ $company->company_name }} ({{ $company->company_code }})</option>
                                    @endforeach
                                </select>
                           
                        </div>
                        <div class="col-lg-4 pt-2">
                            <label for="plant_name">Plant Name </label>
                                <select name="plant_name[]" id="plant_id" data-placeholder="Select Plant Name" class="form-control select2bs4" multiple>
                                    <option value=""></option>
                                </select>
                        </div>
                        <div class="col-lg-4 pt-2">
                            <label for="storage_location">Storage Location </label>
                                <select name="storage_location[]"  id="storage_id" data-placeholder="Select Storage Location" class="form-control select2bs4" multiple>
                                    <option value=""></option>
                                </select>
                        </div>
                        <div class="col-lg-4 pt-2">
                            <label for="sales_org">Sales Org </label>
                                <select name="sales_org[]"  id="sales_org" data-placeholder="Select Sales Organization" class="form-control select2bs4" multiple>
                                    <option value=""></option>
                                </select>
                        </div>
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
                            <label for="business_area"> Business Area </label>
                                <select name="business_area[]"  id="business_location" data-placeholder="Select Business Area" class="form-control select2bs4" multiple>
                                    <option value=""></option>
                                    @foreach($business as $b)
                                        <option value="{{ $b->business_code }}">{{ $b->business_name }} </option>
                                    @endforeach
                                </select>
                        </div>
                        <div class="col-lg-4 pt-2">
                            <label for="purchase_org"> Purchase Org </label>
                                <select name="purchase_org[]"  id="purchase_org" data-placeholder="Select Purchase Organization" class="form-control select2bs4" multiple>
                                    <option value=""></option>
                                    @foreach($po as $p)
                                        <option value="{{ $p->po_code }}">{{ $p->po_name }} </option>
                                    @endforeach
                                </select>
                        </div>
                        <div class="col-lg-4 pt-2">
                            <label for="po_release"> PO Release </label>
                                <select name="po_release[]"  id="po_release" data-placeholder="Select PO Release" class="form-control select2bs4" multiple>
                                    <option value=""></option>
                                    @foreach($po_release as $p)
                                        <option value="{{ $p->rel_code }}">{{ $p->rel_description }} </option>
                                    @endforeach
                                </select>
                        </div>

                        <div class="col-lg-12 pt-2 t_code_section">

                        </div>
                    </div>
                </form> --}}
               
                    <div class="row">
                        <div class="col-11 col-md-12 col-lg-12 col-xl-12 text-center p-2 mb-2">
                            <div class="card p-2 pt-4 pb-0 mt-3 mb-3">
                                <h2 id="heading">SAP Request Form</h2>
                                <p>Fill all form field to go to next step</p>
                                <form id="msform" method="post">
                                    <!-- progressbar -->
                                    <ul id="progressbar">
                                        <li class="active" id="account"><strong>Step 1</strong></li>
                                        <li id="personal"><strong>Step 2</strong></li>
                                        <li id="payment"><strong>Step 3</strong></li>
                                        <li id="confirm"><strong>Step 4</strong></li>
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
                                                    <h2 class="steps">Step 1 - 4</h2>
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
                                   
                                    <fieldset>
                                        <div class="form-card">
                                            <div class="row">
                                                <div class="col-7">
                                                    <h2 class="fs-title">Select Type:</h2>
                                                </div>
                                                <div class="col-5">
                                                    <h2 class="steps">Step 2 - 4</h2>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <h5>Select any one option or both to continue</h5>
                                                </div>
                                                <div class="col-lg-6 pt-2">
                                                    <label for="sales_org">Sales Organization </label>
                                                        <select name="sales_org[]"  id="sales_org" data-placeholder="Select Sales Organization" class="form-control select2bs4" multiple>
                                                            <option value=""></option>
                                                        </select>
                                                </div>
                                                <div class="col-lg-6 pt-2">
                                                    <label for="purchase_org"> Purchase Organization </label>
                                                        <select name="purchase_org[]"  id="purchase_org" data-placeholder="Select Purchase Organization" class="form-control select2bs4" multiple>
                                                            <option value=""></option>
                                                            @foreach($po as $p)
                                                                <option value="{{ $p->id }}">{{ $p->po_name }} ({{ $p->po_code }}) </option>
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
                                                    <h2 class="fs-title">Other Information:</h2>
                                                </div>
                                                <div class="col-5">
                                                    <h2 class="steps">Step 3 - 4</h2>
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
                                                    <h2 class="steps">Step 4 - 4</h2>
                                                </div>
                                               
                                            </div> 
                                            <h2 class="purple-text text-center"><strong>Final Step</strong></h2> <br>
                                            <div class="row">
                                                <div class="col-lg-12" id="modules_tcodes_block">
                                                    <div id="div_tree"></div>
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
    @stop

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/custom/main.css') }}">
@stop

@section('js')

    <script>
        var tree;

        function pickerTreeRender(data) {
            tree = new PickleTree({
                    switchCallback: (node) => {
                        console.log(node)
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
        }


        loadTcodes();
        function loadTcodes(dependencies = []){
            $.ajax({
                url:"{{ route('tcodes.for.user') }}",
                type:"GET",
                data:null,
                error:(r) => {
                    toastr.error('Something went wrong');
                    console.log(r);
                },
                success:(r) => {
                    if(r) {
                        toastr.success('Tcodes found');
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
                $("#po_release").parent().removeClass('d-none');
            } else {
                $("#po_release").parent().addClass('d-none');
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
            if(current == 4) {
                return false;
            }
            /** Data validation for all steps */
            if(!stepValidation(current)) {
                return false;
            }

           

            //Add Class Active
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
           enabled: true,
           pageSize: 10
       },
       columnChooser: {
           enabled: true,
           mode: "select" // or "dragAndDrop"
       },
       headerFilter: {
           //visible: true
       },
       scrolling: {
           scrollByContent: true,
       },
       sorting: {
            mode: "none"
        },
       wordWrapEnabled: true,
       columns: [
           {
            caption:"Req No.",
            dataField:"sl_no",
            width:75
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
                            {
                                caption: 'Status',
                                dataField:"status",
                                cellTemplate:(container, options) => {
                                    //console.log(options.data.module)
                                    var status = JSON.parse(options.data.status);
                                    console.log(status)
                                    var html = ``;
                                    if(status ==0) {
                                        html = `<span class='badge badge-warning'>Not Approved</span>`;
                                    } else if(status == 1) {
                                        html = `<span class='badge badge-success'>Approved</span>`;
                                    } else {
                                        html = `<span class='badge badge-danger'>Rejected</span>`;
                                    }
                                       
                                    
                                   
                                    container.append(html)
                                }
                            },
                            ],
                            dataSource: new DevExpress.data.DataSource({
                                store: new DevExpress.data.ArrayStore({
                                    key: "id",
                                    data: window.subData
                                }),
                                 filter: ["id", "=", options.key]
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
                var sales = $("#sales_org").val();
                var purchase = $("#purchase_org").val();
                if(purchase.length == 0 && sales.length == 0) {
                    toastr.error('Either purchase / sales input must be filled to continue');
                    flag = false
                }
                populateStep3(sales,purchase);
                break;
                case 3:
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
            
                    if(po.length == 0) {
                        toastr.error('Po Release must be filled');
                        flag = false
                    }
                }
                break;
                case 4:
                loadTcodes();
                break;

            }

            return flag;
            
        }

/** final submit of the form with tcodes */
$("#finalSubmit").on('click', (e) => {
    e.preventDefault();
    console.log(tree.getSelected());
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

    $.ajax({
        url:"{{ route('save.sap.request') }}",
        type:"POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
        data:formData,
        error:(r) => {
            console.log('error');
            console.log(r);
        },
        success: (r) => {
            toastr.success('sent');
            console.log(r);
        }
    })
});



       
    
    </script>
@stop