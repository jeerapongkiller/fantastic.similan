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
    <!-- END: Theme JS-->

    <?php
    $columntarget = $_SESSION["supplier"]["role_id"] == 1 || $_SESSION["supplier"]["role_id"] == 2 ? '4, 5' : '4, 5';
    ?>

    <!-- BEGIN: Page JS-->
    <script type="text/javascript">
        // Ajax Delete Receipt
        // --------------------------------------------------------------------
        function deleteReceipt() {
            var rec_id = $('#rec_id').val();
            var res_bo = JSON.parse($('#rec' + rec_id).attr('data-bo_id'));
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
                        url: "pages/receipt/function/delete.php",
                        type: "POST",
                        data: {
                            rec_id: rec_id,
                            res_bo: res_bo,
                            action: 'delete'
                        },
                        success: function(response) {
                            // console.log(response);
                            if (response != false) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: 'Your item has been deleted.',
                                    customClass: {
                                        confirmButton: 'btn btn-success'
                                    }
                                }).then(function() {
                                    location.reload(); // refresh page
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

            $('#search_rec_form').change(function() {
                $('#search_rec_to').flatpickr({
                    onReady: function(selectedDates, dateStr, instance) {
                        if (instance.isMobile) {
                            $(instance.mobileInput).attr('step', null);
                        }
                    },
                    static: true,
                    altInput: true,
                    altFormat: 'j F Y',
                    dateFormat: 'Y-m-d',
                    defaultDate: $('#search_rec_form').val()
                });
            });

            // Ajax Search
            // --------------------------------------------------------------------
            jqForm.on("submit", function(e) {
                var serializedData = $(this).serialize();
                $.ajax({
                    url: "pages/receipt/function/search-list.php",
                    type: "POST",
                    data: serializedData + "&action=search",
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
                            $("#receipt-search-table").html(response);
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
                        'name': {
                            required: true
                        }
                    },
                    messages: {},
                    submitHandler: function(form) {
                        // update ajax request data
                        var rec_id = $('#rec_id').val();
                        var formData = new FormData(form);
                        formData.append('action', 'edit');
                        formData.append('rec_id', rec_id);
                        $.ajax({
                            url: "pages/receipt/function/edit.php",
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

        function fun_search_period() {
            var search_period = document.getElementById('search_period').value;
            document.getElementById('div-form').hidden = search_period == 'custom' ? false : true;
            document.getElementById('div-to').hidden = search_period == 'custom' ? false : true;

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

            $('#search_rec_form').flatpickr({
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

            $('#search_rec_to').flatpickr({
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

        function modal_show_receipt(rec_id) {
            var formData = new FormData();
            formData.append('action', 'preview');
            formData.append('rec_id', rec_id);
            $.ajax({
                url: "pages/receipt/print.php",
                type: "POST",
                processData: false,
                contentType: false,
                data: formData,
                success: function(response) {
                    if (response != false) {
                        $("#div-show-receipt").html(response);
                    }
                }
            });
        }

        function modal_edit_receipt(rec_id) {
            $('#modal-show-receipt').modal('toggle');
            $('#modal-add-receipt').modal('show');
            $("#modal-add-receipt").css({
                "overflow-y": "auto"
            });

            var res_inv = JSON.parse($('#rec' + rec_id).attr('data-inv_id'));
            var res_inv_no = JSON.parse($('#rec' + rec_id).attr('data-inv_no'));
            var res_voucher = JSON.parse($('#rec' + rec_id).attr('data-voucher'));
            var res_product_name = JSON.parse($('#rec' + rec_id).attr('data-product_name'));
            var res_bo_full = JSON.parse($('#rec' + rec_id).attr('data-bo_full'));
            var res_travel_date = JSON.parse($('#rec' + rec_id).attr('data-travel_date'));
            var res_payment_id = JSON.parse($('#rec' + rec_id).attr('data-payment_id'));
            var res_payment_paid = JSON.parse($('#rec' + rec_id).attr('data-payment_paid'));
            var res_discount = JSON.parse($('#rec' + rec_id).attr('data-discount'));
            var res_total = JSON.parse($('#rec' + rec_id).attr('data-total'));
            var rec_date = $('#rec' + rec_id).attr('data-rec_date');
            var cheque_date = $('#rec' + rec_id).attr('data-cheque_date');
            var payment = $('#rec' + rec_id).attr('data-payment');
            var bank = $('#rec' + rec_id).attr('data-bank');
            var banacc = $('#rec' + rec_id).attr('data-banacc');
            var vat = $('#rec' + rec_id).attr('data-vat');
            var vat_name = $('#rec' + rec_id).attr('data-vat_name');
            var withholding = $('#rec' + rec_id).attr('data-withholding');
            document.getElementById('branch_text').innerHTML = $('#rec' + rec_id).attr('data-branche');
            document.getElementById('payments_type').value = payment;
            document.getElementById('rec_bank').value = bank;
            document.getElementById('bank_account').value = banacc;
            document.getElementById('check_no').value = $('#rec' + rec_id).attr('data-cheque_no');
            document.getElementById('note').value = $('#rec' + rec_id).attr('data-note');
            document.getElementById('rec_id').value = rec_id;
            document.getElementById('agent_name_text').innerHTML = $('#rec' + rec_id).attr('data-agent_name');
            document.getElementById('agent_address_text').innerHTML = $('#rec' + rec_id).attr('data-agent_address');
            document.getElementById('agent_tel_text').innerHTML = $('#rec' + rec_id).attr('data-agent_telephone');
            document.getElementById('agent_tax_text').innerHTML = $('#rec' + rec_id).attr('data-agent_tat');
            var text_html = '';
            var no = 1;
            var vat_total = 0;
            var vat_cut = 0;
            var withholding_total = 0;
            var total = 0;
            var discount = 0;
            var cot = 0;
            var dep = 0;
            if (rec_id > 0 && res_inv.length > 0) {
                for (let index = 0; index < res_inv.length; index++) {
                    var inv_full = res_inv_no[index] > 0 ? $('#rec' + rec_id).attr('data-inv_full') + '/' + res_inv_no[index] : $('#rec' + rec_id).attr('data-inv_full');
                    text_html += '<tr>' +
                        '<td class="text-center">' + Number(no++) + '</td>' +
                        '<td class="text-center"> ' + inv_full + ' </td>' +
                        '<td class="text-center"> ' + res_voucher[index] + ' </td>' +
                        '<td class="text-center"> ' + res_bo_full[index] + ' </td>' +
                        '<td class="text-center"> ' + res_product_name[index] + ' </td>' +
                        '<td class="text-center"> ' + res_travel_date[index] + ' </td>' +
                        '<td class="text-center"> ' + numberWithCommas(Number(res_total[index])) + ' </td>' +
                        '</tr>';
                    total = total + Number(res_total[index]);
                    discount = discount + res_discount[index];
                    cot = (res_payment_id[index] == 4) ? cot + res_payment_paid[index] : cot;
                    dep = (res_payment_id[index] == 5) ? dep + res_payment_paid[index] : dep;
                }
            }

            text_html += '<tr>' +
                '<td colspan="5"></td>' +
                '<td class="text-center"><b>รวมเป็นเงิน</b><br><small>(Total)</small></td>' +
                '<td class="text-center">' + numberWithCommas(total) + '</td>' +
                '</tr>'

            total = (discount > 0) ? Number(total - discount) : total;
            total = (cot > 0) ? Number(total - cot) : total;
            total = (dep > 0) ? Number(total - dep) : total;
            if (vat == 1) {
                vat_total = Number(((total * 100) / 107));
                vat_cut = vat_total;
                vat_total = Number(total - vat_total);
                withholding_total = withholding > 0 ? Number((vat_cut * withholding) / 100) : 0;
                total = Number(total - withholding_total);
            } else if (vat == 2) {
                vat_total = Number(((total * 7) / 100));
                total = Number(total) + Number(vat_total);
                withholding_total = withholding > 0 ? Number(((total - vat_total) * withholding) / 100) : 0;
                total = Number(total - withholding_total);
            }

            if (discount > 0) {
                text_html += '<tr>' +
                    '<td colspan="5"></td>' +
                    '<td class="text-center"><b>ส่วนลด</b><br><small>(Discount)</small></td>' +
                    '<td class="text-center">' + numberWithCommas(discount) + '</td>' +
                    '</tr>';
            }

            if (cot > 0) {
                text_html += '<tr>' +
                    '<td colspan="5"></td>' +
                    '<td class="text-center"><b>Cash on tour</b></td>' +
                    '<td class="text-center">' + numberWithCommas(cot) + '</td>' +
                    '</tr>';
            }

            if (dep > 0) {
                text_html += '<tr>' +
                    '<td colspan="5"></td>' +
                    '<td class="text-center"><b>Deposit</b></td>' +
                    '<td class="text-center">' + numberWithCommas(dep) + '</td>' +
                    '</tr>';
            }

            if (vat != 0) {
                text_html += '<tr>' +
                    '<td colspan="5"></td>' +
                    '<td class="text-center"><b>' + vat_name + '</b><br><small>(Vat)</small></td>' +
                    '<td class="text-center">' + Number(vat_total).toLocaleString("en-US", {
                        maximumFractionDigits: 2
                    }) + '</td>' +
                    '</tr>';
            }

            if (withholding != 0) {
                text_html += '<tr>' +
                    '<td colspan="5"></td>' +
                    '<td class="text-center"><b>หัก ณ ที่จ่าย (' + withholding + '%)</b><br><small>(Withholding Tax)</small></td>' +
                    '<td class="text-center">' + numberWithCommas(withholding_total) + '</td>' +
                    '</tr>';
            }

            text_html += '<tr>' +
                '<td colspan="5"></td>' +
                '<td class="text-center"><b>ยอดชำระ</b><br><small>(Payment Amount)</small></td>' +
                '<td class="text-center" id="price-amount">' + numberWithCommas(total) + '</td>' +
                '</tr>';

            $('#tbody-invoice').html(text_html);

            document.getElementById('price_total').value = total;

            $('#payments_type').each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>');
                $this
                    .select2({
                        placeholder: 'Select ...',
                        dropdownParent: $this.parent(),
                        'val': payment
                    })
                    .change(function() {
                        $(this).valid();
                    })
            });

            $('#rec_bank').each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>');
                $this
                    .select2({
                        placeholder: 'Select ...',
                        dropdownParent: $this.parent(),
                        'val': bank
                    })
                    .change(function() {
                        $(this).valid();
                    })
            });

            $('#bank_account').each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>');
                $this
                    .select2({
                        placeholder: 'Select ...',
                        dropdownParent: $this.parent(),
                        'val': banacc
                    })
                    .change(function() {
                        $(this).valid();
                    })
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
                defaultDate: rec_date
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
                defaultDate: cheque_date
            });

            check_payment();
        }

        function check_payment() {
            var payments_type = document.getElementById('payments_type').value;
            document.getElementById('div-bank-account').hidden = payments_type == 4 ? false : true;
            document.getElementById('div-bank').hidden = payments_type == 5 ? false : true;
            document.getElementById('div-check-no').hidden = payments_type == 5 ? false : true;
            document.getElementById('div-check-date').hidden = payments_type == 5 ? false : true;
        }

        function download_image() {
            var img_name = document.getElementById('name_img').value;
            var node = document.getElementById('receipt-preview-vertical');
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