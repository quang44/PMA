@extends('backend.layouts.app')

@section('content')

<div class="row">
    <div class="col-lg-6 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('Thông tin khách hàng')}}</h5>
            </div>

            <form action="{{ route('customers.update', $user->id) }}" method="POST">
                <input name="_method" type="hidden" value="PATCH">
            	@csrf
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="name">{{translate('Tên')}}</label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="{{translate('Tên')}}" id="name" name="name" value="{{ old('name', $user->name) }}" class="form-control" required>
                            @error('name')
                            <div class="" style="color: red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
{{--                    <div class="form-group row">--}}
{{--                        <label class="col-sm-3 col-from-label" for="email">{{translate('Email')}}</label>--}}
{{--                        <div class="col-sm-9">--}}
{{--                            <input type="text" placeholder="{{translate('Email')}}" id="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" >--}}
{{--                            @error('email')--}}
{{--                            <div class="" style="color: red">{{ $message }}</div>--}}
{{--                            @enderror--}}
{{--                        </div>--}}
{{--                    </div>--}}
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="mobile">{{translate('Số điện thoại')}}</label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="{{translate('Số điện thoại')}}" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" class="form-control" required>
                            @error('phone')
                            <div class="" style="color: red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
{{--                    <div class="form-group row">--}}
{{--                        <label class="col-sm-3 col-from-label" for="package_id">{{translate('Nhóm')}}</label>--}}
{{--                        <div class="col-sm-9">--}}
{{--                            <select name="customer_package_id" id="" class="form-control" required>--}}
{{--                                @foreach($packages as $package)--}}
{{--                                    <option value="{{ $package->id }}" @if(old('customer_package_id', $user->customer_package_id) == $package->id) selected @endif>{{ $package->name }}</option>--}}
{{--                                @endforeach--}}
{{--                            </select>--}}
{{--                            @error('package_id')--}}
{{--                            <div class="" style="color: red">{{ $message }}</div>--}}
{{--                            @enderror--}}
{{--                        </div>--}}
{{--                    </div>--}}

                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="email">{{translate('Mật khẩu khách hàng')}}</label>
                        <div class="col-sm-9">
                            <input type="password" placeholder="" id="password" name="password" value="" class="form-control" >
                            @error('password')
                            <div class="" style="color: red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="email">{{translate('Xác nhận mật khẩu')}}</label>
                        <div class="col-sm-9">
                            <input type="password" placeholder="" id="password_confirmation" name="password_confirmation" value="" class="form-control" >
                        </div>
                    </div>

{{--                    <div class="form-group row">--}}
{{--                        <label class="col-sm-3 col-from-label" for="email">{{translate('Tài khoản Best Api')}}</label>--}}
{{--                        <div class="col-sm-9">--}}
{{--                            <input type="text" placeholder="" id="best_api_user" name="best_api_user" value="{{ old('best_api_user', $user->best_api_user) }}" class="form-control" >--}}
{{--                            @error('best_api_user')--}}
{{--                            <div class="" style="color: red">{{ $message }}</div>--}}
{{--                            @enderror--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="form-group row">--}}
{{--                        <label class="col-sm-3 col-from-label" for="email">{{translate('Mật khẩu Best Api')}}</label>--}}
{{--                        <div class="col-sm-9">--}}
{{--                            <input type="text" placeholder="" id="best_api_password" name="best_api_password" value="{{ old('best_api_password', $user->best_api_password) }}" class="form-control" >--}}
{{--                        </div>--}}

{{--                    </div>--}}

                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-sm btn-primary">{{translate('Lưu')}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
