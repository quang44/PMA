@extends('backend.layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{translate('Edit Account')}}</h5>
                </div>
                @php $depot_id=null @endphp

                <form action="{{ route('customers.update',$user->id) }}" method="POST">

                    <input name="_method" type="hidden" value="PUT">

                    @csrf
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="name">{{translate('Name')}}</label>
                            <div class="col-sm-9">
                                <input type="text" placeholder="{{translate('Name')}}" id="name" name="name"
                                       value="{{ old('name',$user->name) }}" class="form-control" required>
                                @error('name')
                                <div class="" style="color: red">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="email">{{translate('Email')}}</label>
                            <div class="col-sm-9">
                                <input type="email" placeholder="{{translate('Email')}}" id="name" name="email"
                                       value="{{ old('email',$user->email) }}" class="form-control" required>
                                @error('email')
                                <div class="" style="color: red">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">

                            <div class="form-group col-4">
                                <label for="city-dd">{{translate('Province')}}/{{translate('City')}}</label>
                                <select id="city" name="province" class="form-control aiz-selectpicker"
                                        data-selected-text-format="count" data-live-search="true">
                                    <option>Chọn tỉnh thành phố</option>
                                    @foreach($province as $city)
                                        <option
                                            {{$user->address_one!=null&&$user->address_one->province->id==$city->id?'selected':''}} value="{{$city->id}}">{{$city->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-4">
                                <label for="city-dd">{{translate('District')}}</label>
                                <select id="district" name="district" class="form-control aiz-selectpicker"
                                        data-selected-text-format="count" data-live-search="true">
                                    @foreach($districts as $district)
                                        @if($user->address_one!=null&&$user->address_one->province->id==$district->province_id)
                                            <option
                                                {{$user->address_one!=null&&$user->address_one->district->id==$district->id?'selected':''}} value="{{$district->id}}">{{$district->name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-4">
                                <label for="city-dd">{{translate('Ward')}}</label>
                                <select id="ward" name="ward" class="form-control aiz-selectpicker" data-selected-text-format="count"
                                        data-live-search="true">
                                    @foreach($wards as $ward)
                                        @if($user->address_one!=null&&$user->address_one->district->id==$ward->district_id)
                                            <option
                                                {{$user->address_one!=null&&$user->address_one->ward->id==$ward->id?'selected':''}} value="{{$ward->id}}">{{$ward->name}}</option>
                                        @endif
                                    @endforeach
                                </select>

                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="mobile">{{translate('Phone')}}</label>
                            <div class="col-sm-9">
                                <input type="number" placeholder="{{translate('Phone')}}" id="phone" name="phone"
                                       value="{{ old('phone',$user->phone) }}" class="form-control" required>
                                @error('phone')
                                <div class="" style="color: red">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>


                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="email">{{translate('Password')}}</label>
                            <div class="col-sm-9">
                                <input type="password" placeholder="" id="password" name="password" value=""
                                       class="form-control">
                                @error('password')
                                <div class="" style="color: red">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="depot">{{translate('Depot')}}</label>
                            <div class="col-sm-9">
                                <select name="depot" id="depot" class="form-control aiz-selectpicker"
                                        data-selected-text-format="count" data-live-search="true">
                                    <option>Chọn Tổng kho </option>

                                    @foreach($depots as $depot)
                                        @if($user->user_agent!=null&&$user->user_agent->provider_id==$depot->id)
                                            @php $depot_id= $depot->id @endphp
                                            @endif
                                        <option
                                            {{$user->user_agent->id==$depot->id?'selected':''}} value="{{$depot->id}}">{{$depot->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

{{--                        <div class="form-group row">--}}

{{--                            <label class="col-sm-3 col-from-label " for="depot">{{translate('Agent')}}</label>--}}
{{--                            <div class="col-sm-9">--}}
{{--                                @php $agents=\App\Models\User::where('provider_id',$depot_id)->whereNotNull('provider_id')->get() ; @endphp--}}
{{--                                <select name="agent" id="agent" class="form-control aiz-selectpicker"--}}
{{--                                        data-selected-text-format="count"--}}
{{--                                        data-live-search="true">--}}
{{--                                    @if($agents!=null)--}}
{{--                                    @foreach($agents as $agent)--}}
{{--                                    <option value="{{$agent->id}}">{{$agent->name}}</option>--}}
{{--                                    @endforeach--}}
{{--                                @endif--}}
{{--                                </select>--}}
{{--                            </div>--}}

{{--                        </div>--}}


                        <div class="form-group mb-0 text-right">
                            @if($user->status==0)
                                <a href="#" class="btn btn-sm btn-success " onclick="confirm_lever_up('{{route('customers.updateToAgent', encrypt($user->id))}}');" title="{{ translate('Approve') }}">
                                    {{translate('Upgrade to agent')}}
                                </a>
                                <a href="#" class="btn btn-sm btn-success " onclick="confirm_lever_up('{{route('customers.updateToDepot', encrypt($user->id))}}');" title="{{ translate('Approve') }}">
                                    {{translate('Upgrade to depot')}}
                                </a>
                                @endif

                            <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@section('modal')
    @include('modals.confirm_banned_modal')
@endsection


@section('script')
    <script !src="">


        $('#city').change(function () {
            let city = $(this).find('option:selected');
            CallApi("{{url('')}}/api/v2/districts-by-province/" + city.val(), 'district')
        })

        $('#district').change(function () {
            let district = $(this).find('option:selected');
            CallApi("{{url('')}}/api/v2/wards-by-district/" + district.val(), 'ward')
        })

        $('#depot').change(function () {
            let depot = $(this).find('option:selected');
            CallApi("{{url('')}}/api/v2/agent-by-depot/" + depot.val(), 'agent')
        })

        function confirm_lever_up(url)
        {
            $('#confirm-leverup').modal('show', {backdrop: 'static'});
            document.getElementById('confirmationleverup').setAttribute('href' , url);
        }


        function CallApi(url, element, check) {
            $.ajax({
                url: url,
                method: "GET",
                success: function (res) {
                    $(`#${element}`).attr('disabled', false)
                    HandleRegion(res, element, check)
                }, error: function (error) {
                    console.log(error)
                }
            })
        }

        function HandleRegion(data, element, check) {
            let html = ` <option value="-1">Lựa chọn</option>`;
            html += data.data.map(function (value, key) {
                return `
                    <option ${check == value.id ? 'selected' : ''} value="${value.id}">${value.name}</option>
                    `
            })

            $(`#${element}`).html(data.result == false ? `<option>không tìm thấy dữ liệu</option>` : html)

            AIZ.plugins.bootstrapSelect('refresh');

        }


    </script>

@endsection


@endsection
