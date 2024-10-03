<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
    <meta name="description" content="<?php echo $main_description; ?>">
    <meta name="keywords" content="<?php echo $main_keywords; ?>">
    <meta name="author" content="<?php echo $main_author; ?>">
    <title>Invoice - <?php echo $main_title; ?></title>
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
    <script src="app-assets/vendors/js/forms/cleave/cleave.min.js"></script>
    <script src="app-assets/vendors/js/forms/cleave/addons/cleave-phone.us.js"></script>
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
    $columntarget = $_SESSION["supplier"]["role_id"] == 1 || $_SESSION["supplier"]["role_id"] == 2 ? '4, 5' : '4, 5';
    ?>

    <!-- BEGIN: Page JS-->
    <script type="text/javascript">
        $(document).ready(function() {
            $('.booking-list-table').DataTable({
                "searching": false,
                paging: false,
                order: [
                    [3, 'asc']
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
                    targets: [0],
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

            var // jqForm = $('#invoice-search-form'),
                jqFormInv = $('#invoice-form'),
                picker = $('#dob'),
                dtPicker = $('#dob-bootstrap-val'),
                range = $('.flatpickr-range'),
                select = $('.select2');

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

            if (range.length) {
                range.flatpickr({
                    onReady: function(selectedDates, dateStr, instance) {
                        if (instance.isMobile) {
                            $(instance.mobileInput).attr('step', null);
                        }
                    },
                    mode: 'range',
                    static: true,
                    altInput: true,
                    altFormat: 'j F Y',
                    dateFormat: 'Y-m-d'
                });
            }

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

            //Numeral
            $('.numeral-mask').toArray().forEach(function(field) {
                new Cleave(field, {
                    numeral: true,
                    numeralThousandsGroupStyle: 'thousand'
                });
            });

            $('#search_travel_form').change(function() {
                $('#search_travel_to').flatpickr({
                    onReady: function(selectedDates, dateStr, instance) {
                        if (instance.isMobile) {
                            $(instance.mobileInput).attr('step', null);
                        }
                    },
                    static: true,
                    altInput: true,
                    altFormat: 'j F Y',
                    dateFormat: 'Y-m-d',
                    defaultDate: $('#search_travel_form').val()
                });
            });

            // jQuery Validation
            // --------------------------------------------------------------------
            if (jqFormInv.length) {
                $.validator.addMethod("regex", function(value, element, regexp) {
                    return this.optional(element) || regexp.test(value);
                }, "Please check your input.");

                jqFormInv.validate({
                    rules: {
                        'name': {
                            required: true
                        }
                    },
                    messages: {

                    },
                    submitHandler: function(form) {
                        // update ajax request data
                        var bo_id = [];
                        let checked = $(".dt-checkboxes:checked");
                        for (let index = 0; index < checked.length; index++) {
                            bo_id.push(checked[index].value);
                        }
                        var formData = new FormData(form);
                        formData.append('action', 'create');
                        formData.append('bo_id', JSON.stringify(bo_id));
                        $.ajax({
                            url: "pages/invoice/function/create.php",
                            type: "POST",
                            processData: false,
                            contentType: false,
                            data: formData,
                            success: function(response) {
                                // $('#div-show').html(response);
                                if (response != false && response > 0) {
                                    Swal.fire({
                                        title: "The information has been added successfully.",
                                        icon: "success",
                                    }).then(function(isConfirm) {
                                        if (isConfirm) {
                                            location.reload(); // refresh page
                                        }
                                    });
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

            search_invoice();
        });

        function search_invoice() {
            var search_travel = document.getElementById('search_travel').value;
            var search_agent = document.getElementById('search_agent').value;
            var formData = new FormData();
            formData.append('action', 'search');
            formData.append('search_travel', search_travel);
            formData.append('search_agent', search_agent);
            $.ajax({
                url: "pages/invoice/function/search.php",
                type: "POST",
                processData: false,
                contentType: false,
                data: formData,
                success: function(response) {
                    if (response != false) {
                        var agent_name = $('#search_agent').find(':selected').attr('data-name');
                        var agent_address = $('#search_agent').find(':selected').attr('data-address');
                        var agent_telephone = $('#search_agent').find(':selected').attr('data-telephone');
                        var agent_tat = $('#search_agent').find(':selected').attr('data-tat');
                        document.getElementById('agent_name_text').innerHTML = agent_name;
                        document.getElementById('agent_address_text').innerHTML = agent_address;
                        document.getElementById('agent_tel_text').innerHTML = agent_telephone;
                        document.getElementById('agent_tax_text').innerHTML = agent_tat;

                        $("#booking-search-table").html(response);

                        $('.booking-list-table').DataTable({
                            "searching": false,
                            paging: false,
                            order: [
                                [3, 'asc']
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
                                targets: [0],
                                orderable: false
                            }],
                            language: {
                                sLengthMenu: 'Show _MENU_'
                            },
                            // Buttons with Dropdown
                            buttons: [{
                                text: feather.icons['plus'].toSvg({
                                    class: 'mr-50 font-small-4'
                                }) + 'สร้าง Invoice',
                                className: 'create-new btn btn-primary',
                                action: function(e, dt, node, config) {
                                    check_box_booking();
                                },
                                init: function(api, node, config) {
                                    $(node).removeClass('btn-secondary');
                                }
                            }],
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
        }

        function numberWithCommas(x) {
            return x.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
        }

        function check_box_booking() {
            let checked = $(".dt-checkboxes:checked");
            if (checked.length == 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'กรุณาเลือก Booking ที่จะออก Invoice.',
                    customClass: {
                        confirmButton: 'btn btn-primary'
                    }
                });
            } else {
                $('#modal-add-invoice').modal('show');
                modal_invoice();
            }
        }

        function modal_invoice() {
            let checked = $(".dt-checkboxes:checked");
            if (checked.length == 1) {
                var no = 3;
                var id = $(".dt-checkboxes:checked").val();
                var total_price = 0;
                var res_transfer = JSON.parse($('#checkbox' + id).attr('data-transfer'));
                var res_extra = $('#checkbox' + id).attr('data-extra') != '' ? JSON.parse($('#checkbox' + id).attr('data-extra')) : '';
                document.getElementById('div-only-booking').hidden = false;
                document.getElementById('div-multi-booking').hidden = true;
                document.getElementById('voucher_no_text').innerHTML = $('#checkbox' + id).attr('data-voucher');
                document.getElementById('booking_no_text').innerHTML = $('#checkbox' + id).attr('data-book_full');
                document.getElementById('programe_text').innerHTML = $('#checkbox' + id).attr('data-programe_name');
                document.getElementById('travel_date_text').innerHTML = $('#checkbox' + id).attr('data-travel_date');
                document.getElementById('guest_type_text').innerHTML = $('#checkbox' + id).attr('data-bo_type_name');
                document.getElementById('cus_name_text').innerHTML = $('#checkbox' + id).attr('data-cus_name');
                document.getElementById('hotel_text').innerHTML = $('#checkbox' + id).attr('data-hotel_pickup');
                document.getElementById('room_text').innerHTML = $('#checkbox' + id).attr('data-room_no');
                document.getElementById('pickup_time_text').innerHTML = $('#checkbox' + id).attr('data-pickup_time');
                var text_html = '';
                if ($('#checkbox' + id).attr('data-bo_type') == 2) {
                    text_html += '<tr>' +
                        '<td class="text-center">1</td>' +
                        '<td>Adult</td>' +
                        '<td class="text-center">' + $('#checkbox' + id).attr('data-adult') + '</td>' +
                        '<td class="text-center">' + numberWithCommas($('#checkbox' + id).attr('data-rate_adult')) + '</td>' +
                        '<td class="text-center" rowspan="3">' + numberWithCommas(Number($('#checkbox' + id).attr('data-rate_total'))) + '</td>' +
                        '</tr>' +
                        '<tr>' +
                        '<td class="text-center">2</td>' +
                        '<td>Children</td>' +
                        '<td class="text-center">' + $('#checkbox' + id).attr('data-child') + '</td>' +
                        '<td class="text-center">' + numberWithCommas($('#checkbox' + id).attr('data-rate_child')) + '</td>' +
                        '</tr>' +
                        '<tr>' +
                        '<td class="text-center">3</td>' +
                        '<td>Infant</td>' +
                        '<td class="text-center">' + $('#checkbox' + id).attr('data-infant') + '</td>' +
                        '<td class="text-center">' + numberWithCommas($('#checkbox' + id).attr('data-rate_infant')) + '</td>' +
                        '</tr>';
                } else {
                    text_html += '<tr>' +
                        '<td class="text-center">1</td>' +
                        '<td>Adult</td>' +
                        '<td class="text-center">' + $('#checkbox' + id).attr('data-adult') + '</td>' +
                        '<td class="text-center">' + numberWithCommas($('#checkbox' + id).attr('data-rate_adult')) + '</td>' +
                        '<td class="text-center">' + numberWithCommas(Number($('#checkbox' + id).attr('data-adult') * $('#checkbox' + id).attr('data-rate_adult'))) + '</td>' +
                        '</tr>' +
                        '<tr>' +
                        '<td class="text-center">2</td>' +
                        '<td>Children</td>' +
                        '<td class="text-center">' + $('#checkbox' + id).attr('data-child') + '</td>' +
                        '<td class="text-center">' + numberWithCommas($('#checkbox' + id).attr('data-rate_child')) + '</td>' +
                        '<td class="text-center">' + numberWithCommas(Number($('#checkbox' + id).attr('data-child') * $('#checkbox' + id).attr('data-rate_child'))) + '</td>' +
                        '</tr>' +
                        '<tr>' +
                        '<td class="text-center">3</td>' +
                        '<td>Infant</td>' +
                        '<td class="text-center">' + $('#checkbox' + id).attr('data-infant') + '</td>' +
                        '<td class="text-center">' + numberWithCommas($('#checkbox' + id).attr('data-rate_infant')) + '</td>' +
                        '<td class="text-center">' + numberWithCommas(Number($('#checkbox' + id).attr('data-infant') * $('#checkbox' + id).attr('data-rate_infant'))) + '</td>' +
                        '</tr>';
                }

                if ($('#checkbox' + id).attr('data-transfer_type') == 1) {
                    text_html += '<tr>' +
                        '<td colspan="6">Transfer : <b>Join</b></td>' +
                        '</tr>' +
                        '<tr>' +
                        '<td class="text-center">4</td>' +
                        '<td>Adult</td>' +
                        '<td class="text-center">' + res_transfer['bt_adult'] + '</td>' +
                        '<td class="text-center">' + res_transfer['btr_rate_adult'] + '</td>' +
                        '<td class="text-center">' + numberWithCommas(res_transfer['bt_adult'] * res_transfer['btr_rate_adult']) + '</td>' +
                        '</tr>' +
                        '<tr>' +
                        '<td class="text-center">5</td>' +
                        '<td>Children</td>' +
                        '<td class="text-center">' + res_transfer['bt_child'] + '</td>' +
                        '<td class="text-center">' + res_transfer['btr_rate_child'] + '</td>' +
                        '<td class="text-center">' + numberWithCommas(res_transfer['bt_child'] * res_transfer['btr_rate_child']) + '</td>' +
                        '</tr>' +
                        '<tr>' +
                        '<td class="text-center">6</td>' +
                        '<td>Infant</td>' +
                        '<td class="text-center">' + res_transfer['bt_infant'] + '</td>' +
                        '<td class="text-center">' + res_transfer['btr_rate_infant'] + '</td>' +
                        '<td class="text-center">' + numberWithCommas(res_transfer['bt_infant'] * res_transfer['btr_rate_infant']) + '</td>' +
                        '</tr>';
                    no = 6;
                } else if ($('#checkbox' + id).attr('data-transfer_type') == 2) {
                    text_html += '<tr><td colspan="6">Transfer : <b>Private</b></td></tr>';
                    for (let index = 0; index < res_transfer['cars_category'].length; index++) {
                        no++;
                        text_html += '<tr>' +
                            '<td class="text-center">' + no + '</td>' +
                            '<td>' + res_transfer['cars_category'][index] + '</td>' +
                            '<td class="text-center">1</td>' +
                            '<td class="text-center">' + numberWithCommas(res_transfer['rate_private'][index]) + '</td>' +
                            '<td class="text-center">' + numberWithCommas(res_transfer['rate_private'][index]) + '</td>' +
                            '</tr>';
                    }
                }

                if (res_extra != '') {
                    text_html += '<tr><td colspan="6">Extra Charge</td></tr>';
                    for (let index = 0; index < res_extra['bec_id'].length; index++) {
                        var extra_name = res_extra['extra_name'][index] != '' ? res_extra['extra_name'][index] : res_extra['bec_name'][index];
                        if (res_extra['bec_type'][index] == 1) {
                            no++;
                            text_html += '<tr>' +
                                '<td class="text-center">' + no + '</td>' +
                                '<td>' + extra_name + ' (Adult)</td>' +
                                '<td class="text-center">' + res_extra['bec_adult'][index] + '</td>' +
                                '<td class="text-center">' + numberWithCommas(res_extra['bec_rate_adult'][index]) + '</td>' +
                                '<td class="text-center">' + numberWithCommas(res_extra['bec_adult'][index] * res_extra['bec_rate_adult'][index]) + '</td>' +
                                '</tr>';
                            no++;
                            text_html += '<tr>' +
                                '<td class="text-center">' + no + '</td>' +
                                '<td>' + extra_name + ' (Children)</td>' +
                                '<td class="text-center">' + res_extra['bec_child'][index] + '</td>' +
                                '<td class="text-center">' + numberWithCommas(res_extra['bec_rate_child'][index]) + '</td>' +
                                '<td class="text-center">' + numberWithCommas(res_extra['bec_child'][index] * res_extra['bec_rate_child'][index]) + '</td>' +
                                '</tr>';
                            no++;
                            text_html += '<tr>' +
                                '<td class="text-center">' + no + '</td>' +
                                '<td>' + extra_name + ' (Infant)</td>' +
                                '<td class="text-center">' + res_extra['bec_infant'][index] + '</td>' +
                                '<td class="text-center">' + numberWithCommas(res_extra['bec_rate_infant'][index]) + '</td>' +
                                '<td class="text-center">' + numberWithCommas(res_extra['bec_infant'][index] * res_extra['bec_rate_infant'][index]) + '</td>' +
                                '</tr>';
                        } else {
                            no++;
                            text_html += '<tr>' +
                                '<td class="text-center">' + no + '</td>' +
                                '<td>' + extra_name + '</td>' +
                                '<td class="text-center">' + res_extra['bec_privates'][index] + '</td>' +
                                '<td class="text-center">' + numberWithCommas(res_extra['bec_rate_private'][index]) + '</td>' +
                                '<td class="text-center">' + numberWithCommas(res_extra['bec_privates'][index] * res_extra['bec_rate_private'][index]) + '</td>' +
                                '</tr>';
                        }
                    }
                }

                text_html += '<tr>' +
                    '<td colspan="3"></td>' +
                    '<td class="text-center">' +
                    '<b>รวมเป็นเงิน</b><br><small>(Total)</small></td>' +
                    '<td class="text-center">' + numberWithCommas($('#checkbox' + id).attr('data-total')) + '</td>' +
                    '</tr>';
                total_price = Number(total_price + $('#checkbox' + id).attr('data-total'));

                if ($('#checkbox' + id).attr('data-discount') > 0) {
                    text_html += '<tr>' +
                        '<td colspan="3"></td>' +
                        '<td class="text-center">' +
                        '<b>ส่วนลด</b><br><small>(Discount)</small></td>' +
                        '<td class="text-center">' + numberWithCommas($('#checkbox' + id).attr('data-discount')) + ' <input type="hidden" id="discount" name="discount[]" value="' + $('#checkbox' + id).attr('data-discount') + '"></td>' +
                        '</tr>';
                    total_price = Number(total_price - $('#checkbox' + id).attr('data-discount'));
                }

                if ($('#checkbox' + id).attr('data-payment_id') == 4) {
                    text_html += '<tr>' +
                        '<td colspan="3"></td>' +
                        '<td class="text-center">' +
                        '<b>Cash on tour</b></td>' +
                        '<td class="text-center">' + numberWithCommas($('#checkbox' + id).attr('data-total_paid')) + '</td>' +
                        '</tr>';
                    total_price = Number(total_price - $('#checkbox' + id).attr('data-total_paid'));
                } else if ($('#checkbox' + id).attr('data-payment_id') == 5) {
                    text_html += '<tr>' +
                        '<td colspan="3"></td>' +
                        '<td class="text-center">' +
                        '<b>Deposit</b></td>' +
                        '<td class="text-center">' + numberWithCommas($('#checkbox' + id).attr('data-total_paid')) + '</td>' +
                        '</tr>';
                    total_price = Number(total_price - $('#checkbox' + id).attr('data-total_paid'));
                }

                text_html += '<tr id="tr-only-vat" hidden>' +
                    '<td colspan="3"></td>' +
                    '<td class="text-center">' +
                    '<b id="vat-only-text"></b><br><small>(Vat)</small></td>' +
                    '<td class="text-center" id="price-only-vat"></td>' +
                    '</tr>';

                text_html += '<tr id="tr-only-withholding" hidden>' +
                    '<td colspan="3"></td>' +
                    '<td class="text-center">' +
                    '<b id="withholding-only-text"></b><br><small>(Withholding Tax)</small></td>' +
                    '<td class="text-center" id="price-only-withholding"></td>' +
                    '</tr>';

                text_html += '<tr>' +
                    '<td colspan="3"></td>' +
                    '<td class="text-center">' +
                    '<b>ยอดชำระ</b><br><small>(Payment Amount)</small></td>' +
                    '<td class="text-center" id="price-only-amount">' + numberWithCommas(total_price) + '</td>' +
                    '</tr>';

                $('#tbody-only-booking').html(text_html);

                document.getElementById('price_total').value = Number(total_price);
            } else if (checked.length > 1) {
                document.getElementById('div-only-booking').hidden = true;
                document.getElementById('div-multi-booking').hidden = false;
                var text_html = '';
                var payment_name = '';
                var total = 0;
                var amount = 0;
                var cot = 0;
                var dep = 0;
                var discount = 0;
                var no = 1;
                var payment_type = [];
                for (let index = 0; index < checked.length; index++) {
                    var id = checked[index].value;
                    payment_name = '<b>' + $('#checkbox' + id).attr('data-payment_name') + '</b>';
                    payment_name = ($('#checkbox' + id).attr('data-payment_id') == 4 || $('#checkbox' + id).attr('data-payment_id') == 5) ? payment_name + '</br>(' + numberWithCommas($('#checkbox' + id).attr('data-total_paid')) + ')' : payment_name;
                    total = Number($('#checkbox' + id).attr('data-total'));
                    total = ($('#checkbox' + id).attr('data-payment_id') == 4 || $('#checkbox' + id).attr('data-payment_id') == 5) ? Number(total - $('#checkbox' + id).attr('data-total_paid')) : total;

                    text_html += '<tr>' +
                        '<td class="text-center">' + Number(no++) + '</td>' +
                        '<td class="text-center"> ' + $('#checkbox' + id).attr('data-voucher') + ' </td>' +
                        '<td class="text-center"> ' + $('#checkbox' + id).attr('data-book_full') + ' </td>' +
                        '<td class="text-center"> ' + $('#checkbox' + id).attr('data-cus_name') + ' </td>' +
                        '<td class="text-center"> ' + $('#checkbox' + id).attr('data-programe_name') + ' </td>' +
                        '<td class="text-center"> ' + $('#checkbox' + id).attr('data-travel_date') + ' </td>' +
                        '<td class="text-center"> ' + payment_name + ' </td>' +
                        '<td class="text-center"> ' + numberWithCommas(total) + ' </td>' +
                        '</tr>';

                    discount = Number(discount) + Number($('#checkbox' + id).attr('data-discount'));
                    payment_type.push($('#checkbox' + id).attr('data-payment_id'));
                    amount = amount + total;
                }

                text_html += '<tr>' +
                    '<td colspan="6"></td>' +
                    '<td class="text-center"><b>รวมเป็นเงิน</b><br><small>(Total)</small></td>' +
                    '<td class="text-center">' + numberWithCommas(amount) + '</td>' +
                    '</tr>'

                if (discount > 0) {
                    amount = amount - discount;
                    text_html += '<tr>' +
                        '<td colspan="6"></td>' +
                        '<td class="text-center"><b>ส่วนลด</b><br><small>(Discount)</small></td>' +
                        '<td class="text-center">' + numberWithCommas(discount) + '</td>' +
                        '</tr>'
                }

                text_html += '<tr id="tr-multi-vat" hidden>' +
                    '<td colspan="6"></td>' +
                    '<td class="text-center"><b id="vat-multi-text"></b><br><small>(Vat)</small></td>' +
                    '<td class="text-center" id="price-multi-vat"></td>' +
                    '</tr>';

                text_html += '<tr id="tr-multi-withholding" hidden>' +
                    '<td colspan="6"></td>' +
                    '<td class="text-center"><b id="withholding-multi-text"></b><br><small>(Withholding Tax)</small></td>' +
                    '<td class="text-center" id="price-multi-withholding"></td>' +
                    '</tr>';

                text_html += '<tr>' +
                    '<td colspan="6"></td>' +
                    '<td class="text-center"><b>ยอดชำระ</b><br><small>(Payment Amount)</small></td>' +
                    '<td class="text-center" id="price-multi-amount"></td>' +
                    '</tr>';

                $('#tbody-multi-booking').html(text_html);

                document.getElementById('price_total').value = amount;
            }

            $('#inv_date').flatpickr({
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

            $('#rec_date').flatpickr({
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

            calculator_price();
        }

        function fun_search_period() {
            var search_period = document.getElementById('search_period').value;
            document.getElementById('div-travel-form').hidden = search_period == 'custom' ? false : true;
            document.getElementById('div-travel-to').hidden = search_period == 'custom' ? false : true;

            const date = new Date();
            let day = date.getDate();
            let month = date.getMonth() + 1;
            let year = date.getFullYear();
            switch (search_period) {
                case 'tomorrow':
                    day = date.getDate() + 1;
                    break;
                case 'week':
                    day = date.getDate() + 7;
                    break;
                case 'month':
                    month = date.getMonth() + 2;
                    break;
                case 'year':
                    year = date.getFullYear() + 1;
                    break;
            }
            let currentDate = `${year}-${month}-${day}`;

            $('#search_travel_form').flatpickr({
                onReady: function(selectedDates, dateStr, instance) {
                    if (instance.isMobile) {
                        $(instance.mobileInput).attr('step', null);
                    }
                },
                static: true,
                altInput: true,
                altFormat: 'j F Y',
                dateFormat: 'Y-m-d',
                defaultDate: currentDate
            });

            $('#search_travel_to').flatpickr({
                onReady: function(selectedDates, dateStr, instance) {
                    if (instance.isMobile) {
                        $(instance.mobileInput).attr('step', null);
                    }
                },
                static: true,
                altInput: true,
                altFormat: 'j F Y',
                dateFormat: 'Y-m-d',
                defaultDate: currentDate
            });
        }

        function check_diff_date(type) {
            var today = document.getElementById('today').value;
            var rec_date = document.getElementById(type).value;
            const date1 = new Date(today);
            const date2 = new Date(rec_date);
            const diffTime = date2.getTime() - date1.getTime();
            const diffDays = diffTime / (1000 * 60 * 60 * 24);
            var days = diffDays <= 0 ? '' : "อีก " + diffDays + " วัน";
            $('#diff_rec_date').html(days);
        }

        function calculator_price() {
            let checked = $(".dt-checkboxes:checked");
            var type = checked.length > 0 ? checked.length == 1 ? 'only' : 'multi' : '';
            var vat_total = 0;
            var vat_cut = 0;
            var withholding_total = 0;
            var total = 0;
            var price_amount = document.getElementById('price-' + type + '-amount');
            var vat = document.getElementById('vat');
            var withholding = document.getElementById('withholding');
            var price_withholding = document.getElementById('price-' + type + '-withholding');
            var price_total = document.getElementById('price_total');
            var tr_vat = document.getElementById('tr-' + type + '-vat');
            var vat_text = document.getElementById('vat-' + type + '-text');
            var price_vat = document.getElementById('price-' + type + '-vat');
            var tr_withholding = document.getElementById('tr-' + type + '-withholding');
            var withholding_text = document.getElementById('withholding-' + type + '-text');
            var amount = document.getElementById('amount');
            tr_vat.hidden = vat.value > 0 ? false : true;
            vat_text.innerHTML = vat.value > 0 ? vat.value == 1 ? 'รวมภาษี 7%' : 'แยกภาษี 7%' : '';
            tr_withholding.hidden = withholding.value > 0 ? false : true;
            withholding_text.innerHTML = withholding.value > 0 ? 'หักภาษี ณ ที่จ่าย ' + withholding.value + ' %' : '';
            // --- sum price total --- //
            total = price_total.value;
            // --- vat and withholding --- //
            if (vat.value == 1) {
                vat_total = Number(((total * 100) / 107));
                vat_cut = vat_total;
                vat_total = Number(total - vat_total);
                withholding_total = withholding.value > 0 ? Number((vat_cut * withholding.value) / 100) : 0;
                total = Number(total - withholding_total);
            } else if (vat.value == 2) {
                vat_total = Number(((total * 7) / 100));
                total = Number(total) + Number(vat_total);
                withholding_total = withholding.value > 0 ? Number(((total - vat_total) * withholding.value) / 100) : 0;
                total = Number(total - withholding_total);
            }
            price_vat.innerHTML = Number(vat_total).toLocaleString("en-US", {
                maximumFractionDigits: 2
            });
            price_withholding.innerHTML = Number(withholding_total).toLocaleString("en-US", {
                maximumFractionDigits: 2
            });
            price_amount.innerHTML = Number(total).toLocaleString("en-US", {
                maximumFractionDigits: 2
            });
            amount.value = total;
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