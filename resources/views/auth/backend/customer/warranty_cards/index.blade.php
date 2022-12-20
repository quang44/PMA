@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3 row">
        <div class=" col-md-6 align-items-center">
            <h1 class="h3">{{translate('List of Warranty')}}</h1>
        </div>
        <div class="col-md-6 text-md-right">
            <a href="{{route('warranty_card.create')}}" class="btn btn-circle btn-info">
                <span>{{translate('Add new card')}}</span>
            </a>
        </div>
    </div>

    <div class="card">
        <form class="" id="sort_Card" action="" method="GET">
            <div class="card-header row gutters-5">
                <div class="col">
                    <h5 class="mb-0 h6">{{translate('List of Warranty')}}</h5>
                </div>


                <div class="col-md-3">
                    <div class="form-group mb-0">
                        <select name="sort_status" id="sort_selectCart" class="form-control aiz-selectpicker"
                                data-selected-text-format="count"
                                data-live-search="true"
                        >
                            <option value="-1">Trạng thái của thẻ....</option>
                            <option value="0"
                                    @if(request('sort_status',-1)==0) selected @endif>
                                {{\App\Utility\WarrantyCardUtility::$aryStatus[\App\Utility\WarrantyCardUtility::STATUS_NEW]}}
                            </option>
                            <option value="1"
                                    @if(request('sort_status',-1)==1) selected @endif>
                                {{\App\Utility\WarrantyCardUtility::$aryStatus[\App\Utility\WarrantyCardUtility::STATUS_SUCCESS]}}
                            </option>
                            <option value="2"
                                    @if(request('sort_status',-1)==2) selected @endif>
                                {{\App\Utility\WarrantyCardUtility::$aryStatus[\App\Utility\WarrantyCardUtility::STATUS_CANCEL]}}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group mb-0">

                        <input type="text" class="form-control aiz-selectpicker " id="search" name="search"
                               @isset($search) value="{{ $search }}"
                               @endisset placeholder="{{ translate('enter customer name or phone number or address') }}">
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table aiz-table mb-0">
                        <thead>
                        <tr>
                            <th>{{translate('Product')}} </th>
                            <th data-breakpoints="lg">{{translate('Color')}}</th>
                            <th data-breakpoints="lg">{{translate('Image')}}</th>
                            <th data-breakpoints="lg">{{translate('Customer info')}}</th>
                            <th data-breakpoints="lg">{{translate('Active time')}}</th>
                            <th data-breakpoints="lg">{{translate('Accept by')}}</th>
                            <th data-breakpoints="lg">{{translate('Status')}}</th>
                            <th class="text-right">{{translate('Options')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($warranty_cards as $key => $warranty_card)
                            @if ($warranty_card != null)
                                <tr>
                                    @foreach($warranty_card->cardDetail as $k=> $detail)
                                    <td>
                                        <h7 class="text-danger">{{$detail->product->name}} </h7>
                                    </td>
                                    <td>
                                    <span class='size-25px d-inline-block mr-2 rounded border'
                                    style='background:{{$detail->color}}'></span>
                                    </td>
                                    <td>
                                        <div class="row no-gutters " id="gallery{{$k}}">
                                            <div class="col-lg-4">
                                                <a class="a-key" style="position: relative;width: 100%; height: 100%;"
                                                   data-key="{{$k}}"
                                                   href="{{ uploaded_asset($detail->image) }}">
                                                    <img class="h-60px image"
                                                         src="{{ uploaded_asset($detail->image) }}"
                                                         alt="image">
                                                </a>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        {{translate('Customer')}} : {{ strtoupper($warranty_card->user_name)}} <br>
                                        {{translate('Address')}} : {{ strtoupper($warranty_card->address)}} <br>
                                        {{translate('phone')}} : {{ strtoupper($warranty_card->phone)}}

                                    </td>
                                    <td> @if($warranty_card->active_time>0)
                                            {{date('d-m-Y H:i:s ',strtotime($warranty_card->active_time))}}
                                        @else
                                            --
                                        @endif
                                    </td>

                                    <td>
                                        @if($warranty_card->accept_by!=null)
                @if($warranty_card->active_user_id!=null && $warranty_card->active_user_id->user_type='admin')
                                                <span class="badge badge-inline badge-success">Admin</span>
                                            @else
                                                <span class="badge badge-inline badge-success">CTV</span>
                                            @endif
                                        @endif
                                    </td>

                                    <td>
                                    @if($warranty_card->status == 0)
                                        <span class="badge badge-inline badge-secondary">
                                                {{\App\Utility\WarrantyCardUtility::$aryStatus[\App\Utility\WarrantyCardUtility::STATUS_NEW]}}</span>
                                    @else
                                        @if($warranty_card->status == 1)
                                            <span class="badge badge-inline badge-success">
                                                    {{\App\Utility\WarrantyCardUtility::$aryStatus[\App\Utility\WarrantyCardUtility::STATUS_SUCCESS]}}
</span>
                                            @else
                                                <span class="badge badge-inline badge-danger">
                                                    {{\App\Utility\WarrantyCardUtility::$aryStatus[\App\Utility\WarrantyCardUtility::STATUS_CANCEL]}}
</span>
                                            @endif
                                        @endif
                                    </td>


                                    <td class="text-right">
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
                                                <i class="las la-credit-card"></i>
                                            </a>

                                            <a href="{{ route('warranty_card.edit', [ encrypt($warranty_card->id) ]) }}"
                                               class="btn btn-soft-warning btn-icon btn-circle btn-sm"
                                               title="{{ translate('Cập nhật thông tin thẻ') }}">
                                                <i class="las la-edit"></i>
                                            </a>
                                        @endif
                                            <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                               href="{{ route('warranty_card.show',[encrypt($warranty_card->id)]) }}"
                                               title="View">
                                                <i class="las la-eye"></i>
                                            </a>

                                        {{--                                        @if($warranty_card->status==\App\Utility\WarrantyCardUtility::$aryStatus[\App\Utility\WarrantyCardUtility::STATUS_CANCEL])--}}
                                        {{--                                            <a href="#"--}}
                                        {{--                                               class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"--}}
                                        {{--                                               data-href="{{route('warranty_card.destroy', encrypt($warranty_card->id))}}"--}}
                                        {{--                                               title="{{ translate('Xóa') }}">--}}
                                        {{--                                                <i class="las la-trash"></i>--}}
                                        {{--                                            </a>--}}
                                        {{--                                            @endif--}}
                                    </td>
                                    @endforeach

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



@section('modal')
    @include('modals.confirm_modal')
@endsection


@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection

@section('script')
    <script type="text/javascript">

        window.onload = function () {
            $(document).on('focus', '.a-key', function (e) {
                let key = $(this).attr('data-key')
                $(`div#gallery` + key).magnificPopup({
                    delegate: 'a',
                    type: 'image',
                    gallery: {
                        enabled: true
                    }
                })
            })
        }

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
