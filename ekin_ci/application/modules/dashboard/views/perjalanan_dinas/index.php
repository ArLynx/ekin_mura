<!-- Main content
# @Author: Awan Tengah
# @Date:   2019-08-12T08:21:25+07:00
# @Last modified by:   Awan Tengah
# @Last modified time: 2019-09-03T18:49:37+07:00
-->

<section class="content" ng-app="perjalananDinasModule" ng-controller="perjalananDinasController as mc">

    <!-- Your Page Content Here -->
    <div class="box">
        <div class="box-header">
            <div class="row" ng-cloak>
                <div class="col-md-6" ng-if="<?php echo (isset($_created) == 1); ?>">
                    <a href="<?php echo site_url('dashboard/perjalanan-dinas/add'); ?>" class="btn btn-primary">Tambah</a>
                </div>
                <div class="col-md-6">
                    <div class="box-tools pull-right" ng-cloak>
                        Showing {{mc.numbering + $index}} to {{mc.lengthFilter}} of {{mc.itemsLength}} entries
                    </div>
                </div>
            </div>
        </div>
        <div class="box-body">
            <?php echo alert_message_dashboard(); ?>
            <div class="table-responsive">
                <table class="table table-hover table-bordered margin-0" st-pipe="mc.callServer" st-table="mc.displayed" st-safe-src="mc.callServer" refresh-table>
                    <thead>
                        <tr>
                            <th width="10">No</th>
                            <th width="150">No Surat</th>
                            <th>Perihal</th>
                            <th width="200">Lama Perjalanan</th>
                            <th width="114" class="th-top th-action">Aksi</th>
                        </tr>
                    </thead>
                    <tbody ng-show="!mc.isLoading" ng-cloak>
                        <tr ng-repeat="row in mc.displayed">
                            <td>{{mc.numbering + $index}}</td>
                            <td>{{row.no_surat}}</td>
                            <td>{{row.maksud_perjalanan}}</td>
                            <td>{{row.lama_perjalanan}}</td>
                            <td class="td-action">
                                <div class="btn-group btn-group-md" role="group" aria-label="...">
                                    <!-- <button type="button" class="btn btn-success" ng-click="openDetailModal(row)" title="View">
                                        <i class="ion-eye"></i>
                                    </button> -->
                                    <a href="<?php echo site_url('dashboard/perjalanan-dinas/edit/{{row.id}}'); ?>" ng-if="<?php echo (isset($_updated) == 1); ?>" class="btn btn-warning" title="Ubah">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <button type="button" ng-if="<?php echo (isset($_deleted) == 1); ?>" class="btn btn-danger" ng-click="delete(row)" title="Hapus">
                                        <i class="ion-trash-a"></i>
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
            <h3 class="modal-title">Surat Perjalanan Dinas</h3>
        </div>
        <div class="modal-body">

                <div class="box-body">
                    <table>
                        <tr>
                            <td><img src="<?php echo base_url('assets/img/Lambang_Kabupaten_Kotawaringin_Barat.png'); ?>" width="50"></td>
                            <td colspan="2" class="text-center" style="width: 100%;">
                                PEMERINTAH KABUPATEN KOTAWARINGIN BARAT<br>
                                {{selected.item.NM_UNOR | uppercase}}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" style="border-bottom: 1px solid #ccc; padding-top: 1em;"></td>
                        </tr>
                        <tr>
                            <th colspan="3" class="text-center" style="padding-top: 1em;">
                                <p>
                                    SURAT PERJALANAN DINAS<br>
                                    Nomor: {{selected.item.no_surat}}
                                </p>
                            </th>
                        </tr>
                        <tr>
                            <td style="vertical-align: initial; min-width: 75px;">Dasar</td>
                            <td style="vertical-align: initial; min-width: 30px;">:</td>
                            <td>
                                <ol style="padding-left: 1em;">
                                    <li>Peraturan Menteri Dalam Negeri No. 13 Tahun 2006 tentang Pedoman Pengelolaan Keuangan Daerah.</li>
                                    <li>Peraturan Menteri Keuangan Nomor 133/PMK.05/2012 Tentang Perjalanan Dinas Dalam Negeri bagi Pejabat Negara, Pegawai Negeri Sipil dan Pegawai Tidak Tetap.</li>
                                    <li>Peraturan Bupati Kotawaringin Barat Nomor 1 Tahun 2016 Tentang Perjalanan Dinas Dalam Negeri Bagi Pejabat Negara, Pegawai Negeri dan Pegawai Tidak Tetap di Lingkungan Pemerintah Kabupaten Kotawaringin Barat.</li>
                                    <li>{{selected.item.dasar_penugasan}}</li>
                                </ol>
                            </td>
                        </tr>
                        <tr>
                            <th colspan="3" class="text-center">
                                <p>MEMERINTAHKAN :</p>
                            </th>
                        </tr>
                        <tr>
                            <td style="vertical-align: initial;">Kepada</td>
                            <td style="vertical-align: initial;">:</td>
                            <td>
                                <table>
                                    <tr ng-repeat-start="user in userList">
                                        <td width="15">{{$index+1+'.'}}</td>
                                        <td width="130">Nama</td>
                                        <td width="15">:</td>
                                        <td>{{user.PNS_NAMA}}</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>NIP</td>
                                        <td>:</td>
                                        <td>{{user.PNS_PNSNIP}}</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Pangkat/Golongan</td>
                                        <td>:</td>
                                        <td ng-if="user.NM_PKT != '-' && user.NM_GOL != '-'">{{user.NM_PKT + '/' + user.NM_GOL}}</td>
                                        <td ng-if="user.NM_PKT == '-' && user.NM_GOL == '-'">-</td>
                                    </tr>
                                    <tr ng-repeat-end>
                                        <td></td>
                                        <td>Jabatan</td>
                                        <td>:</td>
                                        <td>{{user.NM_GENPOS}}</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="vertical-align: initial; min-width: 75px;">Untuk</td>
                            <td style="vertical-align: initial; min-width: 30px;">:</td>
                            <td>{{selected.item.keperluan}}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td>
                                <table>
                                    <tr>
                                        <td width="15">1</td>
                                        <td>Lama penugasan selama {{selected.item.lama_penugasan}}</td>
                                    </tr>
                                    <tr>
                                        <td width="15">2</td>
                                        <td>Perintah ini dilaksanakan dengan penuh tanggung jawab.</td>
                                    </tr>
                                    <tr>
                                        <td width="15">3</td>
                                        <td>Melaporkan hasil pelaksanaan tugas kepada Kepala Dinas / Instansi.</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td class="pull-right" style="margin-top: 1em;">
                                <div class="text-center">
                                    Dikeluarkan di Pangkalan Bun<br>
                                    Pada tanggal {{formatDate(selected.item.created_at) | date: 'dd MMMM yyyy'}}<br>
                                    {{penandatanganList[0].NM_GENPOS}}<br><br><br>
                                    {{penandatanganList[0].PNS_NAMA}}<br>
                                    NIP. {{penandatanganList[0].PNS_PNSNIP}}
                                </div>
                            </td>
                        </tr>
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
    var app = angular.module('perjalananDinasModule', ['smart-table', 'oitozero.ngSweetAlert', 'ui.bootstrap']);

    app.factory('Resource', ['$q', '$filter', '$timeout', '$http', function($q, $filter, $timeout, $http) {

        function getPage(start, number, params) {

            var deferred = $q.defer();

            var url = base_url + '/dashboard/perjalanan-dinas/get-data';

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

    app.controller('perjalananDinasController', ['Resource', '$scope', 'SweetAlert', '$http', '$uibModal', '$timeout',
        function(service, $scope, SweetAlert, $http, $uibModal, $timeout) {

            var ctrl = this;
            this.displayed = [];

            $scope.loadingImg = base_url + '/assets/img/loading.svg';

            // $scope.alerts = [];
            // $scope.closeAlert = function(index) {
            //     $scope.alerts.splice(index, 1);
            // };

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
                    windowClass: 'app-modal-window',
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
                $scope.userList = JSON.parse(row.memerintahkan);
                $scope.penandatanganList = JSON.parse(row.penandatangan);

                $scope.modalInstance = modal;

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
                                $http.delete("<?php echo base_url('dashboard/perjalanan-dinas/delete'); ?>" + "/" + row.id);
                                $scope.$broadcast('refreshData');
                            }
                            SweetAlert.swal("Hapus!", "Data berhasil dihapus", "success");
                        } else {
                            SweetAlert.swal("Batal", "Hapus data dibatalkan", "error");
                        }
                    });
            }

            $scope.formatDate = function(date){
                  var dateOut = new Date(date);
                  return dateOut;
            };

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
