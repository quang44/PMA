@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col">
			<h1 class="h3">{{ translate('Sửa tin tức') }}</h1>
		</div>
	</div>
</div>
<div class="card">


	<form class="p-4" action="{{ route('news.update', $news->id) }}" method="POST" enctype="multipart/form-data">
		@csrf
		<input type="hidden" name="_method" value="PATCH">

<!--		<div class="card-header px-0">
			<h6 class="fw-600 mb-0">{{ translate('Nội dung tin tức') }}</h6>
		</div>-->
		<div class="card-body px-0">
			<div class="form-group row">
				<label class="col-sm-2 col-from-label" for="name">{{translate('Tiêu đề')}} <span class="text-danger">*</span> <i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
				<div class="col-sm-10">
					<input type="text" class="form-control" placeholder="{{translate('Title')}}" name="title" value="{{ $news->title }}" required>
				</div>
			</div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="signinSrEmail">
                {{translate('Ảnh đại diện')}}
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
                        <input type="hidden" name="icon" class="selected-files" value="{{ $news->icon }}">
                    </div>
                    <div class="file-preview box sm">
                    </div>
                </div>
            </div>
{{--            <div class="form-group row">--}}
{{--                <label class="col-sm-2 col-form-label" for="signinSrEmail"><small>Ảnh bài viết</small></label>--}}
{{--                <div class="col-sm-10">--}}
{{--                    <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">--}}
{{--                        <div class="input-group-prepend">--}}
{{--                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>--}}
{{--                        </div>--}}
{{--                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>--}}
{{--                        <input type="hidden" name="images" class="selected-files" value="{{ $news->images }}">--}}
{{--                    </div>--}}
{{--                    <div class="file-preview box sm">--}}
{{--                    </div>--}}
{{--                    <small class="text-muted">{{translate('TheseC images are visible in product details page gallery. Use 600x600 sizes images.')}}</small>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="form-group row">--}}
{{--                <label class="col-sm-2 col-from-label" for="name">Đường dẫn ảnh/video <span class="text-danger">*</span></label>--}}
{{--                <div class="col-sm-10">--}}
{{--                    <textarea class="form-control" name="link" id="" cols="30" rows="10">--}}
{{--                        {!! $news->link !!}--}}
{{--                    </textarea>--}}
{{--                </div>--}}
{{--            </div>--}}
			<div class="form-group row">
				<label class="col-sm-2 col-from-label" for="name">{{translate('Nội dung')}} <span class="text-danger">*</span></label>
				<div class="col-sm-10">
					<textarea
						class="aiz-text-editor form-control"
						placeholder="{{translate('Content..')}}"
						data-buttons='[["font", ["bold", "underline", "italic", "clear"]],["para", ["ul", "ol", "paragraph"]],["style", ["style"]],["color", ["color"]],["table", ["table"]],["insert", ["link", "picture", "video"]],["view", ["fullscreen", "codeview", "undo", "redo"]]]'
						data-min-height="300"
						name="content"
					>{!! $news->content !!}</textarea>
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
					<input type="text" class="form-control" placeholder="{{translate('Title')}}" name="meta_title" value="{{ $news->meta_title }}">
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-2 col-from-label" for="name">{{translate('Meta Description')}}</label>
				<div class="col-sm-10">
					<textarea class="resize-off form-control" placeholder="{{translate('Description')}}" name="meta_description">{!! $news->meta_description !!}</textarea>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-2 col-from-label" for="name">{{translate('Keywords')}}</label>
				<div class="col-sm-10">
					<textarea class="resize-off form-control" placeholder="{{translate('Keyword, Keyword')}}" name="keywords">{!! $news->keywords !!}</textarea>
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
						<input type="hidden" name="meta_image" class="selected-files" value="{{ $news->meta_image }}">
					</div>
					<div class="file-preview">
					</div>
				</div>
			</div>-->

			<div class="text-right">
				<button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
			</div>
		</div>
	</form>
</div>
@endsection
