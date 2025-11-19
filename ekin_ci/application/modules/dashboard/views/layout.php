<!DOCTYPE html>
<!-- @Author: Awan Tengah Studio-->
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $_app_title ? $_app_title : ''; ?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="author" content="Awan Tengah Studio">

    <link rel="stylesheet" href="<?php echo base_url(); ?>/assets/AdminLTE/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>/assets/AdminLTE/bower_components/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>/assets/AdminLTE/bower_components/Ionicons/css/ionicons.min.css">
    <link rel="stylesheet" href="<?php echo base_url('assets/AdminLTE/bower_components/select2/dist/css/select2.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/plugin/DataTables-1.10.20/css/dataTables.bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>/assets/AdminLTE/css/AdminLTE.min.css">
    <link rel="stylesheet" href="<?php echo base_url('assets/AdminLTE/plugins/iCheck/all.css'); ?>">
    <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
        page. However, you can choose any other skin. Make sure you
        apply the skin class to the body tag so the changes take effect. -->
    <link rel="stylesheet" href="<?php echo base_url('assets/AdminLTE/css/skins/skin-awantengah.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/dashboard.min.css'); ?>">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha384-..." crossorigin="anonymous">

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

    <!-- App favicon -->
    <link rel="shortcut icon" href="<?php echo base_url('assets/img/favicon.ico'); ?>">

    <link rel="stylesheet" href="<?php echo base_url('assets/css/sweet-alert.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/plugin/angular-moment-picker/dist/angular-moment-picker.min.css'); ?>">

    <!-- jQuery 3 -->
    <script src="<?php echo base_url(); ?>/assets/AdminLTE/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="<?php echo base_url('assets/js/angular.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/angular-locale_id-id.js'); ?>"></script>
    <script src="<?php echo base_url('assets/plugin/moment/min/moment-with-locales.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/plugin/angular-moment-picker/dist/angular-moment-picker.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/plugin/DataTables-1.10.20/js/jquery.dataTables.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/plugin/DataTables-1.10.20/js/dataTables.bootstrap.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/plugin/currency.js/dist/currency.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/plugin/cleave.js/dist/cleave.min.js'); ?>"></script>
    <!-- <script src="<?php //echo base_url('assets/js/app.js'); ?>"></script> -->
    <script>
        let base_url = "<?php echo base_url(); ?>";
        let limit = '<?php echo isset($limit) ? $limit : 10; ?>';
    </script>

    <!--time picker-->
    <script src="<?php echo base_url('assets/plugin/timepicker.js/timepicker.min.js'); ?>"></script>

    <script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>

    <style>
        #print-box .table-bordered>thead>tr>th,
        #print-box .table-bordered>tbody>tr>th,
        #print-box .table-bordered>tfoot>tr>th,
        #print-box .table-bordered>thead>tr>td,
        #print-box .table-bordered>tbody>tr>td,
        #print-box .table-bordered>tfoot>tr>td,
        .print-box .table-bordered>thead>tr>th,
        .print-box .table-bordered>tbody>tr>th,
        .print-box .table-bordered>tfoot>tr>th,
        .print-box .table-bordered>thead>tr>td,
        .print-box .table-bordered>tbody>tr>td,
        .print-box .table-bordered>tfoot>tr>td {
            border: 1px solid #ccc !important;
        }
        #print-box ol,
        .print-box ol {
            padding-left: 1em !important;
            margin-bottom: 0 !important;
        }
        .dataTables_wrapper {
            margin-top: 1em;
        }
        .alert-info {
            color: #31708f !important;
            background-color: #d9edf7 !important;
            border-color: #bce8f1 !important;
        }
        body { padding-right: 0 !important; }

        .select2 {
            width: 100% !important;
        }
    </style>
</head>

<body class="hold-transition skin-awantengah sidebar-mini">
    <div class="wrapper">

        <!-- Main Header -->
        <header class="main-header">

            <!-- Logo -->
            <a href="<?php echo site_url('dashboard'); ?>" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini"><?php echo $_app_title_sort ? $_app_title_sort : ''; ?></span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg"><?php echo $_app_title ? $_app_title : ''; ?></span>
            </a>

            <!-- Header Navbar -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>
                <!-- Navbar Right Menu -->
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <!-- User Account Menu -->
                        <li class="dropdown user user-menu">
                            <!-- Menu Toggle Button -->
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <!-- The user image in the navbar-->
                                <img src="<?php echo !is_null($_user_login->photo) ? base_url(get_config_item('user_path') . $_user_login->photo) : base_url('assets/img/user.png'); ?>" class="user-image" style="border-radius: 6px !important;" alt="User Image">
                                <!-- hidden-xs hides the username on small devices so only the image appears. -->
                                <span class="hidden-xs">
                                    <?php echo isset($_user_login) ? $_user_login->username : ''; ?>
                                </span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- The user image in the menu -->
                                <li class="user-header">
                                    <img src="<?php echo !is_null($_user_login->photo) ? base_url(get_config_item('user_path') . $_user_login->photo) : base_url('assets/img/user.png'); ?>" class="img-rounded" alt="User Image" style="height: auto !important;">

                                    <p>
                                        <?php echo isset($_user_login) ? (get_session('id_groups') == '3' ? $_user_login->PNS_NAMA : $_user_login->first_name . ' ' . $_user_login->last_name) : ''; ?>
                                    </p>
                                </li>

                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="<?php echo site_url('dashboard/profile'); ?>" class="btn btn-default btn-flat">Profile</a>
                                    </div>
                                    <div class="pull-right">
                                        <a href="<?php echo site_url('logout'); ?>" class="btn btn-default btn-flat">Sign out</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar">

            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">

                <!-- Sidebar user panel (optional) -->
                <div class="user-panel">
                    <div class="pull-left image">
                        <img src="<?php echo !is_null($_user_login->photo) ? base_url(get_config_item('user_path') . $_user_login->photo) : base_url('assets/img/user.png'); ?>" class="img-rounded" alt="User Image">
                    </div>
                    <div class="pull-left info" style="white-space: normal;">
                        <p><?php echo isset($_user_login) ? $_user_login->username : ''; ?></p>
                        <!-- Status -->
                        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <ul class="sidebar-menu" data-widget="tree">
                    <li class="header">MENU</li>
                    <!-- Optionally, you can add icons to the links -->
                    <?php foreach ($ci->parent_menu() as $row): ?>
                        <?php if ($ci->has_child_menu($row->id)): ?>
                            <li class="treeview <?php echo isset($active_menu) ? (strtolower($active_menu) == strtolower($row->title) ? 'menu-open' : '') : ''; ?>">
                                <a href="<?php echo site_url($row->url); ?>">
                                    <i class="<?php echo $row->icon; ?>"></i> <span><?php echo $row->title; ?></span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu" <?php echo isset($active_menu) ? (strtolower($active_menu) == strtolower($row->title) ? 'style="display: block;"' : '') : ''; ?>>
                                    <?php foreach ($ci->has_child_menu($row->id) as $row): ?>
                                        <li>
                                            <a href="<?php echo site_url($row->url); ?>">
                                                <i class="<?php echo $row->icon; ?>"></i>
                                                <span><?php echo $row->title; ?></span>
                                            </a>
                                        </li>
                                    <?php endforeach;?>
                                </ul>
                            </li>
                        <?php else: ?>
                            <?php if ($row->url != 'dashboard/#'): ?>
                                <li>
                                    <a href="<?php echo site_url($row->url); ?>">
                                        <i class="<?php echo $row->icon; ?>"></i>
                                        <span><?php echo $row->title; ?></span>
                                    </a>
                                </li>
                            <?php endif;?>
                        <?php endif;?>
                    <?php endforeach;?>
                </ul>
                <!-- /.sidebar-menu -->
            </section>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    <?php echo isset($page_title) ? $page_title : ''; ?>
                </h1>
                <?php if (isset($breadcrumb)): ?>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php echo site_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Beranda</a>
                    </li>
                    <?php foreach ($breadcrumb as $row): ?>
                    <?php if ($row['active'] == '1'): ?>
                    <li class="active"><?php echo ucwords(strtolower($row['title'])); ?></li>
                    <?php else: ?>
                    <li>
                        <a href="<?php echo $row['link']; ?>">
                            <?php echo ucwords(strtolower($row['title'])); ?>
                        </a>
                    </li>
                    <?php endif;?>
                    <?php endforeach;?>
                </ol>
                <?php endif;?>
            </section>

            <?php echo $_main_content; ?>

        </div>
        <!-- /.content-wrapper -->

        <!-- Main Footer -->
        <footer class="main-footer">
            <!-- To the right -->
            <!-- <div class="pull-right hidden-xs">
                Anything you want
            </div> -->
            <!-- Default to the left -->
            <strong>Copyright &copy; <?php echo date('Y'); ?> DISKOMINFO Kabupaten Kotawaringin Barat.</strong>
        </footer>

    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED JS SCRIPTS -->
    <!-- Bootstrap 3.3.7 -->
    <script src="<?php echo base_url(); ?>/assets/AdminLTE/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- AdminLTE App -->
    <script src="<?php echo base_url(); ?>/assets/AdminLTE/js/adminlte.min.js"></script>
    <script src="<?php echo base_url('assets/AdminLTE/plugins/iCheck/icheck.min.js'); ?>"></script>

    <script src="<?php echo base_url('assets/js/smart-table.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/SweetAlert.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/sweet-alert.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/ui-bootstrap-2.0.0.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/ui-bootstrap-tpls-2.0.0.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/AdminLTE/bower_components/select2/dist/js/select2.full.min.js'); ?>"></script>

    <script>
        $(function() {
            setTimeout(function() {
                $('.select2').select2();
            }, 500);
        })
    </script>

    <!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
</body>

</html>
