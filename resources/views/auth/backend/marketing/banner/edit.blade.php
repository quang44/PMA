@extends('backend.layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{translate('Banner Information')}}</h5>
                </div>
                <div class="card-body">
                    <form id="add_form" class="form-horizontal" action="{{ route('banner.update', ['banner' => request('banner')]) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">
                                {{translate('Banner Title')}}
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-9">
                                <input type="text" placeholder="{{translate('Banner Title')}}" id="name" name="name" value="{{ $banner->name }}"
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
                                    <input type="hidden" name="image" class="selected-files" value="{{ $banner->image }}">
                                </div>
                                <div class="file-preview box sm">
                                </div>
                            </div>
                        </div>
                        @php
                            $start_date = date('d-m-Y H:i:s', $banner->start_time);
                            $end_date = date('d-m-Y H:i:s', $banner->end_time);
                        @endphp
                        <div class="form-group row">
                            <label class="col-sm-3 control-label" for="start_date">{{translate('Date')}}</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control aiz-date-range" value="{{ $start_date.' to '.$end_date }}" name="date_range"
                                       placeholder="Select Date" data-time-picker="true" data-format="DD-MM-Y HH:mm:ss"
                                       data-separator=" to " autocomplete="off" required>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-sm-3 control-label" for="products">{{translate('Type')}}</label>
                            <div class="col-sm-9">
                                <select name="type" id="type" class="form-control aiz-selectpicker" required
                                        data-placeholder="{{ translate('Choose type') }}">
                                    <option value="link" @if($banner->type == 'link') selected  @endif >{{ trans('Link') }}</option>
                                    <option value="content" @if($banner->type == 'content') selected  @endif>{{ trans('Content') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row" id="row_link" @if($banner->type != 'link') style="display: none" @endif>
                            <label class="col-md-3 col-form-label">
                                {{translate('Link')}}
                            </label>
                            <div class="col-md-9">
                                <input type="text" placeholder="" value="{{ $banner->link }}" name="link" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">
                                Đối tượng hiển thị
                            </label>
                            <div class="col-md-9">
                                <select name="subject" id="subject" class="form-control aiz-selectpicker" required
                                        data-placeholder="{{ translate('Chọn đối tượng hiển thị') }}">
                                    <option value="customer" @if($banner->subject == 'customer') selected  @endif>{{ trans('Khách hàng') }}</option>
                                    <option value="kol" @if($banner->subject == 'kol') selected  @endif>{{ trans('Cộng tác viên') }}</option>
                                    <option value="employee" @if($banner->subject == 'employee') selected  @endif>{{ trans('Đại lý') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row" id="row_content" @if($banner->type != 'content') style="display: none"  @endif >
                            <label class="col-md-3 col-from-label">
                                {{translate('Content')}}
                            </label>
                            <div class="col-md-9">
                                <textarea class="aiz-text-editor" name="content">{{ $banner->content }}</textarea>
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
