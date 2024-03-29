@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
  <div class="row">
      <div class="col-md-6 align-items-center">
          <h1 class="h3">{{translate('Tất cả tài khoản')}}</h1>
      </div>

      <div class="col-md-6 text-md-right">
          <a href="{{ route('customers.create') }}" class="btn btn-circle btn-info">
              <span>{{translate('Thêm người dùng')}}</span>
          </a>
      </div>

  </div>
</div>


<div class="card">
    <form class="" id="sort_customers" action="" method="GET">
        <div class="card-header row gutters-5">
            <div class="col">
                <h5 class="mb-0 h6">{{translate('Tài khoản')}}</h5>
            </div>

           <div class="dropdown mb-2 mb-md-0">
                <button class="btn border dropdown-toggle" type="button" data-toggle="dropdown">
                    {{translate('Bulk Action')}}
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="#" onclick="bulk_delete()">{{translate('Delete selection')}}</a>
                </div>
            </div>
{{--            <div class="col-md-2">--}}
{{--                <div class="form-group mb-0">--}}
{{--                    <select name="referred_by" id="referred_by" class="form-control">--}}
{{--                        <option value="">KOL giới thiệu</option>--}}
{{--                        @foreach($kols as $key => $value)--}}
{{--                            <option value="{{ $key }}" @if(request('referred_by') == $key) selected @endif>{{ $value }}</option>--}}
{{--                        @endforeach--}}
{{--                    </select>--}}
{{--                </div>--}}
{{--            </div>--}}
            <div class="col-md-3">
                <div class="form-group mb-0">
                    <select name="banned" id="banned" class="form-control aiz-selectpicker"
                            data-selected-text-format="count"
                            data-live-search="true"
                    >
                        <option value="-1">Trạng thái tài khoản</option>
                        <option value="1" @if(request('banned', -1) == 1) selected @endif>Khóa</option>
                        <option value="0" @if(request('banned', -1) == 0) selected @endif>Đang hoạt động</option>
{{--                        <option value="0" @if(request('banned', -1) == 0) selected @endif>Chưa kích hoạt</option>--}}
                    </select>
                </div>
            </div>
{{--            <div class="col-md-3">--}}
{{--                <div class="form-group mb-0">--}}
{{--                    <select name="bank_updated" id="bank_updated" class="form-control  aiz-selectpicker"--}}
{{--                            data-selected-text-format="count"--}}
{{--                            data-live-search="true"--}}
{{--                    >--}}
{{--                        <option value="-1">Tình trạng tk ngân hàng</option>--}}
{{--                        <option value="0" @if(request('bank_updated', -1) == 0) selected @endif>Chưa cập nhật</option>--}}
{{--                        <option value="1" @if(request('bank_updated', -1) == 1) selected @endif>Mới cập nhật</option>--}}
{{--                        <option value="2" @if(request('bank_updated', -1) == 2) selected @endif>Đã cập nhật</option>--}}
{{--                    </select>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="col-md-2">--}}
{{--                <div class="form-group mb-0">--}}
{{--                    <select name="has_best_api" id="has_best_api" class="form-control">--}}
{{--                        <option value="0">Trạng thái best api</option>--}}
{{--                        <option value="1" @if(request('has_best_api') == 1) selected @endif>Chưa có tài khoản</option>--}}
{{--                        <option value="2" @if(request('has_best_api') == 2) selected @endif>Đã có tài khoản</option>--}}
{{--                    </select>--}}
{{--                </div>--}}
{{--            </div>--}}
            <div class="col-md-3">
                <div class="form-group mb-0">

                    <input type="text" class="form-control" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="{{ translate('Nhập tên hoặc số điện thoại') }}">
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table aiz-table mb-0">
                    <thead>
                    <tr>
{{--                      <th data-breakpoints="lg">#</th>--}}
                                       <th>
                                                    <div class="form-group">
                                                        <div class="aiz-checkbox-inline">
                                                            <label class="aiz-checkbox">
                                                                <input type="checkbox" class="check-all">
                                                                <span class="aiz-square-check"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </th>
                        <th>{{translate('Name')}}</th>
                        <th data-breakpoints="md">  {{translate('Phone')}}</th>
                        <th data-breakpoints="md">  {{translate('Email')}}</th>
                        <th data-breakpoints="md">  {{translate('Address')}}</th>
                        <th data-breakpoints="md">Thuộc đại lý - Tổng kho</th>
                        <th data-breakpoints="md">{{translate('Status')}}</th>
                        <th data-breakpoints="md">{{translate('Created_at')}}</th>
                        <th data-breakpoints="md">{{translate('Updated_at')}}</th>
                        {{--                        <th data-breakpoints="lg">{{translate('Người cập nhật')}}</th>--}}
                        <th class="text-right">{{translate('Options')}}</th>
                    </tr>
                    </thead>
                    <tbody>


                    @foreach($users as $key => $user)
                        @if ($user != null)
                            <tr>
                                                        <td>
                                    <div class="form-group">
                                        <div class="aiz-checkbox-inline">
                                            <label class="aiz-checkbox">
                                                <input type="checkbox" class="check-one" name="id[]" value="{{$user->id}}">
                                                <span class="aiz-square-check"></span>
                                            </label>
                                        </div>
                                    </div>
                                </td>
                                <td><a title="lịch sử thanh toán"
                                       href="{{route('customers.show',encrypt($user->id))}}">{{$user->name}}</a>
                                </td>
                                <td>
                                   {{$user->phone}}
                                </td>
                                <td>
                                   {{$user->email}}
                                </td>
                                <td>
{{--                                    @if($user->addresses!=null)--}}
{{--                                        @foreach($user->addresses as $address)--}}
                                         {{$user->address}}

{{--                                            - {{$address->province!=null?$address->province->name:''}}---}}
{{--                                            {{$address->district!=null?$address->district->name:''}}---}}
{{--                                            {{$address->ward!=null?$address->ward->name:''}}--}}
{{--                                        @endforeach--}}
{{--                                    @endif--}}
                                </td>

                                <td>
                                    @if($user->user_agent!=null)
                                   {{$user-> user_agent->name}}
                               @endif
                                </td>
{{--                                <td>--}}
{{--                                    @if($user->customer_bank)--}}
{{--                                        <span @if($user->bank_updated == 2) style="color: green" @endif>--}}
{{--                                            STK : {{ $user->customer_bank->number }}--}}
{{--                                        </span>--}}
{{--                                        <br>--}}
{{--                                        <span @if($user->bank_updated == 2) style="color: green" @endif>--}}
{{--                                            Chủ tài khoản : {{ $user->customer_bank->username }}--}}
{{--                                        </span>--}}
{{--                                        <br>--}}
{{--                                        <span @if($user->bank_updated == 2) style="color: green" @endif>--}}
{{--                                            Ngân hàng : {{ $user->customer_bank->name }}--}}
{{--                                        </span>--}}
{{--                                    @else--}}
{{--                                        <span style="color: #ff0000">Chưa có tài khoản</span>--}}
{{--                                    @endif--}}
{{--                                </td>--}}
{{--                                <td>--}}
{{--                                    @if($user->best_api_user)--}}
{{--                                        <span style="color: green" >--}}
{{--                                        TK : {{ $user->best_api_user }}--}}
{{--                                        </span>--}}
{{--                                        <br>--}}
{{--                                        <span style="color: green" >--}}
{{--                                        MK : {{ $user->best_api_password }}--}}
{{--                                        </span>--}}
{{--                                    @else--}}
{{--                                        <span style="color: red">Chưa có tài khoản</span>--}}
{{--                                    @endif--}}
                                {{--                                </td>--}}
                                <td>
                                    @if($user->banned == 1)
                                        <span class="badge badge-inline badge-danger">{{ trans('Khóa') }}</span>

                                    @else
                                        <span class="badge badge-inline badge-success">{{ trans('Hoạt động') }}</span>
                                    @endif
                                </td>
                                {{--                                <td>{{$kols[$user->referred_by] ?? ''}}</td>--}}
                                {{--                                <td>--}}
                                {{--                                    @if ($user->customer_package != null)--}}
                                {{--                                        {{$user->customer_package->getTranslation('name')}}--}}
                                {{--                                    @endif--}}
                                {{--                                </td>--}}
                                <td>{{ $user->created_at }}</td>
                                <td>{{ $user->updated_at }}</td>
                                {{--                                <td>{{ $user->user_updated->name ?? ''}}</td>--}}
                                <td class="text-right">
                                {{--                                    @if($user->bank_updated == 1)--}}
                                {{--                                        <a href="javascript:void(0)" class="btn btn-soft-info btn-icon btn-circle btn-sm" onclick="updateBank('{{route('customers.bank', encrypt($user->id))}}');" title="{{ translate('Xác nhận cập nhật tài khoản ngân hàng bên Best') }}">--}}
                                {{--                                            <i class="las la-credit-card"></i>--}}
                                {{--                                        </a>--}}
                                {{--                                    @endif--}}
                                <!--                                    onclick="openUpdatePackage(`{{ $user->id }}`, `{{ $user->customer_package_id }}`)"-->
                                    <a href="{{route('wallet-balance.balance',encrypt($user->id))}}" class="btn btn-soft-success btn-icon btn-circle btn-sm" title="Lịch sử giao dịch" >
                                        <i class="las la-money-bill"></i>
                                    </a>
                                    <a href="{{ route('customers.edit', [encrypt($user->id)]) }}"
                                       class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                       title="{{ translate('Update account') }}">
                                        <i class="las la-edit"></i>
                                    </a>

                                    @if($user->banned == 0)
                                        <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm"
                                           onclick="confirm_ban('{{route('customers.ban', encrypt($user->id))}}');"
                                           title="{{ translate('Lock account') }}">
                                            <i class="las la-user-slash"></i>
                                        </a>
                                    @else
                                        <a href="#" class="btn btn-soft-success btn-icon btn-circle btn-sm"
                                           onclick="confirm_unban('{{route('customers.ban', encrypt($user->id))}}');"
                                           title="{{ translate('Active account') }}">
                                            <i class="las la-user-check"></i>
                                        </a>
                                @endif

                                  <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('customers.destroy', $user->id)}}" title="{{ translate('Delete') }}">
                                        <i class="las la-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="aiz-pagination">
                {{ $users->appends(request()->input())->links() }}
            </div>
        </div>
    </form>
</div>

{{--<div class="modal fade" id="update-package" data-backdrop="static">--}}
{{--    <div class="modal-dialog">--}}
{{--        <div class="modal-content">--}}
{{--            <div class="modal-header">--}}
{{--                <h5 class="modal-title h6">{{translate('Update package')}}</h5>--}}
{{--                <button type="button" class="close" data-dismiss="modal"></button>--}}
{{--            </div>--}}
{{--            <div class="modal-body">--}}
{{--                <div class="form-group">--}}
{{--                    <label for="">{{ trans('Package') }}</label>--}}
{{--                    <select name="package_id" id="" class="form-control">--}}
{{--                        @foreach($packages as $package)--}}
{{--                        <option value="{{ $package->id }}">{{ $package->name }}</option>--}}
{{--                        @endforeach--}}
{{--                    </select>--}}
{{--                    <input type="hidden" name="user_id">--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="modal-footer">--}}
{{--                <button type="button" class="btn btn-light" data-dismiss="modal">{{translate('Cancel')}}</button>--}}
{{--                <a type="button" id="updatePackage" class="btn btn-primary">{{translate('Save')}}</a>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}

{{--<div class="modal fade" id="confirm-update-bank">--}}
{{--    <div class="modal-dialog">--}}
{{--        <div class="modal-content">--}}
{{--            <div class="modal-header">--}}
{{--                <h5 class="modal-title h6">{{translate('Confirmation')}}</h5>--}}
{{--                <button type="button" class="close" data-dismiss="modal"></button>--}}
{{--            </div>--}}
{{--            <div class="modal-body">--}}
{{--                <p>{{translate('Bạn muốn xác nhận đã cập nhật tài khoản ngân hàng cho shop bên Best')}}</p>--}}
{{--            </div>--}}
{{--            <div class="modal-footer">--}}
{{--                <button type="button" class="btn btn-light" data-dismiss="modal">{{translate('Hủy')}}</button>--}}
{{--                <a type="button" id="updateBank" class="btn btn-primary">{{translate('Xác nhận')}}</a>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}

<div class="modal fade" id="confirm-ban">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title h6">{{translate('Xác nhận')}}</h5>
                <button type="button" class="close" data-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>{{translate('Bạn muốn khóa tài khoản ?')}}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">{{translate('Hủy')}}</button>
                <a type="button" id="confirmation" class="btn btn-primary">{{translate('Tiếp tục')}}</a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="confirm-unban">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title h6">{{translate('Xác nhận')}}</h5>
                <button type="button" class="close" data-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>{{translate('Bạn muốn kích hoạt tài khoản ?')}}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">{{translate('Hủy')}}</button>
                <a type="button" id="confirmationunban" class="btn btn-primary">{{translate('Tiếp tục')}}</a>
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
        $('#banned').on('change',function (){
            $('#sort_customers').submit();
        })
        $('#has_best_api').on('change',function (){
            $('#sort_customers').submit();
        })
        $('#bank_updated').on('change',function (){
            $('#sort_customers').submit();
        })

        $('#search').change(function () {
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

        function updateBank(url){
            $('#confirm-update-bank').modal('show', {backdrop: 'static'});
            document.getElementById('updateBank').setAttribute('href' , url);
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
