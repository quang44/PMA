@extends('backend.layouts.app')

@section('content')

<div class="card">
    <form class="" action="" id="sort_orders" method="GET">
        <div class="card-header row gutters-5">
            <div class="col">
                <h5 class="mb-md-0 h6">{{ translate('Lịch sử thanh toán') }}</h5>
            </div>
<!--            <div class="col-lg-2 ml-auto">
                <select class="form-control aiz-selectpicker" name="status" id="status">
                    <option value="">{{translate('Filter by Status')}}</option>

                </select>
            </div>-->
            <div class="col-lg-2">
                <div class="form-group mb-0">
                    <input type="text" class="aiz-date-range form-control" value="{{ request('date') }}" name="date" placeholder="{{ translate('Filter by date') }}" data-format="DD-MM-Y" data-separator=" to " data-advanced-range="true" autocomplete="off">
                </div>
            </div>
            <div class="col-lg-2">
                <div class="form-group mb-0">
                    <input type="text" class="form-control" id="search" name="search" @isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="{{ translate('Thông tin người yêu cầu') }}">
                </div>
            </div>
            <div class="col-auto">
                <div class="form-group mb-0">
                    <button type="submit" class="btn btn-primary">{{ translate('Tìm kiếm') }}</button>
                </div>
            </div>
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
                        <th data-breakpoints="md">Ngày y/cầu</th>
                        <th data-breakpoints="md">Ngày thanh toán</th>
                        <th data-breakpoints="md">Trạng thái</th>
                        <th data-breakpoints="md" class="text-center">Số tiền</th>
                        <th data-breakpoints="md" class="text-center">Thuế</th>
                        <th data-breakpoints="md"  class="text-center">Số tiền TT</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($payments as $key => $payment)
                    <tr>
                        <td>
                            {{ $payment->id }}
                        </td>
                        <td>
                            ID : {{ $payment->user->id }}
                            <br>
                            Name : {{ $payment->user->name }}
                            <br>
                            Phone : {{ $payment->user->phone }}
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
                            {{ $payment->created_time ? date('d-m-Y H:i:s', $payment->created_time) : '--' }}
                        </td>
                        <td>
                            {{ $payment->payment_time ? date('d-m-Y H:i:s', $payment->payment_time) : '--' }}
                        </td>
                        <td>
                            @if($payment->status == 2)
                                <span class="badge badge-inline badge-success">Đã thanh toán</span>
                            @else
                                <span class="badge badge-inline badge-danger">Đã hủy</span>
                            @endif
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
                        location.reload();
                    }else {
                        Swal.fire(response.message, '', 'error')
                    }
                }
            });
        }

    </script>
@endsection
