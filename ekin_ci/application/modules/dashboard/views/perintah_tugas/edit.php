<!-- Main content
# @Author: Awan Tengah
# @Date:   2019-08-09T17:28:19+07:00
# @Last modified by:   Awan Tengah
# @Last modified time: 2019-09-03T19:07:28+07:00
-->

<section class="content" ng-app="pteModule" ng-controller="pteController" data-ng-init="init()">

	<!-- Your Page Content Here -->
	<div class="row" ng-init="unor = <?php echo $_user_login->unor; ?>; isEdit = <?php echo isset($id_perintah_tugas) ? $id_perintah_tugas : 'null'; ?>">
		<div class="col-md-5">
			<div class="box">

				<form name="form" ng-model="form" ng-submit="submitForm(form.$valid)" novalidate>

					<div class="box-body">

						<input type="hidden" name="unor" ng-model="unor" ng-value="{{allSOPD[0].KD_UNOR}}" required>

						<div class="form-group">
							<label for="no_surat">No Surat</label>
							<input type="text" name="no_surat" ng-model="no_surat" id="no_surat" class="form-control" placeholder="No Surat" required>
						</div>

						<div class="form-group">
							<label for="dasar_penugasan">Dasar Penugasan</label>
							<textarea name="dasar_penugasan" ng-model="dasar_penugasan" id="dasar_penugasan" class="form-control" placeholder="Dasar Penugasan" cols="30" rows="5" required></textarea>
						</div>

						<div class="form-group" style="display: inline-table; width: 100%;">
							<label for="memerintahkan">Memerintahkan Kepada</label>
							<div class="col-xs-10" style="padding-left: 0;">
								<select id="memerintahkan" class="form-control select2" ng-model="memerintahkan" ng-options="item as item.PNS_NAMA for item in allPegawai track by item.PNS_PNSNIP" required>
									<option value="">- Pilih Pegawai -</option>
								</select>
							</div>
							<button type="button" class="btn btn-primary col-xs-2" ng-click="addPerintah();"><i class="fa fa-plus"></i></button>
						</div>

						<div class="form-group" ng-cloak>
							<table>
								<tr ng-repeat="list in userList track by $index">
									<th style="padding-bottom: 1em;">{{list.PNS_NAMA}}</th>
									<td width="10"></td>
									<td style="padding-bottom: 1em;">
										<button type="button" class="btn btn-danger btn-xs" ng-click="removePerintah(list);">
											<i class="fa fa-minus"></i>
										</button>
									</td>
								</tr>
							</table>
						</div>

						<div class="form-group">
							<label for="keperluan">Keperluan</label>
							<textarea name="keperluan" ng-model="keperluan" id="keperluan" class="form-control" placeholder="Keperluan" cols="30" rows="5" required></textarea>
						</div>

						<div class="form-group">
							<label for="lama_penugasan">Lama Penugasan</label>
							<input type="text" name="lama_penugasan" ng-model="lama_penugasan" id="lama_penugasan" class="form-control" placeholder="Lama Penugasan" required>
						</div>

						<div class="form-group" style="display: inline-table; width: 100%;">
							<label for="penandatangan">Penandatangan</label>
								<select id="penandatangan" class="form-control select2" ng-model="penandatangan" ng-options="item as item.PNS_NAMA for item in allPegawai track by item.PNS_PNSNIP" required>
									<option value="">- Pilih Pegawai -</option>
								</select>
						</div>

					</div><!-- /.box-body -->

					<div class="box-footer">
						<button type="submit" id="btnSimpan" class="btn btn-primary" ng-disabled="form.$invalid">Simpan</button>
					</div>

				</form>

			</div>
		</div>

		<div class="col-md-7" ng-cloak>
			<div class="box">
				<div class="box-header with-border">
					<h3 class="box-title">Preview</h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-primary" style="position: absolute; top: -12px; right: 0;" ng-show="btnPrint == true" ng-click="printBox()">Cetak SPT</button>
					</div>
				</div>

				<div class="box-body" ng-if="alerts != ''">
					<div uib-alert ng-repeat="alert in alerts" ng-class="'alert-' + (alert.type || 'warning')" close="closeAlert($index)" style="margin: 0;" ng-cloak>{{alert.msg}}</div>
				</div>

				<div id="print-box">
					<div class="box-body">
						<table>
							<tr>
								<td><img ng-src="{{base_url + '/assets/img/Lambang_Kabupaten_Kotawaringin_Barat.png'}}" width="50"></td>
								<td colspan="2" style="width: 100%; text-align: center;">
									PEMERINTAH KABUPATEN KOTAWARINGIN BARAT<br>
									{{allSOPD[0].NM_UNOR | uppercase}}
								</td>
							</tr>
							<tr>
								<td colspan="3" style="border-bottom: 1px solid #ccc; padding-top: 1em;"></td>
							</tr>
							<tr>
								<th colspan="3" class="text-center" style="padding-top: 1em;">
									<p>
										SURAT PERINTAH TUGAS<br>
										Nomor: {{no_surat}}
									</p>
								</th>
							</tr>
							<tr>
								<td style="vertical-align: initial; min-width: 75px;">Dasar</td>
								<td style="vertical-align: initial; min-width: 30px;">:</td>
								<td>
									<ol>
										<li>Peraturan Menteri Dalam Negeri No. 13 Tahun 2006 tentang Pedoman Pengelolaan Keuangan Daerah.</li>
										<li>Peraturan Menteri Keuangan Nomor 133/PMK.05/2012 Tentang Perjalanan Dinas Dalam Negeri bagi Pejabat Negara, Pegawai Negeri Sipil dan Pegawai Tidak Tetap.</li>
										<li>Peraturan Bupati Kotawaringin Barat Nomor 1 Tahun 2016 Tentang Perjalanan Dinas Dalam Negeri Bagi Pejabat Negara, Pegawai Negeri dan Pegawai Tidak Tetap di Lingkungan Pemerintah Kabupaten Kotawaringin
											Barat.</li>
										<li>{{dasar_penugasan}}</li>
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
										<tr ng-repeat-start="user in userList track by $index">
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
								<td>{{keperluan}}</td>
							</tr>
							<tr>
								<td></td>
								<td></td>
								<td>
									<table>
										<tr>
											<td width="15">1</td>
											<td>Lama penugasan selama {{lama_penugasan}}</td>
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
								<td class="" style="margin-top: 1em; float: right;">
									<div class="" style="text-align: center;">
										Dikeluarkan di Pangkalan Bun<br>
										Pada tanggal {{date | date: 'dd MMMM yyyy'}}<br>
										<div ng-if="penandatangan.NM_GENPOS !== 'Kepala'">
											An. Kepala Dinas
										</div>
										<div>
											{{penandatangan.NM_GENPOS}}
										</div>
										<br><br><br>
										{{penandatangan.PNS_NAMA}}<br>
										NIP. {{penandatangan.PNS_PNSNIP}}
									</div>
								</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script type="text/ng-template" id="alert.html">
		<div ng-transclude></div>
    </script>

</section>

<script>
	var app = angular.module('pteModule', ['oitozero.ngSweetAlert', 'ui.bootstrap']);

	app.controller('pteController', ['$scope', 'SweetAlert', '$http', '$window', '$timeout', '$location',
		function($scope, SweetAlert, $http, $window, $timeout, $location) {

			$scope.init = function() {
				$scope.base_url = base_url;
				$scope.date = new Date();
				$scope.$watchGroup(['unor', 'isEdit'], function(newValues, oldValues, scope) {
					var params = {
						params: {
							unor: scope.unor
						}
					};
					$http.get(base_url + '/api/get_all_pegawai_sopd', params)
						.then(function(response) {
							$scope.allPegawai = response.data;
						});
					$http.get(base_url + '/api/get_all_sopd', params)
						.then(function(response) {
							$scope.allSOPD = response.data;
						});
					if (scope.isEdit !== null) {
						$http.get(base_url + '/api/get_perintah_tugas', {
								params: {
									id: scope.isEdit
								}
							})
							.then(function(response) {
								$scope.no_surat = response.data.no_surat;
								$scope.dasar_penugasan = response.data.dasar_penugasan;
								$scope.userList = JSON.parse(response.data.memerintahkan);
								$scope.memerintahkan = $scope.userList[0];
								$scope.keperluan = response.data.keperluan;
								$scope.lama_penugasan = response.data.lama_penugasan;
								$scope.penandatangan = JSON.parse(response.data.penandatangan);
							});
					}
				});
			}

			$scope.alerts = [];
			$scope.closeAlert = function(index) {
				$scope.alerts.splice(index, 1);
			};

			$scope.userList = [];
			$scope.btnPrint = false;

			$scope.addPerintah = function() {
				if ($scope.memerintahkan) {
					$scope.userList.push({
						'PNS_PNSNIP': !$scope.memerintahkan.PNS_PNSNIP.includes('TKD') ? $scope.memerintahkan.PNS_PNSNIP : '-',
						'PNS_NAMA': $scope.memerintahkan.PNS_NAMA,
						'NM_PKT': $scope.memerintahkan.NM_PKT ? $scope.memerintahkan.NM_PKT : '-',
						'NM_GOL': $scope.memerintahkan.NM_GOL ? $scope.memerintahkan.NM_GOL : '-',
						'NM_GENPOS': $scope.memerintahkan.NM_GENPOS ? $scope.memerintahkan.NM_GENPOS : 'Tenaga Kontrak'
					});
				}
			}

			$scope.removePerintah = function(user) {
				if ($scope.userList.indexOf(user) >= 0) {
					$scope.userList.splice($scope.userList.indexOf(user), 1);
				}
			}

			$scope.submitForm = function(isValid) {
				if (isValid) {
					if ($scope.isEdit === 'null') {
						var pushSubmit = {
							method: 'POST',
							url: base_url + '/dashboard/perintah-tugas/add',
							headers: {
								'Content-Type': 'application/x-www-form-urlencoded'
							},
							data: $.param({
								unor: $scope.unor,
								no_surat: $scope.no_surat,
								dasar_penugasan: $scope.dasar_penugasan,
								memerintahkan: JSON.stringify($scope.userList),
								keperluan: $scope.keperluan,
								lama_penugasan: $scope.lama_penugasan,
								penandatangan: JSON.stringify($scope.penandatangan)
							})
						}
					} else {
						var pushSubmit = {
							method: 'POST',
							url: $location.absUrl(),
							headers: {
								'Content-Type': 'application/x-www-form-urlencoded'
							},
							data: $.param({
								unor: $scope.unor,
								no_surat: $scope.no_surat,
								dasar_penugasan: $scope.dasar_penugasan,
								memerintahkan: JSON.stringify($scope.userList),
								keperluan: $scope.keperluan,
								lama_penugasan: $scope.lama_penugasan,
								penandatangan: JSON.stringify($scope.penandatangan)
							})
						}
					}

					$http(pushSubmit)
						.then(function(response) {
							if (response) {
								$(".col-md-5").hide();
								$("#btnSimpan").hide();
								$scope.form.$invalid = true;
								$scope.btnPrint = true;
								$scope.alerts.push({
									"type": response.data.type,
									"msg": response.data.msg
								});
							}
						});
				}
			}

			$scope.printBox = function() {
				var left = ($(window).width() / 2) - (900 / 2),
					top = ($(window).height() / 2) - (600 / 2),
					popup = window.open("", "popup", "width=900, height=600, top=" + top + ", left=" + left);

				var divContents = $("#print-box").html();
				var printWindow = window.open('', 'popup', 'width=900, height=600, top=' + top + ', left=' + left);
				printWindow.document.write('<html><head><title>Perintah Tugas</title>');
				printWindow.document.write('<link rel="stylesheet" href="' + base_url + '/assets/AdminLTE/bower_components/bootstrap/dist/css/bootstrap.min.css">');
				printWindow.document.write('<link rel="stylesheet" href="' + base_url + '/assets/css/print.css">');
				printWindow.document.write('</head><body><div class="container">');
				printWindow.document.write(divContents);
				printWindow.document.write('</div></body></html>');
				printWindow.document.close();

				$timeout(function() {
					var content = printWindow.document.querySelector('html').innerHTML;

					if (content.length) {
						printWindow.print()
						printWindow.close()
					} else {
						$scope.checkForContent()
					}
				}, 200);
			}

			$scope.checkForContent = function() {
				$timeout(function() {
					var content = window.document.querySelector('body').innerHTML

					if (content.length) {
						window.print()
						window.close()
					} else {
						checkForContent()
					}
				}, 200)
			}

		}
	]);
</script>
