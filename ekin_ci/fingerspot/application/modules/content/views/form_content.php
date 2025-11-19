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
						<label class="col-form-label col-lg-2">Judul <span class="text-danger">*</span></label>
						<div class="col-lg-10">
							<input type="text" class="form-control" value="<?php echo !empty($content)?$content->judul:""; ?>" name="judul" required placeholder="Nama Lengkap">
						</div>
					</div>
					
					<div class="form-group row">
						<label class="col-form-label col-lg-2">Content <span class="text-danger">*</span></label>
						<div class="col-lg-10">
                            <textarea name="content" class="form-control" id="editor" rows="6"><?php echo !empty($content)?$content->description:""; ?></textarea>
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

CKEDITOR.replace( 'editor',{
        filebrowserImageBrowseUrl : '<?php echo base_url(); ?>assets/kcfinder'
    } );
</script>