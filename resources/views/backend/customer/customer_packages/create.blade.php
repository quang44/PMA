@extends('backend.layouts.app')
@section('content')

    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="align-items-center">
            <h1 class="h3">{{translate('Create New Customer')}}</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-body p-0">
                    <form class="p-4" action="{{ route('customer_packages.store') }}"
                          method="POST">
                        @csrf
                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">{{translate('Customer group name ')}}
                                <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control"
                                       value="{{old('name')}}" name="name">
                                @error('name')
                                <span class="text-danger"> {{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label" for="signinSrEmail">
                                {{translate('Avatar')}}
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
                                    <input type="hidden"
                                           value="{{old('avatar')}}"
                                           name="avatar" class="selected-files">
                                </div>
                                <div class="file-preview box sm">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">{{translate('Reward Points')}} <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control"
                                       value="{{old('bonus')}}" name="bonus">
                                @error('bonus')
                                <span class="text-danger"> {{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">{{translate('Description')}}</label>
                            <div class="col-sm-10">
					<textarea
                        class="aiz-text-editor form-control"
                        placeholder="{{translate('Content..')}}"
                        data-buttons='[["font", ["bold", "underline", "italic", "clear"]],["para", ["ul", "ol", "paragraph"]],["style", ["style"]],["color", ["color"]],["table", ["table"]],["insert", ["link", "picture", "video"]],["view", ["fullscreen", "codeview", "undo", "redo"]]]'
                        data-min-height="300"
                        name="description"
                    >{!! old('description')!!}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label"
                                   for="name">{{translate('Amount that can be withdrawn')}} <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type=text class="form-control"
                                       value="{{old('withdraw')}}"
                                       name="withdraw">
                                @error('withdraw')
                                <span class="text-danger"> {{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">{{translate('Number of points')}} <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control"
                                       value="{{old('point')}}"
                                       name="point">
                                @error('point')
                                <span class="text-danger"> {{$message}}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">{{translate('Default')}} </label>
                            <div class="col-sm-10">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input class="default" name="default" type="checkbox">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
