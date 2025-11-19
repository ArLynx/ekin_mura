<link rel="stylesheet" href="<?php echo base_url('assets/plugin/sweetalert2/dist/sweetalert2.min.css'); ?>">
<style>
	.swal2-popup {
		font-size: 1.6rem !important;
	}

</style>
<script src="<?php echo base_url('assets/plugin/sweetalert2/dist/sweetalert2.min.js'); ?>"></script>

<!-- Main content -->
<section class="content" data-updated="<?php echo $_updated; ?>">

	<!-- Your Page Content Here -->
	<div class="box">
		<div class="box-header with-border">
			<div class="row">
				<div class="col-md-4">
					<?php if ($_created == 1): ?>
						<a href="<?php echo site_url('dashboard/master-koef-kelas-jabatan/add'); ?>" class="btn btn-primary">Tambah</a>
					<?php endif;?>
				</div>
			</div>
		</div>
		<div class="box-body" style="padding-top: 0;">
			<div class="row">
				<div class="col-md-12">
					<div class="table-responsive">
						<?php alert_message_dashboard();?>
						<table id="datatableMKKJ" class="table table-striped table-bordered" style="width: 100%;">
							<thead>
								<tr>
									<th>No</th>
									<th>Jabatan PNS</th>
									<th>Kelas Jabatan</th>
									<th>Koef</th>
									<th>Tahun</th>
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
	var datatable = $('#datatableMKKJ').DataTable({
		"columns": [{
				"width": "10"
			},
			{
				"width": "350"
			},
			{
				"width": "150"
			},
			{
				"width": "150"
			},
			{
				"width": "150"
			},
			{
				"width": "50"
			},
		],
		"aaSorting": [],
	});

	let _updated = $(".content").attr('data-updated');

	getData();

	function getData() {
		datatable.clear().draw();

		$("#datatableMKKJ > tbody").html(`
			<tr>
				<td colspan="6" class="text-center">
					<img src="`+base_url +`assets/img/loading.svg">
				</td>
			</tr>
		`);

		$.get(base_url + '/dashboard/master_koef_kelas_jabatan/get_data')
			.then(function (response) {
				if(response != '') {
					let arrData = [];
					$.each(response, function (key, value) {
						let arrAksi = '';
						let data = [
							++key,
							value.jabatan_pns,
							value.kelas_jabatan,
							value.koef,
							value.tahun,
						];

						if (_updated == 1) {
							arrAksi += '<a href="' + base_url + '/dashboard/master-koef-kelas-jabatan/edit/' + value.id + '.html" class="btn btn-warning" title="Ubah">' +
								'<i class="fa fa-edit"></i>' +
								'</a> ';
						}

						if (arrAksi != '') {
							data.push(arrAksi);
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
					$("#datatableMKKJ > tbody").html(`
						<tr>
							<td colspan="6" class="text-center">
								No data available in table
							</td>
						</tr>
					`);
				}
			});
	}

</script>
