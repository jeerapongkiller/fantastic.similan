<?php
require_once '../../../config/env.php';
require_once '../../../controllers/Order.php';

$orderObj = new Order();
$today = date("Y-m-d");

if (isset($_POST['action']) && $_POST['action'] == "search" && !empty($_POST['travel_date'])) {
    // get value from ajax
    $travel_date = $_POST['travel_date'] != "" ? $_POST['travel_date'] : '0000-00-00';

    $first_booking = array();
    $first_company = array();
    $bookings = $orderObj->showlistboats('agent', 0, $travel_date, 'all', 'all');
    if (!empty($bookings)) {
        foreach ($bookings as $booking) {
            # --- get value agent --- #
            if (in_array($booking['comp_id'], $first_company) == false) {
                $first_company[] = $booking['comp_id'];
                $agent_id[] = !empty($booking['comp_id']) ? $booking['comp_id'] : 0;
                $agent_name[] = !empty($booking['comp_name']) ? $booking['comp_name'] : '';
            }
            # --- get value booking --- #
            if (in_array($booking['id'], $first_booking) == false) {
                $first_booking[] = $booking['id'];
                $bo_id[$booking['comp_id']][] = !empty($booking['id']) ? $booking['id'] : 0;
                $adult[$booking['comp_id']][] = !empty($booking['bp_adult']) ? $booking['bp_adult'] : 0;
                $child[$booking['comp_id']][] = !empty($booking['bp_child']) ? $booking['bp_child'] : 0;
                $infant[$booking['comp_id']][] = !empty($booking['bp_infant']) ? $booking['bp_infant'] : 0;
                $foc[$booking['comp_id']][] = !empty($booking['bp_foc']) ? $booking['bp_foc'] : 0;
                $cot[$booking['comp_id']][] = !empty($booking['total_paid']) ? $booking['total_paid'] : 0;
            }
        }
    }
?>

    <div class="d-flex justify-content-between align-items-center header-actions mx-1 row mt-75 pt-1">
        <div class="col-4 text-left text-bold h4"></div>
        <div class="col-4 text-center text-bold h4"><?php echo date('j F Y', strtotime($travel_date)); ?></div>
        <div class="col-4 text-right mb-50"></div>
    </div>

    <?php if (!empty($agent_id)) { ?>
        <table class="table table-striped text-uppercase table-vouchure-t2">
            <thead class="bg-light">
                <tr>
                    <th>ชื่อเอเยนต์</th>
                    <th class="text-center">Booking</th>
                    <th class="text-center">Total</th>
                    <th class="text-center">Audlt</th>
                    <th class="text-center">Children</th>
                    <th class="text-center">Infant</th>
                    <th class="text-center">FOC</th>
                    <th class="text-center">COT</th>
                </tr>
            </thead>
            <tbody>
                <?php for ($i = 0; $i < count($agent_id); $i++) { ?>
                    <tr onclick="modal_detail(<?php echo $agent_id[$i]; ?>, '<?php echo $agent_name[$i]; ?>', '<?php echo $travel_date; ?>');" data-toggle="modal" data-target="#modal-detail">
                        <td><?php echo $agent_name[$i]; ?></td>
                        <td class="text-center"><?php echo !empty($bo_id[$agent_id[$i]]) ? count($bo_id[$agent_id[$i]]) : 0; ?></td>
                        <td class="text-center"><?php echo array_sum($adult[$agent_id[$i]]) + array_sum($child[$agent_id[$i]]) + array_sum($infant[$agent_id[$i]]) + array_sum($foc[$agent_id[$i]]); ?></td>
                        <td class="text-center"><?php echo !empty($adult[$agent_id[$i]]) ? array_sum($adult[$agent_id[$i]]) : 0; ?></td>
                        <td class="text-center"><?php echo !empty($child[$agent_id[$i]]) ? array_sum($child[$agent_id[$i]]) : 0; ?></td>
                        <td class="text-center"><?php echo !empty($infant[$agent_id[$i]]) ? array_sum($infant[$agent_id[$i]]) : 0; ?></td>
                        <td class="text-center"><?php echo !empty($foc[$agent_id[$i]]) ? array_sum($foc[$agent_id[$i]]) : 0; ?></td>
                        <td class="text-center"><?php echo !empty($cot[$agent_id[$i]]) ? number_format(array_sum($cot[$agent_id[$i]])) : 0; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } ?>

<?php
} else {
    echo false;
}
