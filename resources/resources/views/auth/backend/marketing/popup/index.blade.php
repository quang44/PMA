@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="h3">{{translate('All Popup')}}</h1>
        </div>
        <div class="col text-right">
            <a href="{{ route('popup.create') }}" class="btn btn-circle btn-info">
                <span>{{translate('Add New Popup')}}</span>
            </a>
        </div>
    </div>
</div>
<br>

<div class="card">
    <form class="" id="sort_blogs" action="" method="GET">
        <div class="card-header row gutters-5">
            <div class="col text-center text-md-left">
                <h5 class="mb-md-0 h6">{{ translate('All popup') }}</h5>
            </div>

<!--            <div class="col-md-2">
                <div class="form-group mb-0">
                    <input type="text" class="form-control form-control-sm" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="{{ translate('Type & Enter') }}">
                </div>
            </div>-->
        </div>
    </from>
        <div class="card-body">
            <table class="table mb-0 aiz-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{translate('Name')}}</th>
                        <th data-breakpoints="lg">{{translate('Image')}}</th>
                        <th data-breakpoints="lg">{{translate('Type')}}</th>
                        <th data-breakpoints="lg">{{translate('Date')}}</th>
                        <th data-breakpoints="lg">{{translate('Status')}}</th>
                        <th class="text-right">{{translate('Options')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($popups as $key => $popup)
                    <tr>
                        <td>
                            {{ ($key+1) + ($popups->currentPage() - 1) * $popups->perPage() }}
                        </td>
                        <td>
                            {{ $popup->name }}
                        </td>
                        <td>
                            <img src="{{ $popup->url_image }}" alt="banner" class="h-50px">
                        </td>
                        <td>
                            @if($popup->type == 'customer')
                                Khách hàng
                            @elseif($popup->type == 'new_user')
                                Khách hàng mới
                            @elseif($popup->type == 'kol')
                                Cộng tác viên
                            @elseif($popup->type == 'employee')
                                Nhân viên
                            @endif
                        </td>
                        <td>
                            @if($popup->type == 'all_user')
                                {{ date('d/m/Y H:i:s', $popup->start_time) . ' to ' .  date('d/m/Y H:i:s', $popup->end_time)}}
                            @elseif($popup->type == 'new_user')
                                {{ $popup->day }}
                            @endif

                        </td>
                        <td>
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input type="checkbox" onchange="change_status(this)" value="{{ $popup->id }}" <?php if($popup->status == 1) echo "checked";?>>
                                <span></span>
                            </label>
                        </td>
                        <td class="text-right">
                            <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{ route('popup.edit',$popup->id)}}" title="{{ translate('Edit') }}">
                                <i class="las la-pen"></i>
                            </a>

                            <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('popup.destroy', $popup->id)}}" title="{{ translate('Delete') }}">
                                <i class="las la-trash"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
                {{ $popups->links() }}
            </div>
        </div>
</div>

@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection


@section('script')

    <script type="text/javascript">
        function change_status(el){
            var status = 0;
            if(el.checked){
                var status = 1;
            }
            $.post('{{ route('popup.change-status') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '{{ translate('Change popup status successfully') }}');
                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }
    </script>

@endsection
