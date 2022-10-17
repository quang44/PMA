@extends('backend.layouts.app')

@section('content')

<div class="card">
    <form class="" action="" id="sort_orders" method="GET">
        <div class="card-header row gutters-5">
            <div class="col">
                <h5 class="mb-md-0 h6">{{ translate('Danh sách yêu cầu thanh toán') }}</h5>
            </div>
<!--            <div class="col-lg-2 ml-auto">
                <select class="form-control aiz-selectpicker" name="status" id="status">
                    <option value="">{{translate('Filter by Status')}}</option>

                </select>
            </div>
            <div class="col-lg-2">
                <div class="form-group mb-0">
                    <input type="text" class="aiz-date-range form-control" value="{{ request('date') }}" name="date" placeholder="{{ translate('Filter by date') }}" data-format="DD-MM-Y" data-separator=" to " data-advanced-range="true" autocomplete="off">
                </div>
            </div>
            <div class="col-lg-2">
                <div class="form-group mb-0">
                    <input type="text" class="form-control" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="{{ translate('Mã đơn hàng') }}">
                </div>
            </div>
            <div class="col-auto">
                <div class="form-group mb-0">
                    <button type="submit" class="btn btn-primary">{{ translate('Filter') }}</button>
                </div>
            </div>-->
        </div>

        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <!--<th>#</th>-->
<!--                        <th>
                            <div class="form-group">
                                <div class="aiz-checkbox-inline">
                                    <label class="aiz-checkbox">
                                        <input type="checkbox" class="check-all">
                                        <span class="aiz-square-check"></span>
                                    </label>
                                </div>
                            </div>
                        </th>-->
<!--                        <th>{{ translate('#') }}</th>-->
                        <th>#</th>
                        <th data-breakpoints="md">Người y/cầu</th>
                        <th data-breakpoints="md">Vai trò</th>
                        <th data-breakpoints="md">{{ translate('Thông tin CK') }}</th>
                        <th data-breakpoints="md">Ngày y/cầu</th>
                        <th data-breakpoints="md" class="text-center">Số tiền Y/C</th>
                        <th data-breakpoints="md" class="text-center">Thuế</th>
                        <th data-breakpoints="md"  class="text-center">Số tiền TT</th>
                        <th class="text-right">{{translate('options')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($payments as $key => $payment)
                    <tr>
                        <td>
                            {{ $payment->id }}
                        </td>
                        <td>
                            <a href="{{ route('order_delivery.index', [$payment->user->user_type == 'kol' ? 'kol_id' : 'employee_id' => $payment->user->id, 'status_delivery' => [7,8]]) }}">
                                ID : {{ $payment->user->id }}
                                <br>
                                Name : {{ $payment->user->name }}
                                <br>
                                Phone : {{ $payment->user->phone }}
                            </a>

                        </td>
                        <td>
                            @if($payment->user->user_type == 'employee')
                                <span class="badge badge-inline badge-success">Nhân viên</span>
                            @endif
                            @if($payment->user->user_type == 'kol')
                                <span class="badge badge-inline badge-info">CTV</span>
                            @endif
                        </td>
                        <td>
                            STK : {{ $payment->user->customer_bank->number ?? '' }}
                            <br>
                            Chủ tài khoản : {{ $payment->user->customer_bank->username ?? '' }}
                            <br>
                            Ngân hàng : {{ $payment->user->customer_bank->name ?? '' }}
                        </td>
                        <td>
                            {{ date('d-m-Y H:i:s', $payment->created_time) }}
                        </td>
                        <td class="text-right">
                            {{ single_price($payment->value) }}
                        </td>
                        <td class="text-right">
                            {{ single_price($payment->vat) }}
                        </td>
                        <td class="text-right">
                            {{ single_price($payment->amount) }}
                        </td>
                        <td class="text-right">
                            <button type="button" class="btn btn-soft-info btn-icon btn-circle btn-sm " onclick="confirmUpdate(`{{ $payment->id }}`)" title="{{ translate('Update payment bill') }}">
                                <i class="las la-money-bill"></i>
                            </button>
                            <button type="button" class="btn btn-soft-danger btn-icon btn-circle btn-sm " onclick="openCancelPayment(`{{ $payment->id }}`)" title="{{ translate('Hủy yêu cầu') }}">
                                <i class="las la-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="aiz-pagination">
                {{ $payments->appends(request()->input())->links() }}
            </div>

        </div>
    </form>
</div>
<div id="cancel-payment-modal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title h6">Hủy yêu cầu thanh toán</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="reason">Lý do hủy</label>
                    <input type="text" name="reason" class="form-control">
                    <input type="hidden" name="id" class="form-control">
                </div>
                <div class="text-center">
                    <button type="button" class="btn btn-link mt-2" data-dismiss="modal">{{translate('Cancel')}}</button>
                    <a href="" id="cancel_payment" class="btn btn-primary mt-2">Xác nhận</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection

@section('script')
    <script src="{{ asset('public/assets/js/sweetalert2@11.js') }}"></script>
    <script type="text/javascript">

        function confirmUpdate(id){
            Swal.fire({
                title: 'Bạn muốn cập nhật trạng thái thanh toán cho yêu cầu này ?',
                showCancelButton: true,
                confirmButtonText: 'Ok',
                cancelButtonText: `Cancel`,
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    change_status(id)
                }
            })
        }

        function change_status(id) {

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/admin/affiliate/update_payment/' + id,
                type: 'POST',
                /*data: {
                    id:id
                },*/
                success: function (response) {
                    if(response.result === true) {
                        Swal.fire('Cập nhật thành công')
                        console.log('ok');
                        location.reload();
                    }else {
                        console.log('error');
                        Swal.fire(response.message, '', 'error')
                    }
                }
            });
        }

        function openCancelPayment(id){
            let $_this = $('#cancel-payment-modal')
            $_this.modal('show');
            $_this.find('input[name=id]').val(id);
        }

        $('#cancel_payment').on('click', function (){
            let $_this = $('#cancel-payment-modal')
            let id = $_this.find('input[name=id]').val();
            let reason = $_this.find('input[name=reason]').val();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/admin/affiliate/cancel_payment/' + id,
                type: 'POST',
                data: {
                    reason:reason
                },
                success: function (response) {
                    if(response.result === true) {
                        Swal.fire('Cập nhật thành công')
                        location.reload();
                    }else {
                        Swal.fire(response.message, '', 'error')
                    }
                }
            });
        })

    </script>
@endsection
