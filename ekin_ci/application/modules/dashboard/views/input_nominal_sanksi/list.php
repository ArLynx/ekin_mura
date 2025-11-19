<!-- Main content -->
<section class="content">

	<!-- Your Page Content Here -->
	<div class="box">
		<div class="box-header with-border">
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<select class="form-control select2" name="selected_sopd" onchange="getData()"
							style="width: 100%;">
							<?php if (get_session('id_groups') == '5'): ?>
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
				<div class="col-md-4">
					<?php if (isset($_created) == 1): ?>
						<a href="#" id="addINS" class="btn btn-primary">Tambah</a>
					<?php endif;?>
				</div>
			</div>
		</div>
		<div class="box-body" style="padding-top: 0;">
			<div class="row">
				<div class="col-md-12">
					<div class="table-responsive">
						<?php alert_message_dashboard();?>
						<table id="datatablePPlt" class="table table-striped table-bordered" style="width: 100%;">
							<thead>
								<tr>
									<th>No</th>
									<th>NIP</th>
									<th>Nama</th>
									<th>Nominal</th>
									<th>Mulai Tanggal</th>
									<th>Sampai Tanggal</th>
									<th>Aksi</th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

</section>
<!-- /.content -->

<script>
	var datatable = $('#datatablePPlt').DataTable({
		"columns": [{
				"width": "10"
			},
			{
				"width": "150"
			},
			null,
			null,
			null,
			null,
			{
				"width": "100"
			},
		],
		"aaSorting": [],
	});

	getData();
	$("a#addINS").hide();

	const IDR = value => currency(value, {
		symbol: "",
		precision: 0,
		separator: "."
	});

	let today = new Date();
	let current_date = today.getFullYear()+'-'+(today.getMonth()+1);

	function getData() {
		datatable.clear().draw();
		let selected_sopd = $("select[name=selected_sopd]").val();

		$("#datatablePPlt > tbody").html(`
			<tr>
				<td colspan="7" class="text-center">
					<img src="`+base_url +`assets/img/loading.svg">
				</td>
			</tr>
		`);

		if(selected_sopd) {
			$.get(base_url + '/dashboard/input-nominal-sanksi/get_data', {
				selected_sopd: selected_sopd
			})
			.then(function (response) {
				$("a#addINS").attr('href', base_url + '/dashboard/input-nominal-sanksi/add/' + selected_sopd);
				$("a#addINS").show();
				if(response != '') {
					let arrData = [];
					let defFormatCreatedAt;
					let formatCreatedAt;
					$.each(response, function (key, value) {
						let data = [
							++key,
							value.pns_pnsnip,
							value.PNS_NAMA,
							IDR(value.nominal).format(true),
							value.mulai_tanggal,
							value.sampai_tanggal,
						];
						// if(date != )

						defFormatCreatedAt = new Date(value.created_at);
						formatCreatedAt = defFormatCreatedAt.getFullYear()+'-'+(defFormatCreatedAt.getMonth()+1);

						if(current_date === formatCreatedAt) {
							data.push('<div class="td-action">' +
							'<div class="btn-group btn-group-md" role="group" aria-label="...">' +
							'<a href="' + base_url + '/dashboard/input-nominal-sanksi/edit/' + value
							.id_encrypt +
							'.html" class="btn btn-warning" title="Ubah">' +
							'<i class="fa fa-edit"></i>' +
							'</a>' +
							'</div>' +
							'</div>');
						} else {
							data.push('<div class="td-action">' +
							'<div class="btn-group btn-group-md" role="group" aria-label="...">' +
							'<span class="btn btn-danger" title="Locked"><i class="ion-ios-locked-outline"></i></span>' +
							'</div>' +
							'</div>');
						}
						arrData.push(data);
					});
					datatable.rows.add(arrData).draw(false);
				} else {
					$("#datatablePPlt > tbody").html(`
						<tr>
							<td colspan="7" class="text-center">
								No data available in table
							</td>
						</tr>
					`);
				}
			});
		}
	}

</script>
