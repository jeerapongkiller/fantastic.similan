<?php
require_once 'controllers/Order.php';

$manageObj = new Order();
$today = date("Y-m-d");
$tomorrow = new DateTime('tomorrow');
$get_date = !empty($_GET['date_travel_booking']) ? $_GET['date_travel_booking'] : $tomorrow->format("Y-m-d"); // $tomorrow->format("Y-m-d")
$search_car = !empty($_GET['search_car']) ? $_GET['search_car'] : 'all';
$search_return = !empty($_GET['search_return']) ? $_GET['search_return'] : 1;
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
$bookings = $manageObj->showlisttransfers('all', $search_return, $get_date, $search_car, 'all');
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
        $mange['dropoff'][] = !empty($manage['dropoff']) ? $manage['dropoff'] : 0;
        $mange['car'][] = !empty($manage['car_id']) ? $manage['car_name'] : '';
        $mange['driver'][] = !empty($manage['driver']) ? $manage['driver'] : '';
        $mange['license'][] = !empty($manage['license']) ? $manage['license'] : '';
        $mange['telephone'][] = !empty($manage['telephone']) ? $manage['telephone'] : '';
        $mange['driver_id'][] = !empty($manage['driver_id']) ? $manage['driver_id'] : 0;
        $mange['driver_name'][] = !empty($manage['driver_id']) ? $manage['driver_name'] : '';
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
        $arr_trans['name'][] = !empty($manage['driver_name']) ? $manage['driver_name'] : $manage['driver'];
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

        <div class="content-body">
            <!-- Sortable lists section start -->
            <section id="sortable-lists">
                <div class="bs-stepper horizontal-wizard-example">
                    <div class="bs-stepper-header">
                        <div class="step" data-target="#preview-pickup">
                            <button type="button" class="step-trigger">
                                <span class="bs-stepper-box">1</span>
                                <span class="bs-stepper-label">
                                    <span class="bs-stepper-title">ใบจัดรถ Pickup</span>
                                    <span class="bs-stepper-subtitle">จัดการรถ</span>
                                </span>
                            </button>
                        </div>
                        <div class="step" data-target="#preview-dropoff">
                            <button type="button" class="step-trigger">
                                <span class="bs-stepper-box">2</span>
                                <span class="bs-stepper-label">
                                    <span class="bs-stepper-title">ใบจัดรถ Drop off</span>
                                    <span class="bs-stepper-subtitle">Preview เอกสารการจัดรถ</span>
                                </span>
                            </button>
                        </div>
                    </div>

                    <div class="bs-stepper-content p-0">
                        <!-- Management Car (Pickup) -->
                        <div id="preview-pickup" class="content">
                            <div class="content-header">
                                <h5 class="pt-1 pl-2 pb-0">Search Filter</h5>
                                <form id="booking-search-form" name="booking-search-form" method="get" enctype="multipart/form-data">
                                    <input type="hidden" name="pages" value="<?php echo $_GET['pages']; ?>">
                                    <div class="d-flex align-items-center mx-50 row pt-0 pb-0">
                                        <div class="col-md-3 col-12">
                                            <div class="form-group">
                                                <label class="form-label" for="date_travel_booking">วันที่เที่ยว (Travel Date)</label></br>
                                                <input type="text" class="form-control date-picker" id="date_travel_booking" name="date_travel_booking" value="<?php echo $get_date; ?>" />
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
                                            <button type="submit" class="btn btn-primary">Search</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <hr class="pb-0 pt-0">
                            <div class="card-body pt-0 p-50">
                                <a href='./?pages=order-driver/print&date_travel=<?php echo $get_date; ?>&search_car=<?php echo $search_car; ?>&retrun=1&action=print' target="_blank"><button class="btn btn-info" id="print-btn">Print</button></a>
                                <button type="button" class="btn btn-info waves-effect waves-float waves-light btn-page-block-spinner" onclick="download_image(1);">Image</button>
                            </div>
                            <div id="div-driver-job-pickup" style="background-color: #FFF;">
                                <!-- Header starts -->
                                <div class="card-body pb-0">
                                    <div class="d-flex justify-content-between flex-md-row flex-column invoice-spacing mt-0 mb-1">
                                        <div>
                                            <span class="brand-logo"><img src="app-assets/images/logo/logo-500.png" height="150"></span>
                                        </div>
                                        <div>
                                            <span style="color: #000;">
                                                <?php echo $main_document; ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body pb-0">
                                    <div class="text-center card-text">
                                        <h4 class="font-weight-bolder">ใบจัดรถ</h4>
                                        <div class="badge badge-pill badge-light-danger">
                                            <h5 class="m-0 pl-1 pr-1 text-danger"><?php echo date('j F Y', strtotime($get_date)); ?></h5>
                                        </div>
                                    </div>
                                </div>
                                </br>
                                <!-- Header ends -->
                                <!-- Body starts -->
                                <?php
                                if (!empty($mange['id'])) {
                                    for ($i = 0; $i < count($mange['id']); $i++) {
                                        if ($mange['pickup'][$i] == 1) {
                                            $return = $mange['pickup'][$i] == 1 ? 1 : 2;
                                            $text_retrun = $mange['pickup'][$i] == 1 ? 'Pickup' : 'Dropoff';
                                            $mange_retrun = 1;
                                            if ($bomange_bo[$mange['id'][$i]]) {
                                ?>
                                                <div class="d-flex justify-content-between align-items-center header-actions mx-1 row mt-75">
                                                    <div class="col-4 text-left text-bold h4"></div>
                                                    <div class="col-4 text-center text-bold h4"><?php echo !empty($mange['car'][$i]) ? $mange['car'][$i]: ''; ?></div>
                                                    <div class="col-4 text-right mb-50"></div>
                                                </div>

                                                <table class="table table-striped text-uppercase table-vouchure-t2">
                                                    <thead class="bg-light">
                                                        <tr>
                                                            <th colspan="6">ป้ายทะเบียน : <?php echo $mange['license'][$i]; ?></th>
                                                            <th colspan="7">โทรศัพท์ : <?php echo $mange['telephone'][$i]; ?></th>
                                                        </tr>
                                                        <tr>
                                                            <th width="5%">Time</th>
                                                            <th width="10%">Programe</th>
                                                            <th width="15%">Hotel</th>
                                                            <th width="6%">Room</th>
                                                            <th width="15%">Client</th>
                                                            <th width="1%" class="text-center">A</th>
                                                            <th width="1%" class="text-center">C</th>
                                                            <th width="1%" class="text-center">Inf</th>
                                                            <th width="1%" class="text-center">FOC</th>
                                                            <th width="5%" class="text-center">COT</th>
                                                            <th width="10%" class="text-center">Voucher</th>
                                                            <th width="5%" class="text-center">Type</th>
                                                            <th width="15%">Remark</th>
                                                        </tr>
                                                    </thead>
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
                                                            <tr>
                                                                <td><?php echo $start_pickup[$id][$mange_retrun] != '00:00' ? date('H:i', strtotime($start_pickup[$id][$mange_retrun])) : ''; ?></td>
                                                                <td><?php echo $product_name[$id]; ?></td>
                                                                <td><?php echo $mange['pickup'][$i] == 1 ? $outside[$id][1] : $outside[$id][2]; ?></td>
                                                                <td><?php echo $room_no[$id][$mange_retrun]; ?></td>
                                                                <td><?php echo $cus_name[$id][0]; ?></td>
                                                                <td class="text-center"><?php echo $bt_adult[$id][$mange_retrun]; ?></td>
                                                                <td class="text-center"><?php echo $bt_child[$id][$mange_retrun]; ?></td>
                                                                <td class="text-center"><?php echo $bt_infant[$id][$mange_retrun]; ?></td>
                                                                <td class="text-center"><?php echo $bt_foc[$id][$mange_retrun]; ?></td>
                                                                <td class="text-center"><?php echo $cot[$id]; ?></td>
                                                                <td class="text-center"><?php echo !empty($voucher_no[$id]) ? $voucher_no[$id] : $book_full[$id]; ?></td>
                                                                <td><?php echo $bo_type[$id]; ?></td>
                                                                <td><?php echo $note[$id]; ?></td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>

                                                <div class="text-center mt-2 pb-5">
                                                    <h4>
                                                        <div class="badge badge-pill badge-light-warning">
                                                            <b class="text-danger">TOTAL <?php echo $total_tourist; ?></b> | <?php echo $total_adult; ?> <?php echo $total_child; ?> <?php echo $total_infant; ?> <?php echo $total_foc; ?>
                                                        </div>
                                                    </h4>
                                                </div>

                                    <?php  }
                                        }
                                    }  ?>
                                    <input type="hidden" id="name_img_pickup" name="name_img_pickup" value="<?php echo 'ใบจัดรถ pickup - ' . date('j F Y', strtotime($get_date)); ?>">
                                <?php } ?>
                                <!-- Body ends -->
                            </div>
                        </div>
                        <!-- Preview (Pickup) Job Driver -->
                        <div id="preview-dropoff" class="content">
                            <!-- Search Filter Start -->
                            <div class="content-header">
                                <h5 class="pt-1 pl-2 pb-0">Search Filter</h5>
                                <form id="booking-search-form" name="booking-search-form" method="get" enctype="multipart/form-data">
                                    <input type="hidden" name="pages" value="<?php echo $_GET['pages']; ?>">
                                    <div class="d-flex align-items-center mx-50 row pt-0 pb-0">
                                        <div class="col-md-3 col-12">
                                            <div class="form-group">
                                                <label class="form-label" for="search_date_travel">วันที่เที่ยว (Travel Date)</label></br>
                                                <input type="text" class="form-control date-picker" id="search_date_travel" name="date_travel_booking" value="<?php echo $get_date; ?>" />
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-12">
                                            <div class="form-group">
                                                <label for="search_car">ชื่อรถ</label>
                                                <select class="form-control select2" id="search_car" name="search_car">
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
                                            <button type="submit" class="btn btn-primary">Search</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <hr class="pb-0 pt-0">
                            <div class="card-body pt-0 p-50">
                                <a href='./?pages=order-driver/print&date_travel=<?php echo $get_date; ?>&search_car=<?php echo $search_car; ?>&retrun=2&action=print' target="_blank"><button class="btn btn-info" id="print-btn">Print</button></a>
                                <button type="button" class="btn btn-info waves-effect waves-float waves-light btn-page-block-spinner" onclick="download_image(2);">Image</button>
                            </div>
                            <div id="div-driver-job-dropoff" style="background-color: #FFF;">
                                <!-- Header starts -->
                                <div class="card-body pb-0">
                                    <div class="d-flex justify-content-between flex-md-row flex-column invoice-spacing mt-0 mb-1">
                                        <div>
                                            <span class="brand-logo"><img src="app-assets/images/logo/logo-500.png" height="150"></span>
                                        </div>
                                        <div>
                                            <span style="color: #000;">
                                                <?php echo $main_document; ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body pb-0">
                                    <div class="text-center card-text">
                                        <h4 class="font-weight-bolder">ใบจัดรถ</h4>
                                        <div class="badge badge-pill badge-light-danger">
                                            <h5 class="m-0 pl-1 pr-1 text-danger"><?php echo date('j F Y', strtotime($get_date)); ?></h5>
                                        </div>
                                    </div>
                                </div>
                                </br>
                                <!-- Header ends -->
                                <!-- Body starts -->
                                <?php
                                if (!empty($mange['id'])) {
                                    for ($i = 0; $i < count($mange['id']); $i++) {
                                        if ($mange['dropoff'][$i] == 1) {
                                            $return = $mange['pickup'][$i] == 1 ? 1 : 2;
                                            $text_retrun = $mange['pickup'][$i] == 1 ? 'Pickup' : 'Dropoff';
                                            $mange_retrun = 1;
                                            if ($bomange_bo[$mange['id'][$i]]) {
                                ?>
                                                <div class="d-flex justify-content-between align-items-center header-actions mx-1 row mt-75">
                                                    <div class="col-4 text-left text-bold h4"></div>
                                                    <div class="col-4 text-center text-bold h4"><?php echo !empty($mange['car'][$i]) ? $mange['car'][$i] : ''; ?></div>
                                                    <div class="col-4 text-right mb-50"></div>
                                                </div>

                                                <table class="table table-striped text-uppercase table-vouchure-t2">
                                                    <thead class="bg-light">
                                                        <tr>
                                                            <th colspan="6">ป้ายทะเบียน : <?php echo $mange['license'][$i]; ?></th>
                                                            <th colspan="7">โทรศัพท์ : <?php echo $mange['telephone'][$i]; ?></th>
                                                        </tr>
                                                        <tr>
                                                            <th width="5%">Time</th>
                                                            <th width="10%">Programe</th>
                                                            <th width="15%">Hotel</th>
                                                            <th width="6%">Room</th>
                                                            <th width="15%">Client</th>
                                                            <th width="1%" class="text-center">A</th>
                                                            <th width="1%" class="text-center">C</th>
                                                            <th width="1%" class="text-center">Inf</th>
                                                            <th width="1%" class="text-center">FOC</th>
                                                            <th width="5%" class="text-center">COT</th>
                                                            <th width="10%" class="text-center">Voucher</th>
                                                            <th width="5%" class="text-center">Type</th>
                                                            <th width="15%">Remark</th>
                                                        </tr>
                                                    </thead>
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
                                                            <tr>
                                                                <td><?php echo $start_pickup[$id][$mange_retrun] != '00:00' ? date('H:i', strtotime($start_pickup[$id][$mange_retrun])) : ''; ?></td>
                                                                <td><?php echo $product_name[$id]; ?></td>
                                                                <td><?php echo $mange['pickup'][$i] == 1 ? $outside[$id][1] : $outside[$id][2]; ?></td>
                                                                <td><?php echo $room_no[$id][$mange_retrun]; ?></td>
                                                                <td><?php echo $cus_name[$id][0]; ?></td>
                                                                <td class="text-center"><?php echo $bt_adult[$id][$mange_retrun]; ?></td>
                                                                <td class="text-center"><?php echo $bt_child[$id][$mange_retrun]; ?></td>
                                                                <td class="text-center"><?php echo $bt_infant[$id][$mange_retrun]; ?></td>
                                                                <td class="text-center"><?php echo $bt_foc[$id][$mange_retrun]; ?></td>
                                                                <td class="text-center"><?php echo $cot[$id]; ?></td>
                                                                <td class="text-center"><?php echo !empty($voucher_no[$id]) ? $voucher_no[$id] : $book_full[$id]; ?></td>
                                                                <td><?php echo $bo_type[$id]; ?></td>
                                                                <td><?php echo $note[$id]; ?></td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>

                                                <div class="text-center mt-2 pb-5">
                                                    <h4>
                                                        <div class="badge badge-pill badge-light-warning">
                                                            <b class="text-danger">TOTAL <?php echo $total_tourist; ?></b> | <?php echo $total_adult; ?> <?php echo $total_child; ?> <?php echo $total_infant; ?> <?php echo $total_foc; ?>
                                                        </div>
                                                    </h4>
                                                </div>

                                    <?php  }
                                        }
                                    }  ?>
                                    <input type="hidden" id="name_img_dropoff" name="name_img_dropoff" value="<?php echo 'ใบจัดรถ dropoff - ' . date('j F Y', strtotime($get_date)); ?>">
                                <?php } ?>
                                <!-- Body ends -->
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Sortable lists section end -->
             
        </div>
    </div>