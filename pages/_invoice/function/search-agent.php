<?php
require_once '../../../config/env.php';
require_once '../../../controllers/Invoice.php';

$invObj = new Invoice();
$today = date("Y-m-d");
$times = date("H:i:s");

function diff_date($today, $diff_date)
{
    $diff_inv = array();
    $date1 = date_create($today);
    $date2 = date_create($diff_date);
    $diff = date_diff($date1, $date2);
    $diff_inv['day'] =  $diff->format("%R%a");
    $diff_inv['num'] =  $diff->format("%a");

    return $diff_inv;
}

if (isset($_POST['action']) && $_POST['action'] == "search" && isset($_POST['travel_date'])) {

    $travel_date = !empty($_POST['travel_date']) ? $_POST['travel_date'] : $today;
    $travel_date = !empty(substr($_POST['travel_date'], 14, 24)) ? "'" . substr($_POST['travel_date'], 0, 10) . "' AND '" . substr($_POST['travel_date'], 14, 24) . "'" : "'" . $travel_date . "'";

    $agents = $invObj->get_value(
        'bookings.id as bo_id, booking_products.id as bp_id, booking_paid.total_paid as cot, companies.id as agent_id, companies.name as agent_name',
        'bookings LEFT JOIN companies ON companies.id = bookings.company_id LEFT JOIN booking_paid ON bookings.id = booking_paid.booking_id AND booking_paid.booking_payment_id = 4 LEFT JOIN booking_products ON bookings.id = booking_products.booking_id LEFT JOIN invoices ON bookings.id = invoices.booking_id',
        !empty(substr($_POST['travel_date'], 14, 24)) ? 
        ' booking_products.travel_date BETWEEN ' . $travel_date . ' AND invoices.id IS NULL AND companies.id != 1331 AND bookings.booking_status_id != 3 AND bookings.booking_status_id != 4 ORDER BY companies.name ASC' : 
        ' booking_products.travel_date = ' . $travel_date . 
        ' AND invoices.id IS NULL AND companies.id != 1331 AND bookings.booking_status_id != 3 AND bookings.booking_status_id != 4 ORDER BY companies.name ASC',
        1
    );
?>

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
            <?php
            if (!empty($agents)) {
                # --- 
                $array_bo = array();
                foreach ($agents as $agent) {
                    if (in_array($agent['bo_id'], $array_bo) == false) {
                        $array_bo[] = $agent['bo_id'];
                        $bo_id[$agent['agent_id']][] = $agent['bo_id'];
                        $cot[$agent['agent_id']][] = $agent['cot'];
                    }
                }

                $all_rates = $invObj->get_value('*', ' booking_product_rates', 'booking_products_id = ' . $agent['bp_id'], 1);
                foreach ($all_rates as $rates) {
                    $adult[] = $rates['adult'];
                    $child[] = $rates['child'];
                    $infant[] = $rates['infant'];
                    $foc[] = $rates['foc'];
                    $tourrist[] = $rates['adult'] + $rates['child'] + $rates['infant'] + $rates['foc'];
                }

                $array_agent = array();
                foreach ($agents as $agent) {
                    if (in_array($agent['agent_id'], $array_agent) == false) {
                        $array_agent[] = $agent['agent_id'];
            ?>
                        <tr onclick="search_booking(<?php echo !empty($agent['agent_id']) ? $agent['agent_id'] : '\'IS-NULL\''; ?>);">
                            <td><?php echo !empty($agent['agent_name']) ? $agent['agent_name'] : 'ไม่ได้ระบุ'; ?></td>
                            <td class="text-center"><?php echo count($bo_id[$agent['agent_id']]); ?></td>
                            <td class="text-center"><?php echo !empty($tourrist) ? array_sum($tourrist) : 0; ?></td>
                            <td class="text-center"><?php echo !empty($adult) ? array_sum($adult) : 0; ?></td>
                            <td class="text-center"><?php echo !empty($child) ? array_sum($child) : 0; ?></td>
                            <td class="text-center"><?php echo !empty($infant) ? array_sum($infant) : 0; ?></td>
                            <td class="text-center"><?php echo !empty($foc) ? array_sum($foc) : 0; ?></td>
                            <td class="text-center"><?php echo !empty($cot[$agent['agent_id']]) ? number_format(array_sum($cot[$agent['agent_id']])) : 0; ?></td>
                        </tr>
            <?php }
                }
            }
            ?>
        </tbody>
    </table>


<?php
} else {
    echo false;
}
