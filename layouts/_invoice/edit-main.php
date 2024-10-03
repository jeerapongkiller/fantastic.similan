<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
    <meta name="description" content="<?php echo $main_description; ?>">
    <meta name="keywords" content="<?php echo $main_keywords; ?>">
    <meta name="author" content="<?php echo $main_author; ?>">
    <title>Agent - <?php echo $main_title; ?></title>
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
    <script src="app-assets/vendors/js/forms/wizard/bs-stepper.min.js"></script>
    <script src="app-assets/vendors/js/forms/select/select2.full.min.js"></script>
    <script src="app-assets/vendors/js/forms/validation/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
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

    <!-- BEGIN: Page JS-->
    <script type="text/javascript">
        // Ajax Delete Invoice
        // --------------------------------------------------------------------
        function deleteUser() {
            var user_id = document.getElementById('user_id').value;
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
                        url: "pages/agent/function/delete-user.php",
                        type: "POST",
                        data: {
                            user_id: user_id,
                            action: 'delete'
                        },
                        success: function(response) {
                            // $('#show-user').html(response);
                            if (response != false) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: 'Your item has been deleted.',
                                    customClass: {
                                        confirmButton: 'btn btn-success'
                                    }
                                }).then(function() {
                                    location.href = "<?php echo $_SERVER['REQUEST_URI']; ?>";
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
            var jqForm = $('#agent-edit-form'),
                jqUserForm = $('#agent-user-form'),
                picker = $('#dob'),
                dtPicker = $('#dob-bootstrap-val'),
                select = $('.select2'),
                horizontalWizard = document.querySelector('.horizontal-wizard-example');

            // horizontal wizard
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
                    .find('.btn-submit')
                    .on('click', function() {
                        alert('Submitted..!!');
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

            //Numeral
            $('.numeral-mask').toArray().forEach(function(field) {
                new Cleave(field, {
                    numeral: true,
                    numeralThousandsGroupStyle: 'thousand'
                });
            });

            // jQuery Validation
            // --------------------------------------------------------------------
            if (jqForm.length) {
                $.validator.addMethod("regex", function(value, element, regexp) {
                    return this.optional(element) || regexp.test(value);
                }, "Please check your input.");

                $.validator.addMethod('filesize', function(value, element, param) {
                    return this.optional(element) || (element.files[0].size <= param * 1000000)
                }, 'File size must be less than {0} MB');

                jqForm.validate({
                    rules: {
                        'tat_license': {
                            required: true,
                            rangelength: [5, 20],
                            regex: /^[a-zA-Z0-9/]{5,20}$/,
                            remote: {
                                url: "pages/agent/function/check-tat-license.php",
                                type: "post",
                                data: {
                                    company_id: function() {
                                        return $("#company_id").val();
                                    }
                                }
                            }
                        },
                        'name': {
                            required: true
                        },
                        'email': {
                            required: true,
                            email: true
                        },
                        'telephone': {
                            required: true
                        },
                        'address': {
                            required: true
                        },
                        'contact_person': {
                            required: true
                        },
                        'logo[]': {
                            // required: true,
                            extension: "jpg|jpeg|png",
                            filesize: 2
                        }
                    },
                    messages: {
                        'tat_license': {
                            remote: "This tat license is already taken! Try another."
                        },
                        'logo[]': {
                            extension: "Please select only jpg, jpeg and png files"
                        }
                    },
                    submitHandler: function(form) {
                        // update ajax request data
                        var formData = new FormData(form);
                        formData.append('action', 'edit');

                        $.ajax({
                            url: "pages/agent/function/edit.php",
                            type: "POST",
                            processData: false,
                            contentType: false,
                            data: formData,
                            success: function(response) {
                                if (response != false && response > 0) {
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

            if (jqUserForm.length) {
                $.validator.addMethod("regex", function(value, element, regexp) {
                    return this.optional(element) || regexp.test(value);
                }, "Please check your input.");

                $.validator.addMethod('filesize', function(value, element, param) {
                    return this.optional(element) || (element.files[0].size <= param * 1000000)
                }, 'File size must be less than {0} MB');

                jqUserForm.validate({
                    rules: {
                        'username': {
                            rangelength: [5, 20],
                            regex: /^[a-zA-Z0-9]{5,20}$/,
                            remote: {
                                url: "pages/user/function/check-username.php",
                                type: "post"
                            }
                        },
                        'password': {
                            // required: true,
                            rangelength: [6, 20],
                            regex: /^[a-zA-Z0-9#]{6,20}$/
                        },
                        'confirm-password': {
                            // required: true,
                            rangelength: [6, 20],
                            regex: /^[a-zA-Z0-9#]{6,20}$/,
                            equalTo: '#password'
                        },
                        'department': {
                            required: true
                        },
                        'user_fname': {
                            required: true
                        },
                        'user_lname': {
                            required: true
                        },
                        'user_email': {
                            required: true,
                            email: true
                        },
                        'user_telephone': {
                            required: true
                        },
                        'photo[]': {
                            // required: true,
                            extension: "jpg|jpeg|png",
                            filesize: 2
                        }
                    },
                    messages: {
                        'logo[]': {
                            extension: "Please select only jpg, jpeg and png files"
                        }
                    },
                    submitHandler: function(form) {
                        // update ajax request data
                        var position = $('#user_action').val() == 'edit' ? 'edit-user.php' : 'create-user.php';
                        var formData = new FormData(form);

                        $.ajax({
                            url: "pages/agent/function/" + position,
                            type: "POST",
                            processData: false,
                            contentType: false,
                            data: formData,
                            success: function(response) {
                                // $('#show-user').html(response);
                                if (response != false && response > 0) {
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
        });

        // Script Function
        // --------------------------------------------------------------------
        function modal_user(action, user_id, username, is_approved, department_id, firstname, lastname, email, telephone, address, contact_person, note, photo) {
            var depart_id = typeof department_id != 'undefined' ? department_id : 0;
            document.getElementById('user_action').value = action;
            document.getElementById('user_is_approved').checked = is_approved > 0 || action == 'create' ? true : false;
            document.getElementById('user_id').value = typeof user_id != 'undefined' ? user_id : 0;
            document.getElementById('username').value = typeof username != 'undefined' ? username : '';
            document.getElementById('department').value = depart_id;
            document.getElementById('user_fname').value = typeof firstname != 'undefined' ? firstname : '';
            document.getElementById('user_lname').value = typeof lastname != 'undefined' ? lastname : '';
            document.getElementById('user_email').value = typeof email != 'undefined' ? email : '';
            document.getElementById('user_telephone').value = typeof telephone != 'undefined' ? telephone : '';
            document.getElementById('user_address').value = typeof address != 'undefined' ? address : '';
            document.getElementById('user_contact_person').value = typeof contact_person != 'undefined' ? contact_person : '';
            document.getElementById('user_note').value = typeof note != 'undefined' ? note : '';
            document.getElementById('div-photo').hidden = action == 'edit' ? false : true;
            document.getElementById('before_photo').value = typeof photo != 'undefined' ? photo : '';
            document.getElementById('img-photo').src = typeof photo != 'undefined' ? 'storage/uploads/users/agent/' + photo : '';
            document.getElementById('btn-delete-user').hidden = action == 'edit' ? false : true;

            document.getElementById('username').disabled = typeof user_id != 'undefined' && user_id > 0 ? true : false;
            $('#department').each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>');
                $this
                    .select2({
                        placeholder: 'Select ...',
                        dropdownParent: $this.parent(),
                        'val': depart_id
                    })
                    .change(function() {
                        $(this).valid();
                    })
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