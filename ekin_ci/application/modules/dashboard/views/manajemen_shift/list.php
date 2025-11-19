<script type="text/javascript" src="<?php echo base_url('bower_components/moment/min/moment.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('bower_components/moment/locale/id.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js'); ?>"></script>

<link rel="stylesheet" href="<?php echo base_url('bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css'); ?>" />

<style>
	#showDatatable {
		height: 1px;
		font-size: 12px;
	}
    .select2 {
        width: auto !important;
        display: block;
    }
	span.select2.select2-container.select2-container--default.select2-container--below.select2-container--focus,
	.input-group {
		width: 100% !important;
	}
</style>


<!-- Main content -->
<section class="content" data-id_groups="<?php echo get_session('id_groups'); ?>"
	data-updated="<?php echo $_updated; ?>" data-deleted="<?php echo $_deleted; ?>">

	<!-- Your Page Content Here -->
	<div class="box">
		<div class="box-header with-border">
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<select class="form-control select2" name="selected_sopd" onchange="getData()"
							style="width: 100%;">
							<?php if (get_session('id_groups') == '1' || get_session('id_groups') == '4' || get_session('id_groups') == '5'): ?>
							<option value="">- Pilih SOPD -</option>
							<?php endif;?>
							<?php if ($all_sopd): ?>
							<?php foreach ($all_sopd as $row): ?>
							<option value="<?php echo $row->KD_UNOR; ?>" <?php echo get_session('selected_sopd_shift') != null ? (get_session('selected_sopd_shift') == $row->KD_UNOR ? 'selected' : '') : ''; ?>><?php echo $row->NM_UNOR; ?>
							</option>
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
							<option value="<?php echo $row->id; ?>" <?php echo get_session('selected_tipe_pegawai_shift') != null ? (get_session('selected_tipe_pegawai_shift') == $row->id ? 'selected' : '') : ''; ?>><?php echo $row->type; ?></option>
							<?php endforeach;?>
							<?php endif;?>
						</select>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<select class="form-control" name="selected_month" onchange="getData()">
							<option value="">- Pilih Bulan -</option>
							<?php if ($all_month): ?>
							<?php foreach ($all_month as $row): ?>
							<option value="<?php echo $row->month; ?>" <?php echo get_session('selected_month_shift') != null ? (get_session('selected_month_shift') == $row->month ? 'selected' : '') : ''; ?>><?php echo $row->month_text; ?></option>
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
							<option value="<?php echo $row->year; ?>" <?php echo get_session('selected_year_shift') != null ? (get_session('selected_year_shift') == $row->year ? 'selected' : '') : ($row->year == date('Y') ? 'selected' : ''); ?>><?php echo $row->year; ?>
							</option>
							<?php endforeach;?>
							<?php endif;?>
						</select>
					</div>
				</div>
				<div class="col-md-2">
					<?php if ($_created == 1): ?>
					<button type="button" id="btnAdd" class="btn btn-primary" data-toggle="modal" data-target="#modalAdd">
                        Tambah
                    </button>
					<?php endif;?>
					<?php if ($_deleted == 1): ?>
					<button type="button" id="btnDelete" class="btn btn-danger" data-toggle="modal" data-target="#modalDelete">
                        Hapus
                    </button>
					<?php endif;?>
				</div>
			</div>
		</div>
		<div class="box-body" style="padding-top: 0;">
			<div class="row">
				<div class="col-md-12">

				<div class="alert alert-info" role="alert">
					<i class="fa fa-info-circle"></i> <strong>Manajemen Shift</strong> ini hanya diisi bila ada pegawai pada dinas terkait yang memiliki jam shift, dan pengisiannya dilakukan sebelum tanggal berjalan. Terima Kasih..
				</div>

					<div class="table-responsive">
						<?php alert_message_dashboard();?>
						<div id="showDatatable"></div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal Add -->
	<div class="modal fade" id="modalAdd" tabindex="-1" role="dialog" aria-labelledby="modalAddLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
							aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="modalAddLabel">Tambah Shift Pegawai</h4>
				</div>
                <?php echo form_open('dashboard/manajemen_shift/add'); ?>
				<div class="modal-body">
                    <div class="row">
						<div class="col-md-12">
							<div class="table-responsive">
								<table class="table table-borderless margin-0">
									<tbody>
										<tr>
											<th width="120">SKPD</th>
											<th width="10">:</th>
											<td>
                                                <input type="hidden" name="modal_id_tipe_pegawai" id="modalIDTKDType">
                                                <input type="hidden" name="modal_unor" id="modalUNOR">
                                                <span id="skpdName"></span>
                                            </td>
										</tr>
										<tr>
											<th width="120">Pegawai</th>
											<th width="10">:</th>
											<td>
                                                <select class="form-control select2"  name="modal_nip" id="modal_nip" >
                                                    <option value="">- Pilih Pegawais -</option>
                                                </select>
                                            </td>
										</tr>
										<tr>
											<th width="120">Mulai Tanggal</th>
											<th width="10">:</th>
											<td>
                                                <input type="text" name="modal_mulai_tanggal" class="form-control datepicker" placeholder="Mulai Tanggal">
                                            </td>
										</tr>
										<tr>
											<th width="120">Sampai Tanggal</th>
											<th width="10">:</th>
											<td>
                                                <input type="text" name="modal_sampai_tanggal" class="form-control datepicker" placeholder="Sampai Tanggal">
                                            </td>
										</tr>
										<tr>
											<th width="120">Absen Masuk</th>
											<th width="10">:</th>
											<td>
												<div class="input-group date">
													<input type="text" name="modal_absen_masuk" class="form-control timepicker" placeholder="Absen Masuk">
												</div>
                                            </td>
										</tr>
										<tr>
											<th width="120">Absen Pulang</th>
											<th width="10">:</th>
											<td>
												<div class="input-group date">
                                               		<input type="text" name="modal_absen_pulang" class="form-control timepicker" placeholder="Absen Pulang">
                                            	</div>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
                </div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary">Simpan</button>
				</div>
                <?php echo form_close(); ?>
			</div>
		</div>
	</div>

	<!-- Modal Delete -->
	<div class="modal fade" id="modalDelete" tabindex="-1" role="dialog" aria-labelledby="modalDeleteLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
							aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="modalDeleteLabel">Hapus Shift Pegawai</h4>
				</div>
                <?php echo form_open('dashboard/manajemen_shift/delete'); ?>
				<div class="modal-body">
                    <div class="row">
						<div class="col-md-12">
							<div class="table-responsive">
								<table class="table table-borderless margin-0">
									<tbody>
										<tr>
											<th width="120">SKPD</th>
											<th width="10">:</th>
											<td>
                                                <input type="hidden" name="modal_id_tipe_pegawai" id="modalIDTKDType2">
                                                <input type="hidden" name="modal_unor" id="modalUNOR2">
                                                <span id="skpdName2"></span>
                                            </td>
										</tr>
										<tr>
											<th width="120">Pegawai</th>
											<th width="10">:</th>
											<td>
                                                <select name="modal_nip" id="modal_nip2" class="form-control select2">
                                                    <option value="">- Pilih Pegawai -</option>
                                                </select>
                                            </td>
										</tr>
										<tr>
											<th width="120">Mulai Tanggal</th>
											<th width="10">:</th>
											<td>
                                                <input type="text" name="modal_mulai_tanggal" class="form-control datepicker" placeholder="Mulai Tanggal">
                                            </td>
										</tr>
										<tr>
											<th width="120">Sampai Tanggal</th>
											<th width="10">:</th>
											<td>
                                                <input type="text" name="modal_sampai_tanggal" class="form-control datepicker" placeholder="Sampai Tanggal">
                                            </td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
                </div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary">Simpan</button>
				</div>
                <?php echo form_close(); ?>
			</div>
		</div>
	</div>

</section>
<!-- /.content -->

<script>
	$(function() {
		$(".datepicker").datetimepicker({
			locale: 'id',
			format: 'DD-MM-YYYY',
		});

		$(".timepicker").datetimepicker({
			locale: 'id',
			format: 'HH:mm',
		});

		getData();
	});

	$("#btnAdd").hide();

	$(".datepicker").on("dp.change", function() {
		$(".datepicker").data("DateTimePicker").hide();
	});

    $('#modalAdd').on('show.bs.modal', function (event) {
        let selected_sopd = $("select[name=selected_sopd]").val();
		let selected_month = $("select[name=selected_month]").val();
		let selected_year = $("select[name=selected_year]").val();
		let selected_tipe_pegawai = $("select[name=selected_tipe_pegawai]").val();

        $("#modalIDTKDType").val(selected_tipe_pegawai);
        $("#modalUNOR").val(selected_sopd);
		$("#skpdName").html($('select[name="selected_sopd"] option:selected').text());

        let url = '';
        if(selected_tipe_pegawai == '0' || selected_tipe_pegawai == '5' ) {
            url = base_url+'dashboard/pegawai_tpp/get_data?unor='+selected_sopd+'&tipe_pegawai='+selected_tipe_pegawai;
        } else {
            url = base_url+'dashboard/pegawai/get_data?unor='+selected_sopd+'&tipe_pegawai='+selected_tipe_pegawai;
        }

        $.get(url).then(function(response) {
            $("#modal_nip").html('<option value="">- Pilih Pegawai -</option>');
            if(response != '') {
                $.each(response, function(key, value) {
                    $("#modal_nip").append(
                        `<option value="`+value.PNS_PNSNIP+`">`+value.PNS_NAMA+`</option>`
                    );
                })
            }
        });
    });

	$('#modalDelete').on('show.bs.modal', function (event) {
        let selected_sopd = $("select[name=selected_sopd]").val();
		let selected_month = $("select[name=selected_month]").val();
		let selected_year = $("select[name=selected_year]").val();
		let selected_tipe_pegawai = $("select[name=selected_tipe_pegawai]").val();

        $("#modalIDTKDType2").val(selected_tipe_pegawai);
        $("#modalUNOR2").val(selected_sopd);
		$("#skpdName2").html($('select[name="selected_sopd"] option:selected').text());

        let url = '';
        if(selected_tipe_pegawai == '0') {
            url = base_url+'dashboard/pegawai_tpp/get_data?unor='+selected_sopd+'&tipe_pegawai='+selected_tipe_pegawai;
        } else {
            url = base_url+'dashboard/pegawai/get_data?unor='+selected_sopd+'&tipe_pegawai='+selected_tipe_pegawai;
        }

        $.get(url).then(function(response) {
            $("#modal_nip2").html('<option value="">- Pilih Pegawai -</option>');
            if(response != '') {
                $.each(response, function(key, value) {
                    $("#modal_nip2").append(
                        `<option value="`+value.PNS_PNSNIP+`">`+value.PNS_NAMA+`</option>`
                    );
                })
            }
        });
    });

	function calDaysInMonth(month, year) {
		return new Date(year, month, 0).getDate();
	}

	function getData() {
        $("#btnAdd").hide();
		let selected_sopd = $("select[name=selected_sopd]").val();
		let selected_tipe_pegawai = $("select[name=selected_tipe_pegawai]").val();
		let selected_month = $("select[name=selected_month]").val();
		let selected_year = $("select[name=selected_year]").val();

		if (selected_sopd && selected_month && selected_year && selected_tipe_pegawai) {
			let daysInMonth = calDaysInMonth(selected_month, selected_year);
			let tableHeaders = "<tr>" +
				"<th rowspan='2' class='th-top'>No</th>" +
				"<th rowspan='2' class='th-top' width='200'>Nama</th>" +
				"<th id='" + daysInMonth + "' colspan='"+daysInMonth+"' class='text-center'>Tanggal</th>" +
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

			let no = 1;

			let arrData = [
				{
					"render": function (data, type, row) {
						return no++;
					},
				},
				{
					"data": "PNS_NAMA"
				}
			];

            $("#btnAdd").show();


			for (let i = 1; i <= daysInMonth; i++) {
				arrData.push({
					"render": function (data, type, row) {
						return eval("row.absen_masuk"+ i) + "<br>" + eval("row.absen_pulang"+ i);
					},
				});
			}

			$("#datatableRB").dataTable({
				"destroy": true,
				"aaSorting": [],
				"bSort": false,
				"ajax": base_url + 'dashboard/manajemen_shift/get_data?unor='+selected_sopd+'&id_tipe_pegawai='+selected_tipe_pegawai+'&month='+selected_month+'&year='+selected_year,
				"columns": arrData,
				"oLanguage": {
					sLoadingRecords: `<img src="`+base_url + '/assets/img/loading.svg'+`">`
				}
			});
		}
	}

</script>
