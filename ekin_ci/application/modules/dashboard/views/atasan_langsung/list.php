<style>
td, th {
    padding: 4px;
}
</style>

<!-- Main content -->
<section class="content" data-updated="<?php echo $_updated ?? 0; ?>">

	<!-- Your Page Content Here -->
	<div class="box">
		<div class="box-header with-border">
			<div class="row">
                <div class="col-md-4">
					<div class="form-group">
						<select class="form-control select2" name="selected_sopd" onchange="getData()">
							<?php if (get_session('id_groups') == '1' || get_session('id_groups') == '5' || get_session('id_groups') == '6'): ?>
							<option value="">- Pilih SOPD -</option>
							<?php endif;?>
							<?php if ($all_sopd): ?>
							<?php foreach ($all_sopd as $row): ?>
							<option value="<?php echo encode_crypt($row->KD_UNOR); ?>" <?php echo isset($selected_unor) && !empty($selected_unor) ? ($selected_unor == $row->KD_UNOR ? 'selected' : '') : ''; ?>><?php echo $row->NM_UNOR; ?>
							</option>
							<?php endforeach;?>
							<?php endif;?>
						</select>
					</div>
				</div>
				<!-- <div class="col-md-4">
					<a href="<?php //echo site_url('dashboard/atasan-langsung/add'); ?>" class="btn btn-primary">Tambah</a>
				</div> -->
			</div>
		</div>
		<div class="box-body" style="padding-top: 0;">
			<div class="row">
				<div class="col-md-12">
					<div class="table-responsive">
						<?php alert_message_dashboard();?>
						<table id="datatableAL" class="table table-striped table-bordered" width="100%">
							<thead>
								<tr>
									<th>No</th>
									<th>Foto</th>
									<th>Nama</th>
									<th>Jabatan</th>
									<th>Atasan</th>
									<th>Aksi</th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

    <!-- Modal -->
	<div class="modal fade" id="addModalPilAtasan" role="dialog" aria-labelledby="addModalLabel">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
							aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="addModalLabel">Pilih Atasan</h4>
				</div>
				<?php echo form_open('dashboard/atasan-langsung/action'); ?>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-3">
							<img id="modalImage" class="img-rounded" width="100%">
						</div>
						<div class="col-md-9">
							<div class="table-responsive">
								<table class="table table-borderless">
									<tbody>
										<tr>
											<th width="120">Nama</th>
											<th width="10">:</th>
											<td><span id="modalNama"></span></td>
										</tr>
										<tr>
											<th>NIP</th>
											<th>:</th>
											<td><span id="modalNIP"></span></td>
										</tr>
										<tr>
											<th>Jabatan</th>
											<th>:</th>
											<td><span id="modalJabatan"></span></td>
										</tr>
										<tr>
											<th>SKPD</th>
											<th>:</th>
											<td><span id="modalSKPD"></span></td>
										</tr>
										<tr>
											<th>Atasan Langsung</th>
											<th>:</th>
											<td>
												<input type="hidden" name="modal_id_pns_atasan" id="modalIdPnsAtasan">
												<input type="hidden" name="modal_pns_pnsnip" id="modalPnsNip">
												<input type="hidden" name="modal_unor" id="modalUnor">
												<select name="modal_atasan_langsung" id="modalAtasanLangsung" class="form-control select2" style="width: 300px;">
													<option value="">- Pilih Atasan -</option>
												</select>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary">Submit</button>
				</div>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>

</section>
<!-- /.content -->

<script>
	var datatable = $('#datatableAL').DataTable({
		"columns": [{
				"width": "10"
			},
			{
				"width": "30"
			},
			{
				"width": "250"
			},
			{
				"width": "300"
			},
			{
				"width": "150"
			},
			{
				"width": "50"
			},
		],
		"aaSorting": [],
        "createdRow": function (row, data, dataIndex) {
					row.children[1].classList.add("text-center");
				},
	});

	let photoPath = base_url + '/assets/img/upload/user/';
    let no_image_user = base_url + '/assets/img/user.png';

	let _updated = $(".content").attr('data-updated');

    $('#addModalPilAtasan').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var dataPNS = button.data('pns');
		$("#modalImage").attr('src', (dataPNS.PNS_PHOTO != null ? photoPath + dataPNS.PNS_PHOTO : no_image_user));
		$("#modalNama").html(dataPNS.PNS_NAMA);
		$("#modalNIP").html(dataPNS.PNS_PNSNIP);
		$("#modalJabatan").html(dataPNS.nama_jabatan);
		$("#modalSKPD").html(dataPNS.NM_UNOR);
		$("#modalIdPnsAtasan").val(dataPNS.id_pns_atasan_encrypt);
		$("#modalPnsNip").val(dataPNS.PNS_PNSNIP);
		$("#modalUnor").val(dataPNS.unor_encrypt);

		$("#modalAtasanLangsung").html('<option value="">- Pilih Atasan -</option>');

		$.get(base_url + '/api/get_atasan_langsung', {
			unor: dataPNS.KD_UNOR,
			kelas_jabatan: dataPNS.kelas_jabatan
		})
		.then(function(response) {
			if(response) {
				$.each(response, function(key, value) {
					$("#modalAtasanLangsung").append(
						"<option value='"+value.PNS_PNSNIP+"' "+(value.PNS_PNSNIP == dataPNS.pns_atasan ? 'selected' : '')+">"+value.PNS_NAMA+ ' | ' +value.nama_jabatan+"</option>"
					);
				});
			}
		});
    });

	getData();

	function escape(key, val) {
		if (typeof (val) != "string") return val;
		return val.replace(/[\']/g, '');
	}

	function getData() {
		datatable.clear().draw();

		$("#datatableAL > tbody").html(`
			<tr>
				<td colspan="6" class="text-center">
					<img src="`+base_url +`assets/img/loading.svg">
				</td>
			</tr>
		`);

        let selected_sopd = $("select[name=selected_sopd]").val();
		$.get(base_url + '/dashboard/atasan-langsung/get_data', {
                selected_sopd: selected_sopd
            })
			.then(function (response) {
				if(response != '') {
					let arrData = [];
					$.each(response, function (key, value) {
						let data = [
							++key,
							"<img src='"+(value.PNS_PHOTO != null ? photoPath + value.PNS_PHOTO : no_image_user)+"' width='60'>",
							value.PNS_NAMA,
							value.nama_jabatan,
							value.PNS_ATASAN_NAMA
						];

						if(_updated == '1') {
							data.push('<div class="td-action">' +
							'<div class="btn-group btn-group-md" role="group" aria-label="...">' +
							"<button type='button' class='btn btn-warning' data-toggle='modal' data-target='#addModalPilAtasan' data-pns='"+(JSON.stringify(value, escape))+"' title='Pilih Atasan'>" +
							"<i class='fa fa-edit'></i>" +
							"</button>" +
							'</div>' +
							'</div>');
						} else {
							data.push('');
						}
						arrData.push(data);
					});
					datatable.rows.add(arrData).draw(false);
				} else {
					$("#datatableAL > tbody").html(`
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
