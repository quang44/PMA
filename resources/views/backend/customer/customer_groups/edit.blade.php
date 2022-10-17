@extends('backend.layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-6 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{translate('Cập nhật nhóm người dùng')}}</h5>
                </div>

                <form class="form-horizontal" action="{{ route('customer_groups.update', $customer_group->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_method" value="PATCH">
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">{{translate('Tên nhóm')}} <span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" value="{{old('name') ? old('name') : $customer_group->name}}"  name="name" >
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
                                    <input type="hidden" value="{{old('avatar') ? old('avatar') : $customer_group ->avatar}}" name="avatar" class="selected-files">
                                </div>
                                <div class="file-preview box sm">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">{{translate('Tiền thưởng')}} <span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" value="{{old('bonus') ? old('bonus') : $customer_group->bonus}}" name="bonus" >
                                @error('bonus')
                                <span class="text-danger"> {{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">{{translate('Mô tả')}}</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" value="{{old('description') ? old('description') : $customer_group->description}}" name="description" >
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">{{translate('Số tiền rút')}} <span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type=text class="form-control" value="{{old('can_withdraw') ? old('can_withdraw') : $customer_group->can_withdraw}}" name="can_withdraw" >
                                @error('can_withdraw')
                                <span class="text-danger"> {{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">{{translate('Số point')}} <span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text"  class="form-control" value="{{old('point_number') ? old('point_number') : $customer_group->point_number}}" name="point_number" >
                                @error('point_number')
                                <span class="text-danger"> {{$message}}</span>
                                @enderror
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
