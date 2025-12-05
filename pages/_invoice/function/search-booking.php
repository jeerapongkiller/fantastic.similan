<?php
require_once '../../../config/env.php';
require_once '../../../controllers/Invoice.php';

$invObj = new Invoice();
$today = date("Y-m-d");

if (isset($_POST['action']) && $_POST['action'] == "search" && !empty($_POST['travel_date'])) {
    // get value from ajax
    $agent_id = $_POST['agent_id'] != "" ? $_POST['agent_id'] : '';
    $travel_date = !empty($_POST['travel_date']) ? $_POST['travel_date'] : $today;

    $all_bookings = $invObj->fetch_all_booking($travel_date, $agent_id, $refcode = '', $voucher_no = '', $inv_id = 0);

    if (!empty($all_bookings)) {
?>
        <hr>
        <table class="table table-bordered table-striped text-uppercase">
            <thead class="bg-light">
                <tr>
                    <th class="text-center p-50" colspan="8">
                        <h5 class="text-success font-weight-bolder m-0"><?php echo !empty($all_bookings[0]['agent_name']) ? $all_bookings[0]['agent_name'] : 'ไม่ได้ระบุ'; ?></h5>
                    </th>
                    <th class="text-center p-50" colspan="7">
                        <h5 class="text-warning font-weight-bolder m-0"><?php echo !empty(substr($_POST['travel_date'], 14, 24)) ? date('j F Y', strtotime(substr($_POST['travel_date'], 0, 10))) . ' - ' . date('j F Y', strtotime(substr($_POST['travel_date'], 14, 24))) : date('j F Y', strtotime($_POST['travel_date'])); ?></h5>
                    </th>
                </tr>
                <tr>
                    <th class="cell-fit">
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input dt-checkboxes" type="checkbox" id="checkbo_all" name="checkbo_all" onclick="checkbox();">
                            <label class="custom-control-label" for="checkbo_all"></label>
                        </div>
                    </th>
                    <th class="text-center">Status</th>
                    <th>โปรแกรม</th>
                    <th>ชื่อลูกค้า</th>
                    <th>V/C</th>
                    <th>โรงแรม</th>
                    <th>ห้อง</th>
                    <th class="text-center cell-fit">รวม</th>
                    <th class="text-center cell-fit">A</th>
                    <th class="text-center cell-fit">C</th>
                    <th class="text-center cell-fit">Inf</th>
                    <th class="text-center cell-fit">FOC</th>
                    <th class="text-center cell-fit">COT</th>
                    <th class="text-center">AMOUNT</th>
                    <th>Remark</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total_tourist = 0;
                $total_tourist = 0;
                $total_adult = 0;
                $total_child = 0;
                $total_infant = 0;
                $total_foc = 0;
                $bo_arr = array();
                foreach ($all_bookings as $bookings) {
                    if (in_array($bookings['id'], $bo_arr) == false) {
                        $bo_arr[] = $bookings['id'];

                        $amount = 0;
                        $adult = array();
                        $child = array();
                        $infant = array();
                        $foc = array();
                        $tourist_array = array();
                        $all_rates = $invObj->get_value('*', ' booking_product_rates', 'booking_products_id = ' . $bookings['bp_id'], 1);
                        foreach ($all_rates as $rates) {
                            $adult[] = $rates['adult'];
                            $child[] = $rates['child'];
                            $infant[] = $rates['infant'];
                            $foc[] = $rates['foc'];
                            $tourist_array[] = $rates['adult'] + $rates['child'] + $rates['infant'] + $rates['foc'];
                            if ($bookings['booking_type_id'] == 1) {
                                $amount += $rates['adult'] * $rates['rates_adult'];
                                $amount += $rates['child'] * $rates['rates_child'];
                                $amount += $rates['infant'] * $rates['rates_infant'];
                            } elseif ($bookings['booking_type_id'] == 2) {
                                $amount += $rates['rates_private'];
                            }
                        }

                        $total_adult += !empty($adult) ? array_sum($adult) : 0;
                        $total_child += !empty($child) ? array_sum($child) : 0;
                        $total_infant += !empty($infant) ? array_sum($infant) : 0;
                        $total_foc += !empty($foc) ? array_sum($foc) : 0;
                        $total_tourist += !empty($tourist_array) ? array_sum($tourist_array) : 0;
                        $tourist = !empty($tourist_array) ? array_sum($tourist_array) : 0;
                        $text_hotel = '';
                        $text_hotel = (!empty($bookings['hotelp_name'])) ? '<b>Pickup : </b>' . $bookings['hotelp_name'] : '<b>Pickup : </b>' . $bookings['outside_pickup'];
                        $text_hotel .= (!empty($bookings['zonep_name'])) ? ' (' . $bookings['zonep_name'] . ')</br>' : '</br>';
                        $text_hotel .= (!empty($bookings['hoteld_name'])) ? '<b>Dropoff : </b>' . $bookings['hoteld_name'] : '<b>Dropoff : </b>' . $bookings['outside_dropoff'];
                        $text_hotel .= (!empty($bookings['zoned_name'])) ? ' (' . $bookings['zoned_name'] . ')' : '';

                        $e = 0;
                        $extra = '';
                        $extra_charges = $invObj->get_extra_charge($bookings['id']);
                        if (!empty($extra_charges)) {
                            foreach ($extra_charges as $extra_charge) {
                                if ($extra_charge['type'] == 1) {
                                    $amount += $extra_charge['adult'] * $extra_charge['rate_adult'];
                                    $amount += $extra_charge['child'] * $extra_charge['rate_child'];
                                    $amount += $extra_charge['infant'] * $extra_charge['rate_infant'];
                                } elseif ($extra_charge['type'] == 2) {
                                    $amount += $extra_charge['privates'] * $extra_charge['rate_private'];
                                }
                                $extra = $e == 0 ? $extra_charge['extra_name'] : ' : ' . $extra_charge['extra_name'];
                                $e++;
                            }
                        }
                ?>
                        <tr>
                            <td>
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input dt-checkboxes checkbox-bookings" type="checkbox" id="checkbox<?php echo $bookings['id']; ?>" name="bo_id[]" value="<?php echo $bookings['id']; ?>">
                                    <label class="custom-control-label" for="checkbox<?php echo $bookings['id']; ?>"></label>
                                </div>
                            </td>
                            <td class="text-center cell-fit"><?php echo '<span class="badge badge-pill ' . $bookings['status_class'] . ' text-capitalized"> ' . $bookings['status_name'] . ' </span>'; ?></td>
                            <td><?php echo $bookings['product_name']; ?></td>
                            <td><?php echo $bookings['cus_name']; ?></td>
                            <td><?php echo !empty($bookings['voucher_no_agent']) ? $bookings['voucher_no_agent'] : $bookings['bo_full']; ?></td>
                            <td style="padding: 5px;"><?php echo $text_hotel; ?></td>
                            <td><?php echo $bookings['room_no']; ?></td>
                            <td class="cell-fit text-center"><?php echo $tourist; ?></td>
                            <td class="cell-fit text-center"><?php echo !empty($adult) ? array_sum($adult) : 0; ?></td>
                            <td class="cell-fit text-center"><?php echo !empty($child) ? array_sum($child) : 0; ?></td>
                            <td class="cell-fit text-center"><?php echo !empty($infant) ? array_sum($infant) : 0; ?></td>
                            <td class="cell-fit text-center"><?php echo !empty($foc) ? array_sum($foc) : 0; ?></td>
                            <td class="cell-fit text-nowrap"><b class="text-danger"><?php echo !empty($bookings['cot']) ? number_format($bookings['cot']) : ''; ?></b></td>
                            <td class="text-center"><?php echo number_format($amount); ?></td>
                            <td style="padding: 5px;"><b class="text-info"><?php echo $extra . $bookings['bp_note']; ?></b></td>
                        </tr>
                <?php }
                } ?>
            </tbody>
        </table>

        <div class="text-center mt-1">
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
<?php
    }
} else {
    echo false;
}
