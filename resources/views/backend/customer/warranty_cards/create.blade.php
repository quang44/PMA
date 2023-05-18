@extends('backend.layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-12 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{translate('Add warranty card')}}</h5>
                </div>

                <form action="{{ route('warranty_card.store')}}" method="POST">
                    @csrf
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
                            <label class="col-sm-3 col-from-label" for="name">{{translate('Warranty code')}} <span
                                    class="text-danger"> *</span> </label>
                            <div class="col-sm-9">
                                <input type="text" name="warranty_code" class="form-control" required>
                                @error('warranty_code')
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

                        <div class="address  row ">
                            <div class="col-3 d-flex align-items-center">
                                <label class=" col-from-label" for="email">Địa chỉ </label>
                            </div>
                            <div class="col-9 row">
                                <div class="col-4 form-group">
                                    <label class="col-from-label " for="email">{{translate('Province')}}</label>
                                    <select name="province_id" id="city0"  data-city="0" class="form-control aiz-selectpicker city"
                                            data-selected-text-format="count"
                                            data-live-search="true">
                                        <option>Lựa chọn thành phố</option>
                                        @foreach($provinces as $city)
                                            <option  data-coordinate="<?= $city->latlng ?>"  value="{{$city->id}}">{{$city->name}}</option>
                                        @endforeach
                                    </select>

                                </div>
                                <div class="col-4 ">
                                    <div class="form-group ">
                                        <label class=" col-from-label" for="district">{{translate('District')}}</label>
                                        <select name="district_id" data-district="0"  id="district0"  class="district form-control aiz-selectpicker"
                                                data-selected-text-format="count" data-live-search="true" disabled>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-4 form-group">
                                    <div class="form-group ">
                                        <label class="" for="district">{{translate('Ward')}}</label>
                                        <select name="ward_id" id="ward0"   class="ward form-control aiz-selectpicker"
                                                data-selected-text-format="count" data-live-search="true" disabled>
                                        </select>

                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class=" form-group row">
                            <label class="col-sm-3 col-from-label" for="email">Vị trí <span
                                    class="text-danger"> *</span> </label>
                                <div class="col-sm-9">
                                    <input type="hidden" id="map-lat" class="form-control" name="lat" value="<?php // echo $model->getOldAttribute('lat') ?>">
                                    <input type="hidden" id="map-long" class="form-control" name="long" placeholder="<?php // echo $model->getOldAttribute('long') ?>">
                                    <div style="width: 850px; height: 400px;" id="map-canvas"></div>
                                    <div class="help-block"></div>
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
                                @error('product')
                                <div class="" style="color: red">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-3 ">
                                <label class=" col-from-label" for="district">{{translate('Color')}}</label>
                                <select name="color[]" data-color="0" id="color0"
                                        class="color form-control aiz-selectpicker "
                                        data-selected-text-format="count"
                                        data-live-search="true"
                                        required
{{--                                        disabled--}}
                                >
                                    <option value="">Chọn màu</option>
                                @foreach($colors as $key =>$color)
                                        <option value="{{$key}}">{{$color}}</option>
                                    @endforeach
                                </select>
                                @error('color')
                                <div class="" style="color: red">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-1 form-group">
                                <label class="" for="quantity">{{translate('Quantity')}}</label>
                                <input type="number" min="0" name="qty[]" id="quantity0" data-qty="0"
                                       class="quantity form-control" required >
                            </div>
                            <div class="form-group col-4 ">
                                <label class="col-from-label d-sm-flex justify-content-center"
                                       for="image">{{translate('Image')}}</label>
                                <div class="input-group" data-toggle="aizuploader"    data-multiple="true"  data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="image[]" class="selected-files" >
                                </div>
                                <div class="file-preview box sm">
                                </div>
{{--                                  <input type="hidden" id="image0" name="image[]">--}}

{{--                                <div class="box sm d-sm-flex justify-content-center" id="gallery0">--}}
{{--                                    <a class="a-key image0" data-key="0" href="">--}}
{{--                                        <img class="image0 w-50px input-group-lg" src="{{uploaded_asset(275)}}"--}}
{{--                                             alt="image" req>--}}
{{--                                    </a>--}}
{{--                                </div>--}}
                            </div>
                            <div class="col-1  align-items-center" style="display: flex">
                                <a href="javascript:;" class="badge btn btn-info addCard" onclick="addCard()">+</a>
                            </div>


                        </div>

                        <div id="showWarrantyCard"></div>



                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php
    $lat = 21.05;
    $lng = 105.79944;
    ?>

@section('script')
    <link rel="stylesheet" type="text/css" href="https://js.api.here.com/v3/3.1/mapsjs-ui.css" />
    <script type="text/javascript" src="https://js.api.here.com/v3/3.1/mapsjs-core.js"></script>
    <script type="text/javascript" src="https://js.api.here.com/v3/3.1/mapsjs-service.js"></script>
    <script type="text/javascript" src="https://js.api.here.com/v3/3.1/mapsjs-ui.js"></script>
    <script type="text/javascript" src="https://js.api.here.com/v3/3.1/mapsjs-mapevents.js"></script>
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
                },
                error:function (e) {
                    console.log(e)
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

        {{--$(document).on('blur', '.quantity', function () {--}}
        {{--    let key = $(this).attr('data-qty');--}}
        {{--    let value = $('#quantity' + key).val();--}}
        {{--    let product = $(`#product${key} option:selected`).val();--}}
        {{--    let variant = $(`#color${key}  option:selected`).attr('data-name');--}}
        {{--    CallAPI(`{{url('')}}/api/v2/qty-by-color/` + product + '?variant=' + variant + '&qty=' + value)--}}
        {{--})--}}


        {{--$(document).on('change', 'select.product', function () {--}}
        {{--    let key = $(this).attr('data-product')--}}
        {{--    disable(true,key)--}}
        {{--    let value = $('#product' + key).val()--}}
        {{--    CallAPI(`{{url('')}}/api/v2/color-by-product/` + value, 'color', key)--}}
        {{--})--}}


        {{--function disable(check,key){--}}
        {{--    let html = ` <option>Lựa chọn....</option>`--}}
        {{--    $('select#color' + key).html(html)--}}
        {{--    $('#quantity' + key).val('')--}}
        {{--    $('#quantity' + key).attr('disabled',check)--}}
        {{--    $('.image' + key).attr('src',"{{uploaded_asset(275)}}")--}}
        {{--}--}}

        {{--function disable_color(check,key){--}}
        {{--    $('#quantity' + key).val('')--}}
        {{--    $('#quantity' + key).attr('disabled',check)--}}
        {{--    $('.image' + key).attr('src',"{{uploaded_asset(275)}}")--}}
        {{--}--}}


        {{--let dataArr = []--}}
        {{--let ssSelected = []--}}
        {{--$(document).on('change', 'select.color', function () {--}}
        {{--    let check = true--}}
        {{--    let key = $(this).attr('data-color')--}}
        {{--    let product = $(`#product${key} option:selected`).val();--}}
        {{--    let variant = $(`#color${key} option:selected`).attr('data-name')--}}
        {{--    let option = $(`#color${key} option:selected`).attr('id')--}}
        {{--     let data=dataArr--}}
        {{--    if (data != null) {--}}
        {{--            if (dataArr.find((value) => value.product == product && value.color==variant   )) {--}}
        {{--                let check = false--}}
        {{--                ssSelected=ssSelected.filter((val)=>val.key==key && val.product==product)--}}
        {{--                // console.log(ssSelected)--}}
        {{--                // ssSelected.map(function (v,k) {--}}
        {{--                //     $(`select#color${v.key} #${v.option}`).prop('selected',true)--}}
        {{--                // })--}}

        {{--                return   AIZ.plugins.notify('warning', 'Sản phẩm được chọn đã tồn tại vui lòng chọn sản phẩm khác');--}}
        {{--            } else {--}}
        {{--                ssSelected.push({key:key,product:product,option:option})--}}
        {{--                console.log(ssSelected)--}}
        {{--                let check = true--}}
        {{--                const Number = (element) => (element.color != variant && element.product == product) && (element.key==key) ;--}}
        {{--                let index=dataArr.findIndex(Number);--}}
        {{--                if(index>=0){--}}
        {{--                    let checkindex = dataArr.findIndex((val)=>val.color != variant  && val.key==key )--}}
        {{--                     dataArr = dataArr.filter(function(value, index){--}}
        {{--                        return index !=checkindex;--}}
        {{--                    });--}}
        {{--                }--}}
        {{--                dataArr.push({--}}
        {{--                    product: product,--}}
        {{--                    color: variant,--}}
        {{--                    key:key--}}
        {{--                })--}}
        {{--            }--}}
        {{--    }--}}
        {{--    if (check == true) {--}}
        {{--     return    CallAPI(`{{url('')}}/api/v2/product_by_variant/` + product + `?variant=${variant}`, 'image', key)--}}
        {{--    }--}}
        {{--    // console.log(dataArr)--}}
        {{--})--}}


            $(document).on('change','select.city', function () {
                let key= $(this).attr('data-city')
                let value = $('#city'+key).val()
                API(`{{url('')}}/api/v2/districts-by-province/` + value,'district',key)
                 var latlng = jQuery(this).find(':selected').data('coordinate');
            console.log(latlng);
            moveMapTo(window.map, latlng.split(',')[0], latlng.split(',')[1])
            $('#map-lat').val(latlng.split(',')[0]);
            $('#map-long').val(latlng.split(',')[1]);
            })

        $(document).on('change','select.district', function () {
            let key= $(this).attr('data-district')
            let value = $('#district'+key).val()
            API(`{{url('')}}/api/v2/wards-by-district/` + value,'ward',key)
            var latlng = jQuery(this).find(':selected').data('coordinate');
            console.log(latlng);
            moveMapTo(window.map, latlng.split(',')[0], latlng.split(',')[1])
            $('#map-lat').val(latlng.split(',')[0]);
            $('#map-long').val(latlng.split(',')[1]);
        })

        $(document).on('change','select.ward', function () {
            var latlng = jQuery(this).find(':selected').data('coordinate');
            console.log(latlng);
            moveMapTo(window.map, latlng.split(',')[0], latlng.split(',')[1])
            $('#map-lat').val(latlng.split(',')[0]);
            $('#map-long').val(latlng.split(',')[1]);
        })

        function  API(url,element,key) {
            $.ajax({
                method: "GET",
                url: url,
                success: function (res) {
                    let html= ` <option>Lựa chọn....</option>`
                    html += res.data.map(function (v, k) {
                        return `
                        <option value="${v.id}" data-coordinate="${v.latlng}" >${v.name}</option>
                        `
                    })
                    $(`select#${element}`+key).attr('disabled', false)
                    $(`select#${element}`+key).html(html)
                    AIZ.plugins.bootstrapSelect('refresh');

                }
            })
        }




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


    <script type="text/javascript">
        var igl = 0;
        var arrmarker = new Array();

        var platform = new H.service.Platform({
            'apikey': 'IXaetlCntXwtUCqEMmvbcaWYtsD8aSH1tfpSl-ElCS8' // Make sure to add your own API KEY
        });
        // configure an OMV service to use the `core` endpoint

        var typingTimer;
        var doneTypingInterval = 1000;
        // $(document).on('keyup','input[name="address"]',function () {
            // clearTimeout(typingTimer);
            // typingTimer = setTimeout(doneTyping, doneTypingInterval);
            // var searchTerm = $(this).val();
            // var geocoder = platform.getGeocodingService();
            // console.log(geocoder)
            // geocoder.geocode(
            //     {
            //         searchText: searchTerm
            //     },
            //     function(result) {
            //         console.log(result)
            //         var locations = result.Response.View[0].Result;
            //         var position = {
            //             lat: locations[0].Location.DisplayPosition.Latitude,
            //             lng: locations[0].Location.DisplayPosition.Longitude
            //         };
            //         console.log(position)
            //         // maps.setCenter(position);
            //         // var marker = new H.maps.Marker(position);
            //
            //         // maps.addObject(marker);
            //     },
            //     function(error) {
            //         alert(error);
            //     }
            // );
        // })

        // $('input[name="address"]').on('keydown', function () {
        //     clearTimeout(typingTimer);
        // });

        // function doneTyping () {
        //     var searchTerm = $('input[name="address"]').val();
        //     console.log(searchTerm);
        //     var geocoder = platform.getGeocodingService();
        //     geocoder.geocode(
        //         {
        //             searchText: searchTerm,
        //             jsonattributes: 1
        //         },
        //         onSuccess
        //     );
        // }
        // function onSuccess(result) {
        //     var locations = result.response.view[0].result;
        //     map.setCenter({ lat: locations[0].location.displayPosition.latitude, lng: locations[0].location.displayPosition.longitude });
        // }

        // function onError(error) {
        //     alert('Sorry, we could not find your location');
        // }






        function switchMapLanguage(map, platform) {
            // Create default layers
            let defaultLayers = platform.createDefaultLayers({
                lg: 'vi'
            });
            // Set the normal map variant of the vector map type
            map.setBaseLayer(defaultLayers.vector.normal.map);

            // Display default UI components on the map and change default
            // language to simplified Chinese.
            // Besides supported language codes you can also specify your custom translation
            // using H.ui.i18n.Localization.
            var ui = H.ui.UI.createDefault(map, defaultLayers);
            // Remove not needed settings control
            ui.removeControl('mapsettings');
        }

        function moveMapTo(map, lat, lng) {
            map.setCenter({
                lat: lat,
                lng: lng
            });
            map.setZoom(15);
            makeMarker(map, lat, lng);
        }

        function setUpClickListener(map) {
            map.addEventListener('tap', function(evt) {
                //get lat lng click
                var coord = map.screenToGeo(evt.currentPointer.viewportX,
                    evt.currentPointer.viewportY);
                var LocationOfMarker = {
                    lat: Math.abs(coord.lat.toFixed(4)),
                    lng: Math.abs(coord.lng.toFixed(4))
                };
                makeMarker(map, coord.lat.toFixed(4), coord.lng.toFixed(4));
                jQuery('#map-lat').val(Math.abs(coord.lat.toFixed(4)));
                jQuery('#map-long').val(Math.abs(coord.lng.toFixed(4)));
            });
        }

        function makeMarker(map, lat, lng) {
            var LocationOfMarker = {
                lat: lat,
                lng: lng
            };
            var svgIcon = '<svg width="24" height="24" ' +
                'xmlns="http://www.w3.org/2000/svg">' +
                '<rect stroke="white" fill="#1b468d" x="1" y="1" width="22" ' +
                'height="22" /><text x="12" y="18" font-size="12pt" ' +
                'font-family="Arial" font-weight="bold" text-anchor="middle" ' +
                'fill="white">N</text></svg>';

            // Create a marker icon from an image URL:
            var icon = new H.map.Icon(svgIcon, {
                size: {
                    w: 20,
                    h: 25
                }
            });
            // Create a marker using the previously instantiated icon:
            i = window.igl;
            arrmarker[i] = new H.map.Marker(LocationOfMarker, {
                icon: icon,
            });
            map.addObject(arrmarker[i]);
            $('#latlng').val('' + lat + ',' + lng);
            // console.log(i);
            if (i - 1 >= 0) {
                map.removeObject(arrmarker[i - 1]);
            }
            window.igl = i + 1;
        }
        // https://www.google.com/maps/dir/168 Ngọc Khánh, Ba Đình, Hà Nội, Việt Nam?hl=vi-VN
        // https://www.google.com/maps/dir//21.0250595,105.7484402?hl=vi-VN

        /**
         * Boilerplate map initialization code starts below:
         */

        var defaultLayers = platform.createDefaultLayers();
        var LocationOfMarker = {
            lat: <?= $lat ?>,
            lng: <?= $lng ?>
        };
        //Step 2: initialize a map - this map is centered over Europe
        var map = new H.Map(document.getElementById('map-canvas'),
            defaultLayers.vector.normal.map, {
                center: LocationOfMarker,
                zoom: 1,
                type: 'base',
                pixelRatio: window.devicePixelRatio || 1
            });

        // $('.changemap').change(function() {
        //     latlng = $(this).find('option:selected').attr('latlng');
        //     tg = latlng.split(',');
        //     $('#latlng').val(latlng);
        //     moveMapTo(map, tg[0], tg[1]);
        // })
        // add a resize listener to make sure that the map occupies the whole container
        window.addEventListener('resize', () => map.getViewPort().resize());

        //Step 3: make the map interactive
        // MapEvents enables the event system
        // Behavior implements default interactions for pan/zoom (also on mobile touch environments)
        var behavior = new H.mapevents.Behavior(new H.mapevents.MapEvents(map));

        // Create the default UI components
        var ui = H.ui.UI.createDefault(map, defaultLayers);

        // Now use the map as required...
        window.onload = function() {
            moveMapTo(map, '<?= $lat ?>', '<?= $lng ?>');
        }
        setUpClickListener(map);
        switchMapLanguage(map, platform);
    </script>

@endsection

@endsection
