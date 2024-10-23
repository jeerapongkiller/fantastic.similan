<?php

use Mpdf\Tag\Em;

require_once 'controllers/Report.php';

$repObj = new Report();
$times = date("H:i:s");
$lastmonth_frist = date('Y-m-d', strtotime('first day of -1 month'));
$lastmonth_last = date('Y-m-d', strtotime('last day of -1 month'));
$lastweek_monday = date("Y-m-d", strtotime("last monday", strtotime("-7 days")));
$lastweek_sunday = date("Y-m-d", strtotime("first sunday", strtotime("-7 days")));
$yesterday = date("Y-m-d", strtotime(" -1 day"));
$today = date("Y-m-d");
$tomorrow = date("Y-m-d", strtotime(" +1 day"));
$nextweek_monday = date("Y-m-d", strtotime("last monday", strtotime("+7 days")));
$nextweek_sunday = date("Y-m-d", strtotime("first sunday", strtotime("+7 days")));
$nextmonth_frist = date('Y-m-d', strtotime('first day of +1 month'));
$nextmonth_last = date('Y-m-d', strtotime('last day of +1 month'));
// $today = '2024-09-29';
// $tomorrow = '2024-09-30';
?>

<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
        </div>

        <div class="content-body">
            <!-- Basic tabs start -->
            <section id="basic-tabs-components">
                <div class="row match-height">
                    <!-- Basic Tabs starts -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="last-month-tab" data-toggle="tab" href="#last-month" aria-controls="last-month" role="tab" aria-selected="true">Last Month</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="last-week-tab" data-toggle="tab" href="#last-week" aria-controls="last-week" role="tab" aria-selected="true">Last Week</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="yesterday-tab" data-toggle="tab" href="#yesterday" aria-controls="yesterday" role="tab" aria-selected="true">Yesterday</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="today-tab" data-toggle="tab" href="#today" aria-controls="today" role="tab" aria-selected="true">Today</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="tomorrow-tab" data-toggle="tab" href="#tomorrow" aria-controls="tomorrow" role="tab" aria-selected="false">Tomorrow</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="next-week-tab" data-toggle="tab" href="#next-week" aria-controls="next-week" role="tab" aria-selected="true">Next Week</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="next-month-tab" data-toggle="tab" href="#next-month" aria-controls="next-month" role="tab" aria-selected="false">Next Month</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="custom-tab" data-toggle="tab" href="#custom" aria-controls="custom" role="tab" aria-selected="false">Custom</a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="last-month" aria-labelledby="last-month-tab" role="tabpanel">
                                        <h2><?php echo $lastmonth_frist . ' - ' . $lastmonth_last; ?></h2>
                                    </div>
                                    <div class="tab-pane" id="last-week" aria-labelledby="last-week-tab" role="tabpanel">
                                        <h2><?php echo $lastweek_monday . ' - ' . $lastweek_sunday; ?></h2>
                                    </div>
                                    <div class="tab-pane" id="yesterday" aria-labelledby="yesterday-tab" role="tabpanel">
                                        <h2><?php echo $yesterday; ?></h2>
                                    </div>
                                    <div class="tab-pane" id="today" aria-labelledby="today-tab" role="tabpanel">
                                        <h2><?php echo $today; ?></h2>
                                    </div>
                                    <div class="tab-pane" id="tomorrow" aria-labelledby="tomorrow-tab" role="tabpanel">
                                        <h2><?php echo $tomorrow; ?></h2>
                                    </div>
                                    <div class="tab-pane" id="next-week" aria-labelledby="next-week-tab" role="tabpanel">
                                        <h2><?php echo $nextweek_monday . ' - ' . $nextweek_sunday; ?></h2>
                                    </div>
                                    <div class="tab-pane" id="next-month" aria-labelledby="next-month-tab" role="tabpanel">
                                        <h2><?php echo $nextmonth_frist . ' - ' . $nextmonth_last; ?></h2>
                                    </div>
                                    <div class="tab-pane" id="custom" aria-labelledby="custom-tab" role="tabpanel">
                                        <form id="invoice-search-form" name="invoice-search-form" method="get" enctype="multipart/form-data">
                                            <div class="d-flex align-items-center mx-50 row pt-0 pb-0">
                                                <div class="col-md-3 col-12">
                                                    <div class="form-group">
                                                        <label class="form-label" for="travel_date">วันที่เที่ยว (Travel Date)</label></br>
                                                        <input type="text" class="form-control flatpickr-range" id="travel_date" name="travel_date" value="<?php echo $today; ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-12">
                                                    <button type="submit" class="btn btn-primary">Search</button>
                                                </div>
                                            </div>
                                        </form>

                                        <div id="div-invoice-custom">

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Basic Tabs ends -->
                </div>
            </section>
            <!-- Basic Tabs end -->
        </div>

    </div>
</div>