@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col">
			<h1 class="h3">{{ translate('Trang nội dung') }}</h1>
		</div>
	</div>
</div>

<div class="card">
	<div class="card-header">
		<h6 class="mb-0 fw-600">{{ translate('Tất cả trang') }}</h6>
		<a href="{{ route('custom-pages.create') }}" class="btn btn-primary">{{ translate('Thêm mới trang') }}</a>
	</div>
	<div class="card-body">
		<table class="table aiz-table mb-0">
        <thead>
            <tr>
                <th data-breakpoints="lg">#</th>
                <th>{{translate('Tiêu đề')}}</th>
                <th data-breakpoints="md">{{translate('Icon')}}</th>
                <th data-breakpoints="md">{{translate('Đường dẫn')}}</th>
                <th class="text-right">{{translate('Tùy chọn')}}</th>
            </tr>
        </thead>
        <tbody>
        	@foreach ($pages as $key => $page)

        	<tr>
        		<td>{{ $key+1 }}</td>

                <td>{{ $page->getTranslation('title') }}</td>
                <td>
                    <img src="{{ uploaded_asset($page->icon) }}" alt="icon" class="h-50px">
                </td>
                <td><a href="{{ route('custom-pages.show_custom_page', $page->slug) }}" class="text-reset">{{ route('home') }}/{{ $page->slug }}</a></td>
        		<td class="text-right">
					@if($page->type == 'home_page')
						<a href="{{route('custom-pages.edit', ['id'=>$page->id, 'lang'=>env('DEFAULT_LANGUAGE'), 'page'=>'home'] )}}" class="btn btn-icon btn-circle btn-sm btn-soft-primary" title="Edit">
							<i class="las la-pen"></i>
						</a>
					@else
	          			<a href="{{route('custom-pages.edit', ['id'=>$page->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" class="btn btn-icon btn-circle btn-sm btn-soft-primary" title="Edit">
							<i class="las la-pen"></i>
						</a>
					@endif
					@if($page->type == 'custom_page')
          				<a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{ route('custom-pages.destroy', $page->id)}} " title="{{ translate('Delete') }}">
          					<i class="las la-trash"></i>
          				</a>
					@endif
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
