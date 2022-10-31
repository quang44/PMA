@extends('backend.layouts.app')
@section('content')

    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="align-items-center">
            <h1 class="h3">{{translate('Cập nhật nhóm nguười dùng')}}</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-body p-0">
                    <form class="p-4" action="{{ route('customer_packages.update', $customer_package->id) }}"
                          method="POST">
                        <input type="hidden" name="_method" value="PATCH">
                        @csrf
                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">{{translate('Tên nhóm')}} <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control"
                                       value="{{old('name') ? old('name') : $customer_package->name}}" name="name">
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
                                            Browse
                                        </div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden"
                                           value="{{old('avatar') ? old('avatar') : $customer_package ->avatar}}"
                                           name="avatar" class="selected-files">
                                </div>
                                <div class="file-preview box sm">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">{{translate('Điểm thưởng')}} <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control"
                                       value="{{old('bonus') ? old('bonus') : $customer_package->bonus}}" name="bonus">
                                @error('bonus')
                                <span class="text-danger"> {{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">{{translate('Mô tả')}}</label>
                            <div class="col-sm-10">
					<textarea
                        class="aiz-text-editor form-control"
                        placeholder="{{translate('Nội dung ..')}}"
                        data-buttons='[["font", ["bold", "underline", "italic", "clear"]],["para", ["ul", "ol", "paragraph"]],["style", ["style"]],["color", ["color"]],["table", ["table"]],["insert", ["link", "picture", "video"]],["view", ["fullscreen", "codeview", "undo", "redo"]]]'
                        data-min-height="300"
                        name="description"
                    >{!! old('description') ? old('description') : $customer_package->description !!}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">{{translate('Số tiền tối thiểu có thể rút')}} <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type=text class="form-control"
                                       value="{{old('withdraw') ? old('withdraw') : $customer_package->withdraw}}"
                                       name="withdraw">
                                @error('withdraw')
                                <span class="text-danger"> {{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">{{translate('Số point cần đạt')}} <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control"
                                       value="{{old('point') ? old('point') : $customer_package->point}}"
                                       name="point">
                                @error('point')
                                <span class="text-danger"> {{$message}}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-sm btn-primary">{{translate('Lưu')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
