<?php
require_once 'controllers/Order.php';

$manageObj = new Order();
$today = date("Y-m-d");
$tomorrow = date("Y-m-d", strtotime(" +1 day"));
// $today = '2024-09-29';
// $tomorrow = '2024-09-30';
$get_date = !empty($_GET['date_travel_booking']) ? $_GET['date_travel_booking'] : $tomorrow; // $tomorrow->format("Y-m-d")
$search_car = !empty($_GET['search_car']) ? $_GET['search_car'] : 'all';
$search_return = !empty($_GET['search_return']) ? $_GET['search_return'] : 1;
$search_status = $_GET['search_status'] != "" ? $_GET['search_status'] : 'all';
$search_agent = $_GET['search_agent'] != "" ? $_GET['search_agent'] : 'all';
$search_product = $_GET['search_product'] != "" ? $_GET['search_product'] : 'all';
$search_voucher_no = $_GET['voucher_no'] != "" ? $_GET['voucher_no'] : '';
$refcode = $_GET['refcode'] != "" ? $_GET['refcode'] : '';
$name = $_GET['name'] != "" ? $_GET['name'] : '';
# --- show list boats booking --- #
$first_booking = array();
$first_prod = array();
$first_cus = array();
$first_program = array();
$frist_bt[1] = [];
$frist_bt[2] = [];
$first_manage = [];
$first_bo = [];
$first_trans = [];
$frist_bomange = array();
$bookings = $manageObj->showlisttransfers('all', $search_return, $get_date, $search_car, 'all', $search_status, $search_agent, $search_product, $search_voucher_no, $refcode, $name);
# --- Check products --- #
if (!empty($bookings)) {
    foreach ($bookings as $booking) {
        # --- get value Programe --- #
        if (in_array($booking['product_id'], $first_prod) == false) {
            $first_prod[] = $booking['product_id'];
            $programe_id[] = !empty($booking['product_id']) ? $booking['product_id'] : 0;
            $programe_name[] = !empty($booking['product_name']) ? $booking['product_name'] : '';
            $programe_type[] = !empty($booking['pg_type_name']) ? $booking['pg_type_name'] : '';
        }

        # --- get value booking --- #
        if (in_array($booking['id'], $first_booking) == false) {
            $first_booking[] = $booking['id'];
            $bo_id[$booking['product_id']][] = !empty($booking['id']) ? $booking['id'] : 0;
            $bo_type[$booking['id']] = !empty($booking['booktye_name']) ? $booking['booktye_name'] : '';
            $status_by_name[$booking['id']] = !empty($booking['status_by']) ? $booking['stabyFname'] . ' ' . $booking['stabyLname'] : '';
            $status[$booking['id']] = '<span class="badge badge-pill ' . $booking['booksta_class'] . ' text-capitalized"> ' . $booking['booksta_name'] . ' </span>';
            $category_name[$booking['id']] = !empty($booking['category_name']) ? $booking['category_name'] : '';
            $adult[$booking['id']] = !empty($booking['bp_adult']) ? $booking['bp_adult'] : 0;
            $child[$booking['id']] = !empty($booking['bp_child']) ? $booking['bp_child'] : 0;
            $infant[$booking['id']] = !empty($booking['bp_infant']) ? $booking['bp_infant'] : 0;
            $foc[$booking['id']] = !empty($booking['bp_foc']) ? $booking['bp_foc'] : 0;
            $cate_transfer[$booking['id']] = !empty($booking['category_transfer']) ? $booking['category_transfer'] : 0;
            $cus_name[$booking['id']][] = !empty($booking['cus_name']) ? $booking['cus_name'] : '';
            $sender[$booking['id']] = !empty($booking['sender']) ? $booking['sender'] : '';
            $note[$booking['id']] = !empty($booking['bp_note']) ? $booking['bp_note'] : '';
            $bp_id[$booking['id']] = !empty($booking['bp_id']) ? $booking['bp_id'] : 0;
            $cot[$booking['id']] = !empty($booking['total_paid']) ? $booking['total_paid'] : 0;
            $book_full[$booking['id']] = !empty($booking['book_full']) ? $booking['book_full'] : '';
            $voucher_no[$booking['id']] = !empty(!empty($booking['voucher_no_agent'])) ? $booking['voucher_no_agent'] : '';
            $travel_date[$booking['id']] = !empty(!empty($booking['travel_date'])) ? $booking['travel_date'] : '0000-00-00';
            $product_id[$booking['id']] = !empty(!empty($booking['product_id'])) ? $booking['product_id'] : '';
            $product_name[$booking['id']] = !empty(!empty($booking['product_name'])) ? $booking['product_name'] : '';
            $pier_name[$booking['id']] = !empty(!empty($booking['pier_name'])) ? $booking['pier_name'] : '';
            $agent_name[$booking['id']] = !empty($booking['comp_name']) ? $booking['comp_name'] : '';
            $boat_name[$booking['id']] = !empty($booking['boat_name']) ? $booking['boat_name'] : $booking['outside_boat'];
            # --- array programe --- #
            $prod_adult[$booking['product_id']][] = !empty($booking['bp_adult']) && $booking['mange_id'] == 0 ? $booking['bp_adult'] : 0;
            $prod_child[$booking['product_id']][] = !empty($booking['bp_child']) && $booking['mange_id'] == 0 ? $booking['bp_child'] : 0;
            $prod_infant[$booking['product_id']][] = !empty($booking['bp_infant']) && $booking['mange_id'] == 0 ? $booking['bp_infant'] : 0;
            $prod_foc[$booking['product_id']][] = !empty($booking['bp_foc']) && $booking['mange_id'] == 0 ? $booking['bp_foc'] : 0;
            # --- array manage --- #
            $book['adult'][$booking['mange_id']][] = !empty($booking['bp_adult']) ? $booking['bp_adult'] : 0;
            $book['child'][$booking['mange_id']][] = !empty($booking['bp_child']) ? $booking['bp_child'] : 0;
            $book['infant'][$booking['mange_id']][] = !empty($booking['bp_infant']) ? $booking['bp_infant'] : 0;
            $book['foc'][$booking['mange_id']][] = !empty($booking['bp_foc']) ? $booking['bp_foc'] : 0;
        }

        # --- get value booking transfer --- #
        if ((in_array($booking['bt_id'], $frist_bt[1]) == false)) {
            $frist_bt[1][] = $booking['bt_id'];
            $bt_id[$booking['id']][1] = !empty($booking['bt_id']) ? $booking['bt_id'] : 0;
            $mange_id[$booking['id']][1] = !empty($booking['mange_id']) ? $booking['mange_id'] : 0;
            $arrange[$booking['id']][1] = !empty($booking['arrange']) ? $booking['arrange'] : 0;
            $bt_adult[$booking['id']][1] = !empty($booking['bt_adult']) ? $booking['bt_adult'] : 0;
            $bt_child[$booking['id']][1] = !empty($booking['bt_child']) ? $booking['bt_child'] : 0;
            $bt_infant[$booking['id']][1] = !empty($booking['bt_infant']) ? $booking['bt_infant'] : 0;
            $bt_foc[$booking['id']][1] = !empty($booking['bt_foc']) ? $booking['bt_foc'] : 0;
            $hotel_name[$booking['id']][1] = !empty($booking['pickup_name']) ? $booking['pickup_name'] : $booking['outside'];
            $hotel_name[$booking['id']][2] = !empty($booking['dropoff_name']) ? $booking['dropoff_name'] : $booking['outside_dropoff'];
            $room_no[$booking['id']][1] = !empty($booking['room_no']) ? $booking['room_no'] : '';
            $start_pickup[$booking['id']][1] = !empty($booking['start_pickup']) && $booking['start_pickup'] != '00:00' ? $booking['start_pickup'] : '00:00';
            $end_pickup[$booking['id']][1] = !empty($booking['end_pickup']) && $booking['end_pickup'] != '00:00' ? $booking['end_pickup'] : '00:00';
            $outside[$booking['id']][1] = !empty($booking['outside']) ? $booking['outside'] : '';
            $outside[$booking['id']][2] = !empty($booking['outside_dropoff']) ? $booking['outside_dropoff'] : '';

            if (($booking['pickup_id'] != $booking['dropoff_id']) || ($booking['outside'] != $booking['outside_dropoff'])) {
                $check_dropoff[$booking['product_id']][] = !empty($booking['id']) ? $booking['id'] : 0;
            }

            if ($booking['mange_id'] > 0) {
                $bo_manage['id'][$booking['mange_id']][1][] = !empty($booking['id']) ? $booking['id'] : 0;
            }
        }

        if ($booking['mange_id'] > 0 && (in_array($booking['bomange_id'], $frist_bomange) == false)) {
            $frist_bomange[] = $booking['bomange_id'];
            $reteun = $booking['mange_pickup'] > 0 ? 1 : 2;
            $bomange_id[$booking['mange_id']][] = !empty($booking['bomange_id']) ? $booking['bomange_id'] : 0;
            $bomange_bo[$booking['mange_id']][] = !empty($booking['id']) ? $booking['id'] : 0;
            $check_book[$reteun][] = !empty($booking['id']) ? $booking['id'] : 0;
            $check_bt[$reteun][] = !empty($booking['bt_id']) ? $booking['bt_id'] : 0;
            $check_mange[$reteun][$booking['product_id']][] = !empty($booking['mange_id']) ? $booking['mange_id'] : 0;
        }
    }
}
# --- get data --- #
$manages = $manageObj->show_manage_transfer($get_date, $search_return);
foreach ($manages as $manage) {
    if (in_array($manage['id'], $first_manage) == false) {
        $first_manage[] = $manage['id'];
        $mange['id'][] = !empty($manage['id']) ? $manage['id'] : 0;
        $mange['seat'][] = !empty($manage['seat']) ? $manage['seat'] : 0;
        $mange['pickup'][] = !empty($manage['pickup']) ? $manage['pickup'] : 0;
        // $mange['dropoff'][] = !empty($manage['dropoff']) ? $manage['dropoff'] : 0;
        $mange['car'][] = !empty($manage['car_id']) ? $manage['car_name'] : '';
        $mange['driver'][] = !empty($manage['driver']) ? $manage['driver'] : '';
        $mange['license'][] = !empty($manage['license']) ? $manage['license'] : '';
        $mange['telephone'][] = !empty($manage['telephone']) ? $manage['telephone'] : '';
        $mange['driver_id'][] = !empty($manage['driver_id']) ? $manage['driver_id'] : 0;
        $mange['driver_name'][] = !empty($manage['driver_name']) ? $manage['driver_name'] : $manage['outside_driver'];
        $mange['guide_id'][] = !empty($manage['guide_id']) ? $manage['guide_id'] : 0;
        $mange['guide_name'][] = !empty($manage['guide_id']) ? $manage['guide_name'] : '';
        $mange['car_id'][] = !empty($manage['car_id']) ? $manage['car_id'] : 0;
        $mange['car_name'][] = !empty($manage['car_id']) ? $manage['car_name'] : $manage['outside_car'];
        $mange['outside_car'][] = !empty($manage['outside_car']) ? $manage['outside_car'] : '';
        $mange['note'][] = !empty($manage['note']) ? $manage['note'] : '';

        $arr_trans['mange_id'][] = !empty($manage['id']) ? $manage['id'] : 0;
        $arr_trans['pickup'][] = !empty($manage['pickup']) ? $manage['pickup'] : 0;
        $arr_trans['dropoff'][] = !empty($manage['dropoff']) ? $manage['dropoff'] : 0;
        $arr_trans['driver_id'][] = !empty($manage['driver_id']) ? $manage['driver_id'] : 0;
        $arr_trans['name'][] = !empty($manage['driver_name']) ? $manage['driver_name'] : $manage['outside_driver'];
        $arr_trans['car'][] = !empty($manage['car_name']) ? $manage['car_name'] : '';
        $arr_trans['driver'][] = !empty($manage['driver']) ? $manage['driver'] : '';
        $arr_trans['license'][] = !empty($manage['license']) ? $manage['license'] : '';
        $arr_trans['telephone'][] = !empty($manage['telephone']) ? $manage['telephone'] : '';
        $arr_trans['note'][] = !empty($manage['note']) ? $manage['note'] : '';
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
                        <h2 class="content-header-title float-left mb-0">จัดรถ</h2>
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
            <!-- list start -->
            <section class="app-booking-list">
                <!-- filter start -->
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
                                        $bookstype = $manageObj->showliststatus();
                                        foreach ($bookstype as $booktype) {
                                            $selected = $search_status == $booktype['id'] ? 'selected' : '';
                                        ?>
                                            <option value="<?php echo $booktype['id']; ?>" <?php echo $selected; ?>><?php echo $booktype['name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label for="search_agent">Agent</label>
                                    <select class="form-control select2" id="search_agent" name="search_agent">
                                        <option value="all">All</option>
                                        <?php
                                        $agents = $manageObj->showlistagent();
                                        foreach ($agents as $agent) {
                                            $selected = $search_agent == $agent['id'] ? 'selected' : '';
                                        ?>
                                            <option value="<?php echo $agent['id']; ?>" <?php echo $selected; ?>><?php echo $agent['name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label for="search_product">Programe</label>
                                    <select class="form-control select2" id="search_product" name="search_product">
                                        <option value="all">All</option>
                                        <?php
                                        $products = $manageObj->showlistproduct();
                                        foreach ($products as $product) {
                                            $selected = $search_product == $product['id'] ? 'selected' : '';
                                        ?>
                                            <option value="<?php echo $product['id']; ?>" <?php echo $selected; ?>><?php echo $product['name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label for="search_ccar">ชื่อรถ</label>
                                    <select class="form-control select2" id="search_ccar" name="search_car">
                                        <option value="all">All</option>
                                        <?php
                                        $cars = $manageObj->show_cars();
                                        foreach ($cars as $car) {
                                            $selected_car = ($car['id'] == $search_car) ? 'selected' : '';
                                        ?>
                                            <option value="<?php echo $car['id']; ?>" data-name="<?php echo $car['name']; ?>" <?php echo $selected_car; ?>><?php echo $car['name']; ?></option>
                                        <?php
                                        }
                                        ?>
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
                            <input type="hidden" id="pickup_retrun" name="search_retrun" value="1">
                            <div class="col-md-4 col-12">
                                <button type="submit" class="btn btn-primary">Search</button>
                                <button type="button" class="btn btn-success waves-effect waves-float waves-light btn-page-block-spinner" data-toggle="modal" data-target="#modal-transfers" onclick="modal_transfer('<?php echo date('j F Y', strtotime($get_date)); ?>', 0, 0, 1);"><i data-feather='plus'></i> เปิดรถ</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- filter end -->

                <div class="card">
                    <!-- Start Table Programe -->
                    <!------------------------------------------------------------------>
                    <div id="div-manages-list">
                        <textarea id="array_trans" hidden><?php echo !empty($arr_trans) ? json_encode($arr_trans, true) : ''; ?></textarea>
                        <?php
                        if (!empty($mange['id'])) {
                            for ($i = 0; $i < count($mange['id']); $i++) {
                                if ($mange['pickup'][$i] == 1) {
                                    $return = $mange['pickup'][$i] == 1 ? 1 : 2;
                                    $text_retrun = $mange['pickup'][$i] == 1 ? 'Pickup' : 'Dropoff';
                                    $mange_retrun = 1;
                        ?>
                                    <div class="card-body pt-0 p-50">
                                        <div class="d-flex justify-content-between align-items-center header-actions mx-1 row mt-75">
                                            <div class="col-4 text-left text-bold h4"></div>
                                            <div class="col-4 text-center text-bold h4"><?php echo !empty($mange['car'][$i]) ? $mange['car'][$i] : ''; ?></div>
                                            <div class="col-4 text-right mb-50">
                                                <button type="button" class="btn btn-icon btn-icon rounded-circle btn-flat-info waves-effect btn-page-block-spinner" data-toggle="modal" data-target="#modal-booking" onclick="search_booking('not', '<?php echo $get_date; ?>', <?php echo $return; ?>, <?php echo $mange['id'][$i]; ?>, '<?php echo $mange['car'][$i]; ?>', <?php echo $mange['seat'][$i]; ?>);">เพิ่ม Booking</button> <!--- <i data-feather='plus-circle'></i> --->
                                                <button type="button" class="btn btn-icon btn-icon rounded-circle btn-flat-warning waves-effect btn-page-block-spinner" data-toggle="modal" data-target="#modal-transfers" onclick="modal_transfer('<?php echo date('j F Y', strtotime($get_date)); ?>', <?php echo $mange['id'][$i]; ?>, <?php echo $i; ?>, 1)">แก้ใขรถ</button> <!--- <i data-feather='plus-edit'></i> --->
                                                <input type="hidden" id="arr_mange<?php echo $mange['id'][$i]; ?>" value='<?php echo json_encode($mange, JSON_HEX_APOS, JSON_UNESCAPED_UNICODE); ?>'>
                                            </div>
                                        </div>
                                        <table class="table table-bordered table-striped">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th colspan="3">คนขับ : <?php echo $mange['driver_name'][$i]; ?></th>
                                                    <th colspan="7">ป้ายทะเบียน : <?php echo $mange['license'][$i]; ?></th>
                                                    <th colspan="4">โทรศัพท์ : <?php echo $mange['telephone'][$i]; ?></th>
                                                </tr>
                                                <tr>
                                                    <th class="cell-fit text-center">รถ</th>
                                                    <!-- <th class="cell-fit text-center">เรือ</th> -->
                                                    <th>Programe</th>
                                                    <th>Time</th>
                                                    <th>Hotel</th>
                                                    <th>Room</th>
                                                    <th>Client</th>
                                                    <th class="text-center">A</th>
                                                    <th class="text-center">C</th>
                                                    <th class="text-center">Inf</th>
                                                    <th class="text-center">FOC</th>
                                                    <th>AGENT</th>
                                                    <th>SENDER</th>
                                                    <th>V/C</th>
                                                    <th>Remark</th>
                                                </tr>
                                            </thead>
                                            <?php if ($bomange_bo[$mange['id'][$i]]) { ?>
                                                <tbody>
                                                    <?php
                                                    $total_tourist = 0;
                                                    $total_adult = 0;
                                                    $total_child = 0;
                                                    $total_infant = 0;
                                                    $total_foc = 0;
                                                    for ($a = 0; $a < count($bomange_bo[$mange['id'][$i]]); $a++) {
                                                        $id = $bomange_bo[$mange['id'][$i]][$a];
                                                        $total_tourist = $total_tourist + $bt_adult[$id][$mange_retrun] + $bt_child[$id][$mange_retrun] + $bt_infant[$id][$mange_retrun] + $bt_foc[$id][$mange_retrun];
                                                        $total_adult = $total_adult + $bt_adult[$id][$mange_retrun];
                                                        $total_child = $total_child + $bt_child[$id][$mange_retrun];
                                                        $total_infant = $total_infant + $bt_infant[$id][$mange_retrun];
                                                        $total_foc = $total_foc + $bt_foc[$id][$mange_retrun];
                                                    ?>
                                                        <a href="javascripy:void(0);">
                                                            <tr>
                                                                <td><a href="javascript:void(0);" data-toggle="modal" data-target="#edit_manage_transfer" onclick="modal_manage_transfer(<?php echo $bt_id[$id][$mange_retrun]; ?>, <?php echo $return; ?>, <?php echo $mange['id'][$i]; ?>);"><span class="badge badge-pill badge-light-success text-capitalized"><?php echo $mange['car'][$i]; ?></span><?php // echo $bt_id[$id][$mange_retrun]; 
                                                                                                                                                                                                                                                                                                                                                                                                            ?></a></td>
                                                                <!-- <td><?php echo $boat_name[$id]; ?></td> -->
                                                                <td><?php echo $product_name[$id]; ?></td>
                                                                <td><?php echo $start_pickup[$id][$mange_retrun] != '00:00' ? date('H:i', strtotime($start_pickup[$id][$mange_retrun])) . ' - ' . date('H:i', strtotime($end_pickup[$id][$mange_retrun])) : ''; ?></td>
                                                                <td><?php echo (!empty($outside[$id][$return])) ? $outside[$id][$return] : $hotel_name[$id][$return]; ?></td>
                                                                <td><?php echo $room_no[$id][$mange_retrun]; ?></td>
                                                                <td><?php echo $cus_name[$id][0]; ?></td>
                                                                <td class="text-center"><?php echo $bt_adult[$id][$mange_retrun]; ?></td>
                                                                <td class="text-center"><?php echo $bt_child[$id][$mange_retrun]; ?></td>
                                                                <td class="text-center"><?php echo $bt_infant[$id][$mange_retrun]; ?></td>
                                                                <td class="text-center"><?php echo $bt_foc[$id][$mange_retrun]; ?></td>
                                                                <td><?php echo $agent_name[$id]; ?></td>
                                                                <td><?php echo $sender[$id]; ?></td>
                                                                <td><?php echo !empty($voucher_no[$id]) ? $voucher_no[$id] : $book_full[$id]; ?></td>
                                                                <td><b class="text-info"><?php echo $note[$id]; ?></b></td>
                                                            </tr>
                                                        </a>
                                                    <?php } ?>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="16" class="text-center h5">Total: <?php echo $total_tourist; ?> | <?php echo $total_adult; ?> <?php echo $total_child; ?> <?php echo $total_infant; ?> <?php echo $total_foc; ?></td>
                                                    </tr>
                                                </tfoot>
                                            <?php } ?>
                                        </table>
                                    </div>
                        <?php }
                            }
                        } ?>
                    </div>
                    <!------------------------------------------------------------------>
                    <!-- End Table Programe -->

                    <!-- Start Management Transfer -->
                    <!------------------------------------------------------------------>
                    <div id="div-booking-list">
                        <?php if (!empty($programe_id)) { ?>
                            <div class="divider divider-dark">
                                <div class="divider-text">
                                    <h3 class="text-bold mb-0">รถที่ยังไม่ได้จัด</h3>
                                </div>
                            </div>
                            <?php
                            $retrun = 1;
                            for ($a = 0; $a < count($programe_id); $a++) {
                                if (!empty($bo_id[$programe_id[$a]])) {
                            ?>
                                    <div class="card-body pt-0 p-50">
                                        <div class="d-flex justify-content-between align-items-center header-actions mx-1 row mt-75">
                                            <div class="col-lg-12 col-xl-12 text-center text-bold h4"><?php echo $programe_name[$a]; ?></div>
                                        </div>
                                        <table class="table table-bordered table-striped">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th class="cell-fit text-center">คนขับ</th>
                                                    <th class="cell-fit text-center">STATUS</th>
                                                    <th class="text-nowrap">TRAVEL DATE</th>
                                                    <th class="text-nowrap">TIME</th>
                                                    <th>HOTEL</th>
                                                    <th class="text-nowrap">ROOM</th>
                                                    <th class="text-nowrap">Name</th>
                                                    <th>A</th>
                                                    <th>C</th>
                                                    <th>INF</th>
                                                    <th>FOC</th>
                                                    <th class="text-nowrap">AGENT</th>
                                                    <th class="text-nowrap">V/C</th>
                                                    <th class="text-nowrap">COT</th>
                                                    <th>REMARKE</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $total_tourist = 0;
                                                $total_adult = 0;
                                                $total_child = 0;
                                                $total_infant = 0;
                                                $total_foc = 0;
                                                for ($i = 0; $i < count($bo_id[$programe_id[$a]]); $i++) {
                                                    if (empty($check_book[1]) || !empty($check_book[1]) && in_array($bo_id[$programe_id[$a]][$i], $check_book[1]) == false) {
                                                        $total_tourist = $total_tourist + $adult[$bo_id[$programe_id[$a]][$i]] + $child[$bo_id[$programe_id[$a]][$i]] + $infant[$bo_id[$programe_id[$a]][$i]] + $foc[$bo_id[$programe_id[$a]][$i]];
                                                        $total_adult = $total_adult + $adult[$bo_id[$programe_id[$a]][$i]];
                                                        $total_child = $total_child + $child[$bo_id[$programe_id[$a]][$i]];
                                                        $total_infant = $total_infant + $infant[$bo_id[$programe_id[$a]][$i]];
                                                        $total_foc = $total_foc + $foc[$bo_id[$programe_id[$a]][$i]];
                                                ?>
                                                        <tr>
                                                            <td><a href="javascript:void(0);" data-toggle="modal" data-target="#edit_manage_transfer" onclick="modal_manage_transfer(<?php echo $bt_id[$bo_id[$programe_id[$a]][$i]][$retrun]; ?>, 1, 0);"><span class="badge badge-light-danger">ไม่มีการจัดรถ <?php // echo $bt_id[$bo_id[$programe_id[$a]][$i]][$retrun]; 
                                                                                                                                                                                                                                                                                                                ?></span></a></td>
                                                            <td><?php echo $status[$bo_id[$programe_id[$a]][$i]]; ?></td>
                                                            <td><span class="text-nowrap"><?php echo (!empty($bp_id[$bo_id[$programe_id[$a]][$i]])) ? date('j F Y', strtotime($travel_date[$bo_id[$programe_id[$a]][$i]])) : 'ไม่มีสินค้า'; ?></span></td>
                                                            <td><?php echo !empty($start_pickup[$bo_id[$programe_id[$a]][$i]][$retrun]) ? date("H:i", strtotime($start_pickup[$bo_id[$programe_id[$a]][$i]][$retrun])) . ' - ' . date("H:i", strtotime($end_pickup[$bo_id[$programe_id[$a]][$i]][$retrun])) : '00:00'; ?></td>
                                                            <td><?php echo (!empty($hotel_name[$bo_id[$programe_id[$a]][$i]][$retrun])) ? $hotel_name[$bo_id[$programe_id[$a]][$i]][$retrun] : $outside[$bo_id[$programe_id[$a]][$i]][$retrun];
                                                                // echo ' | ' . $outside[$bo_id[$programe_id[$a]][$i]][2]; 
                                                                ?></td>
                                                            <td><?php echo (!empty($room_no[$bo_id[$programe_id[$a]][$i]][$retrun])) ? $room_no[$bo_id[$programe_id[$a]][$i]][$retrun] : ''; ?></td>
                                                            <td><?php echo !empty($cus_name[$bo_id[$programe_id[$a]][$i]][0]) ? $cus_name[$bo_id[$programe_id[$a]][$i]][0] : ''; ?></td>
                                                            <td class="text-center"><?php echo $bt_adult[$bo_id[$programe_id[$a]][$i]][$retrun]; ?></td>
                                                            <td class="text-center"><?php echo $bt_child[$bo_id[$programe_id[$a]][$i]][$retrun]; ?></td>
                                                            <td class="text-center"><?php echo $bt_infant[$bo_id[$programe_id[$a]][$i]][$retrun]; ?></td>
                                                            <td class="text-center"><?php echo $bt_foc[$bo_id[$programe_id[$a]][$i]][$retrun]; ?></td>
                                                            <td><?php echo $agent_name[$bo_id[$programe_id[$a]][$i]]; ?></a></td>
                                                            <td><?php echo !empty($voucher_no[$bo_id[$programe_id[$a]][$i]]) ? $voucher_no[$bo_id[$programe_id[$a]][$i]] : $book_full[$bo_id[$programe_id[$a]][$i]]; ?></td>
                                                            <td class="text-nowrap"><?php echo number_format($cot[$bo_id[$programe_id[$a]][$i]]); ?></td>
                                                            <td><?php echo $note[$bo_id[$programe_id[$a]][$i]]; ?></td>
                                                        </tr>
                                                <?php }
                                                } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="15" class="text-center h5">Total: <?php echo $total_tourist; ?> | <?php echo $total_adult; ?> <?php echo $total_child; ?> <?php echo $total_infant; ?> <?php echo $total_foc; ?></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                        <?php }
                            }
                        } ?>
                    </div>
                    <!------------------------------------------------------------------>
                    <!-- End Management Transfer -->
                </div>
            </section>


            <!-- Start Form Modal -->
            <!------------------------------------------------------------------>
            <!-- action boat -->
            <div class="modal-size-xl d-inline-block">
                <div class="modal fade text-left" id="modal-transfers" tabindex="-1" role="dialog" aria-labelledby="myModalLabel17" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="myModalLabel17">จัดรถ (<span id="text-travel-date"></span>)</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div id="show-div-cus"></div>
                                <form id="transfer-form" name="transfer-form" action="" method="post" enctype="multipart/form-data">
                                    <input type="hidden" id="manage_id" name="manage_id" value="">
                                    <input type="hidden" id="retrun" name="retrun" value="">
                                    <div class="row">
                                        <div class="col-md-3 col-12">
                                            <div class="form-group" id="frm-car">
                                                <label for="car">ชื่อรถ</label>
                                                <select class="form-control select2" id="car" name="car" onchange="check_outside('car');">
                                                    <option value="0">กรุญาเลือกรถ...</option>
                                                    <option value="outside">กรอกข้อมูลเพิ่มเติม</option>
                                                    <?php
                                                    $cars = $manageObj->show_cars();
                                                    foreach ($cars as $car) {
                                                    ?>
                                                        <option value="<?php echo $car['id']; ?>" data-name="<?php echo $car['name']; ?>"><?php echo $car['name']; ?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group" id="frm-car-outside" hidden>
                                                <label class="form-label" for="outside_car">กรอกข้อมูลเพิ่มเติม </label></br>
                                                <div class="input-group input-group-merge mb-2">
                                                    <input type="text" class="form-control" id="outside_car" name="outside_car" value="" />
                                                    <div class="input-group-append" onclick="check_outside('outside_car');">
                                                        <span class="input-group-text cursor-pointer"><i data-feather='x'></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-12">
                                            <div class="form-group" id="frm-driver">
                                                <label for="driver">ชื่อคนขับ</label>
                                                <select class="form-control select2" id="driver" name="driver" onchange="check_driver();">
                                                    <option value="0">กรุญาเลือกคนขับ...</option>
                                                    <option value="outside">กรอกข้อมูลเพิ่มเติม</option>
                                                    <?php
                                                    $drivers = $manageObj->show_drivers();
                                                    foreach ($drivers as $driver) {
                                                    ?>
                                                        <option value="<?php echo $driver['id']; ?>" data-name="<?php echo $driver['name']; ?>" data-seat="<?php echo $driver['seat']; ?>" data-license="<?php echo $driver['number_plate']; ?>" data-telephone="<?php echo $driver['telephone']; ?>"><?php echo $driver['name']; ?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group" id="frm-driver-outside" hidden>
                                                <label class="form-label" for="outside_driver">กรอกข้อมูลเพิ่มเติม </label></br>
                                                <div class="input-group input-group-merge mb-2">
                                                    <input type="text" class="form-control" id="outside_driver" name="outside_driver" value="" />
                                                    <div class="input-group-append" onclick="check_outside('outside_driver');">
                                                        <span class="input-group-text cursor-pointer"><i data-feather='x'></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-3 col-12">
                                            <label for="seat">ที่นั่ง</label>
                                            <select class="form-control select2" id="seat" name="seat">
                                                <option value="0">กรุญาเลือกจำนวนที่นั่ง...</option>
                                                <option value="10">10</option>
                                                <option value="12">12</option>
                                                <option value="13">13</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3 col-12">
                                            <div class="form-group">
                                                <label class="form-label" for="license">ป้ายทะเบียน</label></br>
                                                <input type="text" class="form-control" id="license" name="license" value="" />
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-12">
                                            <div class="form-group">
                                                <label class="form-label" for="telephone">โทรศัพท์</label></br>
                                                <input type="text" class="form-control" id="telephone" name="telephone" value="" />
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-12">
                                            <div class="form-group">
                                                <label class="form-label" for="note">หมายเหตุ</label></br>
                                                <textarea name="note" id="note" class="form-control" cols="30" rows="3"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <hr />
                                    <div class="d-flex justify-content-between">
                                        <button type="button" class="btn btn-danger" id="delete_manage" onclick="delete_transfer();">Delete</button>
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
            <div class="modal fade text-left" id="edit_manage_transfer" tabindex="-1" aria-labelledby="myModalLabel18" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="myModalLabel18">แก้ใขคนขับ</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <form id="edit-manage-form" name="edit-manage-form" action="" method="post" enctype="multipart/form-data">
                            <input type="hidden" id="brfore_manage_id" name="brfore_manage_id" value="">
                            <input type="hidden" id="edit_bt_id" name="edit_bt_id" value="">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="edit_manage">รถ</label>
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