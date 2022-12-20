@extends('backend.layouts.app')

@section('content')

    <div class="aiz-titlebar text-left mt-2 mb-3 row">
        <div class="align-items-center col-md-6">
            <h1 class="h3">{{translate('All Notifications')}}</h1>
        </div>
        <div class="col-md-6 text-md-right">
            <a href="{{route('notification.create')}}" class="btn btn-circle btn-info">
                <span>Thêm thông báo</span>
            </a>
        </div>
    </div>



    <div class="row">
        <div class="col-md-12 mx-auto">
            <div class="card">
                <form class="" id="sort_customers" action="" method="GET">
                    <div class="card-header row gutters-5">
                        <div class="col">
                            <h5 class="mb-0 h6">{{translate('Notifications')}}</h5>
                        </div>
                    </div>

                    <div class="card-body">

                        <table class="table aiz-table mb-0">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>{{translate('Title')}} </th>
                                <th data-breakpoints="lg">{{translate('Content')}}</th>
{{--                                <th data-breakpoints="lg">{{translate('Image')}}</th>--}}
                                <th data-breakpoints="lg">{{translate('Thời gian gửi')}}</th>
                                <th class="text-right">{{translate('Tùy chọn')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($notifications as $key=> $notification)
                                <tr>
                                    <td>{{$key+=1}}</td>
                                    <td>{{$notification->title}}</td>
                                    <td>{{$notification->text}}</td>
{{--                                    <td><img src="{{uploaded_asset($notification->image)}}" width="100px" alt="icon"></td>--}}
                                    <td>{{$notification->created_at}}</td>
                                    <td class="text-right">
                                        <a href="{{ route('notification.edit', [ encrypt($notification->id) ]) }}"
                                           class="btn btn-soft-warning btn-icon btn-circle btn-sm"
                                           title="{{ translate('Cập nhật thông tin thẻ') }}">
                                            <i class="las la-edit"></i>
                                        </a>
                                        <a href="#"
                                           class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                           data-href="{{route('notification.destroy', encrypt($notification->id))}}"
                                           title="{{ translate('Xóa') }}">
                                            <i class="las la-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        {{ $notifications->links() }}
                    </div>
                </form>
            </div>
        </div>
    </div>

@section('modal')
    @include('modals.delete_modal')
@endsection

@endsection

