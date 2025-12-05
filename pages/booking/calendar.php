<?php
require_once 'controllers/Booking.php';

$bookObj = new Booking();
$today = date("Y-m-d");

$arr_travel = array();
$arr_prod = array();
$programed = $bookObj->get_values(
    'booking_product_rates.id, booking_products.travel_date, booking_products.product_id, booking_product_rates.adult, booking_product_rates.child, booking_product_rates.infant, booking_product_rates.foc',
    'booking_products LEFT JOIN booking_product_rates ON booking_products.id = booking_product_rates.booking_products_id',
    'booking_products.id > 0 AND booking_products.travel_date >= "' . $today . '" ORDER BY booking_products.travel_date ASC',
    1
);

if (!empty($programed)) {
    $arr_id = array();
    foreach ($programed as $programe) {
        $date = $programe['travel_date'];
        $prod = $programe['product_id'];
        $id = $programe['id'];
        if (!in_array($date, $arr_travel)) {
            $arr_travel[] = $date;
            $travel_date[] = $date;
        }
        if (!isset($arr_prod[$date])) {
            $arr_prod[$date] = array();
        }
        if (!in_array($prod, $arr_prod[$date])) {
            $arr_prod[$date][] = $prod;
            $product_id[$date][] = $prod;
        }
        $tourist[$date][$prod][] = $programe['adult'] + $programe['child'] + $programe['infant'] + $programe['foc'];
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
                                        <?php
                                        $col = 0;
                                        $programed = $bookObj->show_product();
                                        foreach ($programed as $programe) {
                                            $col++;
                                            switch ($col) {
                                                case '1':
                                                    $color = 'danger';
                                                    break;
                                                case '2':
                                                    $color = 'warning';
                                                    break;
                                                case '3':
                                                    $color = 'success';
                                                    break;
                                                case '4':
                                                    $color = 'info';
                                                    break;
                                                case '5':
                                                    $color = 'secondary';
                                                    break;
                                                default:
                                                    $color = 'primary';
                                                    break;
                                            } ?>
                                            <div class="custom-control custom-control-<?php echo $color; ?> custom-checkbox mb-1">
                                                <input type="checkbox" class="custom-control-input input-filter" id="<?php echo $programe['id'] ?>" data-value="<?php echo $programe['id'] ?>" checked />
                                                <label class="custom-control-label" for="<?php echo $programe['id'] ?>"><?php echo $programe['name'] ?></label>
                                            </div>
                                        <?php }
                                        ?>
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