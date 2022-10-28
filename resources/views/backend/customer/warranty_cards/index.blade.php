@extends('backend.layouts.app')

@section('content')

    <div class="aiz-titlebar text-left mt-2 mb-3 row">
        <div class=" col-md-6 align-items-center">
            <h1 class="h3">{{translate('Danh sách thẻ bảo hành')}}</h1>
        </div>
        {{--        <div class="col-md-6 text-md-right">--}}
        {{--            <a href="{{route('warranty_card.create')}}" class="btn btn-circle btn-info">--}}
        {{--                <span>Add New Card</span>--}}
        {{--            </a>--}}
        {{--        </div>--}}
    </div>

    <div class="card">
        <form class="" id="sort_Card" action="" method="GET">
            <div class="card-header row gutters-5">
                <div class="col">
                    <h5 class="mb-0 h6">{{translate('Thẻ bảo hành')}}</h5>
                </div>


                <div class="col-md-3">
                    <div class="form-group mb-0">
                        <select name="sort_status" id="sort_selectCart" class="form-control">
                            <option value="-1">Trạng thái của thẻ....</option>
                            <option value="0"
                                    @if(request('sort_status',-1)==0) selected @endif>{{translate('Chưa xét duyệt')}}</option>
                            <option value="1"
                                    @if(request('sort_status',-1)==1) selected @endif>{{translate('Đã xét duyệt')}}</option>
                            <option value="2"
                                    @if(request('sort_status',-1)==2) selected @endif>{{translate('Hủy')}}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group mb-0">

                        <input type="text" class="form-control" id="search" name="search"
                               @isset($search) value="{{ $search }}"
                               @endisset placeholder="{{ translate('Nhập tên khách hàng hoặc số sê-ri') }}">
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table aiz-table mb-0">
                        <thead>
                        <tr>
                            <th>{{translate('Tên nhãn hiệu')}}</th>
                            <th data-breakpoints="lg">{{translate('Tên khách hàng')}}</th>
                            <th data-breakpoints="lg">{{translate('Địa chỉ')}}</th>
                            <th data-breakpoints="lg">{{translate('Seri')}}</th>
                            <th data-breakpoints="lg">{{translate('Thời gian kích hoạt')}}</th>
                            <th data-breakpoints="lg">{{translate('Ảnh đại dện')}}</th>
                            <th data-breakpoints="lg">{{translate('Trạng thái')}}</th>
                            <th class="text-right">{{translate('Tùy chọn')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($warranty_cards as $key => $warranty_card)
                            @if ($warranty_card != null)
                                <tr>
                                    <td>
                                        <h7 class="text-danger">{{$warranty_card->brand->name}} </h7>
                                    </td>
                                    <td>{{$warranty_card->user_name}}</td>
                                    <td>{{$warranty_card->address}}</td>
                                    <td>{{$warranty_card->seri}}</td>
                                    <td> @if($warranty_card->active_time>0)
                                            {{date('d/m/Y H:i:s ',strtotime($warranty_card->active_time))}}
                                        @else
                                            <span
                                                class="badge badge-inline badge-secondary">{{ trans('Not activated') }}</span>
                                        @endif
                                    </td>
                                    <td>

                                        @foreach($warranty_card->uploads as $img)
                                            <a href="javascript:;"  class="a-exampleModal">
                                                <img class="h-50px image"
                                                     src="{{ get_image_asset($img->id,$img->object_id) }}" alt="image">
                                            </a>
                                        @endforeach
                                    </td>

                                    <td>
                                        @if($warranty_card->status == 0)
                                            <span
                                                class="badge badge-inline badge-secondary">{{ translate('Chưa xét duyệt') }}</span>
                                        @else
                                            @if($warranty_card->status == 1)
                                                <span
                                                    class="badge badge-inline badge-success">{{ translate('Đã xét duyệt') }}</span>
                                            @else
                                                <span
                                                    class="badge badge-inline badge-danger">{{ translate('Hủy') }}</span>
                                            @endif
                                        @endif
                                    </td>


                                    <td class="text-right">


                                        <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                           href="{{ route('warranty_card.show',[encrypt($warranty_card->id)]) }}"
                                           title="View">
                                            <i class="las la-eye"></i>
                                        </a>
                                        @if($warranty_card->status==0 )
                                            <a href="javascript:void(0)"
                                               class="btn btn-soft-info btn-icon btn-circle btn-sm"
                                               onclick="updateCard('{{route('warranty_card.ban', encrypt($warranty_card->id))}}',1);"
                                               title="{{ translate('Kích hoạt thẻ') }}">
                                                <i class="las la-credit-card"></i>
                                            </a>


                                            <a href="javascript:void(0)"
                                               class="btn btn-soft-danger btn-icon btn-circle btn-sm"
                                               onclick="confirm_ban('{{route('warranty_card.ban', encrypt($warranty_card->id))}}' ,2);"
                                               title="{{ translate('Hủy thẻ') }}">
                                                <i class="las la-user-alt-slash"></i>
                                            </a>
                                        @endif
                                        {{--                                        <a href="{{ route('warranty_card.edit', [ encrypt($warranty_card->id) ]) }}"--}}
                                        {{--                                           class="btn btn-soft-warning btn-icon btn-circle btn-sm"--}}
                                        {{--                                           title="{{ translate('Cập nhật thông tin thẻ') }}">--}}
                                        {{--                                            <i class="las la-edit"></i>--}}
                                        {{--                                        </a>--}}
                                        <a href="#"
                                           class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                           data-href="{{route('warranty_card.destroy', encrypt($warranty_card->id))}}"
                                           title="{{ translate('Xóa') }}">
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

    <div class="modal fade" id="modal-image">
        <div class="modal-dialog">
            <img id="image" style="width: 100%; height: 100% ;object-fit: contain" src="" alt="">
        </div>
    </div>

    <div class="modal fade" id="confirm-update-bank">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h6">{{translate('Xác nhận kích hoạt thẻ')}}</h5>
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
        <form action="" id="confirmation" method="GET">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title h6">{{translate('Nhập lý do hủy')}}</h5>
                        <button type="button" class="close" data-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="text" class="form-control" name="reason" placeholder="Lý do hủy">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light"
                                data-dismiss="modal">{{translate('Hủy')}}</button>
                        <button type="submit" class="btn btn-primary">{{translate('Tiếp tục')}}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>


@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection

@section('script')
    <script type="text/javascript">
        $(document).on('click','.a-exampleModal', function () {
            console.log(  $('#modal-image'))
            $('#modal-image').modal('show', {backdrop: 'static'})
        })


        $(document).on('click', '.image', function () {
            let img = $(this).attr('src');
            $('#image').attr('src', img)
        })


        $('#sort_selectCart').on('change', function () {
            $('#sort_Card').submit();
        })

        function confirm_ban(url, status) {
            $('#confirm-ban').modal('show', {backdrop: 'static'});
            document.getElementById('confirmation').setAttribute('action', url + '?status=' + status);
        }

        function updateCard(url, status) {
            $('#confirm-update-bank').modal('show', {backdrop: 'static'});
            document.getElementById('updateCard').setAttribute('href', url + '?status=' + status);
        }


        function bulk_delete() {
            var data = new FormData($('#sort_Card')[0]);
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
