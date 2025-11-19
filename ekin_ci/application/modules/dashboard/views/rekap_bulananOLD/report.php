<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>REPORT</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="<?php echo base_url(); ?>/assets/AdminLTE/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>/assets/AdminLTE/bower_components/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>/assets/AdminLTE/bower_components/Ionicons/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>/assets/AdminLTE/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
        page. However, you can choose any other skin. Make sure you
        apply the skin class to the body tag so the changes take effect. -->
    <link rel="stylesheet" href="<?php echo base_url('assets/AdminLTE/css/skins/skin-awantengah.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/dashboard.min.css'); ?>">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

    <link rel="stylesheet" href="<?php echo base_url('assets/css/sweet-alert.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/plugin/angular-moment-picker/dist/angular-moment-picker.min.css'); ?>">

    <script src="<?php echo base_url('assets/js/angular.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/plugin/moment/min/moment-with-locales.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/plugin/angular-moment-picker/dist/angular-moment-picker.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/app.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/xepOnline.jqPlugin.js'); ?>"></script>
    <!-- jQuery 3 -->
    <script src="<?php echo base_url(); ?>/assets/AdminLTE/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="<?php echo base_url('assets/js/prototype.js'); ?>"></script>
    <script>
        var limit = 1000;

        document.observe('dom:loaded', function() {

            jQuery(document).on("xepOnlineStatus", function(event, state) {
                // if (state == "Started") {
                //     var screenTop = jQuery(document).scrollTop();
                //     var screenHeight = jQuery(window).height();
                //     jQuery('#spinner-overlay').css('top', screenTop);
                //     jQuery('#spinner-overlay').css('height', screenHeight);
                //     jQuery('#spinner-overlay').toggle('show');
                // } else
                if (state == "Finished") {
                    jQuery('#spinner-overlay').toggle('hide');
                }
            });

        });

        function generatePDF(filename) {
            var screenTop = jQuery(document).scrollTop();
            var screenHeight = jQuery(window).height();
            jQuery('#spinner-overlay').css('top', screenTop);
            jQuery('#spinner-overlay').css('height', screenHeight);
            jQuery('#spinner-overlay').toggle('show');

            setTimeout(function() {
                return xepOnline.Formatter.Format('reportTable', {
                    pageWidth: '439mm',
                    pageHeight: '216mm',
                    render: 'embed',
                    filename: filename
                });
            }, 1000);

        }
    </script>
    <style>
        .border-none {
            border: none !important;
        }

        #mytable,
        #mytable>tbody+tbody {
            border-top: 1px solid #333 !important;
        }

        #mytable th,
        #mytable td {
            border: 1px solid #333;
        }

        #spinner-overlay {
            background-color: #aaa;
            opacity: 0.9;
            position: absolute;
            left: 0px;
            top: 0px;
            z-index: 100;
            height: 100%;
            width: 100%;
            overflow: hidden;
            background-image: url(<?php echo base_url('assets/img/Blocks-1s-200px.gif');
?>);
            background-position: center;
            background-repeat: no-repeat;
        }
    </style>
</head>

<!-- <body class="container" style="width: 1465px;"> -->
<body class="container">
    <div id="spinner-overlay" style="display:none;"></div>
    <div class="wrapper">
        <!-- Main content -->
        <section class="content" style="padding: 0;" ng-app="rekapBulananReportModule" ng-controller="rekapBulananReportController as mc" data-ng-init="init()">
            <div style="margin: 1em;">
                <div class="alert alert-info" role="alert" style="color: #31708f !important; background-color: #d9edf7 !important; border-color: #bce8f1 !important;">
                    <strong>Semakin banyak data, akan memerlukan waktu yang lebih lama dalam membuat file PDF..</strong>
                </div>
                <a href="#" class="btn btn-default" onclick="generatePDF('ABSEN TKD <?php echo isset($dinas) ? $dinas . ' ' . $year : 'document'; ?>')">
                    Generate PDF
                </a>
            </div>
            <!-- Your Page Content Here -->
            <div class="box" ng-cloak style="border: none; border-radius: 0; box-shadow: none;">
                <div class="box-body" style="padding-top: 0;">
                    <div id="reportTable" style="background: #fff;">
                        <div class="text-center" style="margin-bottom: 1em; font-size: 1.3em;">

                            <p>REKAPITULASI ABSENSI BULANAN <?php echo $tipe_pegawai == '0' ? 'PEGAWAI NEGERI SIPIL' : 'TENAGA KONTRAK DAERAH'; ?></p>
                            <p><?php echo isset($dinas) ? $dinas : ''; ?></p>
                            <p>PERIODE <?php echo strtoupper(get_indo_month_name($month)) . ' ' . $year; ?></p>

                        </div>
                        <div class="table-responsive">
                            <div uib-alert ng-repeat="alert in alerts" ng-class="'alert-' + (alert.type || 'warning')" close="closeAlert($index)" ng-cloak>{{alert.msg}}</div>
                            <table id="mytable" class="table table-hover table-bordered border-none" st-pipe="mc.callServer" st-table="mc.displayed" st-safe-src="mc.callServer" refresh-table table-watch>
                                <thead>
                                    <tr>
                                        <th width="10" rowspan="2" class="th-top">No</th>
                                        <th rowspan="2" class="th-top" style="min-width: 200px;">Nama</th>
                                        <th colspan="{{daysInMonth}}" class="text-center">Tanggal</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center" ng-repeat="n in [] | range:daysInMonth">{{n}}</th>
                                    </tr>
                                </thead>
                                <tbody ng-show="!mc.isLoading && !isSelectSOPD">
                                    <tr ng-repeat="row in mc.displayed" fostyle="keep-together.within-page: always;">
                                        <td class="text-center">{{mc.numbering + $index}}</td>
                                        <td>{{row.nama}}</td>
                                        <td class="text-center" ng-repeat="n in collectedAbsen" style="{{getIsHoliday(row.hari_kerja, n) ? getIsHoliday(row.hari_kerja, n).style : '' || getIsHolidaySpecial(row.absen, n)}}min-width: 39px;max-width: 39px; padding: 0; word-break: break-word;">
                                            <span ng-bind-html="getAbsen(row.absen, n, (getIsHoliday(row.hari_kerja, n) ? getIsHoliday(row.hari_kerja, n).isHoliday : '')) | htmlSafe" style="font-size: 0.9em;"></span>
                                        </td>
                                    </tr>
                                    <tr ng-show="mc.emptyData">
                                        <td colspan="{{daysInMonth+2}}" class="text-center">Data not found.</td>
                                    </tr>
                                </tbody>
                                <tbody ng-show="mc.isLoading">
                                    <tr>
                                        <td colspan="{{daysInMonth+2}}" class="text-center">
                                            <img ng-src="{{loadingImg}}" alt="Loading..">
                                        </td>
                                    </tr>
                                </tbody>
                                <tbody style="border: none;" fostyle="keep-together.within-page: always;">
                                    <tr>
                                        <td colspan="{{daysInMonth-8}}" class="border-none"></td>
                                        <td colspan="{{10}}" class="text-center border-none">
                                            <p>
                                                Kotawaringin Barat, <?php echo date('d') . ' ' . get_indo_month_name(date('n')) . ' ' . date('Y'); ?>
                                            </p>
                                            <p>
                                                <strong><?php echo isset($atasan_skpd) ? $atasan_skpd->title : ''; ?></strong>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="{{daysInMonth-8}}" class="border-none"></td>
                                        <td colspan="{{10}}" class="text-center border-none" style="padding-top: 3em;">
                                            <p>
                                                <strong><?php echo isset($atasan_skpd) ? $atasan_skpd->PNS_NAMA : ''; ?></strong>
                                            </p>
                                            <p><?php echo isset($atasan_skpd) ? 'NIP. ' . $atasan_skpd->PNS_PNSNIP : ''; ?></p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </section>
        <!-- /.content -->

        <script>
            var app = angular.module('rekapBulananReportModule', ['smart-table', 'oitozero.ngSweetAlert', 'ui.bootstrap']);

            app.factory('Resource', ['$q', '$filter', '$timeout', '$http', function($q, $filter, $timeout, $http) {

                function getPage(start, number, params, selectedSOPD = null, selectedMonth = null, selectedYear = null, selectedTipePegawai = null) {

                    var deferred = $q.defer();

                    var url = base_url + '/dashboard/rekap-bulanan/get-data';
                    var params_custom = selectedSOPD !== null ? {
                        unor: selectedSOPD,
                        month: selectedMonth,
                        year: selectedYear,
                        tipe_pegawai: selectedTipePegawai,
                        per_page: 200
                    } : {};

                    var getData = $http.get(url, {
                            params: params_custom
                        })
                        .then(function(response) {

                            recordItems = response.data.data;

                            var filtered = params.search.predicateObject ? $filter('filter')(recordItems, params.search.predicateObject) : recordItems;

                            if (params.sort.predicate) {
                                filtered = $filter('orderBy')(filtered, params.sort.predicate, params.sort.reverse);
                            }

                            var result = filtered.slice(start, start + number);

                            $timeout(function() {
                                //note, the server passes the information about the data set size
                                deferred.resolve({
                                    data: result,
                                    itemsLength: recordItems.length,
                                    numberOfPages: Math.ceil(filtered.length / number)
                                });
                            }, 300);
                        });

                    return deferred.promise;
                }

                return {
                    getPage: getPage
                };

            }]);

            app.directive("refreshTable", function() {
                return {
                    require: 'stTable',
                    restrict: "A",
                    link: function(scope, elem, attr, table) {
                        scope.$on("refreshData", function() {
                            table.pipe(table.tableState());
                        });
                    }
                }
            });

            app.directive('tableWatch', function($rootScope) {
                return {
                    require: '^stTable',
                    link: function(scope, element, attr, ctrl) {
                        $rootScope.$on('reset-pagination', function() {
                            ctrl.tableState().pagination.start = 0;
                            ctrl.pipe();
                        });
                    }
                }
            });

            app.filter('range', function() {
                return function(input, total) {
                    total = parseInt(total);

                    for (var i = 1; i <= total; i++) {
                        input.push(i);
                    }

                    return input;
                };
            });

            app.filter("htmlSafe", ['$sce', function($sce) {
                return function(htmlCode) {
                    return $sce.trustAsHtml(htmlCode);
                };
            }]);

            app.controller('rekapBulananReportController', ['Resource', '$scope', 'SweetAlert', '$http', '$uibModal',
                function(service, $scope, SweetAlert, $http, $uibModal) {

                    var ctrl = this;
                    this.displayed = [];

                    $scope.loadingImg = base_url + '/assets/img/loading.svg';

                    $scope.alerts = [];
                    $scope.closeAlert = function(index) {
                        $scope.alerts.splice(index, 1);
                    };

                    ctrl.tableState = '';
                    ctrl.isStart = 0;

                    $scope.isSelectSOPD = true;

                    this.callServer = function callServer(tableState) {
                        ctrl.isLoading = true;
                        ctrl.emptyData = false;

                        var pagination = tableState.pagination;

                        var start = pagination.start || 0;
                        var number = pagination.number || 1000;

                        $scope.selectedSOPD = "<?php echo $unor; ?>";
                        $scope.selectedMonth = "<?php echo $month; ?>";
                        $scope.selectedYear = "<?php echo $year; ?>";
                        $scope.selectedTipePegawai = "<?php echo $tipe_pegawai; ?>";

                        $scope.daysInMonth = moment($scope.selectedYear + "-" + $scope.selectedMonth, "YYYY-MM").daysInMonth();

                        service.getPage(start, number, tableState, $scope.selectedSOPD, $scope.selectedMonth, $scope.selectedYear, $scope.selectedTipePegawai).then(function(result) {
                            ctrl.isStart += number;
                            ctrl.tableState = tableState;
                            ctrl.displayed = result.data;
                            ctrl.itemsLength = result.itemsLength;
                            ctrl.emptyData = ctrl.displayed.length > 0 ? false : true;
                            ctrl.numbering = ctrl.emptyData == true ? start : start + 1;
                            ctrl.lengthFilter = ctrl.emptyData == true ? 0 : (ctrl.numbering - 1 + ctrl.displayed.length);
                            tableState.pagination.numberOfPages = result.numberOfPages;
                            ctrl.isLoading = false;

                            if (!$scope.selectedSOPD || $scope.selectedSOPD == '') {
                                $scope.isSelectSOPD = true;
                            } else {
                                $scope.isSelectSOPD = false;
                                $scope.collectedAbsen = [];

                                for (var i = 1; i <= $scope.daysInMonth; i++) {
                                    if (i < 10) {
                                        $scope.collectedAbsen.push('0' + i);
                                    } else {
                                        $scope.collectedAbsen.push(i);
                                    }
                                }

                                $http.get(base_url + '/api/get_all_absen_libur', {
                                        params: {
                                            fields: 'DAY(tanggal) as tanggal',
                                            month: $scope.selectedMonth,
                                            year: $scope.selectedYear
                                        }
                                    })
                                    .then(function(response) {
                                        var tmp = [];
                                        angular.forEach(response.data, function(value, key) {
                                            if (value.tanggal < 10) {
                                                tmp.push('0' + value.tanggal);
                                            } else {
                                                tmp.push(value.tanggal);
                                            }
                                        });
                                        $scope.allLIBUR = tmp;

                                        $scope.getAbsen = function(arr, n, isHoliday) {
                                            var check_arr = (arr !== undefined) ? (arr[n] !== undefined ? true : false) : false;
                                            var absenIn = check_arr === true ? (arr[n].in != undefined ? (arr[n].uraian === "[MANUAL]" ? "<span style='color:#FF9900;font-style:italic;'>" + arr[n].in + "</span>" : arr[n].in) : "") : "";
                                            var absenOut = check_arr === true ? (arr[n].out != undefined ? (arr[n].uraian === "[MANUAL]" ? "<span style='color:#FF9900;font-style:italic;'>" + arr[n].out + "</span>" : arr[n].out) : "") : "";
                                            var absenText = (absenIn !== "" && absenOut !== "") ? ("<div>" + absenIn + "</div><div>" + absenOut + "</div>") : (isHoliday != true ? (check_arr === true ? (arr[n].jenis === 'ket' && arr[n].keterangan != 0 ? "<div style='font-size: 0.8em;'>" + arr[n].singkatan + "</div>" : (absenIn !== "" ? "<div>" + absenIn + "</div><div>-</div>" : "<div>-</div><div>" + absenOut + "</div>")) : "<div style='font-size: 0.8em;'>TKS</div>") : "");
                                            return absenText;
                                        }

                                        $scope.getIsHolidaySpecial = function(arr, n) {
                                            var check_arr = (arr !== undefined) ? (arr[n] !== undefined ? true : false) : false;
                                            if (check_arr === true) {
                                                if (arr[n].jenis === 'ket' && arr[n].keterangan != 0 && arr[n].singkatan == 'LS') {
                                                    return "vertical-align: middle; background: #ccc;";
                                                } else {
                                                    return "vertical-align: middle;";
                                                }
                                            } else {
                                                return "vertical-align: middle;";
                                            }
                                        }

                                        $scope.getIsHoliday = function(hari_kerja, n) {
                                            $style = "background: #ccc;"
                                            if ($scope.allLIBUR.indexOf(n.toString()) !== -1 && hari_kerja !== "7") {
                                                return {
                                                    isHoliday: true,
                                                    style: $style
                                                };
                                            } else {
                                                var currentMonth = $scope.selectedYear + '-' + ($scope.selectedMonth < 10 ? '0' + $scope.selectedMonth : $scope.selectedMonth) + '-' + n;
                                                var checkWeekend = moment(currentMonth).format('dddd');
                                                if (hari_kerja === "5") {
                                                    if (checkWeekend == 'Sunday' || checkWeekend == 'Saturday') {
                                                        return {
                                                            isHoliday: true,
                                                            style: $style
                                                        };
                                                    }
                                                    return;
                                                } else if (hari_kerja === "6") {
                                                    if (checkWeekend == 'Sunday') {
                                                        return {
                                                            isHoliday: true,
                                                            style: $style
                                                        };
                                                    }
                                                    return;
                                                }
                                                return;
                                            }
                                        }
                                    });
                            }
                        });
                    };

                }
            ]);
        </script>
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED JS SCRIPTS -->

    <!-- Bootstrap 3.3.7 -->
    <script src="<?php echo base_url(); ?>/assets/AdminLTE/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- AdminLTE App -->
    <script src="<?php echo base_url(); ?>/assets/AdminLTE/js/adminlte.min.js"></script>

    <script src="<?php echo base_url('assets/js/smart-table.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/SweetAlert.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/sweet-alert.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/ui-bootstrap-2.0.0.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/ui-bootstrap-tpls-2.0.0.min.js'); ?>"></script>

    <!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
</body>

</html>