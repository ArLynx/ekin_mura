<!-- Main content -->
<section class="content" ng-app="mgjkModule" ng-controller="mgjkController as mc">

    <!-- Your Page Content Here -->
    <div class="box">
        <div class="box-header">
            <div class="row">
                <div class="col-md-6">
                    <button type="button" class="btn btn-primary" ng-click="openAddEditModal()">Tambah</button>
                </div>
                <div class="col-md-6">
                    <div class="box-tools pull-right" ng-cloak>
                        Showing {{mc.numbering + $index}} to {{mc.lengthFilter}} of {{mc.itemsLength}} entries
                    </div>
                </div>
            </div>
        </div>
        <div class="box-body">
            <div class="table-responsive">
                <div uib-alert ng-repeat="alert in alerts" ng-class="'alert-' + (alert.type || 'warning')" close="closeAlert($index)" ng-cloak>{{alert.msg}}</div>
                <table class="table table-hover table-bordered margin-0" st-pipe="mc.callServer" st-table="mc.displayed" st-safe-src="mc.callServer" refresh-table>
                    <thead>
                        <tr>
                            <th width="10">No</th>
                            <th st-sort="group_name">Nama Group</th>
                            <th st-sort="NM_UNOR">Penggunaan SOPD</th>
                            <th width="350">Jam Kerja</th>
                            <th width="40" st-sort="order">Prioritas</th>
                            <th width="150" rowspan="2" class="th-top th-action">Aksi</th>
                        </tr>
                    </thead>
                    <tbody ng-show="!mc.isLoading" ng-cloak>
                        <tr ng-repeat="row in mc.displayed">
                            <td>{{mc.numbering + $index}}</td>
                            <td>{{row.group_name}}</td>
                            <td>{{row.NM_UNOR}}</td>
                            <td>
                                <span ng-if="row.jam_shift1 != ''">Group 1: {{row.jam_shift1}}</span>
                                <span ng-if="row.jam_shift2 != ''"><br>Group 2: {{row.jam_shift2}}</span>
                                <span ng-if="row.jam_shift3 != ''"><br>Group 3: {{row.jam_shift3}}</span>
                            </td>
                            <td>{{row.order}}</td>
                            <td class="td-action">
                                <div class="btn-group btn-group-md" role="group" aria-label="...">
                                    <button type="button" class="btn btn-success" ng-click="openDetailModal(row)" title="Detail">
                                        <i class="ion-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-warning" ng-click="openAddEditModal(row)" title="Ubah">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger" ng-click="delete(row)" title="Hapus">
                                        <i class="ion-trash-a"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr ng-show="mc.emptyData">
                            <td colspan="6" class="text-center">Data not found.</td>
                        </tr>
                    </tbody>
                    <tbody ng-show="mc.isLoading" ng-cloak>
                        <tr>
                            <td colspan="6" class="text-center">
                                <img ng-src="{{loadingImg}}" alt="Loading..">
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="text-center" st-pagination="" st-items-by-page="limit" colspan="6">
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <script type="text/ng-template" id="viewModal.html">
        <div class="modal-header">
            <h3 class="modal-title">Group Jam Kerja</h3>
        </div>
        <div class="modal-body">
            <div class="table-responsive">
                <table class="table table-hover margin-0">
                    <tbody>
                        <tr>
                            <th width="100">Nama Group</th>
                            <td width="10">:</td>
                            <td>{{selected.item.group_name}}</td>
                        </tr>
                        <tr>
                            <th width="100">Prioritas</th>
                            <td width="10">:</td>
                            <td>{{selected.item.order}}</td>
                        </tr>
                        <tr ng-if="selected.item.jam_shift1 != ''">
                            <th width="100">Group 1</th>
                            <td width="10">:</td>
                            <td>{{selected.item.jam_shift1}}</td>
                        </tr>
                        <tr ng-if="selected.item.jam_shift2 != ''">
                            <th width="100">Group 2</th>
                            <td width="10">:</td>
                            <td>{{selected.item.jam_shift2}}</td>
                        </tr>
                        <tr ng-if="selected.item.jam_shift3 != ''">
                            <th width="100">Group 3</th>
                            <td width="10">:</td>
                            <td>{{selected.item.jam_shift3}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-warning" type="button" ng-click="cancel()">Tutup</button>
        </div>
    </script>

    <script type="text/ng-template" id="addEditModal.html">
        <div class="modal-header">
            <h3 class="modal-title">Tambah Group Jam Kerja</h3>
        </div>
        <div class="modal-body">
            <div class="">
                <table class="table table-hover margin-0">
                    <tbody>
                        <tr>
                            <th>SOPD</th>
                            <td width="10">:</td>
                            <td>
                                <select class="form-control" ng-model="unor" ng-init="unor = {kd_unor: selected.item.unor}" ng-options="item as item.NM_UNOR for item in allSOPD track by item.KD_UNOR">
                                    <option value="">- Pilih SOPD -</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>Nama Group<span style="color: red;">*</span></th>
                            <td width="10">:</td>
                            <td>
                                <input type="text" ng-model="group_name" class="form-control">
                            </td>
                        </tr>
                        <tr>
                            <th>Prioritas</th>
                            <td width="10">:</td>
                            <td>
                                <input type="text" ng-model="order" class="form-control">
                            </td>
                        </tr>
                        <tr>
                            <th>Jam Shift 1<span ng-if="!selected.item.id" style="color: red;">*</span></th>
                            <td width="10">:</td>
                            <td>
                                <select class="form-control" ng-model="shift1" ng-init="shift1 = {id: selected.item.shift1}" ng-options="item as ('Masuk: ' + item.jam_masuk + ' | Pulang: ' + item.jam_pulang) for item in allJamKerja track by item.id">
                                    <option value="">- Pilih Shift 1 -</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>Jam Shift 2<br><span style="color: red;">Diisi bila ada jam shift 2</span></th>
                            <td width="10">:</td>
                            <td>
                                <select class="form-control" ng-model="shift2" ng-init="shift2 = {id: selected.item.shift2}" ng-options="item as ('Masuk: ' + item.jam_masuk + ' | Pulang: ' + item.jam_pulang) for item in allJamKerja track by item.id">
                                    <option value="">- Pilih Shift 2 -</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>Jam Shift 3<br><span style="color: red;">Diisi bila ada jam shift 3</span></th>
                            <td width="10">:</td>
                            <td>
                                <select class="form-control" ng-model="shift3" ng-init="shift3 = {id: selected.item.shift3}" ng-options="item as ('Masuk: ' + item.jam_masuk + ' | Pulang: ' + item.jam_pulang) for item in allJamKerja track by item.id">
                                    <option value="">- Pilih Shift 3 -</option>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <th colspan="3"><span style="color: red;">*</span> inputan wajib diisi</th>
                        </tr>

                        <tr>
                            <td colspan="3">
                                <button type="button" ng-disabled="isDisabled" class="btn btn-primary" ng-click="submit()">Simpan</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-warning" type="button" ng-click="cancel()">Tutup</button>
        </div>
    </script>

    <script type="text/ng-template" id="alert.html">
        <div ng-transclude></div>
    </script>

</section>
<!-- /.content -->

<script>
    var app = angular.module('mgjkModule', ['smart-table', 'oitozero.ngSweetAlert', 'ui.bootstrap', 'moment-picker']);

    app.factory('Resource', ['$q', '$filter', '$timeout', '$http', function($q, $filter, $timeout, $http) {

        function getPage(start, number, params) {

            var deferred = $q.defer();

            var url = base_url + '/dashboard/master-group-jam-kerja/get-data';

            var getData = $http.get(url)
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

    app.controller('mgjkController', ['Resource', '$scope', 'SweetAlert', '$http', '$uibModal', '$timeout',
        function(service, $scope, SweetAlert, $http, $uibModal, $timeout) {

            var ctrl = this;
            this.displayed = [];

            $scope.loadingImg = base_url + '/assets/img/loading.svg';

            $scope.alerts = [];
            $scope.closeAlert = function(index) {
                $scope.alerts.splice(index, 1);
            };

            this.callServer = function callServer(tableState) {
                ctrl.isLoading = true;
                ctrl.emptyData = false;

                var pagination = tableState.pagination;

                var start = pagination.start || 0;
                var number = pagination.number || limit;

                service.getPage(start, number, tableState, $scope.selectedSOPD).then(function(result) {
                    ctrl.displayed = result.data;
                    ctrl.itemsLength = result.itemsLength;
                    ctrl.emptyData = ctrl.displayed.length > 0 ? false : true;
                    ctrl.numbering = ctrl.emptyData == true ? start : start + 1;
                    ctrl.lengthFilter = ctrl.emptyData == true ? 0 : (ctrl.numbering - 1 + ctrl.displayed.length);
                    tableState.pagination.numberOfPages = result.numberOfPages;
                    ctrl.isLoading = false;
                });
            };

            $scope.openDetailModal = function(row) {
                var modal = $uibModal.open({
                    templateUrl: 'viewModal.html',
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

                return modal.result
            };

            $scope.openAddEditModal = function(row) {
                var modal = $uibModal.open({
                    templateUrl: 'addEditModal.html',
                    controller: 'mgjkController',
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

                $http.get(base_url + '/api/get_all_jam_kerja')
                    .then(function(response) {
                        $scope.allJamKerja = response.data;
                    });

                $http.get(base_url + '/api/get_all_sopd')
                    .then(function(response) {
                        $scope.allSOPD = response.data;
                    });

                $scope.group_name = row ? row.group_name : '';
                $scope.order = row ? row.order : '';

                $scope.isDisabled = false;

                modal.result.then(function(scope) {
                    $scope.isDisabled = true;
                    if (!row) {
                        var url = base_url + '/dashboard/master-group-jam-kerja/add';
                        var params = {
                            unor: scope.unor !== null ? scope.unor.KD_UNOR : '',
                            group_name: scope.group_name,
                            order: scope.order,
                            shift1: scope.shift1 !== null ? scope.shift1.id : '',
                            shift2: scope.shift2 !== null ? scope.shift2.id : '',
                            shift3: scope.shift3 !== null ? scope.shift3.id : '',
                        };
                    } else {
                        var url = base_url + '/dashboard/master-group-jam-kerja/edit';
                        var params = {
                            id_master_group_jam_kerja: row.id,
                            unor: scope.unor !== null ? scope.unor.KD_UNOR : '',
                            group_name: scope.group_name,
                            order: scope.order,
                            shift1: scope.shift1 !== null ? scope.shift1.id : '',
                            shift2: scope.shift2 !== null ? scope.shift2.id : '',
                            shift3: scope.shift3 !== null ? scope.shift3.id : '',
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
                                $http.delete("<?php echo base_url('dashboard/master_group_jam_kerja/delete'); ?>" + "/" + row.id);
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