<?php
require_once '../../../config/env.php';
require_once '../../../controllers/Order.php';

$orderObj = new Order();
$today = date("Y-m-d");

function check_in($var)
{
    return ($var > 0);
}

if (isset($_POST['action']) && $_POST['action'] == "search" && !empty($_POST['travel_date'])) {
    // get value from ajax
    $travel_date = $_POST['travel_date'] != "0000-00-00" ? $_POST['travel_date'] : $today;

    $all_agents = $orderObj->fetch_all_agent($travel_date);
    $travel_text = (!empty($travel_date) && $travel_date != '') ? !empty(substr($travel_date, 14, 24)) ?
        date('j F Y', strtotime(substr($travel_date, 0, 10)))  . " - " . date('j F Y', strtotime(substr($travel_date, 14, 24))) :
        date('j F Y', strtotime($travel_date)) :
        "";
    $travel_strto = (!empty($travel_date) && $travel_date != '') ? !empty(substr($travel_date, 14, 24)) ?
        strtotime(substr($travel_date, 0, 10))  . " - " . strtotime(substr($travel_date, 14, 24)) :
        strtotime($travel_date) :
        "";
?>

    <div class="d-flex justify-content-between align-items-center header-actions mx-1 row mt-75 pt-1">
        <div class="col-4 text-left text-bold h4"></div>
        <div class="col-4 text-center text-bold h4"><?php echo $travel_text; ?></div>
        <div class="col-4 text-right mb-50"></div>
    </div>

    <table class="table table-striped text-uppercase table-vouchure-t2">
        <thead class="bg-light">
            <tr>
                <th class="text-center" width="1%">
                    <div class="custom-control custom-checkbox">
                        <input class="custom-control-input dt-checkboxes" type="checkbox" id="checkall-<?php echo $travel_strto; ?>" onclick="checkbox('<?php echo $travel_strto; ?>');" />
                        <label class="custom-control-label" for="checkall-<?php echo $travel_strto; ?>"></label>
                    </div>
                </th>
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
            if (!empty($all_agents)) {

                // $no = 0;
                // $tourist = 0;
                // $adult = 0;
                // $child = 0;
                // $infant = 0;
                // $foc = 0;
                // $cot = 0;
                // $confirm = 0;
                $booking_arr = array();
                foreach ($all_agents as $booking) {
                    if (in_array($booking['id'], $booking_arr) == false) {
                        $booking_arr[] = $booking['id'];
                        $cot[$booking['agent_id']][] = $booking['cot'];
                        $confirm[$booking['agent_id']] = $booking['confirm'];
                        // $no++;
                        $booking_no[$booking['agent_id']][] = $booking['id'];
                    }
                    // $booking_no[$booking['agent_id']][] = $booking['id'];
                    $tourist[$booking['agent_id']][] = $booking['max_tourist'];
                    $adult[$booking['agent_id']][] = $booking['adult'];
                    $child[$booking['agent_id']][] = $booking['child'];
                    $infant[$booking['agent_id']][] = $booking['infant'];
                    $foc[$booking['agent_id']][] = $booking['foc'];
                }

                $agent_arr = array();
                foreach ($all_agents as $agents) {
                    if (in_array($agents['agent_id'], $agent_arr) == false && $agents['agent_id'] > 0) {
                        $agent_arr[] = $agents['agent_id'];
                        $tr = 'onclick="modal_detail(' . $agents['agent_id'] . ', \'' . addslashes($agents['agent_name']) . '\', \'' . $travel_date . '\');" data-toggle="modal" data-target="#modal-detail"';

                        // $confirm_agent = (!empty($travel_date) && $travel_date != '') ? !empty(substr($travel_date, 14, 24)) ? " AND confirm_agent.travel_date BETWEEN '" . substr($travel_date, 0, 10) . "' AND '" . substr($travel_date, 14, 24) . "'" : " AND confirm_agent.travel_date = '" . $travel_date . "'" : "";
                        // $travel_agent = (!empty($travel_date) && $travel_date != '') ? !empty(substr($travel_date, 14, 24)) ? " AND BP.travel_date BETWEEN '" . substr($travel_date, 0, 10) . "' AND '" . substr($travel_date, 14, 24) . "'" : " AND BP.travel_date = '" . $travel_date . "'" : "";

                        // $bookings = $orderObj->get_values(
                        //     'bookings.id, BPRS.adult, BPRS.child, BPRS.infant, BPRS.foc, BPRS.max_tourist, BOPA.total_paid as cot, confirm_agent.id as confirm',
                        //     'bookings
                        //     LEFT JOIN companies
                        //         ON bookings.company_id = companies.id
                        //     LEFT JOIN booking_products BP
                        //         ON bookings.id = BP.booking_id
                        //     LEFT JOIN (
                        //         SELECT BP.booking_id,
                        //             SUM(BPR.adult) AS adult,
                        //             SUM(BPR.child) AS child,
                        //             SUM(BPR.infant) AS infant,
                        //             SUM(BPR.foc) AS foc,
                        //             SUM(BPR.adult) + SUM(BPR.child) + SUM(BPR.infant) + SUM(BPR.foc) AS max_tourist
                        //         FROM booking_products BP
                        //         JOIN booking_product_rates BPR 
                        //             ON BP.id = BPR.booking_products_id
                        //         GROUP BY BP.booking_id, BPR.category_id
                        //     ) BPRS 
                        //         ON BPRS.booking_id = bookings.id
                        //     LEFT JOIN booking_paid BOPA
                        //         ON bookings.id = BOPA.booking_id
                        //         AND BOPA.booking_payment_id = 4
                        //     LEFT JOIN confirm_agent
                        //         ON confirm_agent.agent_id = ' . $agents['agent_id'] . $confirm_agent,
                        //     'companies.id = ' . $agents['agent_id'] . $travel_agent . ' AND bookings.booking_status_id != 3 AND bookings.booking_status_id != 4',
                        //     1
                        // );
            ?>
                        <tr <?php echo !empty($confirm[$agents['agent_id']]) ? 'class="table-success"' : ''; ?>>
                            <td class="text-center">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input dt-checkboxes checkbox-<?php echo $travel_strto; ?>" type="checkbox" id="checkbox<?php echo strtotime($travel_date) . '-' . $agents['id']; ?>"
                                        data-travel="<?php echo $travel_date; ?>"
                                        data-check="<?php echo !empty($confirm[$agents['agent_id']]) ? $confirm[$agents['agent_id']] : 0; ?>"
                                        data-confirm="<?php echo !empty($confirm[$agents['agent_id']]) ? $confirm[$agents['agent_id']] : 0; ?>"
                                        value="<?php echo $agents['agent_id']; ?>"
                                        onclick="submit_check_in('only', this);" <?php echo !empty($confirm[$agents['agent_id']]) ? 'checked' : ''; ?> />
                                    <label class="custom-control-label" for="checkbox<?php echo strtotime($travel_date) . '-' . $agents['id']; ?>"></label>
                                </div>
                            </td>
                            <td <?php echo $tr; ?>><?php echo $agents['agent_name']; ?></td>
                            <td class="text-center" <?php echo $tr; ?>><?php echo !empty($booking_no[$agents['agent_id']]) ? count($booking_no[$agents['agent_id']]) : 0;; ?></td>
                            <td class="text-center" <?php echo $tr; ?>><?php echo !empty($tourist[$agents['agent_id']]) ? array_sum($tourist[$agents['agent_id']]) : 0; ?></td>
                            <td class="text-center" <?php echo $tr; ?>><?php echo !empty($adult[$agents['agent_id']]) ? array_sum($adult[$agents['agent_id']]) : 0; ?></td>
                            <td class="text-center" <?php echo $tr; ?>><?php echo !empty($child[$agents['agent_id']]) ? array_sum($child[$agents['agent_id']]) : 0; ?></td>
                            <td class="text-center" <?php echo $tr; ?>><?php echo !empty($infant[$agents['agent_id']]) ? array_sum($infant[$agents['agent_id']]) : 0; ?></td>
                            <td class="text-center" <?php echo $tr; ?>><?php echo !empty($foc[$agents['agent_id']]) ? array_sum($foc[$agents['agent_id']]) : 0; ?></td>
                            <td class="text-center" <?php echo $tr; ?>><?php echo !empty($cot[$agents['agent_id']]) ? number_format(array_sum($cot[$agents['agent_id']])) : 0; ?></td>
                        </tr>
            <?php }
                }
            } ?>
        </tbody>
    </table>

<?php
} else {
    echo false;
}
