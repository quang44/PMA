@extends('backend.layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{translate('Agent Update')}}</h5>
                </div>

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

                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="depot">{{translate('Depot')}}</label>
                            <div class="col-sm-9">
                                <select name="depot" id="depot" class="form-control aiz-selectpicker"
                                        data-selected-text-format="count" data-live-search="true" >
                                    <option>Chọn Tổng kho</option>
                                    @foreach($depots as $depot)
                                        <option
                                            {{$user->user_agent!=null&&$user->user_agent->provider_id==$depot->id?'selected':''}} value="{{$depot->id}}">{{$depot->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="depot">{{translate('Agent')}}</label>
                            <div class="col-sm-9">

                                <select name="agent" id="agent" class="form-control " data-selected-text-format="count"
                                        data-live-search="true" >
                                </select>
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

        function getAgent(idDepot ={{$user->user_agent!=null?$user->user_agent->provider_id:'0'}}) {
            CallApi("{{url('')}}/api/v2/agent-by-depot/" + idDepot, 'agent',{{$user->provider_id}})
        }

        getAgent()

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
            $(`select`).addClass('aiz-selectpicker')
        }


    </script>

@endsection


@endsection
