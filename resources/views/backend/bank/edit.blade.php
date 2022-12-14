@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col">
			<h1 class="h3">{{ translate('Edit Page Information') }}</h1>
		</div>
	</div>
</div>
<div class="card">


	<form class="p-4" action="{{ route('banks.update', $bank->id) }}" method="POST" enctype="multipart/form-data">
		@csrf
		<input type="hidden" name="_method" value="PATCH">
		<div class="card-header px-0">
			<h6 class="fw-600 mb-0">{{ translate('Page Content') }}</h6>
		</div>
		<div class="card-body px-0">
			<div class="form-group row">
				<label class="col-sm-2 col-from-label" for="name">{{translate('Tên')}} <span class="text-danger">*</span></label>
				<div class="col-sm-10">
					<input type="text" class="form-control" placeholder="{{translate('Title')}}" name="name" value="{{ $bank->name }}" required>
				</div>
			</div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="signinSrEmail">
                {{translate('Icon')}}
                <!--                            <small>(1300x650)</small>-->
                </label>
                <div class="col-sm-10">
                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                        <div class="input-group-prepend">
                            <div class="input-group-text bg-soft-secondary font-weight-medium">
                                {{ translate('Browse')}}
                            </div>
                        </div>
                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                        <input type="hidden" name="icon" class="selected-files" value="{{ $bank->icon }}">
                    </div>
                    <div class="file-preview box sm">
                    </div>
                </div>
            </div>
		</div>

		<div class="card-body px-0">
			<div class="text-right">
				<button type="submit" class="btn btn-primary">{{ translate('Cập nhật') }}</button>
			</div>
		</div>
	</form>
</div>
@endsection
