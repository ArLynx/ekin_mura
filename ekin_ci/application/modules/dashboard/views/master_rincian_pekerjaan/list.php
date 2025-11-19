<link rel="stylesheet" href="<?php echo base_url('assets/plugin/sweetalert2/dist/sweetalert2.min.css'); ?>">
<style>
.swal2-popup {
  font-size: 1.6rem !important;
}
.modal-title {
	font-weight: bold;
}
</style>
<script src="<?php echo base_url('assets/plugin/sweetalert2/dist/sweetalert2.min.js'); ?>"></script>

<!-- Main content -->
<section class="content" data-id_groups="<?php echo get_session('id_groups'); ?>" data-updated="<?php echo $_updated; ?>" data-deleted="<?php echo $_deleted; ?>">

	<!-- Your Page Content Here -->
	<div class="box">
		<div class="box-header with-border">
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<select class="form-control select2" name="selected_sopd" onchange="get_jabatan_by_sopd()" disabled>
							<?php if (get_session('id_groups') == '5'): ?>
							<option value="">- Pilih SOPD -</option>
							<?php endif;?>
							<?php if ($all_sopd): ?>
							<?php foreach ($all_sopd as $row): ?>
							<option value="<?php echo encode_crypt($row->KD_UNOR); ?>" <?php echo isset($pekerjaan) ? ($pekerjaan->PNS_UNOR == $row->KD_UNOR ? 'selected' : '') : ''; ?>><?php echo $row->NM_UNOR; ?></option>
							<?php endforeach;?>
							<?php endif;?>
						</select>
					</div>
					<div class="form-group">
						<select class="form-control select2" name="selected_jabatan" onchange="get_pekerjaan_by_jabatan()" disabled>
							<?php if (get_session('id_groups') == '5'): ?>
							<option value="">- Pilih Jabatan -</option>
							<?php endif;?>
							<?php if ($master_kelas_jabatan): ?>
							<?php foreach ($master_kelas_jabatan as $row): ?>
							<option value="<?php echo encode_crypt($row->id); ?>" <?php echo isset($id_master_kelas_jabatan) ? ($id_master_kelas_jabatan == $row->id ? 'selected' : '') : ($master_kelas_jabatan_selected->id == $row->id ? 'selected' : ''); ?>><?php echo $row->nama_jabatan; ?></option>
							<?php endforeach;?>
							<?php endif;?>
						</select>
						<!-- <input type="" name="offl" id="offl" value="<?php //echo decode_crypt($this->input->get('id_master_kelas_jabatan_encrypt', true)); ?>"> -->
						<?php $id_master_kelas_jabatan_encrypt = $this->input->get('id_master_kelas_jabatan_encrypt', true);?>
						<input type="hidden" name="master_kelas_jabatan_asli" id="master_kelas_jabatan_asli" value="<?php echo $id_master_kelas_jabatan_encrypt; ?>">
					</div>
					<div class="form-group">
						<select class="form-control select2" name="selected_pekerjaan" onchange="getData()">
							<?php if (get_session('id_groups') == '5'): ?>
							<option value="">- Pilih Pekerjaan -</option>
							<?php endif;?>
							<?php if ($master_pekerjaan_list): ?>
							<?php foreach ($master_pekerjaan_list as $row): ?>
							<option value="<?php echo encode_crypt($row->id); ?>" <?php echo isset($pekerjaan) ? ($pekerjaan->id == $row->id ? 'selected' : '') : ''; ?>><?php echo $row->nama_pekerjaan; ?></option>
							<?php endforeach;?>
							<?php endif;?>
						</select>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group" style="height: 84px;">
					</div>
					<div class="form-group">
						<button id="addLinkRPekerjaan" class="btn btn-primary load-data-rincian" type="button" data-toggle="modal" data-target="#addModal">Tambah</button>
					</div>
				</div>
			</div>
		</div>
		<div class="box-body" style="padding-top: 0;">
			<div class="row">
				<div class="col-md-12">
					<div class="table-responsive">
						<?php alert_message_dashboard();?>
						<table id="datatableMRincianPekerjaan" class="table table-striped table-bordered" style="width: 100%;">
							<thead>
								<tr>
									<th>No</th>
									<th>Nama Rincian Pekerjaan</th>
									<th>Norma Waktu</th>
									<th>Satuan</th>
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

<!-- Modal -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
				<button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Tambah Rincian Kegiatan</h4>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-body">
                        <span class="origin-form">
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">SOPD</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control textsopd" readonly />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Kelas Jabatan</label>
                                <div class="col-md-9">
									<input type="hidden" id="idkelasjabatan">
									<input type="hidden" id="idklsasli">
                                    <input type="text" class="form-control textjabatan" value="" readonly />
                                </div>
                            </div>
							<div class="form-group row">
                                <label class="col-md-3 col-form-label">Nama Pekerjaan</label>
                                <div class="col-md-9">
									<input type="hidden" id="idpekerjaan">
                                    <input type="text" class="form-control textpekerjaan" value="" readonly />
                                </div>
                            </div>
							<div class="form-group row">
                                <label class="col-md-3 col-form-label">Nama Rincian Pekerjaan</label>
                                <div class="col-md-9">
									<textarea nama="nama_rincian_pekerjaan" id="nama_rincian_pekerjaan" cols="30" rows="2" class="form-control" placeholder="Nama Rincian Pekerjaan" required></textarea>
                                </div>
                            </div>
							<div class="form-group row">
                                <label class="col-md-3 col-form-label">Norma Waktu</label>
                                <div class="col-md-9">
								<input type="text" name="norma_waktu" id="norma_waktu" class="form-control" placeholder="Norma Waktu (Max 60 menit)" value="" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Satuan</label>
                                <div class="col-md-9">
								<select name="id_satuan" id="id_satuan" class="form-control">
									<option value="">Pilih Satuan</option>
									<?php if ($satuan): ?>
									<?php foreach ($satuan as $row): ?>
									<option value="<?php echo $row->id; ?>"><?php echo $row->nama; ?></option>
									<?php endforeach;?>
									<?php endif;?>
								</select>
                                </div>
                            </div>
                            <hr>
                        </span>
                        <div class="place-clone"></div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-sm btn-primary save-add-rincian">
                            <i class="fa fa-dot-circle-o"></i> Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal end -->

<!-- Modal edit -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
				<button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Edit Rincian Kegiatan</h4>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-body">
                        <span class="origin-form">
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">SOPD</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control textsopd" readonly />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Kelas Jabatan</label>
                                <div class="col-md-9">
									<input type="hidden" id="idkelasjabatanedit">
									<input type="hidden" id="idklsasliedit">
                                    <input type="text" class="form-control textjabatan" value="" readonly />
                                </div>
                            </div>
							<div class="form-group row">
                                <label class="col-md-3 col-form-label">Nama Pekerjaan</label>
                                <div class="col-md-9">
									<input type="hidden" id="idpekerjaanedit">
                                    <input type="text" class="form-control textpekerjaan" value="" readonly />
                                </div>
                            </div>
							<div class="form-group row">
                                <label class="col-md-3 col-form-label">Nama Rincian Pekerjaan</label>
                                <div class="col-md-9">
									<input type="hidden" id="idrincianpekerjaanedit">
									<textarea name="nama_rincian_pekerjaan_edit" id="nama_rincian_pekerjaan_edit" cols="30" rows="2" class="form-control namarincianpekerjaanedit" placeholder="Nama Rincian Pekerjaan" required></textarea>
                                </div>
                            </div>
							<div class="form-group row">
                                <label class="col-md-3 col-form-label">Norma Waktu</label>
                                <div class="col-md-9">
								<input type="text" name="norma_waktu_edit" id="norma_waktu_edit" class="form-control normawaktuedit" placeholder="Norma Waktu (Max 60 menit)" value="" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Satuan</label>
                                <div class="col-md-9">
								<select name="id_satuan_edit" id="id_satuan" class="form-control idsatuanedit">
									<option value="">Pilih Satuan</option>
									<?php if ($satuan): ?>
									<?php foreach ($satuan as $row): ?>
									<option value="<?php echo $row->id; ?>"><?php echo $row->nama; ?></option>
									<?php endforeach;?>
									<?php endif;?>
								</select>
                                </div>
                            </div>
                            <hr>
                        </span>
                        <div class="place-clone"></div>
                    </div>
                    <div class="card-footer">
						<button class="btn btn-sm btn-primary save-edit-rincian">
                            <i class="fa fa-dot-circle-o"></i> Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal edit end -->

<script>
	var datatable = $('#datatableMRincianPekerjaan').DataTable({
		"columns": [{
				"width": "1"
			},
			{
				"width": "800"
			},
			null,
			null,
			{
				"width": "15"
			},
		],
		"aaSorting": [],
	});

	getData();
	$("#addLinkRPekerjaan").hide();

	// let _updated = $(".content").attr('data-updated');
	// let _deleted = $(".content").attr('data-deleted');
	let _updated = 1;
	let _deleted = 1;
	let _id_groups = $(".content").attr('data-id_groups');

	$(function() {
		if(_id_groups != '5') {
			getData();
		}
	});

	$(".load-data-rincian").click(function() {
		let sopdtext = $("select[name=selected_sopd]").find("option:selected").text();
		let jabatanid = $("select[name=selected_jabatan]").find("option:selected").val();
        let jabatantext = $("select[name=selected_jabatan]").find("option:selected").text();
		let id_kls_jab_asli = $("#master_kelas_jabatan_asli").val();
		let pekerjaanid = $("select[name=selected_pekerjaan]").find("option:selected").val();
        let pekerjaantext = $("select[name=selected_pekerjaan]").find("option:selected").text();
        $(".textsopd").val(sopdtext);
		$(".textjabatan").val(jabatantext);
		$(".textpekerjaan").val(pekerjaantext);
		$("#idkelasjabatan").val(jabatanid);
		$("#idklsasli").val(id_kls_jab_asli);
		$("#idpekerjaan").val(pekerjaanid);
		$('#nama_rincian_pekerjaan').html("");
		$('#norma_waktu').html("");
		$('#id_satuan').val("");

		$('#addModal').on('hidden.bs.modal', function () {
			$(this).find("input,textarea,select").val('').end();
		});
    });

	$(".save-add-rincian").click(function() {
		let kelasjabatan = $("#idkelasjabatan").val();
		let klsjabatanasli = $("#idklsasli").val();
        let pekerjaan = $("#idpekerjaan").val();
        let rincian_pekerjaan = $("#nama_rincian_pekerjaan").val();
		let norma_waktu = $("#norma_waktu").val();
		let id_satuan = $("#id_satuan").val();

        $.get(base_url + '/api/save_add_rincian', {
			kelasjabatan: kelasjabatan,
			klsjabatanasli: klsjabatanasli,
			pekerjaan: pekerjaan,
            rincian_pekerjaan: rincian_pekerjaan,
			norma_waktu: norma_waktu,
			id_satuan: id_satuan
        })
            .then(function(response) {
				if(norma_waktu > 60 || norma_waktu == ""){
					Swal.fire({
						type: 'error',
						title: 'Oops...',
						text: 'Norma waktu di isi dengan rentang nilai 1 - 60 menit'
					});
				}else if(!norma_waktu.match(/^\d+/)){
					Swal.fire({
						type: 'error',
						title: 'Oops...',
						text: 'Norma waktu harus di isi angka'
					});
				}else if(rincian_pekerjaan == ""){
					Swal.fire({
						type: 'error',
						title: 'Oops...',
						text: 'Rincian pekerjaan tidak boleh kosong'
					});
				}else if(id_satuan == ""){
					Swal.fire({
						type: 'error',
						title: 'Oops...',
						text: 'Satuan tidak boleh kosong'
					});
				}else{
					Swal.fire({
						type: 'success',
						title: 'Sukses',
						text: 'Tambah data sukses'
					});
					getData();
					datatable.clear().draw();
					$(".modal .close").click();
				}
            })
    });

	$(".save-edit-rincian").click(function() {
		let kelasjabatanedit = $("#idkelasjabatanedit").val();
		let klsjabatanasliedit = $("#idklsasliedit").val();
		let id_pekerjaan = $("#idpekerjaanedit").val();
        let id_rincian_pekerjaan = $("#idrincianpekerjaanedit").val();
        let rincian_pekerjaan = $("#nama_rincian_pekerjaan_edit").val();
		let norma_waktu = $("#norma_waktu_edit").val();
		let id_satuan = $("select[name=id_satuan_edit]").find("option:selected").val();
        $.get(base_url + '/api/save_edit_rincian', {
			kelasjabatanedit: kelasjabatanedit,
			klsjabatanasliedit: klsjabatanasliedit,
			id_pekerjaan: id_pekerjaan,
			id_rincian_pekerjaan: id_rincian_pekerjaan,
            rincian_pekerjaan: rincian_pekerjaan,
			norma_waktu: norma_waktu,
			id_satuan: id_satuan
        })

			.then(function(response) {
				if(norma_waktu > 60 || norma_waktu == ""){
					Swal.fire({
						type: 'error',
						title: 'Oops...',
						text: 'Norma waktu di isi dengan rentang nilai 1 - 60 menit'
					});
				}else if(!norma_waktu.match(/^\d+/)){
					Swal.fire({
						type: 'error',
						title: 'Oops...',
						text: 'Norma waktu harus di isi angka'
					});
				}else if(rincian_pekerjaan == ""){
					Swal.fire({
						type: 'error',
						title: 'Oops...',
						text: 'Rincian pekerjaan tidak boleh kosong'
					});
				}else if(id_satuan == ""){
					Swal.fire({
						type: 'error',
						title: 'Oops...',
						text: 'Satuan tidak boleh kosong'
					});
				}else{
					Swal.fire({
						type: 'success',
						title: 'Sukses',
						text: 'Edit sukses'
					});
					getData();
					datatable.clear().draw();
					$(".modal .close").click();
				}
            })
    });

	function getData() {
		let selected_sopd = $("select[name=selected_sopd]").val();
		let selected_jabatan = $("select[name=selected_jabatan]").val();
		let selected_pekerjaan = $("select[name=selected_pekerjaan]").val();
		$.get(base_url + '/dashboard/master_rincian_pekerjaan/get_data', {
			selected_sopd: selected_sopd,
			selected_jabatan: selected_jabatan,
			selected_pekerjaan: selected_pekerjaan
		})
			.then(function (response) {
				datatable.clear().draw();
				$("#addLinkRPekerjaan").show();
				let arrData = [];
				$.each(response, function (key, value) {
					let arrAksi = '';
					arrData = [
						++key,
						value.nama_rincian,
						value.norma_waktu,
						value.nm_satuan,
					];

					arrAksi += '<div class="td-action">' +
							   		'<div class="btn-group btn-group-md" role="group" aria-label="...">';

					if (_updated == 1) {
						arrAksi += '<button type="button" class="btn btn-warning" onclick="editData(\'' +
							value.id_encrypt + '\')" data-toggle="modal" data-target="#editModal" title="Ubah">' +
							'<i class="fa fa-edit"></i>' +
							'</button>';
					}

					if (_deleted == 1) {
						arrAksi += '<button type="button" class="btn btn-danger" onclick="deleteData(\'' +
							value.id_encrypt + '\')" title="Hapus">' +
							'<i class="ion-trash-a"></i>' +
							'</button>';
					}

					arrAksi +=	'</div>' +
							'</div>';

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

	function get_jabatan_by_sopd() {
		let selected_sopd = $("select[name=selected_sopd]").val();
		$.get(base_url + '/api/get_jabatan_bysopd', {
			selected_sopd: selected_sopd
		})
            .then(function(response) {
                $("select[name=selected_jabatan]").html("<option value=''>- Pilih Jabatan -</option>");
                $.each(response, function(key, value) {
                    $("select[name=selected_jabatan]").append(
                        "<option value='" + value.id_master_kelas_jabatan_encrypt + "'>" + value.nama_jabatan + "</option>"
                    );
                });
            })
	}

	function get_pekerjaan_by_jabatan() {
		let selected_sopd = $("select[name=selected_sopd]").val();
		let selected_jabatan = $("select[name=selected_jabatan]").val();
		$.get(base_url + '/api/get_pekerjaan_byjabatan', {
			selected_sopd: selected_sopd,
			selected_jabatan: selected_jabatan
		})
            .then(function(response) {
                $("select[name=selected_pekerjaan]").html("<option value=''>- Pilih Pekerjaan -</option>");
                $.each(response, function(key, value) {
                    $("select[name=selected_pekerjaan]").append(
                        "<option value='" + value.id_master_pekerjaan_encrypt + "'>" + value.nama_pekerjaan + "</option>"
                    );
                });
            })
	}

	function editData(id_encrypt) {
		let sopdtext = $("select[name=selected_sopd]").find("option:selected").text();
		let idkelasjabatan_edit = $("select[name=selected_jabatan]").find("option:selected").val();
		let idklsjabatanasli_edit = $("#master_kelas_jabatan_asli").val();
        let jabatantext = $("select[name=selected_jabatan]").find("option:selected").text();
		let pekerjaanid = $("select[name=selected_pekerjaan]").find("option:selected").val();
        let pekerjaantext = $("select[name=selected_pekerjaan]").find("option:selected").text();

		$.get(base_url + '/api/get_rincian_pekerjaan_byid', {
			id_encrypt: id_encrypt
		})
            .then(function(response) {
                $.each(response, function(key, value) {
					$(".textsopd").val(sopdtext);
					$(".textjabatan").val(jabatantext);
					$(".textpekerjaan").val(pekerjaantext);
					$("#idkelasjabatanedit").val(idkelasjabatan_edit);
					$("#idklsasliedit").val(idklsjabatanasli_edit);
					$("#idpekerjaanedit").val(pekerjaanid);
					$("#idrincianpekerjaanedit").val(value.id);
					$(".namarincianpekerjaanedit").val(value.nama_rincian);
					$(".normawaktuedit").val(value.norma_waktu);
					$(".idsatuanedit").val(value.id_satuan);
                });
            })

		$('#editModal').on('hidden.bs.modal', function () {
			$(this).find("input,textarea,select").val('').end();
		});
	}

	function deleteData(id_encrypt) {
		let selected_jabatan = $("select[name=selected_jabatan]").val();
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
                $.get(base_url + "/dashboard/master_rincian_pekerjaan/delete",{
                    id_encrypt:id_encrypt
                }).then(function() {
                    getData();
					datatable.clear().draw();
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
