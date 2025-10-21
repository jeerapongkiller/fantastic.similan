<?php
require_once 'controllers/Order.php';

$today = date("Y-m-d");
$tomorrow = new DateTime('tomorrow');
$manageObj = new Order();

if (isset($_GET['action']) && $_GET['action'] == "print" && !empty($_GET['date_travel'])) {
    // get value from ajax
    $get_date = $_GET['date_travel'] != "" ? $_GET['date_travel'] : '0000-00-00';
    $search_boat = $_GET['search_boat'] != "" ? $_GET['search_boat'] : 'all';
    $search_status = $_GET['search_status'] != "" ? $_GET['search_status'] : 'all';
    $search_agent = $_GET['search_agent'] != "" ? $_GET['search_agent'] : 'all';
    $search_product = $_GET['search_product'] != "" ? $_GET['search_product'] : 'all';
    $search_voucher_no = $_GET['search_voucher_no'] != "" ? $_GET['search_voucher_no'] : '';
    $refcode = $_GET['refcode'] != "" ? $_GET['refcode'] : '';
    $name = $_GET['name'] != "" ? $_GET['name'] : '';

    $all_manages = $manageObj->fetch_all_manageboat($get_date, $search_boat, 0);

    $categorys_array = array();
    $all_bookings = $manageObj->fetch_all_bookingboat('all', $get_date, $search_status, $search_agent, $search_product, $search_voucher_no, $refcode, $name, 0);

    foreach ($all_bookings as $categorys) {
        $categorys_array[] = $categorys['id'];
        $category_name[$categorys['id']][] = $categorys['category_name'];
    }
?>
    <div id="div-boat-job-image" style="background-color: #FFF;">
        <!-- Header starts -->
        <div class="card-body pb-0">
            <div class="row">
                <span class="col-6 brand-logo"><img src="app-assets/images/logo/logo-500.png" height="50"></span>
                <span class="col-6 text-right" style="color: #000;">
                    โทร : 062-3322800 / 084-7443000 / 083-1757444 </br>
                    Email : Fantasticsimilantravel11@gmail.com
                </span>
            </div>
            <div class="text-center card-text">
                <h4 class="font-weight-bolder">ใบจัดเรือ</h4>
                <div class="badge badge-pill badge-light-danger">
                    <h5 class="m-0 pl-1 pr-1 text-danger"><?php echo date('j F Y', strtotime($get_date)); ?></h5>
                </div>
            </div>
        </div>
        <!-- Header ends -->
        <!-- Body starts -->
        <?php
        if ($all_manages) {
            foreach ($all_manages as $key => $manages) {
        ?>
                <div class="d-flex justify-content-between align-items-center header-actions mx-1 row mt-75 pt-1">
                    <div class="col-4 text-left text-bold h4"></div>
                    <div class="col-4 text-center text-bold h1"><?php echo $manages['boat_name']; ?></div>
                    <div class="col-4 text-right mb-50"></div>
                </div>

                <table class="tableprint">
                    <thead class="">
                        <tr>
                            <td colspan="5">ไกด์ : <?php echo $manages['guide_name']; ?></td>
                            <td colspan="6">เคาน์เตอร์ : <?php echo $manages['counter']; ?></td>
                            <td colspan="3" style="background-color: <?php echo $manages['color_hex']; ?>; <?php echo $manages['text_color'] != '' ? 'color: ' . $manages['text_color'] . ';' : ''; ?>">
                                สี : <?php echo $manages['color_name_th']; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>เวลารับ</th>
                            <th width="5%">Driver</th>
                            <th width="15%">เอเยนต์</th>
                            <th width="15%">ชื่อลูกค้า</th>
                            <th width="5%">V/C</th>
                            <th width="20%">โรงแรม</th>
                            <th>ห้อง</th>
                            <th class="text-center">รวม</th>
                            <th class="text-center">A</th>
                            <th class="text-center">C</th>
                            <th class="text-center">Inf</th>
                            <th class="text-center">FOC</th>
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
                        $all_bookings = $manageObj->fetch_all_bookingboat('manage', $get_date, $search_status, $search_agent, $search_product, $search_voucher_no, $refcode, $name, $manages['id']);
                        foreach ($all_bookings as $bookings) {
                            if (in_array($bookings['bomange_id'], $bomange_arr) == false) {
                                $bomange_arr[] = $bookings['bomange_id'];
                                $total_adult += !empty($bookings['adult']) ? $bookings['adult'] : 0;
                                $total_child += !empty($bookings['child']) ? $bookings['child'] : 0;
                                $total_infant += !empty($bookings['infant']) ? $bookings['infant'] : 0;
                                $total_foc += !empty($bookings['foc']) ? $bookings['foc'] : 0;
                                $total_tourist += $bookings['adult'] + $bookings['child'] + $bookings['infant'] + $bookings['foc'];
                                $tourist = $bookings['adult'] + $bookings['child'] + $bookings['infant'] + $bookings['foc'];
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
                                    <td class="cell-fit"><?php echo date('H:i', strtotime($bookings['start_pickup'])) . ' - ' . date('H:i', strtotime($bookings['end_pickup'])); ?></td>
                                    <td class="cell-fit">
                                        <?php if (!empty($cars)) {
                                            foreach ($cars as $key => $car) {
                                                echo $key > 0 ? '<br>' : '';
                                                echo '<div class="badge badge-light-success">' . $car['name'] . '</div>';
                                            }
                                        } ?>
                                    </td>
                                    <td><?php echo $bookings['agent_name']; ?></td>
                                    <td><?php echo $bookings['cus_name']; ?></td>
                                    <td><?php echo !empty($bookings['voucher_no_agent']) ? $bookings['voucher_no_agent'] : $bookings['book_full']; ?></td>
                                    <td style="padding: 5px;"><?php echo $text_hotel; ?></td>
                                    <td class="cell-fit"><?php echo $bookings['room_no']; ?></td>
                                    <td class="cell-fit text-center bg-warning bg-lighten-3"><?php echo $tourist; ?></td>
                                    <td class="cell-fit text-center bg-info bg-lighten-3"><?php echo !empty($bookings['adult']) ? $bookings['adult'] : 0; ?></td>
                                    <td class="cell-fit text-center bg-warning bg-lighten-3"><?php echo !empty($bookings['child']) ? $bookings['child'] : 0; ?></td>
                                    <td class="cell-fit text-center bg-info bg-lighten-3"><?php echo !empty($bookings['infant']) ? $bookings['infant'] : 0; ?></td>
                                    <td class="cell-fit text-center bg-warning bg-lighten-3"><?php echo !empty($bookings['foc']) ? $bookings['foc'] : 0; ?></td>
                                    <td class="cell-fit text-nowrap"><b class="text-warning"><?php echo number_format($bookings['cot']); ?></b></td>
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
                </table>

                <div class="text-center mt-1 pb-2">
                    <h4>
                        <div class="badge badge-pill badge-light-warning">
                            <b class="text-danger">TOTAL <?php echo $total_tourist; ?></b> |
                            Adult : <?php echo $total_adult; ?>
                            Child : <?php echo $total_child; ?>
                            Infant : <?php echo $total_infant; ?>
                            FOC : <?php echo $total_foc; ?>
                        </div>
                    </h4>
                </div>

                <div class="pagebreak"></div>
        <?php }
        } ?>
        <input type="hidden" id="name_img" name="name_img" value="<?php echo 'ใบจัดเรือ - ' . date('j F Y', strtotime($get_date)); ?>">
        <!-- Body ends -->
    </div>
<?php
} elseif (isset($_GET['action']) && $_GET['action'] == "check_in" && !empty($_GET['travel_date']) && !empty($_GET['manage_id'])) {
    // get value from ajax
    $manage_id = $_GET['manage_id'] != "" ? $_GET['manage_id'] : 0;
    $travel_date = $_GET['travel_date'] != "" ? $_GET['travel_date'] : '0000-00-00';

    # --- show list boats booking --- #
    $first_booking = array();
    $first_cus = array();
    $first_program = array();
    $first_ext = array();
    $first_bomanage = array();
    $first_bo = [];
    $first_trans = [];
    $bookings = $manageObj->showlistboats('list', 0, $travel_date, 'all', 'all', 'all', 'all', 'all', '', '', '', '');
    if (!empty($bookings)) {
        foreach ($bookings as $booking) {
            if ($booking['mange_id'] == $manage_id) {
                if (in_array($booking['id'], $first_bo) == false) {
                    $first_bo[] = $booking['id'];
                    $book['id'][$booking['mange_id']][] = !empty($booking['id']) ? $booking['id'] : 0;
                    $book['check'][$booking['mange_id']][] = !empty($booking['check_id']) ? $booking['check_id'] : 0;
                    $book['voucher'][$booking['mange_id']][] = !empty($booking['voucher_no_agent']) ? $booking['voucher_no_agent'] : '';
                    $book['book_full'][$booking['mange_id']][] = !empty($booking['book_full']) ? $booking['book_full'] : '';
                    $book['sender'][$booking['mange_id']][] = !empty($booking['sender']) ? $booking['sender'] : '';
                    $book['start_pickup'][$booking['mange_id']][] = !empty($booking['start_pickup']) ? date('H:i', strtotime($booking['start_pickup'])) : '';
                    $book['end_pickup'][$booking['mange_id']][] = !empty($booking['end_pickup']) ? date('H:i', strtotime($booking['end_pickup'])) : '';
                    $book['hotel'][$booking['mange_id']][] = !empty($booking['pickup_name']) ? $booking['pickup_name'] : '';
                    $book['room_no'][$booking['mange_id']][] = !empty($booking['room_no']) ? $booking['room_no'] : '';
                    $book['cus_name'][$booking['mange_id']][] = !empty($booking['cus_name']) ? $booking['cus_name'] : '';
                    $book['telephone'][$booking['mange_id']][] = !empty($booking['telephone']) ? $booking['telephone'] : '';
                    $book['comp_name'][$booking['mange_id']][] = !empty($booking['comp_name']) ? $booking['comp_name'] : '';
                    $book['adult'][$booking['mange_id']][] = !empty($booking['bp_adult']) ? $booking['bp_adult'] : 0;
                    $book['child'][$booking['mange_id']][] = !empty($booking['bp_child']) ? $booking['bp_child'] : 0;
                    $book['infant'][$booking['mange_id']][] = !empty($booking['bp_infant']) ? $booking['bp_infant'] : 0;
                    $book['foc'][$booking['mange_id']][] = !empty($booking['bp_foc']) ? $booking['bp_foc'] : 0;
                    $book['rate_adult'][$booking['mange_id']][] = !empty($booking['rate_adult']) ? $booking['rate_adult'] : 0;
                    $book['rate_child'][$booking['mange_id']][] = !empty($booking['rate_child']) ? $booking['rate_child'] : 0;
                    $book['rate_infant'][$booking['mange_id']][] = !empty($booking['rate_infant']) ? $booking['rate_infant'] : 0;
                    $book['rate_private'][$booking['mange_id']][] = !empty($booking['rate_private']) ? $booking['rate_private'] : 0;
                    $book['discount'][$booking['mange_id']][] = !empty(!empty($booking['bp_discount'])) ? $booking['bp_discount'] : 0;
                    $book['note'][$booking['mange_id']][] = !empty($booking['bp_note']) ? $booking['bp_note'] : '';
                    $book['cot'][$booking['mange_id']][] = !empty($booking['total_paid']) ? $booking['total_paid'] : 0;
                    $book['total'][$booking['mange_id']][] = $booking['booktye_id'] == 1 ? ($booking['bp_adult'] * $booking['rate_adult']) + ($booking['bp_child'] * $booking['rate_child']) + ($booking['rate_infant'] * $booking['rate_infant']) : $booking['rate_private'];
                    $book['bo_mange_id'][$booking['mange_id']][] = !empty($booking['boman_id']) ? $booking['boman_id'] : 0;

                    $boat_name[$booking['mange_id']] = !empty($booking['boat_name']) ? $booking['boat_name'] : '';
                    $color_id[$booking['mange_id']] = !empty($booking['color_id']) ? $booking['color_id'] : '';
                    $color_name[$booking['mange_id']] = !empty($booking['color_name']) ? $booking['color_name'] : '';
                    $color_hex[$booking['mange_id']] = !empty($booking['color_hex']) ? $booking['color_hex'] : '';
                    $guide_name[$booking['mange_id']] = !empty($booking['guide_name']) ? $booking['guide_name'] : '';
                }
                # --- get value booking extra chang --- #
                if ((in_array($booking['bec_id'], $first_ext) == false) && !empty($booking['bec_id'])) {
                    $first_ext[] = $booking['bec_id'];
                    $bec_id[$booking['id']][] = !empty($booking['bec_id']) ? $booking['bec_id'] : 0;
                    $bec_name[$booking['id']][] = !empty($booking['bec_name']) ? $booking['bec_name'] : $booking['extra_name'];
                    $bec_type[$booking['id']][] = !empty($booking['bec_type']) ? $booking['bec_type'] : 0;
                    $bec_adult[$booking['id']][] = !empty($booking['bec_adult']) ? $booking['bec_adult'] : 0;
                    $bec_child[$booking['id']][] = !empty($booking['bec_child']) ? $booking['bec_child'] : 0;
                    $bec_infant[$booking['id']][] = !empty($booking['bec_infant']) ? $booking['bec_infant'] : 0;
                    $bec_privates[$booking['id']][] = !empty($booking['bec_privates']) ? $booking['bec_privates'] : 0;
                    $bec_rate_adult[$booking['id']][] = !empty($booking['bec_rate_adult']) ? $booking['bec_rate_adult'] : 0;
                    $bec_rate_child[$booking['id']][] = !empty($booking['bec_rate_child']) ? $booking['bec_rate_child'] : 0;
                    $bec_rate_infant[$booking['id']][] = !empty($booking['bec_rate_infant']) ? $booking['bec_rate_infant'] : 0;
                    $bec_rate_private[$booking['id']][] = !empty($booking['bec_rate_private']) ? $booking['bec_rate_private'] : 0;
                    $bec_rate_total[$booking['id']][] = $booking['bec_type'] > 0 ? $booking['bec_type'] == 1 ? (($booking['bec_adult'] * $booking['bec_rate_adult']) + ($booking['bec_child'] * $booking['bec_rate_child']) + ($booking['bec_infant'] * $booking['bec_rate_infant'])) : ($booking['bec_privates'] * $booking['bec_rate_private']) : 0;
                }

                if (in_array($booking['bomanage_id'], $first_bomanage) == false) {
                    $first_managet[] = $booking['bomanage_id'];
                    $retrun_t = !empty($booking['pickup']) ? 1 : 2;
                    $managet['bomanage_id'][$booking['id']][$retrun_t] = !empty($booking['bomanage_id']) ? $booking['bomanage_id'] : 0;
                    $managet['id'][$booking['id']][$retrun_t] = !empty($booking['manget_id']) ? $booking['manget_id'] : 0;
                    $managet['car'][$booking['id']][$retrun_t] = !empty($booking['car_name']) ? $booking['car_name'] : '';
                    $managet['pickup'][$booking['id']][] = !empty($booking['pickup']) ? $booking['pickup'] : 0;
                    $managet['dropoff'][$booking['id']][] = !empty($booking['dropoff']) ? $booking['dropoff'] : 0;
                }
            }
        }
    }
?>
    <div id="div-boat-job-image" style="background-color: #FFF;">
        <!-- Header starts -->
        <div class="card-body pb-0">
            <div class="row">
                <span class="col-6 brand-logo"><img src="app-assets/images/logo/logo-500.png" height="50"></span>
                <span class="col-6 text-right" style="color: #000;">
                    โทร : 062-3322800 / 084-7443000 / 083-1757444 </br>
                    Email : Fantasticsimilantravel11@gmail.com
                </span>
            </div>
            <div class="text-center card-text">
                <h4 class="font-weight-bolder">Check-In</h4>
                <div class="badge badge-pill badge-light-danger">
                    <h5 class="m-0 pl-1 pr-1 text-danger"><?php echo date('j F Y', strtotime($travel_date)); ?></h5>
                </div>
            </div>
        </div>
        <!-- Header ends -->
        <!-- Body starts -->
        <div class="d-flex justify-content-between align-items-center header-actions mx-1 row mt-75 pt-1">
            <div class="col-4 text-left text-bold h4"></div>
            <div class="col-4 text-center text-bold h1"><?php echo $boat_name[$manage_id]; ?></div>
            <div class="col-4 text-right mb-50"></div>
        </div>

        <table class="tableprint">
            <thead class="">
                <tr>
                    <td colspan="10">ไกด์ : <?php echo $guide_name[$manage_id]; ?></td>
                    <td colspan="4" style="background-color: <?php echo $color_hex[$manage_id]; ?>;">
                        สี : <?php echo $color_name[$manage_id]; ?>
                    </td>
                </tr>
                <tr>
                    <th width="2%"></th>
                    <th width="5%">เวลารับ</th>
                    <th width="5%">Driver</th>
                    <th width="15%">เอเยนต์</th>
                    <th width="15%">ชื่อลูกค้า</th>
                    <th width="5%">V/C</th>
                    <th width="22%">โรงแรม</th>
                    <th width="5%">ห้อง</th>
                    <th class="text-center" width="1%">A</th>
                    <th class="text-center" width="1%">C</th>
                    <th class="text-center" width="1%">Inf</th>
                    <th class="text-center" width="1%">FOC</th>
                    <!-- <th class="text-center">รวม</th> -->
                    <th width="5%">COT</th>
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
                if (!empty($book['id'][$manage_id])) {
                    for ($a = 0; $a < count($book['id'][$manage_id]); $a++) {
                        $total_tourist = $total_tourist + $book['adult'][$manage_id][$a] + $book['child'][$manage_id][$a] + $book['infant'][$manage_id][$a] + $book['foc'][$manage_id][$a];
                        $total_adult = $total_adult + $book['adult'][$manage_id][$a];
                        $total_child = $total_child + $book['child'][$manage_id][$a];
                        $total_infant = $total_infant + $book['infant'][$manage_id][$a];
                        $total_foc = $total_foc + $book['foc'][$manage_id][$a];
                        $id = $book['id'][$manage_id][$a];
                        $class_tr = ($a % 2 == 1) ? 'table-active' : '';
                ?>
                        <tr class="<?php echo $class_tr; ?>">
                            <td class="text-center"><?php echo $book['check'][$manage_id][$a] > 0 ? '<i data-feather="check"></i>' : ''; ?></td>
                            <td class="bg-primary bg-lighten-4"><?php echo $book['start_pickup'][$manage_id][$a] != '00:00' ? $book['start_pickup'][$manage_id][$a] . ' - ' . $book['end_pickup'][$manage_id][$a] : ''; ?></td>
                            <td style="padding: 5px;"><?php echo (!empty($managet['car'][$id][1])) ? $managet['car'][$id][1] : ''; ?></td>
                            <td><?php echo $book['comp_name'][$manage_id][$a]; ?></td>
                            <td><?php echo !empty($book['telephone'][$manage_id][$a]) ? $book['cus_name'][$manage_id][$a] . ' <br>(' . $book['telephone'][$manage_id][$a] . ')' : $book['cus_name'][$manage_id][$a]; ?></td>
                            <td><?php echo !empty($book['voucher'][$manage_id][$a]) ? $book['voucher'][$manage_id][$a] : $book['book_full'][$manage_id][$a]; ?></td>
                            <td style="padding: 5px;">
                                <?php if ($pickup_type[$id] == 1) {
                                    echo (!empty($hotel_name[$id])) ? '<b>Pickup : </b>' . $hotel_name[$id] . $zone_pickup[$id] . '</br>' : '<b>Pickup : </b>' . $outside[$id] . $zone_pickup[$id] . '</br>';
                                    echo (!empty($dropoff_name[$id])) ? '<b>Dropoff : </b>' . $dropoff_name[$id] . $zone_dropoff[$id] : '<b>Dropoff : </b>' . $outside_dropoff[$id]  . $zone_dropoff[$id];
                                } else {
                                    echo 'เดินทางมาเอง';
                                } ?>
                            </td>
                            <td><?php echo $book['room_no'][$manage_id][$a]; ?></td>
                            <td class="text-center bg-warning bg-lighten-3"><?php echo $book['adult'][$manage_id][$a]; ?></td>
                            <td class="text-center bg-info bg-lighten-3"><?php echo $book['child'][$manage_id][$a]; ?></td>
                            <td class="text-center bg-warning bg-lighten-3"><?php echo $book['infant'][$manage_id][$a]; ?></td>
                            <td class="text-center bg-info bg-lighten-3"><?php echo $book['foc'][$manage_id][$a]; ?></td>
                            <!-- <td class="text-center"><?php echo !empty($bec_rate_total[$id]) ? number_format($book['total'][$manage_id][$a] + array_sum($bec_rate_total[$id])) : number_format($book['total'][$manage_id][$a]); ?></td> -->
                            <td class="text-nowrap"><b class="text-danger"><?php echo $book['cot'][$manage_id][$a] > 0 ? number_format($book['cot'][$manage_id][$a]) : ''; ?></b></td>
                            <td><b class="text-info">
                                    <?php if (!empty($bec_id[$id])) {
                                        for ($e = 0; $e < count($bec_name[$id]); $e++) {
                                            echo $e == 0 ? $bec_name[$id][$e]  : ' : ' . $bec_name[$id][$e];
                                        }
                                    }
                                    echo !empty($book['note'][$manage_id][$a]) ? ' / ' . $book['note'][$manage_id][$a] : ''; ?>
                                </b></td>
                        </tr>
                <?php }
                } ?>
            </tbody>
        </table>

        <div class="text-center mt-1 pb-2">
            <h4>
                <div class="badge badge-pill badge-light-warning">
                    <b class="text-danger">TOTAL <?php echo $total_tourist; ?></b> |
                    Adult : <?php echo $total_adult; ?>
                    Child : <?php echo $total_child; ?>
                    Infant : <?php echo $total_infant; ?>
                    FOC : <?php echo $total_foc; ?>
                </div>
            </h4>
        </div>
        <input type="hidden" id="name_img" name="name_img" value="<?php echo 'ใบจัดเรือ - ' . date('j F Y', strtotime($travel_date)); ?>">
        <!-- Body ends -->
    </div>
<?php
}
