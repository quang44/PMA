@extends('backend.layouts.app')

@section('content')

<div class="card">
    <form class="" action="{{ route('customer_bill.create', request('user_id')) }}" id="" method="POST">
        @csrf
        <div class="card-header row gutters-5">
            <div class="col">
                <h5 class="mb-md-0 h6">{{ translate('Tạo phiếu đối soát cho khách hàng') }}</h5>
            </div>
        </div>

        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <!--<th>#</th>-->
                        <th>
                            <div class="form-group">
                                <div class="aiz-checkbox-inline">
                                    <label class="aiz-checkbox">
                                        <input type="checkbox" checked class="check-all">
                                        <span class="aiz-square-check"></span>
                                    </label>
                                </div>
                            </div>
                        </th>
                        <th data-breakpoints="md">{{ translate('Mã đơn hàng') }}</th>
                        <th data-breakpoints="md">{{ translate('Khách hàng') }}</th>
                        <th data-breakpoints="md">{{ translate('Đc lấy hàng') }}</th>
                        <th data-breakpoints="md">{{ translate('Đc nhận hàng') }}</th>
                        <th data-breakpoints="md">{{ translate('Đc hoàn hàng') }}</th>
                        <th data-breakpoints="md">{{ translate('Ngày tạo') }}</th>
                        <th data-breakpoints="md">{{ translate('Thu hộ') }}</th>
                        <th data-breakpoints="md">{{ translate('Tổng phí') }}</th>
                        <th class="text-right">{{translate('options')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $key => $order)
                    <tr>
                        <td>
                            <div class="form-group">
                                <div class="aiz-checkbox-inline">
                                    <label class="aiz-checkbox">
                                        <input type="checkbox" class="check-one" checked name="id[]" value="{{$order->id}}">
                                        <span class="aiz-square-check"></span>
                                    </label>
                                </div>
                            </div>
                        </td>
                        <td>
                            MĐH : {{ $order->id }}
                            <br>
                            MGV : {{ $order->partner_code }}
                        </td>
                        <td>
                            ID : {{ $order->user->id }}
                            <br>
                            Name : {{ $order->user->name }}
                            <br>
                            Phone : {{ $order->user->phone }}
                        </td>
                        <td>
                            {{ $order->source_name }}
                            <br>
                            {{ $order->source_phone }}
                            <br>
                            {{ $order->source_address }}, {{ $order->source_ward }}, {{ $order->source_district }}, {{ $order->source_province }}
                        </td>
                        <td>
                            {{ $order->dest_name }}
                            <br>
                            {{ $order->dest_phone }}
                            <br>
                            {{ $order->dest_address }}, {{ $order->dest_ward }}, {{ $order->dest_district }}, {{ $order->dest_province }}
                        </td>
                        <td>
                            {{ $order->return_name }}
                            <br>
                            {{ $order->return_phone }}
                            <br>
                            {{ $order->return_address }}, {{ $order->return_ward }}, {{ $order->return_district }}, {{ $order->return_province }}
                        </td>
                        <td>
                            {{ date('d-m-Y H:i:s', $order->created_time) }}
                        </td>
                        <td>
                            {{ single_price($order->collect_amount) }}
                        </td>
                        <td>
                            {{ single_price($order->customer_total_fee) }}
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

            <div class="aiz-pagination text-right">
                <input class="btn btn-primary" type="submit" value="{{ translate('Confirm') }}">
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
