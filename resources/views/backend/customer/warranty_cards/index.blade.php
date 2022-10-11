@extends('backend.layouts.app')

@section('content')

    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="align-items-center">
            <h1 class="h3">{{translate('Danh sách thẻ bảo hành')}}</h1>
        </div>
    </div>


    <div class="card">
        <form class="" id="sort_customers" action="" method="GET">
            <div class="card-header row gutters-5">
                <div class="col">
                    <h5 class="mb-0 h6">{{translate('Thẻ bảo hành')}}</h5>
                </div>
                <div class="col-md-3">
                    <div class="form-group mb-0">

                        <input type="text" class="form-control" id="search" name="search"
                               @isset($search) value="{{ $search }}"
                               @endisset placeholder="{{ translate('Nhập tên hoặc số seri') }}">
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table aiz-table mb-0">
                        <thead>
                        <tr>
                            <th>{{translate('Tên Hãng')}}</th>
                            <th data-breakpoints="lg">{{translate('Tên khách hàng')}}</th>
                            <th data-breakpoints="lg">{{translate('Địa chỉ')}}</th>
                            <th data-breakpoints="lg">{{translate('Thời gian kích hoạt')}}</th>
                            <th data-breakpoints="lg">{{translate('Seri')}}</th>
                            <th data-breakpoints="lg">{{translate('Ảnh QR Code')}}</th>
                            <th data-breakpoints="lg">{{translate('Ảnh Seri')}}</th>
                            <th data-breakpoints="lg">{{translate('Điểm nhận được')}}</th>
                            <th data-breakpoints="lg">{{translate('Trạng thái')}}</th>
                            <th class="text-right">{{translate('Options')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($warranty_cards as $key => $warranty_card)
                            @if ($warranty_card != null)
                                <tr>
                                    <td>{{$warranty_card->brand->name}}</td>
                                    <td>{{$warranty_card->user_name}}</td>
                                    <td>{{$warranty_card->address}}</td>
                                    <td>{{$warranty_card->seri}}</td>
                                    <td>{{date('d-m-y ',strtotime($warranty_card->active_time))}}</td>
                                    <td><img src="{{ uploaded_asset($warranty_card->qr_code_image) }}" alt="Qr Code "></td>
                                    <td> <img src=" {{uploaded_asset($warranty_card->seri_image)}}" alt="Seri"></td>
                                    <td>{{$warranty_card->point}}</td>
                                    <td>
                                        @if($warranty_card->status == 0)
                                            <span
                                                class="badge badge-inline badge-secondary">{{ trans('Chưa kích hoạt') }}</span>
                                        @else
                                            @if($warranty_card->status == 1)
                                                <span
                                                    class="badge badge-inline badge-success">{{ trans('Hoạt động') }}</span>
                                            @else
                                                <span class="badge badge-inline badge-danger">{{ trans('Khóa') }}</span>
                                            @endif
                                        @endif
                                    </td>

                                    <td class="text-right">
                                     @if($warranty_card->status==0)
                                            <a href="javascript:void(0)"
                                               class="btn btn-soft-info btn-icon btn-circle btn-sm"
                                               onclick="updateCard('{{route('customers.bank', encrypt($warranty_card->id))}}');"
                                               title="{{ translate('Kích hoạt Thẻ') }}">
                                                <i class="las la-credit-card"></i>
                                            </a>
                                         @endif
                                    @if($warranty_card->status==1)
                                             <a href="javascript:void(0)" class="btn btn-soft-danger btn-icon btn-circle btn-sm"
                                                onclick="confirm_ban('{{route('customers.ban', encrypt($warranty_card->id))}}');"
                                                title="{{ translate('Khóa thẻ') }}">
                                                 <i class="las la-user-alt-slash"></i>
                                             </a>
                                        @endif
                                        <a href="{{ route('customers.edit', [encrypt($warranty_card->id)]) }}"
                                           class="btn btn-soft-warning btn-icon btn-circle btn-sm"
                                           title="{{ translate('Cập nhật thông tin thẻ') }}">
                                            <i class="las la-edit"></i>
                                        </a>
                                        <a href="#"
                                           class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                           data-href="{{route('customers.destroy', $warranty_card->id)}}"
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

                {{--                <div class="aiz-pagination">--}}
                {{--                    {{ $warranty_cards->appends(request()->input())->links() }}--}}
                {{--                </div>--}}
            </div>
        </form>
    </div>

@endsection




<div class="modal fade" id="confirm-update-bank">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title h6">{{translate('Confirmation')}}</h5>
                <button type="button" class="close" data-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>{{translate('Bạn muốn xác nhận kích hoạt thẻ không')}}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">{{translate('Hủy')}}</button>
                <a type="button" id="updateCard" class="btn btn-primary">{{translate('Xác nhận')}}</a>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="confirm-ban">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title h6">{{translate('Confirmation')}}</h5>
                <button type="button" class="close" data-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>{{translate('Bạn muốn khóa tài khoản shop?')}}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">{{translate('Cancel')}}</button>
                <a type="button" id="confirmation" class="btn btn-primary">{{translate('Proceed!')}}</a>
            </div>
        </div>
    </div>
</div>


@section('modal')
    @include('modals.delete_modal')
@endsection

@section('script')
    <script type="text/javascript">

        function confirm_ban(url)
        {
            $('#confirm-ban').modal('show', {backdrop: 'static'});
            document.getElementById('confirmation').setAttribute('href' , url);
        }

        function updateCard(url){
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
                    if (response == 1) {
                        location.reload();
                    }
                }
            });
        }
    </script>
@endsection
