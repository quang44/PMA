@extends('backend.layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-6 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{translate('Thêm nhóm người dùng')}}</h5>
                </div>
                <form class="form-horizontal" action="{{ route('customer_groups.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">{{translate('Tên nhóm')}} <span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" value="{{old('name')}}"  name="name" >
                                @error('name')
                                <span class="text-danger"> {{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label" for="signinSrEmail">
                            {{translate('Ảnh đại diện')}}
                            <!--                            <small>(1300x650)</small>-->
                            </label>
                            <div class="col-sm-10">
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">
                                            {{ translate('Browse')}}
                                        </div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input value="{{old('avatar')}}" type="hidden" name="avatar" class="selected-files">
                                </div>
                                <div class="file-preview box sm">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">{{translate('Tiền thưởng')}} <span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" value="{{old('bonus')}}" class="form-control"  name="bonus" >
                                @error('bonus')
                                <span class="text-danger"> {{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">{{translate('Mô tả')}}</label>
                            <div class="col-sm-10">
                                <input type="text" value="{{old('description')}}" class="form-control"  name="description" >
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">{{translate('Số point')}} <span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" value="{{old('point_number')}}" class="form-control"  name="point_number" >
                                @error('point_number')
                                <span class="text-danger"> {{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">{{translate('Số tiền rút')}} <span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" value="{{old('can_withdraw')}}" name="can_withdraw" >
                                @error('can_withdraw')
                                <span class="text-danger"> {{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">{{translate('Mặc định')}}</label>
                            <div class="col-sm-10">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input class="hidden" name="default" type="checkbox" @if(old('default') && old('default') == 'on') checked @endif>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>

@endsection
