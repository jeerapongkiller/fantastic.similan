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

$bo_arr = array();
$bomange_arr = array();
$categorys_array = array();
$cars_arr = array();
$extra_arr = array();
$bpr_arr = array();
$manages_arr = array();
$all_bookings = $manageObj->fetch_all_bookingboat('all', $get_date, $search_status, $search_agent, $search_product, $search_voucher_no, $refcode, $name, $hotel = '', $search_boat, $search_guide = 'all', 0);
foreach ($all_bookings as $categorys) {
    if (in_array($categorys['manage_id'], $manages_arr) == false && !empty($categorys['manage_id'])) {
        $manages_arr[] = $categorys['manage_id'];
        $manage_id[] = $categorys['manage_id'];
        $manage_time[] = $categorys['manage_time'];
        $boat_id[] = $categorys['boat_id'];
        $boat_name[] = $categorys['boat_name'];
        $guide_id[] = $categorys['guide_id'];
        $guide_name[] = $categorys['guide_name'];
        $counter[] = $categorys['manage_counter'];
        $color_id[] = $categorys['color_id'];
        $color_hex[] = $categorys['color_hex'];
        $text_color[] = $categorys['text_color'];
        $color_name_th[] = $categorys['color_name_th'];
        $manage_note[] = $categorys['manage_note'];
    }

    if (in_array($categorys['bpr_id'], $bpr_arr) == false) {
        $bpr_arr[] = $categorys['bpr_id'];
        $categorys_array[] = $categorys['id'];
        $category_name[$categorys['id']][] = $categorys['category_name'];
        $adult[$categorys['id']][] = $categorys['adult'];
        $child[$categorys['id']][] = $categorys['child'];
        $infant[$categorys['id']][] = $categorys['infant'];
        $foc[$categorys['id']][] = $categorys['foc'];
        $tourist_array[$categorys['id']][] = $categorys['adult'] + $categorys['child'] + $categorys['infant'] + $categorys['foc'];
    }

    if (in_array($categorys['bomange_id'], $bomange_arr) == false) {
        $bomange_arr[] = $categorys['bomange_id'];
        $booking_id[$categorys['manage_id']][] = $categorys['id'];
    }

    if (in_array($categorys['id'], $bo_arr) == false) {
        $bo_arr[] = $categorys['id'];
        $bo_id[] = $categorys['id'];
        $bomange_id[$categorys['id']] = $categorys['bomange_id'];
        $hotelp_name[$categorys['id']] = $categorys['hotelp_name'];
        $outside_pickup[$categorys['id']] = $categorys['outside_pickup'];
        $zonep_name[$categorys['id']] = $categorys['zonep_name'];
        $hoteld_name[$categorys['id']] = $categorys['hoteld_name'];
        $zoned_name[$categorys['id']] = $categorys['zoned_name'];
        $outside_dropoff[$categorys['id']] = $categorys['outside_dropoff'];
        $start_pickup[$categorys['id']] = $categorys['start_pickup'];
        $end_pickup[$categorys['id']] = $categorys['end_pickup'];
        $product_name[$categorys['id']] = $categorys['product_name'];
        $telephone[$categorys['id']] = $categorys['telephone'];
        $cus_name[$categorys['id']] = $categorys['cus_name'];
        $voucher_no_agent[$categorys['id']] = $categorys['voucher_no_agent'];
        $book_full[$categorys['id']] = $categorys['book_full'];
        $room_no[$categorys['id']] = $categorys['room_no'];
        $bp_note[$categorys['id']] = $categorys['bp_note'];
        $agent_name[$categorys['id']] = $categorys['agent_name'];
        $status_name[$categorys['id']] = $categorys['status_name'];
        $booksta_class[$categorys['id']] = $categorys['booksta_class'];
        $sender[$categorys['id']] = $categorys['sender'];
        $cot[$categorys['id']] = $categorys['cot'];
    }

    if (in_array($categorys['bot_id'], $cars_arr) == false) {
        $cars_arr[] = $categorys['bot_id'];
        $car_name[$categorys['id']][] = $categorys['car_name'];
    }

    if (in_array($categorys['bec_id'], $extra_arr) == false) {
        $extra_arr[] = $categorys['bec_id'];
        $extra_name[$categorys['id']][] = $categorys['extra_name'];
    }
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
                                                <!-- <th class="text-center">รวม</th> -->
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
                                            if (!empty($booking_id[$manages["id"]])) {
                                                $booking_id_arr = array();
                                                for ($i = 0; $i < count($booking_id[$manages["id"]]); $i++) {
                                                    if (in_array($booking_id[$manages["id"]][$i], $booking_id_arr) == false) {
                                                        $booking_id_arr[] = $booking_id[$manages["id"]][$i];
                                                        $id = $booking_id[$manages["id"]][$i];

                                                        $total_adult += !empty($adult[$id]) ? array_sum($adult[$id]) : 0;
                                                        $total_child += !empty($child[$id]) ? array_sum($child[$id]) : 0;
                                                        $total_infant += !empty($infant[$id]) ? array_sum($infant[$id]) : 0;
                                                        $total_foc += !empty($foc[$id]) ? array_sum($foc[$id]) : 0;
                                                        $total_tourist += !empty($tourist_array[$id]) ? array_sum($tourist_array[$id]) : 0;
                                                        $tourist = !empty($tourist_array[$id]) ? array_sum($tourist_array[$id]) : 0;

                                                        $text_hotel = '';
                                                        $text_hotel = (!empty($hotelp_name[$id])) ? $hotelp_name[$id] : $outside_pickup[$id];
                                                        $text_hotel .= (!empty($zonep_name[$id])) ? ' (' . $zonep_name[$id] . ')' : '</br>';
                                                        // $text_hotel .= (!empty($hoteld_name[$id])) ? '<b>Dropoff : </b>' . $hoteld_name[$id] : '<b>Dropoff : </b>' . $outside_dropoff[$id];
                                                        // $text_hotel .= (!empty($zoned_name[$id])) ? ' (' . $zoned_name[$id] . ')' : '';
                                            ?>
                                                        <tr>
                                                            <td class="cell-fit">
                                                                <a href="javascript:void(0);" data-toggle="modal" data-target="#edit_manage_boat"
                                                                    onclick="modal_manage_boat(
                                                                <?php echo $manages['boat_id']; ?>, 
                                                                <?php echo $id; ?>, 
                                                                <?php echo $bomange_id[$id]; ?>, 
                                                                <?php echo $manages['id']; ?>);">
                                                                    <span class="badge badge-pill badge-light-success text-capitalized"><?php echo $manages['boat_name']; ?></span>
                                                                </a>
                                                            </td>
                                                            <td class="cell-fit">
                                                                <?php if (!empty($car_name[$id])) {
                                                                    for ($c = 0; $c < count($car_name[$id]); $c++) {
                                                                        echo $c > 0 ? '<br>' : '';
                                                                        echo '<div class="badge badge-light-success">' . $car_name[$id][$c] . '</div>';
                                                                    }
                                                                } ?>
                                                            </td>
                                                            <td><?php echo date('H:i', strtotime($start_pickup[$id])) . ' - ' . date('H:i', strtotime($end_pickup[$id])); ?></td>
                                                            <td><?php echo $text_hotel; ?></td>
                                                            <td><?php echo $room_no[$id]; ?></td>
                                                            <td><?php echo $cus_name[$id]; ?></td>
                                                            <td class="text-center"><?php echo !empty($adult[$id]) ? array_sum($adult[$id]) : 0; ?></td>
                                                            <td class="text-center"><?php echo !empty($child[$id]) ? array_sum($child[$id]) : 0; ?></td>
                                                            <td class="text-center"><?php echo !empty($infant[$id]) ? array_sum($infant[$id]) : 0; ?></td>
                                                            <td class="text-center"><?php echo !empty($foc[$id]) ? array_sum($foc[$id]) : 0; ?></td>
                                                            <td class="text-nowrap"><?php echo $agent_name[$id]; ?></td>
                                                            <td class="text-nowrap"><?php echo $sender[$id]; ?></td>
                                                            <td class="text-nowrap"><?php echo !empty($voucher_no_agent[$id]) ? $voucher_no_agent[$id] : $book_full[$id]; ?></td>
                                                            <td><b class="text-danger"><?php echo !empty($cot[$id]) ? number_format($cot[$id]) : ''; ?></b></td>
                                                            <td>
                                                                <b class="text-info">
                                                                    <?php
                                                                    if (!empty($extra_name[$id])) {
                                                                        for ($e = 0; $e < count($extra_name[$id]); $e++) {
                                                                            echo $e == 0 ? $extra_name[$id][$e] : ' : ' . $extra_name[$id][$e];
                                                                        }
                                                                    }
                                                                    echo $bp_note[$id]; ?>
                                                                </b>
                                                            </td>
                                                        </tr>
                                            <?php }
                                                }
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
                        if (!empty($bo_id)) {
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
                                            <!-- <th width="1%">รวม</th> -->
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
                                        for ($i = 0; $i < count($bo_id); $i++) {
                                            $id = $bo_id[$i];
                                            if (empty($bomange_id[$id])) {

                                                $total_adult += !empty($adult[$id]) ? array_sum($adult[$id]) : 0;
                                                $total_child += !empty($child[$id]) ? array_sum($child[$id]) : 0;
                                                $total_infant += !empty($infant[$id]) ? array_sum($infant[$id]) : 0;
                                                $total_foc += !empty($foc[$id]) ? array_sum($foc[$id]) : 0;
                                                $total_tourist += !empty($tourist_array[$id]) ? array_sum($tourist_array[$id]) : 0;
                                                $tourist = !empty($tourist_array[$id]) ? array_sum($tourist_array[$id]) : 0;

                                                $text_hotel = '';
                                                $text_hotel = (!empty($hotelp_name[$id])) ? $hotelp_name[$id] : $outside_pickup[$id];
                                                $text_hotel .= (!empty($zonep_name[$id])) ? ' (' . $zonep_name[$id] . ')' : '</br>';
                                        ?>
                                                <tr>
                                                    <td><span class="badge <?php echo $booksta_class[$id]; ?>"><?php echo $status_name[$id]; ?></span></td>
                                                    <td class="cell-fit">
                                                        <?php if (!empty($category_name[$id])) {
                                                            for ($c = 0; $c < count($category_name[$id]); $c++) {
                                                                echo $c == 0 ? $category_name[$id][$c] : ', ' . $category_name[$id][$c];
                                                            }
                                                        } ?>
                                                    </td>
                                                    <td><?php echo date('H:i', strtotime($start_pickup[$id])) . ' - ' . date('H:i', strtotime($end_pickup[$id])); ?></td>
                                                    <td><?php echo $text_hotel; ?></td>
                                                    <td><?php echo $room_no[$id]; ?></td>
                                                    <td><?php echo $cus_name[$id]; ?></td>
                                                    <td class="text-center"><?php echo !empty($adult[$id]) ? array_sum($adult[$id]) : 0; ?></td>
                                                    <td class="text-center"><?php echo !empty($child[$id]) ? array_sum($child[$id]) : 0; ?></td>
                                                    <td class="text-center"><?php echo !empty($infant[$id]) ? array_sum($infant[$id]) : 0; ?></td>
                                                    <td class="text-center"><?php echo !empty($foc[$id]) ? array_sum($foc[$id]) : 0; ?></td>
                                                    <td class="text-nowrap"><?php echo $agent_name[$id]; ?></td>
                                                    <td class="text-nowrap"><?php echo !empty($voucher_no_agent[$id]) ? $voucher_no_agent[$id] : $book_full[$id]; ?></td>
                                                    <td><b class="text-danger"><?php echo !empty($cot[$id]) ? number_format($cot[$id]) : ''; ?></td>
                                                    <td>
                                                        <b class="text-info">
                                                            <?php
                                                            if (!empty($extra_name[$id])) {
                                                                for ($e = 0; $e < count($extra_name[$id]); $e++) {
                                                                    echo $e == 0 ? $extra_name[$id][$e] : ' : ' . $extra_name[$id][$e];
                                                                }
                                                            }
                                                            echo $bp_note[$id]; ?>
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

            <!-- edit booking manage boat -->
            <div class="modal fade text-left" id="edit_manage_boat" tabindex="-1" aria-labelledby="myModalLabel18" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="myModalLabel18">แก้ใขเรือ</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <form id="edit-manage-form" name="edit-manage-form" action="" method="post" enctype="multipart/form-data">
                            <input type="hidden" id="bo_mange_id" name="bo_mange_id" value="">
                            <input type="hidden" id="brfore_manage_id" name="brfore_manage_id" value="">
                            <input type="hidden" id="edit_bo_id" name="edit_bo_id" value="">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="edit_manage">เรือ</label>
                                            <select class="form-control select2" id="edit_manage" name="edit_manage">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary waves-effect waves-float waves-light">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!------------------------------------------------------------------>
            <!-- End Form Modal -->

        </div>
    </div>