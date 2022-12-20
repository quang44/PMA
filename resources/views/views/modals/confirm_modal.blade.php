<div class="modal fade" id="confirm-update-bank">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title h6">{{translate('approval confirmation')}}</h5>
                <button type="button" class="close" data-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>{{translate('Do you want to confirm approval?')}}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">{{translate('cancel')}}</button>
                <a type="button" id="updateCard" class="btn btn-primary">{{translate('Continue')}}</a>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="confirm-ban">
    <form action="" id="confirmation" method="GET">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h6">{{translate('Enter the reason for cancellation')}}</h5>
                    <button type="button" class="close" data-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control" name="reason" placeholder="Lý do hủy">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light"
                            data-dismiss="modal">{{translate('cancel')}}</button>
                    <button type="submit" class="btn btn-primary">{{translate('Continue')}}</button>
                </div>
            </div>
        </div>
    </form>
</div>
