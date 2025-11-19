<link rel="stylesheet" href="<?php echo base_url('assets/plugin/summernote/summernote.min.css'); ?>">
<script type="text/javascript" src="<?php echo base_url('assets/plugin/summernote/summernote.min.js'); ?>"></script>

<!-- Main content -->
<section class="content">

	<div class="row">
		<div class="col-md-12">
			<!-- Your Page Content Here -->
			<div class="box box-primary">
				<?php echo form_open(); ?>
				<div class="box-body">
					<?php alert_message_dashboard();?>

                    <div class="form-group">
                        <label for="title">Judul Informasi</label>
                        <input type="text" name="title" class="form-control" placeholder="Judul Informasi" value="<?php echo isset($information) ? $information->title : ''; ?>">
                    </div>

					<div class="form-group">
						<label for="content">Informasi</label>
						<textarea name="content" id="content" class="summernote form-control" placeholder="Informasi"><?php echo isset($information) ? $information->content : ''; ?></textarea>
					</div>

				</div><!-- /.box-body -->

				<div class="box-footer">
					<?php if($_created): ?>
						<button type="submit" class="btn btn-primary">Simpan</button>
					<?php endif; ?>
				</div>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>

</section><!-- /.content -->

<script type="text/javascript">
    $('.summernote').hide();
	$(document).ready(function () {
        $('.summernote').show();
		$('.summernote').summernote({
			height: 400,
		});
	});

</script>
