<style>
	.greee-thead {
		background: #079992;
		color: #fff;
	}

	.table-bordered>thead.greee-thead>tr>th {
		border-bottom-width: 1px !important;
	}

	table.table-bordered.dataTable th:last-child, table.table-bordered.dataTable th:last-child {
		border-right-width: 1px !important;
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
						<select class="form-control" name="selected_month" onchange="getData()">
							<option value="">- Pilih Bulan -</option>
							<?php if ($all_month): ?>
							<?php foreach ($all_month as $row): ?>
							<option value="<?php echo $row->month; ?>" <?php echo $row->month == date('n') ? 'selected' : ''; ?>><?php echo $row->month_text; ?></option>
							<?php endforeach;?>
							<?php endif;?>
						</select>
					</div>
				</div>

				<div class="col-md-2">
					<div class="form-group">
						<select class="form-control" name="selected_year" onchange="getData()">
							<option value="">- Pilih Tahun -</option>
							<?php if ($all_year): ?>
							<?php foreach ($all_year as $row): ?>
							<option value="<?php echo $row->year; ?>" <?php echo $row->year == date('Y') ? 'selected' : ''; ?>><?php echo $row->year; ?></option>
							<?php endforeach;?>
							<?php endif;?>
						</select>
					</div>
				</div>

				<div class="col-md-4">
					<div class="form-group">
						<select class="form-control select2" name="selected_sopd" onchange="getData()" style="width: 100%;">
							<?php if (get_session('id_groups') == '1' || get_session('id_groups') == '5'): ?>
							<option value="">- Pilih SOPD -</option>
							<?php endif;?>
							<?php if ($all_sopd): ?>
							<?php foreach ($all_sopd as $row): ?>
							<option value="<?php echo encode_crypt($row->KD_UNOR); ?>"><?php echo $row->NM_UNOR; ?></option>
							<?php endforeach;?>
							<?php endif;?>
						</select>
					</div>
				</div>

				<div class="col-md-2">
					<div class="form-group">
						<select class="form-control" name="selected_tipe_pegawai" onchange="getData()">
							<option value="">- Pilih Tipe Pegawai -</option>
							<?php if ($all_tipe_pegawai): ?>
								<?php foreach ($all_tipe_pegawai as $row): ?>
									<option value="<?php echo $row->id; ?>" <?php echo isset($_user_login->ID_TIPE_PEGAWAI) ? ($_user_login->ID_TIPE_PEGAWAI == $row->id ? 'selected' : '') : ''; ?>><?php echo $row->type; ?></option>
								<?php endforeach;?>
							<?php endif;?>
						</select>
					</div>
				</div>
			</div>
		</div>
		<div class="box-body" style="padding-top: 0;">
			<div class="table-responsive">
				<table id="datatableIK" class="table table-striped table-bordered table-hover" style="width: 100%;">
					<thead>
						<tr>
							<th rowspan="3" class="text-center th-top">No</th>
                            <th rowspan="3" class="text-center th-top">Nama</th>
                            <th colspan="8" class="text-center">Terlambat</th>
							<th colspan="8" class="text-center">Pulang Cepat</th>
							<th colspan="6" class="text-center">TKS/Cuti</th>
                            <th rowspan="3" class="text-center th-top">&Sigma; Skor</th>
                            <th rowspan="3" class="text-center th-top">Persentase</th>
                        </tr>
                        <tr>
                            <th colspan="2" class="text-center">1</th>
							<th colspan="2" class="text-center">2</th>
							<th colspan="2" class="text-center">3</th>
							<th colspan="2" class="text-center">4</th>
							<th colspan="2" class="text-center">5</th>
							<th colspan="2" class="text-center">6</th>
							<th colspan="2" class="text-center">7</th>
							<th colspan="2" class="text-center">8</th>
							<th colspan="2" class="text-center">9</th>
							<th colspan="2" class="text-center">10</th>
							<th colspan="2" class="text-center">11</th>
                        </tr>
                        <tr>
                            <th>&Sigma; Hari</th>
                            <th>Skor</th>
                            <th>&Sigma; Hari</th>
                            <th>Skor</th>
                            <th>&Sigma; Hari</th>
                            <th>Skor</th>
                            <th>&Sigma; Hari</th>
                            <th>Skor</th>
                            <th>&Sigma; Hari</th>
                            <th>Skor</th>
                            <th>&Sigma; Hari</th>
                            <th>Skor</th>
                            <th>&Sigma; Hari</th>
                            <th>Skor</th>
                            <th>&Sigma; Hari</th>
                            <th>Skor</th>
                            <th>&Sigma; Hari</th>
                            <th>Skor</th>
                            <th>&Sigma; Hari</th>
                            <th>Skor</th>
                            <th>&Sigma; Hari</th>
                            <th>Skor</th>
                        </tr>
					</thead>
				</table>
			</div>
		</div>
	</div>






	<div class="box">
		<div class="box-header with-border" style="padding-bottom: 0;">
			<h3 class="text-bold">Lama Keterlambatan</h3>
		</div>
		<div class="box-body" style="padding-top: 20;">
			  <p><i class="fa fa-cog " aria-hidden="true"></i>  <a>Indikator 1</a> <span class="text-bold"> 1 menit s.d  <i class="fa-solid fa-xs  fa-less-than-equal"></i> 30 Menit</span></p>
			  <p><i class="fa fa-cog" aria-hidden="true"></i> <a>Indikator 2</a> <span class="text-bold"> 31 menit s.d <i class="fa-solid fa-xs  fa-less-than-equal"></i> 60 Menit</span> </p>
			  <p><i class="fa fa-cog" aria-hidden="true"></i> <a>Indikator 3</a> <span class="text-bold"> 61 menit s.d <i class="fa-solid fa-xs  fa-less-than-equal"></i> 90 Menit </span> </p>
			  <p><i class="fa fa-cog" aria-hidden="true"></i> <a>Indikator 4</a> <span class="text-bold"> <i class="fa-solid fa-xs  fa-greater-than"></i> 90 Menit </span></p>
			 
		</div>
	</div>


		<div class="box">
		<div class="box-header with-border" style="padding-bottom: 0;">
			<h3 class="text-bold">Lama Pulang Sebelum Waktunya</h3>
		</div>
		<div class="box-body" style="padding-top: 20;">
			  <p><i class="fa fa-cog" aria-hidden="true"></i> <a>Indikator 5</a>  <span class="text-bold">1 menit s.d <i class="fa-solid fa-xs  fa-less-than-equal"></i> 30 Menit </span></p>
			  <p><i class="fa fa-cog" aria-hidden="true"></i> <a>Indikator 6</a> <span class="text-bold">31 menit s.d  <i class="fa-solid fa-xs  fa-less-than-equal"></i> 60 Menit</span> </p>
			  <p><i class="fa fa-cog" aria-hidden="true"></i> <a>Indikator 7</a>  <span class="text-bold">61 menit s.d  <i class="fa-solid fa-xs  fa-less-than-equal"></i> 90 Menit  </span></p>
			  <p><i class="fa fa-cog" aria-hidden="true"></i> <a>Indikator 8</a> <span class="text-bold"> <i class="fa-solid fa-xs  fa-greater-than"></i> 90 Menit   </span>  </p>
			 
		</div>
	</div>

	
		<div class="box">
		<div class="box-header with-border" style="padding-bottom: 0;">
			<h3 class="text-bold">Cuti / TKS</h3>
		</div>
		<div class="box-body" style="padding-top: 20;">
			 
			  <p><i class="fa fa-cog" aria-hidden="true"></i> <a>Indikator 9 </a> <span class="text-bold"> Potongan Cuti (Cuti melahirkan anak ke 4 / Cuti tidak ditanggung negara)</span></p>
			  <p><i class="fa fa-cog" aria-hidden="true"></i> <a>Indikator 10</a><span class="text-bold">TKS</span></p>
		</div>
	</div>

</section>
<!-- /.content -->

<script>
	var datatable = $('#datatableIK').DataTable({
		"aaSorting": [],
		"bSort": false,
        "createdRow": function (row, data, dataIndex) {
            row.children[0].classList.add("text-center");
            for(let i = 2; i <= 25; i++) {
			    row.children[i].classList.add("text-center");
            }
		},
        columnDefs: [
            { width: '100px', targets: 1 }
        ],
	});

	getData();

	const NUMBER = value => currency(value, {
		symbol: "",
		precision: 0,
		separator: "."
	});

	function getData() {
		datatable.clear().draw();

		let unor = $("select[name=selected_sopd]").val();
        let month = $("select[name=selected_month]").val();
        let year = $("select[name=selected_year]").val();
        let tipe_pegawai = $("select[name=selected_tipe_pegawai]").val();

		if(unor && month && year && tipe_pegawai) {
			$("#datatableIK > tbody").html(`
				<tr>
					<td colspan="26" class="text-center">
						<img src="`+base_url +`assets/img/loading.svg">
					</td>
				</tr>
			`);

			$.get(base_url + '/dashboard/indikator_kehadiran/get_data', {
				unor: unor,
				month: month,
				year: year,
				tipe_pegawai: tipe_pegawai,
			})
			.then(function (response) {
				if(response != '') {
					if(response.status == 'success_archive') {
						$("thead").addClass('greee-thead');
					} else {
						$("thead").removeClass('greee-thead');
					}
					$.each(response.data, function (key, value) {
						datatable.row.add([
							++key,
							'<strong>'+value.PNS_NAMA+'</strong><br>' + value.PNS_PNSNIP,
							value.skor1,
							value.skor1skor,
							value.skor2,
							value.skor2skor,
							value.skor3,
							value.skor3skor,
							value.skor4,
							value.skor4skor,
							value.skor5,
							value.skor5skor,
							value.skor6,
							value.skor6skor,
							value.skor7,
							value.skor7skor,
							value.skor8,
							value.skor8skor,
							value.skor9,
							value.skor9skor,
							value.skor10,
							value.skor10skor,
							value.skor11,
							value.skor11skor,
							value.totalskor,
							value.persentase + '%',
						]).draw(false);
					});
				} else {
					$("#datatableIK > tbody").html(`
						<tr>
							<td colspan="26" class="text-center">
								No data available in table
							</td>
						</tr>
					`);
				}
			});
		}
	}

</script>
