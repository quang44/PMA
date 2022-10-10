@extends('backend.layouts.app')

@section('content')

<div class="card">
    <div class="card-header">
        <h1 class="h2 fs-16 mb-0">{{ translate('Chi tiết phiếu bảo hành') }}</h1>
    </div>
    <div class="card-body">
        <div class="gutters-5">
            <div><h6>Thông tin cơ bản</h6></div>
            <div class="mt-1">
                <span><b>{{ translate('Mã đối soát') }}</b>: {{ $bill->id }}</span>
            </div>
            <div class="mt-1">
                <span><b>{{ translate('Thời gian tạo') }}</b>: {{ date('d-m-Y H:i:s', $bill->created_time) }}</span>
            </div>
            <div class="mt-1">
                <span><b>{{ translate('Trạng thái') }}</b>: {{ $status[$bill->status] ?? '' }}</span>
            </div>
            @if($bill->status == \App\Utility\CustomerBillUtility::STATUS_SUCCESS)
            <div class="mt-1">
                <span><b>{{ translate('Thời gian thanh toán') }}</b>: {{ date('d-m-Y H:i:s', $bill->payment_time) }}</span>
            </div>
            @endif
        </div>
        <div class="gutters-5 mt-3">
            <div><h6>Thông tin khách hàng</h6></div>
            <div class="mt-1">
                <span><b>{{ translate('Tên') }}</b>: {{ $bill->user->name }}</span>
            </div>
            <div class="mt-1">
                <span><b>{{ translate('Email') }}</b>: {{ $bill->user->email }}</span>
            </div>
            <div class="mt-1">
                <span><b>{{ translate('Số điện thoại') }}</b>: {{ $bill->user->phone }}</span>
            </div>
        </div>
        <div class="row gutters-5 mt-3">
            <table class="table aiz-table mb-0">
                <thead>
                <tr>
                    <th class="text-right">Tiền thu hộ</th>
                    <th class="text-right">Phí vận chuyển</th>
                    <th class="text-right">Tổng tiền cần thanh toán</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="text-right">
                        {{ single_price($bill->total_cod) }}
                    </td>
                    <td class="text-right">
                        {{ single_price($bill->total_fee) }}
                    </td>
                    <td class="text-right">
                        {{ single_price($bill->total_cod - $bill->total_fee) }}
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="row gutters-5 mt-3">
            <div><h6>Danh sách đơn hàng</h6></div>
            <table class="table aiz-table mb-0">
                <thead>
                <tr>
                    <th>{{ trans('Mã đơn hàng') }}</th>
                    <th>{{ trans('Mã vận đơn') }}</th>
                    <th class="text-right">{{ trans('Tổng phí') }}</th>
                    <th class="text-right">{{ trans('Tiền thu hộ') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($orders as $value)
                <tr>
                    <td>{{ $value->id }}</td>
                    <td>{{ $value->parner_code }}</td>
                    <td class="text-right">{{ single_price($value->customer_total_fee) }}</td>
                    <td class="text-right">{{ single_price($value->collect_amount) }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('script')

@endsection
