@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class=" align-items-center">
        <h1 class="h3">Danh sách ví người dùng</h1>
    </div>
</div>

<div class="row">
    <div class="col-md-10 mx-auto">
        <div class="card">
            <form action="{{ route('wallet-history.index') }}" method="GET">
                <div class="card-header row gutters-5">
                    <div class="col text-center text-md-left">
                        <h5 class="mb-md-0 h6">Ví người dùng</h5>
                    </div>
                    @if(Auth::user()->user_type != 'seller')
                    <div class="col-md-3 ml-auto">
                        <select id="demo-ease" class="form-control form-control-sm aiz-selectpicker mb-2 mb-md-0" name="user_id"
                                data-selected-text-format="count"
                                data-live-search="true"
                        >
                            <option value="">{{ translate('Choose User') }}</option>
                            @foreach ($users_with_wallet as $key => $user)
                                <option value="{{ $user->id }}" @if($user->id == $user_id) selected @endif >
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="col-md-3">
                        <div class="form-group mb-0">
                            <input type="text" class="form-control form-control-sm aiz-date-range" id="search" name="date_range"@isset($date_range) value="{{ $date_range }}" @endisset placeholder="{{ translate('Daterange') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-md btn-primary" type="submit">
                            {{ translate('Filter') }}
                        </button>
                    </div>
                </div>
            </form>
            <div class="card-body">

                <table class="table aiz-table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ translate('Thợ')}}</th>
{{--                            <th data-breakpoints="lg">Vai trò</th>--}}
                            <th>Số dư hiện tại </th>
                            <th>Cập nhật gần nhất </th>
{{--                            <th> Nhóm </th>--}}
                            <th data-breakpoints="lg" class="text-right">{{ translate('action')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($wallets as $key => $wallet)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                @if ($wallet->user != null)
                                    <td> <a class=" text-primary" href="{{route('wallet-balance.balance',encrypt($wallet->user_id))}}" title="View">  {{ $wallet->user->name }}    </a></td>
                                @else
                                    <td>{{ translate('User Not found') }}</td>
                                @endif
{{--                                <td>--}}
{{--                                    @if($wallet->user!=null)--}}
{{--                                    @if($wallet->user->user_type == 'employee')--}}
{{--                                        <span class="badge badge-inline badge-success">Nhân viên</span>--}}
{{--                                    @endif--}}
{{--                                    @if($wallet->user->user_type == 'kol')--}}
{{--                                        <span class="badge badge-inline badge-info">CTV</span>--}}
{{--                                    @endif--}}
{{--                                        @endif--}}
{{--                                </td>--}}
                                <td>{{ (int)config_base64_decode($wallet->amount) }} điểm</td>
                                <td>{{date('d-m-Y H:i',strtotime($wallet->updated_at)) }}</td>
{{--                                <td> {{$wallet->user->customer_package->name}} </td>--}}

                                {{--                                <td>{{ ucfirst(str_replace('_', ' ', $wallet ->payment_method)) }}</td>--}}
                                <td class="text-right">
                                    <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('wallet-balance.balance',encrypt($wallet->user_id))}}" title="View">
                                        <i class="las la-eye"></i>
                                    </a>
{{--                                    @if ($wallet->offline_payment)--}}
{{--                                        @if ($wallet->approval)--}}
{{--                                            <span class="badge badge-inline badge-success">{{translate('Approved')}}</span>--}}
{{--                                        @else--}}
{{--                                            <span class="badge badge-inline badge-info">{{translate('Pending')}}</span>--}}
{{--                                        @endif--}}
{{--                                    @else--}}
{{--                                        N/A--}}
{{--                                    @endif--}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="aiz-pagination mt-4">
                    {{ $wallets->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
