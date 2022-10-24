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
            @if($count_common_config == 0)
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
                    <th data-breakpoints="lg">{{translate('Currency unit')}}</th>
                    <th data-breakpoints="lg">{{translate('Points for referrers')}}</th>
                    <th data-breakpoints="lg">{{translate('Point for activator insurance')}}</th>
                    <th data-breakpoints="lg">{{translate('Convert points to cash')}}</th>
                    <th data-breakpoints="lg">{{translate('Contact Info')}}</th>
                    <th width="10%">{{translate('Options')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($common_configs as $key => $common_config)
                    @if($common_config != null)
                        <tr>
                            <td>{{$common_config->id}}</td>
                            <td width="120px"><img src="{{ uploaded_asset($common_config->logo) }}" alt="logo" class="h-50px">
                            <td>{{$common_config->unit}}</td>
                            <td>{{$common_config->for_referrer}}</td>
                            <td>{{$common_config->for_activator}}</td>
                            <td>{{$common_config->exchange}}</td>
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
