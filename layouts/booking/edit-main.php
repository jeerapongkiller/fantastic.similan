<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
    <meta name="description" content="<?php echo $main_description; ?>">
    <meta name="keywords" content="<?php echo $main_keywords; ?>">
    <meta name="author" content="<?php echo $main_author; ?>">
    <title>Booking -
        <?php echo $main_title; ?>
    </title>
    <link rel="apple-touch-icon" href="app-assets/images/ico/favicon.png">
    <link rel="shortcut icon" type="image/x-icon" href="app-assets/images/ico/favicon.png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="app-assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="app-assets/vendors/css/forms/wizard/bs-stepper.min.css">
    <link rel="stylesheet" type="text/css" href="app-assets/vendors/css/forms/select/select2.min.css">
    <link rel="stylesheet" type="text/css" href="app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="app-assets/vendors/css/forms/spinner/jquery.bootstrap-touchspin.css">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Sweetalert2 CSS-->
    <link rel="stylesheet" type="text/css" href="app-assets/vendors/css/extensions/sweetalert2.min.css">

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="app-assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="app-assets/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="app-assets/css/colors.css">
    <link rel="stylesheet" type="text/css" href="app-assets/css/components.css">
    <link rel="stylesheet" type="text/css" href="app-assets/css/themes/dark-layout.css">
    <link rel="stylesheet" type="text/css" href="app-assets/css/themes/bordered-layout.css">
    <link rel="stylesheet" type="text/css" href="app-assets/css/themes/semi-dark-layout.css">

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="app-assets/css/core/menu/menu-types/vertical-menu.css">
    <link rel="stylesheet" type="text/css" href="app-assets/css/plugins/forms/form-validation.css">
    <link rel="stylesheet" type="text/css" href="app-assets/css/plugins/forms/form-wizard.css">
    <link rel="stylesheet" type="text/css" href="app-assets/css/plugins/forms/pickers/form-flat-pickr.css">
    <link rel="stylesheet" type="text/css" href="app-assets/fonts/fontawesome/css/all.css" rel="stylesheet">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <!-- END: Custom CSS-->
</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern navbar-floating footer-static menu-collapsed pace-done" data-open="click" data-menu="vertical-menu-modern" data-col="">

    <!-- BEGIN: Header-->
    <?php include 'layouts/header.php'; ?>
    <!-- END: Header-->

    <!-- BEGIN: Main Menu-->
    <?php include 'layouts/main-menu.php'; ?>
    <!-- END: Main Menu-->

    <!-- BEGIN: Content-->
    <?php include 'pages/' . $_GET['pages'] . '.php'; ?>
    <!-- END: Content-->

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <!-- BEGIN: Footer-->
    <?php include 'layouts/footer.php'; ?>
    <!-- END: Footer-->

    <!-- BEGIN: Vendor JS-->
    <script src="app-assets/vendors/js/vendors.min.js"></script>
    <!-- BEGIN Vendor JS-->

    <?php if ($_GET['action'] == "details") { ?>
        <script src="app-assets/vendors/js/forms/wizard/booking/bs-stepper-details.min.js"></script>
    <?php } else { ?>
        <script src="app-assets/vendors/js/forms/wizard/bs-stepper.min.js"></script>
    <?php } ?>

    <!-- BEGIN: Page Vendor JS-->
    <!-- <script src="app-assets/vendors/js/forms/wizard/bs-stepper.min.js"></script> -->
    <script src="app-assets/vendors/js/forms/select/select2.full.min.js"></script>
    <script src="app-assets/vendors/js/forms/validation/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
    <script src="app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js"></script>
    <script src="app-assets/vendors/js/forms/repeater/jquery.repeater.min.js"></script>
    <script src="app-assets/vendors/js/forms/spinner/jquery.bootstrap-touchspin.js"></script>
    <script src="app-assets/vendors/js/forms/cleave/cleave.min.js"></script>
    <script src="app-assets/vendors/js/forms/cleave/addons/cleave-phone.us.js"></script>
    <script src="app-assets/fonts/fontawesome/js/all.js"></script>
    <script src="app-assets/js/scripts/node_modules/dom-to-image/src/dom-to-image.js"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN Sweetalert2 JS -->
    <script src="app-assets/vendors/js/extensions/sweetalert2.all.min.js"></script>
    <script src="app-assets/vendors/js/extensions/polyfill.min.js"></script>
    <!-- END Sweetalert2 JS -->

    <!-- BEGIN: Theme JS-->
    <script src="app-assets/js/core/app-menu.js"></script>
    <script src="app-assets/js/core/app.js"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <script type="text/javascript">
        // Ajax Delete Booking
        // --------------------------------------------------------------------
        function deleteBooking(bo_id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                customClass: {
                    confirmButton: 'btn btn-primary',
                    cancelButton: 'btn btn-outline-danger ml-1'
                },
                buttonsStyling: false
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        url: "pages/booking/function/delete.php",
                        type: "POST",
                        data: {
                            bo_id: bo_id,
                            action: 'delete'
                        },
                        success: function(response) {
                            if (response != false) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: 'Your item has been deleted.',
                                    customClass: {
                                        confirmButton: 'btn btn-success'
                                    }
                                }).then(function() {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Please try again.',
                                    text: 'Failed to delete data!',
                                    customClass: {
                                        confirmButton: 'btn btn-success'
                                    }
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Please try again.',
                                text: 'Failed to delete data!',
                                customClass: {
                                    confirmButton: 'btn btn-success'
                                }
                            });
                        }
                    });
                }
            });
        }

        $(document).ready(function() {
            var jqFormBooking = $('#booking-edit-form'),
                jqFormInv = $('#invoice-form'),
                picker = $('#dob'),
                dtPicker = $('#dob-bootstrap-val'),
                timePickr = $('.flatpickr-time'),
                verticalWizard = document.querySelector('.vertical-wizard-example'),
                horizontalWizard = document.querySelector('.horizontal-wizard-example'),
                pageBlockSpinner = $('.btn-page-block-spinner'),
                select = $('.select2');

            if (pageBlockSpinner.length) {
                pageBlockSpinner.on('click', function() {
                    $.blockUI({
                        message: '<div class="spinner-grow spinner-grow-sm text-white" role="status"></div>',
                        timeout: 1000,
                        css: {
                            backgroundColor: 'transparent',
                            border: '0'
                        },
                        overlayCSS: {
                            opacity: 0.5
                        }
                    });
                });
            }

            //Numeral
            $('.numeral-mask').toArray().forEach(function(field) {
                new Cleave(field, {
                    numeral: true,
                    numeralThousandsGroupStyle: 'thousand'
                });
            });

            $('.date-flatpickr').flatpickr({
                onReady: function(selectedDates, dateStr, instance) {
                    if (instance.isMobile) {
                        $(instance.mobileInput).attr('step', null);
                    }
                },
                static: true,
                altInput: true,
                altFormat: 'j F Y',
                dateFormat: 'Y-m-d'
            });

            // Start time
            if (timePickr.length) {
                timePickr.flatpickr({
                    enableTime: true,
                    noCalendar: true,
                    time_24hr: true,
                    static: true
                    // defaultDate: "07:00"
                });
            }

            // select2
            select.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>');
                $this
                    .select2({
                        placeholder: 'Select ...',
                        dropdownParent: $this.parent()
                    })
                    .change(function() {
                        $(this).valid();
                    });
            });

            // Default Spin
            $('.touchspin').TouchSpin({
                buttondown_class: 'btn btn-primary',
                buttonup_class: 'btn btn-primary',
                buttondown_txt: feather.icons['minus'].toSvg(),
                buttonup_txt: feather.icons['plus'].toSvg()
            });

            // Time
            $('.time-mask').toArray().forEach(function(field) {
                new Cleave(field, {
                    time: true,
                    timePattern: ['h', 'm']
                });
            });

            // Picker
            if (picker.length) {
                picker.flatpickr({
                    onReady: function(selectedDates, dateStr, instance) {
                        if (instance.isMobile) {
                            $(instance.mobileInput).attr('step', null);
                        }
                    }
                });
            }

            if (dtPicker.length) {
                dtPicker.flatpickr({
                    onReady: function(selectedDates, dateStr, instance) {
                        if (instance.isMobile) {
                            $(instance.mobileInput).attr('step', null);
                        }
                    }
                });
            }

            // form repeater jquery
            $('.extra-charge-repeater').repeater({
                show: function() {
                    $(this).slideDown();

                    $(this).find('[data-extra-repeater="select2"]').select2();

                    // $(this).find('[data-extra-repeater="datepicker"]').flatpickr();

                    // new Tagify(this.querySelector('[data-extra-repeater="tagify"]'));

                    $('.numeral-mask').toArray().forEach(function(field) {
                        new Cleave(field, {
                            numeral: true,
                            numeralThousandsGroupStyle: 'thousand'
                        });
                    });

                    // Feather Icons
                    if (feather) {
                        feather.replace({
                            width: 14,
                            height: 14
                        });
                    }
                },
                hide: function(deleteElement) {
                    $(this).slideUp(deleteElement);
                },
                ready: function() {
                    // Init select2
                    $('[data-extra-repeater="select2"]').select2();

                    // Init flatpickr
                    // $('[data-extra-repeater="datepicker"]').flatpickr();

                    // Init Tagify
                    // new Tagify(document.querySelector('[data-extra-repeater="tagify"]'));

                    check_start_extra();
                },
                isFirstItemUndeletable: false
            });

            // Horizontal Wizard
            // --------------------------------------------------------------------
            if (typeof horizontalWizard !== undefined && horizontalWizard !== null) {
                var horizontalStepper = new Stepper(horizontalWizard, {
                    linear: false
                });
                $(horizontalWizard)
                    .find('.btn-next')
                    .on('click', function() {
                        horizontalStepper.next();
                    });
                $(horizontalWizard)
                    .find('.btn-prev')
                    .on('click', function() {
                        horizontalStepper.previous();
                    });

                $(horizontalWizard)
                // .find('.btn-submit')
                // .on('click', function() {
                //     alert('Submitted..!!');
                // });
            }

            if (jqFormBooking.length) {
                $.validator.addMethod("regex", function(value, element, regexp) {
                    return this.optional(element) || regexp.test(value);
                }, "Please check your input.");

                jqFormBooking.validate({
                    rules: {
                        'product_id': {
                            required: true
                        },
                        'category_id[]': {
                            required: true
                        }
                    },
                    messages: {
                        // 'pax_group': {
                        //     regex: "This tat license is already taken! Try another."
                        // }
                    },
                    submitHandler: function(form) {
                        // update ajax request data
                        var formData = new FormData(form);
                        formData.append('action', 'edit');
                        $.ajax({
                            url: "pages/booking/function/edit.php",
                            type: "POST",
                            processData: false,
                            contentType: false,
                            data: formData,
                            success: function(response) {
                                // console.log(response);
                                if (response != false && response > 0) {
                                    Swal.fire({
                                        title: "The information has been updated.",
                                        icon: "success",
                                    }).then(function(isConfirm) {
                                        if (isConfirm) {
                                            location.reload(); // refresh page
                                        }
                                    })
                                } else {
                                    Swal.fire({
                                        title: "Please try again.",
                                        icon: "error",
                                    });
                                }
                            }
                        });
                    }
                });
            }

            check_date();
            // check_transfer_type();
        });

        function numberWithCommas(x) {
            return x.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
        }

        // Script Function Booking
        // ------------------------------------------------------------------------------------
        function download_image(type) {
            switch (type) {
                case 'voucher':
                    var img_name = document.getElementById('booking_full').value;
                    var node = document.getElementById('div-inc-print');
                    break;
            }
            domtoimage.toJpeg(node, {
                    quality: 0.95
                })
                .then(function(dataUrl) {
                    var link = document.createElement('a');
                    link.download = img_name + '.jpg';
                    link.href = dataUrl;
                    link.click();
                });
        }

        function check_hotel(type) {
            var hotel = document.getElementById('hotel_' + type);
            document.getElementById('hotel_' + type + '_outside').disabled = hotel.value > 0 ? true : false;
            if (type == 'pickup') {
                var zone = $('#hotel_pickup').find(':selected').attr('data-zone');
                document.getElementById('pickup').value = zone;
                $("#pickup").val(zone).trigger("change");
            }

            if (type == 'dropoff') {
                var zone = $('#hotel_dropoff').find(':selected').attr('data-zone');
                document.getElementById('dropoff').value = zone;
                $("#dropoff").val(zone).trigger("change");
            }
        }

        // Script Function Program
        // ------------------------------------------------------------------------------------
        function check_date() {
            var bp_id = document.getElementById('bp_id').value;
            var travel_date = document.getElementById('travel_date').value;
            var book_type = document.getElementById('booktype1').checked == true ? 1 : 2;
            // document.getElementsByClassName('td-x')[0].hidden = (book_type == 1) ? false : true;
            // document.getElementsByClassName('td-x')[1].hidden = (book_type == 1) ? false : true;
            // document.getElementsByClassName('td-x')[2].hidden = (book_type == 1) ? false : true;
            // document.getElementById('td-adult').hidden = (book_type == 1) ? false : true;
            // document.getElementById('td-child').hidden = (book_type == 1) ? false : true;
            // document.getElementById('td-infant').hidden = (book_type == 1) ? false : true;
            // if (open_rates == 1) {
            //     document.getElementById('rate_total').readOnly = (book_type == 1) ? true : false;
            // } else {
            //     document.getElementById('div-total').hidden = true;
            // }

            if ($('#travel_date').length) {
                $('#travel_date').flatpickr({
                    onReady: function(selectedDates, dateStr, instance) {
                        if (instance.isMobile) {
                            $(instance.mobileInput).attr('step', null);
                        }
                    },
                    static: true,
                    altInput: true,
                    altFormat: 'j F Y',
                    dateFormat: 'Y-m-d',
                    defaultDate: bp_id > 0 ? travel_date : 'today'
                });
            }

            if ($('#paid_date').length) {
                $('#paid_date').flatpickr({
                    onReady: function(selectedDates, dateStr, instance) {
                        if (instance.isMobile) {
                            $(instance.mobileInput).attr('step', null);
                        }
                    },
                    static: true,
                    altInput: true,
                    altFormat: 'j F Y',
                    dateFormat: 'Y-m-d',
                    defaultDate: 'today'
                });
            }

            search_program();
        }

        function search_program() {
            var agent = document.getElementById('agent').value;
            document.getElementById('frm-agent').hidden = agent !== 'outside' ? false : true;
            document.getElementById('frm-agent-outside').hidden = agent !== 'outside' ? true : false;
            var product_id = document.getElementById('product_id').value;
            var cate_id = document.getElementById('cate_id').value;
            var formData = new FormData();
            formData.append('action', 'search');
            formData.append('prod_id', product_id);
            $.ajax({
                url: "pages/booking/function/search-category.php",
                type: "POST",
                processData: false,
                contentType: false,
                data: formData,
                success: function(response) {
                    if (response != '' && response != false) {
                        $('#category_id').find('option').remove();
                        var res = $.parseJSON(response);
                        var countcate = Object.keys(res.id).length;
                        if (countcate) {
                            for (let index = 0; index < countcate; index++) {
                                var check_selected = (cate_id.includes(res.id[index]) == true) ? "selected" : "";
                                $('#category_id').append("<option value=\"" + res.id[index] + "\" data-name=\"" + res.name[index] + "\" data-transfer=\"" + res.transfer[index] + "\" " + check_selected + ">" + res.name[index] + "</option>");
                            }
                        } else {
                            $('#category_id').append("<option value=\"0\"></option>");
                        }

                        check_category();
                    }
                }
            });
        }

        function check_category() {
            var bp_id = document.getElementById('bp_id').value;
            var json_bpr = document.getElementById('json_bpr').value;
            var book_type = document.getElementById('booktype1').checked == true ? 1 : 2;
            var agent = document.getElementById('agent').value;
            var travel_date = document.getElementById('travel_date').value;
            const selectCategory = document.getElementById('category_id');
            const selectedValues = [];
            for (const option of selectCategory.options) {
                if (option.selected) {
                    selectedValues.push({
                        id: option.value,
                        name: option.dataset.name,
                        transfer: option.dataset.transfer
                    });
                }
            }
            var formData = new FormData();
            formData.append('action', 'search');
            formData.append('agent_id', (agent !== 'outside') ? agent : 0);
            formData.append('categorys', JSON.stringify(selectedValues));
            formData.append('json_bpr', json_bpr);
            formData.append('travel_date', travel_date);
            formData.append('book_type', book_type);
            $.ajax({
                url: "pages/booking/function/search-rate.php",
                type: "POST",
                processData: false,
                contentType: false,
                data: formData,
                success: function(response) {
                    $('#div-rates').html(response);

                    $('.numeral-mask').toArray().forEach(function(field) {
                        new Cleave(field, {
                            numeral: true,
                            numeralThousandsGroupStyle: 'thousand'
                        });
                    });

                    check_transfer();
                    check_rate();
                }
            });
        }

        function check_transfer() {
            var pickup_type = document.getElementsByName('pickup_type');
            if (pickup_type[0].checked == true) {
                document.getElementById('div-transfer').hidden = false;
                var pickup = document.getElementsByClassName('div-transfer-pickup');
                for (let index = 0; index < pickup.length; index++) {
                    pickup[index].hidden = false;
                }
            } else if (pickup_type[1].checked == true) {
                document.getElementById('div-transfer').hidden = true;
            } else if (pickup_type[2].checked == true) {
                document.getElementById('div-transfer').hidden = false;
                var pickup = document.getElementsByClassName('div-transfer-pickup');
                for (let index = 0; index < pickup.length; index++) {
                    pickup[index].hidden = true;
                }
            }
        }

        function check_date_agent() {
            var booking_type = document.getElementById('booktype1').checked == true ? 1 : 2;
            var bp_id = $('#bp_id').val();
            var product_id = bp_id > 0 ? $('#product_id').val() : 0;
            var formData = new FormData();
            formData.append('action', 'check');
            formData.append('bp_id', bp_id);
            formData.append('product_id', product_id);
            formData.append('booking_type', booking_type);
            formData.append('agent_id', $("#agent").val());
            formData.append('travel_date', $("#travel_date").val());
            $.ajax({
                url: "pages/booking/function/check-date.php",
                type: "POST",
                processData: false,
                contentType: false,
                data: formData,
                success: function(response) {
                    // $('#div-show').html(response);
                    $('#product_id').find('option').remove();
                    $('#div-data-category').html('');
                    if (response != '' && response != false) {
                        var check_id = [];
                        var res = $.parseJSON(response);
                        var count = Object.keys(res.prod_id).length
                        for (let index = 0; index <= count; index++) {
                            if (res.prod_id[index] != undefined && res.prod_id[index] > 0 && check_id.includes(res.prod_id[index]) == false) {
                                check_id.push(res.prod_id[index]);
                                $('#product_id').append("<option value=\"" + res.prod_id[index] + "\" data-bpr=\"" + res.bpr_id[index] + "\" data-seat=\"" + res.over_seat[index] + "\" data-name=\"" + res.prod_name[index] + "\">" + res.prod_name[index] + "</option>");
                            }
                            if (res.proca_id[index] != undefined && res.proca_id[index] > 0) {
                                $('#div-data-category').append("<input type=\"hidden\" id=\"data_category\" name=\"data_category" + res.prod_id[index] + "[]\" data-name=\"" + res.proca_name[index] + "\" data-period=\"" + res.prop_id[index] + "\" value=\"" + res.proca_id[index] + "\">");
                            }
                        }
                    } else {
                        $('#product_id').append("<option value=\"0\" data-bpr=\"0\" data-seat=\"0\" data-name=\"0\"></option>");
                    }

                    check_product();
                }
            });
        }

        function check_rate() {
            var total_product = 0;
            var book_type = document.getElementById('booktype1').checked == true ? 1 : 2;
            var rate_total = document.getElementById('rate_total');
            var adult = document.getElementsByName('adult[]');
            var child = document.getElementsByName('child[]');
            var infant = document.getElementsByName('infant[]');
            var foc = document.getElementsByName('foc[]');
            // var bt_adult = 0;
            // var bt_child = 0;
            // var bt_infant = 0;
            // var bt_foc = 0;

            var rates_adult = document.getElementsByName('rates_adult[]');
            var rates_child = document.getElementsByName('rates_child[]');
            var rates_infant = document.getElementsByName('rates_infant[]');
            for (let index = 0; index < adult.length; index++) {
                // bt_adult += Number(adult[index].value);
                // bt_child += Number(child[index].value);
                // bt_infant += Number(infant[index].value);
                // bt_foc += Number(foc[index].value);
                if (book_type == 1) {
                    total_product += Number(rates_adult[index].value.replace(/,/g, '')) * Number(adult[index].value);
                    total_product += Number(rates_child[index].value.replace(/,/g, '')) * Number(child[index].value);
                    total_product += Number(rates_infant[index].value.replace(/,/g, '')) * Number(infant[index].value);
                }
            }
            if (book_type == 2) {
                var rates_private = document.getElementsByName('rates_private[]');
                for (let index = 0; index < rates_private.length; index++) {
                    total_product += Number(rates_private[index].value.replace(/,/g, ''));
                }
            }

            rate_total.value = numberWithCommas(total_product);

            // document.getElementById('bt_adult').value = bt_adult;
            // document.getElementById('bt_child').value = bt_child;
            // document.getElementById('bt_infant').value = bt_infant;
            // document.getElementById('bt_foc').value = bt_foc;
        }

        function check_time(params) {
            var zone_id = params == 'zone_pickup' ? document.getElementById('zone_pickup') : document.getElementById('zone_dropoff');
            var start_pickup = $('#zone_pickup').find(':selected').attr('data-start-pickup');
            var end_pickup = $('#zone_pickup').find(':selected').attr('data-end-pickup');
            document.getElementById('start_pickup').value = typeof start_pickup !== 'undefined' ? start_pickup : '00:00';
            document.getElementById('end_pickup').value = typeof end_pickup !== 'undefined' ? end_pickup : '00:00';
        }

        // Script Function Extra Charge
        // ------------------------------------------------------------------------------------
        function check_start_extra() {
            var $div = $('div[id^="div-start-extra-charge"]');
            for (let index = 0; index < $div.length; index++) {
                var extra_charge = document.getElementsByName('extra-charge[' + index + '][extra_charge]')[0].value;
                var extra_type = document.getElementsByName('extra-charge[' + index + '][extra_type]')[0].value;
                // readOnly Name
                document.getElementsByName('extra-charge[' + index + '][extc_name]')[0].readOnly = extra_charge != 0 ? true : false;
                // hidden perpax or private
                document.getElementsByName('extra-charge[' + index + '][div_extar_perpax]')[0].hidden = extra_type == 1 ? false : true;
                document.getElementsByName('extra-charge[' + index + '][div_extar_perpax]')[1].hidden = extra_type == 1 ? false : true;
                document.getElementsByName('extra-charge[' + index + '][div_extar_perpax]')[2].hidden = extra_type == 1 ? false : true;

                document.getElementsByName('extra-charge[' + index + '][div_extar_total]')[0].hidden = extra_type == 2 ? false : true;
            }
            if ($div.length > 0) {
                checke_rate_extar();
            }
        }

        function chang_extra_charge(select) {
            // console.log(select.value);
            if (select.value > 0) {
                var ex_name = select.name.replace('[extra_charge]', '[extc_name]');
                document.getElementsByName(ex_name)[0].readOnly = select.value != 0 ? true : false;
                if (select.value != 0) {
                    var ex_inp_ad = select.name.replace('[extra_charge]', '[extra_rate_adult]');
                    var extar_ad = document.getElementById('extar_ad' + select.value).value;
                    document.getElementsByName(ex_inp_ad)[0].value = numberWithCommas(extar_ad);

                    var ex_inp_chd = select.name.replace('[extra_charge]', '[extra_rate_child]');
                    var extar_chd = document.getElementById('extar_chd' + select.value).value;
                    document.getElementsByName(ex_inp_chd)[0].value = numberWithCommas(extar_chd);

                    var ex_inp_inf = select.name.replace('[extra_charge]', '[extra_rate_infant]');
                    var extar_inf = document.getElementById('extar_inf' + select.value).value;
                    document.getElementsByName(ex_inp_inf)[0].value = numberWithCommas(extar_inf);

                    var ex_inp_total = select.name.replace('[extra_charge]', '[extra_rate_private]');
                    var extar_total = document.getElementById('extar_total' + select.value).value;
                    document.getElementsByName(ex_inp_total)[0].value = numberWithCommas(extar_total);
                }
            }
            checke_rate_extar();
        }

        function check_extar_type(select) {
            var adult = document.getElementById('adult').value;
            var child = document.getElementById('child').value;
            var infant = document.getElementById('infant').value;

            var div_name_perpax = select.name.replace('[extra_type]', '[div_extar_perpax]');
            document.getElementsByName(div_name_perpax)[0].hidden = select.value == 1 ? false : true;
            document.getElementsByName(div_name_perpax)[1].hidden = select.value == 1 ? false : true;
            document.getElementsByName(div_name_perpax)[2].hidden = select.value == 1 ? false : true;

            var extra_adult = select.name.replace('[extra_type]', '[extra_adult]');
            document.getElementsByName(extra_adult)[0].value = adult;
            var extra_child = select.name.replace('[extra_type]', '[extra_child]');
            document.getElementsByName(extra_child)[0].value = child;
            var extra_infant = select.name.replace('[extra_type]', '[extra_infant]');
            document.getElementsByName(extra_infant)[0].value = infant;

            var div_name_total = select.name.replace('[extra_type]', '[div_extar_total]');
            document.getElementsByName(div_name_total)[0].hidden = select.value == 2 ? false : true;
        }

        function checke_rate_extar() {
            var $start = $('div[id^="div-start-extra-charge"]');
            if ($start.length > 0) {
                for (let index = 0; index < $start.length; index++) {
                    var total = 0;
                    var extra_type = document.getElementsByName('extra-charge[' + index + '][extra_type]');

                    var adult = document.getElementsByName('extra-charge[' + index + '][extra_adult]');
                    var rate_adult = document.getElementsByName('extra-charge[' + index + '][extra_rate_adult]');
                    var child = document.getElementsByName('extra-charge[' + index + '][extra_child]');
                    var rate_child = document.getElementsByName('extra-charge[' + index + '][extra_rate_child]');
                    var infant = document.getElementsByName('extra-charge[' + index + '][extra_infant]');
                    var rate_infant = document.getElementsByName('extra-charge[' + index + '][extra_rate_infant]');
                    var ex_total = document.getElementsByName('extra-charge[' + index + '][extra_num_private]');
                    var rate_total = document.getElementsByName('extra-charge[' + index + '][extra_rate_private]');

                    total = extra_type[0].value != 0 ? extra_type[0].value == 2 ? Number(ex_total[0].value * rate_total[0].value.replace(/,/g, '')) : Number((adult[0].value * rate_adult[0].value.replace(/,/g, '')) + (child[0].value * rate_child[0].value.replace(/,/g, '')) + (infant[0].value * rate_infant[0].value.replace(/,/g, ''))) : 0;
                    document.getElementsByName('extra-charge[' + index + '][extc_total]')[0].innerHTML = numberWithCommas(total);
                }
            }

            var $div = $('div[id^="div-extra-charge"]');
            if ($div.length > 0) {
                for (let index = 0; index < $div.length; index++) {
                    var total = 0;
                    var extra_type = document.getElementsByName('extra-charge[' + index + '][extra_type]');

                    var adult = document.getElementsByName('extra-charge[' + index + '][extra_adult]');
                    var rate_adult = document.getElementsByName('extra-charge[' + index + '][extra_rate_adult]');
                    var child = document.getElementsByName('extra-charge[' + index + '][extra_child]');
                    var rate_child = document.getElementsByName('extra-charge[' + index + '][extra_rate_child]');
                    var infant = document.getElementsByName('extra-charge[' + index + '][extra_infant]');
                    var rate_infant = document.getElementsByName('extra-charge[' + index + '][extra_rate_infant]');
                    var ex_total = document.getElementsByName('extra-charge[' + index + '][extra_num_private]');
                    var rate_total = document.getElementsByName('extra-charge[' + index + '][extra_rate_private]');

                    total = extra_type[0].value != 0 ? extra_type[0].value == 2 ? Number(ex_total[0].value * rate_total[0].value.replace(/,/g, '')) : Number((adult[0].value * rate_adult[0].value.replace(/,/g, '')) + (child[0].value * rate_child[0].value.replace(/,/g, '')) + (infant[0].value * rate_infant[0].value.replace(/,/g, ''))) : 0;
                    document.getElementsByName('extra-charge[' + index + '][extc_total]')[0].innerHTML = numberWithCommas(total);
                }
            }
        }
    </script>
    <!-- END: Page JS-->

    <script>
        $(window).on('load', function() {
            if (feather) {
                feather.replace({
                    width: 14,
                    height: 14
                });
            }
        })
    </script>
</body>
<!-- END: Body-->