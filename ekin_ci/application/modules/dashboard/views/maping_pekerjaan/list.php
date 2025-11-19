<link rel="stylesheet" href="<?php echo base_url('assets/plugin/sweetalert2/dist/sweetalert2.min.css'); ?>">
<style>
.swal2-popup {
  font-size: 1.6rem !important;
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
						<select class="form-control select2" name="selected_sopd" onchange="getData()">
							<?php if (get_session('id_groups') == '5'): ?>
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
				<div class="col-md-4">
					<a href="#" id="addLinkMapPekerjaan" class="btn btn-primary">Tambah</a>
				</div>
			</div>
		</div>
		<div class="box-body" style="padding-top: 0;">
			<div class="row">
				<div class="col-md-12">
					<div class="table-responsive">
						<?php alert_message_dashboard();?>
						<table id="datatableMapPekerjaan" class="table table-striped table-bordered" style="width: 100%;">
							<thead>
								<tr>
									<th>No</th>
									<th>Jabatan Lama</th>
									<th>Jabatan Baru</th>
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
	var datatable = $('#datatableMapPekerjaan').DataTable({
		"columns": [{
				"width": "1"
			},
			{
				"width": "450"
			},
			null,
			{
				"width": "80"
			},
		],
		"aaSorting": [],
	});

    $("a#addLinkMapPekerjaan").hide();

	let _updated = $(".content").attr('data-updated');
	let _deleted = $(".content").attr('data-deleted');
	let _rincian = 1;
	let _id_groups = $(".content").attr('data-id_groups');

	$(function() {
		if(_id_groups != '5') {
			getData();
		}
	});

	function getData() {
		let selected_sopd = $("select[name=selected_sopd]").val();
		$.get(base_url + '/dashboard/maping_pekerjaan/get_data', {
			selected_sopd: selected_sopd
		})
			.then(function (response) {
				datatable.clear().draw();
                $("a#addLinkMapPekerjaan").attr('href', base_url + '/dashboard/maping-pekerjaan/add/' + selected_sopd);
                $("a#addLinkMapPekerjaan").show();
				let arrData = [];
				$.each(response, function (key, value) {
					let arrAksi = '';
					arrData = [
						++key,
                        ((value.nama_jabfus != null) ? value.NM_GENPOS + ' | ' + value.nama_jabfus : ((value.NM_FPOS != null) ? value.NM_GENPOS + ' | ' + value.NM_FPOS : value.NM_GENPOS)),
						value.nama_jabatan + ' | ' + value.unit_organisasi,
					];

					arrAksi += '<div class="td-action">' +
									'<div class="btn-group btn-group-md" role="group" aria-label="...">';

					if (_updated == 1) {
						arrAksi += '<a href="' + base_url + '/dashboard/maping-pekerjaan/edit/' + value.id_encrypt
                            + '/' + value.unor_encrypt +
							'.html" class="btn btn-warning" title="Ubah">' +
							'<i class="fa fa-edit"></i>' +
							'</a> ';
					}

					if (_deleted == 1) {
						arrAksi += '<button type="button" class="btn btn-danger" onclick="deleteData(\'' +
							value.id_encrypt + '\')" title="Hapus">' +
							'<i class="ion-trash-a"></i>' +
							'</button>';
					}

					arrAksi += 		'</div>' +
							   '</div>';

					if (arrAksi != '') {
						arrData.push(arrAksi);
					} else {
						arrData.push('<div class="td-action">' +
							'<div class="btn-group btn-group-sm" role="group" aria-label="...">' +
							'<span class="btn btn-danger" title="Locked"><i class="ion-ios-locked-outline"></i></span>' +
							'</div>' +
							'</div>');
					}

					datatable.row.add(arrData).draw(false);
				});
			});
	}

	function deleteData(id_encrypt) {
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
                $.get(base_url + "/dashboard/maping_pekerjaan/delete",{
                    id_encrypt:id_encrypt
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
