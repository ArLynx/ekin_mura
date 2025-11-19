<!-- Main content -->
<section class="content">

	<!-- Your Page Content Here -->
	<div class="box box-primary">
		<?php echo form_open(); ?>
		<div class="box-body">
			<?php alert_message_dashboard();?>

			<div class="form-group">
				<label for="selected_unor">SOPD</label>
				<input type="text" id="selected_unor" class="form-control" value="<?php echo $selected_unor; ?>"
					readonly>
			</div>

			<div class="form-group">
				<label for="selected_groups">Groups</label>
				<input type="text" id="selected_groups" class="form-control" value="<?php echo $selected_groups; ?>"
					readonly>
			</div>

			<?php if ($id_selected_groups == '3'): ?>
			<div class="form-group <?php echo form_error('nip') ? 'has-error' : ''; ?>">
				<label for="nip">Pegawai</label>
				<select name="nip" id="nip" class="form-control select2">
					<option value="">Pilih Pegawai</option>
					<?php if ($pns): ?>
					<?php foreach ($pns as $row): ?>
						<option value="<?php echo $row->PNS_PNSNIP; ?>" <?php echo isset($user) ? ($user->nip == $row->PNS_PNSNIP ? 'selected' : '') : set_select('nip', $row->PNS_PNSNIP); ?>><?php echo $row->PNS_NAMA; ?></option>
					<?php endforeach;?>
					<?php endif;?>
				</select>
				<?php echo form_error('nip', '<p class="help-block text-red">', '</p>'); ?>
			</div>
			<?php endif;?>

			<div class="form-group <?php echo form_error('username') ? 'has-error' : ''; ?>">
				<label for="username">Username</label>
				<input type="text" name="username" id="username" class="form-control"
					placeholder="Username"
					value="<?php echo isset($user) ? $user->username : set_value('username'); ?>" <?php echo $id_selected_groups == '3' ? 'readonly' : ''; ?>>
				<?php echo form_error('username', '<p class="help-block text-red">', '</p>'); ?>
			</div>

			<div class="form-group <?php echo form_error('password') ? 'has-error' : ''; ?>">
				<label for="password">Password <?php echo isset($user) ? '<span style="color: red;">*Kosongkan bila tidak dirubah</span>' : ''; ?></label>
				<input type="password" name="password" id="password" class="form-control" placeholder="Password"
					value="<?php echo set_value('password'); ?>">
				<?php echo form_error('password', '<p class="help-block text-red">', '</p>'); ?>
			</div>

		</div><!-- /.box-body -->

		<div class="box-footer">
			<button type="submit" class="btn btn-primary">Simpan</button>
		</div>
		<?php echo form_close(); ?>
	</div>

</section><!-- /.content -->

<script>
$("#nip").on("change", function() {
  $("input[name=username]").val($("#nip option:selected").val());
});

</script>