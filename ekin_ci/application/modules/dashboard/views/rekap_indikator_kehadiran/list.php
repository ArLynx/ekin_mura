<link rel="stylesheet" href="<?php echo base_url('assets/plugin/sweetalert2/dist/sweetalert2.min.css'); ?>">
<style>
	.swal2-popup {
		font-size: 1.6rem !important;
	}
</style>
<script src="<?php echo base_url('assets/plugin/sweetalert2/dist/sweetalert2.min.js'); ?>"></script>

<!-- Main content -->
<section class="content" data-updated="<?php echo $_updated; ?>" data-deleted="<?php echo $_deleted; ?>">

	<!-- Your Page Content Here -->
	<div class="box">
		<div class="box-header with-border">
			<div class="row">
				<div class="col-md-2">
					<div class="form-group">
						<select class="form-control" name="selected_month" onchange="getData()">
							<option value="">- Pilih Bulan -</option>
							<?php if ($all_month): ?>
							<?php foreach ($all_month as $row): ?>
							<option value="<?php echo encode_crypt($row->month); ?>"><?php echo $row->month_text; ?></option>
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
							<option value="<?php echo encode_crypt($row->year); ?>"
								<?php echo $row->year == date('Y') ? 'selected' : ''; ?>><?php echo $row->year; ?>
							</option>
							<?php endforeach;?>
							<?php endif;?>
						</select>
					</div>
				</div>
			</div>
		</div>
		<div class="box-body" style="padding-top: 0;">
			<div class="row">
				<div class="col-md-12">
					<div class="table-responsive">
						<table id="datatableRIK" class="table table-striped table-bordered" style="width: 100%;">
							<thead>
								<tr>
									<th>No</th>
									<th>SOPD</th>
									<th>Tanggal Rekap</th>
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
	var datatable = $('#datatableRIK').DataTable({
		"columns": [{
				"width": "10"
			},
			null,
			null,
			{
				"width": "100"
			},
		],
		"aaSorting": [],
	});

	let _updated = $(".content").attr('data-updated');
	let _deleted = $(".content").attr('data-deleted');

	function getData() {
		let selected_month = $("select[name=selected_month]").val();
		let selected_year = $("select[name=selected_year]").val();
		datatable.clear().draw();

		$("#datatableRIK > tbody").html(`
			<tr>
				<td colspan="4" class="text-center">
					<img src="`+base_url +`assets/img/loading.svg">
				</td>
			</tr>
		`);

		$.get(base_url + '/dashboard/rekap_indikator_kehadiran/get_data', {
				selected_month: selected_month,
				selected_year: selected_year
			})
			.then(function (response) {
				if(response != '') {
					let arrData = [];
					$.each(response, function (key, value) {
						let arrAksi = '';
						let data = [
							++key,
							value.NM_UNOR,
							value.tanggal_rekap,
						];

						let today = new Date();
						let monthBefore = today.getMonth();

						if(monthBefore == value.month) {
							if (_deleted == 1) {
								arrAksi += '<div class="td-action">' +
									'<div class="btn-group btn-group-md" role="group" aria-label="...">' +
									'<button type="button" class="btn btn-danger" onclick="deleteData(\'' +
									value.unor_encrypt + '\')" title="Hapus">' +
									'<i class="ion-trash-a"></i>' +
									'</button>' +
									'</div>' +
									'</div>';
							}

							if (arrAksi != '') {
								data.push(arrAksi);
							} else {
								//privilege uncheck
								data.push('<div class="td-action">' +
									'<div class="btn-group btn-group-md" role="group" aria-label="...">' +
									'<span class="btn btn-default" title="Locked"><i class="ion-ios-locked-outline"></i></span>' +
									'</div>' +
									'</div>');
							}
						} else {
							data.push('<div class="td-action">' +
									'<div class="btn-group btn-group-md" role="group" aria-label="...">' +
									'<span class="btn btn-primary" title="Locked"><i class="ion-ios-locked-outline"></i></span>' +
									'</div>' +
									'</div>');
						}
						arrData.push(data);
					});
					datatable.rows.add(arrData).draw(false);
				} else {
					$("#datatableRIK > tbody").html(`
						<tr>
							<td colspan="4" class="text-center">
								No data available in table
							</td>
						</tr>
					`);
				}
			});
	}

	function deleteData(unor_encrypt) {
		const swalWithBootstrapButtons = Swal.mixin({
			customClass: {
				confirmButton: 'btn btn-success',
				cancelButton: 'btn btn-danger'
			},
			buttonsStyling: false
		})

		swalWithBootstrapButtons.fire({
			title: 'Apakah Anda yakin ingin menghapus data ini?',
			text: "",
			type: 'warning',
			showCancelButton: true,
			confirmButtonText: 'Hapus',
			cancelButtonText: 'Batal'
		}).then((result) => {
			if (result.value) {
				let selected_month = $("select[name=selected_month]").val();
				let selected_year = $("select[name=selected_year]").val();

				$.get(base_url + "/dashboard/rekap_indikator_kehadiran/delete", {
					unor_encrypt: unor_encrypt,
					month_encrypt: selected_month,
					year_encrypt: selected_year,
				}).then(function () {
					getData();
					swalWithBootstrapButtons.fire(
						'Hapus berhasil',
						'Data berhasil dihapus',
						'success'
					)
				});
			} else if (
				/* Read more about handling dismissals below */
				result.dismiss === Swal.DismissReason.cancel
			) {
				swalWithBootstrapButtons.fire(
					'Hapus dibatalkan',
					'Data aman',
					'error'
				)
			}
		})
	}

</script>
