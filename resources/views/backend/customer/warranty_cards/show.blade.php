@extends('backend.layouts.app')

@section('content')
    <style>
        .remove-attachment{
            display: none;
        }
    </style>
    <div class="row">
        <div class="col-lg-6 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{translate('Thông tin Thẻ Bảo hành ')}} </h5>
                </div>
                    <div class="card-body align-content-center">
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="package_id">{{translate('Hãng ')}} :</label>
                            <div class="col-sm-9">
                                <span>{{$warranty_card->brand->name}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="name">{{translate('Tên khách hàng')}} :</label>
                            <div class="col-sm-9">
                                <span>{{$warranty_card->user_name}}</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="address">{{translate('Địa chỉ')}} :</label>
                            <div class="col-sm-9">
                                <span>{{$warranty_card->address}}</span>

                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="Seri">{{translate('Seri')}} :</label>
                            <div class="col-sm-9">
                                <span>{{$warranty_card->seri}}</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="Seri">{{translate('Điểm nhận được')}} :</label>
                            <div class="col-sm-9">
                                <span class="text-danger">{{$warranty_card->point}}</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="Seri">{{translate('Thời gian Tạo')}} :</label>
                            <div class="col-sm-9">
                                <span >{{date('d-m-Y H:i:s',strtotime($warranty_card->created_at))}}</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="Seri">{{translate('Thời gian kích hoạt thẻ')}} :</label>
                            <div class="col-sm-9">
                                <span class="text-danger">@if($warranty_card->active_time>0)
                                        {{date('d-m-Y H:i:s ',strtotime($warranty_card->active_time))}}
                                    @else
                                   {{ trans('Chưa kích hoạt') }}
                                    @endif
                                </span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="package_id">{{translate('trạng thái')}} :</label>
                            <div class="col-sm-9">
                                <span>
                                    @if($warranty_card->status==0)
                                        {{translate('chưa duyệt')}}
                                        @elseif($warranty_card->status==1)
                                        {{translate('đã duyệt')}}
                                    @else
                                        {{translate('hủy')}}  / lý do :  {{$warranty_card->reason}}
                                    @endif
                                </span>

                            </div>
                        </div>


                        <div class="form-group mb-3 row">
                            <label for="name" class=" col-sm-3">{{translate('Qr Code image ')}} :</label>
                            <div class="col-sm-9">
                            <div class="input-group" data-toggle="aizuploader" data-type="image" >
                                <input type="hidden" name="qr_code_image" class="selected-files" value="{{$warranty_card->qr_code_image}}">
                            </div>
                            <div class="file-preview box sm"> no image</div>
                            </div>
                        </div>

                        <div class="form-group mb-3 row">
                            <label for="name" class=" col-sm-3" >{{translate('Seri image ')}} :</label>
                           <div class="col-sm-9">
                            <div class="input-group" data-toggle="aizuploader" data-type="image">
                                <input type="hidden" name="seri_image" class="selected-files" value="{{$warranty_card->seri_image}}">
                            </div>
                            <div class=" file-preview box sm">
                            </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="package_id">{{translate('ghi chú khách hàng')}} :</label>
                            <div class="col-sm-9">
                                <span>{{$warranty_card->note}}</span>
                            </div>
                        </div>
                    </div>

            </div>
        </div>
    </div>

@endsection
