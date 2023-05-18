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
                <div class="dropdown mb-2 mb-md-0">
                    <button class="btn border dropdown-toggle" type="button" data-toggle="dropdown">
                        {{translate('Bulk Action')}}
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="#" onclick="bulk_delete()"> {{translate('Delete selection')}}</a>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group mb-0">
                        <select name="sort_customer" id="sort_selectCart" class="form-control aiz-selectpicker"
                                data-selected-text-format="count"
                                data-live-search="true"
                        >
                            <option value="-1">Người tạo</option>
                            @foreach($customers as $customer)
                                <option  @if(request('sort_customer',-1)==$customer->id) selected @endif  value="{{$customer->id}}">{{$customer->name}}</option>
                                @endforeach
                        </select>
                    </div>
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
                            <th data-breakpoints="lg"><div class="form-group">
                                    <div class="aiz-checkbox-inline">
                                        <label class="aiz-checkbox">
                                            <input type="checkbox" class="check-all">
                                            <span class="aiz-square-check"></span>
                                        </label>
                                    </div>
                                </div></th>
                            <th data-breakpoints="md">Người bảo hành</th>
                            <th data-breakpoints="md">{{translate('Customer info')}}</th>
                            <th data-breakpoints="md">Cửa bảo hành</th>
                            <th data-breakpoints="md">{{translate('Warranty code')}}</th>
                            <th data-breakpoints="md">{{translate('Created_at')}}</th>
                            <th data-breakpoints="md">{{translate('Active time')}} / Hủy</th>
                            <th data-breakpoints="md">Người xác nhận</th>
                            <th data-breakpoints="md">{{translate('Status')}}</th>
                            <th class="text-right">{{translate('Options')}}</th>
                        </tr>

                        </thead>
                        <tbody>
                        @foreach($warranty_cards as $key => $warranty_card)
                            @if ($warranty_card != null)
                                <tr>
                                    <td>
                                        <div class="form-group d-inline-block">
                                            <label class="aiz-checkbox">
                                                <input type="checkbox" class="check-one" name="id[]"
                                                       value="{{$warranty_card->id}}">
                                                <span class="aiz-square-check"></span>
                                            </label>
                                        </div>
                                    </td>

                                    <td>
                                        @if($warranty_card->user)
                                            {{$warranty_card->user->name}}
                                        @else
                                            người dùng không tồn tại
                                            @endif
                                        </td>
                                    <td>
                                        {{translate('Customer')}} : {{ ucfirst($warranty_card->user_name)}} <br>
                                        {{translate('Address')}} : {{$warranty_card->address?ucfirst($warranty_card->address):null}}, {{$warranty_card->ward? ucfirst($warranty_card->ward->name):null}}, {{$warranty_card->district? ucfirst($warranty_card->district->name):null}}, {{ $warranty_card->province?ucfirst($warranty_card->province->name):null}} <br>
                                        {{translate('phone')}} : {{ ucfirst($warranty_card->phone)}}

                                    </td>
                                    <td>
                                        @foreach($warranty_card->cardDetail as $pr)
                                           - {{!$pr->product?'not found':$pr->product->name}} <br>
                                        @endforeach
                                    </td>
                                    <td>
                                        {{$warranty_card->warranty_code}}
                                    </td>
                                    <td>
                                        {{convertTime($warranty_card->create_time)}}
                                    </td>
                                    <td> @if($warranty_card->active_time>0)
                                            {{convertTime($warranty_card->active_time)}}
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
                                                    {{\App\Utility\WarrantyCardUtility::$aryStatus[\App\Utility\WarrantyCardUtility::STATUS_SUCCESS]}}</span>
                                            @else
                                                <span class="badge badge-inline badge-danger">
                                                    {{\App\Utility\WarrantyCardUtility::$aryStatus[\App\Utility\WarrantyCardUtility::STATUS_CANCEL]}}</span>
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
                                        @endif
                                            <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                               href="{{ route('warranty_card.show',[encrypt($warranty_card->id)]) }}"
                                               title="View">
                                                <i class="las la-eye"></i>
                                            </a>

{{--                                    @if($warranty_card->status==\App\Utility\WarrantyCardUtility::$aryStatus[\App\Utility\WarrantyCardUtility::STATUS_CANCEL]--}}
{{--|| $warranty_card->status==\App\Utility\WarrantyCardUtility::$aryStatus[\App\Utility\WarrantyCardUtility::STATUS_SUCCESS]--}}
{{-- )--}}
                                        <a href="#"
                                           class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                           data-href="{{route('warranty_card.destroy', encrypt($warranty_card->id))}}"
                                           title="{{ translate('Xóa') }}">
                                            <i class="las la-trash"></i>
                                        </a>
{{--                                            @endif--}}
                                    </td>

                                </tr>
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="aiz-pagination">
                    {{ $warranty_cards->appends(request()->input())->links() }}
                </div>
            </div>
        </form>
    </div>



@endsection
@section('modal')
    @include('modals.delete_modal')
    @include('modals.confirm_modal')
@endsection


@section('script')
    <script src="{{ asset('public/assets/js/sweetalert2@11.js') }}"></script>
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

        $('#search').change(function () {
            $('#sort_Card').submit();
        })

        $(document).on('change','#sort_selectCart' ,function () {
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
                url: "{{route('warranty_card.buck-delete')}}",
                type: 'POST',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    if (response == 1) {
                        Swal.fire('Xóa thành công')
                        location.reload();
                    }
                }
            });
        }
    </script>
@endsection
