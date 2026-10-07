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
    $manage = !empty($_GET['manage_id']) ? $_GET['manage_id'] : 0;

    $bo_arr = array();
    $bomange_arr = array();
    $categorys_array = array();
    $cars_arr = array();
    $extra_arr = array();
    $bpr_arr = array();
    $manages_arr = array();
    $all_bookings = $manageObj->fetch_all_bookingboat('all', $get_date, $search_status, $search_agent, $search_product, $search_voucher_no, $refcode, $name, $hotel = '', $search_boat, $search_guide = 'all', $manage);
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
        if (!empty($manage_id)) {
            for ($m = 0; $m < count($manage_id); $m++) {
        ?>
                <div class="d-flex justify-content-between align-items-center header-actions mx-1 row mt-75 pt-1">
                    <div class="col-4 text-left text-bold h4"></div>
                    <div class="col-4 text-center text-bold h1"><?php echo $boat_name[$m]; ?></div>
                    <div class="col-4 text-right mb-50"></div>
                </div>

                <table class="tableprint">
                    <thead class="">
                        <tr>
                            <td colspan="5">ไกด์ : <?php echo $guide_name[$m]; ?></td>
                            <td colspan="6">เคาน์เตอร์ : <?php echo $counter[$m]; ?></td>
                            <td colspan="3" style="background-color: <?php echo $color_hex[$m]; ?>; <?php echo $text_color[$m] != '' ? 'color: ' . $text_color[$m] . ';' : ''; ?>">
                                สี : <?php echo $color_name_th[$m]; ?>
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
                            <!-- <th class="text-center">รวม</th> -->
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
                        if (!empty($booking_id[$manage_id[$m]])) {
                            $booking_id_arr = array();
                            for ($i = 0; $i < count($booking_id[$manage_id[$m]]); $i++) {
                                if (in_array($booking_id[$manage_id[$m]][$i], $booking_id_arr) == false) {
                                    $booking_id_arr[] = $booking_id[$manage_id[$m]][$i];
                                    $id = $booking_id[$manage_id[$m]][$i];

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
                                        <td class="cell-fit"><?php echo date('H:i', strtotime($start_pickup[$id])) . ' - ' . date('H:i', strtotime($end_pickup[$id])); ?></td>
                                        <td class="cell-fit">
                                            <?php if (!empty($car_name[$id])) {
                                                for ($c = 0; $c < count($car_name[$id]); $c++) {
                                                    echo $c > 0 ? '<br>' : '';
                                                    echo '<div class="badge badge-light-success">' . $car_name[$id][$c] . '</div>';
                                                }
                                            } ?>
                                        </td>
                                        <td><?php echo $agent_name[$id]; ?></td>
                                        <td><?php echo $cus_name[$id]; ?></td>
                                        <td><?php echo !empty($voucher_no_agent[$id]) ? $voucher_no_agent[$id] : $book_full[$id]; ?></td>
                                        <td style="padding: 5px;"><?php echo $text_hotel; ?></td>
                                        <td class="cell-fit"><?php echo $room_no[$id]; ?></td>
                                        <td class="text-center"><?php echo !empty($adult[$id]) ? array_sum($adult[$id]) : 0; ?></td>
                                        <td class="text-center"><?php echo !empty($child[$id]) ? array_sum($child[$id]) : 0; ?></td>
                                        <td class="text-center"><?php echo !empty($infant[$id]) ? array_sum($infant[$id]) : 0; ?></td>
                                        <td class="text-center"><?php echo !empty($foc[$id]) ? array_sum($foc[$id]) : 0; ?></td>
                                        <td class="cell-fit text-nowrap"><b class="text-danger"><?php echo !empty($cot[$id]) ? number_format($cot[$id]) : ''; ?></b></td>
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
