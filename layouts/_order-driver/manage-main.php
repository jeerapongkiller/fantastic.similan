<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
    <meta name="description" content="<?php echo $main_description; ?>">
    <meta name="keywords" content="<?php echo $main_keywords; ?>">
    <meta name="author" content="<?php echo $main_author; ?>">
    <title>Management Transfer - <?php echo $main_title; ?></title>
    <link rel="apple-touch-icon" href="app-assets/images/ico/favicon.png">
    <link rel="shortcut icon" type="image/x-icon" href="app-assets/images/ico/favicon.png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="app-assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="app-assets/vendors/css/extensions/dragula.min.css">
    <link rel="stylesheet" type="text/css" href="app-assets/vendors/css/forms/wizard/bs-stepper.min.css">
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
    <link rel="stylesheet" type="text/css" href="app-assets/css/plugins/forms/pickers/form-flat-pickr.css">
    <link rel="stylesheet" type="text/css" href="app-assets/css/pages/app-user.css">
    <link rel="stylesheet" type="text/css" href="app-assets/css/plugins/forms/form-wizard.css">
    <link rel="stylesheet" type="text/css" href="app-assets/css/core/menu/menu-types/vertical-menu.css">
    <link rel="stylesheet" type="text/css" href="app-assets/css/plugins/extensions/ext-component-drag-drop.css">
    <link rel="stylesheet" type="text/css" href="app-assets/fonts/fontawesome/css/all.css" rel="stylesheet">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <!-- END: Custom CSS-->

    <style>
        .list-group-flush>.list-group-item {
            border-width: 0;
        }

        .list-group-item {
            padding: 0;
        }

        .table th,
        .table td {
            padding: 0.72rem 1rem;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #6E6B7B;
        }

        .table thead th {
            border-bottom: 1px solid #6E6B7B;
        }

        .table .thead-light th {
            color: #5E5873;
            background-color: #F3F2F7;
            border-color: #6E6B7B;
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
    <script src="app-assets/js/scripts/node_modules/dom-to-image/src/dom-to-image.js"></script>
    <script src="app-assets/fonts/fontawesome/js/all.js"></script>
    <script src="app-assets/vendors/js/forms/wizard/bs-stepper.min.js"></script>
    <script src="app-assets/vendors/js/forms/cleave/cleave.min.js"></script>
    <script src="app-assets/vendors/js/forms/cleave/addons/cleave-phone.us.js"></script>
    <script src="app-assets/vendors/js/extensions/dragula.min.js"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN Sweetalert2 JS -->
    <script src="app-assets/vendors/js/extensions/polyfill.min.js"></script>
    <script src="app-assets/vendors/js/extensions/sweetalert2.all.min.js"></script>
    <!-- END Sweetalert2 JS -->

    <!-- BEGIN: Theme JS-->
    <script src="app-assets/js/core/app-menu.js"></script>
    <script src="app-assets/js/core/app.js"></script>
    <script src="app-assets/js/scripts/header.js"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <script type="text/javascript">
        $(document).ready(function() {
            var jqFormTransfer = $('#transfer-form'),
                jqFormDriver = $('#driver-car-search-form'),
                FormEdTransfer = $('#edit-manage-form'),
                jqSearch = $('#manages-search-form'),
                picker = $('#dob'),
                DatePicker = $('.date-picker'),
                dtPicker = $('#dob-bootstrap-val'),
                select = $('.select2'),
                pageBlockSpinner = $('.btn-page-block-spinner'),
                horizontalWizard = document.querySelector('.horizontal-wizard-example');

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

            // dragula([document.getElementById('basic-list-group')]);


            //Numeral
            $('.numeral-mask').toArray().forEach(function(field) {
                new Cleave(field, {
                    numeral: true,
                    numeralThousandsGroupStyle: 'thousand'
                });
            });

            // Time
            $('.time-mask').toArray().forEach(function(field) {
                new Cleave(field, {
                    time: true,
                    timePattern: ['h', 'm']
                });
            });

            if (DatePicker.length) {
                DatePicker.flatpickr({
                    onReady: function(selectedDates, dateStr, instance) {
                        if (instance.isMobile) {
                            $(instance.mobileInput).attr('step', null);
                        }
                    },
                    // static: true,
                    altInput: true,
                    altFormat: 'j F Y',
                    dateFormat: 'Y-m-d'
                });
            }

            // select2
            if (select.length) {
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
            }

            if (typeof horizontalWizard !== undefined && horizontalWizard !== null) {
                var horizontaStepper = new Stepper(horizontalWizard, {
                    linear: false
                });
                $(horizontalWizard)
                    .find('.btn-next')
                    .on('click', function() {
                        horizontaStepper.next();
                    });
                $(horizontalWizard)
                    .find('.btn-prev')
                    .on('click', function() {
                        horizontaStepper.previous();
                    });

                $(horizontalWizard)
                // .find('.btn-submit')
                // .on('click', function () {
                //     alert('Submitted..!!');
                // });

                horizontaStepper.to(<?php echo !empty($_GET['search_retrun']) ? $_GET['search_retrun'] : 1; ?>);
                // console.log(horizontaStepper);
                // horizontalWizard.addEventListener('shown.bs-stepper', function(event) {
                //     console.log('Moved to step ' + event.detail.indexStep)
                // })
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

            // Ajax Search
            // --------------------------------------------------------------------
            // jqSearch.on("submit", function(e) {
            //     var serializedData = $(this).serialize();
            //     $.ajax({
            //         url: "pages/order-driver/function/search.php",
            //         type: "POST",
            //         data: serializedData + "&action=search",
            //         success: function(response) {
            //             if (response != false) {
            //                 $('#div-manage-list').html(response);
            //             } else {
            //                 Swal.fire({
            //                     title: "Please try again.",
            //                     icon: "error",
            //                 });
            //             }
            //         }
            //     });
            //     e.preventDefault();
            // });

            FormEdTransfer.on("submit", function(e) {
                var serializedData = $(this).serialize();
                $.ajax({
                    url: "pages/order-driver/function/edit-manage-transfer.php",
                    type: "POST",
                    data: serializedData + "&action=edit",
                    success: function(response) {
                        // console.log(response);
                        if (response != false && response > 0) {
                            location.reload(); // refresh page
                        } else {
                            Swal.fire({
                                title: "Please try again.",
                                icon: "error",
                            });
                        }
                    }
                });
                e.preventDefault();
            });

            jqFormTransfer.on("submit", function(e) {
                var action = ($('#manage_id').val() !== '' && $('#manage_id').val() > 0) ? 'edit' : 'create';
                var serializedData = $(this).serialize();
                $.ajax({
                    url: "pages/order-driver/function/" + action + "-transfer.php",
                    type: "POST",
                    data: serializedData + "&action=" + action + "&travel_date=" + $('#search_travel_date').val() + "&return=" + $('#return').val(),
                    success: function(response) {
                        // console.log(response);
                        if (action === 'create' && response != false) {
                            location.reload(); // refresh page
                        } else if (action === 'edit' && response != false) {
                            location.reload(); // refresh page
                        } else {
                            Swal.fire({
                                title: "Please try again.",
                                icon: "error",
                            });
                        }
                    }
                });
                e.preventDefault();
            });

            search_start_date('today', '<?php echo $today; ?>');
            search_start_date('tomorrow', '<?php echo $tomorrow; ?>');
            search_start_date('custom', '<?php echo $get_date; ?>');
        });

        function search_start_date(type, date) {
            var formData = new FormData();
            formData.append('action', 'search');
            formData.append('type', type);
            formData.append('date', date);
            $.ajax({
                url: "pages/order-driver/function/search-report.php",
                type: "POST",
                processData: false,
                contentType: false,
                data: formData,
                success: function(response) {
                    if (response != 'false') {
                        $('#' + type).html(response);
                    }
                }
            });
        }

        function checkbox(type, programe_id) {
            var checkbox_all = type == 'booking' ? document.getElementById('checkbo_all' + programe_id).checked : document.getElementById('checkmanage_all').checked;
            var checkbox = type == 'booking' ? document.getElementsByClassName('checkbox-' + programe_id) : document.getElementsByClassName('checkbox-manage');

            if (checkbox_all == true && checkbox.length > 0) {
                for (let index = 0; index < checkbox.length; index++) {
                    checkbox[index].checked = true;
                }
            } else if (checkbox_all == false && checkbox.length > 0) {
                for (let index = 0; index < checkbox.length; index++) {
                    checkbox[index].checked = false;
                }
            }

            sum_checkbox();
        }

        function check_max_tourist(bt_id, other, max) {
            var manage = document.getElementById('toc-manage' + bt_id).value;
            document.getElementById('span' + bt_id).innerHTML = Number(max) - (Number(manage) + Number(other));
            document.getElementById('span' + bt_id).classList = ((Number(manage) + Number(other)) - Number(max) > 0) ? 'text-danger' : 'text-success';
        }

        function sum_checkbox() {
            var bookings = document.getElementsByClassName('checkbox-book');
            var manage = document.getElementsByClassName('checkbox-manage');
            var sum_true = 0;
            var sum_false = 0;
            var sum_toc_true = 0;
            var sum_toc_false = 0;
            var toc = 0;
            if (bookings.length > 0) {
                for (let index = 0; index < bookings.length; index++) {
                    toc = document.getElementById('tourist' + bookings[index].value).value;
                    if (bookings[index].checked == true) {
                        sum_true = Number(sum_true + 1);
                        sum_toc_true = Number(sum_toc_true) + Number(toc);
                    } else {
                        sum_false++;
                        sum_toc_false = Number(sum_toc_false) + Number(toc);
                    }
                }
            }
            if (manage.length > 0) {
                for (let index = 0; index < manage.length; index++) {
                    toc = document.getElementById('toc-manage' + manage[index].value).value;
                    if (manage[index].checked == true) {
                        sum_true = Number(sum_true + 1);
                        sum_toc_true = Number(sum_toc_true) + Number(toc);
                    } else {
                        sum_false++;
                        sum_toc_false = Number(sum_toc_false) + Number(toc);
                    }
                }
            }

            document.getElementById('booking-true').innerHTML = sum_true;
            document.getElementById('booking-false').innerHTML = sum_false;
            document.getElementById('toc-true').innerHTML = sum_toc_true;
            document.getElementById('toc-false').innerHTML = sum_toc_false;
        }

        function modal_transfer(travel_date, manage_id) {
            document.getElementById('text-travel-date').innerHTML = '<b>' + travel_date + '</b>';
            if (manage_id > 0) {
                var arr_mange = document.getElementById('arr_mange' + manage_id).value;
                var res = $.parseJSON(arr_mange);

                $("#driver").val(res.driver_id).trigger("change");

                document.getElementById('manage_id').value = res.id;
                document.getElementById('car').value = res.car_id;
                document.getElementById('driver').value = res.driver_id;
                document.getElementById('seat').value = res.seat;
                document.getElementById('license').value = res.license;
                document.getElementById('telephone').value = res.telephone;
                document.getElementById('note').value = res.note;
                document.getElementById('outside_driver').value = res.driver_id == 0 ? res.driver_name : '';

                $("#car").val(res.car_id).trigger("change");
                $("#seat").val(res.seat).trigger("change");
                document.getElementById('delete_manage').disabled = false;
            } else {
                $("#driver").val(0).trigger("change");
                $("#car").val(0).trigger("change");
                $("#seat").val(0).trigger("change");
                document.getElementById('delete_manage').disabled = true;
                document.getElementById('transfer-form').reset();
            }
        }

        function check_outside(type) {
            if (type == 'driver') {
                var driver = document.getElementById('driver').value
                document.getElementById('frm-driver').hidden = driver == 'outside' ? true : false;
                document.getElementById('frm-driver-outside').hidden = driver == 'outside' ? false : true;
            } else if (typeof type !== undefined && type == 'outside_driver') {
                document.getElementById('frm-driver-outside').hidden = true;
                document.getElementById('frm-driver').hidden = false;
            }
            if (type == 'car') {
                var car = document.getElementById('car').value
                document.getElementById('frm-car').hidden = car == 'outside' ? true : false;
                document.getElementById('frm-car-outside').hidden = car == 'outside' ? false : true;
            } else if (typeof type !== undefined && type == 'outside_car') {
                document.getElementById('frm-car-outside').hidden = true;
                document.getElementById('frm-car').hidden = false;
            }
            if (type == 'guide') {
                var guide = document.getElementById('guide').value
                document.getElementById('frm-guide').hidden = guide == 'outside' ? true : false;
                document.getElementById('frm-guide-outside').hidden = guide == 'outside' ? false : true;
            }
        }

        function check_driver() {
            var driver = document.getElementById('driver');

            document.getElementById('frm-driver').hidden = driver.value == 'outside' ? true : false;
            document.getElementById('frm-driver-outside').hidden = driver.value == 'outside' ? false : true;

            if (driver.value !== 'outside') {
                document.getElementById('license').value = driver.options[driver.selectedIndex].getAttribute("data-license");
                document.getElementById('seat').value = driver.options[driver.selectedIndex].getAttribute("data-seat");
                document.getElementById('telephone').value = driver.options[driver.selectedIndex].getAttribute("data-telephone");
                if (driver.options[driver.selectedIndex].getAttribute("data-seat") > 0) {
                    $("#seat").val(driver.options[driver.selectedIndex].getAttribute("data-seat")).trigger("change");
                }
            }
        }

        function search_booking(manage_id, driver, car, seat) {
            // get data
            var formData = new FormData();
            formData.append('action', 'search');
            formData.append('manage_id', manage_id);
            formData.append('search_status', $('#search_status').val());
            formData.append('search_agent', $('#search_agent').val());
            formData.append('search_product', $('#search_product').val());
            formData.append('search_car', $('#search_car').val());
            formData.append('search_travel_date', $('#search_travel_date').val());
            formData.append('refcode', $('#refcode').val());
            formData.append('voucher_no', $('#voucher_no').val());
            formData.append('name', $('#name').val());
            formData.append('hotel', $('#hotel').val());
            formData.append('driver', driver);
            formData.append('car', car);
            formData.append('seat', (seat !== undefined) ? seat : 0);
            $.ajax({
                url: "pages/order-driver/function/search-manage-booking.php",
                type: "POST",
                processData: false,
                contentType: false,
                data: formData,
                success: function(response) {
                    $('#div-manage-boooking').html(response);

                    sum_checkbox();

                    $('.numeral-mask').toArray().forEach(function(field) {
                        new Cleave(field, {
                            numeral: true,
                            numeralThousandsGroupStyle: 'thousand'
                        });
                    });

                    var tbody = document.querySelector('#list-group tbody');
                    // สร้าง Dragula instance
                    if (tbody) {
                        dragula([tbody], {
                            moves: function(el, container, handle) {
                                return handle.tagName.toLowerCase() === 'td'; // กำหนดให้ลากได้เฉพาะที่ td
                            }
                        }).on('drop', function(el, target, source, sibling) {
                            // console.log('Dropped element:', el);
                        });
                    }

                    // if (type !== 'next') {
                    //     var tbody = document.querySelector('#list-group tbody');
                    //     // สร้าง Dragula instance
                    //     if (tbody) {
                    //         dragula([tbody], {
                    //             moves: function(el, container, handle) {
                    //                 return handle.tagName.toLowerCase() === 'td'; // กำหนดให้ลากได้เฉพาะที่ td
                    //             }
                    //         }).on('drop', function(el, target, source, sibling) {
                    //             // console.log('Dropped element:', el);
                    //         });
                    //     }
                    // }
                }
            });
        }

        function submit_booking_manage(manage_id) {
            var bt_id = document.getElementsByName('bt_id[]');
            var tourist = document.getElementsByName('tourist[]');
            var manage_bt = (document.getElementsByName('manage_bt[]')) ? document.getElementsByName('manage_bt[]') : [];
            var manage_tourist = (document.getElementsByName('manage_tourist[]')) ? document.getElementsByName('manage_tourist[]') : [];
            var before_manage = (document.getElementsByName('before_manage[]')) ? document.getElementsByName('before_manage[]') : [];
            var bt_array = [];
            var tourist_array = [];
            var manage_array = [];
            var manage_tourist_array = [];
            var before_array = [];
            var after_array = [];
            if (bt_id) {
                for (let index = 0; index < bt_id.length; index++) {
                    if (bt_id[index].checked == true) {
                        bt_array.push(bt_id[index].value);
                        tourist_array.push(tourist[index].value);
                    }
                }
            }
            if (before_manage) {
                for (let index = 0; index < before_manage.length; index++) {
                    before_array.push(before_manage[index].value);
                }
            }
            if (manage_bt) {
                for (let index = 0; index < manage_bt.length; index++) {
                    if (manage_bt[index].checked == true) {
                        manage_array.push(manage_bt[index].value);
                        manage_tourist_array.push(manage_tourist[index].value);
                        after_array.push(before_manage[index].value);
                    }
                }
            }

            if (manage_id > 0) {
                var formData = new FormData();
                formData.append('action', 'create');
                formData.append('manage_id', manage_id);
                formData.append('bt_array', JSON.stringify(bt_array));
                formData.append('tourist_array', JSON.stringify(tourist_array));
                formData.append('manage_array', JSON.stringify(manage_array));
                formData.append('manage_tourist_array', JSON.stringify(manage_tourist_array));
                formData.append('before_array', JSON.stringify(before_array));
                formData.append('after_array', JSON.stringify(after_array));
                $.ajax({
                    url: "pages/order-driver/function/create_booking_manage.php",
                    type: "POST",
                    processData: false,
                    contentType: false,
                    data: formData,
                    success: function(response) {
                        // console.log(response);
                        if (response != false && response > 0) {
                            location.reload(); // refresh page
                        } else {
                            Swal.fire({
                                title: "Please try again.",
                                icon: "error",
                            });
                        }
                    }
                });
            }
        }

        function delete_transfer() {
            var manage_id = document.getElementById('manage_id');
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
                        url: "pages/order-driver/function/delete-manage.php",
                        type: "POST",
                        data: {
                            manage_id: manage_id.value,
                            return: $('#return').val(),
                            action: 'delete'
                        },
                        success: function(response) {
                            // console.log(response);
                            if (response != false) {
                                location.reload();
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

        function download_image() {
            var img_name = document.getElementById('name_img').value;
            var node = document.getElementById('div-driver-job-image');
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