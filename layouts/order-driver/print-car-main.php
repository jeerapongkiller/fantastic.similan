<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
    <meta name="description" content="<?php echo $main_description; ?>">
    <meta name="keywords" content="<?php echo $main_keywords; ?>">
    <meta name="author" content="<?php echo $main_author; ?>">
    <title>ใบงานรถ <?php echo !empty($_GET['date']) ? htmlspecialchars($_GET['date'], ENT_QUOTES, 'UTF-8') : ''; ?> - <?php echo $main_title; ?></title>
    <link rel="apple-touch-icon" href="app-assets/images/ico/favicon.png">
    <link rel="shortcut icon" type="image/x-icon" href="app-assets/images/ico/favicon.png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="app-assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="app-assets/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="app-assets/css/colors.css">
    <link rel="stylesheet" type="text/css" href="app-assets/css/components.css">

    <style type="text/css" media="print">
        @page {
            size: A4 portrait;
            margin: 10mm;
        }
    </style>

    <style>
        .order-driver__print-car {
            padding: 10px;
        }

        .order-driver__print-car h3,
        .order-driver__print-car h4 {
            color: #000;
        }

        .tableprint {
            width: 100%;
            border-collapse: collapse;
        }

        .tableprint td,
        .tableprint th {
            border: 1px solid #333;
            font-size: 16px;
            padding: 5px;
            color: #000;
        }

        .tableprint th {
            background-color: #333;
            color: #FFF;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    </style>
</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="blank-page" style="background-color: #FFF; color: #000;">

    <!-- BEGIN: Content-->
    <?php include 'pages/' . $_GET['pages'] . '.php'; ?>
    <!-- END: Content-->

    <script>
        window.addEventListener('load', function() {
            window.print();
        });
    </script>
</body>
<!-- END: Body-->
