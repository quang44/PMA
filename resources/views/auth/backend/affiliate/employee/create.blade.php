@extends('backend.layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{translate('Thông tin đại lý')}}</h5>
                </div>

                <form class="form-horizontal" action="{{ route('affiliate.employee.create') }}" method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="name">{{translate('Name')}} <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" autocomplete="off" placeholder="{{translate('Name')}}" id="name"
                                     value="{{old('name')}}"  name="name" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="email">{{translate('Email')}} <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="email" autocomplete="off" placeholder="{{translate('Email')}}" id="email"
                                       value="{{old('email')}}"    name="email" class="form-control" required>
                            </div>
                        </div>



                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label " for="email">{{translate('Depot')}}</label>
                            <div class="col-sm-9">
                                <select name="depot" class="form-control aiz-selectpicker"
                                        data-selected-text-format="count" data-live-search="true">
                                    @foreach($employee as $item)
                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="address  row">
                            <div class="col-4 form-group">
                                <label class="col-from-label " for="email">{{translate('Province')}}</label>
                                <select name="city[]" id="city0"  data-city="0" class="form-control aiz-selectpicker city"
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
                                    <select name="district[]" data-district="0"  id="district0"  class="district form-control aiz-selectpicker"
                                            data-selected-text-format="count" data-live-search="true" disabled>
                                    </select>
                                </div>
                            </div>
                            <div class="col-3 form-group">
                                <div class="form-group ">
                                    <label class="" for="district">{{translate('Ward')}}</label>
                                    <select name="ward[]" id="ward0"  class="ward form-control aiz-selectpicker"
                                            data-selected-text-format="count" data-live-search="true" disabled>
                                    </select>

                                </div>
                            </div>
                            <div class="col-1  align-items-center" style="display: flex">
                                <a href="javascript:;"
                                   class="badge badge-primary btn btn-primary addAddress">+</a>
                            </div>
                        </div>


                        <div class=" form-group" id="showAddress"></div>


                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="mobile">{{translate('Phone')}} <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" autocomplete="off" placeholder="{{translate('Phone')}}" id="phone"
                                       name="phone" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="password">{{translate('Password')}} <span
                                    class="text-danger">*</span></label>
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

    <script  type="text/javascript">
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

       function onDelete(id){
        $('.content'+id).remove()
       }


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
