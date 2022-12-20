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
                    <h5 class="mb-0 h6">{{translate('Info gift')}} </h5>
                </div>
                <div class="card-body align-content-center">

                    <div class="form-group row">
                        <label class="col-sm-4 col-from-label" for="name">{{translate('Customer')}} :</label>
                        <div class="col-sm-8">
                            <span>{{strtoupper($gift->user->name)}}</span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-from-label" for="name">{{translate('phone')}} :</label>
                        <div class="col-sm-8">
                            <span>{{strtoupper($gift->user->phone)}}</span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-from-label" for="name">{{translate('email')}} :</label>
                        <div class="col-sm-8">
                            <span>{{strtoupper($gift->user->email)}}</span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-from-label" for="package_id">{{translate('Name')}} {{translate('Gift')}} :</label>
                        <div class="col-sm-8">
                            <span>{{$gift->gift->name}}</span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-from-label" for="package_id">{{translate('Point')}} {{translate('Gift')}} :</label>
                        <div class="col-sm-8">
                            <span>{{$gift->gift->point}}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 col-from-label" for="address">{{translate('Address')}} :</label>
                        <div class="col-sm-8">
                            <span>{{$gift->address}}</span>

                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 col-from-label" for="Seri">{{translate('Created_at')}} :</label>
                        <div class="col-sm-8">
                            <span>{{convertTime($gift->created_time==null?'':$gift->created_time)}}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 col-from-label" for="Seri">{{translate('Active Time')}}/ Hủy:</label>
                        <div class="col-sm-8">
                            <span>{{$gift->active_time==null?'--':convertTime($gift->active_time)}}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 col-from-label" for="package_id">{{translate('Status')}} :</label>
                        <div class="col-sm-8">

                            @if($gift->status==0)
                                <span class="badge badge-inline badge-secondary">
                                    {{\App\Utility\WarrantyCardUtility::$aryStatus[\App\Utility\WarrantyCardUtility::STATUS_NEW]}}
                                        </span>
                            @elseif($gift->status==1)
                                <span class="badge badge-inline badge-success">
                                        {{\App\Utility\WarrantyCardUtility::$aryStatus[\App\Utility\WarrantyCardUtility::STATUS_SUCCESS]}}
    </span>
                            @else
                                <span class="badge badge-inline badge-danger">
                                        {{\App\Utility\WarrantyCardUtility::$aryStatus[\App\Utility\WarrantyCardUtility::STATUS_CANCEL]}}
                                             </span> / lý do :  {{$gift->reason}}
                            @endif


                        </div>
                    </div>

                    <div class="form-group mb-4 row">
                        <label for="name" class=" col-sm-4">{{translate('Image')}} :</label>
                        <div class="col-sm-8">
                        <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#exampleModal">
                            <img class="image" style="width:100px; height: 100px;object-fit: contain"
                                 src="{{uploaded_asset($gift->gift->image)}}" alt="">
                        </a>
                        </div>
                    </div>


                    @if($gift->status==0 )
                        <a href="javascript:void(0)"
                           class="btn btn-soft-info btn-icon btn-circle btn-sm"
                           onclick="updateCard('{{route('warranty_card.ban', encrypt($gift->id))}}',1);"
                           title="{{ translate('Activate Cards') }}">
                            <i class="las la-gifts"></i>
                        </a>
                        <a href="javascript:void(0)"
                           class="btn btn-soft-danger btn-icon btn-circle btn-sm"
                           onclick="confirm_ban('{{route('warranty_card.ban', encrypt($gift->id))}}' ,2);"
                           title="{{ translate('Cancel the card') }}">
                            <i class="las la-gifts"></i>
                        </a>
                    @endif
                </div>

            </div>
        </div>
    </div>


    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <img id="image" style="width: 100%; height: 100% ;object-fit: contain" src="" alt="">
        </div>
    </div>


@section('modal')
    @include('modals.confirm_modal')
@endsection



@endsection

<!-- CSS only -->
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3"
            crossorigin="anonymous"></script>

    <script>
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
