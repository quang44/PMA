@extends('backend.layouts.app')

@section('content')

    <div class="card">
        <form class="" id="sort_customers" action="" method="GET">
            <div class="card-header row gutters-5">
                <div class="col-md-3">
                    <div class="form-group mb-0">
                        <input type="text" class="form-control" id="search" name="search"
                               @isset($sort_search) value="{{ $sort_search }}"
                               @endisset placeholder="{{ translate('Vui lòng nhập danh sách mã đơn') }}">
                    </div>
                </div>
            </div>
        </form>
        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                <tr>
                    <!--<th>#</th>-->
                    <th data-breakpoints="md">{{ translate('Mã đơn hàng') }}</th>
                    <th data-breakpoints="md">{{ translate('Mã giao vận') }}</th>
                    <th data-breakpoints="md">{{ translate('Ngày tạo') }}</th>
                    <th data-breakpoints="md" class="text-right">{{ translate('Thu hộ') }}</th>
                    <th data-breakpoints="md" class="text-right">{{ translate('Tổng phí') }}</th>
                    <th class="text-right" class="text-right">{{translate('options')}}</th>
                </tr>
                </thead>
                <tbody>
                @php
                    $sum_cod = $sum_fee = 0;
                @endphp
                @foreach ($orders as $key => $order)
                    @php
                        $sum_cod += $order->collect_amount;
                        $sum_fee += $order->total_fee;
                    @endphp
                    <tr>
                        <td>
                            {{ $order->id }}
                        </td>
                        <td>
                            {{ $order->partner_code }}
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
                            <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                               href="{{route('order_delivery.show', encrypt($order->id))}}"
                               title="{{ translate('View') }}">
                                <i class="las la-eye"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="3"></td>
                    <td style="color: red;font-weight: bold" class="text-right">{{ single_price($sum_cod) }}</td>
                    <td style="color: red;font-weight: bold" class="text-right">{{ single_price($sum_fee) }}</td>
                    <td style="color: red;font-weight: bold"
                        class="text-right">{{ single_price($sum_cod - $sum_fee) }}</td>
                </tr>
                </tbody>
            </table>

            <div class="aiz-pagination text-right">
                <input class="btn btn-primary" type="button" value="{{ translate('Xác nhận thanh toán') }}"
                       data-toggle="modal" data-target="#update-payment">
            </div>
        </div>
    </div>

    <div class="modal fade" id="update-payment" data-backdrop="static">
        <form action="{{ route('partner_bill.create') }}" method="POST">
            @csrf
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title h6">{{translate('Xác nhận thanh toán')}}</h5>
                        <button type="button" class="close" data-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">{{ trans('Mã đối soát đối tác') }}</label>
                            <input type="text" class="form-control" name="partner_bill_id" required>
                            <input type="hidden" name="ids" @isset($sort_search) value="{{ $sort_search }}"@endisset>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light"
                                data-dismiss="modal">{{translate('Cancel')}}</button>
                        <button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection

@section('script')
    <script>
        /*$(document).ready(function (){
            $('#updatePayment').on('click',function (){

            });
        });*/
    </script>
@endsection
