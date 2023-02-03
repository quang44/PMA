@extends('backend.layouts.app')

@section('content')
    <style>
        .remove-attachment {
            display: none;
        }
    </style>
    <div class="row">
        <div class="col-lg-12 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{translate('Info Warranty Card')}} </h5>
                </div>
                <div class="card-body align-content-center">
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="name">Người tạo :</label>
                        <div class="col-sm-9">
                            <span>
                                @if($warranty_card->user)
                                    {{strtoupper($warranty_card->user->name)}}
                                @else
                                    người dùng không tồn tại
                                    @endif
                               </span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="name">{{translate('Customer')}} :</label>
                        <div class="col-sm-9">
                            <span>{{strtoupper($warranty_card->user_name)}}</span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="Seri">{{translate('Phone')}} :</label>
                        <div class="col-sm-9">
                            <span>{{$warranty_card->phone}}</span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="email">{{translate('email')}} :</label>
                        <div class="col-sm-9">
                            <span>{{$warranty_card->email??'Chưa có email'}}</span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="address">{{translate('Address')}} :</label>
                        <div class="col-sm-9">
                            <span>{{$warranty_card->address}}, {{$warranty_card->ward->name}}, {{$warranty_card->district->name}}, {{$warranty_card->province->name}}</span>

                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="Seri">{{translate('Created_at')}} :</label>
                        <div class="col-sm-9">
                            <span>{{convertTime($warranty_card->create_time)}}</span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="Seri">{{translate('Active time')}} / Hủy
                            :</label>
                        <div class="col-sm-9">
                                <span class="text-danger">@if($warranty_card->active_time>0)
                                        {{convertTime($warranty_card->active_time)}}
                                    @else
                                        --
                                    @endif
                                </span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="Seri">{{translate('Warranty code')}}:</label>
                        <div class="col-sm-9">
                         {{$warranty_card->warranty_code}}
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="package_id">{{translate('Accept by')}}
                            :</label>
                        <div class="col-sm-9">
                            <span>
                                        @if($warranty_card->accept_by!=null)
                                    @if($warranty_card->active_user_id!=null && $warranty_card->active_user_id->user_type='admin')
                                        <span class="badge badge-inline badge-success">Admin</span>
                                    @else
                                        <span class="badge badge-inline badge-success">CTV</span>
                                    @endif
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="package_id">{{translate('Status')}} :</label>
                        <div class="col-sm-9">

                            @if($warranty_card->status==0)
                                <span class="badge badge-inline badge-secondary">
                                    {{\App\Utility\WarrantyCardUtility::$aryStatus[\App\Utility\WarrantyCardUtility::STATUS_NEW]}}
                                        </span>
                            @elseif($warranty_card->status==1)
                                <span class="badge badge-inline badge-success">
                                        {{\App\Utility\WarrantyCardUtility::$aryStatus[\App\Utility\WarrantyCardUtility::STATUS_SUCCESS]}}
                                </span>
                            @else
                                <span class="badge badge-inline badge-danger">
                                        {{\App\Utility\WarrantyCardUtility::$aryStatus[\App\Utility\WarrantyCardUtility::STATUS_CANCEL]}}
                                             </span> / lý do :  {{$warranty_card->reason}}
                            @endif


                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="package_id">{{translate('Note')}}
                            :</label>
                        <div class="col-sm-9">
                            <span>{{$warranty_card->note}}</span>
                        </div>
                    </div>

                    <div class="  row">
                        <div class="card-body">
                            <div class="table-responsive">
                        <table class="table aiz-table mb-0">
                            <thead>
                            <tr>
                                <th>{{translate('Product')}} </th>
                                <th data-breakpoints="lg">{{translate('Image')}}</th>
                                <th data-breakpoints="lg">{{translate('Video')}}</th>
                                <th data-breakpoints="lg">{{translate('Color')}}</th>
                                <th data-breakpoints="lg">{{translate('Quantity')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                           @foreach($warranty_card->cardDetail as $key=> $detail)
                            <tr>
                                <td>{{$detail->product!=null?$detail->product->name:"sản phẩm không tồn tại"}}</td>
                                <td>
                                    <div class="row" id="gallery{{$key}}">
                                    <a class="a-key" data-key="{{$key}}" href="{{ static_asset($detail->image) }}" >
                                    <img src="{{static_asset($detail->image)}}" alt="" class="h-60px image" >
                                    </a>
                                    </div>
                                </td>
                                <td>
                                    <div class="row" id="video{{$key}}">
                                        <a  >
                                            <video width="200" height="300px" controls>
                                                <source src="{{ static_asset($detail->video) }}" type="video/mp4">
                                            </video>
                                        </a>
                                    </div>
                                </td>
                                <td>
                                    @if(!$detail->color)
                                        <span class='size-25px d-inline-block mr-2 bg-danger '>not found</span>
                                        @else
                                        <span class='size-25px d-inline-block mr-2 rounded border'
                                              style='background:{{$detail->color?$detail->color->code:''}}'></span>
                                        @endif

                                </td>
                                <td>{{$detail->qty}}</td>

                            </tr>
                           @endforeach
                            </tbody>
                        </table>
                    </div>
                        </div>
                    </div>

                    @if($warranty_card->status==0 )
                        <a href="javascript:void(0)"
                           class="btn btn-soft-info btn-icon btn-circle btn-sm"
                           onclick="updateCard('{{route('warranty_card.ban', encrypt($warranty_card->id))}}',1);"
                           title="{{ translate('Activate Cards') }}">
                            <i class="las la-credit-card"></i>
                        </a>
                        <a href="javascript:void(0)"
                           class="btn btn-soft-danger btn-icon btn-circle btn-sm"
                           onclick="confirm_ban('{{route('warranty_card.ban', encrypt($warranty_card->id))}}' ,2);"
                           title="{{ translate('Cancel the card') }}">
                            <i class="las la-credit-card"></i>
                        </a>
                    @endif
                </div>

            </div>
        </div>
    </div>



@section('modal')
    @include('modals.confirm_modal')
@endsection

@endsection

<!-- CSS only -->
@section('script')


    <script>
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

        $(document).on('focus', '.a-video', function (e) {
            let key = $(this).attr('data-key')
            $(`div#video` + key).magnificPopup({
                delegate: 'a',
                gallery: {
                    enabled: true
                }
            })
        })
        // $(`div#gallery`).magnificPopup({
        //     delegate: 'a',
        //     type: 'image',
        //     gallery: {
        //         enabled: true
        //     }
        // })


        $('.image').on('click', function () {
            let img = $(this).attr('src');
            $('#image').attr('src', img)
        })

        function confirm_ban(url, status) {
            $('#confirm-ban').modal('show', {backdrop: 'static'});
            document.getElementById('confirmation').setAttribute('action', url + '?status=' + status);
        }


        function updateCard(url, status) {
            $('#confirm-update-bank').modal('show', {backdrop: 'static'});
            document.getElementById('updateCard').setAttribute('href', url + '?status=' + status);
        }

    </script>
@endsection
