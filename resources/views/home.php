<!doctype html>
<html lang="en">
 
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?= config('app.public_path') ?>assets/vendor/bootstrap/css/bootstrap.min.css">
    <link href="<?= config('app.public_path') ?>assets/vendor/fonts/circular-std/style.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= config('app.public_path') ?>assets/libs/css/style.css">
    <link rel="stylesheet" href="<?= config('app.public_path') ?>assets/vendor/fonts/fontawesome/css/fontawesome-all.css">
    <link rel="stylesheet" href="<?= config('app.public_path') ?>assets/vendor/charts/chartist-bundle/chartist.css">
    <link rel="stylesheet" href="<?= config('app.public_path') ?>assets/vendor/charts/morris-bundle/morris.css">
    <link rel="stylesheet" href="<?= config('app.public_path') ?>assets/vendor/fonts/material-design-iconic-font/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="<?= config('app.public_path') ?>assets/vendor/charts/c3charts/c3.css">
    <link rel="stylesheet" href="<?= config('app.public_path') ?>assets/vendor/fonts/flag-icon-css/flag-icon.min.css">
    <link rel="stylesheet" href="<?= config('app.public_path') ?>assets/libs/css/skeleton-loader.css">
    <title>Concept - Bootstrap 4 Admin Dashboard Template</title>
</head>

<body>
    <!-- ============================================================== -->
    <!-- main wrapper -->
    <!-- ============================================================== -->
    <div class="dashboard-main-wrapper">
        <!-- ============================================================== -->
        <!-- navbar -->
        <!-- ============================================================== -->
        <div class="dashboard-header">
            <nav class="navbar navbar-expand-lg bg-white fixed-top">
                <a class="navbar-brand" href="index.html">Concept</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse " id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto navbar-right-top">
                        <li class="nav-item">
                            <div id="custom-search" class="top-search-bar">
                                <input class="form-control" type="text" placeholder="Search..">
                            </div>
                        </li>
                        <li class="nav-item dropdown notification">
                            <a class="nav-link nav-icons" href="#" id="navbarDropdownMenuLink1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-fw fa-bell"></i> <span class="indicator"></span></a>
                            <ul class="dropdown-menu dropdown-menu-right notification-dropdown">
                                <li>
                                    <div class="notification-title"> Notification</div>
                                    <div class="notification-list">
                                        <div class="list-group">
                                            <a href="#" class="list-group-item list-group-item-action active">
                                                <div class="notification-info">
                                                    <div class="notification-list-user-img"><img src="<?= config('app.public_path') ?>assets/images/avatar-2.jpg" alt="" class="user-avatar-md rounded-circle"></div>
                                                    <div class="notification-list-user-block"><span class="notification-list-user-name">Jeremy Rakestraw</span>accepted your invitation to join the team.
                                                        <div class="notification-date">2 min ago</div>
                                                    </div>
                                                </div>
                                            </a>
                                            <a href="#" class="list-group-item list-group-item-action">
                                                <div class="notification-info">
                                                    <div class="notification-list-user-img"><img src="<?= config('app.public_path') ?>assets/images/avatar-3.jpg" alt="" class="user-avatar-md rounded-circle"></div>
                                                    <div class="notification-list-user-block"><span class="notification-list-user-name">John Abraham </span>is now following you
                                                        <div class="notification-date">2 days ago</div>
                                                    </div>
                                                </div>
                                            </a>
                                            <a href="#" class="list-group-item list-group-item-action">
                                                <div class="notification-info">
                                                    <div class="notification-list-user-img"><img src="<?= config('app.public_path') ?>assets/images/avatar-4.jpg" alt="" class="user-avatar-md rounded-circle"></div>
                                                    <div class="notification-list-user-block"><span class="notification-list-user-name">Monaan Pechi</span> is watching your main repository
                                                        <div class="notification-date">2 min ago</div>
                                                    </div>
                                                </div>
                                            </a>
                                            <a href="#" class="list-group-item list-group-item-action">
                                                <div class="notification-info">
                                                    <div class="notification-list-user-img"><img src="<?= config('app.public_path') ?>assets/images/avatar-5.jpg" alt="" class="user-avatar-md rounded-circle"></div>
                                                    <div class="notification-list-user-block"><span class="notification-list-user-name">Jessica Caruso</span>accepted your invitation to join the team.
                                                        <div class="notification-date">2 min ago</div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="list-footer"> <a href="#">View all notifications</a></div>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown connection">
                            <a class="nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="fas fa-fw fa-th"></i> </a>
                            <ul class="dropdown-menu dropdown-menu-right connection-dropdown">
                                <li class="connection-list">
                                    <div class="row">
                                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12 ">
                                            <a href="#" class="connection-item"><img src="<?= config('app.public_path') ?>assets/images/github.png" alt="" > <span>Github</span></a>
                                        </div>
                                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12 ">
                                            <a href="#" class="connection-item"><img src="<?= config('app.public_path') ?>assets/images/dribbble.png" alt="" > <span>Dribbble</span></a>
                                        </div>
                                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12 ">
                                            <a href="#" class="connection-item"><img src="<?= config('app.public_path') ?>assets/images/dropbox.png" alt="" > <span>Dropbox</span></a>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12 ">
                                            <a href="#" class="connection-item"><img src="<?= config('app.public_path') ?>assets/images/bitbucket.png" alt=""> <span>Bitbucket</span></a>
                                        </div>
                                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12 ">
                                            <a href="#" class="connection-item"><img src="<?= config('app.public_path') ?>assets/images/mail_chimp.png" alt="" ><span>Mail chimp</span></a>
                                        </div>
                                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12 ">
                                            <a href="#" class="connection-item"><img src="<?= config('app.public_path') ?>assets/images/slack.png" alt="" > <span>Slack</span></a>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="conntection-footer"><a href="#">More</a></div>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown nav-user">
                            <a class="nav-link nav-user-img" href="#" id="navbarDropdownMenuLink2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="<?= config('app.public_path') ?>assets/images/avatar-1.jpg" alt="" class="user-avatar-md rounded-circle"></a>
                            <div class="dropdown-menu dropdown-menu-right nav-user-dropdown" aria-labelledby="navbarDropdownMenuLink2">
                                <div class="nav-user-info">
                                    <h5 class="mb-0 text-white nav-user-name"><?php echo auth_user_name() ?> </h5>
                                    <span class="status"></span><span class="ml-2">Available</span>
                                </div>
                                <a class="dropdown-item" href="#"><i class="fas fa-user mr-2"></i>Account</a>
                                <a class="dropdown-item" href="#"><i class="fas fa-cog mr-2"></i>Setting</a>
                                <a class="dropdown-item" href="<?php echo url('/logout') ?>"><i class="fas fa-power-off mr-2"></i>Logout</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
        <!-- ============================================================== -->
        <!-- end navbar -->
        <!-- ============================================================== -->
        <?php include('partials/sidebar.php') ?>
        <!-- ============================================================== -->
        <!-- end left sidebar -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- wrapper  -->
        <!-- ============================================================== -->
        <div class="dashboard-wrapper">
            <div class="dashboard-ecommerce">
                <div class="container-fluid dashboard-content ">
                    <!-- ============================================================== -->
                    <!-- pageheader  -->
                    <!-- ============================================================== -->
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="page-header">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h2 class="pageheader-title">Welcome, <?= auth()->name; ?> </h2>
                                    <div style="gap: 20px" class="d-flex align-items-center g-3 gap-3 justify-content-center">
                                        <div class="form-group">
                                            <label for="service" class="col-form-label">Choose a Service</label> 
                                            <select class="form-control" id="service" name="service">
                                                <option value="all" selected>All</option>
                                                <?php
                                                $services = App\Models\Game::all();
                                                foreach ($services as $service) { 
                                                    echo '<option value="'.$service->service_id.'">'.$service->name.'</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="campaign-chooser" class="col-form-label">Choose a campaign agency</label> 
                                            <select class="form-control" id="campaign-chooser" name="campaign-chooser">
                                                <option value="all" selected>All</option>
                                                <?php
                                                $campaignAgencies = App\Models\CampaignAgency::all();
                                                foreach ($campaignAgencies as $campaignAgency) { 
                                                    echo '<option value="'.$campaignAgency->code.'">'.$campaignAgency->name.'</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <?= csrf_field() ?>
                                        <div class="form-group">
                                            <label for="campaign-chooser" class="col-form-label">Choose a date range</label> 
                                            <div style="gap: 10px" class="form-group d-flex align-items-end">
                                                <input class="form-control" type="date" data-date="<?php echo $date['initial'] ?>" id="from" name="from" />
                                                <input class="form-control" type="date" data-date="<?php echo $date['final'] ?>" id="to" name="to" />
                                                <button class="btn btn-primary" id="clear-button">Clear</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <p class="pageheader-text">Nulla euismod urna eros, sit amet scelerisque torton lectus vel mauris facilisis faucibus at enim quis massa lobortis rutrum.</p>
                                <div class="page-breadcrumb d-flex justify-content-between">
                                    <nav aria-label="breadcrumb">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Dashboard</a></li>
                                            <!-- <li class="breadcrumb-item active" aria-current="page">E-Commerce Dashboard Template</li> -->
                                        </ol>
                                    </nav>
                                    <div class="loader">
                                        <p class="text-red color-red text-danger text-secondary d-none">Loading...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- ============================================================== -->
                    <!-- end pageheader  -->
                    <!-- ============================================================== -->
                    <div class="ecommerce-widget">

                        <div class="row stats">
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                                <div class="card">
                                    <div class="card-body revenue">
                                        <h5 class="text-muted">Total Revenue</h5>
                                        <div class="metric-value d-inline-block">
                                            <h1 class="mb-1"><?php echo $stats['revenue']['total'] ?></h1>
                                        </div>
                                        <div class="metric-label d-inline-block float-right text-primary font-weight-bold">
                                            <?php echo $stats['revenue']['percentage'] ?>
                                        </div>
                                    </div>
                                    <div id="sparkline-revenue"></div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                                <div class="card">
                                    <div class="card-body subs">
                                        <h5 class="text-muted">Total Subs</h5>
                                        <div class="metric-value d-inline-block">
                                            <h1 class="mb-1"><?php echo $stats['subs']['total'] ?></h1>
                                        </div>
                                        <div class="metric-label d-inline-block float-right text-primary font-weight-bold">
                                            <?php echo $stats['subs']['percentage'] ?>
                                        </div>
                                    </div>
                                    <div id="sparkline-revenue2"></div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                                <div class="card">
                                    <div class="card-body unSubs">
                                        <h5 class="text-muted">Unsubs</h5>
                                        <div class="metric-value d-inline-block">
                                            <h1 class="mb-1"><?php echo $stats['unSubs']['total'] ?></h1>
                                        </div>
                                        <div class="metric-label d-inline-block float-right text-primary font-weight-bold">
                                            <span><?php echo $stats['unSubs']['percentage'] ?></span>
                                        </div>
                                    </div>
                                    <div id="sparkline-revenue3"></div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                                <div class="card">
                                    <div class="card-body subRev">
                                        <h5 class="text-muted">Subs Revenue</h5>
                                        <div class="metric-value d-inline-block">
                                            <h1 class="mb-1"><?php echo $stats['subRev']['total'] ?></h1>
                                        </div>
                                        <div class="metric-label d-inline-block float-right text-secondary font-weight-bold">
                                            <span ><?php echo $stats['subRev']['percentage'] ?></span>
                                        </div>
                                    </div>
                                    <div id="sparkline-revenue4"></div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                                <div class="card">
                                    <div class="card-body renRev">
                                        <h5 class="text-muted">Renew Revenue</h5>
                                        <div class="metric-value d-inline-block">
                                            <h1 class="mb-1"><?php echo $stats['renRev']['total'] ?></h1>
                                        </div>
                                        <div class="metric-label d-inline-block float-right text-secondary font-weight-bold">
                                            <span ><?php echo $stats['renRev']['percentage'] ?></span>
                                        </div>
                                    </div>
                                    <div id="renew-revenue4"></div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                                <div class="card">
                                    <div class="card-body churnRate">
                                        <h5 class="text-muted">Churn Rate</h5>
                                        <div class="metric-value d-inline-block">
                                            <h1 class="mb-1"><?php echo $stats['churnRate']['total'] ?></h1>
                                        </div>
                                        <div class="metric-label d-inline-block float-right text-secondary font-weight-bold">
                                            <span ><?php echo $stats['churnRate']['percentage'] ?></span>
                                        </div>
                                    </div>
                                    <div id="renew-revenue5"></div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                                <div class="card">
                                    <div class="card-body conversionRate">
                                        <h5 class="text-muted">Conversion Rate</h5>
                                        <div class="metric-value d-inline-block">
                                            <h1 class="mb-1"><?php echo $stats['conversionRate']['total'] ?></h1>
                                        </div>
                                        <div class="metric-label d-inline-block float-right text-secondary font-weight-bold">
                                            <span ><?php echo $stats['conversionRate']['percentage'] ?></span>
                                        </div>
                                    </div>
                                    <div id="renew-revenue4"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <!-- ============================================================== -->
                      
                            <!-- ============================================================== -->

                                          <!-- recent orders  -->
                            <!-- ============================================================== -->
                            <div class="col-xl-9 col-lg-12 col-md-6 col-sm-12 col-12">
                                <div class="card">
                                    <h5 class="card-header">Daily Data</h5>
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead class="bg-light">
                                                    <tr class="border-0">
                                                        <!-- <th class="border-0">#</th> -->
                                                        <th class="border-0">Date</th>
                                                        <th class="border-0">New Conversions</th>
                                                        <th class="border-0">CPA</th>
                                                        <th class="border-0">TCPA</th>
                                                        <th class="border-0">Subscriptions</th>
                                                        <th class="border-0">Unsubscriptions</th>
                                                        <th class="border-0">Churn %</th>
                                                        <th class="border-0">Renewals</th>
                                                        <th class="border-0">Renewal Revenue</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                        foreach($report as $item) { ?>
                                                            <tr>
                                                                <!-- <td>1</td> -->
                                                                <td>
                                                                    <?= $item->date ?>
                                                                </td>
                                                                <td> <?= number_format($item->new_conversion) ?></td>
                                                                <td><?= $item->cost_per_aqui ?> </td>
                                                                <td><?= format_money($item->conversion_amount, '$') ?></td>
                                                                <td><?= number_format($item->subscription) ?></td>
                                                                <td><?= number_format($item->unsub) ?> </td>
                                                                <td><?= $item->churn_rate > 5 ? "<p class='text-danger'> ".round($item->churn_rate, 2)." </p>" : "<p class='text-success'>".round($item->churn_rate, 2)."</p>" ?> </td>
                                                                <td><?= number_format($item->total_rev) ?></td>
                                                                <td><?= format_money($item->renewal_revenue) ?> </td>
                                                            </tr>
                                                        <?php } 
                                                    ?>
                                                    <tr>
                                                        <td colspan="9">
                                                        <?php // $report->links() ?>    
                                                        <a href="#" class="btn btn-outline-light float-right">View Details</a></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- ============================================================== -->
                            <!-- end recent orders  -->

    
                            <!-- ============================================================== -->
                            <!-- ============================================================== -->
                            <!-- customer acquistion  -->
                            <!-- ============================================================== -->
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                                <div class="card">
                                    <h5 class="card-header">Customer Acquisition</h5>
                                    <div class="card-body">
                                        <div class="ct-chart ct-golden-section" style="height: 354px;"></div>
                                        <div class="text-center">
                                            <span class="legend-item mr-2">
                                                    <span class="fa-xs text-primary mr-1 legend-tile"><i class="fa fa-fw fa-square-full"></i></span>
                                            <span class="legend-text">Renewals</span>
                                            </span>
                                            <span class="legend-item mr-2">

                                                    <span class="fa-xs text-secondary mr-1 legend-tile"><i class="fa fa-fw fa-square-full"></i></span>
                                            <span class="legend-text">Subscriptions</span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- ============================================================== -->
                            <!-- end customer acquistion  -->
                            <!-- ============================================================== -->
                        </div>
                        <div class="row">
                            <!-- ============================================================== -->
              				                        <!-- product category  -->
                            <!-- ============================================================== -->
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                                <div class="card">
                                    <h5 class="card-header"> Service Transactions</h5>
                                    <div class="card-body">
                                        <!-- <div class="ct-chart-category ct-golden-section" style="height: 315px;"></div> -->
                                        <div class="apex-chart-category apex-golden-section"></div>
                                        <!-- -->
                                    </div>
                                </div>
                            </div>
                            <!-- ============================================================== -->
                            <!-- end product category  -->
                                   <!-- product sales  -->
                            <!-- ============================================================== -->
                            
                            <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="apex-chart-product apex-golden-section"></div>
                                    </div>
                                </div>
                            </div>
                            <!-- ============================================================== -->
                            <!-- end product sales  -->
                            <!-- ============================================================== -->
                            <div class="col-xl-3 col-lg-12 col-md-6 col-sm-12 col-12">
                                <!-- ============================================================== -->
                                <!-- top perfomimg  -->
                                <!-- ============================================================== -->
                                <div class="card">
                                    <h5 class="card-header">Top Performing Campaigns</h5>
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table no-wrap p-table">
                                                <thead class="bg-light">
                                                    <tr class="border-0">
                                                        <th class="border-0">Campaign</th>
                                                        <th class="border-0">Subs</th>
                                                        <th class="border-0">Renewal Revenue</th>
                                                        <th class="border-0">Total Cost</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    foreach ($campaigns as $campaign) { 
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $campaign->agency_name ?></td>
                                                            <td><?php echo number_format($campaign->transaction_count) ?> </td>
                                                            <td><?php echo format_money($campaign->total_amount) ?></td>
                                                            <td><?php echo format_money(amount: $campaign->transaction_count * 0.35, symbol: '$') ?></td>
                                                        </tr>
                                                    <?php }

                                                    ?>
                                                    <tr>
                                                        <td colspan="3">
                                                            <a href="#" class="btn btn-outline-light float-right">Details</a>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- ============================================================== -->
                                <!-- end top perfomimg  -->
                                <!-- ============================================================== -->
                            </div>
                        </div>

                        
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- footer -->
            <!-- ============================================================== -->
            <div class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                             Copyright © 2018 Concept. All rights reserved. Dashboard by <a href="https://colorlib.com/wp/">Colorlib</a>.
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="text-md-right footer-links d-none d-sm-block">
                                <a href="javascript: void(0);">About</a>
                                <a href="javascript: void(0);">Support</a>
                                <a href="javascript: void(0);">Contact Us</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- end footer -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- end wrapper  -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- end main wrapper  -->
    <!-- ============================================================== -->
    <!-- Optional JavaScript -->
    <!-- jquery 3.3.1 -->
    <script src="<?= config('app.public_path') ?>assets/vendor/jquery/jquery-3.3.1.min.js"></script>
    <!-- bootstap bundle js -->
    <script src="<?= config('app.public_path') ?>assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>
    <!-- slimscroll js -->
    <script src="<?= config('app.public_path') ?>assets/vendor/slimscroll/jquery.slimscroll.js"></script>
    <!-- main js -->
    <script src="<?= config('app.public_path') ?>assets/libs/js/main-js.js"></script>
    <!-- chart chartist js -->
    <script src="<?= config('app.public_path') ?>assets/vendor/charts/chartist-bundle/chartist.min.js"></script>
    <!-- sparkline js -->
    <script src="<?= config('app.public_path') ?>assets/vendor/charts/sparkline/jquery.sparkline.js"></script>
    <!-- morris js -->
    <script src="<?= config('app.public_path') ?>assets/vendor/charts/morris-bundle/raphael.min.js"></script>
    <script src="<?= config('app.public_path') ?>assets/vendor/charts/morris-bundle/morris.js"></script>
    <!-- chart c3 js -->
    <script src="<?= config('app.public_path') ?>assets/vendor/charts/c3charts/c3.min.js"></script>
    <script src="<?= config('app.public_path') ?>assets/vendor/charts/c3charts/d3-5.4.0.min.js"></script>
    <script src="<?= config('app.public_path') ?>assets/vendor/charts/c3charts/C3chartjs.js"></script>
    <!-- Apex charts js -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        $(document).ready(function () {
            // Cache DOM elements
            const $service = $("#service");
            const $campaignChooser = $("#campaign-chooser");
            const $from = $("#from");
            const $to = $("#to");
            const $csrfToken = $("input[name='csrf_token']");
            const $loader = $(".loader > p");

            const FILTER_STORAGE_KEY = "filterState";
            const DATA_STORAGE_KEY = "cachedData";
            let intervalId = null; // Track interval
            let activeRequest = null; // Track active request to avoid overlap

            $("#clear-button").click(function () {
                $service.val("all");
                $campaignChooser.val("all");
                $from.val("");
                $to.val("");
                saveFilterState();
                fetchData();
            });

            // Function to clear existing interval
            function clearRefreshInterval() {
                if (intervalId) {
                    clearInterval(intervalId);
                    intervalId = null;
                }
            }

            // Function to save filter state to localStorage
            function saveFilterState() {
                const filterState = {
                    service: $service.val(),
                    agency: $campaignChooser.val(),
                    from: $from.val(),
                    to: $to.val(),
                };
                localStorage.setItem(FILTER_STORAGE_KEY, JSON.stringify(filterState));
            }

            // Function to load filter state from localStorage
            function loadFilterState() {
                const savedState = localStorage.getItem(FILTER_STORAGE_KEY);
                if (savedState) {
                    const { service, agency, from, to } = JSON.parse(savedState);
                    if (service) $service.val(service);
                    if (agency) $campaignChooser.val(agency);
                    if (from) $from.val(from);
                    if (to) $to.val(to);
                }
            }

            // Function to generate a unique key for the current filter state
            function generateFilterKey() {
                return JSON.stringify({
                    service: $service.val(),
                    agency: $campaignChooser.val(),
                    from: $from.val(),
                    to: $to.val(),
                });
            }

            // Function to get cached data from localStorage
            function getCachedData(filterKey) {
                const cachedData = JSON.parse(localStorage.getItem(DATA_STORAGE_KEY) || "{}");
                return cachedData[filterKey] || null;
            }

            // Function to save data to localStorage
            function saveCachedData(filterKey, data) {
                const cachedData = JSON.parse(localStorage.getItem(DATA_STORAGE_KEY) || "{}");
                cachedData[filterKey] = data;
                localStorage.setItem(DATA_STORAGE_KEY, JSON.stringify(cachedData));
            }

            // Function to fetch data from the server
            function fetchData() {
                const filterKey = generateFilterKey();

                // Check for cached data
                const cachedData = getCachedData(filterKey);
                const parsedCachedData = JSON.parse(filterKey);
                if (cachedData && (parsedCachedData.agency !== 'all' && parsedCachedData.to !== '')) {
                    updateStats(cachedData.stats);
                    console.log("Loaded from cache:", cachedData);
                    return;
                }

                // If there's an active request, abort it to prevent overlap
                if (activeRequest) {
                    activeRequest.abort();
                    activeRequest = null;
                }

                // Prepare data for AJAX
                const data = {
                    service: $service.val() === "all" ? "" : $service.val(),
                    agency: $campaignChooser.val() === "all" ? "" : $campaignChooser.val(),
                    from: $from.val() || null,
                    to: $to.val() || null,
                    csrf_token: $csrfToken.val(),
                };

                // Make the AJAX request
                const url = "<?php echo url('get-data') ?>";

                $loader.removeClass("d-none")
        
                activeRequest = $.post(url, data, function (response) {
                    if (response.status === "success") {
                        $loader.addClass("d-none")

                        const fullData = response.data;

                        // Cache the retrieved data
                        saveCachedData(filterKey, fullData);

                        // Update the UI with the new data
                        updateStats(fullData.stats);
                        console.log("Fetched and updated data:", fullData);
                    } else {
                        $loader.addClass("d-none")
                        console.error("Error: Unexpected response status");
                    }
                })
                    .fail(function (jqXHR, textStatus, errorThrown) {
                        $loader.addClass("d-none")
                        console.error("AJAX request failed:", textStatus, errorThrown);
                    })
                    .always(function () {
                        $loader.addClass("d-none")
                        activeRequest = null; // Clear the active request
                    });
            }

            // Function to update stats in the UI
            function updateStats(stats) {
                Object.entries(stats).forEach(([key, value]) => {
                    const $parent = $("." + key);

                    // Update the total value only if it has changed
                    const $h1 = $parent.find("h1");
                    if ($h1.text() !== value.total) {
                        $h1.text(value.total);
                    }

                    // Update the percentage label only if it has changed
                    const $metricLabel = $parent.find(".metric-label");
                    if ($metricLabel.text() !== value.percentage) {
                        $metricLabel.empty().append(`<span>${value.percentage}</span>`);
                    }
                });
            }

            // Main function to refresh data with throttling
            function refreshData(cancel = false) {
                if (cancel) {
                    clearRefreshInterval(); // Stop the interval when filters are applied
                    fetchData(); // Immediately fetch data based on filters
                    return;
                }

                // Ensure only one interval exists
                clearRefreshInterval();

                // Fetch data immediately and then set up periodic refresh
                fetchData();
                intervalId = setInterval(() => {
                    if (!activeRequest) {
                        fetchData(); // Only fetch if no active request
                    }
                }, 30000);
            }

            // Handle filter changes
            function onFilterChange() {
                saveFilterState();
                refreshData(true); // Stop intervals and fetch new data immediately
            }

            // Load saved filter state on page load
            // loadFilterState();

            // Attach change handlers
            $service.on("change", onFilterChange);
            $campaignChooser.on("change", onFilterChange);
            $from.on("change", onFilterChange);
            $to.on("change", onFilterChange);

            // Trigger initial data load
            refreshData();
        });

        function getLastNDaysLabels(n) {
            const daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
            const labels = [];
            const today = new Date();

            for (let i = n - 1; i >= 0; i--) {
                const date = new Date();
                date.setDate(today.getDate() - i);
                labels.push(daysOfWeek[date.getDay()]);
            }

            return labels;
        }

        function getLastNMonthsLabels(n) {
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            const labels = [];
            const today = new Date();

            for (let i = 0; i < n; i++) {
                const date = new Date();
                date.setMonth(today.getMonth() - i);
                labels.push(months[date.getMonth()]);
            }

            return labels.reverse(); // Ensure order is from oldest to newest
        }





        var revenueArr = <?php echo json_encode(get_interval_data('transactions', 'amount', [['column'=> 'amount', 'operator' => '>', 'value'=> 1]], 'day', 7, 'mysql2', 't_date', false )) ?>;
        var subsArr = <?php echo json_encode(get_interval_data('transactions', 'amount', [['column'=> 'amount', 'operator' => '>', 'value'=> 1],['column'=> 'charges_status', 'value'=> 'Success'], ['column'=> 'bearer_id', 'value'=> 'SecureD']], 'day', 7, 'mysql2', 't_date', true, )) ?>;
        var unSubArr = <?php echo json_encode(get_interval_data('transactions', 'amount', [['column' => 'charges_status', 'operator' => '=', 'value'=>'You deactivate the service successfully.']], 'day', 7, 'mysql2', 't_date', true )) ?>;
        var subRevArr = <?php echo json_encode(get_interval_data('transactions', 'amount', [['column'=> 'amount', 'operator' => '>', 'value'=> 1],['column'=> 'charges_status', 'value'=> 'Success'], ['column'=> 'bearer_id', 'operator'=> '<>', 'value'=> 'system-renewal']], 'day', 7, 'mysql2', 't_date', )) ?>;
        var renRevArr = <?php echo json_encode(get_interval_data('transactions', 'amount', [['column'=> 'amount', 'operator' => '>', 'value'=> 1],['column'=> 'charges_status', 'value'=> 'Success'], ['column'=> 'bearer_id', 'operator'=> '=', 'value'=> 'system-renewal']], 'day', 7, 'mysql2', 't_date', )) ?>;
        var renewals = <?php echo json_encode(get_interval_data('transactions', 'amount', [['column'=> 'amount', 'operator' => '>', 'value'=> 1],['column'=> 'charges_status', 'value'=> 'Success'], ['column'=> 'bearer_id', 'operator'=> '=', 'value'=> 'system-renewal']], 'day', 4, 'mysql2', 't_date', true )) ?>;
        var subscriptions = <?php echo json_encode(get_interval_data('transactions', 'amount', [['column'=> 'charges_status', 'value'=> 'Success'], ['column'=> 'bearer_id', 'operator'=> '<>', 'value'=> 'system-renewal']], 'day', 4, 'mysql2', 't_date', true )) ?>;

        var gameNames = <?php echo $gameNames; ?>;
        var transactionCounts = <?php echo $transactionCounts; ?>;
        var pastFourMonths = <?php echo $pastFourMonths; ?>;






        var chartData = pastFourMonths.map((counts, index) => ({
            name: gameNames[index],
            data: counts
          }))
          console.log(chartData)
        // Charts
        var options = {
            series: chartData,
          chart: {
          type: 'bar',
          height: 350,
        //   stacked: true,
        },
            plotOptions: {
            bar: {
                horizontal: false,
                vertical: true,
                dataLabels: {
                total: {
                    enabled: true,
                    offsetX: 0,
                    style: {
                    fontSize: '40px',
                    fontWeight: 900
                    }
                }
                }
            },
            },
            stroke: {
                width: 1,
                colors: ['#fff']
            },
            title: {
            text: 'Game Sales'
            },
            xaxis: {
            categories: getLastNMonthsLabels(pastFourMonths[0].length),
            labels: {
                formatter: function (val) {
                return val
                }
            }
            },
            yaxis: {
            title: {
                text: undefined
            },
            labels: {
                formatter: function (val) {
                return "₦" + (val / 1000000) + "M"
                }
            }
            },
            tooltip: {
            y: {
                formatter: function (val) {
                return "₦" + (val / 1000000).toFixed(1) + "M"
                }
            }
            },
            fill: {
            opacity: 1
            },
            legend: {
            position: 'top',
            horizontalAlign: 'left',
            offsetX: 40
            }
        };

        var chart = new ApexCharts(document.querySelector(".apex-chart-product"), options);
        chart.render();


        var options = {
          series: transactionCounts,
          chart: {
          type: 'donut',
        },
        labels: gameNames,
        dataLabels: {
          enabled: true,
        },
            tooltip: {
            y: {
                formatter: function (val) {
                return (val / 1000).toFixed(1) + "K"
                }
            }
            },
        legend: {
            position: 'bottom',
            horizontalAlign: 'center',
            // offsetX: 40
        },
        responsive: [{
          breakpoint: 480,
          options: {
            chart: {
              width: 200
            },
            legend: {
              position: 'bottom'
            }
          }
        }]
        };

        var serviceTransactions = new ApexCharts(document.querySelector(".apex-chart-category"), options);
        serviceTransactions.render();
    </script>
    <script src="<?= config('app.public_path') ?>assets/libs/js/dashboard-ecommerce.js"></script>
</body>
 
</html>