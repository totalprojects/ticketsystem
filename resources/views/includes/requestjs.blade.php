@section('js')
<script>
    
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

function loadTcodes(dependencies = []){
    pickerTreeRender([]);
    $("#modules_tcodes_block").append("<h3 class='ldr_tc badge badge-warning p-1 m-1'><i class='fas fa-spinner fa-spin'></i> Loading...</h3>")
    
    var role_id = $("#role").val();
    var tcode = $("#ctcode").val();
    $.ajax({
        url:"{{ route('tcodes.for.user') }}",
        type:"GET",
        data:{role_id:role_id,tcode:tcode},
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
                        //   $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

                // show the next fieldset
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
        var data = tree.getSelected();
        if(data == '') {
            toastr.error('You must select atleast one tcode to continue')
            return false
        }
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
    console.log('data')
    console.log(data)
   
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
    
    customLoader(1);

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
                    customLoader(0);
                },
                success: (r) => {
                    console.log(r)
                    if(r.message == 'success') {
                        toastr.success('Your Request has been saved successfully');
                        customLoader(0);
                        $("#msform")[0].reset();
                        $("#requestModal").modal('hide');
                        fetch_data();
                    } else {
                        customLoader(0);
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

    setTimeout(function(){ scrollTable() }, 2000);
}

/** Ajax calls */
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

$("#purchase_org").on('change', (e) => {
    var value = $("#purchase_org").val();

    if(value.length>0) {
        $("input[name='action_type']").prop('disabled',false);
    } else {
        $("input[name='action_type']").prop('disabled',true);
    }
});
</script>
@stop