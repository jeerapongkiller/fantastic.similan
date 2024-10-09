<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
    <meta name="description" content="<?php echo $main_description; ?>">
    <meta name="keywords" content="<?php echo $main_keywords; ?>">
    <meta name="author" content="<?php echo $main_author; ?>">
    <title>Booking - <?php echo $main_title; ?></title>
    <link rel="apple-touch-icon" href="app-assets/images/ico/favicon.png">
    <link rel="shortcut icon" type="image/x-icon" href="app-assets/images/ico/favicon.png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="app-assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="app-assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="app-assets/vendors/css/tables/datatable/responsive.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="app-assets/vendors/css/tables/datatable/buttons.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="app-assets/vendors/css/forms/select/select2.min.css">
    <link rel="stylesheet" type="text/css" href="app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css">
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
    <link rel="stylesheet" type="text/css" href="app-assets/css/plugins/forms/pickers/form-flat-pickr.css">
    <link rel="stylesheet" type="text/css" href="app-assets/css/pages/app-user.css">
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

    <!-- BEGIN: Page Vendor JS-->
    <script src="app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js"></script>
    <script src="app-assets/vendors/js/tables/datatable/datatables.bootstrap4.min.js"></script>
    <script src="app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js"></script>
    <script src="app-assets/vendors/js/tables/datatable/responsive.bootstrap4.js"></script>
    <script src="app-assets/vendors/js/tables/datatable/datatables.buttons.min.js"></script>
    <script src="app-assets/vendors/js/tables/datatable/buttons.bootstrap4.min.js"></script>
    <script src="app-assets/vendors/js/forms/select/select2.full.min.js"></script>
    <script src="app-assets/vendors/js/forms/validation/jquery.validate.min.js"></script>
    <script src="app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js"></script>
    <script src="app-assets/fonts/fontawesome/js/all.js"></script>
    <script src="app-assets/vendors/js/forms/cleave/cleave.min.js"></script>
    <script src="app-assets/vendors/js/forms/cleave/addons/cleave-phone.us.js"></script>
    <script src="app-assets/vendors/js/forms/repeater/jquery.repeater.min.js"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN Sweetalert2 JS -->
    <script src="app-assets/vendors/js/extensions/sweetalert2.all.min.js"></script>
    <script src="app-assets/vendors/js/extensions/polyfill.min.js"></script>
    <!-- END Sweetalert2 JS -->

    <!-- BEGIN: Theme JS-->
    <script src="app-assets/js/core/app-menu.js"></script>
    <script src="app-assets/js/core/app.js"></script>
    <!-- END: Theme JS-->

    <?php
    $columntarget = $_SESSION["supplier"]["role_id"] == 1 || $_SESSION["supplier"]["role_id"] == 2 ? '5, 6' : '5';
    ?>

    <!-- BEGIN: Page JS-->
    <script type="text/javascript">
        // Ajax Delete Booking
        // --------------------------------------------------------------------
        function deleteBooking(booking_id) {
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
                            booking_id: booking_id,
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
                                    location.href = './?pages=booking/list';
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
            $('.booking-list-table').DataTable({
                "searching": false,
                order: [
                    // [4, 'desc']
                ],
                dom: '<"d-flex justify-content-between align-items-center header-actions mx-1 row mt-75"' +
                    '<"col-lg-12 col-xl-6" l>' +
                    '<"col-lg-12 col-xl-6 pl-xl-75 pl-0"<"dt-action-buttons text-xl-right text-lg-left text-md-right text-left d-flex align-items-center justify-content-lg-end align-items-center flex-sm-nowrap flex-wrap mr-1"<"mr-1"f>B>>' +
                    '>t' +
                    '<"d-flex justify-content-between mx-2 row mb-1"' +
                    '<"col-sm-12 col-md-6"i>' +
                    '<"col-sm-12 col-md-6"p>' +
                    '>',
                columnDefs: [{
                    // targets: [<?php echo $columntarget; ?>],
                    orderable: false
                }],
                language: {
                    sLengthMenu: 'Show _MENU_'
                },
                // Buttons with Dropdown
                buttons: [],
                language: {
                    paginate: {
                        // remove previous & next text from pagination
                        previous: '&nbsp;',
                        next: '&nbsp;'
                    }
                },
            });

            var jqFormBooking = $('#booking-create-form'),
                jqForm = $('#booking-search-form'),
                picker = $('#dob'),
                dtPicker = $('#dob-bootstrap-val'),
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

            if ($('#search_travel').length) {
                $('#search_travel').flatpickr({
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
            }

            $('.time-mask').toArray().forEach(function(field) {
                new Cleave(field, {
                    time: true,
                    timePattern: ['h', 'm']
                });
            });

            //Numeral
            $('.numeral-mask').toArray().forEach(function(field) {
                new Cleave(field, {
                    numeral: true,
                    numeralThousandsGroupStyle: 'thousand'
                });
            });

            $('.outside-text').on('click', function(e) {
                e.preventDefault();
                document.getElementById('frm-agent').hidden = false;
                document.getElementById('frm-agent-outside').hidden = true;

                $("#agent").val(0).trigger("change");
            });

            $('.extra-charge-repeater').repeater({
                show: function() {
                    $(this).slideDown();
                    $(this).find('[data-extra-repeater="select2"]').select2();
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
                },
                isFirstItemUndeletable: true
            });

            // Ajax Search
            // --------------------------------------------------------------------
            jqForm.on("submit", function(e) {
                var serializedData = $(this).serialize();
                $.ajax({
                    url: "pages/booking/function/search.php",
                    type: "POST",
                    data: serializedData + "&action=search",
                    success: function(response) {
                        if (response != false) {
                            $("#booking-search-table").html(response);

                            $('.booking-list-table').DataTable({
                                "searching": false,
                                order: [
                                    // [4, 'desc']
                                ],
                                dom: '<"d-flex justify-content-between align-items-center header-actions mx-1 row mt-75"' +
                                    '<"col-lg-12 col-xl-6" l>' +
                                    '<"col-lg-12 col-xl-6 pl-xl-75 pl-0"<"dt-action-buttons text-xl-right text-lg-left text-md-right text-left d-flex align-items-center justify-content-lg-end align-items-center flex-sm-nowrap flex-wrap mr-1"<"mr-1"f>B>>' +
                                    '>t' +
                                    '<"d-flex justify-content-between mx-2 row mb-1"' +
                                    '<"col-sm-12 col-md-6"i>' +
                                    '<"col-sm-12 col-md-6"p>' +
                                    '>',
                                columnDefs: [{
                                    // targets: [<?php echo $columntarget; ?>],
                                    orderable: false
                                }],
                                language: {
                                    sLengthMenu: 'Show _MENU_'
                                },
                                // Buttons with Dropdown
                                buttons: [],
                                language: {
                                    paginate: {
                                        // remove previous & next text from pagination
                                        previous: '&nbsp;',
                                        next: '&nbsp;'
                                    }
                                },
                            });

                            if (feather) {
                                feather.replace({
                                    width: 14,
                                    height: 14
                                });
                            }
                        }
                    }
                });
                e.preventDefault();
            });

            if (jqFormBooking.length) {
                $.validator.addMethod("regex", function(value, element, regexp) {
                    return this.optional(element) || regexp.test(value);
                }, "Please check your input.");

                jqFormBooking.validate({
                    rules: {
                        'product_id': {
                            required: true
                        },
                        'category_id': {
                            required: true
                        }
                    },
                    messages: {

                    },
                    submitHandler: function(form) {
                        // update ajax request data
                        var formData = new FormData(form);
                        formData.append('action', 'create');
                        formData.append('quick', 1);
                        $.ajax({
                            url: "pages/booking/function/create.php",
                            type: "POST",
                            processData: false,
                            contentType: false,
                            data: formData,
                            success: function(response) {
                                // $('#div-show').html(response);
                                if (response != false && response > 0) {
                                    Swal.fire({
                                        title: "The information has been updated.",
                                        icon: "success",
                                    }).then(function(isConfirm) {
                                        if (isConfirm) {
                                            window.location.href = './?pages=booking/list';
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

            search_start_date('today', 'boat');
            search_start_date('tomorrow', 'boat');
            search_start_date('today', 'driver');
            search_start_date('tomorrow', 'driver');
        });

        function search_start_date(type_date, type) {
            var formData = new FormData();
            formData.append('action', 'search');
            formData.append('type_date', type_date);
            formData.append('type', type);
            $.ajax({
                url: "pages/booking/function/search-report.php",
                type: "POST",
                processData: false,
                contentType: false,
                data: formData,
                success: function(response) {
                    if (response != 'false' && type == 'boat') {
                        $('#boat-report-' + type_date).html(response);
                    } else if (response != 'false' && type == 'driver') {
                        $('#driver-report-' + type_date).html(response);
                    }
                }
            });
        }

        function search_program() {
            var open_rates = document.getElementById('open-rates').value;
            var book_type = document.getElementById('book_type1').checked == true ? 1 : 2;
            document.getElementById('div-adult').hidden = (book_type == 1 && open_rates == 1) ? true : false;
            document.getElementById('table-adult').hidden = (book_type == 1 && open_rates == 1) ? false : true;
            document.getElementById('div-child').hidden = (book_type == 1 && open_rates == 1) ? true : false;
            document.getElementById('table-child').hidden = (book_type == 1 && open_rates == 1) ? false : true;
            document.getElementById('div-infant').hidden = (book_type == 1 && open_rates == 1) ? true : false;
            document.getElementById('table-infant').hidden = (book_type == 1 && open_rates == 1) ? false : true;
            if (open_rates == 1) {
                document.getElementById('rate_total').readOnly = (book_type == 1) ? true : false;
            } else {
                document.getElementById('div-total').hidden = true;
            }


            var agent = document.getElementById('agent').value;
            var product_id = document.getElementById('product_id').value;

            document.getElementById('frm-agent').hidden = agent !== 'outside' ? false : true;
            document.getElementById('frm-agent-outside').hidden = agent !== 'outside' ? true : false;

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
                                $('#category_id').append("<option value=\"" + res.id[index] + "\">" + res.name[index] + "</option>");
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
            var open_rates = document.getElementById('open-rates').value;
            var book_type = document.getElementsByName('booking_type')[0].checked == true ? 1 : 2;
            var agent = document.getElementById('agent').value;
            var product_id = document.getElementById('product_id').value;
            var category_id = document.getElementById('category_id').value;
            var travel_date = document.getElementById('travel_date').value;
            var formData = new FormData();
            formData.append('action', 'search');
            formData.append('agent_id', agent);
            formData.append('product_id', product_id);
            formData.append('category_id', category_id);
            formData.append('travel_date', travel_date);
            $.ajax({
                url: "pages/booking/function/search-rate.php",
                type: "POST",
                processData: false,
                contentType: false,
                data: formData,
                success: function(response) {
                    if (response != '' && response != false && open_rates == 1) {
                        var res = $.parseJSON(response);
                        if (book_type == 1) {
                            document.getElementById('pror_id').value = res.prodrid;
                            document.getElementById('rate_adult').value = res.rate_adult;
                            document.getElementById('rate_child').value = res.rate_child;
                            document.getElementById('rate_infant').value = res.rate_infant;
                        } else if (book_type == 2) {
                            document.getElementById('rate_total').value = res.rate_private;
                        }
                        document.getElementById('div-transfer').hidden = (res.transfer == 0) ? true : false;
                        document.getElementById('start_pickup').value = (res.transfer == 0) ? '08:15' : '';
                        document.getElementById('end_pickup').value = (res.transfer == 0) ? '08:15' : '';
                        document.getElementById('include').value = (res.transfer === 1) ? '1' : '2';

                        check_rate();
                    }
                }
            });
        }

        function check_rate() {
            var total_product = 0;
            var book_type = document.getElementById('book_type1').checked == true ? 1 : 2;
            var rate_total = document.getElementById('rate_total');
            var adult = book_type == 2 ? document.getElementById('adult') : document.getElementById('cover-adult');
            var child = book_type == 2 ? document.getElementById('child') : document.getElementById('cover-child');
            var infant = book_type == 2 ? document.getElementById('infant') : document.getElementById('cover-infant');
            if (book_type == 1) {
                var rate_adult = document.getElementById('rate_adult').value.replace(/,/g, '');
                var rate_child = document.getElementById('rate_child').value.replace(/,/g, '');
                var rate_infant = document.getElementById('rate_infant').value.replace(/,/g, '');
            }
            if (book_type == 1) {
                total_product = Number(total_product) + Number(rate_adult) * Number(adult.value);
                total_product = Number(total_product) + Number(rate_child) * Number(child.value);
                total_product = Number(total_product) + Number(rate_infant) * Number(infant.value);
            } else {
                total_product = Number(rate_total.value.replace(/,/g, ''));
            }
            rate_total.value = numberWithCommas(total_product);
        }

        function check_time(params) {
            var hotel = params == 'zone_pickup' ? '#hotel_pickup' : '#hotel_dropoff';
            var zone_id = params == 'zone_pickup' ? document.getElementById('zone_pickup') : document.getElementById('zone_dropoff');
            var start_pickup = $('#zone_pickup').find(':selected').attr('data-start-pickup');
            var end_pickup = $('#zone_pickup').find(':selected').attr('data-end-pickup');
            document.getElementById('start_pickup').value = typeof start_pickup !== 'undefined' ? start_pickup : '00:00';
            document.getElementById('end_pickup').value = typeof end_pickup !== 'undefined' ? end_pickup : '00:00';

            var formData = new FormData();
            formData.append('action', 'search');
            formData.append('zone_id', zone_id.value);
            $.ajax({
                url: "pages/booking/function/search-hotel.php",
                type: "POST",
                processData: false,
                contentType: false,
                data: formData,
                success: function(response) {
                    $(hotel).find('option').remove();
                    $(hotel).append("<option data-zone='0' value='0'>Please Select Hotel...</option>");
                    $(hotel).append("<option data-zone='0' value='outside'>กรอกข้อมูลเพิ่มเติม</option>");
                    if (response != '' && response != false) {
                        var res = $.parseJSON(response);
                        var count = Object.keys(res).length;
                        if (count) {
                            for (let index = 0; index < count; index++) {
                                $(hotel).append("<option value=\"" + res[index].id + "\">" + res[index].name + "</option>");
                            }
                        }
                    }
                }
            });

            // console.log($('#hotel_pickup').find('option[value="381"]'));
            // $('#hotel_pickup').find('option[value="381"]').prop('disabled', true);
            // $('#hotel_pickup').find('option[value="381"]').css("display","none");
            // $('#hotel_pickup').find('option[value="381"]').hide();
        }

        function check_outside(params) {
            return false; // ปิดการใช้งาน
            if (typeof params !== undefined && params == 'hotel_pickup' && document.getElementById('hotel_pickup').value == 'outside') {
                document.getElementById('frm-hotel-pickup').hidden = true;
                document.getElementById('frm-hotel-outside').hidden = false;
            } else if (typeof params !== undefined && params == 'hotel_outside') {
                document.getElementById('frm-hotel-outside').hidden = true;
                document.getElementById('frm-hotel-pickup').hidden = false;
            } else if (typeof params !== undefined && params == 'hotel_dropoff' && document.getElementById('hotel_dropoff').value == 'outside') {
                document.getElementById('frm-dropoff').hidden = true;
                document.getElementById('frm-dropoff-outside').hidden = false;
            } else if (typeof params !== undefined && params == 'dropoff_outside') {
                document.getElementById('frm-dropoff').hidden = false;
                document.getElementById('frm-dropoff-outside').hidden = true;
            }
        }

        function numberWithCommas(x) {
            return x.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
        }

        function check_no_agent(voucher_no) {
            var agent = document.getElementById('agent');

            var formData = new FormData();
            formData.append('action', 'check');
            formData.append('agent', agent.value);
            formData.append('voucher_no', voucher_no.value);
            $.ajax({
                url: "pages/booking/function/check-voucher-no.php",
                type: "POST",
                processData: false,
                contentType: false,
                data: formData,
                success: function(response) {
                    document.getElementById("invalid-voucher-no").style.display = (response > 0) ? "block" : "none";
                }
            });
        }

        function accordion_check(type) {
            var accordion = type == 'note' ? document.getElementById('accordionOne') : document.getElementById('accordionTwo');
            if (accordion.classList[3] !== 'hidden') {
                accordion.classList.remove("show");
                accordion.classList.add("hidden");
            } else {
                accordion.classList.remove("hidden");
                accordion.classList.add("show");
            }
        }

        function rows_customer() {
            check_rate();
            // if (open_rates == 1) {
            //     check_rate();
            // }
            return false; // ปิดการมใช้งาน
            var open_rates = document.getElementById('open-rates').value;
            var book_type = document.getElementById('book_type1').checked == true ? 1 : 2;
            var before_arr = document.getElementById('before_arr_cus').value;
            array = (before_arr !== undefined && before_arr != '' && before_arr != 'undefined') ? $.parseJSON(before_arr) : '';
            var frm = document.getElementById('frm-customer');
            var adult = (book_type == 1 && open_rates == 1) ? document.getElementById('cover-adult') : document.getElementById('adult');
            var child = (book_type == 1 && open_rates == 1) ? document.getElementById('cover-child') : document.getElementById('child');
            var infant = (book_type == 1 && open_rates == 1) ? document.getElementById('cover-infant') : document.getElementById('infant');
            var foc = document.getElementById('foc');
            var sum = Number(adult.value) + Number(child.value) + Number(infant.value) + Number(foc.value);

            var text = '';
            var text_age = '';
            var cus_age = 0;

            for (let index = 0; index < sum; index++) {
                // console.log(adult.value + ' | ' + child.value + ' | ' + infant.value + ' | ' + foc.value + ' | ' + Number(index + 1));
                if ((Number(adult.value) - Number(index + 1)) >= 0) {
                    text_age = 'Adult';
                    cus_age = 1;
                } else if (((Number(adult.value) + Number(child.value)) - Number(index + 1)) >= 0) {
                    text_age = 'Children';
                    cus_age = 2;
                } else if (((Number(adult.value) + Number(child.value) + Number(infant.value)) - Number(index + 1)) >= 0) {
                    text_age = 'Infant';
                    cus_age = 3;
                } else {
                    text_age = 'FOC';
                    cus_age = 4;
                }

                var cus_id = (array != '' && array['cus_id'][index]) ? array['cus_id'][index] : 0;
                var id_card = (array != '' && array['id_card'][index]) ? array['id_card'][index] : '';
                var cus_name = (array != '' && array['cus_name'][index]) ? array['cus_name'][index] : '';
                var birth_date = (array != '' && array['birth_date'][index]) ? array['birth_date'][index] : '';
                var nation_id = (array != '' && array['nation_id'][index]) ? array['nation_id'][index] : 0;

                text += '<div class="col-md-1 col-12">' +
                    '<div class="form-group pt-2">' +
                    '<strong>' + text_age + '</strong>' +
                    '<input type="hidden" name="customers[cus_id][]" value="' + cus_id + '" />' +
                    '<input type="hidden" name="customers[cus_age][]" value="' + cus_age + '" />' +
                    '</div>' +
                    '</div>' +
                    '<div class="col-md-2 col-12">' +
                    '<div class="form-group">' +
                    '<label for="id_card">ID Passport/ ID Card</label>' +
                    '<input type="text" class="form-control" name="customers[id_card][]" value="' + id_card + '" />' +
                    '</div>' +
                    '</div>' +
                    '<div class="col-md-4 col-12">' +
                    '<div class="form-group">' +
                    '<label for="name">Name</label>' +
                    '<input type="text" class="form-control" name="customers[cus_name][]" value="' + cus_name + '" />' +
                    '</div>' +
                    '</div>' +
                    '<div class="col-md-2 col-12">' +
                    '<div class="form-group">' +
                    '<label for="birth_date">Birth Date</label>' +
                    '<input type="date" class="form-control birth-date" name="customers[cus_birth_date][]" value="' + birth_date + '" />' +
                    '</div>' +
                    '</div>' +
                    '<div class="col-md-3 col-12">' +
                    '<div class="form-group">' +
                    '<label for="nationality_id">Nationality</label>' +
                    '<select class="form-control select2" id="customers' + index + '" name="customers[cus_nationality_id][]">' +
                    '<option value="0">Please Select Nationality...</option>' +
                    <?php
                    $nations = $bookObj->shownation();
                    foreach ($nations as $nation) {
                    ?> '<option value="<?php echo $nation['id']; ?>" ';
                text += (nation_id == <?php echo $nation['id']; ?>) ? 'selected' : '';
                text += "><?php echo $nation['name']; ?></option>" +
                <?php
                    }
                ?> '</select>' +
                '</div>' +
                '</div>';
            }

            frm.innerHTML = text;

            // select2
            $('.select2').each(function() {
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
        }

        // Script Function Extra Charge
        // ------------------------------------------------------------------------------------
        function chang_extra_charge(select) {
            if (select.value > 0) {
                var ex_name = select.name.replace('[extra_charge]', '[extc_name]');
                document.getElementsByName(ex_name)[0].readOnly = select.value != 0 ? true : false;

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

        function check_extar_type(select) {
            var adult = document.getElementById('cover-adult').value;
            var child = document.getElementById('cover-child').value;
            var infant = document.getElementById('cover-infant').value;

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

            checke_rate_extar();
        }

        function checke_rate_extar() {
            var $div = $('div[id^="div-extra-charge"]');
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