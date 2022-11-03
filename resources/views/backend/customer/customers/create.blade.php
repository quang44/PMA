@extends('backend.layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-6 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{translate('Thêm mới tài khoản')}}</h5>
                </div>

                <form action="{{ route('customers.addNew') }}" method="POST">
                    <input name="_method" type="hidden" value="POST">

                    @csrf
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="name">{{translate('Name')}}</label>
                            <div class="col-sm-9">
                                <input type="text" placeholder="{{translate('Name')}}" id="name" name="name" value="{{ old('name') }}" class="form-control" required>
                                @error('name')
                                <div class="" style="color: red">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
{{--                        <div class="form-group row">--}}
{{--                            <label class="col-sm-3 col-from-label" for="email">{{translate('Email')}}</label>--}}
{{--                            <div class="col-sm-9">--}}
{{--                                <input type="text" placeholder="{{translate('Email')}}" id="email" name="email" value="{{ old('email') }}" class="form-control" >--}}
{{--                                @error('email')--}}
{{--                                <div class="" style="color: red">{{ $message }}</div>--}}
{{--                                @enderror--}}
{{--                            </div>--}}
{{--                        </div>--}}
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="mobile">{{translate('Phone')}}</label>
                            <div class="col-sm-9">
                                <input type="number" placeholder="{{translate('Phone')}}" id="phone" name="phone" value="{{ old('phone') }}" class="form-control" required>
                                @error('phone')
                                <div class="" style="color: red">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
{{--                        <div class="form-group row">--}}
{{--                            <label class="col-sm-3 col-from-label" for="package_id">{{translate('Nhóm')}}</label>--}}
{{--                            <div class="col-sm-9">--}}
{{--                                <select name="customer_package_id" id="" class="form-control" required>--}}
{{--                                    @foreach($packages as $package)--}}
{{--                                        <option {{$package->default==1?'selected':''}} value="{{ $package->id }}" >{{ $package->name }}</option>--}}
{{--                                    @endforeach--}}
{{--                                </select>--}}
{{--                                @error('customer_package_id')--}}
{{--                                <div class="" style="color: red">{{ $message }}</div>--}}
{{--                                @enderror--}}
{{--                            </div>--}}
{{--                        </div>--}}


                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="email">{{translate('Mật khẩu khách hàng')}}</label>
                            <div class="col-sm-9">
                                <input type="password" placeholder="" id="password" name="password" value="" class="form-control"  >
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

                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
