@extends('adminlte::page')

@section('title', 'SAP Request')

@section('content_header')
<meta http-equiv="x-ua-compatible" content="ie=edge">
<meta name="csrf-token" content="{{ csrf_token() }}">
  
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
                <form method="post" id="sap_request_frm">
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
                </form>
            </div>
            {{-- <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary">Save changes</button>
            </div> --}}
          </div>
        </div>
      </div>
    @stop

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/custom/main.css') }}">
@stop

@section('js')

    <script>
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
                    html2 += `<option value='${so[i].so_code}'> ${so[i].so_description} </option>`;
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
                    html += `<option value='${storages[i].storage_code}'> ${storages[i].storage_description} (${storages[i].storage_code}) </option>`;
                });
                $("#storage_id").html(html);
                $(".loading").addClass('d-none');
            }
        })
      });

      $("#storage_id").on('change', () => {

          console.log('change')
         
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
                        html += `<option value='${storages[i].sales_office_code}'> ${storages[i].sales_office.sales_office_name} (${storages[i].sales_office_code}) </option>`;
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
    var jsonData = [];
//    var jsonData = new DevExpress.data.CustomStore({
//        key: "id",
//        load: function (loadOptions) {
//            // console.log(loadOptions)
//            var deferred = $.Deferred(),
//                args = {};
//            [
//                "skip",
//                "take",
//                "requireTotalCount",
//                "sort",
//                "filter",
//            ].forEach(function (i) {
//                if (i in loadOptions && isNotEmpty(loadOptions[i]))
//                    args[i] = JSON.stringify(loadOptions[i]);
//            })

//            let take = loadOptions.take
//            let skip = loadOptions.skip
//            var dataSet = []
//            //var url = "{{ route('get.menu.list') }}"
//         //    $.ajax({
//         //        url: url,
//         //        type: 'GET',
//         //        headers: {
//         //            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
//         //        },
//         //        dataType: "json",
//         //        data: '&take=' + take + '&skip=' + skip,
//         //        complete: function (result) {
//         //            var res = result.responseJSON;
//         //            var data = res.data;
//         //            console.log(res)
//         //            deferred.resolve(data, {
//         //                totalCount: res.totalCount,
//         //            });
                   
//         //        },
//         //        error: function () {
//         //            deferred.reject("Data Loading Error");
//         //        },
//         //        //timeout: 2000000
//         //    });
//            return deferred.promise();
//        }
 //  });
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
       columns: [],
   });
    
}
    
    </script>
@stop