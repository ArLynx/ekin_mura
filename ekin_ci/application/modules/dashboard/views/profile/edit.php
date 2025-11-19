<!-- Main content -->
<section class="content">

	<!-- Your Page Content Here -->
	<div class="box box-primary">
		<?php echo form_open_multipart(); ?>
		<div class="box-body">
		<?php alert_message_dashboard();?>

			<div class="form-group <?php echo form_error('username') ? 'has-error' : ''; ?>">
				<label for="username">Username</label>
				<input type="text" id="username" class="form-control" placeholder="Username"
					value="<?php echo isset($user) ? $user->username : set_value('username'); ?>" readonly>
				<?php echo form_error('username', '<p class="help-block text-red">', '</p>'); ?>
			</div>

			<div class="form-group <?php echo form_error('password') ? 'has-error' : ''; ?>">
				<label for="password">Password (Biarkan saja bila tidak ingin dirubah.)</label>
				<input type="password" name="password" id="password" class="form-control" placeholder="Password"
					value="<?php echo isset($user) ? $user->password : set_value('password'); ?>">
				<?php echo form_error('password', '<p class="help-block text-red">', '</p>'); ?>
			</div>

			<div class="form-group <?php echo form_error('email') ? 'has-error' : ''; ?>">
				<label for="email">Email</label>
				<input type="text" name="email" id="email" class="form-control" placeholder="Email"
					value="<?php echo isset($user) ? $user->email : set_value('email'); ?>">
				<?php echo form_error('email', '<p class="help-block text-red">', '</p>'); ?>
			</div>

			<div class="attachment-block clearfix">
				<img class="attachment-img"
					src="<?php echo !empty($user->photo) ? base_url(path_image('user_path') . $user->photo) : base_url('assets/img/user.png'); ?>"
					alt="<?php echo $user->username; ?>">
			</div>

			<div class="form-group">
				<label for="photo">Photo</label>
				<input type="file" name="photo" id="photo" class="form-control" placeholder="Photo">
			</div>

			<?php if(get_session('akses_login') == '2'): ?>

			<div class="form-group <?php echo form_error('sopd_name') ? 'has-error' : ''; ?>">
				<label for="sopd_name">Nama SOPD</label>
				<input type="text" name="sopd_name" id="sopd_name" class="form-control" placeholder="Nama SOPD"
					value="<?php echo isset($user) ? $user->sopd_name : set_value('sopd_name'); ?>">
				<?php echo form_error('sopd_name', '<p class="help-block text-red">', '</p>'); ?>
			</div>
			
			<div class="form-group <?php echo form_error('title_atasan_skpd') ? 'has-error' : ''; ?>">
				<label for="title_atasan_skpd">Sebutan Kepala SOPD</label>
				<input type="text" name="title_atasan_skpd" id="title_atasan_skpd" class="form-control" placeholder="Sebutan Kepala SOPD"
					value="<?php echo isset($user) ? $user->title_atasan_skpd : set_value('title_atasan_skpd'); ?>">
				<?php echo form_error('title_atasan_skpd', '<p class="help-block text-red">', '</p>'); ?>
			</div>

			<div class="form-group <?php echo form_error('atasan_sopd') ? 'has-error' : ''; ?>">
				<label for="atasan_sopd">Atasan SOPD</label>
				<select name="atasan_sopd" id="atasan_sopd" class="form-control select2">
					<option value="">Pilih Atasan SOPD</option>
					<?php if ($pns): ?>
					<?php foreach ($pns as $row): ?>
						<option value="<?php echo $row->PNS_PNSNIP; ?>" <?php echo isset($user) ? ($user->atasan_sopd == $row->PNS_PNSNIP ? 'selected' : '') : set_select('atasan_sopd', $row->PNS_PNSNIP); ?>><?php echo $row->PNS_NAMA; ?></option>
					<?php endforeach;?>
					<?php endif;?>
				</select>
				<?php echo form_error('atasan_sopd', '<p class="help-block text-red">', '</p>'); ?>
			</div>

			<?php endif; ?>

		</div><!-- /.box-body -->

		<div class="box-footer">
			<button type="submit" class="btn btn-primary">Simpan</button>
		</div>
		<?php echo form_close(); ?>
	</div>

</section><!-- /.content -->
