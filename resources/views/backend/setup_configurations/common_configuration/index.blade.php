@extends('backend.layouts.app')

@section('content')
    @php
        $count_common_config = \App\Models\CommonConfig::count();
    @endphp
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{translate('Cấu hình chung')}}</h1>
            </div>
            @if($count_common_config <= 1)
            <div class="col-md-6 text-md-right">
                <a href="{{ route('common_configs.create') }}" class="btn btn-circle btn-info">
                    <span>{{translate('Tạo cấu hình chung')}}</span>
                </a>
            </div>
                @endif
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{translate('Common Config')}}</h5>
        </div>
        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                <tr>
                    <th data-breakpoints="lg" width="10%">#</th>
                    <th data-breakpoints="lg">{{translate('Logo')}}</th>
                    <th data-breakpoints="lg">{{translate('Đơn vị tiền tệ')}}</th>
                    <th data-breakpoints="lg">{{translate('Số point cho người giới thiệu')}}</th>
                    <th data-breakpoints="lg">{{translate('Số point cho người kích hoạt bảo hiểm')}}</th>
                    <th data-breakpoints="lg">{{translate('Thông tin liên hệ')}}</th>
                    <th width="10%">{{translate('Options')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($common_configs as $key => $common_config)
                    @if($common_config != null)
                        <tr>
                            <td>{{$common_config->id}}</td>
                            <td><img src="{{ uploaded_asset($common_config->logo) }}" alt="logo" class="h-50px">
                            <td>{{$common_config->unit}}</td>
                            <td>{{$common_config->for_referrer}}</td>
                            <td>{{$common_config->for_activator}}</td>
                            <td>{{$common_config->contact_info}}</td>
                            <td class="text-right">
                                <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                   href="{{route('common_configs.edit', encrypt($common_config->id))}}"
                                   title="{{ translate('Edit') }}">
                                    <i class="las la-edit"></i>
                                </a>
                            </td>
                        </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection

@section('script')
    <script type="text/javascript">
    </script>
@endsection
