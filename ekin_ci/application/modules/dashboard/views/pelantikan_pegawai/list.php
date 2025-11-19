<link rel="stylesheet" href="<?php echo base_url('assets/plugin/sweetalert2/dist/sweetalert2.min.css'); ?>">
<style>
	.swal2-popup {
		font-size: 1.6rem !important;
	}

	.sweet_loader {
		width: 140px;
		height: 140px;
		margin: 0 auto;
		animation-duration: 0.5s;
		animation-timing-function: linear;
		animation-iteration-count: infinite;
		animation-name: ro;
		transform-origin: 50% 50%;
		transform: rotate(0) translate(0, 0);
	}

	@keyframes ro {
		100% {
			transform: rotate(-360deg) translate(0, 0);
		}
	}

</style>
<script src="<?php echo base_url('assets/plugin/sweetalert2/dist/sweetalert2.min.js'); ?>"></script>

<!-- Main content -->
<section class="content">

	<!-- Your Page Content Here -->
	<div class="box">
		<div class="box-header with-border">
			<div class="row">
				<div class="col-md-3">
					<div class="form-group">
						<select class="form-control select2" name="id_mutasi" onchange="getData()" style="width: 100%;">
							<option value="">- Pilih Tanggal Pelantikan -</option>
							<?php if ($mutasi): ?>
							<?php foreach ($mutasi as $row): ?>
							<option value="<?php echo encode_crypt($row->id); ?>"><?php echo $row->tanggal; ?>
							</option>
							<?php endforeach;?>
							<?php endif;?>
						</select>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<select class="form-control select2" name="selected_sopd" onchange="getData()"
							style="width: 100%;">
							<option value="">- Pilih SOPD -</option>
							<?php if ($all_sopd): ?>
							<?php foreach ($all_sopd as $row): ?>
							<option value="<?php echo encode_crypt($row->KD_UNOR); ?>"><?php echo $row->NM_UNOR; ?>
							</option>
							<?php endforeach;?>
							<?php endif;?>
						</select>
					</div>
				</div>
				<div class="col-md-5">
					<?php if (isset($_created) == 1): ?>
					<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#addModal">Tambah</a>
					<a href="#" class="btn btn-warning" data-toggle="modal" data-target="#addModalTglPelantikan">Tambah
						Tanggal Pelantikan</a>
					<?php if (get_session('id_groups') == '1' || get_session('id_groups') == '5'): ?>
						<a href="#" class="btn btn-info" data-toggle="modal" data-target="#modalProsesPending"s>Proses Pending Mutasi</a>
					<?php endif;?>
					<?php endif;?>
				</div>
			</div>
		</div>
		<div class="box-body" style="padding-top: 0;">
			<div class="row">
				<div class="col-md-12">
					<div class="table-responsive">
						<?php alert_message_dashboard();?>
						<table id="datatablePP" class="table table-striped table-bordered" style="width: 100%;">
							<thead>
								<tr>
									<th>No</th>
									<th>Nama</th>
									<th>Tanggal Pelantikan</th>
									<th>Status</th>
									<th>Jabatan/SKPD Asal</th>
									<th>Jabatan/SKPD Baru</th>
									<th>Action</th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal -->
	<div class="modal fade" id="modalProsesPending" role="dialog" aria-labelledby="modalProsesPendingLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
							aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="modalProsesPendingLabel">Proses Pending Mutasi</h4>
				</div>
				<?php echo form_open('dashboard/pelantikan-pegawai/process_pending'); ?>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Tanggal Pelantikan</label>
								<select class="form-control select2" name="id_mutasi_proses_encrypt" style="width: 100%;">
									<option value="">- Pilih Tanggal Pelantikan -</option>
									<?php if ($mutasi): ?>
									<?php foreach ($mutasi as $row): ?>
									<option value="<?php echo encode_crypt($row->id); ?>"><?php echo $row->tanggal; ?>
									</option>
									<?php endforeach;?>
									<?php endif;?>
								</select>
							</div>
								<div class="form-group">
								<label>Unor Tujuan</label>
								<select class="form-control select2" name="id_kode_unor_tujuan_encrypt" style="width: 100%;" required>
									<option value="">- Pilih Unor Tujuan -</option>
									<?php if ($all_sopd): ?>
									<?php foreach ($all_sopd as $row): ?>
									<option value="<?php echo encode_crypt($row->KD_UNOR); ?>"><?php echo $row->NM_UNOR; ?>
									</option>
									<?php endforeach;?>
									<?php endif;?>
								</select>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary">Proses</button>
				</div>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>

	<!-- Modal -->
	<div class="modal fade" id="addModalTglPelantikan" role="dialog" aria-labelledby="addModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
							aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="addModalLabel">Tambah Tanggal Pelantikan</h4>
				</div>
				<?php echo form_open('dashboard/pelantikan-pegawai/add-tanggal-pelantikan'); ?>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Tanggal Pelantikan</label>
								<input type="date" name="tanggal_pelantikan" placeholder="Tanggal Pelantikan"
									class="form-control">
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

	<!-- Modal -->
	<div class="modal fade" id="addModal" role="dialog" aria-labelledby="addModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
							aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="addModalLabel">Data PNS</h4>
				</div>
				<?php echo form_open('dashboard/pelantikan-pegawai/add'); ?>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Asal SOPD</label>
								<select class="form-control select2" name="asal_sopd_modal" onchange="getDataPegawai()"
									style="width: 100%;">
									<option value="">- Pilih Asal SOPD -</option>
									<?php if ($all_sopd): ?>
									<?php foreach ($all_sopd as $row): ?>
									<option value="<?php echo encode_crypt($row->KD_UNOR); ?>">
										<?php echo $row->NM_UNOR; ?>
									</option>
									<?php endforeach;?>
									<?php endif;?>
								</select>
							</div>
							<div class="form-group">
								<label>Pegawai</label>
								<select class="form-control select2" name="nip_pegawai_modal" style="width: 100%;">
									<option value="">- Pilih Pegawai -</option>
								</select>
							</div>
							<div class="form-group">
								<label>Per Tanggal</label>
								<select class="form-control select2" name="id_mutasi_modal" style="width: 100%;">
									<option value="">- Pilih Tanggal Pelantikan -</option>
									<?php if ($mutasi): ?>
									<?php foreach ($mutasi as $row): ?>
									<option value="<?php echo encode_crypt($row->id); ?>"><?php echo $row->tanggal; ?>
									</option>
									<?php endforeach;?>
									<?php endif;?>
								</select>
							</div>
							<div class="form-group">
								<label>Tujuan SOPD</label>
								<select class="form-control select2" name="tujuan_sopd_modal"
									onchange="getKelasJabatan()" style="width: 100%;">
									<option value="">- Pilih SOPD -</option>
									<?php if ($all_sopd): ?>
									<?php foreach ($all_sopd as $row): ?>
									<option value="<?php echo encode_crypt($row->KD_UNOR); ?>">
										<?php echo $row->NM_UNOR; ?>
									</option>
									<?php endforeach;?>
									<?php endif;?>
								</select>
							</div>
							<div class="form-group">
								<label>Kelas Jabatan</label>
								<select class="form-control select2" name="id_master_kelas_jabatan_modal"
									style="width: 100%;">
									<option value="">- Pilih Kelas Jabatan -</option>
								</select>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<div class="alert alert-danger text-justify" role="alert">
						Pastikan data benar sebelum menekan <strong>"Tombol Submit"</strong>, karena data tidak dapat
						dihapus..
					</div>
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
	var datatable = $('#datatablePP').DataTable({
		"columns": [{
				"width": "10"
			},
			{
				"width": "250"
			},
			{
				"width": "150"
			},
			{
				"width": "50"
			},
			{
				"width": "20"
			},
			null,
			{
				"width": "5"
			},
		],
		"aaSorting": [],
	});

	getData();

	function getData() {
		datatable.clear().draw();
		let id_mutasi = $("select[name=id_mutasi]").val();
		let selected_sopd = $("select[name=selected_sopd]").val();

		$("#datatablePP > tbody").html(`
			<tr>
				<td colspan="7" class="text-center">
					<img src="`+base_url +`assets/img/loading.svg">
				</td>
			</tr>
		`);

		$.get(base_url + '/dashboard/pelantikan-pegawai/get_data', {
			id_mutasi: id_mutasi,
			selected_sopd: selected_sopd
		})
		.then(function (response) {
			if(response != '') {
				let arrData = [];
				$.each(response, function (key, value) {
					arrData.push([
						++key,
						value.PNS_NAMA + '<br>NIP. ' + (!value.PNS_PNSNIP ? '' : value.PNS_PNSNIP) +
						'<br>' + (!value.NM_PKT ? '' : value.NM_PKT) + ' ' + (!value.NM_GOL ? '' : value
							.NM_GOL),
						value.tanggal_mutasi,
						"<label class='label " + (value.status_text == 'Pending' ? 'label-warning' :
							'label-success') + "'>" + value.status_text + "</label>",
						'<strong>' + (value.nama_jabatan_lama ? value.nama_jabatan_lama : (value
							.genpos_lama ? value.genpos_lama : '')) + '</strong><br>' + (value.asal_sopd ?
							value.asal_sopd : ''),
						'<strong>' + (value.nama_jabatan_baru ? value.nama_jabatan_baru : (value
							.genpos_baru ? value.genpos_baru : '')) + '</strong><br>' + (value
							.tujuan_sopd ? value.tujuan_sopd : ''),
						// '<strong>' + (value.id),
				
						'<strong>' + (value.status_text == 'Sukses' ? '' : 
						
						'<button type="button" class="btn btn-danger" onclick="deleteData(\''+value.id_mutasi_detail+'\')" title="Hapus">' +
						'<i class="ion-trash-a"></i>' +
						'</button>' 
						) 

						// '<button type="button" class="btn btn-danger" onclick="deleteData(\''+value.id_encrypt+'\')" title="Hapus">' +
						// '<i class="ion-trash-a"></i>' +
						// '</button>'
					
					
					]);
				
						// '<div class="td-action">'
						// '<button type="button" class="btn btn-danger" onclick="deleteData" title="Hapus">' +
						// '<i class="ion-trash-a"></i>' +
						// '</button>'
						// '</div>';
						
					
				});
				datatable.rows.add(arrData).draw(false);
			} else {
				$("#datatablePP > tbody").html(`
					<tr>
						<td colspan="7" class="text-center">
							No data available in table
						</td>
					</tr>
				`);
			}
		});

	}

	function getDataPegawai() {
		let asal_sopd_modal = $("select[name=asal_sopd_modal]").val();
		if (asal_sopd_modal) {
			$.get(base_url + '/api/get_all_pegawai_sopd_2', {
					unor: asal_sopd_modal,
					is_tkd: 'yes'
				})
				.then(function (response) {
					$("select[name=nip_pegawai_modal]").html('<option value="">- Pilih Pegawai -</option>');
					$.each(response, function (key, value) {
						$("select[name=nip_pegawai_modal]").append(
							'<option value="' + value.PNS_PNSNIP + '">' + value.PNS_NAMA + ' | ' + value
							.nama_jabatan + '</option>'
						);
					});
				});
		}
	}

	function getKelasJabatan() {
		let tujuan_sopd_modal = $("select[name=tujuan_sopd_modal]").val();
		if (tujuan_sopd_modal) {
			$.get(base_url + 'api/get_all_master_kelas_jabatan_2', {
					unor: tujuan_sopd_modal
				})
				.then(function (response) {
					$("select[name=id_master_kelas_jabatan_modal]").html(
						'<option value="">- Pilih Kelas Jabatan -</option>');
					$.each(response, function (key, value) {
						$("select[name=id_master_kelas_jabatan_modal]").append(
							'<option value="' + value.id + '">' + value.kelas_jabatan + ' (' + value
							.nama_jabatan + ') ' + '(' + value.unit_organisasi + ')</option>'
						);
					});
				});
		}
	}

		function deleteData(id_mutasi_detail) {
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
                $.get(base_url + "/dashboard/pelantikan_pegawai/delete",{
                    id_mutasi_detail:id_mutasi_detail
                }).then(function() {
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
