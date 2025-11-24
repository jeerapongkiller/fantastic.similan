<nav class="header-navbar navbar navbar-expand-lg align-items-center floating-nav navbar-light navbar-shadow">
    <div class="navbar-container d-flex content">
        <div class="bookmark-wrapper d-flex align-items-center">
            <ul class="nav navbar-nav d-xl-none">
                <li class="nav-item"><a class="nav-link menu-toggle" href="javascript:void(0);"><i class="ficon" data-feather="menu"></i></a></li>
            </ul>
        </div>
        <ul class="nav navbar-nav align-items-center ml-auto">
            <li class="nav-item dropdown dropdown-notification mr-25"><a class="nav-link" href="javascript:void(0);" data-toggle="dropdown"><i class="ficon" data-feather="bell"></i><span class="badge badge-pill badge-danger badge-up"><span class="mt-25" id="span-header-main"></span></span></a>
                <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
                    <li class="scrollable-container media-list">
                        <a class="d-flex" href="./?pages=order-boat/manage">
                            <div class="media d-flex align-items-start">
                                <div class="media-left mt-50">
                                    <img src="app-assets/images/yacht.png" alt="avatar" width="50">
                                </div>
                                <div class="media-body">
                                    <h6 class="media-heading font-weight-bolder">Boats</h6><small class="notification-text">มี Booking ที่ยังไม่ได้จัดเรือ</small>
                                </div>
                                <div class="badge badge-pill badge-light-primary mt-50" id="header-div-boats"></div>
                            </div>
                        </a>
                        <a class="d-flex" href="./?pages=order-driver/manage">
                            <div class="media d-flex align-items-start">
                                <div class="media-left">
                                    <img src="app-assets/images/car.png" alt="avatar" width="50">
                                </div>
                                <div class="media-body">
                                    <h6 class="media-heading font-weight-bolder">Transfers</h6><small class="notification-text">มี Booking ที่ยังไม่ได้จัดรถ</small>
                                </div>
                                <div class="badge badge-pill badge-light-primary mt-50" id="header-div-transfers"></div>
                            </div>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item dropdown dropdown-user"><a class="nav-link dropdown-toggle dropdown-user-link" id="dropdown-user" href="javascript:void(0);" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="user-nav d-sm-flex d-none">
                        <span class="user-name font-weight-bolder"><?php echo $_SESSION["supplier"]["fullname"]; ?></span>
                        <span class="user-status"><?php echo $_SESSION["supplier"]["role_name"]; ?></span>
                    </div>
                    <span class="avatar">
                        <img class="round" src="app-assets/images/avatars/user.jpg" alt="avatar" height="40" width="40">
                        <span class="avatar-status-online"></span>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-user">
                    <a class="dropdown-item" href="./?pages=user/edit&id=<?php echo $_SESSION["supplier"]["id"]; ?>"><i class="mr-50" data-feather="user"></i>Profile</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="./"><i class="mr-50" data-feather="power"></i> Logout</a>
                </div>
            </li>
        </ul>
    </div>
</nav>