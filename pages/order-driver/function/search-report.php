<?php

use Mpdf\Tag\Em;

require_once '../../../config/env.php';
require_once '../../../controllers/Order.php';

$manageObj = new Order();
$times = date("H:i:s");

if (isset($_POST['action']) && $_POST['action'] == "search" && !empty($_POST['type']) && !empty($_POST['date'])) {

    $first_zone = array();
    $first_book = array();
    $reports = $manageObj->showlistreport($_POST['date']);
    if (!empty($reports)) {
        foreach ($reports as $report) {
            # --- get value zone --- #
            if (in_array($report['pickup_id'], $first_zone) == false) {
                $first_zone[] = $report['pickup_id'];
                $mange_zone[$report['province_id']]['id'][] = !empty($report['pickup_id']) ? $report['pickup_id'] : 0;
                $mange_zone[$report['province_id']]['name'][] = !empty($report['pickup_name']) ? $report['pickup_name'] : '';
            }
            # --- get value booking --- #
            if (in_array($report['id'], $first_book) == false) {
                $first_book[] = $report['id'];
                $mange_trans[$report['pickup_id']]['total'][] = $report['adult'] + $report['child'] + $report['infant'] + $report['foc'];
            }
        }
    }

?>
    <div class="text-center">
        <h5 class="card-title text-danger"><?php echo date('j F Y', strtotime($_POST['date'])); ?></h5>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-sm">
            <thead class="table-primary">
                <tr>
                    <th class="text-center" width="34%" colspan="2">Phuket</th>
                    <th class="text-center" width="33%" colspan="2">Khaolak</th>
                    <th class="text-center" width="33%" colspan="2">Karbi</th>
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
<?php
} else {
    echo false;
}
