<?php
require_once '../../../config/env.php';
require_once '../../../controllers/Order.php';

$manageObj = new Order();
$times = date("H:i:s");

if (isset($_POST['action']) && $_POST['action'] == "search" && !empty($_POST['type']) && !empty($_POST['date'])) {

    $all_manages = $manageObj->fetch_all_manageboat($_POST['date'], 'all', 0);
?>
    <div class="text-center">
        <h5 class="card-title text-danger"><?php echo date('j F Y', strtotime($_POST['date'])); ?></h5>
    </div>
    <div class="card-body p-0">
        <?php if (!empty($all_manages)) {
            foreach ($all_manages as $key => $manages) {
                $all_bookings = $manageObj->fetch_all_bookingboat('manage', $_POST['date'], 'all', 'all', 'all', '', '', '', '', $manages['id']);
                if (!empty($all_bookings)) {
                    $tourist = 0;
                    $adult = 0;
                    $child = 0;
                    $infant = 0;
                    $foc = 0;
                    $book_array = array();
                    foreach ($all_bookings as $bookings) {
                        if (in_array($bookings['id'], $book_array) == false) {
                            $book_array[] = $bookings['id'];
                            $count_bo[$key][] = 1;
                            $adult += !empty($bookings['adult']) ? $bookings['adult'] : 0;
                            $child += !empty($bookings['child']) ? $bookings['child'] : 0;
                            $infant += !empty($bookings['infant']) ? $bookings['infant'] : 0;
                            $foc += !empty($bookings['foc']) ? $bookings['foc'] : 0;
                            $tourist += $bookings['adult'] + $bookings['child'] + $bookings['infant'] + $bookings['foc'];
                        }
                    }
                }
        ?>
                <div class="row text-center mx-0">
                    <div class="col-4 border-top border-right text-left py-50 pb-1">
                        <a data-manage="<?php echo $manages['id']; ?>" onclick="trigger_search(this);">
                            <h5 class="card-text text-warning mb-0"><?php echo $manages['guide_name']; ?></h5>
                            <h4 class="font-weight-bolder mb-0" style="color: <?php echo $manages['color_hex']; ?>;">
                                <?php echo $manages['boat_name']; ?>
                            </h4>
                        </a>
                    </div>
                    <div class="col-2 border-top border-right py-50 pb-1">
                        <small class="card-text text-muted mb-0">Booking</small>
                        <h4 class="font-weight-bolder mb-0"><?php echo !empty($count_bo[$key]) ? count($count_bo[$key]) : 0; ?></h4>
                    </div>
                    <div class="col-2 border-top border-right py-50 pb-1">
                        <small class="card-text text-muted mb-0">Total</small>
                        <h4 class="font-weight-bolder mb-0"><?php echo !empty($tourist) ? $tourist : 0; ?></h4>
                    </div>
                    <div class="col-1 border-top border-right py-50 pb-1">
                        <small class="card-text text-muted mb-0">AD</small>
                        <h4 class="font-weight-bolder mb-0"><?php echo !empty($adult) ? $adult : 0; ?></h4>
                    </div>
                    <div class="col-1 border-top border-right py-50 pb-1">
                        <small class="card-text text-muted mb-0">CHD</small>
                        <h4 class="font-weight-bolder mb-0"><?php echo !empty($child) ? $child : 0; ?></h4>
                    </div>
                    <div class="col-1 border-top border-right py-50 pb-1">
                        <small class="card-text text-muted mb-0">INF</small>
                        <h4 class="font-weight-bolder mb-0"><?php echo !empty($infant) ? $infant : 0; ?></h4>
                    </div>
                    <div class="col-1 border-top py-50 pb-1">
                        <small class="card-text text-muted mb-0">FOC</small>
                        <h4 class="font-weight-bolder mb-0"><?php echo !empty($foc) ? $foc : 0; ?></h4>
                    </div>
                </div>
        <?php }
        } ?>
    </div>
<?php
} else {
    echo false;
}
