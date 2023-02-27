<div class="modal fade" id="confirm-ban">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title h6"><?php echo e(translate('Confirmation')); ?></h5>
                <button type="button" class="close" data-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Bạn muốn khóa tài khoản này ?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal"><?php echo e(translate('Cancel')); ?></button>
                <a type="button" id="confirmation" class="btn btn-primary"><?php echo e(translate('Proceed!')); ?></a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="confirm-unban">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title h6"><?php echo e(translate('Confirmation')); ?></h5>
                <button type="button" class="close" data-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Bạn muốn Kích hoạt tài khoản này ?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal"><?php echo e(translate('Cancel')); ?></button>
                <a type="button" id="confirmationunban" class="btn btn-primary"><?php echo e(translate('Proceed!')); ?></a>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="confirm-leverup">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title h6"><?php echo e(translate('Confirmation')); ?></h5>
                <button type="button" class="close" data-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Bạn muốn nâng cấp tài khoản này ?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal"><?php echo e(translate('Cancel')); ?></button>
                <a type="button" id="confirmationleverup" class="btn btn-primary"><?php echo e(translate('Proceed!')); ?></a>
            </div>
        </div>
    </div>
</div>

<?php /**PATH F:\PHP\PMA\resources\views/modals/confirm_banned_modal.blade.php ENDPATH**/ ?>