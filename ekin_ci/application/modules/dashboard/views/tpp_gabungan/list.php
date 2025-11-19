<style>
	/*
 * @Author: Awan Tengah
 */

	.green-thead {
		background: #079992;
		color: #fff;
	}

	.table-bordered>thead.green-thead>tr>th {
		border-bottom-width: 1px !important;
	}

	p {
		 font-weight: bold !important;
	}

</style>

<!-- Main content -->
<section class="content" data-lampiranPath="<?php echo get_config_item('lampiran_path'); ?>">

	<!-- Your Page Content Here -->
	<div class="box">
		<div class="box-header with-border" style="padding-bottom: 0;">
			<div class="row">
				<div class="col-md-12">
					<div class="row">

						<div class="col-md-2">
							<div class="form-group">
								<select class="form-control" name="selected_month" onchange="getData(0)">
									<option value="">- Pilih Bulan -</option>
									<?php if ($all_month): ?>
									<?php foreach ($all_month as $row): ?>
									<option value="<?php echo encode_crypt($row->month); ?>">
										<?php echo $row->month_text; ?></option>
									<?php endforeach;?>
									<?php endif;?>
								</select>
							</div>
						</div>

						<div class="col-md-2">
							<div class="form-group">
								<select class="form-control" name="selected_year" onchange="getData(0)">
									<option value="">- Pilih Tahun -</option>
									<?php if ($all_year): ?>
									<?php foreach ($all_year as $row): ?>
									<option value="<?php echo $row->year; ?>"
										<?php echo $row->year == date('Y') ? 'selected' : ''; ?>>
										<?php echo $row->year; ?></option>
									<?php endforeach;?>
									<?php endif;?>
								</select>
							</div>
						</div>

						<div class="col-md-4">
							<!-- <?php var_dump($all_sopd[0]) ?> -->
							<div class="form-group">
								<select class="form-control select2" name="selected_sopd" onchange="getData(1)"
									style="width: 100%;">
									<?php if (get_session('id_groups') == '1' || get_session('id_groups') == '5' || get_session('id_groups') == '6' || $this->_user_login->PNS_PNSNIP == '197712242005012006'): ?>
									<option value="">- Pilih SOPD -</option>
									<?php endif;?>
									<?php if ($all_sopd): ?>
										
									<?php foreach ($all_sopd as $row):  ?>
									
									<option value="<?php echo encode_crypt($row->KD_UNOR); ?>">
										<!-- <?php if($row->Status_UNOR == 'aktif'): ?> -->
										<?php echo $row->NM_UNOR; ?>
									<!-- <?php endif ?> -->
									</option>
										
									<?php endforeach;?>
									<?php endif;?>
								</select>
							</div>
						</div>

					
						<?php if (get_session('id_groups') == '1' || get_session('id_groups') == '2' || get_session('id_groups') == '5' || get_session('id_groups') == '6'): ?>
						<div class="col-md-4">
							<!-- <?php var_dump($all_sopd[0]) ?> -->
							<div class="form-group">
							<select class="form-control select2" name="id_penanda_tangan" id="selected_penanda_tangan" onchange="getData(0)"
									style="width: 100%;">
	
									<option value="">- Pilih Penanda Tangan -</option>
								</select>
							</div>
						</div>	
					<?php endif; ?>
					<!-- <select class="form-control select2"  name="selected_plt" id="id_selected_plt" onchange="getData(0)"> <option value="">- Pilih PLT-</option>	</select> -->
						<hr>
						<div class="col-md-4" style="display: inline-flex; padding-bottom: 1em;">
							<?php if (get_session('id_groups') == '1' || get_session('id_groups') == '2' || get_session('id_groups') == '5' || get_session('id_groups') == '6'): ?>
							<div id="printTPPGabungan"></div>
							<?php if (get_session('id_groups') == '1' || get_session('id_groups') == '2' || get_session('id_groups') == '5' || get_session('id_groups') == '6'): ?>
							<div id="printTPPGabunganExcel" style="margin-left: 0.5em;"></div>
							<div id="printTemplateSIPD" style="margin-left: 0.5em;"></div>
							<div id="uploadDOC" style="margin-left: 0.5em;"></div>
							<?php endif;?>
							<?php endif;?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="box-body" style="padding-top: 0;">
			<div class="alert alert-info" role="alert">
				<i class="fa fa-info-circle"></i> <strong>TPP Gabungan</strong> hanya bisa <strong>DICETAK</strong> bila
				Pekerjaan sudah <strong>terverifikasi oleh BKPP</strong> dan data sudah direkap oleh sistem..<br>
				Sistem melakukan rekap sebanyak 2 kali, setiap pukul <strong>10:30 & 14:30</strong>. Terima Kasih..
			</div>
			<!-- <div class="alert alert-danger" role="alert">
				<i class="fa fa-info-circle"></i> <strong>TPP Gabungan</strong> sedang dalam <strong>pengujian</strong> untuk tahun 2021 dan <strong>bukan nilai sebenarnya</strong>..
			</div> -->
			<div class="table-responsive">
				<?php alert_message_dashboard();?>
				<table id="datatableTPPGabungan" class="table table-striped table-bordered table-hover"
					style="width: 100%;">
					
					<thead>
						<tr>
							<th class="th-top">No</th>
							<th class="th-top">Nama / NIP / GOLRU / GRADE</th>
							<th class="th-top">PANGKAT / JABATAN</th>
							<th class="th-top">PRESTASI KERJA <span id="prestasi_kerja"></span> / AKTIVITAS KERJA (MENIT)  </th>	
							<th class="th-top">BEBAN KERJA (40%) / SPK</th>
							<th class="th-top">TKS / PENGURANGAN CPNS</th>
							<th class="th-top">KONDISI KERJA /  KELANGKAAN PROFESI / TEMPAT BERTUGAS  </th>
							<th class="th-top">JUMLAH TPP / TUNJ. PLT / RAPEL</th>
							<th class="th-top">1% BPJS</th>
							<th class="th-top">PPH</th>
							<th class="th-top">JUMLAH TPP YG DITERIMA</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td colspan="12" class="text-center">No data available in table</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<!-- Modal -->
	<div class="modal fade" id="uploadDOCModal" tabindex="-1" role="dialog" aria-labelledby="uploadDOCModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
							aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="uploadDOCModalLabel"></h4>
				</div>
				<?php echo form_open_multipart('dashboard/tpp-gabungan/uploadDOC'); ?>
				<div class="modal-body"></div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary">Upload</button>
				</div>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>

</section>
<!-- /.content -->

<script>
	
	const NUMBER = (value) => currency(value, {
		symbol: "",
		precision: 0,
		separator: "."
	});

	let is_plt = false;
	
	function getPenandaTangan() {
	
		let selected_sopd = $("select[name=selected_sopd]").val();
		let selectedValue = $("select[name=id_penanda_tangan]").val();
		// variabel untuk menyimpan data dari URL pertama dan URL kedua
		var data1, data2;

		// URL pertama
		$.get(base_url + 'api/get_penanda_tangan', { unor: selected_sopd }, function(response1) {
		data1 = response1;
		combineData();
		});

		// URL kedua
		$.get(base_url + 'api/get_penanda_tangan_plt', { unor: selected_sopd }, function(response2) {
		data2 = response2;
		combineData();
		});


			// fungsi untuk menggabungkan data dari kedua URL
			function combineData() {
			// jika kedua variabel data1 dan data2 sudah terisi
			if (data1 && data2) {
				// membersihkan select
				$("select[name=id_penanda_tangan]").html('');

				// membuat optgroup
				var optgroup0 = $('<optgroup label="Pilih Penanda Tangan"></optgroup>');
				var optgroup1 = $('<optgroup label="PNS"></optgroup>');
				var optgroup2 = $('<optgroup label="PLT"></optgroup>');

				optgroup0.append( '<option value="">- Pilih Penanda Tangan-</option>');
				// menambahkan option ke masing-masing optgroup
				$.each(data1, function (key, value) {
				var selected = '';
				if (selectedValue === value.PNS_PNSNIP) {
					selected = 'selected';
					is_plt = false;
				
				}
				
				optgroup1.append('<option value="' + value.PNS_PNSNIP + '" '+ selected +'>' + value.PNS_NAMA + ' | ' + value.nama_jabatan + '</option>');
				});

				$.each(data2, function (key, values) {
				var selected_plt = '';
				if (selectedValue === values.PNS_PNSNIP) {
					selected_plt = 'selected';	
					is_plt = true;
	
				}
				optgroup2.append('<option value="' + values.PNS_PNSNIP + '" '+ selected_plt +'>' + values.PNS_NAMA + ' | '  +  values.nama_jabatan_plt +'</option>');	
			});
		
				// menambahkan optgroup ke dalam select
				$("select[name=id_penanda_tangan]").append(optgroup0);
				$("select[name=id_penanda_tangan]").append(optgroup1);
				$("select[name=id_penanda_tangan]").append(optgroup2);
				

				// mengatur event listener untuk elemen select
				$("select[name=id_penanda_tangan]").on("change", function() {
					var selectedValue = $(this).val();
					// menampilkan nilai yang dipilih pada elemen HTML dengan ID "selected-value"
					$("#selected-value").text(selectedValue);
					
					
				});
			}	
		
	}

	
}

	function getData(nilai_angka) {

		let selected_sopd = $("select[name=selected_sopd]").val();
		let selected_month = $("select[name=selected_month]").val();
		let selected_year = $("select[name=selected_year]").val();
		let selected_ttd = $("select[name=id_penanda_tangan]").val();
		let value_plt = $("select[name=selected_plt]").val();
		let selected_month_text = $("select[name=selected_month] option:selected").text();
		let real_month = $("select[name=selected_month]").prop('selectedIndex');
		

		$("#printTPPGabungan").html('');
		$("#printTPPGabunganExcel").html('');
		$("#printTemplateSIPD").html('');
		$("#uploadDOC").html('');
		$("#prestasi_kerja").html('');
		

		if(nilai_angka == 1){
			selected_ttd = ''
		}

		$("#selected_penanda_tangan").val(selected_ttd);
		if (selected_sopd && selected_month && selected_year) {
				if(selected_year == 2023){
							$("#prestasi_kerja").append(" (60%)")
						}
						if(selected_year != 2023){
							$("#prestasi_kerja").append("(60%)")
						}

			
			
			$.get(base_url + '/api/get_master_pengurangan_tpp', {
				year: selected_year
			})
			.then(function(response) {
				if(response) {
					$("#percenPengurangan").html(`${response.pengurangan}%`);
				} else {
					$("#percenPengurangan").html(`-%`);
				}
			});

			$.get(base_url + '/api/check_rekap_tpp_gabungan_exists', {
					unor_encrypt: selected_sopd,
					month: selected_month,
					year: selected_year,
					
				})
				.then(function (response) {
					if (response) {
							getPenandaTangan();
						if (real_month == 6) {
							$('#uploadDOCModal').find('.modal-title').html('');
							$('#uploadDOCModal').find('.modal-body').html('');

							let lampiranPath = $(".content").attr('data-lampiranPath');
							var modalBody = '';
							
							
							$.get(base_url + '/api/get_tpp_gabungan_doc', {
									unor_encrypt: selected_sopd,
									month_encrypt: selected_month,
									year: selected_year,
								})
								.then(function (responsexx) {
									
									if (responsexx) {
										modalBody += "<a href='" + base_url + lampiranPath + responsexx.doc +
											"' class='btn btn-info btn-sm' style='margin-bottom: 1em;'>Download Dokumen</a>";
									}

									modalBody += "<input type='hidden' name='selected_sopd_modal' value='" +
										selected_sopd + "'>" +
										"<input type='hidden' name='selected_month_modal' value='" +
										selected_month + "'>" +
										"<input type='hidden' name='selected_year_modal' value='" + selected_year +
										"'>" + 
										"<div class='form-group'>" +
										"<label>Dokumen</label>" +
										"<input type='file' name='doc_upload_modal' class='form-control'>" +
										"</div>";

									$('#uploadDOCModal').find('.modal-title').append(
										'Upload Dokumen TPP Gabungan: ' + selected_month_text);
									$('#uploadDOCModal').find('.modal-body').append(modalBody);
									$("#uploadDOC").html(
										'<a class="btn btn-primary" data-toggle="modal" data-target="#uploadDOCModal">Upload DOC</a>'
									);
								});
						}
							
						
				
						$("#printTPPGabungan").append('<a href=' + base_url + '/dashboard/tpp-gabungan/report/' +
							selected_sopd + '/' + selected_month + '/' + selected_year + '/' + selected_ttd +
							' target="_blank" class="' + (response ? (response ? 'btn btn-primary' :
								'btn btn-default') : 'btn btn-default') + '">' + (response ? (response ?
								'Arsip Laporan PDF' : 'Cetak Laporan') : 'Cetak Laporan') + '</a>');
						$("#printTPPGabunganExcel").append('<a href=' + base_url +
							'/dashboard/tpp-gabungan/report-excel/' + selected_sopd + '/' + selected_month + '/' +
							selected_year + ' class="' + (response ? (response ? 'btn btn-primary' :
								'btn btn-default') : 'btn btn-default') + '">' + (response ? (response ?
								'Arsip Laporan Excel' : 'Laporan Excel') : 'Laporan Excel') + '</a>');

						$("#printTemplateSIPD").append('<a href=' + base_url +
							'/dashboard/tpp-gabungan/template-sipd/' + selected_sopd + '/' + selected_month + '/' +
							selected_year + ' class="' + (response ? (response ? 'btn btn-primary' :
								'btn btn-default') : 'btn btn-default') + '">' + (response ? (response ?
								'Template SIPD' : 'Template SIPD') : 'Template SIPD') + '</a>');
					}
				});

	

			let datatable = $("#datatableTPPGabungan").DataTable({
				"destroy": true,
				"aaSorting": [],
				"bSort": false,
				"createdRow": function (row, data, dataIndex) {
					row.children[0].classList.add("text-center");
					row.children[3].classList.add("text-center");
					row.children[4].classList.add("text-right");
					row.children[5].classList.add("text-right");
					row.children[6].classList.add("text-right");
					row.children[7].classList.add("text-right");
					row.children[8].classList.add("text-right");
					row.children[9].classList.add("text-right");
					row.children[10].classList.add("text-right");
					// row.children[11].classList.add("text-right");
					if (data.keterangan_rapel !== '' && data.keterangan_rapel !== null) {
						$('#datatableTPPGabungan').DataTable().row(dataIndex).child(
							$(
								'<tr>' +
									'<td></td>' +
									'<td colspan="11"><strong>Keterangan Rapel: </strong>' + data.keterangan_rapel + '</td>' +
								'</tr>'
							)
						).show();
					}
				},
				"ajax": base_url + '/dashboard/tpp_gabungan/get_data?unor=' + selected_sopd + '&month=' +
					selected_month + '&year=' + selected_year,
				"columns": [{
						"data": "no",
						"render": function (data, type, row, meta) {
						return meta.row + 1;
			}
					},
							{
				"data": null,
				"render": function (data, type, row) {
					// Combine the values of "ket_pns" and "eselon_jabatan_pns"
					return row.ket_pns + ' <br> <strong>'  + row.kelas_jabatan + '</strong>';
				}
},

					{
						"data": "eselon_jabatan_pns"
					},
					
					{
						"data": "total_norma_waktu",
						"render": function (data, type, row) {
							// return NUMBER(data).format(true);
							
								return NUMBER(row.tpp_prestasi_kerja).format(true)  + '<br> <strong>' + NUMBER(data).format(true)	 + '</strong>';
							
						}
					},
					// {
					// 	"data": "tpp_prestasi_kerja",
					// 	"render": function (data, type, row) {
					// 		return NUMBER(data).format(true);
					// 	}
					// },
					{
						"data": "tpp_beban_kerja",
						"render": function (data, type, row) {
							return NUMBER(data).format(true) + '<br><strong>' + row
								.persentase_indikator_kehadiran + '%</strong>';
						}
					},
					{
						"data": "besaran_hukuman_tks",
						"render": function (data, type, row) {
							return NUMBER(data).format(true) + '<br>' + NUMBER(row.pengurangan_cpns)
								.format(true);
						}
					},
					{
						"data": "tpp_kondisi_kerja",
						"render": function (data, type, row) {
							return NUMBER(data).format(true) + '<br><strong>' + NUMBER(row
									.tpp_kelangkaan_profesi).format(true) + '</strong>' + '<br>' + NUMBER(row.tpp_tempat_bertugas)
								.format(true) 
						}
					},

					{
						"data": "tpp_gabungan",
						"render": function (data, type, row) {
							return NUMBER(data).format(true) + '<br>' + NUMBER(row.tunjangan_plt).format(
								true) + '<br>' + NUMBER(row.nominal_rapel).format(true);
						}
					},
					{
						"data": "cost_bpjs",
						"render": function (data, type, row) {
							return NUMBER(data).format(true);
						}
					},
					{
						"data": "pph",
						"render": function (data, type, row) {
							return NUMBER(data).format(true);
						}
					},
					{
						"data": "tpp_gabungan_setelah_pph",
						"render": function (data, type, row) {
							return NUMBER(data).format(true);
						}
					},
				],
				"oLanguage": {
					sLoadingRecords: `<img src="`+base_url + '/assets/img/loading.svg'+`">`
				}
			});

			datatable.on('xhr', function () {
				var json = datatable.ajax.json();
				$("thead").removeClass('green-thead');
				if (json) {
					if (json.status == "success_archive") {
						$("thead").addClass('green-thead');
					}
				}
			});

		}
	}

</script>
