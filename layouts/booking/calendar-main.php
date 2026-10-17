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
    <link rel="stylesheet" type="text/css" href="app-assets/vendors/css/calendars/fullcalendar.min.css">
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
    <link rel="stylesheet" type="text/css" href="app-assets/css/pages/app-calendar.css">
    <link rel="stylesheet" type="text/css" href="app-assets/css/core/menu/menu-types/vertical-menu.css">
    <link rel="stylesheet" type="text/css" href="app-assets/css/plugins/forms/form-validation.css">
    <link rel="stylesheet" type="text/css" href="app-assets/css/plugins/forms/pickers/form-flat-pickr.css">
    <link rel="stylesheet" type="text/css" href="app-assets/css/pages/app-user.css">
    <link rel="stylesheet" type="text/css" href="app-assets/fonts/fontawesome/css/all.css" rel="stylesheet">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <!-- END: Custom CSS-->

    <style>
        .fc-day {
            height: 12px !important;
        }

        .fc .fc-daygrid-event-harness .fc-event {
            font-size: 1rem;
            font-weight: 600;
            padding: 0.25rem 0.5rem;
        }

        .fc-list-event-time {
            display: none;
        }

        /* Day cell: total pax in the middle + pax per program below */
        .booking__calendar-day {
            text-align: center;
            white-space: normal;
        }

        .booking__calendar-total {
            font-size: 1.75rem;
            font-weight: 600;
            line-height: 1.2;
            color: #5e5873;
        }

        .dark-layout .booking__calendar-total {
            color: #d0d2d6;
        }

        .booking__calendar-programs {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 2px;
            margin-top: 0.25rem;
        }

        .booking__calendar-pax {
            min-width: 28px;
            padding: 1px 4px;
            border-radius: 4px;
            color: #fff;
            font-size: 0.9rem;
            font-weight: 600;
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
    <script src="app-assets/vendors/js/calendar/fullcalendar.min.js"></script>
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
    <script src="app-assets/js/scripts/header.js"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <script type="text/javascript">
        // onHidden event
        $('#hidden-tooltip')
            .tooltip({
                title: 'Tooltip Hidden Event',
                trigger: 'click',
                placement: 'left'
            })
            .on('hidden.bs.tooltip', function() {
                alert('Hidden event fired.');
            });

        /**
         * Booking Calendar: one event per day = total pax + pax per program (fixed order/color)
         */
        const calendarPax = <?php echo json_encode($calendar_pax, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;
        const calendarPrograms = <?php echo json_encode($calendar_programs, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;

        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');
            const selectAll = $('.select-all');
            const calEventFilter = $('.calendar-events-filter');
            const filterInput = $('.input-filter');

            function escapeHtml(text) {
                return $('<div>').text(text).html();
            }

            function selectedPrograms() {
                return $('.calendar-events-filter input:checked').map(function() {
                    return String($(this).attr('data-value'));
                }).get();
            }

            function fetchEvents(info, successCallback) {
                const selected = selectedPrograms();
                const dayEvents = [];

                $.each(calendarPax, function(travelDate, paxByProgram) {
                    let total = 0;
                    let boxes = '';
                    calendarPrograms.forEach(function(program) {
                        const pax = paxByProgram[program.id];
                        if (pax === undefined || selected.indexOf(String(program.id)) === -1) {
                            return;
                        }
                        total += pax;
                        boxes += '<span class="booking__calendar-pax bg-' + program.color + '" title="' + escapeHtml(program.name) + '">' + pax + '</span>';
                    });
                    if (boxes === '') {
                        return;
                    }
                    dayEvents.push({
                        start: travelDate,
                        allDay: true,
                        backgroundColor: 'transparent',
                        borderColor: 'transparent',
                        extendedProps: {
                            htmlContent: '<div class="booking__calendar-day"><div class="booking__calendar-total">' + total + '</div><div class="booking__calendar-programs">' + boxes + '</div></div>'
                        }
                    });
                });

                successCallback(dayEvents);
            }

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: fetchEvents,
                contentHeight: 'auto',
                dayMaxEvents: false,
                customButtons: {
                    sidebarToggle: {
                        text: 'Sidebar'
                    }
                },
                headerToolbar: {
                    start: 'sidebarToggle, prev,next, title',
                    end: 'dayGridMonth,listMonth'
                },
                direction: $('html').data('textdirection') == 'rtl' ? 'rtl' : 'ltr',
                initialDate: new Date(),
                navLinks: true,
                eventContent: function(arg) {
                    return {
                        html: arg.event.extendedProps.htmlContent
                    };
                }
            });

            calendar.render();

            $('.fc-sidebarToggle-button')
                .empty()
                .append(feather.icons['menu'].toSvg({
                    class: 'ficon'
                }));

            // Select all & filter functionality
            selectAll.on('change', function() {
                calEventFilter.find('input').prop('checked', $(this).prop('checked'));
                calendar.refetchEvents();
            });

            filterInput.on('change', function() {
                selectAll.prop('checked', $('.input-filter:checked').length === calEventFilter.find('input').length);
                calendar.refetchEvents();
            });
        });
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