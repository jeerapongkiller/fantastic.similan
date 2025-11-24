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
    <link rel="stylesheet" type="text/css" href="app-assets/vendors/css/forms/select/select2.min.css">
    <link rel="stylesheet" type="text/css" href="app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="app-assets/vendors/css/forms/wizard/bs-stepper.min.css">
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
    <link rel="stylesheet" type="text/css" href="app-assets/css/plugins/forms/form-wizard.css">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <!-- END: Custom CSS-->

    <style>
        .table-black {
            color: #FFFFFF;
            background-color: #003285;
        }

        .table-black-2 {
            color: #FFFFFF;
            background-color: #0060ff;
        }
    </style>
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
    <script src="app-assets/vendors/js/forms/select/select2.full.min.js"></script>
    <script src="app-assets/vendors/js/forms/validation/jquery.validate.min.js"></script>
    <script src="app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js"></script>
    <script src="app-assets/vendors/js/forms/cleave/cleave.min.js"></script>
    <script src="app-assets/vendors/js/forms/cleave/addons/cleave-phone.us.js"></script>
    <script src="app-assets/vendors/js/forms/wizard/bs-stepper.min.js"></script>
    <script src="app-assets/js/scripts/node_modules/dom-to-image/src/dom-to-image.js"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN Sweetalert2 JS -->
    <script src="app-assets/vendors/js/extensions/sweetalert2.all.min.js"></script>
    <script src="app-assets/vendors/js/extensions/polyfill.min.js"></script>
    <!-- END Sweetalert2 JS -->

    <!-- BEGIN: Theme JS-->
    <script src="app-assets/js/core/app-menu.js"></script>
    <script src="app-assets/js/core/app.js"></script>
    <script src="app-assets/js/scripts/header.js"></script>
    <!-- <script src="app-assets/js/scripts/header.js"></script> -->
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <script type="text/javascript">
        // Ajax Delete Invoice
        // --------------------------------------------------------------------
        function deleteInvoice(cover_id) {
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
                        url: "pages/invoice/function/delete.php",
                        type: "POST",
                        data: {
                            cover_id: cover_id,
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
                                    $('#modal-invoice').modal('hide');
                                    var serializedData = $('#invoice-search-form').serialize();
                                    $.ajax({
                                        url: "pages/invoice/function/search.php",
                                        type: "POST",
                                        data: serializedData + "&action=search",
                                        success: function(response) {
                                            if (response != false) {
                                                $("#invoice-search-table").html(response);
                                            }
                                        }
                                    });
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
            var jqForm = $('#invoice-search-form'),
                jqFormInv = $('#invoice-form'),
                jqFormRec = $('#receipt-form'),
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

            // Range
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
                    dateFormat: 'Y-m-d',
                    defaultDate: ["<?php echo $today; ?>", "<?php echo $tomorrow; ?>"]
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

            //Numeral
            $('.numeral-mask').toArray().forEach(function(field) {
                new Cleave(field, {
                    numeral: true,
                    numeralThousandsGroupStyle: 'thousand'
                });
            });

            if (dtPicker.length) {
                dtPicker.flatpickr({
                    onReady: function(selectedDates, dateStr, instance) {
                        if (instance.isMobile) {
                            $(instance.mobileInput).attr('step', null);
                        }
                    }
                });
            }

            $('#search_inv_date').flatpickr({
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

            $('#search_travel').flatpickr({
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

            // Ajax Search
            // --------------------------------------------------------------------
            jqForm.on("submit", function(e) {

                $.blockUI({
                    message: '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>',
                    css: {
                        backgroundColor: 'transparent',
                        border: '0'
                    },
                    overlayCSS: {
                        // backgroundColor: '#ffffff4d',
                        opacity: 0.8
                    }
                });

                var serializedData = $(this).serialize();
                $.ajax({
                    url: "pages/invoice/function/search.php",
                    type: "POST",
                    data: serializedData + "&action=search",
                    success: function(response) {
                        if (response != false) {
                            $("#invoice-search-table").html(response);
                        } else {
                            $("#invoice-search-table").html('');
                        }
                    },
                    complete: function() {
                        $.unblockUI();
                    }
                });
                e.preventDefault();
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
                    messages: {},
                    submitHandler: function(form) {

                        $.blockUI({
                            message: '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>',
                            css: {
                                backgroundColor: 'transparent',
                                border: '0'
                            },
                            overlayCSS: {
                                // backgroundColor: '#ffffff4d',
                                opacity: 0.8
                            }
                        });

                        // update ajax request data
                        var formData = new FormData(form);
                        formData.append('action', $('#action').val());
                        $.ajax({
                            url: "pages/invoice/function/" + $('#action').val() + ".php",
                            type: "POST",
                            processData: false,
                            contentType: false,
                            data: formData,
                            success: function(response) {
                                // console.log(response);
                                if (response != false && response > 0) {
                                    Swal.fire({
                                        title: "The information has been added successfully.",
                                        icon: "success",
                                    }).then(function(isConfirm) {
                                        if (isConfirm) {
                                            // location.reload(); // refresh page
                                            $('#modal-invoice').modal('hide');
                                            var serializedData = $('#invoice-search-form').serialize();
                                            $.ajax({
                                                url: "pages/invoice/function/search.php",
                                                type: "POST",
                                                data: serializedData + "&action=search",
                                                success: function(response) {
                                                    if (response != false) {
                                                        $("#invoice-search-table").html(response);
                                                    } else {
                                                        $("#invoice-search-table").html('');
                                                    }
                                                }
                                            });
                                        }
                                    });
                                } else {
                                    Swal.fire({
                                        title: "Please try again.",
                                        icon: "error",
                                    });
                                }
                            },
                            complete: function() {
                                $.unblockUI();
                            }
                        });
                    }
                });
            }

            if (jqFormRec.length) {
                $.validator.addMethod("regex", function(value, element, regexp) {
                    return this.optional(element) || regexp.test(value);
                }, "Please check your input.");

                jqFormRec.validate({
                    rules: {},
                    messages: {},
                    submitHandler: function(form) {
                        var action = $('#rec_id').val() > 0 ? 'edit' : 'create';

                        $.blockUI({
                            message: '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>',
                            css: {
                                backgroundColor: 'transparent',
                                border: '0'
                            },
                            overlayCSS: {
                                opacity: 0.8
                            }
                        });

                        // update ajax request data
                        var formData = new FormData(form);
                        formData.append('action', action);
                        $.ajax({
                            url: "pages/receipt/function/" + action + ".php",
                            type: "POST",
                            processData: false,
                            contentType: false,
                            data: formData,
                            success: function(response) {
                                // console.log(response);
                                if (response != false && response > 0) {
                                    Swal.fire({
                                        title: "The information has been added successfully.",
                                        icon: "success",
                                    }).then(function(isConfirm) {
                                        if (isConfirm) {
                                            // location.reload(); // refresh page
                                            $('#modal-receipt').modal('hide');
                                            var serializedData = $('#invoice-search-form').serialize();
                                            $.ajax({
                                                url: "pages/invoice/function/search.php",
                                                type: "POST",
                                                data: serializedData + "&action=search",
                                                success: function(response) {
                                                    if (response != false) {
                                                        $("#invoice-search-table").html(response);
                                                    }
                                                }
                                            });
                                        }
                                    });
                                } else {
                                    Swal.fire({
                                        title: "Please try again.",
                                        icon: "error",
                                    });
                                }
                            },
                            complete: function() {
                                $.unblockUI();
                            }
                        });
                    }
                });
            }
        });

        function modal_invoice(cover_id) {
            if (cover_id > 0) {
                modal_page_invoice('edit');

                $.blockUI({
                    message: '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>',
                    css: {
                        backgroundColor: 'transparent',
                        border: '0'
                    },
                    overlayCSS: {
                        // backgroundColor: '#fff',
                        opacity: 0.8
                    }
                });

                var formData = new FormData();
                formData.append('action', 'div');
                formData.append('cover_id', cover_id);
                $.ajax({
                    url: "pages/invoice/function/invoice.php",
                    type: "POST",
                    processData: false,
                    contentType: false,
                    data: formData,
                    success: function(response) {
                        if (response != 'false') {
                            $('#div-form-invoice-edit').html(response);

                            $('#inv_date, #rec_date').flatpickr({
                                static: true,
                                altInput: true,
                                altFormat: 'j F Y',
                                dateFormat: 'Y-m-d'
                            });

                            $('#currency, #currency, #vat, #branch, #bank_account').select2();

                            $('.numeral-mask').toArray().forEach(function(field) {
                                new Cleave(field, {
                                    numeral: true,
                                    numeralThousandsGroupStyle: 'thousand'
                                });
                            });

                            calculator_price();
                        }
                    },
                    complete: function() {
                        $.unblockUI();
                    }
                });
            } else {
                modal_page_invoice('previous');
                var travel_date = document.getElementById('travel_date').value;

                $.blockUI({
                    message: '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>',
                    css: {
                        backgroundColor: 'transparent',
                        border: '0'
                    },
                    overlayCSS: {
                        // backgroundColor: '#fff',
                        opacity: 0.8
                    }
                });

                var formData = new FormData();
                formData.append('action', 'search');
                formData.append('travel_date', travel_date);
                $.ajax({
                    url: "pages/invoice/function/search-agent.php",
                    type: "POST",
                    processData: false,
                    contentType: false,
                    data: formData,
                    success: function(response) {
                        if (response != 'false') {
                            $('#div-agent').html(response);
                        } else {
                            $('#div-agent').html('');
                        }
                    },
                    complete: function() {
                        $.unblockUI();
                    }
                });
            }
        }

        function search_booking(agent_id) {
            var travel_date = document.getElementById('travel_date').value;

            $.blockUI({
                message: '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>',
                css: {
                    backgroundColor: 'transparent',
                    border: '0'
                },
                overlayCSS: {
                    // backgroundColor: '#fff',
                    opacity: 0.8
                }
            });

            var formData = new FormData();
            formData.append('action', 'search');
            formData.append('travel_date', travel_date);
            formData.append('agent_id', agent_id);
            $.ajax({
                url: "pages/invoice/function/search-booking.php",
                type: "POST",
                processData: false,
                contentType: false,
                data: formData,
                success: function(response) {
                    if (response != 'false') {
                        $('#div-booking').html(response);
                    }
                },
                complete: function() {
                    $.unblockUI();
                }
            });
        }

        function checkbox() {
            var checkbox_all = document.getElementById('checkbo_all').checked;
            var checkbox = document.getElementsByClassName('checkbox-bookings');

            if (checkbox_all == true && checkbox.length > 0) {
                for (let index = 0; index < checkbox.length; index++) {
                    checkbox[index].checked = true;
                }
            } else if (checkbox_all == false && checkbox.length > 0) {
                for (let index = 0; index < checkbox.length; index++) {
                    checkbox[index].checked = false;
                }
            }
        }

        function modal_page_invoice(type) {
            if (type == 'next') {
                var bo_id = [];
                let checked = $(".checkbox-bookings");
                for (let index = 0; index < checked.length; index++) {
                    if (checked[index].checked == true) {
                        bo_id.push(checked[index].value);
                    }
                }

                if (bo_id != '') {
                    // div hidden
                    document.getElementById('div-select-invoice').hidden = true;
                    document.getElementById('div-form-invoice').hidden = false;
                    // document.getElementById('div-form-invoice-edit').hidden = true;
                    // document.getElementById('div-preview-invoice').hidden = true;
                    // button hidden
                    document.getElementById('btn-modal-previous').hidden = false;
                    document.getElementById('btn-modal-next').hidden = true;
                    document.getElementById('btn-modal-submit').hidden = false;
                    document.getElementById('btn-modal-preview').hidden = true;
                    document.getElementById('btn-modal-preview-previous').hidden = true;
                    document.getElementById('btn-modal-print').hidden = true;
                    document.getElementById('btn-modal-image').hidden = true;
                    document.getElementById('h4-title').innerHTML = 'สร้าง Invoice';

                    var formData = new FormData();
                    formData.append('action', 'div');
                    formData.append('bo_id', JSON.stringify(bo_id));
                    $.ajax({
                        url: "pages/invoice/function/invoice.php",
                        type: "POST",
                        processData: false,
                        contentType: false,
                        data: formData,
                        success: function(response) {
                            if (response != 'false') {
                                $('#div-form-invoice').html(response);

                                $('#inv_date, #rec_date').flatpickr({
                                    static: true,
                                    altInput: true,
                                    altFormat: 'j F Y',
                                    dateFormat: 'Y-m-d'
                                });

                                $('#currency, #currency, #vat, #branch, #bank_account').select2();

                                $('.numeral-mask').toArray().forEach(function(field) {
                                    new Cleave(field, {
                                        numeral: true,
                                        numeralThousandsGroupStyle: 'thousand'
                                    });
                                });

                                calculator_price();
                            }
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: ' กรุณาเลือก Booking ที่ต้องการออก Invoice',
                        icon: 'error',
                        customClass: {
                            confirmButton: 'btn btn-primary'
                        },
                        buttonsStyling: false
                    });
                }
            } else if (type == 'previous') {
                $('#div-form-invoice').html('');
                // div hidden
                document.getElementById('div-form-invoice-edit').hidden = true;
                document.getElementById('div-preview-invoice').hidden = true;
                document.getElementById('div-select-invoice').hidden = false;
                document.getElementById('div-form-invoice').hidden = true;
                // button hidden
                document.getElementById('btn-modal-previous').hidden = true;
                document.getElementById('btn-modal-submit').hidden = true;
                document.getElementById('btn-modal-next').hidden = false;
                document.getElementById('btn-modal-preview').hidden = true;
                document.getElementById('btn-modal-preview-previous').hidden = true;
                document.getElementById('btn-modal-print').hidden = true;
                document.getElementById('btn-modal-image').hidden = true;

                document.getElementById('h4-title').innerHTML = 'เลือก Invoice';
            } else if (type == 'edit') {
                // div hidden
                document.getElementById('div-form-invoice').innerHTML = '';
                document.getElementById('div-form-invoice').hidden = true;
                document.getElementById('div-preview-invoice').hidden = true;
                document.getElementById('div-form-invoice-edit').hidden = false;
                document.getElementById('div-select-invoice').hidden = true;
                // button hidden
                document.getElementById('btn-modal-previous').hidden = true;
                document.getElementById('btn-modal-next').hidden = true;
                document.getElementById('btn-modal-preview').hidden = false;
                document.getElementById('btn-modal-submit').hidden = false;
                document.getElementById('btn-modal-preview-previous').hidden = true;
                document.getElementById('btn-modal-print').hidden = true;
                document.getElementById('btn-modal-image').hidden = true;
            } else if (type == 'preview') {
                var cover_id = document.getElementById('cover_id').value;
                if (cover_id > 0) {
                    // div hidden
                    document.getElementById('div-form-invoice').hidden = true;
                    document.getElementById('div-preview-invoice').hidden = false;
                    document.getElementById('div-form-invoice-edit').hidden = true;
                    // button hidden
                    document.getElementById('btn-modal-previous').hidden = true;
                    document.getElementById('btn-modal-next').hidden = true;
                    document.getElementById('btn-modal-submit').hidden = true;
                    document.getElementById('btn-modal-preview').hidden = true;
                    document.getElementById('btn-modal-preview-previous').hidden = false;
                    document.getElementById('btn-modal-print').hidden = false;
                    document.getElementById('btn-modal-image').hidden = false;
                    document.getElementById('btn-modal-print').href = "./?pages=invoice/print&action=preview&cover_id=" + cover_id;

                    var formData = new FormData();
                    formData.append('action', 'preview');
                    formData.append('cover_id', cover_id);
                    $.ajax({
                        url: "pages/invoice/print.php",
                        type: "POST",
                        processData: false,
                        contentType: false,
                        data: formData,
                        success: function(response) {
                            if (response != false) {
                                $("#div-preview-invoice").html(response);
                            }
                        }
                    });
                }
            } else if (type == 'receipt-preview') {
                var rec_id = document.getElementById('rec_id').value;
                document.getElementById('div-receipt-form').hidden = true;
                document.getElementById('div-receipt-preview').hidden = false;
                document.getElementById('btn-modal-receipt-preview').hidden = true;
                document.getElementById('btn-modal-receipt-submit').hidden = true;
                document.getElementById('btn-modal-receipt-previous').hidden = false;
                document.getElementById('btn-modal-receipt-print').hidden = false;
                document.getElementById('btn-modal-receipt-image').hidden = false;
                document.getElementById('btn-modal-receipt-print').href = "./?pages=receipt/print&action=invoice&rec_id=" + rec_id;

                var formData = new FormData();
                formData.append('action', 'invoice');
                formData.append('rec_id', rec_id);
                $.ajax({
                    url: "pages/receipt/print.php",
                    type: "POST",
                    processData: false,
                    contentType: false,
                    data: formData,
                    success: function(response) {
                        if (response != false) {
                            $("#div-receipt-preview").html(response);
                        }
                    }
                });
            } else if (type == 'receipt-form') {
                var rec_id = document.getElementById('rec_id').value;
                document.getElementById('div-receipt-form').hidden = false;
                document.getElementById('div-receipt-preview').hidden = true;
                document.getElementById('btn-modal-receipt-previous').hidden = true;
                document.getElementById('btn-modal-receipt-submit').hidden = false;
                document.getElementById('btn-modal-receipt-print').hidden = true;
                document.getElementById('btn-modal-receipt-image').hidden = true;
                document.getElementById('btn-modal-receipt-preview').hidden = rec_id == 0 ? true : false;
            }
        }

        function calculator_price() {
            var vat_total = 0;
            var vat_cut = 0;
            var withholding_total = 0;
            var total = 0;
            var price_amount = document.getElementById('price-amount');
            var vat = document.getElementById('vat');
            var withholding = document.getElementById('withholding');
            var price_withholding = document.getElementById('price-withholding');
            var price_total = document.getElementById('price_total');
            var tr_vat = document.getElementById('tr-vat');
            var vat_text = document.getElementById('vat-text');
            var price_vat = document.getElementById('price-vat');
            var price_total_1 = document.getElementById('price-total');
            var tr_withholding = document.getElementById('tr-withholding');
            var withholding_text = document.getElementById('withholding-text');
            var discount = document.getElementById('discount');
            var cot = document.getElementById('cot');
            var amount = document.getElementById('amount');
            tr_vat.hidden = vat.value > 0 ? false : true;
            vat_text.innerHTML = vat.value > 0 ? vat.value == 1 ? 'รวมภาษี 7%' : 'แยกภาษี 7%' : '';
            tr_withholding.hidden = withholding.value > 0 ? false : true;
            withholding_text.innerHTML = withholding.value > 0 ? 'หักภาษี ณ ที่จ่าย ' + withholding.value + ' %' : '';
            // --- sum price total --- //
            total = price_total.value;
            total = discount.value > 0 ? Number(total - discount.value) : total;
            total = cot.value > 0 ? Number(total - cot.value) : total;
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

        function check_diff_date(type) {
            var today = document.getElementById('input_today').value;
            var rec_date = document.getElementById(type).value;
            const date1 = new Date(today);
            const date2 = new Date(rec_date);
            const diffTime = date2.getTime() - date1.getTime();
            const diffDays = diffTime / (1000 * 60 * 60 * 24);
            var days = diffDays <= 0 ? '' : "อีก " + diffDays + " วัน";
            $('#diff_rec_date').html(days);
        }

        function custom_day() {
            var search_period = document.getElementById('search_period').value;
            var days = '';
            switch (search_period) {
                case 'today':
                    days = ['<?php echo $today; ?>'];
                    break;
                case 'yesterday':
                    days = ['<?php echo $yesterday; ?>'];
                    break;
                case 'last7days':
                    days = [<?php echo "'$day7', '$yesterday'"; ?>];
                    break;
                case 'last15days':
                    days = [<?php echo "'$day15', '$yesterday'"; ?>];
                    break;
                case 'last30days':
                    days = [<?php echo "'$day30', '$yesterday'"; ?>];
                    break;
                case 'custom':
                    document.getElementById('div_search_travel').hidden = false;
                    break;
                default:
                    document.getElementById('div_search_travel').hidden = true;
                    break;
            }

            $('#search_travel').flatpickr({
                onReady: function(selectedDates, dateStr, instance) {
                    if (instance.isMobile) {
                        $(instance.mobileInput).attr('step', null);
                    }
                },
                mode: 'range',
                static: true,
                altInput: true,
                altFormat: 'j F Y',
                dateFormat: 'Y-m-d',
                defaultDate: days
            });
        }

        // Script Function Receipt
        // ------------------------------------------------------------------------------------
        function modal_receipt(cover_id, agent_id) {
            $('#modal-show').modal('toggle');
            $('#modal-add-receipt').modal('show');
            $("#modal-add-receipt").css({
                "overflow-y": "auto"
            });

            $.blockUI({
                message: '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>',
                css: {
                    backgroundColor: 'transparent',
                    border: '0'
                },
                overlayCSS: {
                    opacity: 0.8
                }
            });

            var formData = new FormData();
            formData.append('action', 'modal');
            formData.append('cover_id', cover_id);
            formData.append('agent_id', agent_id);
            $.ajax({
                url: "pages/invoice/function/receipt.php",
                type: "POST",
                processData: false,
                contentType: false,
                data: formData,
                success: function(response) {
                    if (response != 'false') {
                        $('#div-receipt-form').html(response);

                        $('#rec_date_2').flatpickr({
                            static: true,
                            altInput: true,
                            altFormat: 'j F Y',
                            dateFormat: 'Y-m-d'
                        });

                        $('#payments_type, #bank_account, #rec_bank').select2();

                        check_payment();
                        modal_page_invoice('receipt-form');
                    }
                },
                complete: function() {
                    $.unblockUI();
                }
            });
        }

        function check_payment() {
            var payments_type = document.getElementById('payments_type').value;
            document.getElementById('div-bank-account-2').hidden = payments_type == 4 ? false : true;
            document.getElementById('div-bank').hidden = payments_type == 5 ? false : true;
            document.getElementById('div-check-no').hidden = payments_type == 5 ? false : true;
            document.getElementById('div-check-date').hidden = payments_type == 5 ? false : true;

            if (payments_type == 5) {
                $('#date_check').flatpickr({
                    static: true,
                    altInput: true,
                    altFormat: 'j F Y',
                    dateFormat: 'Y-m-d'
                });
            }
        }

        function download_image(div) {
            var img_name = document.getElementById('name_img').value;
            var node = document.getElementById(div + '-preview-vertical');
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
    </script>

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