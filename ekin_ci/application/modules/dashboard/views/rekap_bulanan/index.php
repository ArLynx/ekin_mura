<style>
	table {
		height: 1px;
		font-size: 12px;
	}

	tr {
		height: 100%;
	}

	td {
		height: 100%;
	}

	td>div {
		height: 100%;
		padding: 0.5em;
	}

	.dataTable tbody td {
		vertical-align: middle !important;
		text-align: center;
	}

	.dataTable tbody tr,
	.dataTable tbody td {
		padding: 0 !important;
	}

	.dataTable tbody td div.brown {
		background: #ccc;
	}

	.dataTable tbody td.text-left {
		text-align: left !important;
		padding: 0.5em !important;
	}

	.dataTable tbody td.dataTables_empty {
		padding: 8px !important;
	}

	.dataTable>thead>tr>th[class*="sort"] {
		padding: 8px !important;
	}

	.dataTable>thead>tr>th[class*="sort"]::after {
		display: none;
	}

	.green-thead {
		background: #079992;
		color: #fff;
	}

	.table-bordered>thead.green-thead>tr>th {
		border-bottom-width: 1px !important;
	}

</style>
<!-- Main content -->
<section class="content">

	<!-- Your Page Content Here -->
	<div class="box">
		<div class="box-header with-border" style="padding-bottom: 0;">
			<div class="row">

				<div class="col-md-2">
					<div class="form-group">
						<select class="form-control" name="selected_month" onchange="getData(0)">
							<option value="">- Pilih Bulan -</option>
							<?php if ($all_month): ?>
							<?php foreach ($all_month as $row): ?>
							<option value="<?php echo encode_crypt($row->month); ?>" data-month="<?php echo $row->month; ?>"><?php echo $row->month_text; ?></option>
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
								<?php echo $row->year == date('Y') ? 'selected' : ''; ?>><?php echo $row->year; ?>
							</option>
							<?php endforeach;?>
							<?php endif;?>
						</select>
					</div>
				</div>

				<div class="col-md-4">
					<div class="form-group">
						<select class="form-control select2" name="selected_sopd" onchange="getData(1)"
							style="width: 100%;">
							<?php if (get_session('id_groups') == '1' || get_session('id_groups') == '4' || get_session('id_groups') == '5'): ?>
							<option value="">- Pilih SOPD -</option>
							<?php endif;?>
							<?php if ($all_sopd): ?>
							<?php foreach ($all_sopd as $row): ?>
							<option value="<?php echo encode_crypt($row->KD_UNOR); ?>"><?php echo $row->NM_UNOR; ?>
							</option>
							<?php endforeach;?>
							<?php endif;?>
						</select>
					</div>
				</div>

				<div class="col-md-2">
					<div class="form-group">
						<select class="form-control" name="selected_tipe_pegawai" onchange="getData(0)">
							<option value="">- Pilih Tipe Pegawai -</option>
							<?php if ($all_tipe_pegawai): ?>
								<?php foreach ($all_tipe_pegawai as $row): ?>
									<option value="<?php echo $row->id; ?>" <?php echo isset($_user_login->ID_TIPE_PEGAWAI) ? ($_user_login->ID_TIPE_PEGAWAI == $row->id ? 'selected' : '') : ''; ?>><?php echo $row->type; ?></option>
								<?php endforeach;?>
							<?php endif;?>
						</select>
					</div>
				</div>

					<div class="col-md-4">
							<!-- <?php var_dump($all_sopd[0]) ?> -->
							<div class="form-group">
							<select class="form-control select2" name="id_penanda_tangan" id="selected_penanda_tangan" onchange="getData(0)"
									style="width: 100%;">
	
									<option value="">- Pilih Penanda Tangan -</option>
								</select>
							</div>
						</div>	

				<div class="col-md-2">
					<?php if (get_session('id_groups') != '3'): ?>
						<div id="printLaporan"></div>
					<?php elseif (get_session('id_groups') == '3'): ?>
						<?php //$id_users = get_session('id_users');?>
						<!-- <div id="uploadAbsen"></div> -->
					<?php endif;?>
				</div>

			</div>
		</div>
		<div class="box-body" style="padding-top: 0;">
			<div class="row">
				<div class="col-md-12">
					<div class="table-responsive">
						<?php alert_message_dashboard();?>
						<div id="showDatatable"></div>
					</div>
				</div>
			</div>
		</div>
	</div>

</section>
<!-- /.content -->

<script>
	function calDaysInMonth(month, year) {
		return new Date(year, month, 0).getDate();
	}

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
		let selected_tipe_pegawai = $("select[name=selected_tipe_pegawai]").val();
		let selected_ttd = $("select[name=id_penanda_tangan]").val();

		$("#printLaporan").html('');
		// $("#uploadAbsen").html('');
		
		if(nilai_angka == 1){
			selected_ttd = ''
		}

		$("#selected_penanda_tangan").val(selected_ttd);
		if (selected_sopd && selected_month && selected_year && selected_tipe_pegawai) {

			$.get(base_url + '/api/check_rekap_absen_bulanan_exists', {
				unor_encrypt: selected_sopd,
				month: selected_month,
				year: selected_year
			})
			.then(function(response) {
					getPenandaTangan();
				// if(response) {
					$("#printLaporan").append('<a href='+base_url + '/dashboard/rekap-bulanan/report/' + selected_sopd + '/' + selected_month + '/' + selected_year + '/' + selected_tipe_pegawai + '/' + selected_ttd + ' target="_blank" class="'+(response ? (response ? 'btn btn-primary' : 'btn btn-default') : 'btn btn-default')+'">'+(response ? (response ? 'Cetak Arsip Laporan' : 'Cetak Laporan') : 'Cetak Laporan')+'</a>');
					// $("#uploadAbsen").append('<a href='+base_url + '/dashboard/rekap-bulanan/uploadAbsen/ class="btn btn-primary">' + 'Upload' + '</a>');
				// }
			});

			let selectedDataMonth = $("select[name=selected_month]").find(':selected').attr('data-month');

			let daysInMonth = calDaysInMonth(selectedDataMonth, selected_year);
		
			let tableHeaders = "<tr>" +
				"<th rowspan='2' class='th-top'>No</th>" +
				"<th rowspan='2' class='th-top' width='200'>Nama</th>" +
				"<th id='" + daysInMonth + "' colspan='31' class='text-center'>Tanggal</th>" +
				"</tr>";

			for (let i = 1; i <= daysInMonth; i++) {
				if (i == 1) {
					tableHeaders += "<tr>";
				}
				tableHeaders += "<th class='text-center'>" + i + "</th>";

				if (i == daysInMonth) {
					tableHeaders += "</tr>";
				}
			}

			$("#showDatatable").empty();
			$("#showDatatable").append(
				'<table id="datatableRB" class="table table-striped table-bordered" style="width: 100%;"><thead><tr>' +
				tableHeaders + '</tr></thead></table>');
			$("#datatableRB").dataTable();

			let arrData = [{
					"data": "no"
				},
				{
					"data": "PNS_NAMA"
				}
			];

			for (let i = 1; i <= daysInMonth; i++) {
				if (i < 10) {
					i = '0' + i;
				}
				arrData.push({
					"data": "absen" + i
				});
			}

			
			let datatable = $("#datatableRB").DataTable({
				"destroy": true,
				"aaSorting": [],
				"bSort": false,
				"createdRow": function (row, data, dataIndex) {
					row.children[1].classList.add("text-left");
				},
				"ajax": base_url + '/dashboard/rekap_bulanan/get_data?unor=' + selected_sopd + '&month=' +
					selected_month + '&year=' + selected_year + '&type=' + selected_tipe_pegawai,
				"columns": arrData,
				"oLanguage": {
					sLoadingRecords: `<img src="`+base_url + '/assets/img/loading.svg'+`">`
				}
			});

			datatable.on( 'xhr', function () {
                var json = datatable.ajax.json();
				if(json) {
					if(json.message) {
						$("thead").addClass('green-thead');
					}
				}
			});
		}
	}

</script>
