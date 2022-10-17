@extends('backend.layouts.app')
@section('style')
    <style>
        p{
            margin: 0;
        }
    </style>
@endsection
@section('content')

<div class="card">
    <form class="" action="" id="sort_orders" method="GET">
        <div class="col p-3">
            <div class="row pb-3">
                <div class="col-lg-3">
                    <select class="form-control aiz-selectpicker" name="status_delivery[]" multiple="" id="status_delivery" data-title="{{translate('Tình trạng giao hàng')}}" data-size="10">
                    <!--                    <option value="">{{translate('Tình trạng giao hàng')}}</option>-->
                        @foreach($status_delivery as $key => $value)
                            <option value="{{ $key }}" @if (in_array($key, request('status_delivery', [999]))) selected @endif >{{translate($value)}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3">
                    <select class="form-control aiz-selectpicker" name="status_payment" id="status_payment">
                        <option value="">{{translate('Trạng thái tt shop')}}</option>
                        @foreach($status_payment as $key => $value)
                            <option value="{{ $key }}" @if (request('status_payment', 999) == $key) selected @endif>{{translate($value)}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3">
                    <select class="form-control aiz-selectpicker" name="partner_status_payment" id="partner_status_payment">
                        <option value="">{{translate('Trạng thái tt đối tác')}}</option>
                        @foreach($partner_status_payment as $key => $value)
                            <option value="{{ $key }}" @if (request('partner_status_payment', 999) == $key) selected @endif>{{translate($value)}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row pb-3">
                <div class="col-lg-3">
                    <div class="form-group mb-0">
                        <input type="text" class="aiz-date-range form-control" value="{{ request('date') }}" name="date" placeholder="{{ translate('Ngày tạo') }}" data-format="DD-MM-Y" data-separator=" to " data-advanced-range="true" autocomplete="off">
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group mb-0">
                        <input type="text" class="form-control" id="phone" name="phone" value="{{ request('phone') }}" placeholder="Số điện thoại khách hàng">
                    </div>
                </div><div class="col-lg-3">
                    <div class="form-group mb-0">
                        <input type="text" class="form-control" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="{{ translate('mã đơn hàng, mã giao vận') }}">
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-primary">{{ translate('Tìm kiếm') }}</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table aiz-table mb-0 table-bordered">
                    <thead>
                    <tr>
                        <th colspan="6"><b>Tổng số đơn hàng: <span style="color: red">{{ $orders->total() }}</span> </b></th>
                        <th class="text-right" style="color: red">{{ single_price($sum_cod) }}</th>
                        <th class="text-right" style="color: red">{{ single_price($sum_fee) }}</th>
                        <th class="text-right" style="color: red">{{ single_price($sum_customer_fee) }}</th>
                        <th colspan="4"></th>
                    </tr>
                    <tr>
                        <th data-breakpoints="md">{{ translate('Mã đơn hàng ') }}</th>
                        <th data-breakpoints="md">{{ translate('Khách hàng') }}</th>
                        <th data-breakpoints="md">{{ translate('Kol') }}</th>
                        <th data-breakpoints="md">{{ translate('Nhân viên') }}</th>
                        <th data-breakpoints="md">{{ translate('Thông tin giao hàng') }}</th>
                        <th data-breakpoints="md">{{ translate('Thơi gian tạo') }}</th>
                        <th data-breakpoints="md">{{ translate('Tiền thu hộ') }}</th>
                        <th data-breakpoints="md">{{ translate('Tiền phí') }}</th>
                        <th data-breakpoints="md">{{ translate('Tiền thu shop') }}</th>
                        <th data-breakpoints="md">{{ translate('TT giao hàng') }}</th>
                        <th data-breakpoints="md">{{ translate('TT thanh toán shop') }}</th>
                        <th data-breakpoints="md">{{ translate('TT thanh toán đối tác') }}</th>
                        <th class="text-right">{{translate('options')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($orders as $key => $order)
                        <tr>
                            <td>
                                <b>Mã đơn hàng : </b>
                                <p><a href="{{route('order_delivery.show', encrypt($order->id))}}">{{ $order->id }}</a></p>
                                <b>Mã giao vận</b>
                                <p>{{ $order->partner_code }}</p>
                                <b>Mã đối soát shop</b>
                                <p>{{ $order->bill_id }}</p>
                                <b>Mã đối soát đối tác</b>
                                <p>{{ $order->partner_bill_id }}</p>
                                <p><a href="{{route('order_delivery.show', encrypt($order->id))}}">Xem chi tiết</a></p>
                            </td>
                            <td>
                                {{ $order->user->id ?? '' }}
                                <br>
                                {{ $order->user->name ?? '' }}
                                <br>
                                {{ $order->user->phone ?? '' }}
                            </td>
                            <td>
                                {{ $order->kol->id ?? '' }}
                                <br>
                                {{ $order->kol->name ?? '' }}
                                <br>
                                {{ $order->kol->phone ?? '' }}
                            </td>
                            <td>
                                {{ $order->employee->id ?? '' }}
                                <br>
                                {{ $order->employee->name ?? '' }}
                                <br>
                                {{ $order->employee->phone ?? '' }}
                            </td>
                            <td>
                                <b>Địa chỉ lấy hàng</b>
                                <p>{{ $order->source_name }}</p>
                                <p>{{ $order->source_phone }}</p>
                                <p>
                                    {{ $order->source_address }}, {{ $order->source_ward }}, {{ $order->source_district }}, {{ $order->source_province }}
                                </p>
                                <b>Địa chỉ giao hàng</b>
                                <p>{{ $order->dest_name }}</p>
                                <p>{{ $order->dest_phone }}</p>
                                <p>
                                    {{ $order->dest_address }}, {{ $order->dest_ward }}, {{ $order->dest_district }}, {{ $order->dest_province }}
                                </p>
                                <b>Địa chỉ hoàn hàng</b>
                                <p>{{ $order->return_name }}</p>
                                <p>{{ $order->return_phone }}</p>
                                <p>
                                    {{ $order->return_address }}, {{ $order->return_ward }}, {{ $order->return_district }}, {{ $order->return_province }}
                                </p>
                            </td>
                            <td>
                                {{ date('d-m-Y H:i:s', $order->created_time) }}
                            </td>
                            <td class="text-right">
                                {{ single_price($order->collect_amount) }}
                            </td>
                            <td class="text-right">
                                {{ single_price($order->total_fee) }}
                            </td>
                            <td class="text-right">
                                {{ single_price($order->customer_total_fee) }}
                            </td>
                            <td>
                                {{$order->status}}
                                @if($order->status == \App\Utility\OrderDeliveryUtility::STATUS_DELIVERED)
                                    <span class="badge badge-inline badge-success">{{ $status_delivery[$order->status] ?? '' }}</span>
                                @elseif($order->status == \App\Utility\OrderDeliveryUtility::STATUS_CANCEL)
                                    <span class="badge badge-inline badge-danger">{{ $status_delivery[$order->status] ?? '' }}</span>
                                @elseif($order->status == \App\Utility\OrderDeliveryUtility::STATUS_RETURNED)
                                    <span class="badge badge-inline badge-warning">{{ $status_delivery[$order->status] ?? '' }}</span>
                                @elseif($order->status == \App\Utility\OrderDeliveryUtility::STATUS_LOST)
                                    <span class="badge badge-inline badge-primary">{{ $status_delivery[$order->status] ?? '' }}</span>
                                @elseif($order->status == \App\Utility\OrderDeliveryUtility::STATUS_NEW)
                                    <span class="badge badge-inline badge-danger">{{ $status_delivery[$order->status] ?? '' }}</span>
                                @else
                                    <span class="badge badge-inline badge-info">{{ $status_delivery[$order->status] ?? '' }}</span>
                                @endif
                            </td>
                            <td>
                                {{$order->status_payment}}
                                @if($order->status_payment == \App\Utility\OrderDeliveryUtility::STATUS_PAYMENT_PENDING)
                                    <span class="badge badge-inline badge-warning">{{ $status_payment[$order->status_payment] ?? '' }}</span>
                                @elseif($order->status_payment == \App\Utility\OrderDeliveryUtility::STATUS_PAYMENT_CONFIRM)
                                    <span class="badge badge-inline badge-info">{{ $status_payment[$order->status_payment] ?? '' }}</span>
                                @elseif($order->status_payment == \App\Utility\OrderDeliveryUtility::STATUS_PAYMENT_SUCCESS)
                                    <span class="badge badge-inline badge-success">{{ $status_payment[$order->status_payment] ?? '' }}</span>
                                @else
                                    <span class="badge badge-inline badge-dark">{{ $status_payment[$order->status_payment] ?? '' }}</span>
                                @endif

                            </td>
                            <td>
                                @if($order->partner_status_payment == \App\Utility\OrderDeliveryUtility::PARTNER_STATUS_PAYMENT_SUCCESS)
                                    <span class="badge badge-inline badge-success">{{ $partner_status_payment[$order->partner_status_payment] ?? '' }}</span>
                                @else
                                    <span class="badge badge-inline badge-danger">{{ $partner_status_payment[$order->partner_status_payment] ?? '' }}</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('order_delivery.show', encrypt($order->id))}}" title="{{ translate('View') }}">
                                    <i class="las la-eye"></i>
                                </a>
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
            </div>

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

//        function change_status() {
//            var data = new FormData($('#order_form')[0]);
//            $.ajax({
//                headers: {
//                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//                },
//                url: "{{route('bulk-order-status')}}",
//                type: 'POST',
//                data: data,
//                cache: false,
//                contentType: false,
//                processData: false,
//                success: function (response) {
//                    if(response == 1) {
//                        location.reload();
//                    }
//                }
//            });
//        }

        function bulk_delete() {
            var data = new FormData($('#sort_orders')[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('bulk-order-delete')}}",
                type: 'POST',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    if(response == 1) {
                        location.reload();
                    }
                }
            });
        }
    </script>
@endsection
