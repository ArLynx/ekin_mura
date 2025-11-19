<style>
.user-image-custom{
	margin-bottom:10px;
}
</style>
<div class="content">
	<!-- Form inputs -->
	<div class="card">
		<div class="card-body">
			<?php echo form_open_multipart(current_url(),array('class'=>'form-validate-jquery')); ?>
				<fieldset class="mb-3">
					<legend class="text-uppercase font-size-sm font-weight-bold">User</legend>
					
					<div class="form-group row">
						<label class="col-form-label col-lg-2">Nama Lengkap <span class="text-danger">*</span></label>
						<div class="col-lg-10">
							<input type="text" class="form-control" value="<?php echo !empty($content)?$content->nama_lengkap:""; ?>" name="nama_lengkap" required placeholder="Nama Lengkap">
						</div>
					</div>
					
					<div class="form-group row">
						<label class="col-form-label col-lg-2">Username <span class="text-danger">*</span></label>
						<div class="col-lg-10">
							<input type="text" class="form-control" value="<?php echo !empty($content)?$content->username:""; ?>" name="username" required placeholder="Username">
						</div>
					</div>

					<div class="form-group row">
						<label class="col-form-label col-lg-2">Password <?php echo !empty($content)?'':'<span class="text-danger">*</span>'; ?></label>
						<div class="col-lg-10">
							<input type="password" name="password" id="password" <?php echo !empty($content)?'':'required'; ?> placeholder="Password" class="form-control">
							<?php echo empty($content)?'':'<span class="form-text text-muted">Jika ingin merubah password, silahkan diisi</span>'; ?>
						</div>
					</div>
					
					<div class="form-group row">
						<label class="col-form-label col-lg-2">Level <span class="text-danger">*</span></label>
						<div class="col-lg-10">
							<select class="form-control select-search" name="level_user" required>
								<option value="">-- Pilih Level User --</option>
								<?php
								foreach($level_user as $key=>$row){
									$selected = "";
									if(!empty($content)){
										if($row->id_level_user == $content->level_user_id){
											$selected = 'selected="selected"';
										}
									}
									?>
									<option <?php echo $selected; ?> value="<?php echo $row->id_level_user; ?>"><?php echo $row->nama_level_user; ?></option>
									<?php
								}
								?>
							</select>
						</div>
					</div>
					
					<div class="form-group row">
						<label class="col-lg-2 col-form-label">Foto <?php echo !empty($content)?'':'<span class="text-danger">*</span>'; ?></label>
						<div class="col-lg-10">
							<?php
							if(!empty($content)){
							?>
							<img class="user-image-custom" src="<?php echo base_url().'assets/foto_user/'.$content->foto_user; ?>">
							<?php
							}
							?>
							<input type="file" class="file-input" name="foto_user" <?php echo !empty($content)?'':'required'; ?> data-show-upload="false">
						</div>
					</div>
				</fieldset>

				<div class="text-right">
					<button type="submit" class="btn btn-primary">Submit <i class="icon-paperplane ml-2"></i></button>
				</div>
			<?php echo form_close(); ?>
		</div>
	</div>
	<!-- /form inputs -->

</div>

<script>
$(".file-input").on('change',function(){
	$(".user-image-custom").hide();
});
</script>