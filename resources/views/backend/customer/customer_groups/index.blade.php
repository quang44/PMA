@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{translate('Nhóm người dùng')}}</h1>
            </div>
            <div class="col-md-6 text-md-right">
                <a href="{{ route('customer_groups.create') }}" class="btn btn-circle btn-info">
                    <span>{{translate('Thêm nhóm người dùng')}}</span>
                </a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{translate('Customer Groups')}}</h5>
        </div>
        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                <tr>
                    <th data-breakpoints="lg" width="10%">#</th>
                    <th>{{translate('Tên nhóm')}}</th>
                    <th>{{translate('Ảnh đại diện')}}</th>
                    <th>{{translate('Tiền thưởng')}}</th>
                    <th>{{translate('Mô tả')}}</th>
                    <th>{{translate('Mặc định')}}</th>
                    <th>{{translate('Ẩn')}}</th>
                    <th data-breakpoints="lg">{{translate('Ngày tạo')}}</th>
                    <th data-breakpoints="lg">{{translate('Ngày cập nhật')}}</th>
                    <th width="10%">{{translate('Options')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($customer_groups as $key => $customer_group)
                    @if($customer_group != null)
                        <tr>
                            <td>{{$customer_group->id}}</td>
                            <td>{{$customer_group->name}}</td>
                            <td>
                                <img src="{{ uploaded_asset($customer_group->avatar) }}" alt="avatar" class="h-50px">
                            </td>
                            <td>{{single_price($customer_group->bonus)}}</td>
                            <td>{{$customer_group->description != null ? $customer_group->description : 'Chưa có mô tả !'}}</td>
                            <td>
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input class="default" value="{{ $customer_group->id }}" type="checkbox" @if($customer_group->default == 1) checked @endif >
                                    <span class="slider round"></span>
                                </label>
                            </td>
                            <td> <label class="aiz-switch aiz-switch-success mb-0">
                                    <input class="hidden" value="{{ $customer_group->id }}" type="checkbox" @if($customer_group->status == 1) checked @endif>
                                    <span class="slider round"></span>
                                </label></td>
                            <td>{{$customer_group->created_at}}</td>
                            <td>{{$customer_group->updated_at}}</td>
                            <td class="text-right">
                                <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                   href="{{route('customer_groups.edit', encrypt($customer_group->id))}}"
                                   title="{{ translate('Edit') }}">
                                    <i class="las la-edit"></i>
                                </a>
                                <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                   data-href="{{route('customer_groups.destroy', $customer_group->id)}}"
                                   title="{{ translate('Delete') }}">
                                    <i class="las la-trash"></i>
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
                if (data == 1) {
                    location.reload();
                } else {
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                    location.reload();
                }
            });
        }

        $(document).on('click', '.hidden', function () {
            const route = '{{ route('customer_groups.setup_hidden') }}';
            setup_hidden($(this), route);
        });

        $(document).on('click', '.default', function () {
            const route = '{{ route('customer_groups.setup_default') }}';
            setup_hidden($(this), route);
        });


    </script>
@endsection
