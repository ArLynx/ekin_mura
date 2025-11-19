<!-- Main content -->
<section class="content">

	<!-- Your Page Content Here -->
	<div class="box box-primary">
		<?php echo form_open(); ?>
		<div class="box-body">
			<?php alert_message_dashboard();?>

			<div class="form-group <?php echo form_error('unor') ? 'has-error' : ''; ?>">
				<label for="unor">SOPD</label>
				<select name="unor" id="unor" class="form-control select2" onchange="getAllPns()">
					<option value="">- Pilih SOPD -</option>
					<?php if ($all_sopd): ?>
					<?php foreach ($all_sopd as $row): ?>
					<option value="<?php echo $row->KD_UNOR; ?>"
						<?php echo $selected_unor == $row->KD_UNOR ? 'selected' : ''; ?>><?php echo $row->NM_UNOR; ?>
					</option>
					<?php endforeach;?>
					<?php endif;?>
				</select>
				<?php echo form_error('unor', '<p class="help-block text-red">', '</p>'); ?>
			</div>

			<div class="form-group <?php echo form_error('pns_pnsnip') ? 'has-error' : ''; ?>">
				<label for="pns_pnsnip">PNS</label>
				<select name="pns_pnsnip" id="pns_pnsnip" class="form-control select2">
					<option value="">- Pilih PNS -</option>
					<?php if (isset($detail_pns)): ?>
					<option value="<?php echo $detail_pns->PNS_PNSNIP ?>" selected>
						<?php echo "{$detail_pns->PNS_NAMA} | {$detail_pns->nama_jabatan}"; ?></option>
					<?php endif;?>
				</select>
				<?php echo form_error('pns_pnsnip', '<p class="help-block text-red">', '</p>'); ?>
			</div>

			<div class="form-group <?php echo form_error('nominal') ? 'has-error' : ''; ?>">
				<label for="nominal">Nominal</label>
				<input type="text" name="nominal" class="form-control input-currency" placeholder="Nominal"
					value="<?php echo isset($nominal_rapel) ? $nominal_rapel->nominal : set_value('nominal'); ?>">
				<?php echo form_error('nominal', '<p class="help-block text-red">', '</p>'); ?>
			</div>

			<div class="form-group <?php echo form_error('bulan_rapel') ? 'has-error' : ''; ?>">
				<label for="bulan_rapel">Bulan Rapel</label>
				<input type="month" name="bulan_rapel" class="form-control" placeholder="Bulan Rapel"
					value="<?php echo isset($nominal_rapel) ? $nominal_rapel->bulan_rapel : set_value('bulan_rapel'); ?>">
				<?php echo form_error('bulan_rapel', '<p class="help-block text-red">', '</p>'); ?>
			</div>

			<div class="form-group <?php echo form_error('keterangan') ? 'has-error' : ''; ?>">
				<label for="keterangan">Keterangan Rapel</label>
				<textarea name="keterangan" id="keterangan" class="form-control" placeholder="Keterangan Rapel"
					cols="30"
					rows="10"><?php echo isset($nominal_rapel) ? $nominal_rapel->keterangan : set_value('keterangan'); ?></textarea>
				<?php echo form_error('keterangan', '<p class="help-block text-red">', '</p>'); ?>
			</div>

		</div><!-- /.box-body -->

		<div class="box-footer">
			<button type="submit" class="btn btn-primary">Simpan</button>
		</div>
		<?php echo form_close(); ?>
	</div>

</section><!-- /.content -->

<?php if (!isset($detail_pns)): ?>
<script>
	$(function() {
		getAllPns();
	});
</script>
<?php endif;?>

<script>
	var cleave = new Cleave('.input-currency', {
		numeral: true,
		numeralThousandsGroupStyle: 'thousand',
		numeralDecimalMark: ',',
		delimiter: '.',
	});

	function getAllPns() {
		let selected_unor = $("select[name=unor]").val();
		if (selected_unor) {
			$("select[name=pns_pnsnip]").html('<option value="">- Pilih PNS -</option>');

			$.get(base_url + '/api/get_all_pegawai_sopd', {
					unor: selected_unor,
					is_tkd: 'yes'
				})
				.then(function (response) {
					$.each(response, function (key, value) {
						$("select[name=pns_pnsnip]").append(
							"<option value='" + value.PNS_PNSNIP + "'>" + value.PNS_NAMA + " | " + value
							.nama_jabatan + "</option>"
						);
					});
				});
		}
	}

</script>
