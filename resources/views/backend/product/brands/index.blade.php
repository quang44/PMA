@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="align-items-center">
			<h1 class="h3">Nhãn hiệu</h1>
	</div>
</div>

<div class="row">
	<div class="col-md-7">
		<div class="card">
		    <div class="card-header row gutters-5">
				<div class="col text-center text-md-left">
					<h5 class="mb-md-0 h6">Danh sách nhãn hiệu</h5>
				</div>
				<div class="col-md-4">
					<form class="" id="sort_brands" action="" method="GET">
						<div class="input-group input-group-sm">
					  		<input type="text" class="form-control" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="Nhập từ khóa ...">
						</div>
					</form>
				</div>
		    </div>
		    <div class="card-body">
		        <table class="table aiz-table mb-0">
		            <thead>
		                <tr>
		                    <th>#</th>
		                    <th>Tên nhãn hiệu</th>
                            <th>Mã code</th>
		                    <th>Logo</th>
                            <th>Trạng thái</th>
		                    <th class="text-right">Tùy chọn</th>
		                </tr>
		            </thead>
		            <tbody>
		                @foreach($brands as $key => $brand)
		                    <tr>
		                        <td>{{ ($key+1) + ($brands->currentPage() - 1)*$brands->perPage() }}</td>
		                        <td>{{ $brand->getTranslation('name') }}</td>
                                <td>{{ $brand->code }}</td>
								<td>
		                            <img src="{{ uploaded_asset($brand->logo) }}" alt="{{translate('Brand')}}" class="h-50px">
		                        </td>
                                <td>
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input value="4" type="checkbox" @if($brand->status==1) checked @endif onclick="ChangeStatus( {{$brand->id}},{{$brand->status}})" >
                                        <span class="slider round"></span>
                                    </label>
                                </td>
		                        <td class="text-right">
		                            <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('brands.edit', ['id'=>$brand->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" title="{{ translate('Edit') }}">
		                                <i class="las la-edit"></i>
		                            </a>
		                            <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('brands.destroy', $brand->id)}}" title="{{ translate('Delete') }}">
		                                <i class="las la-trash"></i>
		                            </a>
		                        </td>
		                    </tr>
		                @endforeach
		            </tbody>
		        </table>
		        <div class="aiz-pagination">
                	{{ $brands->appends(request()->input())->links() }}
            	</div>
		    </div>
		</div>
	</div>
	<div class="col-md-5">
		<div class="card">
			<div class="card-header">
				<h5 class="mb-0 h6">Thêm mới nhãn hiệu</h5>
			</div>
			<div class="card-body">
				<form action="{{ route('brands.store') }}" method="POST">
					@csrf
					<div class="form-group mb-3">
						<label for="name">Tên nhãn hiệu <small class="text-danger">*</small>  </label>
						<input type="text" placeholder="Tên nhãn hiệu" name="name" class="form-control" required>
					</div>
					<div class="form-group mb-3">
						<label for="name">Logo <small>({{ translate('120x80') }})</small></label>
						<div class="input-group" data-toggle="aizuploader" data-type="image">
							<div class="input-group-prepend">
									<div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
							</div>
							<div class="form-control file-amount">{{ translate('Choose File') }}</div>
							<input type="hidden" name="logo" class="selected-files">
						</div>
{{--						<div class="file-preview box sm">--}}
{{--						</div>--}}
					</div>
					<div class="form-group mb-3">
						<label for="name"></label>
						<input type="text" class="form-control" name="meta_title" placeholder="{{translate('Tiêu đề')}}">
					</div>
					<div class="form-group mb-3">
						<label for="name">{{translate('Thông tin chi tiết')}}</label>
						<textarea name="meta_description" rows="5" class="form-control"></textarea>
					</div>
					<div class="form-group mb-3 text-right">
						<button type="submit" class="btn btn-primary">{{translate('Lưu')}}</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection

@section('script')
<script type="text/javascript">
    function sort_brands(el){
        $('#sort_brands').submit();
    }
    function  ChangeStatus(id,status) {
        if(status==0){
            status=1;
        }else{
            status=0;
        }
        $.post('{{ route('brands.update_status') }}', {_token:'{{ csrf_token() }}',
            id:id, status:status}, function(data){
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
