<?php
require_once '../../../config/env.php';
require_once '../../../controllers/Order.php';

$orderObj = new Order();
$today = date("Y-m-d");

if (isset($_POST['action']) && $_POST['action'] == "search" && !empty($_POST['agent_id']) && !empty($_POST['travel_date'])) {
    // get value from ajax
    $agent_id = $_POST['agent_id'] != "" ? $_POST['agent_id'] : 0;
    $travel_date = $_POST['travel_date'] != "" ? $_POST['travel_date'] : '0000-00-00';
    $travel_text = (!empty($travel_date) && $travel_date != '') ? !empty(substr($travel_date, 14, 24)) ?
        date('j F Y', strtotime(substr($travel_date, 0, 10)))  . " - " . date('j F Y', strtotime(substr($travel_date, 14, 24))) :
        date('j F Y', strtotime($travel_date)) :
        "";

    $all_bookings = $orderObj->fetch_all_bookingagent($agent_id, $travel_date);

    $booking_array = array();
    foreach ($all_bookings as $categorys) {
        $categorys_array[] = $categorys['id'];
        $category_name[$categorys['id']][] = $categorys['category_name'];
        $adult[$categorys['id']][] = $categorys['adult'];
        $child[$categorys['id']][] = $categorys['child'];
        $infant[$categorys['id']][] = $categorys['infant'];
        $foc[$categorys['id']][] = $categorys['foc'];
        $tourist_array[$categorys['id']][] = $categorys['adult'] + $categorys['child'] + $categorys['infant'] + $categorys['foc'];

        if (in_array($categorys['id'], $booking_array) == false) {
            $booking_array[] = $categorys['id'];
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
        }
    }
?>
    <div class="modal-header">
        <h4 class="modal-title"><span class="text-success"><?php echo $all_bookings[0]['agent_name']; ?></span> (<?php echo $travel_text; ?>)</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body" id="order-agent-image-table" style="background-color: #fff;">

        <div class="row mb-50">
            <span class="col-6 brand-logo"><img src="app-assets/images/logo/logo-500.png" height="50"></span>
            <span class="col-6 text-right" style="color: #000;">
                โทร : 062-3322800 / 084-7443000 / 083-1757444 </br>
                Email : Fantasticsimilantravel11@gmail.com
            </span>
        </div>

        <div class="text-center mt-1 mb-1">
            <h4 class="font-weight-bolder">Re Confirm Agent</h4>
            <h4>
                <div class="badge badge-pill badge-light-warning">
                    <b class="text-danger"><?php echo $all_bookings[0]['agent_name']; ?></b> <span class="text-danger">(<?php echo $travel_text; ?>)</span>
                </div>
            </h4>
        </div>

        <table class="table table-bordered table-striped text-uppercase table-vouchure-t2">
            <thead class="bg-light">
                <tr>
                    <th width="7%">Time</th>
                    <th width="14%">Programe</th>
                    <th width="15%">Name</th>
                    <th width="5%">V/C</th>
                    <th width="20%">Hotel</th>
                    <th width="5%">Room</th>
                    <!-- <th class="text-center" width="1%">รวม</th> -->
                    <th class="text-center" width="1%">A</th>
                    <th class="text-center" width="1%">C</th>
                    <th class="text-center" width="1%">Inf</th>
                    <th class="text-center" width="1%">FOC</th>
                    <th width="5%">COT</th>
                    <th width="8%">Remark</th>
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
                // foreach ($all_bookings as $key => $bookings) {
                for ($i = 0; $i < count($bo_id); $i++) {
                    $id = $bo_id[$i];
                    if (in_array($id, $booking_array) == false) {
                        $booking_array[] = $id;
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
                            <td class="text-center text-nowrap"><?php echo date('H:i', strtotime($start_pickup[$id])) . ' - ' . date('H:i', strtotime($end_pickup[$id])); ?></td>
                            <td><?php echo $product_name[$id]; ?></td>
                            <td><?php echo !empty($telephone[$id]) ? $cus_name[$id] . ' (' . $telephone[$id] . ')' : $cus_name[$id]; ?></td>
                            <td class="text-nowrap"><?php echo !empty($voucher_no_agent[$id]) ? $voucher_no_agent[$id] : $book_full[$id]; ?></td>
                            <td><?php echo $text_hotel; ?></td>
                            <td><?php echo $room_no[$id]; ?></td>
                            <!-- <td class="cell-fit text-center"><?php echo $tourist; ?></td> -->
                            <td class="text-center"><?php echo !empty($adult[$id]) ? array_sum($adult[$id]) : 0; ?></td>
                            <td class="text-center"><?php echo !empty($child[$id]) ? array_sum($child[$id]) : 0; ?></td>
                            <td class="text-center"><?php echo !empty($infant[$id]) ? array_sum($infant[$id]) : 0; ?></td>
                            <td class="text-center"><?php echo !empty($foc[$id]) ? array_sum($foc[$id]) : 0; ?></td>
                            <td><b class="text-danger"><?php echo !empty($bookings['cot']) ? number_format($bookings['cot']) : ''; ?></b></td>
                            <td>
                                <b class="text-info">
                                    <?php
                                    $e = 0;
                                    $extra_charges = $orderObj->get_extra_charge($id);
                                    if (!empty($extra_charges)) {
                                        foreach ($extra_charges as $extra_charge) {
                                            echo $e == 0 ? $extra_charge['extra_name'] : ' : ' . $extra_charge['extra_name'];
                                            $e++;
                                        }
                                    }
                                    echo $bp_note[$id]; ?>
                                </b>
                            </td>
                        </tr>
                <?php }
                } ?>
            </tbody>
        </table>

        <table class="table table-bordered table-striped text-uppercase table-vouchure-t2">
            <thead class="bg-light">
                <tr>
                    <th class="text-center">Total</th>
                    <th class="text-center">Adult</th>
                    <th class="text-center">Child</th>
                    <th class="text-center">Infant</th>
                    <th class="text-center">Foc</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center"><b><?php echo $total_tourist; ?></b></td>
                    <td class="text-center"><b><?php echo $total_adult; ?></b></td>
                    <td class="text-center"><b><?php echo $total_child; ?></b></td>
                    <td class="text-center"><b><?php echo $total_infant; ?></b></td>
                    <td class="text-center"><b><?php echo $total_foc; ?></b></td>
                </tr>
            </tbody>
        </table>

    </div>
    <input type="hidden" id="name_img" value="<?php echo 'Re Confirm Agent - ' . $all_bookings[0]['agent_name'] . ' (' . date('j F Y', strtotime($travel_date)) . ')'; ?>">
    <div class="modal-footer">
        <a href="./?pages=order-agent/print&action=print&search_period=today&agent_id=<?php echo $agent_id; ?>&travel_date=<?php echo $travel_date; ?>" target="_blank" class="btn btn-info">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer">
                <polyline points="6 9 6 2 18 2 18 9"></polyline>
                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                <rect x="6" y="14" width="12" height="8"></rect>
            </svg>
            Print
        </a>
        <a href="javascript:void(0)">
            <button type="button" class="btn btn-info" value="image" onclick="download_image();">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-image">
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                    <circle cx="8.5" cy="8.5" r="1.5"></circle>
                    <polyline points="21 15 16 10 5 21"></polyline>
                </svg>
                Image
            </button>
        </a>
    </div>
<?php
} else {
    echo false;
}
