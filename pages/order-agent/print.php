<?php
require_once 'controllers/Order.php';

$bookingObj = new Order();

if (isset($_GET['action']) && $_GET['action'] == "print" && !empty($_GET['agent_id']) && !empty($_GET['travel_date'])) {
    // get value from ajax
    $agent_id = $_GET['agent_id'] != "" ? $_GET['agent_id'] : 0;
    $travel_date = $_GET['travel_date'] != "" ? $_GET['travel_date'] : '0000-00-00';
    // $search_status = $_GET['search_status'] != "" ? $_GET['search_status'] : 'all';
    // $search_agent = $_GET['search_agent'] != "" ? $_GET['search_agent'] : 'all';
    // $search_product = $_GET['search_product'] != "" ? $_GET['search_product'] : 'all';
    // $search_voucher_no = $_GET['search_voucher_no'] != "" ? $_GET['search_voucher_no'] : '';
    // $refcode = $_GET['refcode'] != "" ? $_GET['refcode'] : '';
    // $name = $_GET['name'] != "" ? $_GET['name'] : '';

    $first_booking = array();
    $first_ext = array();
    $bookings = $bookingObj->showlistboats('agent', $agent_id, $travel_date, 'all', 'all', 'all', 'all', 'all', '', '', '');
    if (!empty($bookings)) {
        foreach ($bookings as $booking) {
            # --- get value booking --- #
            if (in_array($booking['id'], $first_booking) == false) {
                $first_booking[] = $booking['id'];
                $bo_id[] = !empty($booking['id']) ? $booking['id'] : 0;
                $agent_name[] = !empty($booking['comp_name']) ? $booking['comp_name'] : '';
                $adult[] = !empty($booking['bp_adult']) ? $booking['bp_adult'] : 0;
                $child[] = !empty($booking['bp_child']) ? $booking['bp_child'] : 0;
                $infant[] = !empty($booking['bp_infant']) ? $booking['bp_infant'] : 0;
                $foc[] = !empty($booking['bp_foc']) ? $booking['bp_foc'] : 0;
                $rate_adult[] = !empty($booking['rate_adult']) ? $booking['rate_adult'] : 0;
                $rate_child[] = !empty($booking['rate_child']) ? $booking['rate_child'] : 0;
                $cot[] = !empty($booking['total_paid']) ? $booking['total_paid'] : 0;
                $start_pickup[] = !empty($booking['start_pickup']) ? date('H:i', strtotime($booking['start_pickup'])) : '00:00:00';
                $end_pickup[] = !empty($booking['end_pickup']) ? date('H:i', strtotime($booking['end_pickup'])) : '00:00:00';
                $car_name[] = !empty($booking['car_name']) ? $booking['car_name'] : '';
                $cus_name[] = !empty($booking['cus_name']) ? $booking['cus_name'] : '';
                $telephone[] = !empty($booking['telephone']) ? $booking['telephone'] : '';
                $book_full[] = !empty($booking['book_full']) ? $booking['book_full'] : '';
                $voucher_no[] = !empty($booking['voucher_no_agent']) ? $booking['voucher_no_agent'] : '';
                $pickup_type[] = !empty($booking['pickup_type']) ? $booking['pickup_type'] : 0;
                $room_no[] = !empty($booking['room_no']) ? $booking['room_no'] : '-';
                $hotel_pickup[] = !empty($booking['pickup_name']) ? $booking['pickup_name'] : $booking['outside'];
                $zone_pickup[] = !empty($booking['zonep_name']) ? ' (' . $booking['zonep_name'] . ')' : '';
                $hotel_dropoff[] = !empty($booking['dropoff_name']) ? $booking['dropoff_name'] : $booking['outside_dropoff'];
                $zone_dropoff[] = !empty($booking['zoned_name']) ? ' (' . $booking['zoned_name'] . ')' : '';
                $bp_note[] = !empty($booking['bp_note']) ? $booking['bp_note'] : '';
                $product_name[] = !empty($booking['product_name']) ? $booking['product_name'] : '';
                $total[] = $booking['booktye_id'] == 1 ? ($booking['bp_adult'] * $booking['rate_adult']) + ($booking['bp_child'] * $booking['rate_child']) + ($booking['rate_infant'] * $booking['rate_infant']) : $booking['rate_private'];
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
        }
    }
?>

    <div class="card-body pb-0 pt-0">
        <div class="row">
            <span class="col-6 brand-logo"><img src="app-assets/images/logo/logo-500.png" height="50"></span>
            <span class="col-6 text-right" style="color: #000;">
                โทร : 062-3322800 / 084-7443000 / 083-1757444 </br>
                Email : Fantasticsimilantravel11@gmail.com
            </span>
        </div>
        <div class="text-center card-text">
            <h4 class="font-weight-bolder">Re Confirm Agent</h4>
            <h5 class="font-weight-bolder"><?php echo date('j F Y', strtotime($travel_date)); ?></h5>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center header-actions mx-1 row pt-1">
        <div class="col-4 text-left text-bold h4"></div>
        <div class="col-4 text-center text-bold h4"><?php echo $agent_name[0]; ?></div>
        <div class="col-4 text-right mb-50"></div>
    </div>

    <div class="table-responsive" id="order-agent-search-table">
        <table>
            <thead>
                <tr>
                    <th class="text-center" width="5%">เวลารับ</th>
                    <th width="14%">โปรแกรม</th>
                    <th width="15%">ชื่อลูกค้า</th>
                    <th class="text-center" width="7%">V/C</th>
                    <th width="20%">โรงแรม</th>
                    <th width="5%">ห้อง</th>
                    <th class="text-center" width="1%">A</th>
                    <th class="text-center" width="1%">C</th>
                    <th class="text-center" width="1%">Inf</th>
                    <th class="text-center" width="1%">FOC</th>
                    <!-- <th class="text-center">รวม</th> -->
                    <th class="text-center" width="3%">COT</th>
                    <th width="10%">Remark</th>
            </thead>
            <tbody>
                <?php
                $total_tourist = 0;
                $total_adult = 0;
                $total_child = 0;
                $total_infant = 0;
                $total_foc = 0;
                if (!empty($bo_id)) {
                    for ($i = 0; $i < count($bo_id); $i++) {
                        $total_tourist = $total_tourist + $adult[$i] + $child[$i] + $infant[$i] + $foc[$i];
                        $total_adult = $total_adult + $adult[$i];
                        $total_child = $total_child + $child[$i];
                        $total_infant = $total_infant + $infant[$i];
                        $total_foc = $total_foc + $foc[$i];
                        $class_tr = ($a % 2 == 1) ? 'table-active' : ''; ?>
                        <tr class="<?php echo $class_tr; ?>">
                            <td class="text-center"><?php echo $start_pickup[$i] . ' - ' . $end_pickup[$i]; ?></td>
                            <td><?php echo $product_name[$i]; ?></td>
                            <td><?php echo !empty($telephone[$i]) ? $cus_name[$i] . ' <br>(' . $telephone[$i] . ')' : $cus_name[$i]; ?></td>
                            <td><?php echo !empty($voucher_no[$i]) ? $voucher_no[$i] : $book_full[$i]; ?></td>
                            <td style="padding: 5px;">
                                <?php if ($pickup_type[$i] == 1) {
                                    echo (!empty($hotel_pickup[$i])) ? '<b>Pickup : </b>' . $hotel_pickup[$i] . $zone_pickup[$i] : '';
                                    echo (!empty($hotel_dropoff[$i])) ? '</br><b>Dropoff : </b>' . $hotel_dropoff[$i] . $zone_dropoff[$i] : '';
                                } else {
                                    echo 'เดินทางมาเอง';
                                } ?>
                            </td>
                            <td><?php echo $room_no[$i]; ?></td>
                            <td class="text-center"><?php echo $adult[$i]; ?></td>
                            <td class="text-center"><?php echo $child[$i]; ?></td>
                            <td class="text-center"><?php echo $infant[$i]; ?></td>
                            <td class="text-center"><?php echo $foc[$i]; ?></td>
                            <!-- <td class="text-center"><?php echo !empty($bec_rate_total[$bo_id[$i]]) ? number_format($total[$i] + array_sum($bec_rate_total[$bo_id[$i]])) : number_format($total[$i]); ?></td> -->
                            <td class="text-nowrap"><b class="text-danger"><?php echo !empty($cot[$i]) ? number_format($cot[$i]) : ''; ?></b></td>
                            <td><b class="text-info">
                                    <?php if (!empty($bec_id[$bo_id[$i]])) {
                                        for ($e = 0; $e < count($bec_name[$bo_id[$i]]); $e++) {
                                            echo $e == 0 ? $bec_name[$bo_id[$i]][$e] : ' : ' . $bec_name[$bo_id[$i]][$e];
                                            // if ($bec_type[$bo_id[$i]][$e] == 1) {
                                            //     echo 'A ' . $bec_adult[$bo_id[$i]][$e] . ' X ' . $bec_rate_adult[$bo_id[$i]][$e];
                                            //     echo !empty($bec_child[$bo_id[$i]][$e]) ? ' C ' . $bec_child[$bo_id[$i]][$e] . ' X ' . $bec_rate_child[$bo_id[$i]][$e] : '';
                                            // } elseif ($bec_type[$bo_id[$i]][$e] == 2) {
                                            //     echo $bec_privates[$bo_id[$i]][$e] . ' X ' . $bec_rate_total[$bo_id[$i]][$e] . ' ';
                                            // }
                                        }
                                    }
                                    echo !empty($bp_note[$i]) ? ' / ' . $bp_note[$i] : ''; ?>
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
    </div>
    <input type="hidden" id="name_img" name="name_img" value="<?php echo $name_img; ?>">
<?php
} else {
    echo false;
}
