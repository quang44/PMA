@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{translate('Tất cả nhóm người dùng')}}</h1>
            </div>
            <div class="col-md-6 text-md-right">
                <a href="{{ route('customer_packages.create') }}" class="btn btn-circle btn-info">
                    <span>{{translate('Thêm nhóm người dùng')}}</span>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        @foreach ($customer_packages as $key => $customer_package)
            <div class="col-lg-3 col-md-4 col-sm-12">
                <div class="card">
                    <div class="card-body text-center">
                        <p class="mb-3 h6 fw-600">{{$customer_package->name}}</p>
                        <img src="{{ uploaded_asset($customer_package->avatar) }}" alt="avatar" class="h-50px"><br><br>
                        <p><b>{{ translate('Điểm thưởng') }} : {{number_format($customer_package->bonus,0,'.','.')}}</b></p>
                        <p><b>{{ translate('Số point cần đạt') }} : {{number_format($customer_package->point, 0,'.', '.')}}</b></p>
                        <p><b>{{translate('Số tiền tối thiểu có thể rút')}} : {{single_price($customer_package->withdraw)}}</b></p>
                        <label class="aiz-switch aiz-switch-success mb-0">
                            <span>{{translate('Mặc định')}}</span>
                            <input class="default" value="{{ $customer_package->id }}" type="checkbox"
                                   @if($customer_package->default == 1) checked @endif>
                            <span class="slider round"></span>
                        </label>
                        <label class="aiz-switch aiz-switch-success mb-0">
                            <span>{{translate('Ẩn')}}</span>
                            <input class="hidden" value="{{ $customer_package->id }}" type="checkbox"
                                   @if($customer_package->status == 1) checked @endif>
                            <span class="slider round"></span>
                        </label>
                        <div class="mar-top mt-3">
                            <a href="{{route('customer_packages.edit', $customer_package->id )}}"
                               class="btn btn-sm btn-info">{{translate('Sửa')}}</a>
                            @if($customer_package->default != 1)
                                <a href="#" data-href="{{route('customer_packages.destroy', $customer_package->id)}}"
                                   class="btn btn-sm btn-danger confirm-delete">{{translate('Xóa')}}</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection

@section('script')
    <script type="text/javascript">
        function setup_hidden(el, route) {
            let status = 0;
            if (el.prop("checked")) {
                status = 1;
            }
            $.post(route, {
                _token: '{{ csrf_token() }}',
                id: el.val(),
                status: status
            }, function (data) {
                location.reload();
            });
        }

        $(document).on('click', '.hidden', function () {
            const route = '{{ route('customer_packages.setup_hidden') }}';
            setup_hidden($(this), route);
        });

        $(document).on('click', '.default', function () {
            const route = '{{ route('customer_packages.setup_default') }}';
            setup_hidden($(this), route);
        });

    </script>
@endsection
