<?php
require_once '../../../config/env.php';
require_once '../../../controllers/Order.php';

$manageObj = new Order();
$times = date("H:i:s");

if (isset($_POST['action']) && $_POST['action'] == "search" && !empty($_POST['type']) && !empty($_POST['date'])) {

    $first_driver = array();
    $first_book = array();
    $bookings = $manageObj->showlisttransfers('all', 1, $_POST['date'], 'all', 'all', 'all', 'all', 'all', '', '', '');
    if (!empty($bookings)) {
        foreach ($bookings as $booking) {
            # --- get value booking --- #
            if (in_array($booking['mange_id'], $first_driver) == false && !empty($booking['car_id'])) {
                $first_driver[] = $booking['mange_id'];
                $mange_trans['id'][] = !empty($booking['mange_id']) ? $booking['mange_id'] : 0;
                $mange_trans['name'][] = !empty($booking['car_name']) ? $booking['car_name'] : '';
                $mange_trans['license'][] = !empty($booking['license']) ? $booking['license'] : '';
                $mange_trans['seat'][] = !empty($booking['seat']) ? $booking['seat'] : '-';
            }
            # --- get value booking --- #
            if (in_array($booking['id'], $first_book) == false) {
                $first_book[] = $booking['id'];
                $bo_id[] = !empty($booking['id']) ? $booking['id'] : 0;
                $adult[] = !empty($booking['bp_adult']) ? $booking['bp_adult'] : 0;
                $child[] = !empty($booking['bp_child']) ? $booking['bp_child'] : 0;
                $infant[] = !empty($booking['bp_infant']) ? $booking['bp_infant'] : 0;
                $foc[] = !empty($booking['bp_foc']) ? $booking['bp_foc'] : 0;
                $total[] = $booking['bp_adult'] + $booking['bp_child'] + $booking['bp_infant'] + $booking['bp_foc'];

                $mange_trans[$booking['mange_id']]['bo_id'][] = !empty($booking['id']) ? $booking['id'] : 0;
                $mange_trans[$booking['mange_id']]['adult'][] = !empty($booking['bp_adult']) ? $booking['bp_adult'] : 0;
                $mange_trans[$booking['mange_id']]['child'][] = !empty($booking['bp_child']) ? $booking['bp_child'] : 0;
                $mange_trans[$booking['mange_id']]['infant'][] = !empty($booking['bp_infant']) ? $booking['bp_infant'] : 0;
                $mange_trans[$booking['mange_id']]['foc'][] = !empty($booking['bp_foc']) ? $booking['bp_foc'] : 0;
                $mange_trans[$booking['mange_id']]['total'][] = $booking['bp_adult'] + $booking['bp_child'] + $booking['bp_infant'] + $booking['bp_foc'];
            }
        }
    }
?>
    <div class="text-center">
        <h5 class="card-title text-danger"><?php echo date('j F Y', strtotime($_POST['date'])); ?></h5>
    </div>
    <div class="card-body p-0">
        <?php if (!empty($mange_trans['id'])) {
            for ($i = 0; $i < count($mange_trans['id']); $i++) {
                $id = $mange_trans['id'][$i]; ?>
                <div class="row text-center mx-0">
                    <div class="col-4 border-top border-right text-left py-50 pb-1">
                        <h5 class="card-text text-warning mb-0"><?php echo $mange_trans['license'][$i]; ?></h5>
                        <h4 class="font-weight-bolder text-danger mb-0">
                            <?php echo $mange_trans['name'][$i]; ?>
                        </h4>
                    </div>
                    <div class="col-2 border-top border-right py-50 pb-1">
                        <small class="card-text text-muted mb-0">Booking</small>
                        <h4 class="font-weight-bolder mb-0"><?php echo !empty($mange_trans[$id]['bo_id']) ? count($mange_trans[$id]['bo_id']) : 0; ?></h4>
                    </div>
                    <div class="col-2 border-top border-right py-50 pb-1">
                        <small class="card-text text-muted mb-0">Total</small>
                        <h4 class="font-weight-bolder mb-0"><?php echo !empty($mange_trans[$id]['adult']) ? array_sum($mange_trans[$id]['adult']) : 0; ?></h4>
                    </div>
                    <div class="col-1 border-top border-right py-50 pb-1">
                        <small class="card-text text-muted mb-0">AD</small>
                        <h4 class="font-weight-bolder mb-0"><?php echo !empty($mange_trans[$id]['total']) ? array_sum($mange_trans[$id]['total']) : 0; ?></h4>
                    </div>
                    <div class="col-1 border-top border-right py-50 pb-1">
                        <small class="card-text text-muted mb-0">CHD</small>
                        <h4 class="font-weight-bolder mb-0"><?php echo !empty($mange_trans[$id]['child']) ? array_sum($mange_trans[$id]['child']) : 0; ?></h4>
                    </div>
                    <div class="col-1 border-top border-right py-50 pb-1">
                        <small class="card-text text-muted mb-0">INF</small>
                        <h4 class="font-weight-bolder mb-0"><?php echo !empty($mange_trans[$id]['infant']) ? array_sum($mange_trans[$id]['infant']) : 0; ?></h4>
                    </div>
                    <div class="col-1 border-top py-50 pb-1">
                        <small class="card-text text-muted mb-0">FOC</small>
                        <h4 class="font-weight-bolder mb-0"><?php echo !empty($mange_trans[$id]['foc']) ? array_sum($mange_trans[$id]['foc']) : 0; ?></h4>
                    </div>
                </div>
        <?php }
        } ?>
    </div>
<?php
} else {
    echo false;
}
