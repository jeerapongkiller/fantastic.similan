<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
    <meta name="description" content="<?php echo $main_description; ?>">
    <meta name="keywords" content="<?php echo $main_keywords; ?>">
    <meta name="author" content="<?php echo $main_author; ?>">
    <title>Receipt - <?php echo $main_title; ?></title>
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
            $('.invoice-list-table').DataTable({
                "searching": false,
                paging: false,
                order: [
                    [1, 'asc']
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

            var jqForm = $('#receipt-search-form'),
                jqFormRec = $('#receipt-form'),
                picker = $('#dob'),
                dtPicker = $('#dob-bootstrap-val'),
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

            $('#search_due_form').change(function() {
                $('#search_due_to').flatpickr({
                    onReady: function(selectedDates, dateStr, instance) {
                        if (instance.isMobile) {
                            $(instance.mobileInput).attr('step', null);
                        }
                    },
                    static: true,
                    altInput: true,
                    altFormat: 'j F Y',
                    dateFormat: 'Y-m-d',
                    defaultDate: $('#search_due_form').val()
                });
            });

            // Ajax Search
            // --------------------------------------------------------------------
            jqForm.on("submit", function(e) {
                var serializedData = $(this).serialize();
                $.ajax({
                    url: "pages/receipt/function/search.php",
                    type: "POST",
                    data: serializedData + "&action=search",
                    success: function(response) {
                        if (response != false) {
                            $("#invoice-search-table").html(response);

                            $('.invoice-list-table').DataTable({
                                "searching": false,
                                paging: false,
                                order: [
                                    [1, 'asc']
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

            // jQuery Validation
            // --------------------------------------------------------------------
            if (jqFormRec.length) {
                $.validator.addMethod("regex", function(value, element, regexp) {
                    return this.optional(element) || regexp.test(value);
                }, "Please check your input.");

                jqFormRec.validate({
                    rules: {

                    },
                    messages: {

                    },
                    submitHandler: function(form) {
                        // update ajax request data
                        var formData = new FormData(form);
                        formData.append('action', 'create');
                        $.ajax({
                            url: "pages/receipt/function/create.php",
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
        });

        function numberWithCommas(x) {
            return x.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
        }

        function modal_receipt(cover_id) {
            var res_inv = JSON.parse($('#checkbox' + cover_id).attr('data-inv_id'));
            if (res_inv.length > 0 && cover_id > 0) {
                var text_html = '';
                var no = 1;
                var vat_total = 0;
                var vat_cut = 0;
                var withholding_total = 0;
                var total = 0;
                var discount = 0;
                var cot = 0;
                var dep = 0;
                var res_inv_no = JSON.parse($('#checkbox' + cover_id).attr('data-inv_no'));
                var res_bo_id = JSON.parse($('#checkbox' + cover_id).attr('data-bo_id'));
                var res_bo_full = JSON.parse($('#checkbox' + cover_id).attr('data-bo_full'));
                var res_voucher = JSON.parse($('#checkbox' + cover_id).attr('data-voucher'));
                var res_cus_name = JSON.parse($('#checkbox' + cover_id).attr('data-cus_name'));
                var res_product_name = JSON.parse($('#checkbox' + cover_id).attr('data-product_name'));
                var res_discount = JSON.parse($('#checkbox' + cover_id).attr('data-discount'));
                var res_total = JSON.parse($('#checkbox' + cover_id).attr('data-total'));
                var res_payment_id = JSON.parse($('#checkbox' + cover_id).attr('data-payment_id'));
                var res_payment_name = JSON.parse($('#checkbox' + cover_id).attr('data-payment_name'));
                
                document.getElementById('cover_id').value = $('#checkbox' + cover_id).val();
                document.getElementById('bo_id').value = $('#checkbox' + cover_id).attr('data-bo_id');
                document.getElementById('agent_name_text').innerHTML = $('#checkbox' + cover_id).attr('data-agent_name');
                document.getElementById('agent_address_text').innerHTML = $('#checkbox' + cover_id).attr('data-agent_address');
                document.getElementById('agent_tel_text').innerHTML = $('#checkbox' + cover_id).attr('data-agent_telephone');
                document.getElementById('agent_tax_text').innerHTML = $('#checkbox' + cover_id).attr('data-agent_tat');
                document.getElementById('branch_text').innerHTML = $('#checkbox' + cover_id).attr('data-brch_name');

                var payment_name = '';
                for (let index = 0; index < res_inv.length; index++) {
                    var inv_full = res_inv_no[index] > 0 ? $('#checkbox' + cover_id).attr('data-inv_full') + '/' + res_inv_no[index] : $('#checkbox' + cover_id).attr('data-inv_full');
                    payment_name = '<b>' + res_payment_name[index] + '</b>';
                    payment_name = (res_payment_id[index] == 4 || res_payment_id[index] == 5) ? payment_name : payment_name;

                    text_html += '<tr>' +
                        '<td class="text-center">' + Number(no++) + '</td>' +
                        '<td class="text-center text-nowrap"> ' + inv_full + ' </td>' +
                        '<td class="text-center"> ' + res_bo_full[index] + ' </td>' +
                        '<td class="text-center"> ' + res_voucher[index] + ' </td>' +
                        '<td class="text-center"> ' + res_cus_name[index] + ' </td>' +
                        '<td class="text-center"> ' + res_product_name[index] + ' </td>' +
                        '<td class="text-center"> ' + $('#checkbox' + cover_id).attr('data-rec_date') + ' </td>' +
                        '<td class="text-center"> ' + payment_name + ' </td>' +
                        '<td class="text-center"> ' + numberWithCommas(Number(res_total[index])) + ' </td>' +
                        '</tr>';

                    // if (res_payment_id[index] == 4) {
                    //     cot = Number(cot) + Number(res_total_paid[index]);
                    // } else if (res_payment_id[index] == 5) {
                    //     dep = Number(dep) + Number(res_total_paid[index]);
                    // }
                    discount = Number(discount) + Number(res_discount[index]);
                    total = Number(total) + Number(res_total[index]);
                }

                text_html += '<tr>' +
                    '<td colspan="7"></td>' +
                    '<td class="text-center"><b>รวมเป็นเงิน</b><br><small>(Total)</small></td>' +
                    '<td class="text-center">' + numberWithCommas(total) + '</td>' +
                    '</tr>'

                if (discount != 0) {
                    text_html += '<tr>' +
                        '<td colspan="7"></td>' +
                        '<td class="text-center"><b>ส่วนลด</b><br><small>(Discount)</small></td>' +
                        '<td class="text-center">' + numberWithCommas(discount) + '</td>' +
                        '</tr>';
                }

                total = (discount > 0) ? Number(total - discount) : total;
                // --- vat and withholding --- //
                if ($('#checkbox' + cover_id).attr('data-vat_id') == 1) {
                    vat_total = Number(((total * 100) / 107));
                    vat_cut = vat_total;
                    vat_total = Number(total - vat_total);
                    withholding_total = $('#checkbox' + cover_id).attr('data-withholding') > 0 ? Number((vat_cut * $('#checkbox' + cover_id).attr('data-withholding')) / 100) : 0;
                    total = Number(total - withholding_total);
                    withholding_total = Number(withholding_total).toLocaleString("en-US", {
                        maximumFractionDigits: 2
                    });
                } else if ($('#checkbox' + cover_id).attr('data-vat_id') == 2) {
                    vat_total = Number(((total * 7) / 100));
                    total = Number(total) + Number(vat_total);
                    withholding_total = $('#checkbox' + cover_id).attr('data-withholding') > 0 ? Number(((total - vat_total) * $('#checkbox' + cover_id).attr('data-withholding')) / 100) : 0;
                    total = Number(total - withholding_total);
                    withholding_total = Number(withholding_total).toLocaleString("en-US", {
                        maximumFractionDigits: 2
                    });
                }

                if ($('#checkbox' + cover_id).attr('data-vat_id') != 0) {
                    text_html += '<tr>' +
                        '<td colspan="7"></td>' +
                        '<td class="text-center"><b>' + $('#checkbox' + cover_id).attr('data-vat_name') + '</b><br><small>(Vat)</small></td>' +
                        '<td class="text-center">' + Number(vat_total).toLocaleString("en-US", {
                            maximumFractionDigits: 2
                        }) + '</td>' +
                        '</tr>';
                }

                if ($('#checkbox' + cover_id).attr('data-withholding') != 0) {
                    text_html += '<tr>' +
                        '<td colspan="7"></td>' +
                        '<td class="text-center"><b>หัก ณ ที่จ่าย (' + $('#checkbox' + cover_id).attr('data-withholding') + '%)</b><br><small>(Withholding Tax)</small></td>' +
                        '<td class="text-center">' + numberWithCommas(withholding_total) + '</td>' +
                        '</tr>';
                }

                text_html += '<tr>' +
                    '<td colspan="7"></td>' +
                    '<td class="text-center"><b>ยอดชำระ</b><br><small>(Payment Amount)</small></td>' +
                    '<td class="text-center" id="price-amount">' + Number(total).toLocaleString("en-US", {
                        maximumFractionDigits: 2
                    }); + '</td>' +
                '</tr>';

                $('#tbody-invoice').html(text_html);

                document.getElementById('price_total').value = total;
            }

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

            $('#date_check').flatpickr({
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

            check_payment();
        }

        function fun_search_period() {
            var search_period = document.getElementById('search_period').value;
            document.getElementById('div-due-form').hidden = search_period == 'custom' ? false : true;
            document.getElementById('div-due-to').hidden = search_period == 'custom' ? false : true;

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

            $('#search_due_form').flatpickr({
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

            $('#search_due_to').flatpickr({
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

        function check_payment() {
            var payments_type = document.getElementById('payments_type').value;
            document.getElementById('div-bank-account').hidden = payments_type == 4 ? false : true;
            document.getElementById('div-bank').hidden = payments_type == 5 ? false : true;
            document.getElementById('div-check-no').hidden = payments_type == 5 ? false : true;
            document.getElementById('div-check-date').hidden = payments_type == 5 ? false : true;
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