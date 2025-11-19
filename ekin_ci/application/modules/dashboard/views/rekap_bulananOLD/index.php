<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.22/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>

<!-- Main content -->
<section class="content" ng-app="rekapBulananModule" ng-controller="rekapBulananController as mc" data-ng-init="init()">

    <!-- Your Page Content Here -->
    <div class="box" ng-init="id_groups = <?php echo $id_groups; ?>; unor = <?php echo $unor; ?>" ng-cloak>
        <div class="box-header" style="padding-bottom: 0;">
            <div class="row">
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <select class="form-control select2" ng-model="selectedSOPD" ng-options="item as item.NM_UNOR for item in allSOPD track by item.KD_UNOR" ng-change="getSelectedSOPD()">
                                    <option value="">- Pilih SOPD -</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <select class="form-control" ng-model="selectedMonth" ng-options="item as item.month_text for item in allMONTH track by item.month" ng-change="getSelectedSOPD()">
                                    <option value="">- Pilih Bulan -</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <select class="form-control" ng-model="selectedYear" ng-options="item as item.year for item in allYEAR track by item.year" ng-change="getSelectedSOPD()">
                                    <option value="">- Pilih Tahun -</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <select class="form-control" ng-model="selectedTipePegawai" ng-options="item as item.type for item in allTipePegawai track by item.id" ng-change="getSelectedSOPD()">
                                    <option value="">- Pilih Tipe Pegawai -</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <select class="form-control" ng-model="selectedLimit" ng-change="getSelectedSOPD()">
                                    <option value="">- Pilih Limit -</option>
                                    <option value="10" ng-selected="limit == 10">10</option>
                                    <option value="30" ng-selected="limit == 30">30</option>
                                    <option value="50" ng-selected="limit == 50">50</option>
                                    <option value="100" ng-selected="limit == 100">100</option>
                                    <option value="1000" ng-selected="limit == 1000">1000</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2" ng-show="!mc.isLoading && !isSelectSOPD && !isselectedTipePegawai && !mc.emptyData">
                            <button type="button" class="btn btn-default" ng-click="openPrintModal(selectedSOPD.KD_UNOR, selectedMonth.month, selectedYear.year, (selectedTipePegawai != null ? selectedTipePegawai.id : null))">Cetak Laporan</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-2" ng-cloak>
                    <div class="box-tools pull-right" ng-show="!mc.isLoading && !isSelectSOPD && !isselectedTipePegawai && !mc.emptyData">
                        Showing {{mc.numbering + $index}} to {{mc.lengthFilter}} of {{mc.itemsLength}} entries
                    </div>
                </div>
            </div>
        </div>
        <div class="box-body" style="padding-top: 0;">
            <div id="reportTable" class="table-responsive">
                <div uib-alert ng-repeat="alert in alerts" ng-class="'alert-' + (alert.type || 'warning')" close="closeAlert($index)" ng-cloak>{{alert.msg}}</div>
                <table class="table table-hover table-bordered" st-pipe="mc.callServer" st-table="mc.displayed" st-safe-src="mc.callServer" refresh-table table-watch>
                    <thead ng-cloak>
                        <tr>
                            <th width="10" rowspan="2" class="th-top">No</th>
                            <th rowspan="2" class="th-top" style="min-width: 250px;">Nama</th>
                            <th colspan="{{daysInMonth}}" class="text-center">Tanggal</th>
                        </tr>
                        <tr>
                            <th ng-repeat="n in [] | range:daysInMonth">{{n}}</th>
                        </tr>
                    </thead>
                    <tbody ng-show="!mc.isLoading && !isSelectSOPD && !isselectedTipePegawai" ng-cloak>
                        <tr ng-repeat="row in mc.displayed">
                            <td>{{mc.numbering + $index}}</td>
                            <td><strong>{{row.nama}}</strong><br>{{row.nip}}</td>
                            <td class="text-center" ng-repeat="n in collectedAbsen" style="{{getIsHoliday(row.hari_kerja, n) ? getIsHoliday(row.hari_kerja, n).style : '' || getIsHolidaySpecial(row.absen, n)}}">
                                <span ng-bind-html="getAbsen(row.absen, n, (getIsHoliday(row.hari_kerja, n) ? getIsHoliday(row.hari_kerja, n).isHoliday : '')) | htmlSafe"></span>
                            </td>
                        </tr>
                        <tr ng-show="mc.emptyData">
                            <td colspan="{{daysInMonth+2}}" class="text-center">Data not found.</td>
                        </tr>
                    </tbody>
                    <tbody ng-show="mc.isLoading" ng-cloak>
                        <tr>
                            <td colspan="{{daysInMonth+2}}" class="text-center">
                                <img ng-src="{{loadingImg}}" alt="Loading..">
                            </td>
                        </tr>
                    </tbody>
                    <tbody ng-show="!mc.isLoading && isSelectSOPD && isselectedTipePegawai" ng-cloak>
                        <tr>
                            <td colspan="{{daysInMonth+2}}" class="text-center">
                                Silakan pilih tahun, bulan dan SOPD
                            </td>
                        </tr>
                    </tbody>
                    <tfoot ng-show="!isSelectSOPD && !mc.isLoading && !isselectedTipePegawai" ng-cloak>
                        <tr>
                            <td class="text-center" st-pagination="" st-items-by-page="limit" colspan="{{daysInMonth+2}}">
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <hr>
            <table class="table table-borderless" ng-cloak>
                <thead>
                    <tr>
                        <th colspan="3" class="padding-0">KETERANGAN:</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="row in allKET">
                        <th class="padding-0"><i class="fa fa-arrow-circle-right" style="font-size: 0.8em;"></i> {{row.singkatan}}</th>
                        <td class="padding-0">=</td>
                        <td class="padding-0">{{row.keterangan}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <script type="text/ng-template" id="alert.html">
        <div ng-transclude></div>
    </script>

    <script type="text/ng-template" id="myPrintContent.html">
        <div class="modal-body" style="overflow: hidden !important;">
            <iframe ng-src="{{urlPrint}}" width="1500" height="100" frameborder="0" scrolling="no" allowtransparency="true">
                <p>Your browser does not support iframes.</p>
            </iframe>
        </div>
        <div class="modal-footer">
            <button class="btn btn-warning" type="button" ng-click="cancel()">Close</button>
        </div>
    </script>

</section>
<!-- /.content -->

<script>
    var app = angular.module('rekapBulananModule', ['smart-table', 'oitozero.ngSweetAlert', 'ui.bootstrap']);

    app.factory('Resource', ['$q', '$filter', '$timeout', '$http', function($q, $filter, $timeout, $http) {

        function getPage(start, number, params, selectedSOPD = null, selectedMonth = null, selectedYear = null, selectedTipePegawai = null, selectedLimit = null, page = 0) {

            var deferred = $q.defer();

            var url = base_url + '/dashboard/rekap-bulanan/get-data';
            var params_custom = selectedSOPD !== null ? {
                unor: selectedSOPD.KD_UNOR,
                month: selectedMonth.month,
                year: selectedYear.year,
                tipe_pegawai: (selectedTipePegawai != null ? selectedTipePegawai.id : 'null'),
                per_page: selectedLimit,
                page: page
            } : {};

            var getData = $http.get(url, {
                    params: params_custom
                })
                .then(function(response) {
                    recordItems = response.data.data;

                    if(params.search.predicateObject) {
                        var params_custom_search = selectedSOPD !== null ? {
                            unor: selectedSOPD.KD_UNOR,
                            month: selectedMonth.month,
                            year: selectedYear.year,
                            tipe_pegawai: (selectedTipePegawai != null ? selectedTipePegawai.id : 'null'),
                            page: page,
                            search_name: params.search.predicateObject.nama
                        } : {};
                        var extract = $http.get(url, {
                            params: params_custom_search
                        }).then(function(response) {
                            return response.data;
                        });

                        extract.then(function(response) {
                            var filtered = response.data;
                            var result = filtered.slice(0, Infinity);

                            $timeout(function() {
                                //note, the server passes the information about the data set size
                                deferred.resolve({
                                    data: result,
                                    itemsLength: response.data.total,
                                    numberOfPages: response.data.total_pages
                                });
                            }, 300);
                        });
                    } else {
                        var filtered = recordItems;
                        if (params.sort.predicate) {
                            filtered = $filter('orderBy')(filtered, params.sort.predicate, params.sort.reverse);
                        }

                        var result = filtered.slice(0, Infinity);

                        $timeout(function() {
                            //note, the server passes the information about the data set size
                            deferred.resolve({
                                data: result,
                                itemsLength: response.data.total,
                                numberOfPages: response.data.total_pages
                            });
                        }, 300);
                    }
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

    app.controller('rekapBulananController', ['Resource', '$scope', 'SweetAlert', '$http', '$uibModal', '$timeout', '$window',
        function(service, $scope, SweetAlert, $http, $uibModal, $timeout, $window) {

            $scope.init = function() {
                $scope.$watchGroup(['id_groups', 'unor'], function(newValues, oldValues, scope) {
                    params = {};
                    if (scope.id_groups != 5) {
                        params = {
                            params: {
                                unor: scope.unor
                            }
                        }
                        $scope.selectedSOPD = {
                            KD_UNOR: scope.unor
                        }
                    }
                    $http.get(base_url + '/api/get_all_sopd', params)
                        .then(function(response) {
                            $scope.allSOPD = response.data;
                        });
                });
            }

            var ctrl = this;
            this.displayed = [];

            $scope.limit = limit;

            $scope.loadingImg = base_url + '/assets/img/loading.svg';

            $scope.alerts = [];
            $scope.closeAlert = function(index) {
                $scope.alerts.splice(index, 1);
            };

            ctrl.isStart = 0;

            $scope.selectedSOPD = '';
            $scope.isSelectSOPD = true;

            $scope.selectedTipePegawai = '';
            $scope.isselectedTipePegawai = true;

            $scope.daysInMonth = moment().daysInMonth();
            $scope.selectedMonth = {
                'month': moment().month() + 1 //+1 because 0 is jan
            };
            $scope.selectedYear = {
                'year': moment().year()
            };

            $http.get(base_url + '/api/get_all_month')
                .then(function(response) {
                    $scope.allMONTH = response.data;
                });

            $http.get(base_url + '/api/get_all_year')
                .then(function(response) {
                    $scope.allYEAR = response.data;
                });

            $http.get(base_url + '/api/get_all_keterangan_absen')
                .then(function(response) {
                    $scope.allKET = response.data;
                });

            $http.get(base_url + 'api/get_tipe_pegawai')
                .then(function(response) {
                    $scope.allTipePegawai = response.data;
                });

            this.callServer = function callServer(tableState) {
                ctrl.isLoading = true;
                ctrl.emptyData = false;

                var pagination = tableState.pagination;

                var start = pagination.start || 0;
                var number = pagination.number || limit;
                var page = Math.ceil(start / limit);

                if ($scope.selectedSOPD && $scope.selectedSOPD !== '') {
                    service.getPage(start, number, tableState, $scope.selectedSOPD, $scope.selectedMonth, $scope.selectedYear, $scope.selectedTipePegawai, $scope.selectedLimit, page).then(function(result) {
                        ctrl.isStart += number;
                        ctrl.displayed = result.data;
                        ctrl.itemsLength = result.itemsLength;
                        ctrl.emptyData = ctrl.displayed.length > 0 ? false : true;
                        ctrl.numbering = ctrl.emptyData == true ? start : start + 1;
                        ctrl.lengthFilter = ctrl.emptyData == true ? 0 : (ctrl.numbering - 1 + ctrl.displayed.length);
                        tableState.pagination.numberOfPages = result.numberOfPages;
                        ctrl.isLoading = false;

                        if (!$scope.selectedSOPD || $scope.selectedSOPD == '') {
                            $scope.isSelectSOPD = true;
                        } else if (!$scope.selectedTipePegawai && $scope.selectedTipePegawai == '') {
                            $scope.isselectedTipePegawai = true;
                        } else {
                            $scope.isSelectSOPD = false;
                            $scope.isselectedTipePegawai = false;
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
                                        month: $scope.selectedMonth.month,
                                        year: $scope.selectedYear.year
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
                                });

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
                                if ($scope.allLIBUR != undefined) {
                                    $style = "background: #ccc;"
                                    if ($scope.allLIBUR.indexOf(n.toString()) !== -1 && hari_kerja !== "7") {
                                        return {
                                            isHoliday: true,
                                            style: $style
                                        };
                                    } else if (hari_kerja !== "7") {
                                        var currentMonth = $scope.selectedYear.year + '-' + ($scope.selectedMonth.month < 10 ? '0' + $scope.selectedMonth.month : $scope.selectedMonth.month) + '-' + n;
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
                            }

                        }
                    });
                } else {
                    $timeout(function() {
                        ctrl.isLoading = false;
                    }, 300);
                    $scope.isSelectSOPD = true;
                }
            };

            $scope.getSelectedSOPD = function(unor) {
                ctrl.isStart = 0;
                $scope.$emit('reset-pagination');
                $scope.daysInMonth = moment($scope.selectedYear.year + "-" + $scope.selectedMonth.month, "YYYY-MM").daysInMonth();
            };

            $scope.cancel = function() {
                $scope.modalInstance.dismiss('cancel');
            };

            $scope.openPrintModal = function(unor, month, year, tipe_pegawai) {
                // var modal = $uibModal.open({
                //     templateUrl: 'myPrintContent.html',
                //     scope: $scope,
                //     size: 'sm'
                // });

                // $scope.modalInstance = modal;
                // $scope.urlPrint = base_url + '/dashboard/rekap-bulanan/report?unor=' + unor + '&month=' + month + '&year=' + year;

                // return modal.result
                $window.open(base_url + '/dashboard/rekap-bulanan/report?unor=' + unor + '&month=' + month + '&year=' + year + '&tipe_pegawai=' + tipe_pegawai, '_blank');
            }

        }
    ]);
</script>