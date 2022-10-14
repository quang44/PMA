@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col-md-6">
			<h1 class="h3">{{translate('All Classifies Packages')}}</h1>
		</div>
		<div class="col-md-6 text-md-right">
			<a href="{{ route('customer_packages.create') }}" class="btn btn-circle btn-info">
				<span>{{translate('Add New Package')}}</span>
			</a>
		</div>
	</div>
</div>

<div class="row">
    @foreach ($customer_packages as $key => $customer_package)
        <div class="col-lg-3 col-md-4 col-sm-12">
            <div class="card">
                <div class="card-body text-center">
                    <p class="mb-3 h6 fw-600">{{$customer_package->getTranslation('name')}}</p>
                    <p class="h4">{{single_price($customer_package->fee)}}</p>
<!--                    <p class="fs-15">{{translate('Product Upload') }}:
                        <span class="text-bold">{{$customer_package->product_upload}}</span>
                    </p>-->
                    <label class="aiz-switch aiz-switch-success mb-0">
                        <input value="{{ $customer_package->id }}" type="checkbox" @if($customer_package->default == 1) checked @endif disabled >
                        <span class="slider round"></span>
                    </label>
                    <div class="mar-top mt-3">
                        <a href="{{route('customer_packages.edit', ['id'=>$customer_package->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" class="btn btn-sm btn-info">{{translate('Edit')}}</a>
                        @if($customer_package->default != 1)
                        <a href="#" data-href="{{route('customer_packages.destroy', $customer_package->id)}}" class="btn btn-sm btn-danger confirm-delete" >{{translate('Delete')}}</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection

@section('script')
    <script type="text/javascript">
        function update_default(el){
            let status = 0;
            if (el.checked) {
                status = 1;
            }
            $.post('{{ route('customer_packages.update_default') }}', {_token:'{{ csrf_token() }}',
                id:el.value, status:status}, function(data){
                if(data == 1){
                    location.reload();
                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }
    </script>
@endsection
