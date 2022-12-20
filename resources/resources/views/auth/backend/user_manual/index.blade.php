@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col">
			<h1 class="h3">{{ translate('Hướng dẫn sử dụng') }}</h1>
		</div>
	</div>
</div>

<div class="card">
<!--	<div class="card-header">
		<h6 class="mb-0 fw-600">{{ translate('Danh sách') }}</h6>
		<a href="{{ route('news.create') }}" class="btn btn-primary">{{ translate('Thêm tin tức') }}</a>
	</div>-->
	<div class="card-body">
		<table class="table aiz-table mb-0">
        <thead>
            <tr>
                <th data-breakpoints="lg">#</th>
                <th>{{translate('Loại')}}</th>
                <th data-breakpoints="md">{{translate('File')}}</th>
                <th class="text-right">{{translate('Actions')}}</th>
            </tr>
        </thead>
        <tbody>
        	@foreach ($manuals as $key => $manual)

        	<tr>
        		<td>{{ $key+1 }}</td>

                <td>
                    @if($manual->type == 'customer')
                    {{ 'Khách hàng' }}
                    @endif
                    @if($manual->type == 'kol')
                        {{ 'Cộng tác viên' }}
                    @endif
                    @if($manual->type == 'delivery')
                        {{ 'Phí vận chuyển' }}
                    @endif
                </td>
                <td>
                    {{ uploaded_asset($manual->file) }}
                </td>
        		<td class="text-right">

                    <a href="{{route('user_manual.edit', ['id'=>$manual->id] )}}" class="btn btn-icon btn-circle btn-sm btn-soft-primary" title="Edit">
                        <i class="las la-pen"></i>
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
