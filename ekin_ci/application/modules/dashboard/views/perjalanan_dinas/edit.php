<!--
# @Author: Awan Tengah
# @Date:   2019-08-12T08:53:49+07:00
# @Last modified by:   Awan Tengah
# @Last modified time: 2019-08-15T22:26:33+07:00
-->

<style>
	.table-inside td {
		padding: 0.2em 0.2em 0.2em 0.3em;
	}
	.colon {
		padding: 0 1em;
		width: 10px !important;
	}
	.border>tbody>tr>td {
        border: 1px solid #000;
        padding: 0.5em;
    }
</style>
<section class="content" ng-app="pdModule" ng-controller="pdController" data-ng-init="init()">
	<!-- Your Page Content Here -->
	<div class="row" ng-init="unor = <?php echo $_user_login->unor; ?>; isEdit = <?php echo isset($id_perjalanan_dinas) ? $id_perjalanan_dinas : 'null'; ?>">
		<div class="col-md-5">
			<div class="box">

				<form name="form" ng-submit="submitForm(form.$valid)" novalidate>
					<div class="box-body">

						<div class="form-group">
							<label for="no_surat">No Surat</label>
							<input type="text" name="no_surat" ng-model="no_surat" id="no_surat" class="form-control" placeholder="No Surat" required>
						</div>

						<div class="form-group">
							<label for="pejabat_perintah">Pejabat yang memberi perintah</label>
							<input type="text" name="pejabat_perintah" ng-model="pejabat_perintah" id="pejabat_perintah" class="form-control" placeholder="Pejabat yang memberi perintah" required>
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
							<label for="tingkat_biaya">Tingkat Biaya Perjalanan Dinas</label>
							<input type="text" name="tingkat_biaya" ng-model="tingkat_biaya" id="tingkat_biaya" class="form-control" placeholder="Tingkat Biaya Perjalanan Dinas" required>
						</div>

						<div class="form-group">
							<label for="maksud_perjalanan">Maksud Perjalanan Dinas</label>
							<input type="text" name="maksud_perjalanan" ng-model="maksud_perjalanan" id="maksud_perjalanan" class="form-control" placeholder="Maksud Perjalanan Dinas" required>
						</div>

						<div class="form-group">
							<label for="alat_angkutan">Alat Angkutan yang Digunakan</label>
							<input type="text" name="alat_angkutan" ng-model="alat_angkutan" id="alat_angkutan" class="form-control" placeholder="Alat Angkutan yang Digunakan" required>
						</div>

						<div class="form-group">
							<label for="tempat_berangkat">Tempat Berangkat</label>
							<input type="text" name="tempat_berangkat" ng-model="tempat_berangkat" id="tempat_berangkat" class="form-control" placeholder="Tempat Berangkat" required>
						</div>

						<div class="form-group">
							<label for="tempat_tujuan">Tempat Tujuan</label>
							<input type="text" name="tempat_tujuan" ng-model="tempat_tujuan" id="tempat_tujuan" class="form-control" placeholder="Tempat Tujuan" required>
						</div>

						<div class="form-group">
							<label for="lama_perjalanan">Lama Perjalanan Dinas</label>
							<input type="text" name="lama_perjalanan" ng-model="lama_perjalanan" id="lama_perjalanan" class="form-control" placeholder="Lama Perjalanan Dinas" required>
						</div>

						<div class="form-group">
							<label for="tanggal_berangkat">Tanggal Berangkat</label>
							<input type="text" name="tanggal_berangkat" ng-model="tanggal_berangkat" id="tanggal_berangkat" class="form-control" placeholder="Tanggal Berangkat" required>
						</div>

						<div class="form-group">
							<label for="tanggal_kembali">Tanggal Kembali</label>
							<input type="text" name="tanggal_kembali" ng-model="tanggal_kembali" id="tanggal_kembali" class="form-control" placeholder="Tanggal Kembali" required>
						</div>

						<div class="form-group" style="display: inline-table; width: 100%;">
							<label for="instansi_pa">Instansi Pembebanan Anggaran</label>
							<select id="instansi_pa" class="form-control select2" ng-model="instansi_pa" ng-options="item as item.NM_UNOR for item in allSOPD track by item.KD_UNOR" required>
								<option value="">- Pilih SOPD -</option>
							</select>
						</div>

						<div class="form-group">
							<label for="no_mata_anggaran">No. Mata Anggaran</label>
							<input type="text" name="no_mata_anggaran" ng-model="no_mata_anggaran" id="no_mata_anggaran" class="form-control" placeholder="No. Mata Anggaran" required>
						</div>

						<div class="form-group">
							<label for="mata_anggaran">Mata Anggaran</label>
							<input type="text" name="mata_anggaran" ng-model="mata_anggaran" id="mata_anggaran" class="form-control" placeholder="Mata Anggaran" required>
						</div>

						<div class="form-group">
							<label for="keterangan_lain">Keterangan Lain-Lain</label>
							<textarea name="keterangan_lain" ng-model="keterangan_lain" id="keterangan_lain" class="form-control" placeholder="Keterangan Lain-Lain" cols="30" rows="5"></textarea>
						</div>

						<div class="form-group">
							<label for="penandatangan">Penandatangan</label>
							<select id="penandatangan" class="form-control select2" ng-model="penandatangan" ng-options="item as item.PNS_NAMA for item in allPegawai track by item.PNS_PNSNIP" required>
								<option value="">- Pilih Pegawai -</option>
							</select>
						</div>

					</div>

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
				</div>

				<div class="box-body" ng-if="alerts != ''">
					<div uib-alert ng-repeat="alert in alerts" ng-class="'alert-' + (alert.type || 'warning')" close="closeAlert($index)" style="margin: 0;" ng-cloak>{{alert.msg}}</div>
				</div>

				<div class="box-body print-box">
					<!-- Custom Tabs -->
					<div class="nav-tabs-custom" ng-show="userList != ''">
						<ul class="nav nav-tabs">
							<li ng-repeat="user in userList" ng-class="$index == 0 ? 'active' : ''">
								<a href="#tab_{{$index}}" data-toggle="tab">{{user.PNS_NAMA}}</a>
							</li>
							<li>
								<a href="#tab_akhir" data-toggle="tab">Tampilan Belakang</a>
							</li>
						</ul>
						<div class="tab-content">
							<div ng-repeat="user in userList" ng-class="$index == 0 ? 'tab-pane active' : 'tab-pane'" id="tab_{{$index}}">
								<div class="box-tools pull-right">
									<button type="button" class="btn btn-primary" style="position: absolute; top: 5px; right: 6px;" ng-show="btnPrint == true" ng-click="printBox($index)">Cetak SPD: {{user.PNS_NAMA}}</button>
								</div>

								<div id="print-box-{{$index}}" class="">
									<table width="100%">
										<tr>
											<td><img ng-src="{{base_url + '/assets/img/Lambang_Kabupaten_Kotawaringin_Barat.png'}}" width="50"></td>
											<td colspan="2" style="text-align: center;">
												PEMERINTAH KABUPATEN KOTAWARINGIN BARAT<br>
												{{currentSOPD[0].NM_UNOR | uppercase}}
											</td>
										</tr>
										<tr>
											<td colspan="3" style="border-bottom: 1px solid #ccc; padding-top: 1em;"></td>
										</tr>
										<tr>
											<td colspan="2"></td>
											<td style="margin-top: 1em; float: right; width: 230px;">
												<table>
													<tr>
														<td width="100">Lembar ke</td>
														<td class="colon">:</td>
														<td></td>
													</tr>
													<tr>
														<td>Kode No.</td>
														<td class="colon">:</td>
														<td></td>
													</tr>
													<tr>
														<td>Nomor</td>
														<td class="colon">:</td>
														<td>{{no_surat}}</td>
													</tr>
												</table>
											</td>
										</tr>
										<tr>
											<th colspan="3" class="text-center" style="padding-top: 1em;">
												<p>
													SURAT PERJALANAN DINAS (SPD)
												</p>
											</th>
										</tr>
									</table>
									<table class="table table-bordered">
										<tr>
											<td width="10">1.</td>
											<td width="300">Pejabat yang memberi perintah</td>
											<td>{{pejabat_perintah}}</td>
										</tr>
										<tr>
											<td>2.</td>
											<td>Nama/NIP Pegawai yang Diperintahkan</td>
											<td>
												{{userList[$index].PNS_NAMA}}<br>
												NIP. {{userList[$index].PNS_PNSNIP}}
											</td>
										</tr>
										<tr>
											<td>3.</td>
											<td>
												<ol type="a">
													<li>Pangkat dan Golongan</li>
													<li>Jabatan/Instansi</li>
													<li>Tingkat Biaya Perjalanan Dinas</li>
												</ol>
											</td>
											<td>
												<ol type="a">
													<li>{{userList[$index].NM_PKT + ' / ' + userList[$index].NM_GOL}}</li>
													<li>{{userList[$index].NM_GENPOS}}</li>
													<li>{{tingkat_biaya}}</li>
												</ol>
											</td>
										</tr>
										<tr>
											<td>4.</td>
											<td>Maksud Perjalanan Dinas</td>
											<td>{{maksud_perjalanan}}</td>
										</tr>
										<tr>
											<td>5.</td>
											<td>Alat Angkutan yang Digunakan</td>
											<td>{{alat_angkutan}}</td>
										</tr>
										<tr>
											<td>6.</td>
											<td>
												<ol type="a">
													<li>Tempat Berangkat</li>
													<li>Tempat Tujuan</li>
												</ol>
											</td>
											<td>
												<ol type="a">
													<li>{{tempat_berangkat}}</li>
													<li>{{tempat_tujuan}}</li>
												</ol>
											</td>
										</tr>
										<tr>
											<td>7.</td>
											<td>
												<ol type="a">
													<li>Lamanya Perjalanan Dinas</li>
													<li>Tanggal Berangkat</li>
													<li>Tanggal Harus Kembali/Tiba Di Tempat Baru</li>
												</ol>
											</td>
											<td>
												<ol type="a">
													<li>{{lama_perjalanan}}</li>
													<li>{{tanggal_berangkat}}</li>
													<li>{{tanggal_kembali}}</li>
												</ol>
											</td>
										</tr>
										<tr>
											<td>8.</td>
											<td style="padding: 0;">
												<table class="table-inside" width="100%">
													<tr>
														<td width="50%" style="border-bottom: 1px solid #ccc; border-right: 1px solid #ccc;">Pengikut:</td>
														<td width="50%" style="border-bottom: 1px solid #ccc;">Nama</td>
													</tr>
													<tr>
														<td style="border-right: 1px solid #ccc;">1.</td>
														<td></td>
													</tr>
													<tr>
														<td style="border-right: 1px solid #ccc;">2.</td>
														<td></td>
													</tr>
												</table>
											</td>
											<td style="padding: 0;">
												<table class="table-inside" width="100%">
													<tr>
														<td width="50%" style="text-align: center; border-bottom: 1px solid #ccc; border-right: 1px solid #ccc;">Tanggal Lahir</td>
														<td width="50%" style="text-align: center; border-bottom: 1px solid #ccc;">Keterangan</td>
													</tr>
													<tr>
														<td style="border-right: 1px solid #ccc;">&nbsp</td>
														<td></td>
													</tr>
													<tr>
														<td style="border-right: 1px solid #ccc;">&nbsp</td>
														<td></td>
													</tr>
												</table>
											</td>
										</tr>
										<tr>
											<td>9.</td>
											<td>
												Pembebanan Anggaran
												<ol type="a">
													<li>Instansi</li>
													<li>Mata Anggaran</li>
												</ol>
											</td>
											<td>
												<ol type="a">
													<li>{{instansi_pa.NM_UNOR}}</li>
													<li>
														{{no_mata_anggaran}}<br>
														{{mata_anggaran}}
													</li>
												</ol>
											</td>
										</tr>
										<tr>
											<td>10.</td>
											<td>Keterangan Lain-Lain:</td>
											<td>{{keterangan_lain}}</td>
										</tr>
									</table>
									<table width="100%">
										<tr>
											<td class="" style="margin-top: 1em; float: right;">
												<div class="" style="text-align: center;">
													Dikeluarkan di Pangkalan Bun<br>
													Pada tanggal {{date | date: 'dd MMMM yyyy'}}<br>
													Kuasa Pengguna Anggaran
													<br><br><br>
													{{penandatangan.PNS_NAMA}}<br>
													NIP. {{penandatangan.PNS_PNSNIP}}
												</div>
											</td>
										</tr>
									</table>

								</div>
							</div>
							<!-- /.tab-pane -->
							<div class="tab-pane" id="tab_akhir">
								<div class="box-tools pull-right">
									<button type="button" class="btn btn-primary" style="position: absolute; top: 5px; right: 6px;" ng-show="btnPrint == true" ng-click="printBelakang()">Cetak SPD Tampilan Belakang</button>
								</div>

								<div id="print-box-back" class="">
									<table class="border">
										<tr>
											<td width="50%"></td>
											<td width="50%">
												<table>
													<tr style="vertical-align: top;">
														<td width="10">I.</td>
														<td>
															Berangkat Dari<br>
															(Tempat Kedudukan)
														</td>
														<td class="colon">:</td>
														<td>{{tempat_berangkat}}</td>
													</tr>
													<tr>
														<td></td>
														<td>Ke</td>
														<td class="colon">:</td>
														<td>{{tempat_tujuan}}</td>
													</tr>
													<tr>
														<td></td>
														<td>Pada Tanggal</td>
														<td class="colon">:</td>
														<td>{{tanggal_berangkat}}</td>
													</tr>
													<tr>
														<td colspan="4" style="text-align: center;">
															<div style="padding: 0.5em 4em;">
																{{penandatangan.NM_GENPOS}}
																<br><br><br>
																<strong>{{penandatangan.PNS_NAMA}}</strong> <br>
																NIP. {{penandatangan.PNS_PNSNIP}}
															</div>
														</td>
													</tr>
												</table>
											</td>
										</tr>
										<tr style="vertical-align: top;">
											<td>
												<table>
													<tr>
														<td width="10">II.</td>
														<td>Tiba di</td>
														<td class="colon">:</td>
														<td>{{tempat_tujuan}}</td>
													</tr>
													<tr>
														<td></td>
														<td>Pada Tanggal</td>
														<td class="colon">:</td>
														<td>{{tanggal_berangkat}}</td>
													</tr>
												</table>
											</td>
											<td style="padding-bottom: 8em;">
												<table>
													<tr>
														<td>Berangkat Dari</td>
														<td class="colon">:</td>
														<td>{{tempat_tujuan}}</td>
													</tr>
													<tr>
														<td>Ke</td>
														<td class="colon">:</td>
														<td>{{tempat_berangkat}}</td>
													</tr>
													<tr>
														<td>Pada Tanggal</td>
														<td class="colon">:</td>
														<td>{{tanggal_kembali}}</td>
													</tr>
												</table>
											</td>
										</tr>
										<tr style="vertical-align: top;">
											<td>
												<table>
													<tr>
														<td width="10">III.</td>
														<td>Tiba di</td>
														<td class="colon">:</td>
														<td></td>
													</tr>
													<tr>
														<td></td>
														<td>Pada Tanggal</td>
														<td class="colon">:</td>
														<td></td>
													</tr>
												</table>
											</td>
											<td style="padding-bottom: 8em;">
												<table>
													<tr>
														<td>Berangkat Dari</td>
														<td class="colon">:</td>
														<td></td>
													</tr>
													<tr>
														<td>Ke</td>
														<td class="colon">:</td>
														<td></td>
													</tr>
													<tr>
														<td>Pada Tanggal</td>
														<td class="colon">:</td>
														<td></td>
													</tr>
												</table>
											</td>
										</tr>
										<tr style="vertical-align: top;">
											<td>
												<table>
													<tr>
														<td width="10">IV.</td>
														<td>Tiba di</td>
														<td class="colon">:</td>
														<td></td>
													</tr>
													<tr>
														<td></td>
														<td>Pada Tanggal</td>
														<td class="colon">:</td>
														<td></td>
													</tr>
												</table>
											</td>
											<td style="padding-bottom: 8em;">
												<table>
													<tr>
														<td>Berangkat Dari</td>
														<td class="colon">:</td>
														<td></td>
													</tr>
													<tr>
														<td>Ke</td>
														<td class="colon">:</td>
														<td></td>
													</tr>
													<tr>
														<td>Pada Tanggal</td>
														<td class="colon">:</td>
														<td></td>
													</tr>
												</table>
											</td>
										</tr>
										<tr style="vertical-align: top;">
											<td style="position: relative;">
												<table>
													<tr>
														<td width="10">V.</td>
														<td>Tiba Kembali Di</td>
														<td class="colon">:</td>
														<td>{{tempat_berangkat}}</td>
													</tr>
													<tr>
														<td></td>
														<td>Pada Tanggal</td>
														<td class="colon">:</td>
														<td>{{tanggal_kembali}}</td>
													</tr>
													<tr style="position: absolute; bottom: 8px;">
														<td colspan="4" style="text-align: center;">
															<div style="padding: 0.5em 4em;">
																{{penandatangan.NM_GENPOS}}
																<br><br><br>
																<strong>{{penandatangan.PNS_NAMA}}</strong> <br>
																NIP. {{penandatangan.PNS_PNSNIP}}
															</div>
														</td>
													</tr>
												</table>
											</td>
											<td>
												<table>
													<tr>
														<td>
															<p>Telah Diperiksa dengan keterangan bahwa perjalanan tersebut di atas benar dilakukan atas perintahnya dan semata-mata untuk kepentingan jabatan dalam waktu sesingkat-singkatnya.</p>
														</td>
													</tr>
													<tr>
														<td style="text-align: center;">
															<div style="padding: 0.5em 4em;">
																{{penandatangan.NM_GENPOS}}
																<br><br><br>
																<strong>{{penandatangan.PNS_NAMA}}</strong> <br>
																NIP. {{penandatangan.PNS_PNSNIP}}
															</div>
														</td>
													</tr>
												</table>
											</td>
										</tr>
										<tr>
											<td colspan="2">
												Catatan lain
											</td>
										</tr>
										<tr>
											<td colspan="2">
												<p>Perhatian</p>
												<p>
													Pejabat yang berwenang menerbitkan SPPD, pegawai yang melakukan perjalanan dinas, para pejabat
													yang mengesahkan tanggal berangkat/tiba, serta bendaharawan yang bertanggung jawab berdasarkan
													peraturan-peraturan keuangan negara apabila negara menderita kerugian akibat kesalahan, kelalaian, kealpaan dan ........
												</p>
											</td>
										</tr>
									</table>
								</div>
							</div>
						</div>
						<!-- /.tab-content -->
					</div>
					<!-- nav-tabs-custom -->
				</div>
			</div>
		</div>

	</div>

	<script type="text/ng-template" id="alert.html">
		<div ng-transclude></div>
    </script>

</section>

<script>
	var app = angular.module('pdModule', ['oitozero.ngSweetAlert', 'ui.bootstrap']);

	app.controller('pdController', ['$scope', 'SweetAlert', '$http', '$timeout', '$location',
		function($scope, SweetAlert, $http, $timeout, $location) {

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
							$scope.currentSOPD = response.data;
						});
					$http.get(base_url + '/api/get_all_sopd')
						.then(function(response) {
							$scope.allSOPD = response.data;
						});
					if (scope.isEdit !== null) {
						$http.get(base_url + '/api/get_perjalanan_dinas', {
								params: {
									id: scope.isEdit
								}
							})
							.then(function(response) {
								$scope.no_surat = response.data.no_surat;
								$scope.pejabat_perintah = response.data.pejabat_perintah;
								$scope.userList = JSON.parse(response.data.memerintahkan);
								$scope.memerintahkan = $scope.userList[0];
								$scope.tingkat_biaya = response.data.tingkat_biaya;
								$scope.maksud_perjalanan = response.data.maksud_perjalanan;
								$scope.alat_angkutan = response.data.alat_angkutan;
								$scope.tempat_berangkat = response.data.tempat_berangkat;
								$scope.tempat_tujuan = response.data.tempat_tujuan;
								$scope.lama_perjalanan = response.data.lama_perjalanan;
								$scope.tanggal_berangkat = response.data.tanggal_berangkat;
								$scope.tanggal_kembali = response.data.tanggal_kembali;
								$scope.instansi_pa = JSON.parse(response.data.instansi_pa);
								$scope.no_mata_anggaran = response.data.no_mata_anggaran;
								$scope.mata_anggaran = response.data.mata_anggaran;
								$scope.keterangan_lain = response.data.keterangan_lain;
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
							url: base_url + '/dashboard/perjalanan-dinas/add',
							headers: {
								'Content-Type': 'application/x-www-form-urlencoded'
							},
							data: $.param({
								no_surat: $scope.no_surat,
								pejabat_perintah: $scope.pejabat_perintah,
								memerintahkan: JSON.stringify($scope.userList),
								tingkat_biaya: $scope.tingkat_biaya,
								maksud_perjalanan: $scope.maksud_perjalanan,
								alat_angkutan: $scope.alat_angkutan,
								tempat_berangkat: $scope.tempat_berangkat,
								tempat_tujuan: $scope.tempat_tujuan,
								lama_perjalanan: $scope.lama_perjalanan,
								tanggal_berangkat: $scope.tanggal_berangkat,
								tanggal_kembali: $scope.tanggal_kembali,
								instansi_pa: JSON.stringify($scope.instansi_pa),
								no_mata_anggaran: $scope.no_mata_anggaran,
								mata_anggaran: $scope.mata_anggaran,
								keterangan_lain: $scope.keterangan_lain,
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
								pejabat_perintah: $scope.pejabat_perintah,
								memerintahkan: JSON.stringify($scope.userList),
								tingkat_biaya: $scope.tingkat_biaya,
								maksud_perjalanan: $scope.maksud_perjalanan,
								alat_angkutan: $scope.alat_angkutan,
								tempat_berangkat: $scope.tempat_berangkat,
								tempat_tujuan: $scope.tempat_tujuan,
								lama_perjalanan: $scope.lama_perjalanan,
								tanggal_berangkat: $scope.tanggal_berangkat,
								tanggal_kembali: $scope.tanggal_kembali,
								instansi_pa: JSON.stringify($scope.instansi_pa),
								no_mata_anggaran: $scope.no_mata_anggaran,
								mata_anggaran: $scope.mata_anggaran,
								keterangan_lain: $scope.keterangan_lain,
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

			$scope.printBelakang = function() {
				var left = ($(window).width() / 2) - (900 / 2),
					top = ($(window).height() / 2) - (600 / 2),
					popup = window.open("", "popup", "width=900, height=600, top=" + top + ", left=" + left);

				var divContents = $("#print-box-back").html();
				var printWindow = window.open('', 'popup', 'width=900, height=600, top=' + top + ', left=' + left);
				printWindow.document.write('<html><head><title>Perjalanan Dinas</title>');
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

			$scope.printBox = function(id_box) {
				var left = ($(window).width() / 2) - (900 / 2),
					top = ($(window).height() / 2) - (600 / 2),
					popup = window.open("", "popup", "width=900, height=600, top=" + top + ", left=" + left);

				var divContents = $("#print-box-" + id_box).html();
				var printWindow = window.open('', 'popup', 'width=900, height=600, top=' + top + ', left=' + left);
				printWindow.document.write('<html><head><title>Perjalanan Dinas</title>');
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
