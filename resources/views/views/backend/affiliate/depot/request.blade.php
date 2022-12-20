@extends('backend.layouts.app')

@section('content')

    <div class="aiz-titlebar text-left mt-2 mb-3 row">
        <div class=" col-md-6 align-items-center">
            <h1 class="h3">{{translate('List of gift request')}}</h1>
        </div>
        {{--        <div class="col-md-6 text-md-right">--}}
        {{--            <a href="{{route('gift.create')}}" class="btn btn-circle btn-info">--}}
        {{--                <span>{{translate('Add New Gift')}}</span>--}}
        {{--            </a>--}}
        {{--        </div>--}}
    </div>

    <div class="card">
        <form class="" id="sort_Gift" action="" method="GET">
            <div class="card-header row gutters-5">
                <div class="col">
                    <h5 class="mb-0 h6">{{translate('Gift request')}}</h5>
                </div>
                {{--                <div class="dropdown mb-2 mb-md-0">--}}
                {{--                    <button class="btn border dropdown-toggle" type="button" data-toggle="dropdown">--}}
                {{--                        {{translate('Bulk Action')}}--}}
                {{--                    </button>--}}
                {{--                    <div class="dropdown-menu dropdown-menu-right">--}}
                {{--                        <a class="dropdown-item" href="#" onclick="bulk_delete()"> {{translate('Delete selection')}}</a>--}}
                {{--                    </div>--}}
                {{--                </div>--}}

                <div class="col-md-3">
                    <div class="form-group mb-0">
                        <select name="sort_status" id="sort_selectGift" class="form-control aiz-selectpicker"
                                data-selected-text-format="count"
                                data-live-search="true"
                        >
                            <option value="-1">{{translate('gift status')}}</option>
                            <option value="0"
                                    @if(request('sort_status',-1)==0) selected @endif>{{translate('Not approved yet')}}</option>
                            <option value="1"
                                    @if(request('sort_status',-1)==1) selected @endif>{{translate('Approved')}}</option>
                            <option value="2"
                                    @if(request('sort_status',-1)==2) selected @endif>{{translate('Cancelled')}}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group mb-0">

                        <input type="text" class="form-control" id="search" name="search"
                               @isset($search) value="{{ $search }}"
                               @endisset placeholder="{{ translate('Search') }}">
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table aiz-table mb-0">
                        <thead>
                        <tr>
                            <th data-breakpoints="lg">#</th>
                            <th data-breakpoints="lg">{{translate('Customer')}}</th>
                            <th data-breakpoints="lg">{{translate('Info')}}</th>
                            <th data-breakpoints="lg">{{translate('Status')}}</th>
                            <th data-breakpoints="lg">{{translate('Created_at')}}</th>
                            <th data-breakpoints="lg">{{translate('Active Time')}}</th>
                            <th class="text-right">{{translate('Options')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($giftRequest as $key => $gift)
                            @if ($gift != null)
                                <tr>

                                    <td>
                                        {{$key+1}}
                                    </td>

                                    <td>{{$gift->user!=null?$gift->user->name:'người dùng không tồn tại'}}</td>
                                    <td>
                                        <img width="80px" src="{{uploaded_asset($gift->gift->image)}}"
                                             alt="{{translate('Gift')}}"> <br>
                                        tên : {{$gift->gift->name}} <br>
                                        điểm: {{$gift->gift->point}}
                                    </td>
                                    <td>
                                        @if($gift->status == 0)
                                            <span class="badge badge-inline badge-secondary">
                                                {{\App\Utility\WarrantyCardUtility::$aryStatus[\App\Utility\WarrantyCardUtility::STATUS_NEW]}}</span>
                                        @else
                                            @if($gift->status == 1)
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

                                    <td>{{date('d-m-Y',strtotime($gift->created_time))}}</td>
                                    <td>{{$gift->active_time!=null?date('d-m-Y',strtotime($gift->active_time)):'--'}}</td>
                                    <td class="text-right">

                                        <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                           href="{{ route('gift.show',[encrypt($gift->id)]) }}"
                                           title="View">
                                            <i class="las la-eye"></i>
                                        </a>
                                        @if($gift->status==0 )
                                            <a href="javascript:void(0)"
                                               class="btn btn-soft-info btn-icon btn-circle btn-sm"
                                               onclick="updateCard('{{route('gift_ban', [encrypt($gift->id)])}}',1);"
                                               title="xác nhận yêu cầu">
                                                <i class="las la-gifts"></i>
                                            </a>


                                            <a href="javascript:void(0)"
                                               class="btn btn-soft-danger btn-icon btn-circle btn-sm"
                                               onclick="confirm_ban('{{route('gift_ban', [encrypt($gift->id)])}}' ,2);"
                                               title="hủy yêu cầu">
                                                <i class="las la-gifts"></i>
                                            </a>
                                        @endif

                                    </td>
                                </tr>
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="aiz-pagination">
                    {{ $giftRequest->appends(request()->input())->links() }}
                </div>
            </div>
        </form>
    </div>




@endsection


@section('modal')
    @include('modals.confirm_modal')
    @include('modals.delete_modal')
@endsection

@section('script')
    <script src="{{ asset('public/assets/js/sweetalert2@11.js') }}"></script>
    <script type="text/javascript">

        function confirm_ban(url, status) {
            $('#confirm-ban').modal('show', {backdrop: 'static'});
            document.getElementById('confirmation').setAttribute('action', url + '?status=' + status);
        }

        function updateCard(url, status) {
            $('#confirm-update-bank').modal('show', {backdrop: 'static'});
            document.getElementById('updateCard').setAttribute('href', url + '?status=' + status);
        }


        $('#sort_selectGift').on('change', function () {
            $('#sort_Gift').submit();
        })


        $(document).on("change", ".check-all", function () {
            if (this.checked) {
                // Iterate each checkbox
                $('.check-one:checkbox').each(function () {
                    this.checked = true;
                });
            } else {
                $('.check-one:checkbox').each(function () {
                    this.checked = false;
                });
            }

        });


    </script>
@endsection
