<!-- Main content -->
<section class="content" ng-app="mjkModule" ng-controller="mjkController as mc">

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
                            <th st-sort="jam_masuk">Jam Masuk</th>
                            <th st-sort="jam_pulang">Jam Pulang</th>
                            <th width="350">Master Group Jam Kerja</th>
                            <th width="114" rowspan="2" class="th-top th-action">Aksi</th>
                        </tr>
                    </thead>
                    <tbody ng-show="!mc.isLoading" ng-cloak>
                        <tr ng-repeat="row in mc.displayed">
                            <td>{{mc.numbering + $index}}</td>
                            <td>{{row.jam_masuk}}</td>
                            <td>{{row.jam_pulang}}</td>
                            <td>
                                <table>
                                    <tr ng-repeat="childrow in row.group_name">
                                        <td>{{childrow.ket + ': ' + childrow.group}}</td>
                                    </tr>
                                </table>
                            </td>
                            <td class="td-action">
                                <div class="btn-group btn-group-md" role="group" aria-label="...">
                                    <button type="button" class="btn btn-success" ng-click="openDetailModal(row)" title="Detail">
                                        <i class="ion-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-warning" ng-click="openAddEditModal(row)" title="Ubah">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr ng-show="mc.emptyData">
                            <td colspan="5" class="text-center">Data not found.</td>
                        </tr>
                    </tbody>
                    <tbody ng-show="mc.isLoading" ng-cloak>
                        <tr>
                            <td colspan="5" class="text-center">
                                <img ng-src="{{loadingImg}}" alt="Loading..">
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="text-center" st-pagination="" st-items-by-page="limit" colspan="5">
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <script type="text/ng-template" id="viewModal.html">
        <div class="modal-header">
            <h3 class="modal-title">Jam Kerja</h3>
        </div>
        <div class="modal-body">
            <div class="table-responsive">
                <table class="table table-hover margin-0">
                    <tbody>
                        <tr>
                            <th width="100">Jam Masuk</th>
                            <td width="10">:</td>
                            <td>{{selected.item.jam_masuk}}</td>
                        </tr>
                        <tr>
                            <th width="100">Jam Pulang</th>
                            <td width="10">:</td>
                            <td>{{selected.item.jam_masuk}}</td>
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
            <h3 class="modal-title">Tambah Jam Kerja</h3>
        </div>
        <div class="modal-body">
            <div class="">
                <table class="table table-hover margin-0">
                    <tbody>
                        <tr>
                            <th>Jam Masuk<span style="color: red;">*</span></th>
                            <td width="10">:</td>
                            <td>
                                <div class="input-group"
                                    moment-picker="jam_masuk"
                                    locale="id"
                                    format="HH:mm">
                                    <span class="input-group-addon">
                                        <i class="ion-clock"></i>
                                    </span>
                                    <input class="form-control"
                                        placeholder="Pilih jam masuk"
                                        ng-model="jam_masuk"
                                        ng-model-options="{ updateOn: 'blur' }"
                                        ng-value="selected.item.jam_masuk">
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <th>Jam Pulang<span style="color: red;">*</span></th>
                            <td width="10">:</td>
                            <td>
                                <div class="input-group"
                                    moment-picker="jam_pulang"
                                    locale="id"
                                    format="HH:mm">
                                    <span class="input-group-addon">
                                        <i class="ion-clock"></i>
                                    </span>
                                    <input class="form-control"
                                        placeholder="Pilih jam pulang"
                                        ng-model="jam_pulang"
                                        ng-model-options="{ updateOn: 'blur' }"
                                        ng-value="selected.item.jam_pulang">
                                </div>
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
    var app = angular.module('mjkModule', ['smart-table', 'oitozero.ngSweetAlert', 'ui.bootstrap', 'moment-picker']);

    app.factory('Resource', ['$q', '$filter', '$timeout', '$http', function($q, $filter, $timeout, $http) {

        function getPage(start, number, params) {

            var deferred = $q.defer();

            var url = base_url + '/dashboard/master-jam-kerja/get-data';

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

    app.controller('mjkController', ['Resource', '$scope', 'SweetAlert', '$http', '$uibModal', '$timeout',
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
                    controller: 'mjkController',
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

                modal.result.then(function(scope) {
                    $scope.isDisabled = true;
                    if(!row) {
                        var url = base_url + '/dashboard/master-jam-kerja/add';
                        var params = {
                            jam_masuk: scope.jam_masuk,
                            jam_pulang: scope.jam_pulang,
                        };
                    } else {
                        var url = base_url + '/dashboard/master-jam-kerja/edit';
                        var params = {
                            id_master_jam_kerja: row.id,
                            jam_masuk: scope.jam_masuk ? scope.jam_masuk : row.jam_masuk,
                            jam_pulang: scope.jam_pulang ? scope.jam_pulang : row.jam_pulang,
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

            $scope.submit = function() {
                $scope.isDisabled = true;
                $scope.modalInstance.close($scope);
            };

            $scope.cancel = function() {
                $scope.modalInstance.dismiss('cancel');
            };

            $scope.toString = function(val) {
                return val.join(", ");
            }

        }
    ]);
</script>
