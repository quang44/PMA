@extends('backend.layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{translate('Thêm mới tài khoản')}}</h5>
                </div>

                <form action="{{ route('customers.store') }}" method="POST">
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
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="email">{{translate('Email')}}</label>
                            <div class="col-sm-9">
                                <input type="email" placeholder="{{translate('Email')}}" id="name" name="email" value="{{ old('email') }}" class="form-control" required>
                                @error('email')
                                <div class="" style="color: red">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="address">{{translate('Address')}} củ thể</label>
                            <div class="col-sm-9">
                                <input type="text" placeholder="{{translate('Address')}}" id="name" name="address" value="{{ old('address') }}" class="form-control" required>
                                @error('address')
                                <div class="" style="color: red">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3 d-flex align-items-center">
                                <label class=" col-from-label" for="email">Địa chỉ </label>
                            </div>
                            <div class="col-9 row">
                                <div class="form-group col-4">
                                    <label for="city-dd">{{translate('Province')}}/{{translate('City')}}</label>
                                    <select id="city" name="province" class="form-control aiz-selectpicker"  data-selected-text-format="count" data-live-search="true">
                                        <option >Chọn tỉnh thành phố</option>
                                        @foreach($province as $city)
                                            <option value="{{$city->id}}">{{$city->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-4">
                                    <label for="city-dd">{{translate('District')}}</label>
                                    <select id="district" name="district"  class="form-control aiz-selectpicker" disabled data-selected-text-format="count" data-live-search="true" ></select>
                                </div>

                                <div class="form-group col-4">
                                    <label for="city-dd">{{translate('Ward')}}</label>
                                    <select id="ward" name="ward" class="form-control aiz-selectpicker" disabled data-selected-text-format="count" data-live-search="true"></select>
                                </div>
                            </div>

                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="mobile">{{translate('Phone')}}</label>
                            <div class="col-sm-9">
                                <input type="number" placeholder="{{translate('Phone')}}" id="phone" name="phone" value="{{ old('phone') }}" class="form-control" required>
                                @error('phone')
                                <div class="" style="color: red">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>


                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="email">{{translate('Password')}}</label>
                            <div class="col-sm-9">
                                <input type="password" placeholder="" id="password" name="password" value="" class="form-control"  required>
                                @error('password')
                                <div class="" style="color: red">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="password" required>{{translate('Confirm Password')}}</label>
                            <div class="col-sm-9">
                                <input type="password" placeholder="" id="password_confirmation" name="password_confirmation" value="" class="form-control" >

                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="depot">{{translate('depot')}}</label>
                            <div class="col-sm-9">
                                <select name="depot" id="depot" class="form-control aiz-selectpicker" data-selected-text-format="count" data-live-search="true" >
                                    <option selected>Chọn Tổng kho</option>

                                @foreach($depots as $depot)
                                        <option value="{{$depot->id}}">{{$depot->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

{{--                        <div class="form-group row">--}}
{{--                            <label class="col-sm-3 col-from-label" for="depot">{{translate('Agent')}}</label>--}}
{{--                            <div class="col-sm-9">--}}
{{--                                    <select name="agent" id="agent" class="form-control  aiz-selectpicker" disabled data-selected-text-format="count" data-live-search="true" ></select>--}}
{{--                            </div>--}}

{{--                        </div>--}}


                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@section('script')
    <script !src="">
        $(function() {



        $('#city').change(function () {
            let city = $(this).find('option:selected');
            CallApi("{{url('')}}/api/v2/districts-by-province/"+city.val() ,  'district')
        })

        $('#district').change(function () {
            let district = $(this).find('option:selected');
            CallApi("{{url('')}}/api/v2/wards-by-district/"+district.val() ,  'ward')
        })

        {{--$('#depot').change(function () {--}}
        {{--    let depot = $(this).find('option:selected');--}}
        {{--    CallApi("{{url('')}}/api/v2/agent-by-depot/"+depot.val(),'agent')--}}
        {{--})--}}

        {{--$('#Agent').change(function () {--}}
        {{--    let agent = $(this).find('option:selected');--}}
        {{--    if(agent.val()==-1){--}}
        {{--        $(`#addressAgent`).attr('disabled',true)--}}
        {{--    }else{--}}
        {{--        $.ajax({--}}
        {{--            url:  "{{url('')}}/api/v2/address-by-agent/"+agent.val(),--}}
        {{--            method: "GET",--}}
        {{--            success: function (res) {--}}
        {{--                let html =` <option value="-1">Lựa chọn</option>`;--}}
        {{--                html += res.data.map(function (value, key) {--}}
        {{--                    return `--}}
        {{--            <option value="${value.id}">${value.province.name}-${value.district.name}</option>--}}
        {{--            `--}}
        {{--                })--}}
        {{--                $(`#addressAgent`).attr('disabled',false)--}}
        {{--                $(`#addressAgent`).html(  res.result==false?`<option>không tìm thấy dữ liệu</option>`:html)--}}
        {{--            }, error: function (error) {--}}
        {{--                console.log(error)--}}
        {{--            }--}}
        {{--        })--}}
        {{--    }--}}

        {{--})--}}

        function CallApi(url, element) {

            $.ajax({
                url:  url,
                method: "GET",
                success: function (res) {
                        $(`#${element}`).attr('disabled', false)
                        HandleRegion(res, element)
                }, error: function (error) {
                    console.log(error)
                }
            })
        }

        function HandleRegion(data, element) {
               let  html = data.data.map(function (value, key) {
                    return `
                    <option value="${value.id}">${value.name}</option>
                    `
            })

            $(`#${element}`).html(html)
            AIZ.plugins.bootstrapSelect('refresh');

        }

        });
    </script>

@endsection


@endsection
