<?php
require_once 'controllers/Booking.php';

$bookObj = new Booking();
$today = date("Y-m-d");

$programed = $bookObj->get_values(
    'booking_product_rates.id, booking_products.travel_date, booking_products.product_id, booking_product_rates.adult, booking_product_rates.child, booking_product_rates.infant, booking_product_rates.foc',
    'booking_products LEFT JOIN booking_product_rates ON booking_products.id = booking_product_rates.booking_products_id',
    'booking_products.id > 0 AND booking_products.travel_date >= "' . $today . '" ORDER BY booking_products.travel_date ASC',
    1
);

// Total pax per travel date per product: $calendar_pax[date][product_id] = pax
$calendar_pax = array();
if (!empty($programed)) {
    foreach ($programed as $programe) {
        $date = $programe['travel_date'];
        $prod = (int) $programe['product_id'];
        if (!isset($calendar_pax[$date][$prod])) {
            $calendar_pax[$date][$prod] = 0;
        }
        $calendar_pax[$date][$prod] += (int) $programe['adult'] + (int) $programe['child'] + (int) $programe['infant'] + (int) $programe['foc'];
    }
}

// Fixed program order + color (same in every day cell): PK red | KL orange | KB green | no transfer blue
$program_fixed = array(12 => 'danger', 13 => 'warning', 14 => 'success', 15 => 'info');
$program_extra_colors = array('secondary', 'primary', 'dark');

$products = $bookObj->show_product();
$calendar_programs = array();
foreach ($program_fixed as $fixed_id => $fixed_color) {
    foreach ($products as $product) {
        if ((int) $product['id'] == $fixed_id) {
            $calendar_programs[] = array('id' => $fixed_id, 'name' => $product['name'], 'color' => $fixed_color);
        }
    }
}
$extra_no = 0;
foreach ($products as $product) {
    if (!isset($program_fixed[(int) $product['id']])) {
        $color = $program_extra_colors[$extra_no % count($program_extra_colors)];
        $extra_no++;
        $calendar_programs[] = array('id' => (int) $product['id'], 'name' => $product['name'], 'color' => $color);
    }
}
?>
<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body">
            <!-- Full calendar start -->
            <section>
                <div class="app-calendar overflow-hidden border">
                    <div class="row no-gutters">
                        <!-- Sidebar -->
                        <div class="col app-calendar-sidebar flex-grow-0 overflow-hidden d-flex flex-column" id="app-calendar-sidebar">
                            <div class="sidebar-wrapper">
                                <div class="card-body pb-0">
                                    <h5 class="section-label mb-1">
                                        <span class="align-middle">Filter</span>
                                    </h5>
                                    <div class="custom-control custom-checkbox mb-1">
                                        <input type="checkbox" class="custom-control-input select-all" id="select-all" checked />
                                        <label class="custom-control-label" for="select-all">View All</label>
                                    </div>
                                    <div class="calendar-events-filter">
                                        <?php foreach ($calendar_programs as $program) { ?>
                                            <div class="custom-control custom-control-<?php echo $program['color']; ?> custom-checkbox mb-1">
                                                <input type="checkbox" class="custom-control-input input-filter" id="<?php echo $program['id']; ?>" data-value="<?php echo $program['id']; ?>" checked />
                                                <label class="custom-control-label" for="<?php echo $program['id']; ?>"><?php echo htmlspecialchars($program['name'], ENT_QUOTES, 'UTF-8'); ?></label>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-auto">
                                <img src="app-assets/images/pages/calendar-illustration.png" alt="Calendar illustration" class="img-fluid" />
                            </div>
                        </div>
                        <!-- /Sidebar -->

                        <!-- Calendar -->
                        <div class="col position-relative">
                            <div class="card shadow-none border-0 mb-0 rounded-0">
                                <div class="card-body pb-0">
                                    <div id="calendar"></div>
                                </div>
                            </div>
                        </div>
                        <!-- /Calendar -->
                        <div class="body-content-overlay"></div>
                    </div>
                </div>
            </section>
            <!-- Full calendar end -->

        </div>
    </div>
</div>