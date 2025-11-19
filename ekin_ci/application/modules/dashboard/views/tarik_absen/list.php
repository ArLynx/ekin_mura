<link rel="stylesheet" href="<?php echo base_url('assets/plugin/sweetalert2/dist/sweetalert2.min.css'); ?>">
<style>
.swal2-popup {
  font-size: 1.6rem !important;
}

</style>
<script src="<?php echo base_url('assets/plugin/sweetalert2/dist/sweetalert2.min.js'); ?>"></script>

<!-- Main content -->
<section class="content" data-id_groups="<?php echo get_session('id_groups'); ?>" data-updated="<?php echo $_updated; ?>">

	<!-- Your Page Content Here -->
	<div class="box">
		<?php if (get_session('id_groups') == '1' || get_session('id_groups') == '5'): ?>
		<div class="box-header with-border">
			<div class="row">
				<div class="col-md-4">
					<?php if ($_created == 1): ?>
						<a href="<?php echo site_url('dashboard/tarik-absen/add'); ?>" class="btn btn-primary">Tambah</a>
					<?php endif;?>
				</div>
			</div>
		</div>
		<?php endif;?>
		<div class="box-body" style="padding-top: 0;">
			<div class="row">
				<div class="col-md-12">
					<div class="table-responsive">
						<?php alert_message_dashboard();?>
						<table id="datatableTA" class="table table-striped table-bordered" style="width: 100%;">
							<thead>
								<tr>
									<th>No</th>
									<th>SN</th>
									<th>Urutan No</th>
                                    <th>Inisial</th>
									<th>Last Update</th>
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
	var datatable = $('#datatableTA').DataTable({
		"columns": [{
				"width": "1"
			},
			{
				"width": "80"
			},
            {
				"width": "75"
			},
            null,
			{
				"width": "100"
			},
            {
				"width": "60"
			},
		],
		"aaSorting": [],
	});

    getData();

	let _updated = $(".content").attr('data-updated');
	let _id_groups = $(".content").attr('data-id_groups');

	function getData() {
		
		$.get(base_url + '/dashboard/tarik_absen/get_data', {})
			.then(function (response) {
				datatable.clear().draw();
				let arrData = [];
				$.each(response, function (key, value) {
					let arrAksi = '';
					arrData = [
						++key,
						value.sn,
                        value.kd_unor,
                        value.nama_unor,
						value.updated_at,
					];

					if (_updated == 1) {
						arrAksi += '<a href="' + base_url + '/dashboard/tarik-absen/upload_absen/' + value.sn + '/' + value.id +
							'.html" class="btn btn-warning" title="Upload">' +
							'<i class="fa fa-upload"></i>' +
							'</a> ';
					}

					if (arrAksi != '') {
						arrData.push(arrAksi);
					} else {
						arrData.push('<div class="td-action">' +
							'<div class="btn-group btn-group-md" role="group" aria-label="...">' +
							'<span class="btn btn-danger" title="Locked"><i class="ion-ios-locked-outline"></i></span>' +
							'</div>' +
							'</div>');
					}

					datatable.row.add(arrData).draw(false);
				});
			});
	}

</script>
