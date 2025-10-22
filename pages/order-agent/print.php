<?php
require_once 'controllers/Order.php';

$orderObj = new Order();

if (isset($_GET['action']) && $_GET['action'] == "print" && !empty($_GET['agent_id']) && !empty($_GET['travel_date'])) {
    // get value from ajax
    $agent_id = $_GET['agent_id'] != "" ? $_GET['agent_id'] : 0;
    $travel_date = $_GET['travel_date'] != "" ? $_GET['travel_date'] : '0000-00-00';

    $all_bookings = $orderObj->fetch_all_bookingagent($agent_id, $travel_date);
?>

    <div class="card-body pb-0 pt-0">
        <div class="row text-black">
            <span class="col-6 brand-logo"><img src="app-assets/images/logo/logo-500.png" height="50"></span>
            <span class="col-6 text-right" style="color: #000;">
                โทร : 062-3322800 / 084-7443000 / 083-1757444 </br>
                Email : Fantasticsimilantravel11@gmail.com
            </span>
        </div>
        <div class="text-center card-text">
            <h4 class="font-weight-bolder text-black">Re Confirm Agent</h4>
            <h5 class="font-weight-bolder text-black"><?php echo date('j F Y', strtotime($travel_date)); ?></h5>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center header-actions mx-1 row pt-1">
        <div class="col-4 text-left text-bold h4"></div>
        <div class="col-4 text-center text-bold h4 text-black"><?php echo $all_bookings[0]['agent_name']; ?></div>
        <div class="col-4 text-right mb-50"></div>
    </div>

    <div class="table-responsive" id="order-agent-search-table">
        <table>
            <thead>
                <tr>
                    <th class="text-center" width="5%">Time</th>
                    <th width="14%">Programe</th>
                    <th width="15%">Name</th>
                    <th class="text-center" width="7%">V/C</th>
                    <th width="20%">Hotel</th>
                    <th width="5%">Room</th>
                    <th class="text-center" width="1%">รวม</th>
                    <th class="text-center" width="1%">A</th>
                    <th class="text-center" width="1%">C</th>
                    <th class="text-center" width="1%">Inf</th>
                    <th class="text-center" width="1%">FOC</th>
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
                $booking_array = array();
                foreach ($all_bookings as $key => $bookings) {
                    if (in_array($bookings['id'], $booking_array) == false) {
                        $booking_array[] = $bookings['id'];
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
                ?>
                        <tr>
                            <td class="text-center text-nowrap"><?php echo date('H:i', strtotime($bookings['start_pickup'])) . ' - ' . date('H:i', strtotime($bookings['end_pickup'])); ?></td>
                            <td><?php echo $bookings['product_name']; ?></td>
                            <td><?php echo $bookings['cus_name']; ?></td>
                            <td class="text-nowrap"><?php echo !empty($bookings['voucher_no_agent']) ? $bookings['voucher_no_agent'] : $bookings['book_full']; ?></td>
                            <td><?php echo $text_hotel; ?></td>
                            <td><?php echo $bookings['room_no']; ?></td>
                            <td class="cell-fit text-center"><?php echo $tourist; ?></td>
                            <td class="text-center"><?php echo !empty($bookings['adult']) ? $bookings['adult'] : 0; ?></td>
                            <td class="text-center"><?php echo !empty($bookings['child']) ? $bookings['child'] : 0; ?></td>
                            <td class="text-center"><?php echo !empty($bookings['infant']) ? $bookings['infant'] : 0; ?></td>
                            <td class="text-center"><?php echo !empty($bookings['foc']) ? $bookings['foc'] : 0; ?></td>
                            <td><b class="text-warning"><?php echo number_format($bookings['cot']); ?></b></td>
                            <td>
                                <b class="text-info">
                                    <?php
                                    $e = 0;
                                    $extra_charges = $orderObj->get_extra_charge($bookings['id']);
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

        <table class="mt-1">
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
    <input type="hidden" id="name_img" name="name_img" value="<?php echo $name_img; ?>">
<?php
} else {
    echo false;
}
