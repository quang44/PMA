<div class="modal fade" id="confirm-update-bank">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title h6"><?php echo e(translate('approval confirmation')); ?></h5>
                <button type="button" class="close" data-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><?php echo e(translate('Do you want to confirm approval?')); ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal"><?php echo e(translate('cancel')); ?></button>
                <a type="button" id="updateCard" class="btn btn-primary"><?php echo e(translate('Continue')); ?></a>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="confirm-ban">
    <form action="" id="confirmation" method="GET">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h6"><?php echo e(translate('Enter the reason for cancellation')); ?></h5>
                    <button type="button" class="close" data-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control" name="reason" placeholder="Lý do hủy">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light"
                            data-dismiss="modal"><?php echo e(translate('cancel')); ?></button>
                    <button type="submit" class="btn btn-primary"><?php echo e(translate('Continue')); ?></button>
                </div>
            </div>
        </div>
    </form>
</div>
<?php /**PATH F:\PHP\PMA\resources\views/modals/confirm_modal.blade.php ENDPATH**/ ?>