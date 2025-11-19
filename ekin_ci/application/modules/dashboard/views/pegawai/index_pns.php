<!-- Main content -->
<section class="content" ng-app="pegawaiModule" ng-controller="pegawaiController as mc" data-ng-init="init()" ng-cloak>

	<!-- Your Page Content Here -->
	<div class="box" ng-init="id_groups = <?php echo $id_groups; ?>; unor = <?php echo $unor; ?>" ng-cloak>
		<div class="box-header">
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<select class="form-control select2" ng-model="selectedSOPD"
							ng-options="item as item.NM_UNOR for item in allSOPD track by item.KD_UNOR"
							ng-change="getSelectedSOPD()">
							<option value="">- Pilih SOPD -</option>
						</select>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<select class="form-control select2" ng-model="selectedTipePegawai"
							ng-options="item as item.type for item in allTipePegawai.data track by item.id"
							ng-change="getSelectedSOPD()">
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
				 <!-- <tr ng-show="id_groups == 1 || id_groups == 5 ? true : false"> -->
				<div class="col-md-2" ng-cloak>
					<div class="form-group" ng-show="!mc.isLoading && !isSelectSOPD && !isSelectTipePegawai" >
						<?php if ($id_groups == 1 || $id_groups == 5): ?>
						<button type="button" class="btn btn-primary" ng-if="<?php echo (isset($_created) == 1); ?>"
							ng-click="openAddEditModal()">Tambah
							Pegawai</button>
							<?php endif; ?>
					</div>
				</div>
				<div class="col-md-2" ng-cloak>
					<div class="form-group">
						<div class="box-tools pull-right" ng-show="!mc.isLoading && !isSelectSOPD && !isSelectTipePegawai">
							Showing {{mc.numbering + $index}} to {{mc.lengthFilter}} of {{mc.itemsLength}} entries
						</div>
					</div>
				</div>
			</div>

							
				<a href="<?= $base_url?>dashboard/pegawai_tpp/report?unor={{selectedSOPD.KD_UNOR}}" ng-show="!mc.isLoading && !isSelectSOPD && !isSelectTipePegawai" type="button" class="btn btn-primary"
							>Print
							Pegawai </a>
							
		</div>
		
		<div class="box-body">
			<div class="table-responsive">
				<div uib-alert ng-repeat="alert in alerts" ng-class="'alert-' + (alert.type || 'warning')"
					close="closeAlert($index)" ng-cloak>{{alert.msg}}</div>
				<table class="table table-hover table-bordered margin-0" st-pipe="mc.callServer" st-table="mc.displayed"
					st-safe-src="mc.callServer" refresh-table table-watch>
					<thead>
						<tr>
							<th width="10" rowspan="2" class="th-top">No</th>
							<th width="150" st-sort="PNS_PNSNIP">NIP</th>
							<th st-sort="PNS_PNSNAM">Nama</th>
							<th rowspan="2" class="th-top">Pangkat</th>
							<th width="300" rowspan="2" class="th-top">Jabatan</th>
							<th width="80" rowspan="2" class="th-top">Foto</th>
							<th width="114" rowspan="2" class="th-top th-action">Aksi</th>
						</tr>
						<tr>
							<th><input st-search="PNS_PNSNIP" placeholder="Pencarian.." class="input-sm form-control">
							</th>
							<th><input st-search="PNS_PNSNAM" placeholder="Pencarian.." class="input-sm form-control">
							</th>
						</tr>
					</thead>
					<tbody ng-show="!mc.isLoading && !isSelectSOPD && !isSelectTipePegawai" ng-cloak>
						<tr ng-repeat="row in mc.displayed">
							<td>{{mc.numbering + $index}}</td>
							<td>{{row.PNS_PNSNIP}}</td>
							<td>{{row.PNS_NAMA}}</td>
							<td>{{row.pangkat}}</td>
							<td><strong>{{row.jabatan_pns}}</strong><br>{{row.nama_jabatan}}</td>
							<td><img check-image
									ng-src="{{row.PNS_PHOTO != null ? photoPath + row.PNS_PHOTO : no_image_user}}"
									width="60" title="{{row.PNS_NAMA}}" />
							</td>
							<td class="td-action">
								<div class="btn-group btn-group-sm" role="group" aria-label="...">
									<button type="button" class="btn btn-success" ng-click="openDetailModal(row)"
										title="View">
										<i class="ion-eye"></i>
									</button>
										<?php if ($id_groups == 1 || $id_groups == 5 || $id_groups == 2): ?>
									<button type="button" class="btn btn-warning" ng-click="openAddEditModal(row)"
										ng-if="<?php echo (isset($_updated) == 1); ?>" title="Edit">
										<i class="fa fa-edit"></i>
									</button>
									<?php endif;?>
									<!-- Uncomment to delete -->
									<!-- <button type="button" class="btn btn-danger" ng-click="removeItem(row)"
										ng-if="<?php echo (isset($_deleted) == 1); ?>" title="Delete">
										<i class="ion-trash-a"></i>
									</button> -->
								
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
					<tbody ng-show="!mc.isLoading && isSelectSOPD && !isSelectTipePegawai" ng-cloak>
						<tr>
							<td colspan="7" class="text-center">
								Silakan pilih SOPD
							</td>
						</tr>
					</tbody>
					<tfoot ng-show="!isSelectSOPD && !isSelectTipePegawai" ng-cloak>
						<tr>
							<td class="text-center" st-pagination="" st-items-by-page="itemsByPage"
								st-displayed-pages="limit" colspan="7">
							</td>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>

	<script type="text/ng-template" id="viewModal.html">
		<div class="modal-header">
            <h3 class="modal-title">Data Pegawai {{ selected.item.tipe_pegawai }}</h3>
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
                            <th width="100">SOPD</th>
                            <td width="10">:</td>
                            <td>{{selected.item.NM_UNOR}}</td>
                        </tr>
                        <tr>
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
                            <th>Pangkat</th>
                            <td>:</td>
                            <td>{{selected.item.pangkat}}</td>
                        </tr>
                        <tr>
                            <th>Kategori PNS</th>
                            <td>:</td>
                            <td>{{selected.item.jabatan_pns}}</td>
                        </tr>
                        <tr>
                            <th>Jabatan</th>
                            <td>:</td>
                            <td>{{selected.item.nama_jabatan}}</td>
                        </tr>
                        <tr>
                            <th>Kelas Jabatan</th>
                            <td>:</td>
                            <td>{{selected.item.kelas_jabatan}}</td>
                        </tr>
                        <tr>
                            <th>No Rekening</th>
                            <td>:</td>
                            <td>{{selected.item.NM_BANK}} {{ selected.item.PNS_NO_REK }}</td>
                        </tr>
                        <tr>
                            <th>NPWP</th>
                            <td>:</td>
                            <td>{{selected.item.PNS_NPWP}}</td>
                        </tr>
                        <tr>
                            <th>NIK</th>
                            <td>:</td>
                            <td>{{selected.item.PNS_NIK}}</td>
                        </tr>
                        <tr>
                            <th>Alamat</th>
                            <td>:</td>
                            <td>{{selected.item.PNS_ALAMAT}}</td>
                        </tr>
                        <tr>
                            <th colspan="3">
                                <div class="clearfix image-attecment">
                                    <label><strong>Foto</strong></label><br>
                                    <img class="attachment-img" check-image ng-src="{{thumbnail.dataUrl}}" width="250" title="{{selected.item.PNS_NAMA}}" />
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
            <h3 class="modal-title" ng-if="selected.item === undefined">Tambah Data Pegawai {{ tipePegawai.type }}</h3>
            <h3 class="modal-title" ng-if="selected.item !== undefined">Ubah Data Pegawai {{ tipePegawai.type }}</h3>
        </div>
        <div class="modal-body">
            <div class="">
                <table class="table table-hover margin-0">
                    <tbody>
                        <tr>
                            <th width="130">SOPD<span style="color: red;">*</span></th>
                            <td width="10">:</td>
                            <td>
                                <select class="form-control" ng-model="sopd" ng-init="sopd = selectedSOPD || pegawaiSOPD" ng-options="item as item.NM_UNOR for item in allSOPD track by item.KD_UNOR" <?php if($id_groups == 2): ?>disabled <?php endif; ?>>
                                    <option value="">- Pilih SOPD -</option>
                                </select>
                            </td>
                        </tr>
 <!-- <tr ng-show="id_groups == 1 || id_groups == 5 ? true : false"> -->
                        <tr >
                            <th>NIP<span style="color: red;">*</span></th>
                            <td width="10">:</td>
                            <td><input type="text" ng-model="nip" class="form-control" placeholder="NIP" ng-value="selected.item.PNS_PNSNIP" <?php if($id_groups == 2): ?>disabled <?php endif; ?> ></td>
                        </tr>

                        <tr >
                            <th>Gelar Depan</th>
                            <td width="10">:</td>
                            <td><input type="text" ng-model="gelar_depan" class="form-control" placeholder="Gelar Depan" ng-value="selected.item.PNS_GLRDPN" <?php if($id_groups == 2): ?>disabled <?php endif; ?>></td>
                        </tr>

                        <tr >
                            <th>Nama<span style="color: red;">*</span></th>
                            <td width="10">:</td>
                            <td><input type="text" ng-model="nama" class="form-control" placeholder="Nama" ng-value="selected.item.PNS_PNSNAM"  <?php if($id_groups == 2): ?>disabled <?php endif; ?>></td>
                        </tr>

                        <tr >
                            <th>Gelar Belakang</th>
                            <td width="10">:</td>
                            <td><input type="text" ng-model="gelar_belakang" class="form-control" placeholder="Gelar Belakang" ng-value="selected.item.PNS_GLRBLK" <?php if($id_groups == 2): ?>disabled <?php endif; ?>></td>
                        </tr>

                        <tr>
                            <th width="130">Pangkat/Golongan Ruang<span style="color: red;">*</span></th>
                            <td width="10">:</td>
                            <td>
                                <select class="form-control" ng-model="golru" ng-init="golru = {KD_GOL: selected.item.PNS_GOLRU}" ng-options="item as (item.NM_PKT + '('+item.NM_GOL+')') for item in allGolRu track by item.KD_GOL" <?php if($id_groups == 2): ?>disabled <?php endif; ?>>
                                    <option value="">- Pilih Pangkat/Golru -</option>
                                </select>
                            </td>
                        </tr>

                        <tr >
                            <th width="130">Kelas Jabatan<span style="color: red;">*</span></th>
                            <td width="10">:</td>
                            <td>
                                <select class="form-control" ng-model="id_master_kelas_jabatan" ng-init="id_master_kelas_jabatan = {id: selected.item.id_master_kelas_jabatan}" ng-options="item as (item.kelas_jabatan + '('+item.nama_jabatan+')' + '('+item.unit_organisasi+')') for item in allKelasJabatan track by item.id" <?php if($id_groups == 2): ?>disabled <?php endif; ?>>
                                    <option value="">- Pilih Kelas Jabatan -</option>
                                </select>
                            </td>
                        </tr>

						<tr>
                            <th width="130">Bank</th>
                            <td width="10">:</td>
                            <td>
                                <select class="form-control" ng-model="id_bank" ng-init="id_bank = {id: selected.item.PNS_ID_BANK}" ng-options="item as (item.bank) for item in allBank track by item.id">
                                    <option value="">- Pilih Bank -</option>
                                </select>
                            </td>
                        </tr>

						<tr>
                            <th>No Rekening</th>
                            <td width="10">:</td>
                            <td><input type="text" ng-model="no_rek" class="form-control" placeholder="No Rekening" ng-value="selected.item.PNS_NO_REK"></td>
                        </tr>

						<tr>
                            <th>NPWP</th>
                            <td width="10">:</td>
                            <td><input type="text" ng-model="npwp" class="form-control" placeholder="NPWP" ng-value="selected.item.PNS_NPWP"></td>
                        </tr>

						<tr>
                            <th>NIK</th>
                            <td width="10">:</td>
                            <td><input type="text" ng-model="nik" class="form-control" placeholder="NIK" ng-value="selected.item.PNS_NIK"></td>
                        </tr>

						<tr>
                            <th>Alamat</th>
                            <td width="10">:</td>
                            <td>
								<!-- <input type="text" ng-model="alamat" class="form-control" placeholder="Alamat" ng-value="selected.item.PNS_ALAMAT"> -->
								<textarea ng-model="alamat" class="form-control" placeholder="Alamat" cols="30" rows="5"></span></textarea>
							</td>
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
                                <button type="button" ng-disabled="" class="btn btn-primary" ng-click="submit()">Simpan</button>
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



	var app = angular.module('pegawaiModule', ['smart-table', 'oitozero.ngSweetAlert', 'ui.bootstrap', 'moment-picker']);

	app.factory('Resource', ['$q', '$filter', '$timeout', '$http', function ($q, $filter, $timeout, $http) {

		function getPage(start, number, params, selectedSOPD = null, selectedTipePegawai = null) {

			var deferred = $q.defer();

			var url = base_url + '/dashboard/pegawai_tpp/get_data';
			var params_custom = selectedSOPD !== null && selectedTipePegawai !== null ? {
				unor: selectedSOPD.KD_UNOR,
				tipe_pegawai: selectedTipePegawai.id
			} : {};

			var getData = $http.get(url, {
					params: params_custom
				})
				.then(function (response) {

					recordItems = response.data;

					var filtered = params.search.predicateObject ? $filter('filter')(recordItems, params
						.search.predicateObject) : recordItems;

					if (params.sort.predicate) {
						filtered = $filter('orderBy')(filtered, params.sort.predicate, params.sort
							.reverse);
					}

					var result = filtered.slice(start, start + number);

					$timeout(function () {
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

	app.directive("refreshTable", function () {
		return {
			require: 'stTable',
			restrict: "A",
			link: function (scope, elem, attr, table) {
				scope.$on("refreshData", function () {
					table.pipe(table.tableState());
				});
			}
		}
	});

	app.directive('tableWatch', function ($rootScope) {
		return {
			require: '^stTable',
			link: function (scope, element, attr, ctrl) {
				$rootScope.$on('reset-pagination', function () {
					ctrl.tableState().pagination.start = 0;
					ctrl.pipe();
				});
			}
		}
	});

	app.filter("htmlSafe", ['$sce', function ($sce) {
		return function (htmlCode) {
			return $sce.trustAsHtml(htmlCode);
		};
	}]);

	app.directive('convertNumber', function () {
		return {
			require: 'ngModel',
			link: function (scope, el, attr, ctrl) {
				ctrl.$parsers.push(function (value) {
					return parseInt(value, 10);
				});

				ctrl.$formatters.push(function (value) {
					return "" + value;
				});
			}
		}
	});

	app.controller('pegawaiController', ['Resource', '$scope', 'SweetAlert', '$http', '$uibModal', '$timeout',
		function (service, $scope, SweetAlert, $http, $uibModal, $timeout) {

			$scope.init = function () {
				$scope.$watchGroup(['id_groups', 'unor'], function (newValues, oldValues, scope) {
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
						.then(function (response) {
							$scope.allSOPD = response.data;
						});

					$http.get(base_url + 'api/get_tipe_pegawai', {params: {is_tpp: true}})
                        .then(function(response) {
                            $scope.allTipePegawai = response.data;
                        });

					$http.get(base_url + '/api/get_pangkat_golru', {
							params: {
								notpns: true
							}
						})
						.then(function (response) {
							$scope.allGolRu = response.data;
						});
				});
			}

			var ctrl = this;
			this.displayed = [];

			$scope.loadingImg = base_url + '/assets/img/loading.svg';

			$scope.alerts = [];
			$scope.closeAlert = function (index) {
				$scope.alerts.splice(index, 1);
			};
			$scope.photoPath = base_url + '/assets/img/upload/user/';
			$scope.no_image = base_url + '/assets/img/no_image.png';
			$scope.no_image_user = base_url + '/assets/img/user.png';

			ctrl.isStart = 0;

			$scope.selectedSOPD = '';
			$scope.isSelectSOPD = true;

			$scope.selectedTipePegawai = '';
			$scope.isSelectTipePegawai = true;

			this.callServer = function callServer(tableState) {
				ctrl.isLoading = true;
				ctrl.emptyData = false;

				var pagination = tableState.pagination;

				var start = pagination.start || 0;
				var number = pagination.number || limit;

				service.getPage(start, number, tableState, $scope.selectedSOPD, $scope.selectedTipePegawai).then(function (result) {
					ctrl.isStart += number;
					ctrl.displayed = result.data;
					ctrl.itemsLength = result.itemsLength;
					ctrl.emptyData = ctrl.displayed.length > 0 ? false : true;
					ctrl.numbering = ctrl.emptyData == true ? start : start + 1;
					ctrl.lengthFilter = ctrl.emptyData == true ? 0 : (ctrl.numbering - 1 + ctrl
						.displayed.length);
					tableState.pagination.numberOfPages = result.numberOfPages;
					ctrl.isLoading = false;

					if ((!$scope.selectedSOPD && $scope.selectedSOPD == '') || $scope.selectedSOPD == null) {
						$scope.isSelectSOPD = true;
					} else {
						$scope.isSelectSOPD = false;
					}

					if ((!$scope.selectedTipePegawai && $scope.selectedTipePegawai == '') || $scope.selectedTipePegawai == null) {
						$scope.isSelectTipePegawai = true;
					} else {
						$scope.isSelectTipePegawai = false;
					}
				});
			};

			$scope.getSelectedSOPD = function () {
				ctrl.isStart = 0;
				$scope.$emit('reset-pagination');
			};

			$scope.openDetailModal = function (row) {
				var modal = $uibModal.open({
					templateUrl: 'viewModal.html',
					scope: $scope,
					resolve: {
						item: function () {
							return row;
						}
					}
				});

				$scope.items = row;
				$scope.selected = {
					item: $scope.items
				};

				if (row !== undefined) {
					$scope.thumbnail = {
						dataUrl: row.PNS_PHOTO != null ? $scope.photoPath + row.PNS_PHOTO : $scope.no_image
					};
				} else {
					$scope.thumbnail = {
						dataUrl: base_url + '/assets/img/no_image.png'
					};
				}

				$scope.modalInstance = modal;

				return modal.result
			};

			$scope.openAddEditModal = function (row) {
				var modal = $uibModal.open({
					templateUrl: 'addEditModal.html',
					controller: 'pegawaiController',
					scope: $scope,
					resolve: {
						item: function () {
							return row;
						}
					}
				});

				$scope.items = row;
				$scope.selected = {
					item: $scope.items
				};

				$scope.modalInstance = modal;

				$scope.sopd = $scope.selectedSOPD;
				$scope.tipePegawai = $scope.selectedTipePegawai;
				if ($scope.isEdit === true) {
					$scope.alamat = row.PNS_ALAMAT;
				}

				$http.get(base_url + '/api/get_all_master_kelas_jabatan', {
						params: {
							unor: $scope.sopd.KD_UNOR
						}
					})
					.then(function (response) {
						$scope.allKelasJabatan = response.data;
					});

				$http.get(base_url + '/api/get_all_bank')
					.then(function (response) {
						$scope.allBank = response.data;
					});

				$scope.isDisabled = $scope.selected.item !== undefined ? false : true;
				$scope.isEdit = $scope.selected.item === undefined ? false : true;

				if (row !== undefined) {
					$scope.thumbnail = {
						dataUrl: row.PNS_PHOTO != null ? $scope.photoPath + row.PNS_PHOTO : $scope.no_image
					};
				} else {
					$scope.thumbnail = {
						dataUrl: base_url + '/assets/img/no_image.png'
					};
				}

				$scope.pegawaiSOPD = $scope.selectedSOPD !== undefined ? $scope.selectedSOPD : {
					KD_UNOR: row.KD_UNOR,
					NM_UNOR: row.NM_UNOR
				};

				modal.result.then(function (scope) {
					$scope.isDisabled = true;
					if ($scope.isEdit === false) {
						var fd = new FormData();
						var files = document.getElementById('file').files[0];
						fd.append('file', files);
						fd.append('sopd', scope.sopd.KD_UNOR);
						fd.append('nip', scope.nip);
						fd.append('gelar_depan', scope.gelar_depan != undefined ? scope.gelar_depan :
							'');
						fd.append('nama', scope.nama);
						fd.append('gelar_belakang', scope.gelar_belakang != undefined ? scope
							.gelar_belakang : '');
						fd.append('golru', scope.golru.KD_GOL);
						fd.append('id_master_kelas_jabatan', scope.id_master_kelas_jabatan.id);

						fd.append('id_bank', scope.id_bank.id);
						fd.append('no_rek', scope.no_rek);
						fd.append('npwp', scope.npwp);
						fd.append('nik', scope.nik);
						fd.append('alamat', scope.alamat);
						fd.append('tipe_pegawai', $scope.tipePegawai.id);

						var pushSubmit = {
							method: 'POST',
							url: base_url + '/dashboard/pegawai_tpp/add',
							headers: {
								'Content-Type': undefined
							},
							data: fd,
							transformRequest: angular.identity
						}
					} else {
						var fd = new FormData();
						var files = document.getElementById('file').files[0];
						fd.append('id_pegawai_tpp', row.id);
						fd.append('file', files);
						fd.append('sopd', scope.sopd === undefined ? row.sopd : scope.sopd.KD_UNOR);
						fd.append('nip', scope.nip === undefined ? row.PNS_PNSNIP : scope.nip);
						fd.append('gelar_depan', scope.gelar_depan === undefined ? (row.PNS_GLRDPN !=
							null ? row.PNS_GLRDPN : '') : scope.gelar_depan);
						fd.append('nama', scope.nama === undefined ? row.PNS_PNSNAM : scope.nama);
						fd.append('gelar_belakang', scope.gelar_belakang === undefined ? (row
							.PNS_GLRBLK != null ? row.PNS_GLRBLK : '') : scope.gelar_belakang);
						fd.append('golru', scope.golru === undefined ? row.PNS_GOLRU : scope.golru
							.KD_GOL);
						fd.append('id_master_kelas_jabatan', scope.id_master_kelas_jabatan ===
							undefined ? row.id_master_kelas_jabatan : scope.id_master_kelas_jabatan
							.id);

						fd.append('id_bank', scope.id_bank ===
							undefined ? row.PNS_ID_BANK : scope.id_bank
							.id);
						fd.append('no_rek', scope.no_rek ===
							undefined ? row.PNS_NO_REK : scope.no_rek);
						fd.append('npwp', scope.npwp ===
							undefined ? row.PNS_NPWP : scope.npwp);
						fd.append('nik', scope.nik ===
							undefined ? row.PNS_NIK : scope.nik);
						fd.append('alamat', scope.alamat ===
							undefined ? row.PNS_ALAMAT : scope.alamat);

						var pushSubmit = {
							method: 'POST',
							url: base_url + '/dashboard/pegawai_tpp/edit',
							headers: {
								'Content-Type': undefined
							},
							data: fd,
							transformRequest: angular.identity
						}
					}

					$http(pushSubmit)
						.then(function (response) {
							if (response) {
								$scope.alerts.push({
									"type": response.data.type,
									"msg": response.data.msg
								});
								$scope.$emit('refreshData');
								$scope.isDisabled = false;
								$timeout(function () {
									$scope.alerts = [];
								}, 5000);
							}
						});
				});

				return modal.result
			};

			$scope.fileReaderSupported = window.FileReader != null;
			$scope.photoChanged = function (files) {
				if (files != null) {
					var file = files[0];
					if ($scope.fileReaderSupported && file.type.indexOf('image') > -1) {
						$timeout(function () {
							var fileReader = new FileReader();
							fileReader.readAsDataURL(file);
							fileReader.onload = function (e) {
								$timeout(function () {
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
					function (isConfirm) {
						if (isConfirm) {
							var index = ctrl.displayed.indexOf(row);
							if (index !== -1) {
								$http.delete(base_url + "dashboard/pegawai_tpp/delete" + "/" +  row.id);
							
								$scope.$broadcast('refreshData');
							}
							SweetAlert.swal("Hapus!", "Data berhasil dihapus", "success");
						} else {
							SweetAlert.swal("Batal", "Hapus data dibatalkan", "error");
						}
					});
			}


			$scope.submit = function () {
				$scope.isDisabled = true;
				$scope.modalInstance.close($scope);
			};

			$scope.cancel = function () {
				$scope.modalInstance.dismiss('cancel');
			};

		}
	]);

</script>
