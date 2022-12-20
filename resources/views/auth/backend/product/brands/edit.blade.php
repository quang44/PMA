@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <h5 class="mb-0 h6">{{translate('Thông tin hãng sản xuất')}}</h5>
</div>

<div class="col-lg-8 mx-auto">
    <div class="card">
        <div class="card-body p-0">
            <form class="p-4" action="{{ route('brands.update', $brand->id) }}" method="POST" enctype="multipart/form-data">
                <input name="_method" type="hidden" value="PUT">
                <input type="hidden" name="lang" value="{{ $lang }}">
                @csrf
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="name">{{translate('Tên hãng sản xuất')}} <i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{translate('Tên hãng sản xuất')}}" id="name" name="name" value="{{ $brand->getTranslation('name', $lang) }}" class="form-control" required>
                    </div>
                </div>
                <span class="text-danger">
                        @error('name')
                    {{$message}}
                    @enderror
                    </span>
                <div class="form-group row">
                    <label for="name" class="col-sm-3 col-from-label">Code <small class="text-danger">*</small>  </label>
                    <div class="col-sm-9">
                    <input type="text" placeholder="vd :samsungABCD" name="code" class="form-control"value="{{$brand->code}}" >
                    </div>
                </div>
                <span class="text-danger">
                        @error('code')
                    {{$message}}
                    @enderror
                    </span>

                <div class="form-group row">
                    <label class="col-md-3 col-form-label" for="signinSrEmail">{{translate('Logo')}} <small>({{ translate('120x80') }})</small></label>
                    <div class="col-md-9">
                        <div class="input-group" data-toggle="aizuploader" data-type="image">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                            </div>
                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                            <input type="hidden" name="logo" value="{{$brand->logo}}" class="selected-files">
                        </div>
                        <div class="file-preview box sm">
                        </div>
                    </div>
                </div>
{{--                <div class="form-group row">--}}
{{--                    <label class="col-sm-3 col-from-label">{{translate('Tiêu đề')}}</label>--}}
{{--                    <div class="col-sm-9">--}}
{{--                        <input type="text" class="form-control" name="meta_title" value="{{ $brand->meta_title }}" placeholder="{{translate('Tiêu đề')}}">--}}
{{--                    </div>--}}
{{--                </div>--}}
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label">{{translate('Thông tin chi tiết')}}</label>
                    <div class="col-sm-9">
                        <textarea name="meta_description" rows="8" class="form-control">{{ $brand->meta_description }}</textarea>
                    </div>
                </div>
{{--                <div class="form-group row">--}}
{{--                    <label class="col-sm-3 col-from-label" for="name">{{translate('Slug')}}</label>--}}
{{--                    <div class="col-sm-9">--}}
{{--                        <input type="text" placeholder="{{translate('Slug')}}" id="slug" name="slug" value="{{ $brand->slug }}" class="form-control">--}}
{{--                    </div>--}}
{{--                </div>--}}
                <div class="form-group mb-0 text-right">
                    <button type="submit" class="btn btn-primary">{{translate('Lưu')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
