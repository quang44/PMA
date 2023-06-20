@extends('backend.layouts.app')

@section('content')
    <style>
        .remove-attachment {
            display: none;
        }
    </style>
    <div class="row">
        <div class="col-lg-12 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{translate('Info Warranty Card')}} </h5>
                </div>
                <div class="card-body align-content-center">
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="name">Người tạo :</label>
                        <div class="col-sm-9">
                            <span>
                                @if($warranty_card->user)
                                    {{strtoupper($warranty_card->user->name)}}
                                @else
                                    người dùng không tồn tại
                                    @endif
                               </span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="name">{{translate('Customer')}} :</label>
                        <div class="col-sm-9">
                            <span>{{strtoupper($warranty_card->user_name)}}</span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="Seri">{{translate('Phone')}} :</label>
                        <div class="col-sm-9">
                            <span>{{$warranty_card->phone}}</span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="email">{{translate('email')}} :</label>
                        <div class="col-sm-9">
                            <span>{{$warranty_card->email??'Chưa có email'}}</span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="address">{{translate('Address')}} :</label>
                        <div class="col-sm-9">
                            <span>{{$warranty_card->address}}, {{$warranty_card->ward->name??''}}, {{$warranty_card->district->name??''}}, {{$warranty_card->province->name??''}}</span>

                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="email">Vị trí :</label>
                        <div class="col-sm-9">
                            <input type="hidden" id="map-lat" class="form-control" name="User[lat]" value="">
                            <input type="hidden" id="map-long" class="form-control" name="User[long]"
                                   placeholder="<?php // echo $model->getOldAttribute('long') ?>">
                            <div style="width:800px; height:400px;" id="map-canvas"></div>
                            <div class="help-block"></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="email">Ảnh toàn công trình :</label>
                        <div class="col-sm-9 row">
                            @foreach(explode(',',$warranty_card->project_photo) as $index=> $image)
                                <div class="ml-3">
                                    <a class="a-key" data-lightbox="image-gallery" href="{{ static_asset($image) }}">
                                        <img src="{{static_asset($image)}}" alt="" class="h-100px image">
                                    </a>
                                </div>
                            @endforeach
                            {{--                            <a  class="project_photo" href="{{static_asset($warranty_card->project_photo)}}"   data-lightbox="image-gallery">--}}
                            {{--                            <img src="{{static_asset($warranty_card->project_photo)}}" alt="" width="93%">--}}
                            {{--                            </a>--}}
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="Seri">{{translate('Created_at')}} :</label>
                        <div class="col-sm-9">
                            <span>{{convertTime($warranty_card->create_time)}}</span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="Seri">{{translate('Active time')}} / Hủy
                            :</label>
                        <div class="col-sm-9">
                                <span class="text-danger">@if($warranty_card->active_time>0)
                                        {{convertTime($warranty_card->active_time)}}
                                    @else
                                        --
                                    @endif
                                </span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="Seri">{{translate('Warranty code')}}:</label>
                        <div class="col-sm-9">
                         {{$warranty_card->warranty_code}}
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="package_id">{{translate('Accept by')}}
                            :</label>
                        <div class="col-sm-9">
                            <span>
                                        @if($warranty_card->accept_by!=null)
                                    @if($warranty_card->active_user_id!=null && $warranty_card->active_user_id->user_type='admin')
                                        <span class="badge badge-inline badge-success">Admin</span>
                                    @else
                                        <span class="badge badge-inline badge-success">CTV</span>
                                    @endif
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="package_id">{{translate('Status')}} :</label>
                        <div class="col-sm-9">

                            @if($warranty_card->status==0)
                                <span class="badge badge-inline badge-secondary">
                                    {{\App\Utility\WarrantyCardUtility::$aryStatus[\App\Utility\WarrantyCardUtility::STATUS_NEW]}}
                                        </span>
                            @elseif($warranty_card->status==1)
                                <span class="badge badge-inline badge-success">
                                        {{\App\Utility\WarrantyCardUtility::$aryStatus[\App\Utility\WarrantyCardUtility::STATUS_SUCCESS]}}
                                </span>
                            @else
                                <span class="badge badge-inline badge-danger">
                                        {{\App\Utility\WarrantyCardUtility::$aryStatus[\App\Utility\WarrantyCardUtility::STATUS_CANCEL]}}
                                             </span> / lý do :  {{$warranty_card->reason}}
                            @endif


                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="package_id">{{translate('Note')}}
                            :</label>
                        <div class="col-sm-9">
                            <span>{{$warranty_card->note}}</span>
                        </div>
                    </div>

                    <div class="  row">
                        <div class="card-body">
                            <div class="table-responsive">
                        <table class="table aiz-table mb-0">
                            <thead>
                            <tr>
                                <th> Cửa bảo hành </th>
                                <th data-breakpoints="lg">{{translate('Image')}}</th>
{{--                                <th data-breakpoints="lg">{{translate('Video')}}</th>--}}
                                <th data-breakpoints="lg">{{translate('Color')}}</th>
                                <th data-breakpoints="lg">{{translate('Quantity')}}</th>
                                <th data-breakpoints="lg">{{translate('Status')}}</th>
                                <th data-breakpoints="lg">{{translate('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($warranty_card->cardDetail as $key=> $detail)
                                <tr>
                                    <td>{{$detail->product!=null?$detail->product->name:"sản phẩm không tồn tại"}}</td>
                                    <td>
                                        <div class="row" id="gallery{{$key}}">
                                            @foreach(explode(',',$detail->image) as $index=> $image)
                                                <div class="col-3">
                                                    <a class="a-key" data-key="{{$key}}"
                                                       href="{{ static_asset($image) }}">
                                                        <img src="{{static_asset($image)}}" alt=""
                                                             class="h-100px image">
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                    </td>
                                    {{--                                <td>--}}
                                    {{--                                    <div class="row" id="video{{$key}}">--}}
                                    {{--                                        <a  >--}}
                                    {{--                                            <video width="200" height="300px" controls>--}}
                                    {{--                                                <source src="{{ static_asset($detail->video) }}" type="video/mp4">--}}
{{--                                            </video>--}}
{{--                                        </a>--}}
{{--                                    </div>--}}
                                    {{--                                </td>--}}
                                    <td>
                                        @if(!$detail->color)
                                            <span class='size-25px d-inline-block mr-2 bg-danger '>not found</span>
                                        @else
                                            <span class='size-25px d-inline-block mr-2 rounded border'
                                                  style='background:{{$detail->color?$detail->color->code:''}}'></span>
                                            <p>Thời gian bảo hành ({{  timeWarranty($detail->color->warranty_duration)}}
                                                )</p>
                                        @endif
                                    </td>
                                    <td style="position: relative">
                                        @if($detail->status==0)
                                            <i class="las la-edit text-warning c-pointer"
                                               onclick="confirm_edit_qty('{{route('warranty_card.edit_qty',$detail->id)}}',{{$detail->qty}})"
                                               style=" position: absolute;  font-size: 30px;  top: -20px;"></i>
                                        @endif
                                        {{$detail->qty}}
                                    </td>
                                    <td>    @if($detail->status==2)
                                            <span class="text-danger"> Hủy / {{$detail->reason}} </span>
                                        @endif
                                        @if($detail->status==0)
                                            <span class="text-secondary"> Chờ duyệt </span>
                                        @endif

                                        @if($detail->status==1)
                                            <span class="text-secondary"> Đã duyệt </span>
                                        @endif
                                    </td>
                                <td>
                                    @if($warranty_card->status==0 && $detail->status==0)
                                        <a href="javascript:void(0)"
                                           class="btn btn-soft-danger btn-icon btn-circle btn-sm"
                                           onclick="confirm_ban('{{route('warranty_card.ban_detail', encrypt($detail->id))}}' ,2);"
                                           title="{{ translate('Cancel the card') }}">
                                            <i class="las la-credit-card"></i>
                                        </a>
                                        @endif
                                </td>

                            </tr>
                           @endforeach
                            </tbody>
                        </table>
                    </div>
                        </div>
                    </div>

                    @if($warranty_card->status==0 )
                        <a href="javascript:void(0)"
                           class="btn btn-soft-info btn-icon btn-circle btn-sm"
                           onclick="updateCard('{{route('warranty_card.ban', encrypt($warranty_card->id))}}',1);"
                           title="{{ translate('Activate Cards') }}">
                            <i class="las la-credit-card"></i>
                        </a>
                        <a href="javascript:void(0)"
                           class="btn btn-soft-danger btn-icon btn-circle btn-sm"
                           onclick="confirm_ban('{{route('warranty_card.ban', encrypt($warranty_card->id))}}' ,2);"
                           title="{{ translate('Cancel the card') }}">
                            <i class="las la-credit-card"></i>
                        </a>
                    @endif
                </div>

            </div>
        </div>
    </div>



@section('modal')
    @include('modals.confirm_modal')
@endsection


<div class="modal fade" id="confirm-edit">
    <form action="" id="confirmation_edit_qty" method="GET">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h6">Nhập Số lượng</h5>
                    <button type="button" class="close" data-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="number" class="form-control" name="qty" placeholder="Nhập số lượng">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light"
                            data-dismiss="modal">{{translate('cancel')}}</button>
                    <button type="submit" class="btn btn-primary">{{translate('Continue')}}</button>
                </div>
            </div>
        </div>
    </form>
</div>


@endsection

<!-- CSS only -->
@section('script')
    <style>
        .mfp-img {
            /*width: 1920px!important;*/
            height: 1080px !important;
            max-height: inherit !important;
        }

        .lb-image {
            height: auto !important; /* Set chiều cao tối đa */
            width: 800px !important; /* Set chiều rộng tối đa */
        }

        .lb-close {
            position: absolute;
            top: 0;
            right: 240px;
            font-size: 24px;
            color: #fff;
            z-index: 9999;
            cursor: pointer;
        }

    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>



    <link rel="stylesheet" type="text/css" href="https://js.api.here.com/v3/3.1/mapsjs-ui.css"/>
    <script type="text/javascript" src="https://js.api.here.com/v3/3.1/mapsjs-core.js"></script>
    <script type="text/javascript" src="https://js.api.here.com/v3/3.1/mapsjs-service.js"></script>
    <script type="text/javascript" src="https://js.api.here.com/v3/3.1/mapsjs-ui.js"></script>
    <script type="text/javascript" src="https://js.api.here.com/v3/3.1/mapsjs-mapevents.js"></script>
    <script>

        // $(document).on('focus','.project_photo',function () {
        $(document).ready(function () {
            lightbox.option({
                'resizeDuration': 200,
                'wrapAround': true,
                'fitImagesInViewport': false // Không fit ảnh trong khung
            });
        });

        // $('.project_photo').zoomify();
        //     $(`.project_photo`).magnificPopup({
        //         type: 'image',
        //         gallery: {
        //             enabled: true
        //         },
        //         image: {
        //             verticalFit: true
        //         },
        //         zoom:
        //             {
        //                 enabled: true,
        //                 duration: 300 // don't foget to change the duration also in CSS
        //             },
        //     })
        // })

        $(document).on('focus', '.a-key', function (e) {
            let key = $(this).attr('data-key')
            $('img.mfp-img').removeAttr('style')

            $(`div#gallery` + key).magnificPopup({
                delegate: 'a',
                type: 'image',
                gallery: {
                    enabled: true
                },
                image: {
                    verticalFit: true,
                },
                zoom:
                    {
                        enabled: true,
                        duration: 300 // don't foget to change the duration also in CSS
                    },
                // callbacks: {
                //     resize: changeImgSize,
                //     imageLoadComplete:changeImgSize,
                //     change:changeImgSize
                // }
                // callbacks: {
                //     open: function () {
                //         $(".mfp-figure figure").css("cursor", "zoom-in");
                //         $(".mfp-figure figure").zoom({
                //             on: "click",
                //             onZoomIn: function () {
                //                 $(this).css("cursor", "zoom-out");
                //             },
                //             onZoomOut: function () {
                //                 $(this).css("cursor", "zoom-in");
                //             }
                //         });
                //     },
                // }
            })
        })

        function changeImgSize(){
            var img = this.content.find('img');
            img.css('max-height', '100%');
            img.css('width', '100%');
            img.css('max-width', 'auto');
        }

        // var zoom_percent = "100";
        // function zoom(zoom_percent){
        //     $(".mfp-figure figure").click(function(){
        //         switch(zoom_percent){
        //             case "100":
        //                 zoom_percent = "120";
        //                 break;
        //             case "120":
        //                 zoom_percent = "150";
        //                 break;
        //             case "150":
        //                 zoom_percent = "200";
        //                 $(".mfp-figure figure").css("cursor", "zoom-out");
        //                 break;
        //             case "200":
        //                 zoom_percent = "100";
        //                 $(".mfp-figure figure").css("cursor", "zoom-in");
        //                 break;
        //         }
        //         $(this).css("zoom", zoom_percent+"%");
        //     });
        // }




        $(document).on('focus', '.a-video', function (e) {
            let key = $(this).attr('data-key')
            $(`div#video` + key).magnificPopup({
                delegate: 'a',
                gallery: {
                    enabled: true
                }
            })
        })

        $('.image').on('click', function () {
            let img = $(this).attr('src');
            $('#image').attr('src', img)
        })

        function confirm_edit_qty(url, qty) {
            $('#confirm-edit').modal('show', {backdrop: 'static'});
            $('input[name="qty"]').val(qty);
            document.getElementById('confirmation_edit_qty').setAttribute('action', url);
        }

        function confirm_ban(url, status) {
            $('#confirm-ban').modal('show', {backdrop: 'static'});
            document.getElementById('confirmation').setAttribute('action', url + '?status=' + status);
        }


        function updateCard(url, status) {
            $('#confirm-update-bank').modal('show', {backdrop: 'static'});
            document.getElementById('updateCard').setAttribute('href', url + '?status=' + status);
        }

    </script>
    <script type="text/javascript">
        var igl = 0;
        var arrmarker = new Array();

        var platform = new H.service.Platform({
            'apikey': 'IXaetlCntXwtUCqEMmvbcaWYtsD8aSH1tfpSl-ElCS8' // Make sure to add your own API KEY
        });

        function switchMapLanguage(map, platform) {
            // Create default layers
            let defaultLayers = platform.createDefaultLayers({
                lg: 'vi'
            });
            // Set the normal map variant of the vector map type
            map.setBaseLayer(defaultLayers.vector.normal.map);

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


        var defaultLayers = platform.createDefaultLayers();
        var LocationOfMarker = {
            lat:<?= !empty(explode(',',$warranty_card->latlng)[0])?explode(',',$warranty_card->latlng)[0]:'21.0226967'  ?>,
            lng:<?=  !empty(explode(',',$warranty_card->latlng)[1])?explode(',',$warranty_card->latlng)[1]:'105.8369637'   ?>,
        };
        //Step 2: initialize a map - this map is centered over Europe
        var map = new H.Map(document.getElementById('map-canvas'),
            defaultLayers.vector.normal.map, {
                center: LocationOfMarker,
                zoom: 1,
                type: 'base',
                pixelRatio: window.devicePixelRatio || 1
            });


        window.addEventListener('resize', () => map.getViewPort().resize());

        //Step 3: make the map interactive
        // MapEvents enables the event system
        // Behavior implements default interactions for pan/zoom (also on mobile touch environments)
        var behavior = new H.mapevents.Behavior(new H.mapevents.MapEvents(map));

        // Create the default UI components
        var ui = H.ui.UI.createDefault(map, defaultLayers);
        // Now use the map as required...
        window.onload = function() {
            moveMapTo(map, ' <?= !empty(explode(',',$warranty_card->latlng)[0])?explode(',',$warranty_card->latlng)[0]:'21.0226967'  ?> ', ' <?= !empty(explode(',',$warranty_card->latlng)[1])?explode(',',$warranty_card->latlng)[1]:'105.8369637'  ?>');
        }
        setUpClickListener(map);
        switchMapLanguage(map, platform);
    </script>

@endsection
