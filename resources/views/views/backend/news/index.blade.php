@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="h3">{{ translate('Quản trị tin tức') }}</h1>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h6 class="mb-0 fw-600">{{ translate('Danh sách tin tức') }}</h6>
            <a href="{{ route('news.create') }}" class="btn btn-primary">{{ translate('Thêm tin tức') }}</a>
        </div>
        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                <tr>
                    <th data-breakpoints="lg">#</th>
                    <th>{{translate('Tiêu đề')}}</th>
                    <th data-breakpoints="md">{{translate('Ảnh đại diện')}}</th>
                    <th data-breakpoints="md">{{translate('Url')}}</th>
                    <th data-breakpoints="md">{{translate('Status')}}</th>
                    <th class="text-right">{{translate('Actions')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($news as $key => $new)

                    <tr>
                        <td>{{ $key+1 }}</td>

                        <td>{{ $new->title }}</td>
                        <td>
                            <img src="{{ uploaded_asset($new->icon) }}" alt="icon" class="h-50px">
                        </td>
                        <td><a href="{{ route('news.show', $new->slug) }}" class="text-reset">{{ route('home') }}/news/{{ $new->slug }}</a></td>
                        <td>
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input value="4" type="checkbox" @if($new->status==0) checked @endif onclick="ChangeStatus( {{$new->id}},{{$new->status}})" >
                                <span class="slider round"></span>
                            </label>
                        </td>
                        <td class="text-right">

                            <a href="{{route('news.edit', ['id'=>$new->id] )}}" class="btn btn-icon btn-circle btn-sm btn-soft-primary" title="Edit">
                                <i class="las la-pen"></i>
                            </a>

                            <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{ route('news.destroy', $new->id)}} " title="{{ translate('Delete') }}">
                                <i class="las la-trash"></i>
                            </a>

                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>


@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection

@section('script')
    <script !src="" type="text/javascript">
        function  ChangeStatus(id,status) {
            if(status==0){
                status=1;
            }else{
                status=0;
            }
            $.post('{{ route('news.update_status') }}', {_token:'{{ csrf_token() }}',
                id:id, status:status}, function(data){
                console.log(data)
                if(data.result == 1){
                    location.reload()
                    AIZ.plugins.notify('success', '{{ translate('update status successfully') }}');
                } else{
                    location.reload()
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });

        }
    </script>
@endsection
