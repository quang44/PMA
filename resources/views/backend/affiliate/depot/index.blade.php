@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{translate('List of depot')}}</h1>
        </div>
        <div class="col-md-6 text-md-right">
            <a href="{{ route('affiliate.depot.create') }}" class="btn btn-circle btn-info">
                <span>{{translate('Create Account')}}</span>
            </a>
        </div>
    </div>
</div>


<div class="card">
    <form class="" id="sort_customers" action="" method="GET">
        <div class="card-header row gutters-5">
            <div class="col">
                <h5 class="mb-0 h6">{{translate('Account depot')}}</h5>
            </div>

<!--            <div class="dropdown mb-2 mb-md-0">
                <button class="btn border dropdown-toggle" type="button" data-toggle="dropdown">
                    {{translate('Bulk Action')}}
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="#" onclick="bulk_delete()">{{translate('Delete selection')}}</a>
                </div>
            </div>-->
            <div class="col-md-3">
                <div class="form-group mb-0">
                    <select name="banned" id="banned" class="form-control aiz-selectpicker "
                            data-selected-text-format="count"
                            data-live-search="true"
                    >
                        <option value="-1">Trạng thái tài khoản</option>
                        <option value="1" @if(request('banned', -1) == 1) selected @endif>Khóa</option>
                        <option value="0" @if(request('banned', -1) == 0) selected @endif>Đang hoạt động</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group mb-0">
                    <input type="text" class="form-control" id="search" name="search" @isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="{{ translate('Nhập tên hoặc số điện thoại') }}" >
                </div>
            </div>
        </div>

        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <!--<th data-breakpoints="lg">#</th>-->
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
                        <th>#</th>
                        <th>{{translate('Tên Tổng kho')}}</th>
                        <th data-breakpoints="md">{{translate('Email')}}</th>
                        <th data-breakpoints="md">{{translate('Phone')}}</th>
                        <th data-breakpoints="md" >{{translate('Address')}}</th>
                        <th data-breakpoints="md" class="text-center">{{translate('Status')}}</th>
                        <th class="text-right">{{translate('Options')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $key => $user)
                        @if ($user != null)
                            <tr>
                                <td>{{ ($key+1) + ($users->currentPage() - 1)*$users->perPage() }}</td>
{{--                             <td>--}}
{{--                                    <div class="form-group">--}}
{{--                                        <div class="aiz-checkbox-inline">--}}
{{--                                            <label class="aiz-checkbox">--}}
{{--                                                <input type="checkbox" class="check-one" name="id[]" value="{{$user->id}}">--}}
{{--                                                <span class="aiz-square-check"></span>--}}
{{--                                            </label>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </td>--}}
                                <td>@if($user->banned == 1) <i class="fa fa-ban text-danger" aria-hidden="true"></i> @endif {{$user->name}}</td>
                                <td>@if($user->email!=null) {{$user->email}} @else  <span class="text-danger">Chưa có email</span> @endif</td>
                                <td>{{$user->phone}}</td>
{{--                                <td>--}}
{{--                                    {{ $user->referral_code }}--}}
{{--                                </td>--}}
{{--                                <td class="text-right">--}}
{{--                                    {{ single_price($user->balance) }}--}}
{{--                                </td>--}}
                                <td class="text-left">
                                       {{$user->address_one!=null?$user->address_one->province->name:''}}
                                        - {{$user->address_one!=null?$user->address_one->district->name:''}}
                                        - {{$user->address_one!=null ?$user->address_one->ward->name:''}}
                                </td>

                                <td class="text-center">
                                    @if($user->user_type=='customer' && $user->status ==1)
                                        <span class="badge badge-inline badge-warning">Chờ duyệt</span>
                               @else
                                    @if($user->banned != 1)
                                        <span class="badge badge-inline badge-success">{{ trans('Hoạt động') }}</span>
                                    @else
                                        <span class="badge badge-inline badge-danger">{{ trans('Chưa kích hoạt') }}</span>
                                    @endif
                                    @endif
                                </td>
                                <td class="text-right">
                                    <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('affiliate.depot.edit', encrypt($user->id))}}" title="{{ translate('Sửa thông tin') }}">
                                        <i class="las la-edit"></i>
                                    </a>
                                    @if($user->status ==1)
                                        <a href="#" class="btn btn-soft-success btn-icon btn-circle btn-sm" onclick="confirm_lever_up('{{route('affiliate.employee.updateToDepot', encrypt($user->id))}}');" title="{{ translate('Approve') }}">
                                            <i class="las la-level-up-alt"></i>
                                        </a>
                                    @else
{{--                                    @if($user->banned != 1)--}}
{{--                                        <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm" onclick="confirm_ban('{{route('customers.ban', encrypt($user->id))}}');" title="{{ translate('Khóa tài khoản') }}">--}}
{{--                                            <i class="las la-user-slash"></i>--}}
{{--                                        </a>--}}
{{--                                    @else--}}
{{--                                        <a href="#" class="btn btn-soft-success btn-icon btn-circle btn-sm" onclick="confirm_unban('{{route('customers.ban', encrypt($user->id))}}');" title="{{ translate('Kích hoạt tài khoản') }}">--}}
{{--                                            <i class="las la-user-check"></i>--}}
{{--                                        </a>--}}
{{--                                    @endif--}}
                                @endif
<!--                                    <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('affiliate.employee.destroy', $user->id)}}" title="{{ translate('Xóa tài khoản') }}">
                                        <i class="las la-trash"></i>
                                    </a>-->
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
                {{ $users->appends(request()->input())->links() }}
            </div>
        </div>
    </form>
</div>
@endsection

@section('modal')
    @include('modals.confirm_banned_modal')
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

        function confirm_lever_up(url)
        {
            $('#confirm-leverup').modal('show', {backdrop: 'static'});
            document.getElementById('confirmationleverup').setAttribute('href' , url);
        }

        $('#banned').on('change',function (){
            $('#sort_customers').submit();
        })

        $('#search').on('change',function () {
            $('#sort_customers').submit();
        })


        function sort_customers(el){
            $('#sort_customers').submit();
        }

        function openUpdatePackage(user_id, package_id)
        {
            if(package_id){
                $('#update-package').find('select[name="package_id"]').val(package_id);
            }
            $('#update-package').find('input[name="user_id"]').val(user_id);
            $('#update-package').modal('show');
        }

        $('#updatePackage').on('click',function (){
            let user_id =  $('#update-package').find('input[name="user_id"]').val();
            let package_id =  $('#update-package').find('select[name="package_id"]').val();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('customers.update_package')}}",
                type: 'POST',
                data: {
                    user_id:user_id,
                    package_id:package_id
                },
                success: function (response) {
                    if(response.result === true) {
                        location.reload();
                    }
                }
            });
        })

        function confirm_ban(url)
        {
            $('#confirm-ban').modal('show', {backdrop: 'static'});
            document.getElementById('confirmation').setAttribute('href' , url);
        }

        function confirm_unban(url)
        {
            $('#confirm-unban').modal('show', {backdrop: 'static'});
            document.getElementById('confirmationunban').setAttribute('href' , url);
        }

        function bulk_delete() {
            var data = new FormData($('#sort_customers')[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('bulk-customer-delete')}}",
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
