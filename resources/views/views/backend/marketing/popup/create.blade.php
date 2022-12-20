@extends('backend.layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{translate('Popup Information')}}</h5>
                </div>
                <div class="card-body">
                    <form id="add_form" class="form-horizontal" action="{{ route('popup.store') }}" method="POST">
                        @csrf
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">
                                {{translate('Popup Title')}}
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-9">
                                <input type="text" placeholder="{{translate('Popup Title')}}" id="name" name="name" value="{{ old('name') }}"
                                       class="form-control">
                                @error('name')
                                <div style="color: red">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="signinSrEmail">
                            {{translate('Popup')}}
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
                                @error('image')
                                <div style="color: red">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-sm-3 control-label" for="products">{{translate('Type')}}</label>
                            <div class="col-sm-9">
                                <select name="type" id="type" class="form-control aiz-selectpicker"
                                        data-placeholder="{{ translate('Choose type') }}">
                                    <option value="all_user" @if(old('type') == 'customer') selected @endif>{{ trans('Khách hàng') }}</option>
                                    <option value="new_user" @if(old('type') == 'new_user') selected @endif>{{ trans('Khách hàng mới') }}</option>
                                    <option value="kol" @if(old('type') == 'kol') selected @endif>{{ trans('Cộng tác viên') }}</option>
                                    <option value="employee" @if(old('type') == 'employee') selected @endif>{{ trans('Nhân viên') }}</option>
                                </select>
                                @error('type')
                                <div style="color: red">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row" id="row_date" @if(old('type', 'all_user') == 'new_user') style="display: none" @endif>
                            <label class="col-sm-3 control-label" for="start_date">{{translate('Date')}}</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control aiz-date-range" name="date_range" value="{{ old('date_range') }}"
                                       placeholder="Select Date" data-time-picker="true" data-format="DD-MM-Y HH:mm:ss"
                                       data-separator=" to " autocomplete="off" >
                                @error('date_range')
                                <div style="color: red">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row" id="row_day" @if(old('type', 'all_user') != 'new_user') style="display: none" @endif>
                            <label class="col-md-3 col-form-label">
                                {{translate('Số ngày hiển thị')}}
                            </label>
                            <div class="col-md-9">
                                <input type="number" placeholder="" name="day" class="form-control" value="{{ old('day') }}">
                                @error('day')
                                <div style="color: red">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">
                                {{translate('Link')}}
                            </label>
                            <div class="col-md-9">
                                <input type="text" placeholder="" name="link" class="form-control" value="{{ old('link') }}">
                                @error('link')
                                <div style="color: red">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
<!--                        <div class="form-group row" id="row_content" style="display: none">
                            <label class="col-md-3 col-from-label">
                                {{translate('Content')}}
                            </label>
                            <div class="col-md-9">
                                <textarea class="aiz-text-editor" name="content"></textarea>
                            </div>
                        </div>-->

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
                if (type !== 'new_user') {
                    $('#row_date').show();
                    $('#row_day').hide();
                } else {
                    $('#row_date').hide();
                    $('#row_day').show();
                }
            })
        })
    </script>
@endsection
