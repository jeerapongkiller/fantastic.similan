<?php
require_once '../../../config/env.php';
require_once '../../../controllers/Order.php';

$manageObj = new Order();
$times = date("H:i:s");

if (isset($_POST['action']) && $_POST['action'] == "search" && !empty($_POST['type']) && !empty($_POST['date'])) {

    $manages_arr = array();
    $bomange_arr = array();
    $bpr_arr = array();
    $bomange_booking = array();
    $all_bookings = $manageObj->fetch_all_bookingboat('all', $_POST['date'], 'all', 'all', 'all', '', '', '', '', 'all', 'all', 0);
    foreach ($all_bookings as $categorys) {
        if (in_array($categorys['manage_id'], $manages_arr) == false && !empty($categorys['manage_id'])) {
            $manages_arr[] = $categorys['manage_id'];
            $manage_id[] = $categorys['manage_id'];
            $boat_name[] = $categorys['boat_name'];
            $guide_name[] = $categorys['guide_name'];
            $color_hex[] = $categorys['color_hex'];
        }

        if (in_array($categorys['bpr_id'], $bpr_arr) == false) {
            $bpr_arr[] = $categorys['bpr_id'];
            $adult[$categorys['manage_id']][] = $categorys['adult'];
            $child[$categorys['manage_id']][] = $categorys['child'];
            $infant[$categorys['manage_id']][] = $categorys['infant'];
            $foc[$categorys['manage_id']][] = $categorys['foc'];
            $tourist[$categorys['manage_id']][] = $categorys['adult'] + $categorys['child'] + $categorys['infant'] + $categorys['foc'];
            if (!empty($categorys['check_in'])) {
                $check_total[$categorys['manage_id']][] = $categorys['adult'] + $categorys['child'] + $categorys['infant'] + $categorys['foc'];
            }
        }

        if (in_array($categorys['bomange_id'], $bomange_arr) == false) {
            $bomange_arr[] = $categorys['bomange_id'];
            // $bo_id[$categorys['manage_id']][] = $categorys['id'];
            if (!empty($categorys['check_in'])) {
                $check_in[$categorys['manage_id']][] = $categorys['check_in'];
            }
        }

        if (in_array($categorys['id'], $bomange_booking) == false) {
            $bomange_booking[] = $categorys['id'];
            $bo_id[$categorys['manage_id']][] = $categorys['id'];
        }
    }

    $all_manages = $manageObj->fetch_all_manageboat($_POST['date'], 'all', 'all', 0);
?>
    <div class="text-center">
        <h5 class="card-title text-danger"><?php echo date('j F Y', strtotime($_POST['date'])); ?></h5>
    </div>
    <div class="card-body p-0">
        <?php if (!empty($manage_id)) {
            for ($i = 0; $i < count($manage_id); $i++) {
        ?>
                <div class="row text-center mx-0">
                    <div class="col-4 border-top border-right text-left py-50 pb-1">
                        <a data-manage="<?php echo $manage_id[$i]; ?>" onclick="trigger_search(this);">
                            <h5 class="card-text text-warning mb-0"><?php echo $guide_name[$i]; ?></h5>
                            <h4 class="font-weight-bolder mb-0" style="color: <?php echo $color_hex[$i]; ?>;">
                                <?php echo $boat_name[$i]; ?>
                            </h4>
                        </a>
                    </div>
                    <div class="col-2 border-top border-right py-50 pb-1">
                        <small class="card-text text-muted mb-0">Booking</small>
                        <h4 class="font-weight-bolder text-success mb-0">
                            <?php echo !empty($check_in[$manage_id[$i]]) ? count($check_in[$manage_id[$i]]) : 0; ?>/<?php echo !empty($bo_id[$manage_id[$i]]) ? count($bo_id[$manage_id[$i]]) : 0; ?>
                        </h4>
                    </div>
                    <div class="col-2 border-top border-right py-50 pb-1">
                        <small class="card-text text-muted mb-0">Total</small>
                        <h4 class="font-weight-bolder text-warning mb-0">
                            <?php echo !empty($check_total[$manage_id[$i]]) ? array_sum($check_total[$manage_id[$i]]) : 0; ?>/<?php echo !empty($tourist[$manage_id[$i]]) ? array_sum($tourist[$manage_id[$i]]) : 0; ?>
                        </h4>
                    </div>
                    <div class="col-1 border-top border-right py-50 pb-1">
                        <small class="card-text text-muted mb-0">AD</small>
                        <h4 class="font-weight-bolder mb-0"><?php echo !empty($adult[$manage_id[$i]]) ? array_sum($adult[$manage_id[$i]]) : 0; ?></h4>
                    </div>
                    <div class="col-1 border-top border-right py-50 pb-1">
                        <small class="card-text text-muted mb-0">CHD</small>
                        <h4 class="font-weight-bolder mb-0"><?php echo !empty($child[$manage_id[$i]]) ? array_sum($child[$manage_id[$i]]) : 0; ?></h4>
                    </div>
                    <div class="col-1 border-top border-right py-50 pb-1">
                        <small class="card-text text-muted mb-0">INF</small>
                        <h4 class="font-weight-bolder mb-0"><?php echo !empty($infant[$manage_id[$i]]) ? array_sum($infant[$manage_id[$i]]) : 0; ?></h4>
                    </div>
                    <div class="col-1 border-top py-50 pb-1">
                        <small class="card-text text-muted mb-0">FOC</small>
                        <h4 class="font-weight-bolder mb-0"><?php echo !empty($foc[$manage_id[$i]]) ? array_sum($foc[$manage_id[$i]]) : 0; ?></h4>
                    </div>
                </div>
        <?php }
        } ?>
    </div>
<?php
} else {
    echo false;
}
