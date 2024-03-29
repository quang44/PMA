@extends('backend.layouts.app')

@section('content')

<div class="row">
    <div class="col-lg-9 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">Thông tin tổng kho</h5>
            </div>

            <form class="form-horizontal" action="{{ route('affiliate.depot.create') }}" method="POST"
                  enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="name">{{translate('Name')}}</label>
                        <div class="col-sm-9">
                            <input type="text" autocomplete="off" placeholder="{{translate('Name')}}" id="name"
                                   name="name" class="form-control" value="{{old('name')}}" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="email">{{translate('Email')}}</label>
                        <div class="col-sm-9">
                            <input type="text" autocomplete="off" placeholder="{{translate('Email')}}"value="{{old('email')}}"  id="email"
                                   name="email" class="form-control">
                        </div>
                    </div>
                    <div class="address  row ">
                        <div class="col-3 d-flex align-items-center">
                            <label class=" col-from-label" for="email">Địa chỉ </label>
                        </div>
                        <div class="col-9 row">
                        <div class="col-4 form-group">
                            <label class="col-from-label " for="email">{{translate('Province')}}</label>
                            <select name="city" id="city0"  data-city="0" class="form-control aiz-selectpicker city"
                                    data-selected-text-format="count" data-live-search="true">
                                <option>Lựa chọn thành phố</option>
                                @foreach($provinces as $city)
                                    <option value="{{$city->id}}">{{$city->name}}</option>
                                @endforeach
                            </select>

                        </div>
                        <div class="col-4 ">
                            <div class="form-group ">
                                <label class=" col-from-label" for="district">{{translate('District')}}</label>
                                <select name="district" data-district="0"  id="district0"  class="district form-control aiz-selectpicker"
                                        data-selected-text-format="count" data-live-search="true" disabled>
                                </select>
                            </div>
                        </div>
                        <div class="col-4 form-group">
                            <div class="form-group ">
                                <label class="" for="district">{{translate('Ward')}}</label>
                                <select name="ward" id="ward0"  class="ward form-control aiz-selectpicker"
                                        data-selected-text-format="count" data-live-search="true" disabled>
                                </select>

                            </div>
                        </div>

                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="address">{{translate('Address')}} củ thể</label>
                        <div class="col-sm-9">
                            <input type="text" autocomplete="off" placeholder="{{translate('Address')}}" id="address"
                                   name="address" value="{{old('address')}}" class="form-control">

                        </div>
                    </div>


                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="mobile">{{translate('Phone')}}</label>
                        <div class="col-sm-9">
                            <input type="text" value="{{old('phone')}}" autocomplete="off" placeholder="{{translate('Phone')}}" id="phone"
                                   name="phone" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="password">{{translate('Password')}}</label>
                        <div class="col-sm-9">
                            <input type="password" autocomplete="off" placeholder="{{translate('Password')}}"
                                   id="password" name="password" class="form-control" required>
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
@section('script')

    <script !src="" type="text/javascript">

        $(document).on('change','select.city', function () {
            let key= $(this).attr('data-city')
            let value = $('#city'+key).val()
            CallAPI(`{{url('')}}/api/v2/districts-by-province/` + value,'district',key)
        })

        $(document).on('change','select.district', function () {
            let key= $(this).attr('data-district')
            let value = $('#district'+key).val()
            CallAPI(`{{url('')}}/api/v2/wards-by-district/` + value,'ward',key)
        })

        function  CallAPI(url,element,key) {
            $.ajax({
                method: "GET",
                url: url,
                success: function (res) {
                    let html= ` <option>Lựa chọn....</option>`
                    html += res.data.map(function (v, k) {
                        return `
                        <option value="${v.id}">${v.name}</option>
                        `
                    })
                    $(`select#${element}`+key).attr('disabled', false)
                    $(`select#${element}`+key).html(html)
                    AIZ.plugins.bootstrapSelect('refresh');

                }
            })
        }


    </script>
@endsection
@endsection
