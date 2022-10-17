@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{translate('Danh sách cộng tác viên')}}</h1>
        </div>
        <div class="col-md-6 text-md-right">
            <a href="{{ route('affiliate.kol.create') }}" class="btn btn-circle btn-info">
                <span>{{translate('Tạo tài khoản')}}</span>
            </a>
        </div>
    </div>
</div>


<div class="card">
    <form class="" id="sort_customers" action="" method="GET">
        <div class="card-header row gutters-5">
            <div class="col">
                <h5 class="mb-0 h6">{{translate('Tài khoản cộng tác viên')}}</h5>
            </div>

<!--            <div class="dropdown mb-2 mb-md-0">
                <button class="btn border dropdown-toggle" type="button" data-toggle="dropdown">
                    {{translate('Bulk Action')}}
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="#" onclick="bulk_delete()">{{translate('Delete selection')}}</a>
                </div>
            </div>-->
            <div class="mb-2 mb-md-0">
                <div class="form-group mb-0">
                    <select name="referred_by" id="referred_by" class="form-control">
                        <option value="">Vui lòng chọn</option>
                        @foreach($employee as $key => $value)
                            <option value="{{ $key }}" @if(request('referred_by') == $key) selected @endif>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group mb-0">
                    <select name="banned" id="banned" class="form-control">
                        <option value="-1">Trạng thái tài khoản</option>
                        <option value="1" @if(request('banned', -1) == 1) selected @endif>Khóa</option>
                        <option value="0" @if(request('banned', -1) == 0) selected @endif>Đang hoạt động</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group mb-0">
                    <input type="text" class="form-control" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="{{ translate('Nhập tên hoặc số điện thoại') }}">
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
                        <th>{{translate('Tên')}}</th>
                        <th data-breakpoints="lg">{{translate('Email')}}</th>
                        <th data-breakpoints="lg">{{translate('Số điện thoại')}}</th>
                        <th data-breakpoints="lg">{{translate('Nhân viên phụ trách')}}</th>
                        <th data-breakpoints="lg">{{translate('Mã giới thiệu')}}</th>
                        <th data-breakpoints="lg" class="text-right">Số dư</th>
                        <th data-breakpoints="lg" class="text-center">Trạng thái</th>
                        <th class="text-right">{{translate('Options')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $key => $user)
                        @if ($user != null)
                            <tr>
                                <td>{{ ($key+1) + ($users->currentPage() - 1)*$users->perPage() }}</td>
<!--                                <td>
                                    <div class="form-group">
                                        <div class="aiz-checkbox-inline">
                                            <label class="aiz-checkbox">
                                                <input type="checkbox" class="check-one" name="id[]" value="{{$user->id}}">
                                                <span class="aiz-square-check"></span>
                                            </label>
                                        </div>
                                    </div>
                                </td>-->

                                <td>@if($user->banned == 1) <i class="fa fa-ban text-danger" aria-hidden="true"></i> @endif {{$user->name}}</td>
                                <td>{{$user->email}}</td>
                                <td><a href="{{ route('customers.index', ['referred_by' => $user->id])}}">{{$user->phone}}</a></td>
                                <td>{{$employee[$user->referred_by] ?? ''}}</td>

                                <td>
                                    {{ $user->referral_code }}
                                </td>
                                <td class="text-right">
                                    {{ single_price($user->balance) }}
                                </td>
                                <td class="text-center">
                                    @if($user->banned != 1)
                                        <span class="badge badge-inline badge-success">{{ trans('Hoạt động') }}</span>
                                    @else
                                        <span class="badge badge-inline badge-danger">{{ trans('Chưa kích hoạt') }}</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('affiliate.kol.edit', encrypt($user->id))}}" title="{{ translate('Sửa thông tin') }}">
                                        <i class="las la-edit"></i>
                                    </a>
                                    @if($user->banned != 1)
                                        <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm" onclick="confirm_ban('{{route('customers.ban', encrypt($user->id))}}');" title="{{ translate('Khóa tài khoản') }}">
                                            <i class="las la-user-slash"></i>
                                        </a>
                                    @else
                                        <a href="#" class="btn btn-soft-success btn-icon btn-circle btn-sm" onclick="confirm_unban('{{route('customers.ban', encrypt($user->id))}}');" title="{{ translate('Kích hoạt tài khoản') }}">
                                            <i class="las la-user-check"></i>
                                        </a>
                                    @endif
<!--                                    <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('affiliate.kol.destroy', $user->id)}}" title="{{ translate('Xóa tài khoản') }}">
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


<div class="modal fade" id="confirm-ban">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title h6">{{translate('Confirmation')}}</h5>
                <button type="button" class="close" data-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>{{translate('Bạn muốn khóa tài khoản cộng tác viên?')}}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">{{translate('Cancel')}}</button>
                <a type="button" id="confirmation" class="btn btn-primary">{{translate('Proceed!')}}</a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="confirm-unban">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title h6">{{translate('Confirmation')}}</h5>
                <button type="button" class="close" data-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>{{translate('Bạn muốn kích hoạt tài khoản cho cộng tác viên?')}}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">{{translate('Cancel')}}</button>
                <a type="button" id="confirmationunban" class="btn btn-primary">{{translate('Proceed!')}}</a>
            </div>
        </div>
    </div>
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

        $('#referred_by').on('change',function (){
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
        $('#banned').on('change',function (){
            $('#sort_customers').submit();
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
