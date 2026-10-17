<?php
require_once 'controllers/Order.php';

$manageObj = new Order();

// Validate travel date (Y-m-d)
$date_travel = !empty($_GET['date']) ? $_GET['date'] : '';
$date_obj = DateTime::createFromFormat('Y-m-d', $date_travel);
if (!$date_obj || $date_obj->format('Y-m-d') !== $date_travel) {
    echo '<div class="text-center p-3">Invalid date</div>';
    return;
}

// Same data source and grouping as function/search-report.php (car summary)
$first_driver = array();
$first_bpr = array();
$cars = array();
$bookings = $manageObj->showlisttransfers('report', 1, $date_travel, 'all', 'all', 'all', 'all', 'all', '', '', '', '');
if (!empty($bookings)) {
    foreach ($bookings as $booking) {
        if (in_array($booking['mange_id'], $first_driver) == false && !empty($booking['car_id'])) {
            $first_driver[] = $booking['mange_id'];
            $cars[$booking['mange_id']] = array(
                'car' => !empty($booking['car_name']) ? $booking['car_name'] : '',
                'license' => !empty($booking['license']) ? $booking['license'] : '',
                'driver' => !empty($booking['driver_name']) ? $booking['driver_name'] : '',
                'telephone' => !empty($booking['manage_telephone']) ? $booking['manage_telephone'] : '',
                'adult' => 0,
                'child' => 0,
                'infant' => 0,
                'foc' => 0,
            );
        }

        if (in_array($booking['bpr_id'], $first_bpr) == false) {
            $first_bpr[] = $booking['bpr_id'];
            if (isset($cars[$booking['mange_id']])) {
                $cars[$booking['mange_id']]['adult'] += (int) $booking['adult'];
                $cars[$booking['mange_id']]['child'] += (int) $booking['child'];
                $cars[$booking['mange_id']]['infant'] += (int) $booking['infant'];
                $cars[$booking['mange_id']]['foc'] += (int) $booking['foc'];
            }
        }
    }
}

$sum = array('adult' => 0, 'child' => 0, 'infant' => 0, 'foc' => 0);
?>
<div class="order-driver__print-car">
    <div class="text-center mb-1">
        <h3 class="font-weight-bolder mb-0">ใบงานรถ</h3>
        <h4 class="mb-0"><?php echo date('j F Y', strtotime($date_travel)); ?></h4>
    </div>

    <table class="tableprint">
        <thead>
            <tr>
                <th class="text-center" width="5%">#</th>
                <th width="18%">รถ</th>
                <th width="14%">ป้ายทะเบียน</th>
                <th width="20%">คนขับ</th>
                <th width="13%">เบอร์โทรศัพท์</th>
                <th class="text-center" width="6%">A</th>
                <th class="text-center" width="6%">C</th>
                <th class="text-center" width="6%">Inf</th>
                <th class="text-center" width="6%">FOC</th>
                <th class="text-center" width="6%">รวม</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($cars)) {
                $no = 0;
                foreach ($cars as $row) {
                    $no++;
                    $row_total = $row['adult'] + $row['child'] + $row['infant'] + $row['foc'];
                    $sum['adult'] += $row['adult'];
                    $sum['child'] += $row['child'];
                    $sum['infant'] += $row['infant'];
                    $sum['foc'] += $row['foc'];
            ?>
                    <tr>
                        <td class="text-center"><?php echo $no; ?></td>
                        <td><?php echo htmlspecialchars($row['car'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($row['license'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($row['driver'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($row['telephone'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td class="text-center"><?php echo $row['adult']; ?></td>
                        <td class="text-center"><?php echo $row['child']; ?></td>
                        <td class="text-center"><?php echo $row['infant']; ?></td>
                        <td class="text-center"><?php echo $row['foc']; ?></td>
                        <td class="text-center font-weight-bolder"><?php echo $row_total; ?></td>
                    </tr>
                <?php } ?>
                <tr class="font-weight-bolder">
                    <td colspan="5" class="text-right">รวม</td>
                    <td class="text-center"><?php echo $sum['adult']; ?></td>
                    <td class="text-center"><?php echo $sum['child']; ?></td>
                    <td class="text-center"><?php echo $sum['infant']; ?></td>
                    <td class="text-center"><?php echo $sum['foc']; ?></td>
                    <td class="text-center"><?php echo array_sum($sum); ?></td>
                </tr>
            <?php } else { ?>
                <tr>
                    <td colspan="10" class="text-center">ไม่มีข้อมูลรถในวันนี้</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
