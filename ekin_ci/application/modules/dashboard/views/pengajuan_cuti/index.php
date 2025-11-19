<!--
# @Author: Awan Tengah
# @Date:   2019-08-19T01:46:37+07:00
# @Last modified by:   Awan Tengah
# @Last modified time: 2019-09-03T22:13:05+07:00
-->

<style>
.colon {
	padding: 0 1em;
	width: 10px !important;
}
</style>

<section class="content" ng-app="pengajuanCutiModule" ng-controller="pengajuanCutiController as mc">

    <!-- Your Page Content Here -->
    <div class="box">
        <div class="box-header">
            <div class="row" ng-cloak>
                <div class="col-md-6" ng-if="<?php echo (isset($_created) == 1); ?>">
                    <a href="<?php echo site_url('dashboard/pengajuan-cuti/add'); ?>" class="btn btn-primary">Tambah</a>
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
                <div uib-alert ng-repeat="alert in alerts" ng-class="'alert-' + (alert.type || 'warning')" close="closeAlert($index)" ng-cloak>{{alert.msg}}</div>
                <table class="table table-hover table-bordered margin-0" st-pipe="mc.callServer" st-table="mc.displayed" st-safe-src="mc.callServer" refresh-table>
                    <thead>
                        <tr>
                            <th width="10">No</th>
                            <th width="150">No Surat</th>
                            <th>Nama</th>
                            <th width="200">Jenis Cuti</th>
                            <th width="150" class="th-top th-action">Aksi</th>
                        </tr>
                    </thead>
                    <tbody ng-show="!mc.isLoading" ng-cloak>
                        <tr ng-repeat="row in mc.displayed">
                            <td>{{mc.numbering + $index}}</td>
                            <td>{{row.no_surat}}</td>
                            <td>{{parse(row.pegawai).PNS_NAMA}}</td>
                            <td>{{row.jenis_cuti}}</td>
                            <td class="td-action">
                                <div class="btn-group btn-group-md" role="group" aria-label="...">
                                    <button type="button" class="btn btn-success" ng-click="openDetailModal(row)" title="Detail">
                                        <i class="ion-eye"></i>
                                    </button>
                                    <a href="<?php echo site_url('dashboard/pengajuan-cuti/edit/{{row.id}}'); ?>" ng-if="<?php echo (isset($_updated) == 1); ?>" class="btn btn-warning" title="Ubah">
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
            <h3 class="modal-title">Surat Perintah Tugas</h3>
        </div>
        <div class="modal-body" id="print-box">

            <div class="box-body" ng-if="selected.item.id_master_jenis_cuti == '1'">
                <table>
                    <tr>
                        <td><img ng-src="{{base_url + '/assets/img/Lambang_Kabupaten_Kotawaringin_Barat.png'}}" width="50"></td>
                        <td style="width: 100%; text-align: center;">
                            PEMERINTAH KABUPATEN KOTAWARINGIN BARAT<br>
                            {{selected.item.NM_UNOR | uppercase}}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="border-bottom: 1px solid #ccc; padding-top: 1em;"></td>
                    </tr>
                    <tr>
                        <th colspan="2" class="text-center" style="padding-top: 1em;">
                            <p>
                                {{'Surat ' + selected.item.jenis_cuti | uppercase}}<br>
                                NOMOR: {{selected.item.no_surat}}
                            </p>
                        </th>
                    </tr>
                </table>
                <table>
                    <tr>
                        <td style="vertical-align: initial; min-width: 20px;">1.</td>
                        <td>
                            Diberikan {{selected.item.jenis_cuti}} untuk Tahun {{date | date: 'yyyy'}} Kepada Pegawai Negeri Sipil:
                            <table style="margin: 1em 3em;">
                                <tr>
                                    <td>Nama</td>
                                    <td class="colon">:</td>
                                    <td>{{parse(selected.item.pegawai).PNS_NAMA}}</td>
                                </tr>
                                <tr>
                                    <td>NIP</td>
                                    <td class="colon">:</td>
                                    <td>{{checkIsTKD(parse(selected.item.pegawai).PNS_PNSNIP)}}</td>
                                </tr>
                                <tr>
                                    <td>Pangkat/Gol.Ruang</td>
                                    <td class="colon">:</td>
                                    <td ng-if="parse(selected.item.pegawai).NM_PKT && parse(selected.item.pegawai).NM_GOL">{{parse(selected.item.pegawai).NM_PKT + ' / ' + parse(selected.item.pegawai).NM_GOL}}</td>
                                    <td ng-if="!parse(selected.item.pegawai).NM_PKT && !parse(selected.item.pegawai).NM_GOL">-</td>
                                </tr>
                                <tr>
                                    <td>Jabatan</td>
                                    <td class="colon">:</td>
                                    <td ng-if="checkIsTKD(parse(selected.item.pegawai).PNS_PNSNIP) != '-'">{{parse(selected.item.pegawai).NM_GENPOS}}</td>
                                    <td ng-if="checkIsTKD(parse(selected.item.pegawai).PNS_PNSNIP) == '-'">Tenaga Kontrak Daerah</td>
                                </tr>
                                <tr>
                                    <td>Unit Kerja</td>
                                    <td class="colon">:</td>
                                    <td>{{selected.item.NM_UNOR}}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            Selama {{selected.item.lama_cuti}} dengan ketentuan sebagai berikut:
                            <ol type="a">
                                <li>Sebelum menjalankan {{selected.item.jenis_cuti}} wajib menyerahkan pekerjaan kepada Atasan Langsung.</li>
                                <li>Setelah selesai menjalankan {{selected.item.jenis_cuti}} melaporkan diri kepada Atasan Langsung dan bekerja kembali sebagaimana biasa.</li>
                            </ol>
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align: initial; min-width: 20px;">2.</td>
                        <td>Demikian Surat {{selected.item.jenis_cuti}} ini dibuat untuk dapat dipergunakan sebagaimana mestinya.</td>
                    </tr>

                    <tr>
                        <td></td>
                        <td class="" style="margin-top: 1em; float: right;">
                            <div class="" style="text-align: center;">
                                Dikeluarkan di Pangkalan Bun<br>
                                Pada tanggal {{date | date: 'dd MMMM yyyy'}}<br>
                                <div ng-if="parse(selected.item.penandatangan).NM_GENPOS !== 'Kepala'">
                                    An. Kepala Dinas<br>
                                    {{parse(selected.item.penandatangan).NM_GENPOS}}
                                </div>
                                <div ng-if="parse(selected.item.penandatangan).NM_GENPOS == 'Kepala'">
                                    Kepala Dinas
                                </div>
                                <br><br><br>
                                {{parse(selected.item.penandatangan).PNS_NAMA}}<br>
                                NIP. {{parse(selected.item.penandatangan).PNS_PNSNIP}}
                            </div>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="box-body" ng-if="selected.item.id_master_jenis_cuti == '3'">
                <table>
                    <tr>
                        <td><img ng-src="{{base_url + '/assets/img/Lambang_Kabupaten_Kotawaringin_Barat.png'}}" width="50"></td>
                        <td style="width: 100%; text-align: center;">
                            PEMERINTAH KABUPATEN KOTAWARINGIN BARAT<br>
                            {{selected.item.NM_UNOR | uppercase}}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="border-bottom: 1px solid #ccc; padding-top: 1em;"></td>
                    </tr>
                    <tr>
                        <th colspan="2" class="text-center" style="padding-top: 1em;">
                            <p>
                                {{'Surat ' + (selected.item.id_master_jenis_cuti != '3' ? selected.item.jenis_cuti : 'Izin Sakit') | uppercase}}<br>
                                NOMOR: {{selected.item.no_surat}}
                            </p>
                        </th>
                    </tr>
                </table>
                <table>
                    <tr>
                        <td style="vertical-align: initial; min-width: 20px;">1.</td>
                        <td>
                            Diberikan {{selected.item.id_master_jenis_cuti != '3' ? selected.item.jenis_cuti : 'Izin Sakit'}} Kepada Pegawai Negeri Sipil:
                            <table style="margin: 1em 3em;">
                                <tr>
                                    <td>Nama</td>
                                    <td class="colon">:</td>
                                    <td>{{parse(selected.item.pegawai).PNS_NAMA}}</td>
                                </tr>
                                <tr>
                                    <td>NIP</td>
                                    <td class="colon">:</td>
                                    <td>{{checkIsTKD(parse(selected.item.pegawai).PNS_PNSNIP)}}</td>
                                </tr>
                                <tr>
                                    <td>Pangkat/Gol.Ruang</td>
                                    <td class="colon">:</td>
                                    <td ng-if="parse(selected.item.pegawai).NM_PKT && parse(selected.item.pegawai).NM_GOL">{{parse(selected.item.pegawai).NM_PKT + ' / ' + parse(selected.item.pegawai).NM_GOL}}</td>
                                    <td ng-if="!parse(selected.item.pegawai).NM_PKT && !parse(selected.item.pegawai).NM_GOL">-</td>
                                </tr>
                                <tr>
                                    <td>Jabatan</td>
                                    <td class="colon">:</td>
                                    <td ng-if="checkIsTKD(parse(selected.item.pegawai).PNS_PNSNIP) != '-'">{{parse(selected.item.pegawai).NM_GENPOS}}</td>
                                    <td ng-if="checkIsTKD(parse(selected.item.pegawai).PNS_PNSNIP) == '-'">Tenaga Kontrak Daerah</td>
                                </tr>
                                <tr>
                                    <td>Unit Kerja</td>
                                    <td class="colon">:</td>
                                    <td>{{selected.item.NM_UNOR}}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            Selama {{selected.item.lama_cuti}} dengan ketentuan sebagai berikut:
                            <ol type="a">
                                <li>Sebelum menjalankan {{selected.item.id_master_jenis_cuti != '3' ? selected.item.jenis_cuti : 'Izin Sakit'}} wajib menyerahkan pekerjaan kepada Atasan Langsung.</li>
                                <li>Setelah selesai menjalankan {{selected.item.id_master_jenis_cuti != '3' ? selected.item.jenis_cuti : 'Izin Sakit'}} melaporkan diri kepada Atasan Langsung dan bekerja kembali sebagaimana biasa.</li>
                            </ol>
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align: initial; min-width: 20px;">2.</td>
                        <td>Demikian Surat {{selected.item.id_master_jenis_cuti != '3' ? selected.item.jenis_cuti : 'Izin Sakit'}} ini dibuat untuk dapat dipergunakan sebagaimana mestinya.</td>
                    </tr>

                    <tr>
                        <td></td>
                        <td class="" style="margin-top: 1em; float: right;">
                            <div class="" style="text-align: center;">
                                Dikeluarkan di Pangkalan Bun<br>
                                Pada tanggal {{date | date: 'dd MMMM yyyy'}}<br>
                                <div ng-if="parse(selected.item.penandatangan).NM_GENPOS !== 'Kepala'">
                                    An. Kepala Dinas<br>
                                    {{parse(selected.item.penandatangan).NM_GENPOS}}
                                </div>
                                <div ng-if="parse(selected.item.penandatangan).NM_GENPOS == 'Kepala'">
                                    Kepala Dinas
                                </div>
                                <br><br><br>
                                {{parse(selected.item.penandatangan).PNS_NAMA}}<br>
                                NIP. {{parse(selected.item.penandatangan).PNS_PNSNIP}}
                            </div>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="box-body" ng-if="selected.item.id_master_jenis_cuti == '5'">
                <table>
                    <tr>
                        <td><img ng-src="{{base_url + '/assets/img/Lambang_Kabupaten_Kotawaringin_Barat.png'}}" width="50"></td>
                        <td style="width: 100%; text-align: center;">
                            PEMERINTAH KABUPATEN KOTAWARINGIN BARAT<br>
                            {{selected.item.NM_UNOR | uppercase}}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="border-bottom: 1px solid #ccc; padding-top: 1em;"></td>
                    </tr>
                    <tr>
                        <th colspan="2" class="text-center" style="padding-top: 1em;">
                            <p>
                                {{'Surat ' + (selected.item.id_master_jenis_cuti != '5' ? selected.item.jenis_cuti : 'Izin Alasan Penting') | uppercase}}<br>
                                NOMOR: {{selected.item.no_surat}}
                            </p>
                        </th>
                    </tr>
                </table>
                <table>
                    <tr>
                        <td style="vertical-align: initial; min-width: 20px;"></td>
                        <td>
                            Diberikan Surat {{selected.item.id_master_jenis_cuti != '5' ? selected.item.jenis_cuti : 'Izin Alasan Penting'}} Kepada:
                            <table style="margin: 1em 3em;">
                                <tr>
                                    <td>Nama</td>
                                    <td class="colon">:</td>
                                    <td>{{parse(selected.item.pegawai).PNS_NAMA}}</td>
                                </tr>
                                <tr>
                                    <td>NIP</td>
                                    <td class="colon">:</td>
                                    <td>{{checkIsTKD(parse(selected.item.pegawai).PNS_PNSNIP)}}</td>
                                </tr>
                                <tr>
                                    <td>Pangkat/Gol.Ruang</td>
                                    <td class="colon">:</td>
                                    <td ng-if="parse(selected.item.pegawai).NM_PKT && parse(selected.item.pegawai).NM_GOL">{{parse(selected.item.pegawai).NM_PKT + ' / ' + parse(selected.item.pegawai).NM_GOL}}</td>
                                    <td ng-if="!parse(selected.item.pegawai).NM_PKT && !parse(selected.item.pegawai).NM_GOL">-</td>
                                </tr>
                                <tr>
                                    <td>Jabatan</td>
                                    <td class="colon">:</td>
                                    <td ng-if="checkIsTKD(parse(selected.item.pegawai).PNS_PNSNIP) != '-'">{{parse(selected.item.pegawai).NM_GENPOS}}</td>
                                    <td ng-if="checkIsTKD(parse(selected.item.pegawai).PNS_PNSNIP) == '-'">Tenaga Kontrak Daerah</td>
                                </tr>
                                <tr>
                                    <td>Unit Kerja</td>
                                    <td class="colon">:</td>
                                    <td>{{selected.item.NM_UNOR}}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align: initial; min-width: 20px;"></td>
                        <td>
                            Selama {{selected.item.lama_cuti}}.
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align: initial; min-width: 20px;"></td>
                        <td>Demikian Surat {{selected.item.id_master_jenis_cuti != '5' ? selected.item.jenis_cuti : 'Izin Alasan Penting'}} ini dibuat untuk dapat dipergunakan sebagaimana mestinya.</td>
                    </tr>

                    <tr>
                        <td></td>
                        <td class="" style="margin-top: 1em; float: right;">
                            <div class="" style="text-align: center;">
                                Dikeluarkan di Pangkalan Bun<br>
                                Pada tanggal {{date | date: 'dd MMMM yyyy'}}<br>
                                <div ng-if="parse(selected.item.penandatangan).NM_GENPOS !== 'Kepala'">
                                    An. Kepala Dinas<br>
                                    {{parse(selected.item.penandatangan).NM_GENPOS}}
                                </div>
                                <div ng-if="parse(selected.item.penandatangan).NM_GENPOS == 'Kepala'">
                                    Kepala Dinas
                                </div>
                                <br><br><br>
                                {{parse(selected.item.penandatangan).PNS_NAMA}}<br>
                                NIP. {{parse(selected.item.penandatangan).PNS_PNSNIP}}
                            </div>
                        </td>
                    </tr>
                </table>
            </div>


        </div>
        <div class="modal-footer">
            <button class="btn btn-warning" type="button" ng-click="cancel()">Tutup</button>
        </div>
    </script>

</section>
<!-- /.content -->

<script>
    var app = angular.module('pengajuanCutiModule', ['smart-table', 'oitozero.ngSweetAlert', 'ui.bootstrap']);

    app.factory('Resource', ['$q', '$filter', '$timeout', '$http', function($q, $filter, $timeout, $http) {

        function getPage(start, number, params) {

            var deferred = $q.defer();

            var url = base_url + '/dashboard/pengajuan-cuti/get-data';

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

    app.controller('pengajuanCutiController', ['Resource', '$scope', 'SweetAlert', '$http', '$uibModal', '$timeout',
        function(service, $scope, SweetAlert, $http, $uibModal, $timeout) {

            var ctrl = this;
            this.displayed = [];

            $scope.base_url = base_url;
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
                                $http.delete("<?php echo base_url('dashboard/pengajuan-cuti/delete'); ?>" + "/" + row.id);
                                $scope.$broadcast('refreshData');
                            }
                            SweetAlert.swal("Hapus!", "Data berhasil dihapus", "success");
                        } else {
                            SweetAlert.swal("Batal", "Hapus data dibatalkan", "error");
                        }
                    });
            }

            $scope.parse = function(val) {
                return JSON.parse(val);
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

            $scope.checkIsTKD = function(val) {
				return !val.includes('TKD') ? val : '-';
			}

        }
    ]);
</script>
