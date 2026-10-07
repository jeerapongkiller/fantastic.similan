<?php

require_once '../../../config/env.php';
require_once '../../../controllers/Order.php';

$manageObj = new Order();
$today = date("Y-m-d");
$tomorrow = date("Y-m-d", strtotime(" +1 day"));
$times = date("H:i:s");

if (isset($_POST['action']) && $_POST['action'] == "search" && !empty($_POST['search_travel_date'])) {
    # --- get value --- #
    $search_status = $_POST['search_status'] != "" ? $_POST['search_status'] : 'all';
    $search_agent = $_POST['search_agent'] != "" ? $_POST['search_agent'] : 'all';
    $search_product = $_POST['search_product'] != "" ? $_POST['search_product'] : 'all';
    $search_car = !empty($_POST['search_car']) ? $_POST['search_car'] : 'all';
    $search_driver = !empty($_POST['search_driver']) ? $_POST['search_driver'] : 'all';
    $search_travel_date = !empty($_POST['search_travel_date']) ? $_POST['search_travel_date'] : '0000-00-00';
    $refcode = $_POST['refcode'] != "" ? $_POST['refcode'] : '';
    $search_voucher_no = $_POST['voucher_no'] != "" ? $_POST['voucher_no'] : '';
    $name = $_POST['name'] != "" ? $_POST['name'] : '';
    $hotel = $_POST['hotel'] != "" ? $_POST['hotel'] : '';

    $all_manages = $manageObj->fetch_all_manage($search_car, $search_driver, $search_travel_date, 0);
?>
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
                                    onclick="search_booking(<?php echo $manages['id']; ?>, '<?php echo $manages['driver_name']; ?>', '<?php echo $manages['car_name']; ?>', <?php echo $manages['seat']; ?>);">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="12" y1="8" x2="12" y2="16"></line>
                                        <line x1="8" y1="12" x2="16" y2="12"></line>
                                    </svg>
                                    เพิ่ม Booking
                                </button>
                                <button type="button" class="btn btn-icon btn-icon btn-flat-warning waves-effect btn-page-block-spinner" data-toggle="modal" data-target="#modal-transfers"
                                    onclick="modal_transfer('<?php echo date('j F Y', strtotime($search_travel_date)); ?>', <?php echo $manages['id']; ?>)">
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
                                    <th class="text-center">Tourist</th>
                                    <th>AGENT</th>
                                    <th>SENDER</th>
                                    <th>V/C</th>
                                    <th>Remark</th>
                                </tr>
                            </thead>
                            <?php
                            $total_tourist = 0;
                            $bomange_arr = array();
                            $all_bookings = $manageObj->fetch_all_booking('manage', $search_status, $search_agent, $search_product, $refcode, $search_voucher_no, $name, $hotel, '', $manages['id']);
                            if ($all_bookings) { ?>
                                <tbody>
                                    <?php
                                    foreach ($all_bookings as $bookings) {
                                        if (in_array($bookings['bomange_id'], $bomange_arr) == false) {
                                            $bomange_arr[] = $bookings['bomange_id'];
                                            $text_hotel = '';
                                            $text_zone = '';
                                            if ($bookings['category_transfer'] == 1) {
                                                if (!empty($bookings['zonep_name'])) {
                                                    $text_zone = $bookings['zonep_name'] != $bookings['zoned_name'] ? $bookings['zonep_name'] . '<br>(D: ' . $bookings['zoned_name'] . ')' : $bookings['zonep_name'];
                                                }
                                                if (!empty($bookings['hotelp_name'])) {
                                                    $text_hotel = $bookings['hotelp_name'] != $bookings['hoteld_name'] ? $bookings['hotelp_name'] . '<br>(D: ' . $bookings['hoteld_name'] . ')' : $bookings['hotelp_name'];
                                                } else {
                                                    $text_hotel = $bookings['outside_pickup'] != $bookings['outside_dropoff'] ? $bookings['outside_pickup'] . '<br>(D: ' . $bookings['outside_dropoff'] . ')' : $bookings['outside_pickup'];
                                                }
                                            } else {
                                                $text_hotel = 'เดินทางมาเอง';
                                                $text_zone = 'เดินทางมาเอง';
                                            }
                                            $tourist_all = $manageObj->get_values('tourist', 'booking_order_transfer', 'booking_transfer_id = ' . $bookings['bt_id'] . ' AND order_id != ' . $manages['id'], 1);
                                            $tourist_all = !empty($tourist_all) ? array_column($tourist_all, 'tourist') : []; // Extracts all 'price' values
                                            $total_tourist += $bookings['tourist'];
                                    ?>
                                            <tr>
                                                <td><span class="badge badge-pill badge-light-success text-capitalized"><?php echo $manages['car_name']; ?></span></td>
                                                <td><?php echo $bookings['product_name']; ?></td>
                                                <td><?php echo $bookings['start_pickup'] != '00:00' ? date('H:i', strtotime($bookings['start_pickup'])) . ' - ' . date('H:i', strtotime($bookings['end_pickup'])) : ''; ?></td>
                                                <td><?php echo $text_hotel; ?></td>
                                                <td><?php echo $bookings['room_no']; ?></td>
                                                <td><?php echo !empty($bookings['telephone']) ? $bookings['cus_name'] . ' <br>(' . $bookings['telephone'] . ')' : $bookings['cus_name']; ?></td>
                                                <td class="text-center"><?php echo $bookings['tourist']; ?></td>
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
                                        <td colspan="16" class="text-center h5">Total: <?php echo $total_tourist; ?></td>
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
            $all_programed = $manageObj->fetch_all_booking('booking', $search_status, $search_agent, $search_product, $refcode, $search_voucher_no, $name, $hotel, $search_travel_date, 0);
            $categorys_array = array();
            foreach ($all_programed as $key => $categorys) {
                if (in_array($categorys['id'], $categorys_array) == false) {
                    $categorys_array[] = $categorys['id'];
                    $category_name[$categorys['id']][] = $categorys['category_name'];
                    $category_transfer[$categorys['id']][] = $categorys['category_transfer'];
                }
            }
            if (!empty($all_programed)) { ?>
                <div class="card-header">
                    <h4 class="card-title">Booking ที่ยังไม่ได้จัดรถ</h4>
                </div>
                <?php
                $programe_array = array();
                foreach ($all_programed as $programed) {
                    if (in_array($programed['product_id'], $programe_array) == false) {
                        $programe_array[] = $programed['product_id']; ?>
                        <div class="card-body pt-0 p-50">
                            <div class="d-flex justify-content-between align-items-center header-actions mx-1 row mt-75">
                                <div class="col-lg-12 col-xl-12 text-center text-bold h4"><?php echo $programed['product_name']; ?></div>
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
                                        <th class="cell-fit text-center">เหลือ</th>
                                        <th class="cell-fit text-center">A</th>
                                        <th class="cell-fit text-center">C</th>
                                        <th class="cell-fit text-center">INF</th>
                                        <th class="cell-fit text-center">FOC</th>
                                        <th class="text-nowrap">AGENT</th>
                                        <th class="text-nowrap">V/C</th>
                                        <th class="text-nowrap">COT</th>
                                        <th>REMARKE</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $bookings_arr = array();
                                    $total_tourist = 0;
                                    $total_adult = 0;
                                    $total_child = 0;
                                    $total_infant = 0;
                                    $total_foc = 0;
                                    $all_bookings = $manageObj->fetch_all_booking('booking', $search_status, $search_agent, $programed['product_id'], $refcode, $search_voucher_no, $name, $hotel, $search_travel_date, 0);
                                    foreach ($all_bookings as $bookings) {
                                        if (in_array($bookings['id'], $bookings_arr) == false) {
                                            $bookings_arr[] = $bookings['id'];
                                            $tourist = $bookings['adult'] + $bookings['child'] + $bookings['infant'] + $bookings['foc'];
                                            $tourist_all = $manageObj->get_values('tourist', 'booking_order_transfer', 'booking_transfer_id = ' . $bookings['bt_id'], 1);
                                            $tourist_all = !empty($tourist_all) ? array_column($tourist_all, 'tourist') : []; // Extracts all 'price' values
                                            if (($tourist - array_sum($tourist_all)) > 0) {
                                                $total_tourist += $bookings['adult'] + $bookings['child'] + $bookings['infant'] + $bookings['foc'];
                                                $text_hotel = '';
                                                $text_zone = '';
                                                if (in_array(1, $category_transfer[$bookings['id']]) == true) {
                                                    if (!empty($bookings['zonep_name'])) {
                                                        $text_zone = $bookings['zonep_name'] != $bookings['zoned_name'] ? $bookings['zonep_name'] . '<br>(D: ' . $bookings['zoned_name'] . ')' : $bookings['zonep_name'];
                                                    }
                                                    if (!empty($bookings['hotelp_name'])) {
                                                        $text_hotel = $bookings['hotelp_name'] != $bookings['hoteld_name'] ? $bookings['hotelp_name'] . '<br>(D: ' . $bookings['hoteld_name'] . ')' : $bookings['hotelp_name'];
                                                    } else {
                                                        $text_hotel = $bookings['outside_pickup'] != $bookings['outside_dropoff'] ? $bookings['outside_pickup'] . '<br>(D: ' . $bookings['outside_dropoff'] . ')' : $bookings['outside_pickup'];
                                                    }
                                                } else {
                                                    $text_hotel = 'เดินทางมาเอง';
                                                    $text_zone = 'เดินทางมาเอง';
                                                }
                                    ?>
                                                <tr>
                                                    <td><span class="badge badge-pill <?php echo $bookings['booksta_class']; ?> text-capitalized"><?php echo $bookings['status_name']; ?></span></td>
                                                    <td class="cell-fit"><span class="text-nowrap"><?php echo date('j F Y', strtotime($search_travel_date)); ?></span></td>
                                                    <td class="cell-fit"><?php echo $bookings['start_pickup'] != '00:00' ? date('H:i', strtotime($bookings['start_pickup'])) . ' - ' . date('H:i', strtotime($bookings['end_pickup'])) : ''; ?></td>
                                                    <td><?php echo $text_hotel; ?></td>
                                                    <td class="cell-fit"><?php echo $bookings['room_no']; ?></td>
                                                    <td><?php echo !empty($bookings['telephone']) ? $bookings['cus_name'] . ' <br>(' . $bookings['telephone'] . ')' : $bookings['cus_name']; ?></td>
                                                    <td class="text-center"><b class="text-warning"><?php echo ($tourist - array_sum($tourist_all)); ?></b></td>
                                                    <td class="text-center"><?php echo !empty($bookings['adult']) ? $bookings['adult'] : 0; ?></td>
                                                    <td class="text-center"><?php echo !empty($bookings['child']) ? $bookings['child'] : 0; ?></td>
                                                    <td class="text-center"><?php echo !empty($bookings['infant']) ? $bookings['infant'] : 0; ?></td>
                                                    <td class="text-center"><?php echo !empty($bookings['foc']) ? $bookings['foc'] : 0; ?></td>
                                                    <td><?php echo $bookings['agent_name']; ?></td>
                                                    <td><?php echo !empty($bookings['voucher_no_agent']) ? $bookings['voucher_no_agent'] : $bookings['book_full']; ?></td>
                                                    <td class="cell-fit text-nowrap"><?php echo number_format($bookings['cot']); ?></td>
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
                                        }
                                    }
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="15" class="text-center h5">Total: <?php echo $total_tourist; ?></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
            <?php }
                }
            } ?>
        </div>

    </div>
    <!------------------------------------------------------------------>
    <!-- End Management Transfer -->
<?php
} else {
    echo false;
}
