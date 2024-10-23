<?php
require_once '../../../config/env.php';
require_once '../../../controllers/Order.php';

$manageObj = new Order();
$times = date("H:i:s");

if (isset($_POST['action']) && $_POST['action'] == "search" && !empty($_POST['type']) && !empty($_POST['date'])) {
    
    $first_boat = array();
    $first_book = array();
    $bookings = $manageObj->showlistboats('list', 0, $_POST['date'], 'all', 'all', 'all', 'all', 'all', '', '', '');
    if (!empty($bookings)) {
        foreach ($bookings as $booking) {
            # --- get value booking --- #
            if (in_array($booking['mange_id'], $first_boat) == false && !empty($booking['boat_id'])) {
                $first_boat[] = $booking['mange_id'];
                $mange_boat['id'][] = !empty($booking['mange_id']) ? $booking['mange_id'] : 0;
                $mange_boat['boat'][] = !empty($booking['boat_id']) ? $booking['boat_id'] : 0;
                $mange_boat['name'][] = !empty($booking['boat_name']) ? $booking['boat_name'] : '';
                $mange_boat['guide'][] = !empty($booking['guide_name']) ? $booking['guide_name'] : '';
                $mange_boat['color_hex'][] = !empty($booking['color_hex']) ? $booking['color_hex'] : '';
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

                $mange_boat[$booking['mange_id']]['bo_id'][] = !empty($booking['id']) ? $booking['id'] : 0;
                $mange_boat[$booking['mange_id']]['adult'][] = !empty($booking['bp_adult']) ? $booking['bp_adult'] : 0;
                $mange_boat[$booking['mange_id']]['child'][] = !empty($booking['bp_child']) ? $booking['bp_child'] : 0;
                $mange_boat[$booking['mange_id']]['infant'][] = !empty($booking['bp_infant']) ? $booking['bp_infant'] : 0;
                $mange_boat[$booking['mange_id']]['foc'][] = !empty($booking['bp_foc']) ? $booking['bp_foc'] : 0;
                $mange_boat[$booking['mange_id']]['total'][] = $booking['bp_adult'] + $booking['bp_child'] + $booking['bp_infant'] + $booking['bp_foc'];

                $mange_product[$booking['product_id']]['bo_id'][] = !empty($booking['id']) ? $booking['id'] : 0;
            }
        }
    }
?>
    <div class="text-center">
        <h5 class="card-title text-danger"><?php echo date('j F Y', strtotime($_POST['date'])); ?></h5>
    </div>
    <div class="card-body p-0">
        <?php if (!empty($mange_boat['id'])) {
            for ($i = 0; $i < count($mange_boat['id']); $i++) {
                $id = $mange_boat['id'][$i]; ?>
                <div class="row text-center mx-0">
                    <div class="col-4 border-top border-right text-left py-50 pb-1">
                        <h5 class="card-text text-warning mb-0"><?php echo $mange_boat['guide'][$i]; ?></h5>
                        <h4 class="font-weight-bolder mb-0" style="color: <?php echo $mange_boat['color_hex'][$i]; ?>;">
                            <?php echo $mange_boat['name'][$i]; ?>
                        </h4>
                    </div>
                    <div class="col-2 border-top border-right py-50 pb-1">
                        <small class="card-text text-muted mb-0">Booking</small>
                        <h4 class="font-weight-bolder mb-0"><?php echo !empty($mange_boat[$id]['bo_id']) ? count($mange_boat[$id]['bo_id']) : 0; ?></h4>
                    </div>
                    <div class="col-2 border-top border-right py-50 pb-1">
                        <small class="card-text text-muted mb-0">Total</small>
                        <h4 class="font-weight-bolder mb-0"><?php echo !empty($mange_boat[$id]['adult']) ? array_sum($mange_boat[$id]['adult']) : 0; ?></h4>
                    </div>
                    <div class="col-1 border-top border-right py-50 pb-1">
                        <small class="card-text text-muted mb-0">AD</small>
                        <h4 class="font-weight-bolder mb-0"><?php echo !empty($mange_boat[$id]['total']) ? array_sum($mange_boat[$id]['total']) : 0; ?></h4>
                    </div>
                    <div class="col-1 border-top border-right py-50 pb-1">
                        <small class="card-text text-muted mb-0">CHD</small>
                        <h4 class="font-weight-bolder mb-0"><?php echo !empty($mange_boat[$id]['child']) ? array_sum($mange_boat[$id]['child']) : 0; ?></h4>
                    </div>
                    <div class="col-1 border-top border-right py-50 pb-1">
                        <small class="card-text text-muted mb-0">INF</small>
                        <h4 class="font-weight-bolder mb-0"><?php echo !empty($mange_boat[$id]['infant']) ? array_sum($mange_boat[$id]['infant']) : 0; ?></h4>
                    </div>
                    <div class="col-1 border-top py-50 pb-1">
                        <small class="card-text text-muted mb-0">FOC</small>
                        <h4 class="font-weight-bolder mb-0"><?php echo !empty($mange_boat[$id]['foc']) ? array_sum($mange_boat[$id]['foc']) : 0; ?></h4>
                    </div>
                </div>
        <?php }
        } ?>
    </div>
<?php
} else {
    echo false;
}
