@extends('backend.layouts.app')

@section('content')

<div class="card">
    <form class="" action="" id="sort_orders" method="GET">
        <div class="card-header row gutters-5">
            <div class="col">
                <h5 class="mb-md-0 h6">{{ translate('Phiếu đối soát của khách hàng') }}</h5>
            </div>
            <div class="col-lg-2 ml-auto">
                <select class="form-control aiz-selectpicker" name="status" id="status">
                    <option value="">{{translate('Filter by Status')}}</option>
                    @foreach($status as $key => $value)
                        <option value="{{ $key }}" @if (request('status', 999) == $key) selected @endif>{{translate($value)}}</option>
                    @endforeach
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
<!--                        <th>{{ translate('Order Code') }}</th>-->
                        <th data-breakpoints="md">{{ translate('Bill ID') }}</th>
                        <th data-breakpoints="md">{{ translate('Customer') }}</th>
                        <th data-breakpoints="md">{{ translate('Thông tin CK') }}</th>
                        <th data-breakpoints="md">{{ translate('Created At') }}</th>
                        <th data-breakpoints="md">{{ translate('Total') }}</th>
                        <th data-breakpoints="md">{{ translate('Status') }}</th>
                        <th class="text-right">{{translate('options')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $key => $order)
                    <tr>
                        <td>
                            {{ $order->id }}
                        </td>
                        <td>
                            ID : {{ $order->user->id ?? '' }}
                            <br>
                            Name : {{ $order->user->name ?? '' }}
                            <br>
                            Phone : {{ $order->user->phone ?? '' }}
                        </td>
                        <td>
                            STK : {{ $order->user->customer_bank->number ?? '' }}
                            <br>
                            Chủ tài khoản : {{ $order->user->customer_bank->username ?? '' }}
                            <br>
                            Ngân hàng : {{ $order->user->customer_bank->name ?? '' }}
                        </td>
                        <td>
                            {{ date('d-m-Y H:i:s', $order->created_time) }}
                        </td>
                        <td>
                            {{ single_price($order->total_fee - $order->total_cod) }}
                        </td>
                        <td>
                            {{ $status[$order->status] ?? '' }}
                        </td>
                        <td class="text-right">
                            <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('customer_bill.show', encrypt($order->id))}}" title="{{ translate('View') }}">
                                <i class="las la-eye"></i>
                            </a>
                            @if($order->status == \App\Utility\CustomerBillUtility::STATUS_NEW)
                            <button type="button" class="btn btn-soft-info btn-icon btn-circle btn-sm " onclick="confirmUpdate(`{{ $order->id }}`)" title="{{ translate('Update payment bill') }}">
                                <i class="las la-edit"></i>
                            </button>
                            <button type="button" class="btn btn-soft-danger btn-icon btn-circle btn-sm" onclick="confirmCancel(`{{ $order->id }}`)"  title="{{ translate('Cancel bill') }}">
                                <i class="las la-trash"></i>
                            </button>
                            @endif
<!--                            <a class="btn btn-soft-info btn-icon btn-circle btn-sm" href="{{ route('invoice.download', $order->id) }}" title="{{ translate('Download Invoice') }}">
                                <i class="las la-download"></i>
                            </a>
                            <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('orders.destroy', $order->id)}}" title="{{ translate('Delete') }}">
                                <i class="las la-trash"></i>
                            </a>-->
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="aiz-pagination">
                {{ $orders->appends(request()->input())->links() }}
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
        $(document).on("change", ".check-all", function() {
            if(this.checked) {
                // Iterate each checkbox
                $('.check-one:checkbox').each(function() {
                    this.checked = true;
                });
            } else {
                $('.check-one:checkbox').each(function() {
                    this.checked = false;
                });
            }
        });

        function confirmCancel(id){
            Swal.fire({
                title: 'Bạn muốn hủy phiếu thanh toán này ?',
                showCancelButton: true,
                confirmButtonText: 'Ok',
                cancelButtonText: `Cancel`,
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    cancel(id)
                }
            })
        }

        function confirmUpdate(id){
            Swal.fire({
                title: 'Bạn muốn cập nhật trạng thái thanh toán cho phiếu này ?',
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
                url: 'customer_bill/update-payment/' + id,
                type: 'POST',
                /*data: {
                    id:id
                },*/
                success: function (response) {
                    if(response.result === true) {
                        location.reload();
                    }else {
                        Swal.fire(response.message, '', 'error')
                    }
                }
            });
        }

        function cancel(id) {

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: 'customer_bill/cancel/' + id,
                type: 'POST',
                /*data: {
                    id:id
                },*/
                success: function (response) {
                    if(response.result === true) {
                        location.reload();
                    }else {
                        Swal.fire(response.message, '', 'error')
                    }
                }
            });
        }
    </script>
@endsection
