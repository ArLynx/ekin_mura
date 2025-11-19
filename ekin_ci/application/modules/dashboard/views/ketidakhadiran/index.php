<!-- Main content -->
<section class="content" ng-app="ketidakhadiranModule" ng-controller="ketidakhadiranController as mc" data-ng-init="init()">

    <!-- Your Page Content Here -->
     <!-- <div class="box" ng-init="id_groups = <?php echo $id_groups; ?>; unor = <?php echo $unor; ?>; current_day = <?php echo date('j'); ?>; current_month = <?php echo date('n'); ?>; current_year = <?php echo date('Y'); ?>; max_date_edit_presence = <?php echo $max_date_edit_presence; ?>; data_created=<?php echo $_created ?? 0; ?>" ng-cloak> -->
    <div class="box" ng-init="id_groups = <?php echo $id_groups; ?>; unor = <?php echo $unor; ?>; current_day = <?php echo date('j'); ?>; current_month = <?php echo date('n'); ?>; current_year = <?php echo date('Y'); ?>; max_date_edit_presence = <?php echo $max_date_edit_presence; ?>; data_created=<?php echo $_created ?? 0; ?>" ng-cloak>
        <div class="box-header">
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
                                <select class="form-control" ng-model="selectedDay" ng-options="item as item.day for item in getDaysInMonth() track by item.day" ng-change="getSelectedSOPD()">
                                    <option value="">- Pilih Tanggal -</option>
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
                                <select class="form-control" ng-model="selectedTipePegawai" ng-options="item as item.type for item in allTipePegawai.data track by item.id" ng-change="getSelectedSOPD()">
                                    <option value="">- Pilih Tipe Pegawai -</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <select class="form-control" convert-number ng-model="itemsByPage" ng-init="itemsByPage= limit">
                                    <option value="">- Pilih Limit -</option>
                                    <option value="10" ng-selected="itemsByPage == 10">10</option>
                                    <option value="30" ng-selected="itemsByPage == 30">30</option>
                                    <option value="50" ng-selected="itemsByPage == 50">50</option>
                                    <option value="100" ng-selected="itemsByPage == 100">100</option>
                                    <option value="1000" ng-selected="itemsByPage == 1000">1000</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2" ng-cloak>
                    <div class="box-tools pull-right" ng-show="!mc.isLoading && !isSelectSOPD && !isselectedTipePegawai">
                        Showing {{mc.numbering + $index}} to {{mc.lengthFilter}} of {{mc.itemsLength}} entries
                    </div>
                </div>
            </div>
        </div>
        <div class="box-body">
            <div class="table-responsive">
                <div uib-alert ng-repeat="alert in alerts" ng-class="'alert-' + (alert.type || 'warning')" close="closeAlert($index)" ng-cloak>{{alert.msg}}</div>
                <table class="table table-hover table-bordered" st-pipe="mc.callServer" st-table="mc.displayed" st-safe-src="mc.callServer" refresh-table table-watch>
                    <thead>
                        <tr>
                            <th width="10" rowspan="2" class="th-top">No</th>
                            <th st-sort="PNS_PNSNAM">Nama</th>
                            <th width="50" rowspan="2" class="th-top">Datang</th>
                            <th width="50" rowspan="2" class="th-top">Pulang</th>
                            <th width="230" rowspan="2" class="th-top">Keterangan</th>
                            <th rowspan="2" class="th-top">Uraian</th>
                            <th width="60" rowspan="2" class="th-top">Aksi</th>
                        </tr>
                        <tr>
                            <th><input st-search="PNS_PNSNAM" placeholder="Pencarian.." class="input-sm form-control"></th>
                        </tr>
                    </thead>
                    <tbody ng-show="!mc.isLoading && !isSelectSOPD && !isselectedTipePegawai" ng-cloak>
                        <tr ng-repeat="row in mc.displayed">
                            <td>{{mc.numbering + $index}}</td>
                            <td>{{row.PNS_NAMA}}</td>
                            <td style="{{row.in.uraian == '[MANUAL]' ? 'color: #FF9900; font-style: italic;' : ''}}">{{row.in.waktu}}</td>
                            <td style="{{row.out.uraian == '[MANUAL]' ? 'color: #FF9900; font-style: italic;' : ''}}">{{row.out.waktu}}</td>
                            <td>{{row.ket.keterangan_kehadiran}}</td>
                            <td>{{row.ket.uraian}}</td>
                            <td width="100" class="text-center">
                                <div ng-if="data_created">
                                      <div ng-if="id_groups == 1 || id_groups == 5 || id_groups == 2">
                                        <!-- uncomment yang bawah untuk hide dari aku skpd -->
                                    <!-- <div ng-if="id_groups == 1 || id_groups == 5 || ((selectedMonth.month == current_month && selectedYear.year == current_year) || (current_day <= max_date_edit_presence && selectedMonth.month == (current_month - 1) && selectedYear.year == current_year ) || (current_day <= max_date_edit_presence && current_month == 1 && selectedYear.year == (current_year - 1) && selectedMonth.month == 12))" ng-class="!row.ket.id_absen_enroll ? '' : 'btn-group'"> -->
                                        <button type="button" class="btn btn-default" style="padding: 8px 12px 6px;" ng-click="openAddEditModal(row)"><i class="fa fa-edit"></i></button>
                                        <button type="button" ng-if="<?php echo (isset($_deleted) == 1); ?>" ng-class="!row.ket.id_absen_enroll ? 'hidden' : 'btn btn-danger'" ng-click="delete(row)" style="padding: 8px 12px 6px;"><i class="fa fa-trash"></i></button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr ng-show="mc.emptyData">
                            <td colspan="7" class="text-center">Data not found.</td>
                        </tr>
                    </tbody>
                    <tbody ng-show="mc.isLoading" ng-cloak>
                        <tr>
                            <td colspan="7" class="text-center">
                                <img ng-src="{{loadingImg}}" alt="Loading..">
                            </td>
                        </tr>
                    </tbody>
                    <tbody ng-show="!mc.isLoading && isSelectSOPD && isselectedTipePegawai" ng-cloak>
                        <tr>
                            <td colspan="7" class="text-center">
                                Silakan pilih tanggal, bulan, tahun dan SOPD
                            </td>
                        </tr>
                    </tbody>
                    <tfoot ng-show="!isSelectSOPD && !isselectedTipePegawai" ng-cloak>
                        <tr>
                            <td class="text-center" st-pagination="" st-items-by-page="itemsByPage" st-displayed-pages="limit" colspan="7">
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <script type="text/ng-template" id="alert.html">
        <div ng-transclude></div>
    </script>

    <script type="text/ng-template" id="addEditModal.html">
        <div class="modal-header">
            <h3 class="modal-title">Form Absen Manual</h3>
        </div>
        <div class="modal-body">
            <div class="">
                <table class="table table-hover margin-0">
                    <tbody>

                        <tr>
                            <th width="100">Tanggal</th>
                            <td width="10">:</td>
                            <td>
                                <input type="text" class="form-control" ng-value="currentDate" readonly>
                            </td>
                        </tr>

                        <tr>
                            <th>Nama</th>
                            <td width="10">:</td>
                            <td>
                                <input type="text" class="form-control" ng-value="selected.item.PNS_PNSNAM + (selected.item.PNS_GLRBLK != '' ? ', ' + selected.item.PNS_GLRBLK : '')" readonly>
                            </td>
                        </tr>

                        <tr>
                            <th>Keterangan<span style="color: red;">*</span></th>
                            <td width="10">:</td>
                            <td>
                                <select class="form-control" ng-model="chooseKeterangan" ng-options="item as item.keterangan for item in allKET track by item.id">
                                    <option value="">- Pilih Keterangan Tidak Absen -</option>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <th>Uraian<span style="color: red;">*</span></th>
                            <td width="10">:</td>
                            <td>
                                <textarea class="form-control" ng-model="inputUraian" rows="5" style="width: 100%;"></textarea>
                            </td>
                        </tr>

                        <tr>
                            <th colspan="3"><span style="color: red;">*</span> inputan wajib diisi</th>
                        </tr>

                        <tr>
                            <td colspan="3">
                                <button type="button" ng-disabled="isDisabled" class="btn btn-primary" ng-click="submit()">Submit</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-warning" type="button" ng-click="cancel()">Close</button>
        </div>
    </script>

</section>
<!-- /.content -->

<script>
    var app = angular.module('ketidakhadiranModule', ['smart-table', 'oitozero.ngSweetAlert', 'ui.bootstrap']);

    app.factory('Resource', ['$q', '$filter', '$timeout', '$http', function($q, $filter, $timeout, $http) {

        function getPage(start, number, params, selectedSOPD = null, selectedDay = null, selectedMonth = null, selectedYear = null, selectedTipePegawai = null) {

            var deferred = $q.defer();

            var url = base_url + '/dashboard/ketidakhadiran/get-data';
            var params_custom = selectedSOPD !== null ? {
                unor: selectedSOPD.KD_UNOR,
                day: selectedDay.day,
                month: selectedMonth.month,
                year: selectedYear.year,
                tipe_pegawai: (selectedTipePegawai != null ? selectedTipePegawai.id : 'null')
            } : {};

            var getData = $http.get(url, {
                    params: params_custom
                })
                .then(function(response) {

                    recordItems = response.data;

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

    app.directive('convertNumber', function() {
        return {
            require: 'ngModel',
            link: function(scope, el, attr, ctrl) {
                ctrl.$parsers.push(function(value) {
                    return parseInt(value, 10);
                });

                ctrl.$formatters.push(function(value) {
                    return "" + value;
                });
            }
        }
    });

    app.controller('ketidakhadiranController', ['Resource', '$scope', 'SweetAlert', '$http', '$uibModal', '$timeout',
        function(service, $scope, SweetAlert, $http, $uibModal, $timeout) {

            $scope.init = function() {
                $scope.$watchGroup(['id_groups', 'unor', 'current_day', 'current_month', 'current_year', 'max_date_edit_presence', 'data_created'], function(newValues, oldValues, scope) {
                    params = {};
                    if (scope.id_groups != 1 && scope.id_groups != 5) {
                        params = {
                            params: {
                                unor: scope.unor
                            }
                        }
                        $scope.selectedSOPD = {
                            KD_UNOR: scope.unor
                        }
                        $scope.current_day = scope.current_day;
                        $scope.current_month = scope.current_month;
                        $scope.current_year = scope.current_year;
                        $scope.id_groups = scope.id_groups;
                        $scope.max_date_edit_presence = scope.max_date_edit_presence;
                        $scope.data_created = scope.data_created;
                    }
                    $http.get(base_url + '/api/get_all_sopd', params)
                        .then(function(response) {
                            $scope.allSOPD = response.data;
                        });
                    $http.get(base_url + 'api/get_tipe_pegawai')
                        .then(function(response) {
                            $scope.allTipePegawai = response.data;
                        });
                });
            }

            var ctrl = this;
            this.displayed = [];

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

            var currentDate = new Date();
            $scope.selectedDay = {
                day: currentDate.getDate()
            };

            $scope.getDaysInMonth = function() {
                var daysInMonth = moment($scope.selectedYear.year + "-" + $scope.selectedMonth.month, "YYYY-MM").daysInMonth();
                var days = [];
                for (var i = 1; i <= daysInMonth; i++) {
                    days.push({
                        day: i
                    });
                }
                return days;
            }

            $http.get(base_url + '/api/get_all_month')
                .then(function(response) {
                    $scope.allMONTH = response.data;
                });

            $http.get(base_url + '/api/get_all_year')
                .then(function(response) {
                    $scope.allYEAR = response.data;
                });

            this.callServer = function callServer(tableState) {
                ctrl.isLoading = true;
                ctrl.emptyData = false;

                var pagination = tableState.pagination;

                var start = pagination.start || 0;
                var number = pagination.number || limit;

                service.getPage(start, number, tableState, $scope.selectedSOPD, $scope.selectedDay, $scope.selectedMonth, $scope.selectedYear, $scope.selectedTipePegawai).then(function(result) {
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
                    }
                });
            };

            $scope.getSelectedSOPD = function() {
                ctrl.isStart = 0;
                $scope.$emit('reset-pagination');
                $scope.getDaysInMonth();
            };

            $scope.openAddEditModal = function(row) {
                var modal = $uibModal.open({
                    templateUrl: 'addEditModal.html',
                    controller: 'ketidakhadiranController',
                    scope: $scope,
                    resolve: {
                        item: function() {
                            return row;
                        }
                    }
                });

                $scope.items = row;
                $scope.selected = {
                    item: $scope.items
                };

                $scope.modalInstance = modal;

                $http.get(base_url + '/api/get_all_keterangan_absen')
                    .then(function(response) {
                        $scope.allKET = response.data;
                    });

                var currentDate = ($scope.selectedDay.day < 10 ? '0' + $scope.selectedDay.day : $scope.selectedDay.day) + '-' + ($scope.selectedMonth.month < 10 ? '0' + $scope.selectedMonth.month : $scope.selectedMonth.month) + '-' + $scope.selectedYear.year;
                var currentDateSave = $scope.selectedYear.year + '-' + ($scope.selectedMonth.month < 10 ? '0' + $scope.selectedMonth.month : $scope.selectedMonth.month) + '-' + ($scope.selectedDay.day < 10 ? '0' + $scope.selectedDay.day : $scope.selectedDay.day);
                $scope.currentDate = currentDate;

                $scope.chooseKeterangan = {
                    id: row.id_kehadiran
                };
                $scope.inputUraian = row.ket.uraian !== '' ? row.ket.uraian : '';

                $scope.isDisabled = false;

                modal.result.then(function(scope) {
                    $scope.isDisabled = true;

                    if (row.ket.id_absen_enroll === '') {
                        var url = base_url + '/dashboard/ketidakhadiran/add';
                        var params = {
                            id_pns: row.id_pns,
                            PNS_PNSNIP: row.PNS_PNSNIP,
                            PNS_PNSNAM: row.PNS_PNSNAM,
                            PNS_GLRDPN: row.PNS_GLRDPN,
                            PNS_GLRBLK: row.PNS_GLRBLK,
                            PNS_UNOR: row.PNS_UNOR,
                            type: 'ket',
                            tanggal: currentDateSave,
                            keterangan: scope.chooseKeterangan.id,
                            uraian: scope.inputUraian
                        };
                    } else {
                        var url = base_url + '/dashboard/ketidakhadiran/edit';
                        var params = {
                            id_absen_enroll_ket: row.ket.id_absen_enroll,
                            id_pns: row.id_pns,
                            PNS_PNSNIP: row.PNS_PNSNIP,
                            PNS_PNSNAM: row.PNS_PNSNAM,
                            PNS_GLRDPN: row.PNS_GLRDPN,
                            PNS_GLRBLK: row.PNS_GLRBLK,
                            PNS_UNOR: row.PNS_UNOR,
                            type: 'ket',
                            tanggal: currentDateSave,
                            keterangan: scope.chooseKeterangan === undefined ? row.keterangan : scope.chooseKeterangan.id,
                            uraian: scope.inputUraian === undefined ? row.ket.uraian : scope.inputUraian
                        };
                    }

                    var pushSubmit = {
                        method: 'POST',
                        url: url,
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        data: $.param(params)
                    }

                    $http(pushSubmit)
                        .then(function(response) {
                            if (response) {
                                $scope.alerts.push({
                                    "type": response.data.type,
                                    "msg": response.data.msg
                                });
                                $scope.$emit('refreshData');
                                $scope.isDisabled = false;
                                $timeout(function() {
                                    $scope.alerts = [];
                                }, 3000);
                            }
                        });
                });

                return modal.result
            };

            $scope.delete = function(row) {
                SweetAlert.swal({
                        title: "Apakah anda yakin?",
                        text: "Anda tidak akan dapat memulihkan data ini!",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Hapus!",
                        cancelButtonText: "Batal!",
                        closeOnConfirm: false,
                        closeOnCancel: false
                    },
                    function(isConfirm) {
                        if (isConfirm) {
                            var index = ctrl.displayed.indexOf(row);
                            if (index !== -1) {
                                // var id_absen_enroll = row.in.id_absen_enroll !== '' ? row.in.id_absen_enroll : (row.out.id_absen_enroll !== '' ? row.out.id_absen_enroll : (row.ket.id_absen_enroll !== '' ? row.ket.id_absen_enroll : ''));
                                var id_absen_enroll = row.ket.id_absen_enroll;
                                $http.delete("<?php echo base_url('dashboard/ketidakhadiran/delete'); ?>" + "/" + id_absen_enroll);
                                $scope.$broadcast('refreshData');
                            }
                            SweetAlert.swal("Hapus!", "Data berhasil dihapus", "success");
                        } else {
                            SweetAlert.swal("Batal", "Hapus data dibatalkan", "error");
                        }
                    });
            }

            $scope.submit = function() {
                $scope.isDisabled = true;
                $scope.modalInstance.close($scope);
            };

            $scope.cancel = function() {
                $scope.modalInstance.dismiss('cancel');
            };

        }
    ]);
</script>
