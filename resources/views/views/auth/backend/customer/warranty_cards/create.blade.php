@extends('backend.layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{translate('Add warranty card')}}</h5>
                </div>

                <form action="{{ route('warranty_card.store')}}" method="POST">
                    @csrf
                    {{--                    @if ($errors->any())--}}
                    {{--                        <div class="alert alert-danger">--}}
                    {{--                            <ul>--}}
                    {{--                                @foreach ($errors->all() as $error)--}}
                    {{--                                    <li>{{ $error }}</li>--}}
                    {{--                                @endforeach--}}
                    {{--                            </ul>--}}
                    {{--                        </div>--}}
                    {{--                    @endif--}}
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="name">{{translate('Worker')}} <span
                                    class="text-danger"> *</span> </label>
                            <div class="col-sm-9">
                                <select name="user_id" class="form-control aiz-selectpicker"
                                        data-selected-text-format="count"
                                        data-live-search="true"
                                >
                                    @foreach($customers as $customer)
                                        <option value="{{$customer->id}}">{{$customer->name}}</option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                <div class="" style="color: red">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="name">{{translate('Customer name')}} <span
                                    class="text-danger"> *</span> </label>
                            <div class="col-sm-9">
                                <input type="text" placeholder="{{translate('Customer name')}}" id="name"
                                       name="user_name"
                                       value="{{ old('user_name') }}" class="form-control" required>
                                @error('user_name')
                                <div class="" style="color: red">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="email">{{translate('Address')}} <span
                                    class="text-danger"> *</span> </label>
                            <div class="col-sm-9">
                                <input type="text" placeholder="{{translate('Address')}}" id="address" name="address"
                                       value="{{ old('address') }}" class="form-control" required>
                                @error('address')
                                <div class="" style="color: red">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="Seri">{{translate('Phone')}}<span
                                    class="text-danger"> *</span> </label>
                            <div class="col-9 orm-group">
                                <input type="number" placeholder="{{translate('Phone')}}" id="phone" name="phone"
                                       value="{{ old('phone') }}" class="form-control" required>
                                @error('phone')
                                <div class="" style="color: red">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="warranty_card  row">
                            <div class="col-3 form-group">
                                <label class="col-from-label text-center" for="email">{{translate('Product')}}</label>
                                <select name="product[]" id="product0" data-product="0"
                                        class="form-control aiz-selectpicker product" data-selected-text-format="count"
                                        data-live-search="true" required>
                                    <option>lựa chọn sản phẩm</option>

                                    @foreach($products as $product)
                                        <option value="{{$product->id}}">{{$product->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-3 ">
                                <label class=" col-from-label" for="district">{{translate('Color')}}</label>
                                <select name="color[]" data-color="0" id="color0"
                                        class="color form-control aiz-selectpicker "
                                        data-selected-text-format="count"
                                        data-live-search="true"
                                        required
                                        disabled>
                                </select>
                            </div>
                            <div class="col-2 form-group">
                                <label class="" for="quantity">{{translate('Maximum quantity')}}</label>
                                <input type="number" min="0" name="qty[]" id="quantity0" data-qty="0"
                                       class="quantity form-control" required disabled>
                            </div>
                            <div class="form-group col-3 ">
                                <label class="col-from-label d-sm-flex justify-content-center"
                                       for="image">{{translate('Image')}}</label>
                                <input type="hidden" id="image0" name="image[]">
                                <div class="box sm d-sm-flex justify-content-center" id="gallery0">
                                    <a class="a-key image0" data-key="0" href="">
                                        <img class="image0 w-50px input-group-lg" src="{{uploaded_asset(275)}}"
                                             alt="image" req>
                                    </a>
                                </div>
                            </div>
                            <div class="col-1  align-items-center" style="display: flex">
                                <a href="javascript:;" class="badge btn btn-info addCard" onclick="addCard()">+</a>
                            </div>


                        </div>

                        <div id="showWarrantyCard"></div>

                        <div class="form-group mb-3">
                            <label for="name">{{translate('Video URL')}}<span class="text-danger">*</span> </label>
                            <input type="text" name="video_url" class="form-control" required>
                            @error('video_url')
                            <div class="" style="color: red">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="form-group ">
                                <label class="" for="district">{{translate('Warranty code')}}<span
                                        class="text-danger">*</span> </label>
                                <input type="text" name="warranty_code" class="form-control" required>
                            </div>
                            @error('warranty_code')
                            <div class="" style="color: red">{{ $message }}</div>
                            @enderror
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
        $(document).ready(function () {
            sessionStorage.removeItem('color')
        })

        function addCard() {

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: "{{route('warranty_card.combinations')}}",
                success: function (res) {
                    $('#showWarrantyCard').append(res)
                    AIZ.plugins.bootstrapSelect('refresh');
                }
            })
        }

        $(document).on('focus', '.a-key', function (e) {
            let key = $(this).attr('data-key')
            $(`div#gallery` + key).magnificPopup({
                delegate: 'a',
                type: 'image',
                gallery: {
                    enabled: true
                }
            })
        })

        function onDelete(id) {
            $('.content' + id).remove()
        }

        $(document).on('blur', '.quantity', function () {
            let key = $(this).attr('data-qty');
            let value = $('#quantity' + key).val();
            let product = $(`#product${key} option:selected`).val();
            let variant = $(`#color${key}  option:selected`).attr('data-name');
            CallAPI(`{{url('')}}/api/v2/qty-by-color/` + product + '?variant=' + variant + '&qty=' + value)
        })


        $(document).on('change', 'select.product', function () {
            let key = $(this).attr('data-product')
            disable(true,key)
            let value = $('#product' + key).val()
            CallAPI(`{{url('')}}/api/v2/color-by-product/` + value, 'color', key)
        })


        function disable(check,key){
            let html = ` <option>Lựa chọn....</option>`
            $('select#color' + key).html(html)
            $('#quantity' + key).val('')
            $('#quantity' + key).attr('disabled',check)
            $('.image' + key).attr('src',"{{uploaded_asset(275)}}")
        }

        function disable_color(check,key){
            $('#quantity' + key).val('')
            $('#quantity' + key).attr('disabled',check)
            $('.image' + key).attr('src',"{{uploaded_asset(275)}}")
        }


        let dataArr = []
        let ssSelected = []
        $(document).on('change', 'select.color', function () {
            let check = true
            let key = $(this).attr('data-color')
            let product = $(`#product${key} option:selected`).val();
            let variant = $(`#color${key} option:selected`).attr('data-name')
            let option = $(`#color${key} option:selected`).attr('id')
             let data=dataArr
            if (data != null) {
                    if (dataArr.find((value) => value.product == product && value.color==variant   )) {
                        let check = false
                        ssSelected=ssSelected.filter((val)=>val.key==key && val.product==product)
                        // console.log(ssSelected)
                        // ssSelected.map(function (v,k) {
                        //     $(`select#color${v.key} #${v.option}`).prop('selected',true)
                        // })

                        return   AIZ.plugins.notify('warning', 'Sản phẩm được chọn đã tồn tại vui lòng chọn sản phẩm khác');
                    } else {
                        ssSelected.push({key:key,product:product,option:option})
                        console.log(ssSelected)
                        let check = true
                        const Number = (element) => (element.color != variant && element.product == product) && (element.key==key) ;
                        let index=dataArr.findIndex(Number);
                        if(index>=0){
                            let checkindex = dataArr.findIndex((val)=>val.color != variant  && val.key==key )
                             dataArr = dataArr.filter(function(value, index){
                                return index !=checkindex;
                            });
                        }
                        dataArr.push({
                            product: product,
                            color: variant,
                            key:key
                        })
                    }
            }
            if (check == true) {
             return    CallAPI(`{{url('')}}/api/v2/product_by_variant/` + product + `?variant=${variant}`, 'image', key)
            }
            // console.log(dataArr)
        })


        function CallAPI(url = '', element = '', key = '') {
            $.ajax({
                method: "GET",
                url: url,
                success: function (res) {
                    if (element != '') {
                        if (element == 'image') {
                            $('#quantity' + key).attr('disabled', false)
                            $('#quantity' + key).val(res.data.qty)
                            $(`#${element}` + key).val(res.data.image_id)
                            $(`img.${element}` + key).attr('src', res.data.image)
                            $(`a.${element}` + key).attr('href', res.data.image)
                        } else {
                            let html = ` <option>Không có gì được chọn</option>`
                            html += res.data.map(function (v, k) {
                                return `
                        <option value="${v.id}"
                         id="option${k}"
                          check="false"
                         data-name=${v.name}
                         data-content="<span>
                        <span class='size-15px d-inline-block mr-2 rounded border' style='background:${v.code}'></span>
                        <span>${v.name}</span>
                        </span>">
                        </option>
                        `
                            })
                            $(`select#${element}` + key).attr('disabled', false)
                            $(`select#${element}` + key).html(html)
                        }

                    } else {
                        if (res.result == false) {
                            AIZ.plugins.notify('warning', res.message);
                        }

                    }
                    AIZ.plugins.bootstrapSelect('refresh');


                }
            })
        }

    </script>
@endsection

@endsection
