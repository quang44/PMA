

<?php $__env->startSection('content'); ?>
<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col-md-6">
			<h1 class="h3"><?php echo e(translate('Tải file mới')); ?></h1>
		</div>
		<div class="col-md-6 text-md-right">
			<a href="<?php echo e(route('uploaded-files.index')); ?>" class="btn btn-link text-reset">
				<i class="las la-angle-left"></i>
				<span><?php echo e(translate('Quay lại danh sách file đã tải')); ?></span>
			</a>
		</div>
	</div>
</div>
<div class="card">
    <div class="card-header">
        <h5 class="mb-0 h6"><?php echo e(translate('Kéo và thả tệp của bạn')); ?></h5>
    </div>
    <div class="card-body">
    	<div id="aiz-upload-files" class="h-420px" style="min-height: 65vh">

    	</div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
	<script type="text/javascript">
		$(document).ready(function() {
			AIZ.plugins.aizUppy();
		});
	</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\PHP\PMA\resources\views/backend/uploaded_files/create.blade.php ENDPATH**/ ?>