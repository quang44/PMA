@extends('backend.layouts.app')

@section('content')

<div class="row">
    <div class="col-lg-9 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('Thông tin đại lý')}}</h5>
            </div>

            <form action="{{ route('affiliate.depot.update', $user->id) }}" method="POST">
<!--                <input name="_method" type="hidden" value="PATCH">-->
            	@csrf
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="name">{{translate('Name')}}</label>
                        <div class="col-sm-9">
                            <input type="text" autocomplete="off" placeholder="{{translate('Name')}}" id="name" name="name" value="{{ $user->name }}" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="email">{{translate('Email')}}</label>
                        <div class="col-sm-9">
                            <input type="text" autocomplete="off" placeholder="{{translate('Email')}}" id="email" name="email" value="{{ $user->email }}" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="mobile">{{translate('Phone')}}</label>
                        <div class="col-sm-9">
                            <input type="text" autocomplete="off" placeholder="{{translate('Phone')}}" id="phone" name="phone" value="{{ $user->phone }}" class="form-control" required>
                        </div>
                    </div>
                    <div class="address  ">
                        <input type="hidden" name="addresses" id="address_id">
                            <div class="row content0">

                                <div class="col-4 form-group">
                                    <label class="col-from-label " for="email">{{translate('Province')}} <span
                                            class="text-danger">*</span> </label>
                                    <select name="city" id="city0" data-city=0" required
                                            class="form-control aiz-selectpicker city"
                                            data-live-search="true">
                                        <option>Lựa chọn thành phố</option>
                                        @foreach($provinces as $city)
                                            @if($user->address_one!=null &&  $user->address_one->province->id==$city->id)
                                                @php $province_id=$city->id @endphp
                                            @endif
                                            <option
                                                {{$user->address_one!=null && $user->address_one->province->id==$city->id?'selected':''}} value="{{$city->id}}">{{$city->name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-4 form-group">
                                    <label class="col-from-label " for="email">{{translate('District')}} <span
                                            class="text-danger">*</span> </label>
                                    @php $districts=\App\Models\District::query()->where('province_id',$province_id)->get()  @endphp
                                    <select name="district" id="district" data-district="0"  data-selected-text-format="count" data-live-search="true" required
                                            class="form-control aiz-selectpicker district">
                                        <option>Lựa chọn {{translate('District')}}</option>
                                        @foreach($districts as $district)
                                            @if($user->address_one!=null && $user->address_one->district->id==$district->id)
                                                @php $district_id=$district->id @endphp
                                            @endif
                                            <option
                                                {{$user->address_one!=null && $user->address_one->district->id==$user->address_one->district->id?'selected':''}} value="{{$district->id}}">
                                                <a href="">    {{$district->name}}</a>
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-4 form-group">
                                    <label class="col-from-label " for="email">{{translate('Ward')}} <span
                                            class="text-danger">*</span> </label>
                                    @php $wards=\App\Models\Ward::query()->where('district_id',$district_id)->get()  @endphp
                                    <select name="ward" id="ward" data-ward="0"
                                            class="form-control  aiz-selectpicker ward"  data-selected-text-format="count" data-live-search="true" required>
                                        <option>Lựa chọn {{translate('Ward')}}</option>
                                        @foreach($wards as $ward)
                                            <option
                                                {{$user->address_one->ward!=null && $user->address_one->ward->id==$ward->id?'selected':''}} value="{{$ward->id}}">{{$ward->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="address">{{translate('Address')}}</label>
                        <div class="col-sm-9">
                            <input type="text" autocomplete="off" placeholder="{{translate('Address')}}" id="address"
                                   name="address" value="{{old('address',$user->address)}}" class="form-control">

                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="password">{{translate('Password')}}</label>
                        <div class="col-sm-9">
                            <input type="password" autocomplete="off" placeholder="{{translate('Password')}}" id="password" name="password" class="form-control">
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
