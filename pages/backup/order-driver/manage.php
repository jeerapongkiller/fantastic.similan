<?php
require_once 'controllers/Order.php';

$manageObj = new Order();
$today = date("Y-m-d");
$tomorrow = date("Y-m-d", strtotime(" +1 day"));
// $today = '2025-09-24';
// $get_date = '2025-09-25';
$get_date = !empty($_GET['search_travel_date']) ? $_GET['search_travel_date'] : $tomorrow; // $get_date->format("Y-m-d")
$search_status = $_GET['search_status'] != "" ? $_GET['search_status'] : 'all';
$search_agent = $_GET['search_agent'] != "" ? $_GET['search_agent'] : 'all';
$search_product = $_GET['search_product'] != "" ? $_GET['search_product'] : 'all';
$search_car = !empty($_GET['search_car']) ? $_GET['search_car'] : 'all';
$search_driver = !empty($_GET['search_driver']) ? $_GET['search_driver'] : 'all';
$refcode = $_GET['refcode'] != "" ? $_GET['refcode'] : '';
$search_voucher_no = $_GET['voucher_no'] != "" ? $_GET['voucher_no'] : '';
$name = $_GET['name'] != "" ? $_GET['name'] : '';
$hotel = $_GET['hotel'] != "" ? $_GET['hotel'] : '';

$all_manages = $manageObj->fetch_all_manage($search_car, $search_driver, $get_date, 0);

$bo_arr = array();
$bomange_arr = array();
$categorys_array = array();
$cars_arr = array();
$extra_arr = array();
$bpr_arr = array();
$manages_arr = array();
$programe_arr = array();
$all_programed = $manageObj->fetch_all_booking('booking', $search_status, $search_agent, $search_product, $refcode, $search_voucher_no, $name, $hotel, $get_date, 0);
foreach ($all_programed as $categorys) {
    if (in_array($categorys['manage_id'], $manages_arr) == false && !empty($categorys['manage_id'])) {
        $manages_arr[] = $categorys['manage_id'];
        $manage_id[] = $categorys['manage_id'];
    }

    if (in_array($categorys['product_id'], $programe_arr) == false && empty($categorys['manage_id'])) {
        $programe_arr[] = $categorys['product_id'];
        $product_id[] = $categorys['product_id'];
        $product_name[] = $categorys['product_name'];
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
        $booking_manage[$categorys['id']] = $categorys['manage_id'];
        $booking_prod[$categorys['product_id']][] = $categorys['id'];
        $hotelp_name[$categorys['id']] = $categorys['hotelp_name'];
        $outside_pickup[$categorys['id']] = $categorys['outside_pickup'];
        $zonep_name[$categorys['id']] = $categorys['zonep_name'];
        $hoteld_name[$categorys['id']] = $categorys['hoteld_name'];
        $zoned_name[$categorys['id']] = $categorys['zoned_name'];
        $outside_dropoff[$categorys['id']] = $categorys['outside_dropoff'];
        $start_pickup[$categorys['id']] = $categorys['start_pickup'];
        $end_pickup[$categorys['id']] = $categorys['end_pickup'];
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
                    <form id="manages-search-form" name="manages-search-form" method="get" enctype="multipart/form-data">
                        <input type="hidden" name="pages" value="<?php echo $_GET['pages']; ?>">
                        <div class="d-flex align-items-center mx-50 row pt-0 pb-0">
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label for="search_status">Status</label>
                                    <select class="form-control select2" id="search_status" name="search_status">
                                        <option value="all">All</option>
                                        <?php
                                        $bookstype = $manageObj->showliststatus();
                                        foreach ($bookstype as $booktype) {
                                            $select = $booktype['id'] == $search_status ? 'selected' : '';
                                        ?>
                                            <option value="<?php echo $booktype['id']; ?>" <?php echo $select; ?>><?php echo $booktype['name']; ?></option>
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
                                            $select = $agent['id'] == $search_agent ? 'selected' : '';
                                        ?>
                                            <option value="<?php echo $agent['id']; ?>" <?php echo $select; ?>><?php echo $agent['name']; ?></option>
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
                                            $select = $product['id'] == $search_product ? 'selected' : '';
                                        ?>
                                            <option value="<?php echo $product['id']; ?>" <?php echo $select; ?>><?php echo $product['name']; ?></option>
                                        <?php } ?>
                                    </select>
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
                                            $select = $car['id'] == $search_car ? 'selected' : '';
                                        ?>
                                            <option value="<?php echo $car['id']; ?>" data-name="<?php echo $car['name']; ?>" <?php echo $select; ?>><?php echo $car['name']; ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label for="search_driver">ชื่อคนขับ</label>
                                    <select class="form-control select2" id="search_driver" name="search_driver">
                                        <option value="all">All</option>
                                        <?php
                                        $drivers = $manageObj->show_drivers();
                                        foreach ($drivers as $driver) {
                                            $select = $driver['id'] == $search_driver ? 'selected' : '';
                                        ?>
                                            <option value="<?php echo $driver['id']; ?>" <?php echo $select; ?>><?php echo $driver['name']; ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label class="form-label" for="search_travel_date">วันที่เที่ยว (Travel Date)</label></br>
                                    <input type="text" class="form-control date-picker" id="search_travel_date" name="search_travel_date" value="<?php echo $get_date; ?>" />
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
                            <div class="col-md-2 col-12">
                                <div class="form-group">
                                    <label class="form-label" for="hotel">Hotel</label>
                                    <input type="text" class="form-control" id="hotel" name="hotel" value="<?php echo $hotel; ?>" />
                                </div>
                            </div>
                            <div class="col-md-4 col-12 mb-1">
                                <button type="submit" class="btn btn-primary">Search</button>
                                <button type="button" class="btn btn-success waves-effect waves-float waves-light btn-page-block-spinner" data-toggle="modal" data-target="#modal-transfers" onclick="modal_transfer('<?php echo date('j F Y', strtotime($get_date)); ?>', 0, 0, 1);"><i data-feather='plus'></i> เปิดรถ</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- filter end -->

                <div id="div-manage-list">
                    <!-- Start Table Programe -->
                    <!------------------------------------------------------------------>
                    <div class="card">

                        <div id="div-manages-list">
                            <?php
                            if (!empty($all_manages)) {
                                foreach ($all_manages as $manages) {
                            ?>
                                    <div class="card-body pt-0 p-50">
                                        <div class="d-flex justify-content-between align-items-center header-actions mx-1 mt-75">
                                            <div class="col-4 text-left text-bold h4"></div>
                                            <div class="col-4 text-center"><span class="h4 badge-light-purple"><?php echo $manages['car_name']; ?></span></div>
                                            <div class="col-4 text-right mb-50">
                                                <button type="button" class="btn btn-icon btn-icon btn-flat-info waves-effect btn-page-block-spinner" data-toggle="modal" data-target="#modal-booking"
                                                    onclick="search_booking(<?php echo $manages['id']; ?>, '<?php echo $manages['driver_name']; ?>', '<?php echo $manages['car_name']; ?>', <?php echo !empty($manages['seat']) ? $manages['seat'] : 0; ?>);">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle">
                                                        <circle cx="12" cy="12" r="10"></circle>
                                                        <line x1="12" y1="8" x2="12" y2="16"></line>
                                                        <line x1="8" y1="12" x2="16" y2="12"></line>
                                                    </svg>
                                                    เพิ่ม Booking
                                                </button>
                                                <button type="button" class="btn btn-icon btn-icon btn-flat-warning waves-effect btn-page-block-spinner" data-toggle="modal" data-target="#modal-transfers"
                                                    onclick="modal_transfer('<?php echo date('j F Y', strtotime($get_date)); ?>', <?php echo $manages['id']; ?>)">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit">
                                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                    </svg>
                                                    แก้ใขรถ
                                                </button>
                                                <input type="hidden" id="arr_mange<?php echo $manages['id']; ?>" value='<?php echo json_encode($manages, JSON_HEX_APOS, JSON_UNESCAPED_UNICODE); ?>'>
                                            </div>
                                        </div>
                                        <table class="table table-bordered table-striped">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th colspan="3">คนขับ : <?php echo $manages['driver_name']; ?></th>
                                                    <th colspan="7">ป้ายทะเบียน : <?php echo $manages['license']; ?></th>
                                                    <th colspan="4">โทรศัพท์ : <?php echo $manages['telephone']; ?></th>
                                                </tr>
                                                <tr>
                                                    <th class="cell-fit text-center">รถ</th>
                                                    <th>Programe</th>
                                                    <th>Time</th>
                                                    <th>Hotel</th>
                                                    <th>Room</th>
                                                    <th>Client</th>
                                                    <!-- <th class="text-center">Tourist</th> -->
                                                    <th class="cell-fit text-center">A</th>
                                                    <th class="cell-fit text-center">C</th>
                                                    <th class="cell-fit text-center">INF</th>
                                                    <th class="cell-fit text-center">FOC</th>
                                                    <th>AGENT</th>
                                                    <th>SENDER</th>
                                                    <th>V/C</th>
                                                    <th>Remark</th>
                                                </tr>
                                            </thead>
                                            <?php
                                            $total_adult = 0;
                                            $total_child = 0;
                                            $total_infant = 0;
                                            $total_foc = 0;
                                            $total_tourist = 0;
                                            $bomange_arr = array();
                                            $all_bookings = $manageObj->fetch_all_booking('manage', $search_status, $search_agent, $search_product, $refcode, $search_voucher_no, $name, $hotel, $get_date, $manages['id']);
                                            if ($all_bookings) { ?>
                                                <tbody>
                                                    <?php
                                                    foreach ($all_bookings as $bookings) {
                                                        $id = $bookings['id'];
                                                        if (in_array($bookings['bomange_id'], $bomange_arr) == false) {
                                                            $bomange_arr[] = $bookings['bomange_id'];
                                                            // $text_hotel = '';
                                                            // $text_zone = '';
                                                            // if ($bookings['category_transfer'] == 1) {
                                                            //     if (!empty($bookings['zonep_name'])) {
                                                            //         $text_zone = $bookings['zonep_name'] != $bookings['zoned_name'] ? $bookings['zonep_name'] . '<br>(D: ' . $bookings['zoned_name'] . ')' : $bookings['zonep_name'];
                                                            //     }
                                                            //     if (!empty($bookings['hotelp_name'])) {
                                                            //         $text_hotel = $bookings['hotelp_name'] != $bookings['hoteld_name'] ? $bookings['hotelp_name'] . '<br>(D: ' . $bookings['hoteld_name'] . ')' : $bookings['hotelp_name'];
                                                            //     } else {
                                                            //         $text_hotel = $bookings['outside_pickup'] != $bookings['outside_dropoff'] ? $bookings['outside_pickup'] . '<br>(D: ' . $bookings['outside_dropoff'] . ')' : $bookings['outside_pickup'];
                                                            //     }
                                                            // } else {
                                                            //     $text_hotel = 'เดินทางมาเอง';
                                                            //     $text_zone = 'เดินทางมาเอง';
                                                            // }

                                                            $text_hotel = '';
                                                            $text_hotel = (!empty($hotelp_name[$id])) ? $hotelp_name[$id] : $outside_pickup[$id];
                                                            $text_hotel .= (!empty($zonep_name[$id])) ? ' (' . $zonep_name[$id] . ')</br>' : '</br>';

                                                            $total_adult += !empty($adult[$id]) ? array_sum($adult[$id]) : 0;
                                                            $total_child += !empty($child[$id]) ? array_sum($child[$id]) : 0;
                                                            $total_infant += !empty($infant[$id]) ? array_sum($infant[$id]) : 0;
                                                            $total_foc += !empty($foc[$id]) ? array_sum($foc[$id]) : 0;
                                                            $total_tourist += !empty($tourist_array[$id]) ? array_sum($tourist_array[$id]) : 0;
                                                            $tourist = !empty($tourist_array[$id]) ? array_sum($tourist_array[$id]) : 0;


                                                            // $tourist_all = $manageObj->get_values('tourist', 'booking_order_transfer', 'booking_transfer_id = ' . $bookings['bt_id'] . ' AND order_id != ' . $manages['id'], 1);
                                                            // $tourist_all = !empty($tourist_all) ? array_column($tourist_all, 'tourist') : []; // Extracts all 'price' values
                                                            // $total_tourist += $bookings['tourist'];
                                                    ?>
                                                            <tr>
                                                                <td><span class="badge badge-pill badge-light-success text-capitalized"><?php echo $manages['car_name']; ?></span></td>
                                                                <td><?php echo $bookings['product_name']; ?></td>
                                                                <td><?php echo $bookings['start_pickup'] != '00:00' ? date('H:i', strtotime($bookings['start_pickup'])) . ' - ' . date('H:i', strtotime($bookings['end_pickup'])) : ''; ?></td>
                                                                <td><?php echo $text_hotel; ?></td>
                                                                <td><?php echo $bookings['room_no']; ?></td>
                                                                <td><?php echo $bookings['cus_name']; ?></td>
                                                                <!-- <td class="text-center"><?php echo $bookings['tourist']; ?></td> -->
                                                                <td class="text-center"><?php echo !empty($adult[$id]) ? array_sum($adult[$id]) : 0; ?></td>
                                                                <td class="text-center"><?php echo !empty($child[$id]) ? array_sum($child[$id]) : 0; ?></td>
                                                                <td class="text-center"><?php echo !empty($infant[$id]) ? array_sum($infant[$id]) : 0; ?></td>
                                                                <td class="text-center"><?php echo !empty($foc[$id]) ? array_sum($foc[$id]) : 0; ?></td>
                                                                <td><?php echo !empty($bookings['agent_name']) ? $bookings['agent_name'] : '-'; ?></td>
                                                                <td><?php echo $bookings['sender']; ?></td>
                                                                <td><?php echo !empty($bookings['voucher_no_agent']) ? $bookings['voucher_no_agent'] : $bookings['book_full']; ?></td>
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
                                                        <td colspan="16" class="text-center h5">
                                                            <b class="text-danger">TOTAL <?php echo $total_tourist; ?></b> |
                                                            Adult : <?php echo $total_adult; ?>
                                                            Child : <?php echo $total_child; ?>
                                                            Infant : <?php echo $total_infant; ?>
                                                            FOC : <?php echo $total_foc; ?>
                                                        </td>
                                                    </tr>
                                                </tfoot>
                                            <?php } ?>
                                        </table>
                                    </div>
                            <?php }
                            } ?>
                        </div>

                    </div>
                    <!------------------------------------------------------------------>
                    <!-- End Table Programe -->

                    <!-- Start Management Transfer -->
                    <!------------------------------------------------------------------>
                    <div class="card">

                        <div id="div-booking-list">
                            <?php
                            // $all_programed = $manageObj->fetch_all_booking('booking', $search_status, $search_agent, $search_product, $refcode, $search_voucher_no, $name, $hotel, $get_date, 0);
                            // $bpr_array = array();
                            // foreach ($all_programed as $categorys) {
                            //     if (in_array($categorys['bpr_id'], $bpr_array) == false) {
                            //         $bpr_array[] = $categorys['bpr_id'];
                            //         $category_name[$categorys['id']][] = $categorys['category_name'];
                            //         $category_transfer[$categorys['id']][] = $categorys['category_transfer'];
                            //         $adult[$categorys['id']][] = $categorys['adult'];
                            //         $child[$categorys['id']][] = $categorys['child'];
                            //         $infant[$categorys['id']][] = $categorys['infant'];
                            //         $foc[$categorys['id']][] = $categorys['foc'];
                            //         $tourist_array[$categorys['id']][] = $categorys['adult'] + $categorys['child'] + $categorys['infant'] + $categorys['foc'];
                            //     }
                            // }

                            if (!empty($product_id)) { ?>
                                <div class="card-header">
                                    <h4 class="card-title">Booking ที่ยังไม่ได้จัดรถ</h4>
                                </div>
                                <?php
                                // $programe_array = array();
                                // foreach ($all_programed as $programed) {
                                //     if (in_array($programed['product_id'], $programe_array) == false) {
                                //         $programe_array[] = $programed['product_id']; 
                                for ($i = 0; $i < count($product_id); $i++) { ?>
                                    <div class="card-body pt-0 p-50">
                                        <div class="d-flex justify-content-between align-items-center header-actions mx-1 row mt-75">
                                            <div class="col-lg-12 col-xl-12 text-center text-bold h4"><?php echo $product_name[$i]; ?></div>
                                        </div>
                                        <table class="table table-bordered table-striped">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th class="cell-fit text-center">STATUS</th>
                                                    <th class="text-nowrap">TRAVEL DATE</th>
                                                    <th class="text-nowrap">TIME</th>
                                                    <th>HOTEL</th>
                                                    <th class="text-nowrap">ROOM</th>
                                                    <th class="text-nowrap">Name</th>
                                                    <!-- <th class="cell-fit text-center">เหลือ</th> -->
                                                    <th class="cell-fit text-center">A</th>
                                                    <th class="cell-fit text-center">C</th>
                                                    <th class="cell-fit text-center">INF</th>
                                                    <th class="cell-fit text-center">FOC</th>
                                                    <th>AGENT</th>
                                                    <th class="text-nowrap">V/C</th>
                                                    <th class="text-nowrap">COT</th>
                                                    <th>REMARKE</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $total_adult = 0;
                                                $total_child = 0;
                                                $total_infant = 0;
                                                $total_foc = 0;
                                                $total_tourist = 0;
                                                for ($b = 0; $b < count($booking_prod[$product_id[$i]]); $b++) {
                                                    $id = $booking_prod[$product_id[$i]][$b];
                                                    if (empty($booking_manage[$id])) {

                                                        $total_adult += !empty($adult[$id]) ? array_sum($adult[$id]) : 0;
                                                        $total_child += !empty($child[$id]) ? array_sum($child[$id]) : 0;
                                                        $total_infant += !empty($infant[$id]) ? array_sum($infant[$id]) : 0;
                                                        $total_foc += !empty($foc[$id]) ? array_sum($foc[$id]) : 0;
                                                        $total_tourist += !empty($tourist_array[$id]) ? array_sum($tourist_array[$id]) : 0;
                                                        $tourist = !empty($tourist_array[$id]) ? array_sum($tourist_array[$id]) : 0;

                                                        $text_hotel = '';
                                                        $text_hotel = (!empty($hotelp_name[$id])) ? '<b>Pickup : </b>' . $hotelp_name[$id] : '<b>Pickup : </b>' . $outside_pickup[$id];
                                                        $text_hotel .= (!empty($zonep_name[$id])) ? ' (' . $zonep_name[$id] . ')</br>' : '</br>';
                                                        $text_hotel .= (!empty($hoteld_name[$id])) ? '<b>Dropoff : </b>' . $hoteld_name[$id] : '<b>Dropoff : </b>' . $outside_dropoff[$id];
                                                        $text_hotel .= (!empty($zoned_name[$id])) ? ' (' . $zoned_name[$id] . ')' : '';
                                                ?>
                                                        <tr>
                                                            <td><span class="badge badge-pill <?php echo $booksta_class[$id]; ?> text-capitalized"><?php echo $status_name[$id]; ?></span></td>
                                                            <td class="cell-fit"><span class="text-nowrap"><?php echo date('j F Y', strtotime($get_date)); ?></span></td>
                                                            <td class="cell-fit"><?php echo date('H:i', strtotime($start_pickup[$id])) . ' - ' . date('H:i', strtotime($end_pickup[$id])); ?></td>
                                                            <td><?php echo $text_hotel; ?></td>
                                                            <td><?php echo $room_no[$id]; ?></td>
                                                            <td><?php echo !empty($telephone[$id]) ? $cus_name[$id] . ' <br>(' . $telephone[$id] . ')' : $cus_name[$id]; ?></td>
                                                            <!-- <td class="text-center"><b class="text-danger"><?php // echo ($tourist - array_sum($tourist_all)); 
                                                                                                                ?></b></td> -->
                                                            <td class="text-center"><?php echo !empty($adult[$id]) ? array_sum($adult[$id]) : 0; ?></td>
                                                            <td class="text-center"><?php echo !empty($child[$id]) ? array_sum($child[$id]) : 0; ?></td>
                                                            <td class="text-center"><?php echo !empty($infant[$id]) ? array_sum($infant[$id]) : 0; ?></td>
                                                            <td class="text-center"><?php echo !empty($foc[$id]) ? array_sum($foc[$id]) : 0; ?></td>
                                                            <td><?php echo $agent_name[$id]; ?></td>
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
                                                }
                                                ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="15" class="text-center h5">
                                                        <b class="text-danger">TOTAL <?php echo $total_tourist; ?></b> |
                                                        Adult : <?php echo $total_adult; ?>
                                                        Child : <?php echo $total_child; ?>
                                                        Infant : <?php echo $total_infant; ?>
                                                        FOC : <?php echo $total_foc; ?>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                            <?php }
                                // }
                            } ?>
                        </div>

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

        </div>
    </div>