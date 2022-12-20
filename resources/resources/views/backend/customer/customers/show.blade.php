@extends('backend.layouts.app')

@section('content')
    <style>
        .remove-attachment {
            display: none;
        }
    </style>
    <div class="row">
        <div class="col-lg-6 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">Thông tin khách hàng  - <a href="{{route('wallet-balance.balance',encrypt($user->id))}}" class="btn btn-soft-success btn-icon btn-circle btn-sm" title="Lịch sử giao dịch" >
                            <i class="las la-money-bill"></i>
                        </a> </h5>
                </div>
                <div class="card-body align-content-center">
                    <div class="form-group row">
                        <label class="col-sm-6 col-from-label" for="package_id">Tên khách hàng:</label>
                        <div class="col-sm-6">
                            <span>{{$user->name}}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-6 col-from-label" for="name">Số điện thoại :</label>
                        <div class="col-sm-6">
                            <span>{{$user->phone}}</span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-6 col-from-label" for="name">E-mail:</label>
                        <div class="col-sm-6">
                            <span>{{$user->email??'Chưa có email'}}</span>
                        </div>
                    </div>


                    <div class="form-group mb-6 row">
                        <label for="name" class=" col-sm-6">Trạng thại :</label>
                        <div class="col-sm-6" id="gallery">
                                @if($user->banned == 1)
                                    <span class="badge badge-inline badge-danger">{{ trans('Khóa') }}</span>

                                @else
                                    <span class="badge badge-inline badge-success">{{ trans('Hoạt động') }}</span>
                                @endif
                        </div>
                    </div>


                    <div class="form-group mb-3 row">
                        <label for="name" class=" col-sm-6">Avatar :</label>
                        <div class="col-sm-6" id="gallery">
                                    <a class="a-key" href="{{ static_asset($user->avatar) }}" >
                                        <img class="image" style="width:100px; height: 100px;object-fit: contain"
                                             src="{{ static_asset($user->avatar) }}" alt="">

                                    </a>
                        </div>
                    </div>

                    <div class="form-group mb-3 row">
                        <label for="name" class=" col-sm-6">Thuộc Đại lý - Tổng kho :</label>
                        <div  class="col-sm-6" id="gallery">
{{--                            {{$user->customer_package->name}}--}}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <img id="image" style="width: 100%; height: 100% ;object-fit: contain" src="" alt="">
        </div>
    </div>







@endsection

<!-- CSS only -->
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3"
            crossorigin="anonymous"></script>

    <script>

        $(`div#gallery`).magnificPopup({
            delegate: 'a',
            type: 'image',
            gallery: {
                enabled: true
            }
        })


        $('.image').on('click', function () {
            let img = $(this).attr('src');
            $('#image').attr('src', img)
        })

        function confirm_ban(url, status) {
            $('#confirm-ban').modal('show', {backdrop: 'static'});
            document.getElementById('confirmation').setAttribute('action', url + '?status=' + status);
        }


        function updateCard(url, status) {
            $('#confirm-update-bank').modal('show', {backdrop: 'static'});
            document.getElementById('updateCard').setAttribute('href', url + '?status=' + status);
        }

    </script>
@endsection
