<?php
require_once 'controllers/Order.php';

$bookObj = new Order();
$manageObj = new Order();
$today = date("Y-m-d");
$tomorrow = date("Y-m-d", strtotime(" +1 day"));
// $today = '2024-09-29';
// $tomorrow = '2024-09-30';
$get_date = !empty($_GET['date_travel_booking']) ? $_GET['date_travel_booking'] : $tomorrow; // $tomorrow->format("Y-m-d")
$search_boat = !empty($_GET['search_boat']) ? $_GET['search_boat'] : 'all';
$search_status = $_GET['search_status'] != "" ? $_GET['search_status'] : 'all';
$search_agent = $_GET['search_agent'] != "" ? $_GET['search_agent'] : 'all';
$search_product = $_GET['search_product'] != "" ? $_GET['search_product'] : 'all';
$search_voucher_no = $_GET['voucher_no'] != "" ? $_GET['voucher_no'] : '';
$refcode = $_GET['refcode'] != "" ? $_GET['refcode'] : '';
$name = $_GET['name'] != "" ? $_GET['name'] : '';

$all_manages = $manageObj->fetch_all_manageboat($get_date, $search_boat, 'all', 0);

$categorys_array = array();
$all_bookings = $manageObj->fetch_all_bookingboat('all', $get_date, $search_status, $search_agent, $search_product, $search_voucher_no, $refcode, $name, '', 0);

foreach ($all_bookings as $categorys) {
    $categorys_array[] = $categorys['id'];
    $category_name[$categorys['id']][] = $categorys['category_name'];
    $adult[$categorys['id']][] = $categorys['adult'];
    $child[$categorys['id']][] = $categorys['child'];
    $infant[$categorys['id']][] = $categorys['infant'];
    $foc[$categorys['id']][] = $categorys['foc'];
    $tourist_array[$categorys['id']][] = $categorys['adult'] + $categorys['child'] + $categorys['infant'] + $categorys['foc'];
}
?>

<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">Manage Boat</h2>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-right col-md-3 col-12 d-md-block d-none">

            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="today-tab" data-toggle="tab" href="#today" aria-controls="today" role="tab" aria-selected="true">Today</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tomorrow-tab" data-toggle="tab" href="#tomorrow" aria-controls="tomorrow" role="tab" aria-selected="false">Tomorrow</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="customh-tab" data-toggle="tab" href="#custom" aria-controls="custom" role="tab" aria-selected="true">Custom</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="today" aria-labelledby="today-tab" role="tabpanel">

                            </div>
                            <div class="tab-pane" id="tomorrow" aria-labelledby="tomorrow-tab" role="tabpanel">

                            </div>
                            <div class="tab-pane" id="custom" aria-labelledby="custom-tab" role="tabpanel">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">
            <section id="sortable-lists">
                <!-- bookings filter start -->
                <div class="card">
                    <h5 class="card-header">Search Filter</h5>
                    <form id="booking-search-form" name="booking-search-form" method="get" enctype="multipart/form-data">
                        <input type="hidden" name="pages" value="<?php echo $_GET['pages']; ?>">
                        <div class="d-flex align-items-center mx-50 row pt-0 pb-0">
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label for="search_status">Status</label>
                                    <select class="form-control select2" id="search_status" name="search_status">
                                        <option value="all">All</option>
                                        <?php
                                        $bookstype = $bookObj->showliststatus();
                                        foreach ($bookstype as $booktype) {
                                            $selected = $search_status == $booktype['id'] ? 'selected' : '';
                                        ?>
                                            <option value="<?php echo $booktype['id']; ?>" <?php echo $selected; ?>><?php echo $booktype['name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 col-12">
                                <div class="form-group">
                                    <label for="search_agent">Agent</label>
                                    <select class="form-control select2" id="search_agent" name="search_agent">
                                        <option value="all">All</option>
                                        <?php
                                        $agents = $bookObj->showlistagent();
                                        foreach ($agents as $agent) {
                                            $selected = $search_agent == $agent['id'] ? 'selected' : '';
                                        ?>
                                            <option value="<?php echo $agent['id']; ?>" <?php echo $selected; ?>><?php echo $agent['name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 col-12">
                                <div class="form-group">
                                    <label for="search_product">Programe</label>
                                    <select class="form-control select2" id="search_product" name="search_product">
                                        <option value="all">All</option>
                                        <?php
                                        $products = $bookObj->showlistproduct();
                                        foreach ($products as $product) {
                                            $selected = $search_product == $product['id'] ? 'selected' : '';
                                        ?>
                                            <option value="<?php echo $product['id']; ?>" <?php echo $selected; ?>><?php echo $product['name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label class="form-label" for="date_travel_booking">วันที่เที่ยว (Travel Date)</label></br>
                                    <input type="text" class="form-control date-picker" id="date_travel_booking" name="date_travel_booking" value="<?php echo $get_date; ?>" />
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label class="form-label" for="refcode">Booking No #</label>
                                    <input type="text" class="form-control" id="refcode" name="refcode" value="<?php echo $refcode; ?>" />
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label class="form-label" for="voucher_no">Voucher No #</label>
                                    <input type="text" class="form-control" id="voucher_no" name="voucher_no" value="<?php echo $search_voucher_no; ?>" />
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label class="form-label" for="name">Customer Name</label>
                                    <input type="text" class="form-control" id="name" name="name" value="<?php echo $name; ?>" />
                                </div>
                            </div>
                            <div class="col-md-4 col-12">
                                <button type="submit" class="btn btn-primary">Search</button>
                                <button type="button" class="btn btn-success waves-effect waves-float waves-light btn-page-block-spinner" data-toggle="modal" data-target="#modal-boat"
                                    data-travel="<?php echo $get_date; ?>"
                                    data-travel_date="<?php echo date("j F Y", strtotime($get_date)); ?>"
                                    data-boat="0"
                                    onclick="modal_boat(this);">
                                    <i data-feather='plus'></i> เปิดเรือ
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card">
                    <div id="div-booking-list">
                        <!-- Start Management Boat -->
                        <!------------------------------------------------------------------>
                        <?php
                        if ($all_manages) {
                            foreach ($all_manages as $key => $manages) {
                        ?>
                                <div class="card-body pt-0 p-50">
                                    <div class="d-flex justify-content-between align-items-center header-actions mx-1 row mt-75">
                                        <div class="col-4 text-left text-bold h4"></div>
                                        <div class="col-4 text-center text-bold h4"><?php echo $manages['boat_name']; ?></div>
                                        <div class="col-4 text-right mb-50">
                                            <button type="button" class="btn btn-icon btn-icon btn-flat-info waves-effect btn-page-block-spinner" data-toggle="modal" data-target="#modal-booking"
                                                onclick="search_booking('<?php echo $get_date; ?>', <?php echo $manages['id']; ?>);">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle">
                                                    <circle cx="12" cy="12" r="10"></circle>
                                                    <line x1="12" y1="8" x2="12" y2="16"></line>
                                                    <line x1="8" y1="12" x2="16" y2="12"></line>
                                                </svg>
                                                เพิ่ม Booking
                                            </button>
                                            <button type="button" class="btn btn-icon btn-icon btn-flat-warning waves-effect btn-page-block-spinner" data-toggle="modal" data-target="#modal-boat"
                                                data-travel="<?php echo $get_date; ?>"
                                                data-travel_date="<?php echo date("j F Y", strtotime($get_date)); ?>"
                                                data-id="<?php echo $manages["id"]; ?>"
                                                data-time="<?php echo date('H:i', strtotime($manages['time'])); ?>"
                                                data-color="<?php echo $manages['color_id']; ?>"
                                                data-boat="<?php echo $manages['boat_id']; ?>"
                                                data-guide="<?php echo $manages['guide_id']; ?>"
                                                data-note="<?php echo $manages['note']; ?>"
                                                data-counter="<?php echo $manages['counter']; ?>"
                                                data-outside="<?php echo $manages['outside_boat']; ?>"
                                                onclick="modal_boat(this)">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit">
                                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                </svg>
                                                แก้ใขเรือ
                                            </button>
                                        </div>
                                    </div>
                                    <table class="table table-bordered table-striped">
                                        <thead class="bg-light">
                                            <tr>
                                                <th colspan="4">เวลา : <?php echo date('H:i', strtotime($manages['time'])); ?></th>
                                                <th colspan="6">ไกด์ : <?php echo $manages['guide_name']; ?></th>
                                                <th colspan="4">เคาน์เตอร์ : <?php echo $manages['counter']; ?></th>
                                                <th colspan="2" style="background-color: <?php echo $manages['color_hex']; ?>;">
                                                    สี : <?php echo $manages['color_name_th']; ?>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="text-center">เรือ</th>
                                                <th class="text-center">Driver</th>
                                                <th>Time</th>
                                                <th>Hotel</th>
                                                <th>Room</th>
                                                <th>Client</th>
                                                <th class="text-center">รวม</th>
                                                <th class="text-center">A</th>
                                                <th class="text-center">C</th>
                                                <th class="text-center">Inf</th>
                                                <th class="text-center">FOC</th>
                                                <th>AGENT</th>
                                                <th>SENDER</th>
                                                <th>V/C</th>
                                                <th>COT</th>
                                                <th>Remark</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $total_tourist = 0;
                                            $total_adult = 0;
                                            $total_child = 0;
                                            $total_infant = 0;
                                            $total_foc = 0;
                                            $bomange_arr = array();
                                            $all_bookings = $manageObj->fetch_all_bookingboat('manage', $get_date, $search_status, $search_agent, $search_product, $search_voucher_no, $refcode, $name, '', $manages['id']);
                                            foreach ($all_bookings as $bookings) {
                                                if (in_array($bookings['bomange_id'], $bomange_arr) == false) {
                                                    $bomange_arr[] = $bookings['bomange_id'];
                                                    $total_adult += !empty($adult[$bookings['id']]) ? array_sum($adult[$bookings['id']]) : 0;
                                                    $total_child += !empty($child[$bookings['id']]) ? array_sum($child[$bookings['id']]) : 0;
                                                    $total_infant += !empty($infant[$bookings['id']]) ? array_sum($infant[$bookings['id']]) : 0;
                                                    $total_foc += !empty($foc[$bookings['id']]) ? array_sum($foc[$bookings['id']]) : 0;
                                                    $total_tourist += !empty($tourist_array[$bookings['id']]) ? array_sum($tourist_array[$bookings['id']]) : 0;
                                                    $tourist = !empty($tourist_array[$bookings['id']]) ? array_sum($tourist_array[$bookings['id']]) : 0;
                                                    $text_hotel = '';
                                                    $text_hotel = (!empty($bookings['hotelp_name'])) ? '<b>Pickup : </b>' . $bookings['hotelp_name'] : '<b>Pickup : </b>' . $bookings['outside_pickup'];
                                                    $text_hotel .= (!empty($bookings['zonep_name'])) ? ' (' . $bookings['zonep_name'] . ')</br>' : '</br>';
                                                    $text_hotel .= (!empty($bookings['hoteld_name'])) ? '<b>Dropoff : </b>' . $bookings['hoteld_name'] : '<b>Dropoff : </b>' . $bookings['outside_dropoff'];
                                                    $text_hotel .= (!empty($bookings['zoned_name'])) ? ' (' . $bookings['zoned_name'] . ')' : '';

                                                    $cars = $manageObj->get_values(
                                                        'cars.name as name',
                                                        'booking_order_transfer 
                                                            LEFT JOIN order_transfer ON order_transfer.id = booking_order_transfer.order_id 
                                                            LEFT JOIN cars ON order_transfer.car_id = cars.id',
                                                        'booking_order_transfer.booking_transfer_id = ' . $bookings['bt_id'],
                                                        1
                                                    );
                                            ?>
                                                    <tr>
                                                        <td class="cell-fit"><span class="badge badge-pill badge-light-success text-capitalized"><?php echo $manages['boat_name']; ?></span></td>
                                                        <td class="cell-fit">
                                                            <?php if (!empty($cars)) {
                                                                foreach ($cars as $key => $car) {
                                                                    echo $key > 0 ? '<br>' : '';
                                                                    echo '<div class="badge badge-light-success">' . $car['name'] . '</div>';
                                                                }
                                                            } ?>
                                                        </td>
                                                        <td><?php echo date('H:i', strtotime($bookings['start_pickup'])) . ' - ' . date('H:i', strtotime($bookings['end_pickup'])); ?></td>
                                                        <td><?php echo $text_hotel; ?></td>
                                                        <td><?php echo $bookings['room_no']; ?></td>
                                                        <td><?php echo $bookings['cus_name']; ?></td>
                                                        <td class="cell-fit text-center"><?php echo $tourist; ?></td>
                                                        <td class="text-center"><?php echo !empty($adult[$bookings['id']]) ? array_sum($adult[$bookings['id']]) : 0; ?></td>
                                                        <td class="text-center"><?php echo !empty($child[$bookings['id']]) ? array_sum($child[$bookings['id']]) : 0; ?></td>
                                                        <td class="text-center"><?php echo !empty($infant[$bookings['id']]) ? array_sum($infant[$bookings['id']]) : 0; ?></td>
                                                        <td class="text-center"><?php echo !empty($foc[$bookings['id']]) ? array_sum($foc[$bookings['id']]) : 0; ?></td>
                                                        <td class="text-nowrap"><?php echo $bookings['agent_name']; ?></td>
                                                        <td class="text-nowrap"><?php echo $bookings['sender']; ?></td>
                                                        <td class="text-nowrap"><?php echo !empty($bookings['voucher_no_agent']) ? $bookings['voucher_no_agent'] : $bookings['book_full']; ?></td>
                                                        <td><b class="text-warning"><?php echo number_format($bookings['cot']); ?></b></td>
                                                        <td>
                                                            <b class="text-info">
                                                                <?php
                                                                $e = 0;
                                                                $extra_charges = $manageObj->get_extra_charge($bookings['id']);
                                                                if (!empty($extra_charges)) {
                                                                    foreach ($extra_charges as $extra_charge) {
                                                                        echo $e == 0 ? $extra_charge['extra_name'] : ' : ' . $extra_charge['extra_name'];
                                                                        $e++;
                                                                    }
                                                                }
                                                                echo $bookings['bp_note']; ?>
                                                            </b>
                                                        </td>
                                                    </tr>
                                            <?php }
                                            } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="16" class="text-center h5">Total: <?php echo $total_tourist; ?> | <?php echo $total_adult; ?> <?php echo $total_child; ?> <?php echo $total_infant; ?> <?php echo $total_foc; ?></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                        <?php
                            }
                        }
                        ?>
                        <!------------------------------------------------------------------>
                        <!-- End Management Boat -->
                        <div class="divider divider-dark">
                            <div class="divider-text p-0"></div>
                        </div>
                        <!-- Start Table Booking -->
                        <!------------------------------------------------------------------>
                        <?php
                        $all_bookings = $manageObj->fetch_all_bookingboat('booking', $get_date, $search_status, $search_agent, $search_product, $search_voucher_no, $refcode, $name, '', 0);
                        if (!empty($all_bookings)) {
                        ?>
                            <div class="card-body pt-0 p-50">
                                <table class="table table-bordered table-striped">
                                    <thead class="bg-light">
                                        <tr>
                                            <th width="4%" class="cell-fit text-center">STATUS</th>
                                            <th width="5%" class="text-nowrap">Category</th>
                                            <th width="5%" class="text-nowrap">TIME</th>
                                            <th width="22%">HOTEL</th>
                                            <th width="5%" class="text-nowrap">ROOM</th>
                                            <th width="13%" class="text-nowrap">Name</th>
                                            <th width="1%">รวม</th>
                                            <th width="1%">A</th>
                                            <th width="1%">C</th>
                                            <th width="1%">INF</th>
                                            <th width="1%">FOC</th>
                                            <th width="10%" class="text-nowrap">AGENT</th>
                                            <th width="10%" class="text-nowrap">V/C</th>
                                            <th width="5%" class="text-nowrap">COT</th>
                                            <th width="13%">REMARKE</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $total_tourist = 0;
                                        $total_adult = 0;
                                        $total_child = 0;
                                        $total_infant = 0;
                                        $total_foc = 0;
                                        $booking_array = array();
                                        foreach ($all_bookings as $key => $bookings) {
                                            if (in_array($bookings['id'], $booking_array) == false) {
                                                $booking_array[] = $bookings['id'];
                                                $total_adult += !empty($adult[$bookings['id']]) ? array_sum($adult[$bookings['id']]) : 0;
                                                $total_child += !empty($child[$bookings['id']]) ? array_sum($child[$bookings['id']]) : 0;
                                                $total_infant += !empty($infant[$bookings['id']]) ? array_sum($infant[$bookings['id']]) : 0;
                                                $total_foc += !empty($foc[$bookings['id']]) ? array_sum($foc[$bookings['id']]) : 0;
                                                $total_tourist += !empty($tourist_array[$bookings['id']]) ? array_sum($tourist_array[$bookings['id']]) : 0;
                                                $tourist = !empty($tourist_array[$bookings['id']]) ? array_sum($tourist_array[$bookings['id']]) : 0;
                                                $text_hotel = '';
                                                $text_hotel = (!empty($bookings['hotelp_name'])) ? '<b>Pickup : </b>' . $bookings['hotelp_name'] : '<b>Pickup : </b>' . $bookings['outside_pickup'];
                                                $text_hotel .= (!empty($bookings['zonep_name'])) ? ' (' . $bookings['zonep_name'] . ')</br>' : '</br>';
                                                $text_hotel .= (!empty($bookings['hoteld_name'])) ? '<b>Dropoff : </b>' . $bookings['hoteld_name'] : '<b>Dropoff : </b>' . $bookings['outside_dropoff'];
                                                $text_hotel .= (!empty($bookings['zoned_name'])) ? ' (' . $bookings['zoned_name'] . ')' : '';
                                        ?>
                                                <tr>
                                                    <td><span class="badge <?php echo $bookings['booksta_class']; ?>"><?php echo $bookings['status_name']; ?></span></td>
                                                    <td class="cell-fit">
                                                        <?php if (!empty($category_name[$bookings['id']])) {
                                                            for ($i = 0; $i < count($category_name[$bookings['id']]); $i++) {
                                                                echo $i == 0 ? $category_name[$bookings['id']][$i] : ', ' . $category_name[$bookings['id']][$i];
                                                            }
                                                        } ?>
                                                    </td>
                                                    <td><?php echo date('H:i', strtotime($bookings['start_pickup'])) . ' - ' . date('H:i', strtotime($bookings['end_pickup'])); ?></td>
                                                    <td><?php echo $text_hotel; ?></td>
                                                    <td><?php echo $bookings['room_no']; ?></td>
                                                    <td><?php echo $bookings['cus_name']; ?></td>
                                                    <td class="cell-fit text-center"><?php echo $tourist; ?></td>
                                                    <td class="text-center"><?php echo !empty($adult[$bookings['id']]) ? array_sum($adult[$bookings['id']]) : 0; ?></td>
                                                    <td class="text-center"><?php echo !empty($child[$bookings['id']]) ? array_sum($child[$bookings['id']]) : 0; ?></td>
                                                    <td class="text-center"><?php echo !empty($infant[$bookings['id']]) ? array_sum($infant[$bookings['id']]) : 0; ?></td>
                                                    <td class="text-center"><?php echo !empty($foc[$bookings['id']]) ? array_sum($foc[$bookings['id']]) : 0; ?></td>
                                                    <td class="text-nowrap"><?php echo $bookings['agent_name']; ?></td>
                                                    <td class="text-nowrap"><?php echo !empty($bookings['voucher_no_agent']) ? $bookings['voucher_no_agent'] : $bookings['book_full']; ?></td>
                                                    <td><b class="text-warning"><?php echo number_format($bookings['cot']); ?></b></td>
                                                    <td>
                                                        <b class="text-info">
                                                            <?php
                                                            $e = 0;
                                                            $extra_charges = $manageObj->get_extra_charge($bookings['id']);
                                                            if (!empty($extra_charges)) {
                                                                foreach ($extra_charges as $extra_charge) {
                                                                    echo $e == 0 ? $extra_charge['extra_name'] : ' : ' . $extra_charge['extra_name'];
                                                                    $e++;
                                                                }
                                                            }
                                                            echo $bookings['bp_note']; ?>
                                                        </b>
                                                    </td>
                                                </tr>
                                        <?php }
                                        } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="16" class="text-center h5">Total: <?php echo $total_tourist; ?> | <?php echo $total_adult; ?> <?php echo $total_child; ?> <?php echo $total_infant; ?> <?php echo $total_foc; ?></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        <?php
                        }
                        ?>
                        <!------------------------------------------------------------------>
                        <!-- End Table Booking -->
                    </div>
                </div>
                <!-- bookings filter end -->
            </section>

            <!-- Start Form Modal -->
            <!------------------------------------------------------------------>
            <!-- action boat -->
            <div class="modal-size-xl d-inline-block">
                <div class="modal fade text-left" id="modal-boat" tabindex="-1" role="dialog" aria-labelledby="myModalLabel17" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="myModalLabel17">เปิดเรือ</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div id="show-div-cus"></div>
                                <!-- <form id="open-boat-form" onsubmit="return false;"> -->
                                <form id="boat-form" name="boat-form" action="" method="post" enctype="multipart/form-data">
                                    <input type="hidden" id="manage_id" name="manage_id" value="">
                                    <div class="row">
                                        <div class="col-md-12 col-12">
                                            <div class="form-group">
                                                <label>วันที่เที่ยว</label><br>
                                                <span id="text-travel-date"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-12">
                                            <div class="form-group" id="frm-boats">
                                                <label for="boats">เรือ</label>
                                                <select class="form-control select2" id="boats" name="boats" onchange="check_outside('boats');">
                                                </select>
                                            </div>
                                            <div class="form-group" id="frm-boats-outside" hidden>
                                                <label class="form-label" for="outside_boat">เรือนอก </label></br>
                                                <div class="input-group input-group-merge mb-2">
                                                    <input type="text" class="form-control" id="outside_boat" name="outside_boat" value="" />
                                                    <div class="input-group-append" onclick="check_outside('outside_boat');">
                                                        <span class="input-group-text cursor-pointer"><i data-feather='x'></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-12">
                                            <div class="form-group" id="frm-guide">
                                                <label for="guides">ไกด์</label>
                                                <select class="form-control select2" id="guides" name="guides" onchange="check_outside('guides');">
                                                    <option value="">กรุญาเลือกไกด์...</option>
                                                    <option value="outside">กรอกข้อมูลเพิ่มเติม</option>
                                                    <?php
                                                    $guides = $bookObj->show_guides();
                                                    foreach ($guides as $guide) {
                                                    ?>
                                                        <option value="<?php echo $guide['id']; ?>" data-name="<?php echo $guide['name']; ?>"><?php echo $guide['name']; ?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group" id="frm-guide-outside" hidden>
                                                <label class="form-label" for="outside_guide">ไกด์นอก </label></br>
                                                <div class="input-group input-group-merge mb-2">
                                                    <input type="text" class="form-control" id="outside_guide" name="outside_guide" value="" />
                                                    <div class="input-group-append" onclick="check_outside('outside_guide');">
                                                        <span class="input-group-text cursor-pointer"><i data-feather='x'></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-12" id="div-time">
                                            <div class="form-group">
                                                <label for="time">Time (เวลาขึ้นเรือ)</label>
                                                <input type="text" id="time" name="time" class="form-control time-mask" placeholder="HH:MM" value="" />
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-12" id="div-colors">
                                            <div class="form-group">
                                                <label for="color">สี </label>
                                                <select class="form-control select2" id="color" name="color" onchange="chang_color('create');">
                                                    <option value=""></option>
                                                    <?php
                                                    $colors = $bookObj->show_color();
                                                    foreach ($colors as $color) {
                                                    ?>
                                                        <option value="<?php echo $color['id']; ?>" data-color="<?php echo $color['hex_code']; ?>"><?php echo $color['name_th']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-1 col-12" id="div-hex">
                                            <div class="avatar mt-2" id="div-color">
                                                <div style="width: 40px; height: 40px;"></div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label class="form-label" for="note">Note</label></br>
                                                <textarea name="note" id="note" class="form-control" cols="30" rows="2"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-12">
                                            <div class="form-group">
                                                <label class="form-label" for="counter">เคาน์เตอร์</label>
                                                <input type="text" class="form-control" id="counter" name="counter" value="" />
                                            </div>
                                        </div>
                                    </div>
                                    <hr />
                                    <div class="d-flex justify-content-between">
                                        <button type="button" class="btn btn-danger" id="delete_manage" onclick="delete_boat();">Delete</button>
                                        <button type="submit" class="btn btn-primary" name="submit" value="Submit">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- create booking manage boat -->
            <div class="modal-size-xl d-inline-block">
                <div class="modal fade text-left" id="modal-booking" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="myModalLabel17">เลือก Booking</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="table-responsive" id="div-manage-boooking">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!------------------------------------------------------------------>
            <!-- End Form Modal -->

        </div>
    </div>