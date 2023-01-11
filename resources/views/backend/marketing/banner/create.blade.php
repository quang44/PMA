@extends('backend.layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">Thông tin banner</h5>
                </div>
                <div class="card-body">
                    <form id="add_form" class="form-horizontal" action="{{ route('banner.store') }}" method="POST">
                        @csrf
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">
                                {{translate('Banner Title')}}
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-9">
                                <input type="text" placeholder="{{translate('Banner Title')}}" id="name" name="name"
                                       class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="signinSrEmail">
                            {{translate('Banner')}}
                            <!--                            <small>(1300x650)</small>-->
                            </label>
                            <div class="col-md-9">
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">
                                            {{ translate('Browse')}}
                                        </div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="image" class="selected-files">
                                </div>
                                <div class="file-preview box sm">
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 control-label" for="start_date">{{translate('Date')}}</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control aiz-date-range" name="date_range"
                                       placeholder="Select Date" data-time-picker="true" data-format="DD-MM-Y HH:mm:ss"
                                       data-separator=" to " autocomplete="off" required>
                            </div>
                        </div>
{{--                        <div class="form-group row mb-3">--}}
{{--                            <label class="col-sm-3 control-label" for="products">{{translate('Type')}}</label>--}}
{{--                            <div class="col-sm-9">--}}
{{--                                <select name="type" id="type" class="form-control aiz-selectpicker" required--}}
{{--                                        data-placeholder="{{ translate('Choose type') }}">--}}
{{--                                    <option value="link">{{ trans('Link') }}</option>--}}
{{--                                    <option value="content">{{ trans('Content') }}</option>--}}
{{--                                </select>--}}
{{--                            </div>--}}
{{--                        </div>--}}
                    <!--                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">
                            {{translate('Short Description')}}
                        <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-9">
                        <textarea name="short_description" rows="5" class="form-control" required=""></textarea>
                    </div>
                </div>-->
{{--                        <div class="form-group row" id="row_link">--}}
{{--                            <label class="col-md-3 col-form-label">--}}
{{--                                {{translate('Link')}}--}}
{{--                            </label>--}}
{{--                            <div class="col-md-9">--}}
{{--                                <input type="text" placeholder="" name="link" class="form-control">--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="form-group row">--}}
{{--                            <label class="col-md-3 col-form-label">--}}
{{--                                Đối tượng hiển thị--}}
{{--                            </label>--}}
{{--                            <div class="col-md-9">--}}
{{--                                <select name="subject" id="subject" class="form-control aiz-selectpicker" required--}}
{{--                                        data-placeholder="{{ translate('Chọn đối tượng hiển thị') }}">--}}
{{--                                    <option value="customer">{{ trans('Khách hàng') }}</option>--}}
{{--                                    <option value="kol">{{ trans('Cộng tác viên') }}</option>--}}
{{--                                    <option value="employee">{{ trans('Đại lý') }}</option>--}}
{{--                                </select>--}}
{{--                            </div>--}}
{{--                        </div>--}}
                        <div class="form-group row" id="row_content" style="display: none">
                            <label class="col-md-3 col-from-label">
                                {{translate('Content')}}
                            </label>
                            <div class="col-md-9">
                                <textarea class="aiz-text-editor" name="content"></textarea>
                            </div>
                        </div>

                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-primary">
                                {{translate('Save')}}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            $('#type').on('change', function () {
                let type = $(this).val();
                if (type === 'link') {
                    $('#row_link').show();
                    $('#row_content').hide();
                } else {
                    $('#row_link').hide();
                    $('#row_content').show();
                }
            })
        })
    </script>
@endsection
