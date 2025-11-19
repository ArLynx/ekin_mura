<!-- Main content
# @Author: Awan Tengah
# @Date:   2019-08-04T19:31:18+07:00
# @Last modified by:   Awan Tengah
# @Last modified time: 2019-09-03T19:13:33+07:00
-->

<section class="content" ng-app="pegawaiModule" ng-controller="pegawaiController as mc" data-ng-init="init()" ng-cloak>

    <!-- Your Page Content Here -->
    <div class="box" ng-init="id_groups = <?php echo $id_groups; ?>; unor = <?php echo $unor; ?>" ng-cloak>
        <div class="box-header">
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
                <div class="col-md-2" ng-cloak>
                    <div class="form-group" ng-if="<?php echo (isset($_created) == 1); ?>" ng-show="!mc.isLoading && !isSelectSOPD && !isselectedTipePegawai && selectedTipePegawai.id != 0">
                        <button type="button" class="btn btn-primary" ng-click="openAddEditModal()">Tambah Pegawai</button>
                    </div>
                </div>
                <div class="col-md-3" ng-cloak>
                    <div class="form-group">
                        <div class="box-tools pull-right" ng-show="!mc.isLoading && !isSelectSOPD && !isselectedTipePegawai">
                            Showing {{mc.numbering + $index}} to {{mc.lengthFilter}} of {{mc.itemsLength}} entries
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="box-body">
            <div class="table-responsive">
                <div uib-alert ng-repeat="alert in alerts" ng-class="'alert-' + (alert.type || 'warning')" close="closeAlert($index)" ng-cloak>{{alert.msg}}</div>
                <table class="table table-hover table-bordered margin-0" st-pipe="mc.callServer" st-table="mc.displayed" st-safe-src="mc.callServer" refresh-table table-watch>
                    <thead>
                        <tr>
                            <th width="10" rowspan="2" class="th-top">No</th>
                            <th width="300" st-sort="PNS_PNSNAM">Nama</th>
                            <th width="200" rowspan="2" class="th-top">Tipe</th>
                            <th width="100" rowspan="2" class="th-top">Hari Kerja</th>
                            <th width="80" rowspan="2" class="th-top">Foto</th>
                            <th width="114" rowspan="2" class="th-top th-action">Aksi</th>
                        </tr>
                        <tr>
                            <th><input st-search="PNS_PNSNAM" placeholder="Pencarian.." class="input-sm form-control"></th>
                        </tr>
                    </thead>
                    <tbody ng-show="!mc.isLoading && !isSelectSOPD && !isselectedTipePegawai" ng-cloak>
                        <tr ng-repeat="row in mc.displayed">
                            <td>{{mc.numbering + $index}}</td>
                            <td>{{row.PNS_NAMA}}</td>
                            <td>{{row.type}}</td>
                            <td>{{row.hari_kerja + ' hari'}}</td>
                            <td><img ng-src="{{row.foto != null ? photoPath+row.foto : no_image}}" width="60" title="{{row.PNS_NAMA}}" /></td>
                            <td class="td-action">
                                <div class="btn-group btn-group-sm" role="group" aria-label="...">
                                    <button type="button" class="btn btn-success" ng-click="openDetailModal(row)" title="View">
                                        <i class="ion-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-warning" ng-click="openAddEditModal(row)" ng-if="<?php echo (isset($_updated) == 1); ?>" title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger" ng-click="removeItem(row)" ng-if="<?php echo (isset($_deleted) == 1); ?>" title="Delete">
                                        <i class="ion-trash-a"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr ng-show="mc.emptyData">
                            <td colspan="9" class="text-center">Data not found.</td>
                        </tr>
                    </tbody>
                    <tbody ng-show="mc.isLoading" ng-cloak>
                        <tr>
                            <td colspan="9" class="text-center">
                                <img ng-src="{{loadingImg}}" alt="Loading..">
                            </td>
                        </tr>
                    </tbody>
                    <tbody ng-show="!mc.isLoading && isSelectSOPD && isselectedTipePegawai" ng-cloak>
                        <tr>
                            <td colspan="9" class="text-center">
                                Silakan pilih SOPD dan Tipe Pegawai
                            </td>
                        </tr>
                    </tbody>
                    <tfoot ng-show="!isSelectSOPD && !isselectedTipePegawai" ng-cloak>
                        <tr>
                            <td class="text-center" st-pagination="" st-items-by-page="itemsByPage" st-displayed-pages="limit" colspan="9">
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <script type="text/ng-template" id="viewModal.html">
        <div class="modal-header">
            <h3 class="modal-title">Data Pegawai</h3>
        </div>
        <div class="modal-body">
            <div class="table-responsive">
                <table class="table table-hover margin-0">
                    <tbody>
                        <tr>
                            <th width="140">ID REKAM</th>
                            <td width="10">:</td>
                            <td>{{selected.item.id}}</td>
                        </tr>
                        <tr>
                            <th width="100">Tipe Pegawai</th>
                            <td width="10">:</td>
                            <td>{{selected.item.type}}</td>
                        </tr>
                        <tr>
                            <th width="100">SOPD</th>
                            <td width="10">:</td>
                            <td>{{selected.item.NM_UNOR}}</td>
                        </tr>
                        <tr ng-show="selected.item.id_tipe_pegawai == 3">
                            <th>NIP</th>
                            <td>:</td>
                            <td>{{selected.item.PNS_PNSNIP}}</td>
                        </tr>
                        <tr>
                            <th>Nama</th>
                            <td>:</td>
                            <td>{{selected.item.PNS_NAMA}}</td>
                        </tr>
                        <tr>
                            <th>Agama</th>
                            <td>:</td>
                            <td>{{selected.item.agama}}</td>
                        </tr>
                        <tr>
                            <th>Tempat Lahir</th>
                            <td>:</td>
                            <td>{{selected.item.tempat_lahir}}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Lahir</th>
                            <td>:</td>
                            <td>{{selected.item.tanggal_lahir}}</td>
                        </tr>
                        <tr>
                            <th>Hari Kerja</th>
                            <td>:</td>
                            <td>{{selected.item.hari_kerja + ' hari'}}</td>
                        </tr>
                        <tr>
                            <th>Alamat</th>
                            <td>:</td>
                            <td>{{selected.item.alamat}}</td>
                        </tr>
                        <tr>
                            <th colspan="3">
                                <div class="clearfix image-attecment">
                                    <label><strong>Foto</strong></label><br>
                                    <img class="attachment-img" ng-src="{{selected.item.foto != null ? photoPath+selected.item.foto : no_image}}" width="250" title="{{selected.item.PNS_PNSNAM + (selected.item.PNS_GLRBLK != '' ? ', ' + selected.item.PNS_GLRBLK : '')}}" />
                                </div>
                            </th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-warning" type="button" ng-click="cancel()">Close</button>
        </div>
    </script>

    <script type="text/ng-template" id="addEditModal.html">
        <div class="modal-header">
            <h3 class="modal-title" ng-if="selected.item === undefined">Tambah Data Pegawai</h3>
            <h3 class="modal-title" ng-if="selected.item !== undefined">Ubah Data Pegawai</h3>
        </div>
        <div class="modal-body">
            <div class="">
                <table class="table table-hover margin-0">
                    <tbody>
                        <tr>
                            <th width="130">SOPD<span style="color: red;">*</span></th>
                            <td width="10">:</td>
                            <td>
                                <select class="form-control" ng-model="sopd" ng-init="sopd = selectedSOPD || pegawaiSOPD" ng-options="item as item.NM_UNOR for item in allSOPD track by item.KD_UNOR">
                                    <option value="">- Pilih SOPD -</option>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <th width="130">Tipe Pegawai<span style="color: red;">*</span></th>
                            <td width="10">:</td>
                            <td>
                                <select class="form-control" ng-model="tipe_pegawai" ng-init="tipe_pegawai = {id: selected.item.id_tipe_pegawai}" ng-options="item as item.type for item in allTipePegawai.data track by item.id">
                                    <option value="">- Pilih Tipe Pegawai -</option>
                                </select>
                            </td>
                        </tr>

                        <tr ng-show="tipe_pegawai.id == 3">
                            <th>NIP<span style="color: red;">*</span></th>
                            <td width="10">:</td>
                            <td><input type="text" ng-model="nip" class="form-control" placeholder="NIP" ng-value="selected.item.PNS_PNSNIP"></td>
                        </tr>

                        <tr>
                            <th>Gelar Depan</th>
                            <td width="10">:</td>
                            <td><input type="text" ng-model="gelar_depan" class="form-control" placeholder="Gelar Depan" ng-value="selected.item.PNS_GLRDPN"></td>
                        </tr>

                        <tr>
                            <th>Nama<span style="color: red;">*</span></th>
                            <td width="10">:</td>
                            <td><input type="text" ng-model="nama" class="form-control" placeholder="Nama" ng-value="selected.item.PNS_PNSNAM"></td>
                        </tr>
                        <tr>
                            <th>Gelar Belakang</th>
                            <td width="10">:</td>
                            <td><input type="text" ng-model="gelar_belakang" class="form-control" placeholder="Gelar Belakang" ng-value="selected.item.PNS_GLRBLK"></td>
                        </tr>
                        <tr>
                            <th>Agama<span style="color: red;">*</span></th>
                            <td width="10">:</td>
                            <td>
                                <select class="form-control" ng-model="agama" ng-init="agama = {id: selected.item.id_master_agama}" ng-options="item as item.agama for item in allAgama track by item.id">
                                    <option value="">- Pilih Agama -</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>Tempat Lahir<span style="color: red;">*</span></th>
                            <td width="10">:</td>
                            <td><input type="text" ng-model="tempat_lahir" class="form-control" placeholder="Tempat Lahir" ng-value="selected.item.tempat_lahir"></td>
                        </tr>
                        <tr>
                            <th>Tanggal Lahir<span style="color: red;">*</span></th>
                            <td width="10">:</td>
                            <td>
                                <div class="input-group"
                                    moment-picker="tanggal_lahir"
                                    locale="id"
                                    format="DD-MM-YYYY">
                                    <span class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                    <input class="form-control"
                                        placeholder="Pilih Tanggal Lahir"
                                        ng-model="tanggal_lahir"
                                        ng-model-options="{ updateOn: 'blur' }"
                                        ng-value="selected.item.tanggal_lahir">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>Hari Kerja<span style="color: red;">*</span></th>
                            <td width="10">:</td>
                            <td>
                                <select class="form-control" ng-model="hari_kerja" ng-init="hari_kerja = selected.item.hari_kerja">
                                    <option value="">- Pilih Hari Kerja -</option>
                                    <option value="5">5 Hari</option>
                                    <option value="6">6 Hari</option>
                                    <option value="7">7 Hari</option>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <th>Alamat<span style="color: red;">*</span></th>
                            <td width="10">:</td>
                            <td><textarea ng-model="alamat" class="form-control" placeholder="Alamat" rows="6" cols="10"></textarea></td>
                        </tr>

                        <tr>
                            <th colspan="3">
                                <div class="clearfix image-attecment">
                                    <label><strong>Foto</strong></label><br>
                                    <img class="attachment-img img-fluid" ng-src="{{ thumbnail.dataUrl }}" width="250"/>
                                </div>
                            </th>
                        </tr>

                        <tr>
                            <th>Foto (Baju dinas, maksimal ukuran foto 100kb, <span style="color: red;">jpg, jpeg, png</span>)<span style="color: red;">*</span></th>
                            <td width="10">:</td>
                            <td>
                                <div class="form-inline">
                                    <input type="file" name="file" id="file" onchange="angular.element(this).scope().photoChanged(this.files)" />
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <th colspan="3"><span style="color: red;">*</span> inputan wajib diisi</th>
                        </tr>

                        <tr>
                            <td colspan="3">
                                <button type="button" ng-disabled="" class="btn btn-primary" ng-click="submit()">Submit</button>
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

    <script type="text/ng-template" id="alert.html">
        <div ng-transclude></div>
    </script>

</section>
<!-- /.content -->

<script>
    var app = angular.module('pegawaiModule', ['smart-table', 'oitozero.ngSweetAlert', 'ui.bootstrap', 'moment-picker']);

    app.factory('Resource', ['$q', '$filter', '$timeout', '$http', function($q, $filter, $timeout, $http) {

        function getPage(start, number, params, selectedSOPD = null, selectedTipePegawai = null) {

            var deferred = $q.defer();

            var url = base_url + '/dashboard/pegawai/get-data';
            var params_custom = selectedSOPD !== null ? {
                unor: selectedSOPD.KD_UNOR,
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

    app.filter("htmlSafe", ['$sce', function($sce) {
        return function(htmlCode) {
            return $sce.trustAsHtml(htmlCode);
        };
    }]);

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

    app.controller('pegawaiController', ['Resource', '$scope', 'SweetAlert', '$http', '$uibModal', '$timeout', '$parse',
        function(service, $scope, SweetAlert, $http, $uibModal, $timeout, $parse) {

            $scope.init = function() {
                $scope.$watchGroup(['id_groups', 'unor'], function(newValues, oldValues, scope) {
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
                    }

                    $http.get(base_url + '/api/get_all_sopd', params)
                        .then(function(response) {
                            $scope.allSOPD = response.data;
                        });

                    $http.get(base_url + 'api/get_tipe_pegawai', {params: {is_tpp: false}})
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
            $scope.photoPath = base_url + '/assets/img/upload/user/';
            $scope.no_image = base_url + '/assets/img/no_image.png';

            ctrl.isStart = 0;

            $scope.selectedSOPD = '';
            $scope.isSelectSOPD = true;

            $scope.selectedTipePegawai = '';
            $scope.isselectedTipePegawai = true;

            this.callServer = function callServer(tableState) {
                ctrl.isLoading = true;
                ctrl.emptyData = false;

                var pagination = tableState.pagination;

                var start = pagination.start || 0;
                var number = pagination.number || limit;

                service.getPage(start, number, tableState, $scope.selectedSOPD, $scope.selectedTipePegawai).then(function(result) {
                    ctrl.isStart += number;
                    ctrl.displayed = result.data;
                    ctrl.itemsLength = result.itemsLength;
                    ctrl.emptyData = ctrl.displayed.length > 0 ? false : true;
                    ctrl.numbering = ctrl.emptyData == true ? start : start + 1;
                    ctrl.lengthFilter = ctrl.emptyData == true ? 0 : (ctrl.numbering - 1 + ctrl.displayed.length);
                    tableState.pagination.numberOfPages = result.numberOfPages;
                    ctrl.isLoading = false;

                    if (!$scope.selectedSOPD && $scope.selectedSOPD == '') {
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
                    controller: 'pegawaiController',
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

                $http.get(base_url + '/api/get_all_agama')
                    .then(function(response) {
                        $scope.allAgama = response.data;
                    });

                $scope.sopd = $scope.selectedSOPD;

                $scope.isDisabled = $scope.selected.item !== undefined ? false : true;
                $scope.isEdit = $scope.selected.item === undefined ? false : true;
                $scope.alamat = $scope.selected.item !== undefined ? $scope.selected.item.alamat : '';

                if (row !== undefined) {
                    $scope.thumbnail = {
                        dataUrl: row.foto != null ? $scope.photoPath + row.foto : $scope.no_image
                    };
                    $scope.pegawaiSOPD = $scope.selectedSOPD !== undefined ? $scope.selectedSOPD : {
                        KD_UNOR: row.KD_UNOR,
                        NM_UNOR: row.NM_UNOR
                    };
                } else {
                    $scope.thumbnail = {
                        dataUrl: base_url + '/assets/img/no_image.png'
                    };
                }

                modal.result.then(function(scope) {
                    $scope.isDisabled = true;
                    if ($scope.isEdit === false) {
                        var fd = new FormData();
                        var files = document.getElementById('file').files[0];
                        fd.append('file', files);
                        fd.append('sopd', scope.sopd.KD_UNOR != undefined ? scope.sopd.KD_UNOR : '');
                        fd.append('tipe_pegawai', scope.tipe_pegawai.id != undefined ? scope.tipe_pegawai.id : '');
                        fd.append('gelar_depan', scope.gelar_depan != undefined ? scope.gelar_depan : '');
                        fd.append('nama', scope.nama != undefined ? scope.nama : '');
                        fd.append('gelar_belakang', scope.gelar_belakang != undefined ? scope.gelar_belakang : '');
                        fd.append('agama', scope.agama.id != undefined ? scope.agama.id : '');
                        fd.append('tempat_lahir', scope.tempat_lahir != undefined ? scope.tempat_lahir : '');
                        fd.append('tanggal_lahir', scope.tanggal_lahir != undefined ? scope.tanggal_lahir : '');
                        fd.append('hari_kerja', scope.hari_kerja != undefined ? scope.hari_kerja : '');
                        fd.append('alamat', scope.alamat != undefined ? scope.alamat : '');
                        if(scope.tipe_pegawai.id == '3') {
                            fd.append('nip', scope.nip);
                        }
                        var pushSubmit = {
                            method: 'POST',
                            url: base_url + '/dashboard/pegawai/add/non-pns',
                            headers: {
                                'Content-Type': undefined
                            },
                            data: fd,
                            transformRequest: angular.identity
                        }
                    } else {
                        var fd = new FormData();
                        var files = document.getElementById('file').files[0];
                        let getTipePegawai = scope.tipe_pegawai === undefined ? row.tipe_pegawai : scope.tipe_pegawai.id;
                        fd.append('id_tkd', row.id);
                        fd.append('file', files);
                        fd.append('sopd', scope.sopd === undefined ? row.sopd : scope.sopd.KD_UNOR);
                        fd.append('tipe_pegawai', getTipePegawai);
                        fd.append('gelar_depan', scope.gelar_depan === undefined ? (row.PNS_GLRDPN != null ? row.PNS_GLRDPN : '') : scope.gelar_depan);
                        fd.append('nama', scope.nama === undefined ? row.PNS_PNSNAM : scope.nama);
                        fd.append('gelar_belakang', scope.gelar_belakang === undefined ? (row.PNS_GLRBLK != null ? row.PNS_GLRBLK : '') : scope.gelar_belakang);
                        fd.append('agama', scope.agama === undefined ? row.agama : scope.agama.id);
                        fd.append('tempat_lahir', scope.tempat_lahir === undefined ? row.tempat_lahir : scope.tempat_lahir);
                        fd.append('tanggal_lahir', scope.tanggal_lahir === undefined ? row.tanggal_lahir : scope.tanggal_lahir);
                        fd.append('hari_kerja', scope.hari_kerja === undefined ? row.hari_kerja : scope.hari_kerja);
                        fd.append('alamat', scope.alamat === undefined ? row.alamat : scope.alamat);
                        if(getTipePegawai == '3') {
                            fd.append('nip', scope.nip === undefined ? row.PNS_PNSNIP : scope.nip);
                        }
                        var pushSubmit = {
                            method: 'POST',
                            url: base_url + '/dashboard/pegawai/edit',
                            headers: {
                                'Content-Type': undefined
                            },
                            data: fd,
                            transformRequest: angular.identity
                        }
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
                                }, 5000);
                            }
                        });
                });

                return modal.result
            };

            $scope.fileReaderSupported = window.FileReader != null;
            $scope.photoChanged = function(files) {
                if (files != null) {
                    var file = files[0];
                    if ($scope.fileReaderSupported && file.type.indexOf('image') > -1) {
                        $timeout(function() {
                            var fileReader = new FileReader();
                            fileReader.readAsDataURL(file);
                            fileReader.onload = function(e) {
                                $timeout(function() {
                                    $scope.thumbnail.dataUrl = e.target.result;
                                });
                            }
                        });
                    }
                }
            };

            $scope.removeItem = function removeItem(row) {
                SweetAlert.swal({
                        title: "Apakah anda yakin?",
                        text: "Anda tidak akan dapat memulihkan pegawai ini!",
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
                                $http.delete(base_url + "dashboard/pegawai/delete" + "/" + row.id);
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
