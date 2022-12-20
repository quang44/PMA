@extends('backend.layouts.app')
@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col">
			<h1 class="h3">{{ translate('Thêm bài viết') }}</h1>
		</div>
	</div>
</div>
<div class="card">
	<form action="{{ route('questions.store') }}" method="POST" >
		@csrf
		<div class="card-header">
			<h6 class="fw-600 mb-0">{{ translate('Thông tin') }}</h6>
		</div>
		<div class="card-body">
			<div class="form-group row">
				<label class="col-sm-2 col-from-label" for="name">{{translate('Câu hỏi')}} <span class="text-danger">*</span></label>
				<div class="col-sm-10">
					<input type="text" class="form-control" placeholder="" name="question" required>
				</div>
			</div>
            <div class="form-group row">
                <label class="col-sm-2 col-from-label" for="name">Đối tượng hiển thị<span class="text-danger">*</span> </label>
                <div class="col-sm-10">
                    <select class="form-control" name="type">
                        <option value="customer">Khách hàng</option>
                        <option value="kol">Cộng tác viên</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-from-label" for="">{{translate('Ưu tiên')}} <span class="text-danger">*</span></label>
                <div class="col-sm-10">
                    <input type="number" class="form-control" placeholder="" name="priority" required>
                </div>
            </div>
			<div class="form-group row">
				<label class="col-sm-2 col-from-label" for="">{{translate('Câu trả lời')}} <span class="text-danger">*</span></label>
				<div class="col-sm-10">
					<textarea
						class="aiz-text-editor form-control"
						data-buttons='[["font", ["bold", "underline", "italic", "clear"]],["para", ["ul", "ol", "paragraph"]],["style", ["style"]],["color", ["color"]],["table", ["table"]],["insert", ["link", "picture", "video"]],["view", ["fullscreen", "codeview", "undo", "redo"]]]'
						placeholder="Content.."
						data-min-height="300"
						name="answer"
						required
					></textarea>
				</div>
			</div>
		</div>

<!--		<div class="card-header">
			<h6 class="fw-600 mb-0">{{ translate('Seo Fields') }}</h6>
		</div>-->
		<div class="card-body">

<!--			<div class="form-group row">
					<label class="col-sm-2 col-from-label" for="name">{{translate('Meta Title')}}</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" placeholder="{{translate('Title')}}" name="meta_title">
					</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-2 col-from-label" for="name">{{translate('Meta Description')}}</label>
				<div class="col-sm-10">
					<textarea class="resize-off form-control" placeholder="{{translate('Description')}}" name="meta_description"></textarea>
				</div>
			</div>

			<div class="form-group row">
					<label class="col-sm-2 col-from-label" for="name">{{translate('Keywords')}}</label>
					<div class="col-sm-10">
						<textarea class="resize-off form-control" placeholder="{{translate('Keyword, Keyword')}}" name="keywords"></textarea>
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
								<input type="hidden" name="meta_image" class="selected-files">
						</div>
						<div class="file-preview">
						</div>
					</div>
			</div>-->

			<div class="text-right">
				<button type="submit" class="btn btn-primary">{{ translate('Thêm câu hỏi') }}</button>
			</div>
		</div>
	</form>
</div>
@endsection
