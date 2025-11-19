<!--
# @Author: Awan Tengah
# @Date:   2019-08-19T07:51:05+07:00
# @Last modified by:   Awan Tengah
# @Last modified time: 2019-08-26T20:02:52+07:00
-->

<style>
.colon {
	padding: 0 1em;
	width: 10px !important;
}
</style>

<section class="content" ng-app="pceModule" ng-controller="pceController" data-ng-init="init()">

	<!-- Your Page Content Here -->
	<div class="row" ng-init="unor = <?php echo $_user_login->unor; ?>; isEdit = <?php echo isset($id_pengajuan_cuti) ? $id_pengajuan_cuti : 'null'; ?>" ng-cloak>
		<div class="col-md-5" ng-if="loaded">
			<div class="box">

				<form name="form" ng-model="form" ng-submit="submitForm(form.$valid)" novalidate>

					<div class="box-body">

						<div class="form-group">
							<label for="no_surat">No Surat</label>
							<input type="text" name="no_surat" ng-model="$parent.no_surat" id="no_surat" class="form-control" placeholder="No Surat" required>
						</div>

						<div class="form-group" style="display: inline-table; width: 100%;">
							<label for="pegawai">Pegawai</label>
							<select id="pegawai" class="form-control select2" ng-model="$parent.pegawai" ng-options="item as item.PNS_NAMA for item in allPegawai track by item.PNS_PNSNIP" required>
								<option value="">- Pilih Pegawai -</option>
							</select>
						</div>

						<div class="form-group" style="display: inline-table; width: 100%;">
							<label for="id_master_jenis_cuti">Jenis Cuti</label>
							<select id="id_master_jenis_cuti" class="form-control select2" ng-model="$parent.id_master_jenis_cuti" ng-options="item as item.jenis_cuti for item in allJenisCuti track by item.id" required>
								<option value="">- Pilih Jenis Cuti -</option>
							</select>
						</div>

						<div class="form-group">
							<label for="lama_cuti">Lama Cuti</label>
							<input type="text" name="lama_cuti" ng-model="$parent.lama_cuti" id="lama_cuti" class="form-control" placeholder="Lama Cuti" required>
						</div>

						<div class="form-group" style="display: inline-table; width: 100%;">
							<label for="penandatangan">Penandatangan</label>
							<select id="penandatangan" class="form-control select2" ng-model="$parent.penandatangan" ng-options="item as item.PNS_NAMA for item in allPegawai track by item.PNS_PNSNIP" required>
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

		<div class="col-md-7" ng-if="loaded" ng-cloak>
			<div class="box">
				<div class="box-header with-border">
					<h3 class="box-title">Preview</h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-primary" style="position: absolute; top: -12px; right: 0;" ng-show="btnPrint == true" ng-click="printBox()">Cetak</button>
					</div>
				</div>

				<div class="box-body" ng-if="alerts != ''">
					<div uib-alert ng-repeat="alert in alerts" ng-class="'alert-' + (alert.type || 'warning')" close="closeAlert($index)" style="margin: 0;" ng-cloak>{{alert.msg}}</div>
				</div>

				<div id="print-box">
					<div class="box-body" ng-if="id_master_jenis_cuti.id == '1'">
						<table>
							<tr>
								<td><img ng-src="{{base_url + '/assets/img/Lambang_Kabupaten_Kotawaringin_Barat.png'}}" width="50"></td>
								<td style="width: 100%; text-align: center;">
									PEMERINTAH KABUPATEN KOTAWARINGIN BARAT<br>
									{{allSOPD[0].NM_UNOR | uppercase}}
								</td>
							</tr>
							<tr>
								<td colspan="2" style="border-bottom: 1px solid #ccc; padding-top: 1em;"></td>
							</tr>
							<tr>
								<th colspan="2" class="text-center" style="padding-top: 1em;">
									<p>
										{{'Surat ' + id_master_jenis_cuti.jenis_cuti | uppercase}}<br>
										NOMOR: {{no_surat}}
									</p>
								</th>
							</tr>
						</table>
						<table>
							<tr>
								<td style="vertical-align: initial; min-width: 20px;">1.</td>
								<td>
									Diberikan {{id_master_jenis_cuti.jenis_cuti}} untuk Tahun {{date | date: 'yyyy'}} Kepada Pegawai Negeri Sipil:
									<table style="margin: 1em 3em;">
										<tr>
											<td>Nama</td>
											<td class="colon">:</td>
											<td>{{pegawai.PNS_NAMA}}</td>
										</tr>
										<tr>
											<td>NIP</td>
											<td class="colon">:</td>
											<td>{{checkIsTKD(pegawai.PNS_PNSNIP)}}</td>
										</tr>
										<tr>
											<td>Pangkat/Gol.Ruang</td>
											<td class="colon">:</td>
											<td ng-if="pegawai.NM_PKT && pegawai.NM_GOL">{{pegawai.NM_PKT + ' / ' + pegawai.NM_GOL}}</td>
											<td ng-if="!pegawai.NM_PKT && !pegawai.NM_GOL">-</td>
										</tr>
										<tr>
											<td>Jabatan</td>
											<td class="colon">:</td>
											<td ng-if="checkIsTKD(pegawai.PNS_PNSNIP) != '-'">{{pegawai.NM_GENPOS}}</td>
											<td ng-if="checkIsTKD(pegawai.PNS_PNSNIP) == '-'">Tenaga Kontrak Daerah</td>
										</tr>
										<tr>
											<td>Unit Kerja</td>
											<td class="colon">:</td>
											<td>{{allSOPD[0].NM_UNOR}}</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td></td>
								<td>
									Selama {{lama_cuti}} dengan ketentuan sebagai berikut:
									<ol type="a">
										<li>Sebelum menjalankan {{id_master_jenis_cuti.jenis_cuti}} wajib menyerahkan pekerjaan kepada Atasan Langsung.</li>
										<li>Setelah selesai menjalankan {{id_master_jenis_cuti.jenis_cuti}} melaporkan diri kepada Atasan Langsung dan bekerja kembali sebagaimana biasa.</li>
									</ol>
								</td>
							</tr>
							<tr>
								<td style="vertical-align: initial; min-width: 20px;">2.</td>
								<td>Demikian Surat {{id_master_jenis_cuti.jenis_cuti}} ini dibuat untuk dapat dipergunakan sebagaimana mestinya.</td>
							</tr>

							<tr>
								<td></td>
								<td class="" style="margin-top: 1em; float: right;">
									<div class="" style="text-align: center;">
										Dikeluarkan di Pangkalan Bun<br>
										Pada tanggal {{date | date: 'dd MMMM yyyy'}}<br>
										<div ng-if="penandatangan.NM_GENPOS !== 'Kepala'">
											An. Kepala Dinas<br>
											{{penandatangan.NM_GENPOS}}
										</div>
										<div ng-if="penandatangan.NM_GENPOS == 'Kepala'">
											Kepala Dinas
										</div>
										<br><br><br>
										{{penandatangan.PNS_NAMA}}<br>
										NIP. {{penandatangan.PNS_PNSNIP}}
									</div>
								</td>
							</tr>
						</table>
					</div>

					<div class="box-body" ng-if="id_master_jenis_cuti.id == '3'">
						<table>
							<tr>
								<td><img ng-src="{{base_url + '/assets/img/Lambang_Kabupaten_Kotawaringin_Barat.png'}}" width="50"></td>
								<td style="width: 100%; text-align: center;">
									PEMERINTAH KABUPATEN KOTAWARINGIN BARAT<br>
									{{allSOPD[0].NM_UNOR | uppercase}}
								</td>
							</tr>
							<tr>
								<td colspan="2" style="border-bottom: 1px solid #ccc; padding-top: 1em;"></td>
							</tr>
							<tr>
								<th colspan="2" class="text-center" style="padding-top: 1em;">
									<p>
										{{'Surat ' + (id_master_jenis_cuti.id != '3' ? id_master_jenis_cuti.jenis_cuti : 'Izin Sakit') | uppercase}}<br>
										NOMOR: {{no_surat}}
									</p>
								</th>
							</tr>
						</table>
						<table>
							<tr>
								<td style="vertical-align: initial; min-width: 20px;">1.</td>
								<td>
									Diberikan {{id_master_jenis_cuti.id != '3' ? id_master_jenis_cuti.jenis_cuti : 'Izin Sakit'}} Kepada Pegawai Negeri Sipil:
									<table style="margin: 1em 3em;">
										<tr>
											<td>Nama</td>
											<td class="colon">:</td>
											<td>{{pegawai.PNS_NAMA}}</td>
										</tr>
										<tr>
											<td>NIP</td>
											<td class="colon">:</td>
											<td>{{checkIsTKD(pegawai.PNS_PNSNIP)}}</td>
										</tr>
										<tr>
											<td>Pangkat/Gol.Ruang</td>
											<td class="colon">:</td>
											<td ng-if="pegawai.NM_PKT && pegawai.NM_GOL">{{pegawai.NM_PKT + ' / ' + pegawai.NM_GOL}}</td>
											<td ng-if="!pegawai.NM_PKT && !pegawai.NM_GOL">-</td>
										</tr>
										<tr>
											<td>Jabatan</td>
											<td class="colon">:</td>
											<td ng-if="checkIsTKD(pegawai.PNS_PNSNIP) != '-'">{{pegawai.NM_GENPOS}}</td>
											<td ng-if="checkIsTKD(pegawai.PNS_PNSNIP) == '-'">Tenaga Kontrak Daerah</td>
										</tr>
										<tr>
											<td>Unit Kerja</td>
											<td class="colon">:</td>
											<td>{{allSOPD[0].NM_UNOR}}</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td></td>
								<td>
									Selama {{lama_cuti}} dengan ketentuan sebagai berikut:
									<ol type="a">
										<li>Sebelum menjalankan {{id_master_jenis_cuti.id != '3' ? id_master_jenis_cuti.jenis_cuti : 'Izin Sakit'}} wajib menyerahkan pekerjaan kepada Atasan Langsung.</li>
										<li>Setelah selesai menjalankan {{id_master_jenis_cuti.id != '3' ? id_master_jenis_cuti.jenis_cuti : 'Izin Sakit'}} melaporkan diri kepada Atasan Langsung dan bekerja kembali sebagaimana biasa.</li>
									</ol>
								</td>
							</tr>
							<tr>
								<td style="vertical-align: initial; min-width: 20px;">2.</td>
								<td>Demikian Surat {{id_master_jenis_cuti.id != '3' ? id_master_jenis_cuti.jenis_cuti : 'Izin Sakit'}} ini dibuat untuk dapat dipergunakan sebagaimana mestinya.</td>
							</tr>

							<tr>
								<td></td>
								<td class="" style="margin-top: 1em; float: right;">
									<div class="" style="text-align: center;">
										Dikeluarkan di Pangkalan Bun<br>
										Pada tanggal {{date | date: 'dd MMMM yyyy'}}<br>
										<div ng-if="penandatangan.NM_GENPOS !== 'Kepala'">
											An. Kepala Dinas<br>
											{{penandatangan.NM_GENPOS}}
										</div>
										<div ng-if="penandatangan.NM_GENPOS == 'Kepala'">
											Kepala Dinas
										</div>
										<br><br><br>
										{{penandatangan.PNS_NAMA}}<br>
										NIP. {{penandatangan.PNS_PNSNIP}}
									</div>
								</td>
							</tr>
						</table>
					</div>

					<div class="box-body" ng-if="id_master_jenis_cuti.id == '5'">
						<table>
							<tr>
								<td><img ng-src="{{base_url + '/assets/img/Lambang_Kabupaten_Kotawaringin_Barat.png'}}" width="50"></td>
								<td style="width: 100%; text-align: center;">
									PEMERINTAH KABUPATEN KOTAWARINGIN BARAT<br>
									{{allSOPD[0].NM_UNOR | uppercase}}
								</td>
							</tr>
							<tr>
								<td colspan="2" style="border-bottom: 1px solid #ccc; padding-top: 1em;"></td>
							</tr>
							<tr>
								<th colspan="2" class="text-center" style="padding-top: 1em;">
									<p>
										{{'Surat ' + (id_master_jenis_cuti.id != '5' ? id_master_jenis_cuti.jenis_cuti : 'Izin Alasan Penting') | uppercase}}<br>
										NOMOR: {{no_surat}}
									</p>
								</th>
							</tr>
						</table>
						<table>
							<tr>
								<td style="vertical-align: initial; min-width: 20px;"></td>
								<td>
									Diberikan Surat {{id_master_jenis_cuti.id != '5' ? id_master_jenis_cuti.jenis_cuti : 'Izin Alasan Penting'}} Kepada:
									<table style="margin: 1em 3em;">
										<tr>
											<td>Nama</td>
											<td class="colon">:</td>
											<td>{{pegawai.PNS_NAMA}}</td>
										</tr>
										<tr>
											<td>NIP</td>
											<td class="colon">:</td>
											<td>{{checkIsTKD(pegawai.PNS_PNSNIP)}}</td>
										</tr>
										<tr>
											<td>Pangkat/Gol.Ruang</td>
											<td class="colon">:</td>
											<td ng-if="pegawai.NM_PKT && pegawai.NM_GOL">{{pegawai.NM_PKT + ' / ' + pegawai.NM_GOL}}</td>
											<td ng-if="!pegawai.NM_PKT && !pegawai.NM_GOL">-</td>
										</tr>
										<tr>
											<td>Jabatan</td>
											<td class="colon">:</td>
											<td ng-if="checkIsTKD(pegawai.PNS_PNSNIP) != '-'">{{pegawai.NM_GENPOS}}</td>
											<td ng-if="checkIsTKD(pegawai.PNS_PNSNIP) == '-'">Tenaga Kontrak Daerah</td>
										</tr>
										<tr>
											<td>Unit Kerja</td>
											<td class="colon">:</td>
											<td>{{allSOPD[0].NM_UNOR}}</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td style="vertical-align: initial; min-width: 20px;"></td>
								<td>
									Selama {{lama_cuti}}.
								</td>
							</tr>
							<tr>
								<td style="vertical-align: initial; min-width: 20px;"></td>
								<td>Demikian Surat {{id_master_jenis_cuti.id != '5' ? id_master_jenis_cuti.jenis_cuti : 'Izin Alasan Penting'}} ini dibuat untuk dapat dipergunakan sebagaimana mestinya.</td>
							</tr>

							<tr>
								<td></td>
								<td class="" style="margin-top: 1em; float: right;">
									<div class="" style="text-align: center;">
										Dikeluarkan di Pangkalan Bun<br>
										Pada tanggal {{date | date: 'dd MMMM yyyy'}}<br>
										<div ng-if="penandatangan.NM_GENPOS !== 'Kepala'">
											An. Kepala Dinas<br>
											{{penandatangan.NM_GENPOS}}
										</div>
										<div ng-if="penandatangan.NM_GENPOS == 'Kepala'">
											Kepala Dinas
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
	var app = angular.module('pceModule', ['oitozero.ngSweetAlert', 'ui.bootstrap']);

	app.controller('pceController', ['$scope', 'SweetAlert', '$http', '$window', '$timeout', '$location',
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
					$http.get(base_url + '/api/get_master_jenis_cuti')
						.then(function(response) {
							$scope.allJenisCuti = response.data;
						});
					if (scope.isEdit !== null) {
						$http.get(base_url + '/api/get_pengajuan_cuti', {
								params: {
									id: scope.isEdit
								}
							})
							.then(function(response) {
								$scope.no_surat = response.data.no_surat;
								$scope.pegawai = JSON.parse(response.data.pegawai);
								$scope.id_master_jenis_cuti = {id: response.data.id_master_jenis_cuti, jenis_cuti: response.data.jenis_cuti};
								$scope.lama_cuti = response.data.lama_cuti;
								$scope.penandatangan = JSON.parse(response.data.penandatangan);
								$scope.loaded = true;
							});
					} else {
						$scope.loaded = true;
					}
				});
			}

			$scope.alerts = [];
			$scope.closeAlert = function(index) {
				$scope.alerts.splice(index, 1);
			};

			$scope.btnPrint = false;

			$scope.submitForm = function(isValid) {
				if (isValid) {
					if ($scope.isEdit === 'null') {
						var pushSubmit = {
							method: 'POST',
							url: $location.absUrl(),
							headers: {
								'Content-Type': 'application/x-www-form-urlencoded'
							},
							data: $.param({
								no_surat: $scope.no_surat,
								pegawai: JSON.stringify($scope.pegawai),
								id_master_jenis_cuti: $scope.id_master_jenis_cuti['id'],
								lama_cuti: $scope.lama_cuti,
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
								no_surat: $scope.no_surat,
								pegawai: JSON.stringify($scope.pegawai),
								id_master_jenis_cuti: $scope.id_master_jenis_cuti['id'],
								lama_cuti: $scope.lama_cuti,
								penandatangan: JSON.stringify($scope.penandatangan)
							})
						}
					}

					$http(pushSubmit)
						.then(function(response) {
							if (response) {
								$("#btnSimpan").hide();
								$(".col-md-5").hide();
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
				printWindow.document.write('<html><head><title>Pengajuan Cuti</title>');
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

			$scope.checkIsTKD = function(val) {
				return !val.includes('TKD') ? val : '-';
			}

		}
	]);
</script>
