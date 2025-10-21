<?php
if (isset($_SESSION["supplier"]["id"]) && $_SESSION["supplier"]["role_id"] > 2) {
    echo $_GET['id'] != $_SESSION["supplier"]["id"] ? header('location:./?pages=car/list') : '';
}
?>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
    <meta name="description" content="<?php echo $main_description; ?>">
    <meta name="keywords" content="<?php echo $main_keywords; ?>">
    <meta name="author" content="<?php echo $main_author; ?>">
    <title>Quotation - <?php echo $main_title; ?></title>
    <link rel="apple-touch-icon" href="app-assets/images/ico/favicon.png">
    <link rel="shortcut icon" type="image/x-icon" href="app-assets/images/ico/favicon.png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="app-assets/vendors/css/vendors.min.css">
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
    <link rel="stylesheet" type="text/css" href="app-assets/css/pages/app-invoice.css">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <!-- END: Custom CSS-->

    <style>
        .form-control[readonly] {
            background-color: #FFF;
            opacity: 1;
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
    <script src="app-assets/vendors/js/forms/repeater/jquery.repeater.min.js"></script>
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
    <script src="app-assets/js/scripts/node_modules/dom-to-image/src/dom-to-image.js"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <script type="text/javascript">
        // Ajax Delete Quotation Type
        // --------------------------------------------------------------------
        function deleteQuotation(quotation_id) {
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
                        url: "pages/quotation/function/delete.php",
                        type: "POST",
                        data: {
                            quotation_id: quotation_id,
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
                                    location.href = './?pages=quotation/list';
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
            var jqForm = $('#quotation-edit-form'),
                picker = $('.date-picker'),
                dtPicker = $('#dob-bootstrap-val'),
                selectLg = $('.select2-size-lg'),
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

            // Large
            selectLg.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative w-100"></div>');
                $this.select2({
                    dropdownAutoWidth: true,
                    dropdownParent: $this.parent(),
                    width: '100%',
                    containerCssClass: 'select-lg'
                });
            });

            // Picker
            if (picker.length) {
                picker.flatpickr({
                    onReady: function(selectedDates, dateStr, instance) {
                        if (instance.isMobile) {
                            $(instance.mobileInput).attr('step', null);
                        }
                    },
                    static: false,
                    altInput: true,
                    altFormat: 'j F Y',
                    dateFormat: 'Y-m-d'
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
            $('.invoice-product-details').repeater({
                show: function() {
                    $(this).slideDown();

                    $('.numeral-mask').toArray().forEach(function(field) {
                        new Cleave(field, {
                            numeral: true,
                            numeralThousandsGroupStyle: 'thousand'
                        });
                    });

                    updateNumbers();
                    calculateTotals();

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

                    setTimeout(updateNumbers, 600);
                    setTimeout(calculateTotals, 600);
                },
                ready: function() {
                    // Init select2
                    $('.numeral-mask').toArray().forEach(function(field) {
                        new Cleave(field, {
                            numeral: true,
                            numeralThousandsGroupStyle: 'thousand'
                        });
                    });
                },
                isFirstItemUndeletable: true
            });


            // jQuery Validation
            // --------------------------------------------------------------------
            if (jqForm.length) {
                $.validator.addMethod("regex", function(value, element, regexp) {
                    return this.optional(element) || regexp.test(value);
                }, "Please check your input.");

                jqForm.validate({
                    rules: {
                        'cus_name': {
                            required: true,
                        },
                    },
                    messages: {
                        'name': {
                            remote: "This name is already taken! Try another."
                        },
                        'registration': {
                            remote: "This car registration is already taken! Try another."
                        }
                    },
                    submitHandler: function(form) {
                        // update ajax request data
                        var serializedData = $(form).serialize();
                        $.ajax({
                            url: "pages/quotation/function/edit.php",
                            type: "POST",
                            data: serializedData + "&action=edit",
                            success: function(response) {
                                // console.log(response);
                                if (response == true) {
                                    Swal.fire({
                                        title: "The information has been updated.",
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

            updateNumbers();
            calculateTotals();
        });


        // --- ฟังก์ชันเลขไทยเป็นข้อความ ---
        function numberToThaiText(num) {
            num = parseFloat(num).toFixed(2);
            let number = num.toString();
            let [integerPart, decimalPart] = number.split(".");

            let txtnum1 = ["ศูนย์", "หนึ่ง", "สอง", "สาม", "สี่", "ห้า", "หก", "เจ็ด", "แปด", "เก้า"];
            let txtnum2 = ["", "สิบ", "ร้อย", "พัน", "หมื่น", "แสน", "ล้าน"];

            function convertInteger(n) {
                let result = "";
                let len = n.length;
                for (let i = 0; i < len; i++) {
                    let num = parseInt(n.charAt(i));
                    if (num !== 0) {
                        if (i === len - 1 && num === 1 && len > 1) {
                            result += "เอ็ด";
                        } else if (i === len - 2 && num === 2) {
                            result += "ยี่";
                        } else if (i === len - 2 && num === 1) {
                            result += "";
                        } else {
                            result += txtnum1[num];
                        }
                        result += txtnum2[len - i - 1];
                    }
                }
                return result;
            }

            let bahtText = convertInteger(integerPart) + "บาท";
            if (decimalPart === "00") {
                bahtText += "ถ้วน";
            } else {
                bahtText += convertInteger(decimalPart) + "สตางค์";
            }
            return bahtText;
        }

        // --- ฟังก์ชันอัปเดตหมายเลข ---
        function updateNumbers() {
            const items = $('[data-repeater-list="group-a"] > [data-repeater-item]:visible');
            items.each(function(index) {
                $(this).find('.number').text(index + 1);
            });
        }

        // --- ฟังก์ชันคำนวณรวมแต่ละแถว + รวมทั้งหมด ---
        function calculateTotals() {
            let total = 0;
            $('.repeater-wrapper').each(function() {
                let qty = parseFloat($(this).find('.qty-input').val().replace(/,/g, "")) || 0;
                let cost = parseFloat($(this).find('.cost-input').val().replace(/,/g, "")) || 0;
                let discount = parseFloat($(this).find('.discount-input').val().replace(/,/g, "")) || 0;
                let sum = (qty * cost) - discount;

                $(this).find('.sum-price').text(sum.toLocaleString());
                total += sum;
            });

            $('.invoice-total-amount').text(total.toLocaleString());
            $('#bahtText').text("(" + numberToThaiText(total) + ")");
        }

        // --- Event: คำนวณทุกครั้งเมื่อมีการเปลี่ยนค่า ---
        $(document).on('input', '.qty-input, .cost-input, .discount-input', function() {
            calculateTotals();
        });

        function print(id) {
            var formData = new FormData();
            formData.append('action', 'preview');
            formData.append('id', id);
            $.ajax({
                url: "pages/quotation/print.php",
                type: "POST",
                processData: false,
                contentType: false,
                data: formData,
                success: function(response) {
                    $('#show-div-print').html(response);
                }
            });
        }

        function download_image() {
            var img_name = document.getElementById('name_img').value;
            var node = document.getElementById('invoice-preview-wrapper');
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