@extends('backend.layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{translate('Agent Update')}}</h5>
                </div>
@php  $province_id=null; $district_id=null; @endphp
                <form class="form-horizontal" action="{{ route('affiliate.employee.update',$user->id) }}" method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="name">{{translate('Name')}} <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" autocomplete="off" placeholder="{{translate('Name')}}" id="name"
                                       value="{{old('name',$user->name)}}" name="name" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="email">{{translate('Email')}} <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="email" autocomplete="off" placeholder="{{translate('Email')}}" id="email"
                                       value="{{old('email',$user->email)}}" name="email" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="mobile">{{translate('Phone')}} <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" autocomplete="off" placeholder="{{translate('Phone')}}" id="phone"
                                       name="phone" value="{{old('phone',$user->phone)}}" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="password">{{translate('Password')}} <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="password" autocomplete="off" placeholder="{{translate('Password')}}"
                                       id="password" name="password" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label " for="email">{{translate('Depot')}}</label>
                            <div class="col-sm-9">
                                <select name="depot" class="form-control aiz-selectpicker"
                                        data-selected-text-format="count" data-live-search="true">
                                    @foreach($depots as $item)
                                        <option
                                            {{$user->provider_id==$item->id?'selected':''}} value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="address  ">
                            <input type="hidden" name="addresses" id="address_id">
                            @foreach($user->addresses as $key=> $address)
                                <div class="row content{{$key}}">
                                    <input type="hidden" name="address_id[]" value="{{$address->id}}">

                                    <div class="col-4 form-group">
                                        <label class="col-from-label " for="email">{{translate('Province')}} <span
                                                class="text-danger">*</span> </label>
                                        <select name="city[]" id="city{{$key}}" data-city="{{$key}}" required
                                                class="form-control aiz-selectpicker city"
                                                data-live-search="true">
                                            <option>Lựa chọn thành phố</option>
                                            @foreach($provinces as $city)
                                                @if($address->province->id==$city->id)
                                                    @php $province_id=$city->id @endphp
                                                    @endif
                                                <option
                                                    {{$address->province->id==$city->id?'selected':''}} value="{{$city->id}}">{{$city->name}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-4 form-group">
                                        <label class="col-from-label " for="email">{{translate('District')}} <span
                                                class="text-danger">*</span> </label>
                                        @php $districts=\App\Models\District::query()->where('province_id',$province_id)->get()  @endphp
                                        <select name="district[]" id="district{{$key}}" data-district="{{$key}}"  data-selected-text-format="count" data-live-search="true" required
                                                class="form-control aiz-selectpicker district">
                                            <option>Lựa chọn {{translate('District')}}</option>
                                            @foreach($districts as $district)
                                                @if($address->district->id==$district->id)
                                                    @php $district_id=$district->id @endphp
                                                @endif
                                                <option
                                                    {{$address->district->id==$district->id?'selected':''}} value="{{$district->id}}">
                                                    <a href="">    {{$district->name}}</a>
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-3 form-group">
                                        <label class="col-from-label " for="email">{{translate('Ward')}} <span
                                                class="text-danger">*</span> </label>
                                        @php $wards=\App\Models\Ward::query()->where('district_id',$district_id)->get()  @endphp
                                        <select name="ward[]" id="ward{{$key}}" data-ward="{{$key}}"
                                                class="form-control  aiz-selectpicker ward"  data-selected-text-format="count" data-live-search="true" required>
                                            <option>Lựa chọn {{translate('Ward')}}</option>
                                            @foreach($wards as $ward)
                                                <option
                                                    {{$address->ward!=null && $address->ward->id==$ward->id?'selected':''}} value="{{$ward->id}}">{{$ward->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-1  align-items-center" style="display: flex">
                                        @if($key!=0)
                                            <a href="javascript:;"
                                               class="badge badge-danger btn btn-danger" onclick="onDelete({{$key}} ,{{$address->id}})" >X</a>
                                        @else
                                            <a href="javascript:;"
                                               class="badge badge-primary btn btn-primary addAddress">+</a>

                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class=" form-group" id="showAddress"></div>


                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
                        </div>


                    </div>
                </form>

            </div>
        </div>
    </div>
@section('script')

    <script type="text/javascript">
        $(document).on('click', '.addAddress', function () {
            $.ajax({
                type:"GET",
                url:'{{route('affiliate.kol.combinations')}}',
                success:function (res) {
                    $('#showAddress').append(res)
                    AIZ.plugins.bootstrapSelect('refresh');
                }
            })
        })
  let dataAddress=[]
        function onDelete(key,id) {
            dataAddress.push(id)
            $('.content' + key).remove()
            $('#address_id').val(dataAddress)
        }


        $(document).on('change', 'select.city', function () {
            let key = $(this).attr('data-city')
            disable(true,key)
            let value = $('#city' + key).val()
            CallAPI(`{{url('')}}/api/v2/districts-by-province/` + value, 'district', key)
        })

        function disable(check,key){
            let html = ` <option>Lựa chọn....</option>`
            $('select#ward' + key).html(html)
            $('select#ward' + key).attr('disabled',check)
        }

        $(document).on('change', 'select.district', function () {
            let key = $(this).attr('data-district')
            disable(true,key)
            let value = $('#district' + key).val()
            CallAPI(`{{url('')}}/api/v2/wards-by-district/` + value, 'ward', key)
        })

        function CallAPI(url, element, key) {
            $.ajax({
                method: "GET",
                url: url,
                success: function (res) {
                    console.log(res)
                    let html = ` <option>Lựa chọn....</option>`
                    html += res.data.map(function (v, k) {
                        return `
                        <option value="${v.id}">${v.name}</option>
                        `
                    })
                    $(`select#${element}` + key).attr('disabled', false)
                    $(`select#${element}` + key).html(html)
                    AIZ.plugins.bootstrapSelect('refresh');


                }
            })
        }

    </script>
@endsection
@endsection
