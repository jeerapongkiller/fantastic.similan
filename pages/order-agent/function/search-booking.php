<?php
require_once '../../../config/env.php';
require_once '../../../controllers/Order.php';

$orderObj = new Order();
$today = date("Y-m-d");

if (isset($_POST['action']) && $_POST['action'] == "search" && !empty($_POST['agent_id']) && !empty($_POST['travel_date'])) {
    // get value from ajax
    $agent_id = $_POST['agent_id'] != "" ? $_POST['agent_id'] : 0;
    $travel_date = $_POST['travel_date'] != "" ? $_POST['travel_date'] : '0000-00-00';

    $all_bookings = $orderObj->fetch_all_bookingagent($agent_id, $travel_date);
?>
    <div class="modal-header">
        <h4 class="modal-title"><span class="text-success"><?php echo $all_bookings[0]['agent_name']; ?></span> (<?php echo date('j F Y', strtotime($travel_date)); ?>)</h4>
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
                    <b class="text-danger"><?php echo $all_bookings[0]['agent_name']; ?></b> <span class="text-danger">(<?php echo date('j F Y', strtotime($travel_date)); ?>)</span>
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
                    <th class="text-center" width="1%">รวม</th>
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

        <!-- <div class="text-center mt-1 pb-2">
            <h4>
                <div class="badge badge-pill badge-light-warning">
                    <b class="text-danger">TOTAL <?php echo $total_tourist; ?></b> |
                    Adult : <?php echo $total_adult; ?>
                    Child : <?php echo $total_child; ?>
                    Infant : <?php echo $total_infant; ?>
                    FOC : <?php echo $total_foc; ?>
                </div>
            </h4>
        </div> -->
    </div>
    <input type="hidden" id="name_img" value="<?php echo 'Re Confirm Agent - ' . $all_bookings[0]['agent_name'] . ' (' . date('j F Y', strtotime($travel_date)) . ')'; ?>">
    <div class="modal-footer">
        <a href="./?pages=order-agent/print&action=print&search_period=today&agent_id=<?php echo $agent_id; ?>&travel_date=<?php echo $travel_date; ?>" target="_blank" class="btn btn-info">Print</a>
        <a href="javascript:void(0)"><button type="button" class="btn btn-info" value="image" onclick="download_image();">Image</button></a>
    </div>
<?php
} else {
    echo false;
}
