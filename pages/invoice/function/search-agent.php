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
        'bookings.id as bo_id, companies.id as agent_id, companies.name as agent_name',
        ' bookings LEFT JOIN companies ON companies.id = bookings.company_id LEFT JOIN booking_products ON bookings.id = booking_products.booking_id LEFT JOIN invoices ON bookings.id = invoices.booking_id',
        !empty(substr($_POST['travel_date'], 14, 24)) ? ' booking_products.travel_date BETWEEN ' . $travel_date . ' AND invoices.id IS NULL AND companies.id != 1331' : ' booking_products.travel_date = ' . $travel_date . ' AND invoices.id IS NULL AND companies.id != 1331',
        1
    );

    if (!empty($agents)) {
        # --- 
        $array_bo = array();
        foreach ($agents as $agent) {
            if (in_array($agent['bo_id'], $array_bo) == false) {
                $array_bo[] = $agent['bo_id'];
                $bo_id[$agent['agent_id']][] = $agent['bo_id'];
            }
        }

        $array_agent = array();
        foreach ($agents as $agent) {
            if (in_array($agent['agent_id'], $array_agent) == false) {
                $array_agent[] = $agent['agent_id'];
?>
                <div class="col-2 p-50">
                    <a href="javascript:void(0);" onclick="search_booking(<?php echo !empty($agent['agent_id']) ? $agent['agent_id'] : '\'IS-NULL\''; ?>);">
                        <div class="border p-50 text-center">
                            <h6><?php echo !empty($agent['agent_name']) ? $agent['agent_name'] : 'ไม่ได้ระบุ'; ?></h6>
                            <h2 class="fw-bolder"><?php echo count($bo_id[$agent['agent_id']]); ?></h2>
                        </div>
                    </a>
                </div>
<?php }
        }
    }
} else {
    echo false;
}
