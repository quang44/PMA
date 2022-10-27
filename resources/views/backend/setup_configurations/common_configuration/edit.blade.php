@extends('backend.layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-12 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{translate('Cập nhật cấu hình chung')}}</h5>
                </div>

                <form class="form-horizontal" action="{{ route('common_configs.update', $common_config->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_method" value="PATCH">
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label" for="signinSrEmail">
                            {{translate('Logo')}}
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
                                    <input type="hidden" name="logo" class="selected-files" value="{{ old('logo') ? old('logo') : $common_config->logo }}">
                                </div>
                                <div class="file-preview box sm">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">{{translate('Currency unit')}} <span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" value="{{old('unit') ? old('unit') : $common_config->unit }}"  name="unit">
                                @error('unit')
                                <span class="text-danger"> {{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">{{translate('Points for referrers')}} <span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" value="{{old('for_referrer') ? old('for_referrer') : $common_config->for_referrer }}"  name="for_referrer">
                                @error('for_referrer')
                                <span class="text-danger"> {{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">{{translate('Point for activator insurance')}} <span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" value="{{old('for_activator') ? old('for_activator') : $common_config->for_activator }}"  name="for_activator">
                                @error('for_activator')
                                <span class="text-danger"> {{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">{{translate('Convert points to cash')}} <span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" value="{{old('exchange') ? old('exchange') : $common_config->exchange}}" name="exchange" >
                                @error('exchange')
                                <span class="text-danger"> {{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">{{translate('Contact Info')}}</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" value="{{old('contact_info') ? old('contact_info') : $common_config->contact_info }}"  name="contact_info" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name"> điều khoản <span class="text-danger">*</span></label>
                            <div class="col-sm-10">
					<textarea
                        class="aiz-text-editor form-control"
                        data-buttons='[["font", ["bold", "underline", "italic", "clear"]],["para", ["ul", "ol", "paragraph"]],["style", ["style"]],["color", ["color"]],["table", ["table"]],["insert", ["link", "picture", "video"]],["view", ["fullscreen", "codeview", "undo", "redo"]]]'
                        placeholder="Content.."
                        data-min-height="300"
                        name="rules"
                        required
                    > {{$common_config->rules}}</textarea>
                            </div>
                        </div>
                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-sm btn-primary">{{translate('Update')}}</button>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>

@endsection
