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
                    <h5 class="mb-0 h6">{{translate('Warranty Card Information ')}} </h5>
                </div>
                <div class="card-body align-content-center">
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="package_id">{{translate('Brand')}} :</label>
                        <div class="col-sm-9">
                            <span>{{$warranty_card->brand->name}}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="name">{{translate('Customer name')}} :</label>
                        <div class="col-sm-9">
                            <span>{{$warranty_card->user_name}}</span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="address">{{translate('Address')}} :</label>
                        <div class="col-sm-9">
                            <span>{{$warranty_card->address}}</span>

                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="Seri">{{translate('Seri')}} :</label>
                        <div class="col-sm-9">
                            <span>{{$warranty_card->seri}}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="Seri">{{translate('Created_at')}} :</label>
                        <div class="col-sm-9">
                            <span>{{date('d-m-Y H:i:s',strtotime($warranty_card->created_at))}}</span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="Seri">{{translate('Activation time')}}
                            :</label>
                        <div class="col-sm-9">
                                <span class="text-danger">@if($warranty_card->active_time>0)
                                        {{date('d-m-Y H:i:s ',strtotime($warranty_card->active_time))}}
                                    @else
                                        {{ trans('Not activated') }}
                                    @endif
                                </span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="package_id">{{translate('Status')}} :</label>
                        <div class="col-sm-9">
                                <span>
                                    @if($warranty_card->status==0)
                                       đang chờ xử lý
                                    @elseif($warranty_card->status==1)
                                     đã được duyệt
                                    @else
                                        đã hủy  / lý do :  {{$warranty_card->reason}}
                                    @endif
                                </span>

                        </div>
                    </div>


                    <div class="form-group mb-3 row">
                        <label for="name" class=" col-sm-3">{{translate(' image ')}} :</label>
                        @foreach($warranty_card->uploads as $upload)
                                <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                    <img class="image" style="width:100px; height: 100px;object-fit: contain"
                                         src="{{ get_image_asset($upload->id,$upload->object_id) }}" alt="">
                                </a>

                    @endforeach
                </div>

                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="package_id">{{translate('Customer Notes')}}
                        :</label>
                    <div class="col-sm-9">
                        <span>{{$warranty_card->note}}</span>
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
                            <i class="las la-user-alt-slash"></i>
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

    <div class="modal fade" id="confirm-update-bank">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h6">{{translate('Xác nhận kích hoạt thẻ')}}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>{{translate('Bạn muốn xác nhận kích hoạt thẻ không')}}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{translate('Hủy')}}</button>
                    <a type="button" id="updateCard" class="btn btn-primary">{{translate('Xác nhận')}}</a>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="confirm-ban">
        <form action="" id="confirmation" method="GET">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title h6">{{translate('Nhập lý do hủy')}}</h5>
                        <button type="button" class="close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="text" class="form-control" name="reason" placeholder="Lý do hủy">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light"
                                data-bs-dismiss="modal">{{translate('Cancel')}}</button>
                        <button type="submit" class="btn btn-primary">{{translate('Proceed!')}}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>



@endsection

<!-- CSS only -->
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>

    <script >
        $('.image').on('click',function () {
            let img=$(this).attr('src');
            $('#image').attr('src',img)
        })

        function confirm_ban(url,status) {
            $('#confirm-ban').modal('show', {backdrop: 'static'});
            document.getElementById('confirmation').setAttribute('action', url+'?status='+status);
        }


        function updateCard(url,status) {
            $('#confirm-update-bank').modal('show', {backdrop: 'static'});
            document.getElementById('updateCard').setAttribute('href', url+'?status='+status);
        }

    </script>
@endsection
