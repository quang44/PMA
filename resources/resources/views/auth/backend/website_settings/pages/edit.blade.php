@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col">
			<h1 class="h3">{{ translate('Cập nhật trang nội dung') }}</h1>
		</div>
	</div>
</div>
<div class="card">


	<form class="p-4" action="{{ route('custom-pages.update', $page->id) }}" method="POST" enctype="multipart/form-data">
		@csrf
		<input type="hidden" name="_method" value="PATCH">

		<div class="card-header px-0">
			<h6 class="fw-600 mb-0">{{ translate('Nội dung trang') }}</h6>
		</div>
		<div class="card-body px-0">
			<div class="form-group row">
				<label class="col-sm-2 col-from-label" for="name">{{translate('Tiêu đề')}} <span class="text-danger">*</span> <i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
				<div class="col-sm-10">
					<input type="text" class="form-control" placeholder="{{translate('Tiêu đề')}}" name="title" value="{{ $page->title }}" required>
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
                        <input type="hidden" name="icon" class="selected-files" value="{{ $page->icon }}">
                    </div>
                    <div class="file-preview box sm">
                    </div>
                </div>
            </div>
			<div class="form-group row">
				<label class="col-sm-2 col-from-label" for="name">{{translate('Nội dung')}} <span class="text-danger">*</span></label>
				<div class="col-sm-10">
					<textarea
						class="aiz-text-editor form-control"
						placeholder="{{translate('Nội dung ..')}}"
						data-buttons='[["font", ["bold", "underline", "italic", "clear"]],["para", ["ul", "ol", "paragraph"]],["style", ["style"]],["color", ["color"]],["table", ["table"]],["insert", ["link", "picture", "video"]],["view", ["fullscreen", "codeview", "undo", "redo"]]]'
						data-min-height="300"
						name="content"
					>{!! $page->content !!}</textarea>
				</div>
			</div>
		</div>

<!--		<div class="card-header px-0">
			<h6 class="fw-600 mb-0">{{ translate('Seo Fields') }}</h6>
		</div>-->
		<div class="card-body px-0">

<!--			<div class="form-group row">
				<label class="col-sm-2 col-from-label" for="name">{{translate('Meta Title')}}</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" placeholder="{{translate('Title')}}" name="meta_title" value="{{ $page->meta_title }}">
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-2 col-from-label" for="name">{{translate('Meta Description')}}</label>
				<div class="col-sm-10">
					<textarea class="resize-off form-control" placeholder="{{translate('Description')}}" name="meta_description">{!! $page->meta_description !!}</textarea>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-2 col-from-label" for="name">{{translate('Keywords')}}</label>
				<div class="col-sm-10">
					<textarea class="resize-off form-control" placeholder="{{translate('Keyword, Keyword')}}" name="keywords">{!! $page->keywords !!}</textarea>
					<small class="text-muted">{{ translate('Separate with coma') }}</small>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-2 col-from-label" for="name">{{translate('Meta Image')}}</label>
				<div class="col-sm-10">
					<div class="input-group " data-toggle="aizuploader" data-type="image">
							<div class="input-group-prepend">
								<div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div>
						</div>
						<div class="form-control file-amount">{{ translate('Choose File') }}</div>
						<input type="hidden" name="meta_image" class="selected-files" value="{{ $page->meta_image }}">
					</div>
					<div class="file-preview">
					</div>
				</div>
			</div>-->

			<div class="text-right">
				<button type="submit" class="btn btn-primary">{{ translate('Lưu') }}</button>
			</div>
		</div>
	</form>
</div>
@endsection
