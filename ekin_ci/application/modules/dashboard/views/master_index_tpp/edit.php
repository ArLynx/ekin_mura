<!-- Main content -->
<section class="content">

	<!-- Your Page Content Here -->
	<div class="box box-primary">
		<?php echo form_open(); ?>
		<div class="box-body">
			<?php alert_message_dashboard();?>

			<div class="form-group <?php echo form_error('ikfd') ? 'has-error' : ''; ?>">
				<label for="ikfd">IKFD</label>
				<input type="text" name="ikfd" id="ikfd" class="form-control" placeholder="Enter IKFD"
					value="<?php echo isset($master_index_tpp) ? $master_index_tpp->ikfd : set_value('ikfd'); ?>">
				<?php echo form_error('ikfd', '<p class="help-block text-red">', '</p>'); ?>
			</div>

            <div class="form-group <?php echo form_error('ikk') ? 'has-error' : ''; ?>">
				<label for="ikk">IKK</label>
				<input type="text" name="ikk" id="ikk" class="form-control" placeholder="Enter IKK"
					value="<?php echo isset($master_index_tpp) ? $master_index_tpp->ikk : set_value('ikk'); ?>">
				<?php echo form_error('ikk', '<p class="help-block text-red">', '</p>'); ?>
			</div>

            <div class="form-group <?php echo form_error('ippd') ? 'has-error' : ''; ?>">
				<label for="ippd">IPPD</label>
				<input type="text" name="ippd" id="ippd" class="form-control" placeholder="Enter IPPD"
					value="<?php echo isset($master_index_tpp) ? $master_index_tpp->ippd : set_value('ippd'); ?>">
				<?php echo form_error('ippd', '<p class="help-block text-red">', '</p>'); ?>
			</div>

            <div class="form-group <?php echo form_error('tahun') ? 'has-error' : ''; ?>">
				<label for="tahun">Tahun</label>
				<input type="text" name="tahun" id="tahun" class="form-control" placeholder="Enter Tahun"
					value="<?php echo isset($master_index_tpp) ? $master_index_tpp->tahun : set_value('tahun'); ?>">
				<?php echo form_error('tahun', '<p class="help-block text-red">', '</p>'); ?>
			</div>

		</div><!-- /.box-body -->

		<div class="box-footer">
			<button type="submit" class="btn btn-primary">Simpan</button>
		</div>
		<?php echo form_close(); ?>
	</div>

</section><!-- /.content -->
