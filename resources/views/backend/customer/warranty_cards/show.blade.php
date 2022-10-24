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
                                        {{translate('Pendding')}}
                                    @elseif($warranty_card->status==1)
                                        {{translate('Approved')}}
                                    @else
                                        {{translate('Cancelled')}}  / lý do :  {{$warranty_card->reason}}
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>

    <script >
        $('.image').on('click',function () {
            let img=$(this).attr('src');
            $('#image').attr('src',img)
        })
    </script>
@endsection
