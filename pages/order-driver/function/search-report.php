<?php

use Mpdf\Tag\Em;

require_once '../../../config/env.php';
require_once '../../../controllers/Order.php';

$manageObj = new Order();
$times = date("H:i:s");

if (isset($_POST['action']) && $_POST['action'] == "search" && !empty($_POST['type']) && !empty($_POST['date'])) {

    $first_zone = array();
    $first_driver = array();
    $first_book = array();
    $first_bpr = array();
    $bookings = $manageObj->showlisttransfers('report', 1, $_POST['date'], 'all', 'all', 'all', 'all', 'all', '', '', '', '');
    if (!empty($bookings)) {
        foreach ($bookings as $booking) {
            # --- get value booking --- #
            if (in_array($booking['zonep_id'], $first_zone) == false) {
                $first_zone[] = $booking['zonep_id'];
                $mange_zone[$booking['province_id']]['id'][] = !empty($booking['zonep_id']) ? $booking['zonep_id'] : 0;
                $mange_zone[$booking['province_id']]['name'][] = !empty($booking['zonep_name']) ? $booking['zonep_name'] : '';
            }
            # --- get value booking --- #
            if (in_array($booking['mange_id'], $first_driver) == false && !empty($booking['car_id'])) {
                $first_driver[] = $booking['mange_id'];
                $mange_trans['id'][] = !empty($booking['mange_id']) ? $booking['mange_id'] : 0;
                $car[] = $booking['car_name'];
                $driver[] = !empty($booking['driver_name']) ? $booking['driver_name'] : '';
                $telephone[] = !empty($booking['manage_telephone']) ? $booking['manage_telephone'] : '';
            }
            # --- get value booking --- #
            if (in_array($booking['id'], $first_book) == false) {
                $first_book[] = $booking['id'];
                $bo_id[] = !empty($booking['id']) ? $booking['id'] : 0;

                $mange_trans[$booking['mange_id']]['bo_id'][] = !empty($booking['id']) ? $booking['id'] : 0;
            }

            if (in_array($booking['bpr_id'], $first_bpr) == false) {
                $first_bpr[] = $booking['bpr_id'];
                $category_name[$booking['id']][] = !empty($booking['category_name']) ? $booking['category_name'] : '';
                $adult[$booking['id']][] = !empty($booking['adult']) ? $booking['adult'] : 0;
                $child[$booking['id']][] = !empty($booking['child']) ? $booking['child'] : 0;
                $infant[$booking['id']][] = !empty($booking['infant']) ? $booking['infant'] : 0;
                $foc[$booking['id']][] = !empty($booking['foc']) ? $booking['foc'] : 0;
                $total[$booking['id']][] = $booking['adult'] + $booking['child'] + $booking['infant'] + $booking['foc'];

                $mange_trans[$booking['mange_id']]['adult'][] = !empty($booking['adult']) ? $booking['adult'] : 0;
                $mange_trans[$booking['mange_id']]['child'][] = !empty($booking['child']) ? $booking['child'] : 0;
                $mange_trans[$booking['mange_id']]['infant'][] = !empty($booking['infant']) ? $booking['infant'] : 0;
                $mange_trans[$booking['mange_id']]['foc'][] = !empty($booking['foc']) ? $booking['foc'] : 0;
                $mange_trans[$booking['mange_id']]['total'][] = $booking['adult'] + $booking['child'] + $booking['infant'] + $booking['foc'];

                $tourist_manage[$booking['mange_id']][] = $booking['adult'] + $booking['child'] + $booking['infant'] + $booking['foc'];
                $mange_trans[$booking['zonep_id']]['total'][] = $booking['adult'] + $booking['child'] + $booking['infant'] + $booking['foc'];
            }
        }
    }

?>
    <div class="text-center">
        <h5 class="card-title text-danger"><?php echo date('j F Y', strtotime($_POST['date'])); ?></h5>
    </div>

    <div class="row">

        <div class="col-md-8 col-sm-12">
            <div class="table-responsive">
                <table class="table table-striped table-sm">
                    <thead class="table-primary">
                        <tr>
                            <th class="text-center" width="34%" colspan="2">Phuket</th>
                            <th class="text-center" width="33%" colspan="2">Khaolak</th>
                            <th class="text-center" width="33%" colspan="2">Krabi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($mange_zone[66]['name']) || isset($mange_zone[65]['name']) || isset($mange_zone[64]['name'])) {
                            $count = max(
                                isset($mange_zone[66]['name']) ? count($mange_zone[66]['name']) : 0,
                                isset($mange_zone[65]['name']) ? count($mange_zone[65]['name']) : 0,
                                isset($mange_zone[64]['name']) ? count($mange_zone[64]['name']) : 0
                            );

                            for ($i = 0; $i < $count; $i++) {
                                $zone[66][$i] = (!empty($mange_zone[66]['id'][$i]) && !empty($mange_trans[$mange_zone[66]['id'][$i]]['total'])) ? array_sum($mange_trans[$mange_zone[66]['id'][$i]]['total']) : 0;
                                $zone[65][$i] = (!empty($mange_zone[65]['id'][$i]) && !empty($mange_trans[$mange_zone[65]['id'][$i]]['total'])) ? array_sum($mange_trans[$mange_zone[65]['id'][$i]]['total']) : 0;
                                $zone[64][$i] = (!empty($mange_zone[64]['id'][$i]) && !empty($mange_trans[$mange_zone[64]['id'][$i]]['total'])) ? array_sum($mange_trans[$mange_zone[64]['id'][$i]]['total']) : 0;
                        ?>
                                <tr>
                                    <td><?php echo !empty($mange_zone[66]['name'][$i]) ? $mange_zone[66]['name'][$i] : ''; ?></td>
                                    <td><?php echo ($zone[66][$i]) ? $zone[66][$i] : ''; ?></td>
                                    <td><?php echo !empty($mange_zone[65]['name'][$i]) ? $mange_zone[65]['name'][$i] : ''; ?></td>
                                    <td><?php echo ($zone[65][$i]) ? $zone[65][$i] : ''; ?></td>
                                    <td><?php echo !empty($mange_zone[64]['name'][$i]) ? $mange_zone[64]['name'][$i] : ''; ?></td>
                                    <td><?php echo ($zone[64][$i]) ? $zone[64][$i] : ''; ?></td>
                                </tr>
                            <?php } ?>
                            <tr class="font-weight-bolder text-danger">
                                <td><?php echo !empty($zone[66]) ? array_sum($zone[66]) > 0 ? 'รวม' : '' : ''; ?></td>
                                <td><?php echo !empty($zone[66]) ? array_sum($zone[66]) > 0 ? array_sum($zone[66]) : '' : ''; ?></td>
                                <td><?php echo !empty($zone[65]) ? array_sum($zone[65]) > 0 ? 'รวม' : '' : ''; ?></td>
                                <td><?php echo !empty($zone[65]) ? array_sum($zone[65]) > 0 ? array_sum($zone[65]) : '' : ''; ?></td>
                                <td><?php echo !empty($zone[64]) ? array_sum($zone[64]) > 0 ? 'รวม' : '' : ''; ?></td>
                                <td><?php echo !empty($zone[64]) ? array_sum($zone[64]) > 0 ? array_sum($zone[64]) : '' : ''; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-md-4 col-sm-12">
            <div class="table-responsive">
                <table class="table table-striped table-sm">
                    <thead class="table-primary">
                        <tr>
                            <th class="text-center"></th>
                            <th class="text-center">จำนวนคนทั้งหมด</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php for ($i = 0; $i < count($first_driver); $i++) { ?>
                            <tr>
                                <td>
                                    <?php
                                    echo $first_driver[$i];
                                    echo !empty($car[$i]) ? '<span class="font-weight-bolder text-primary">' . $car[$i] . ',</span> ' : ' ';
                                    echo !empty($driver[$i]) ? '<span class="font-weight-bolder text-info">' . $driver[$i] . '</span>' : ' ';
                                    echo !empty($telephone[$i]) ? '<span class="font-weight-bolder text-info">, ' . $telephone[$i] . '</span>' : ' ';
                                    ?>
                                </td>
                                <td class="text-center"><?php echo array_sum($tourist_manage[$first_driver[$i]]); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


<?php
} else {
    echo false;
}
