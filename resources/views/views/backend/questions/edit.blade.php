@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col">
			<h1 class="h3">{{ translate('Sửa bài viết') }}</h1>
		</div>
	</div>
</div>
<div class="card">


	<form class="p-4" action="{{ route('questions.update', $question->id) }}" method="POST" enctype="multipart/form-data">
		@csrf
		<input type="hidden" name="_method" value="PATCH">

<!--		<div class="card-header px-0">
			<h6 class="fw-600 mb-0">{{ translate('Nội dung tin tức') }}</h6>
		</div>-->
        <div class="card-body">
            <div class="form-group row">
                <label class="col-sm-2 col-from-label" for="name">{{translate('Câu hỏi')}} <span class="text-danger">*</span></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" value="{{ $question->question }}" placeholder="" name="question" required>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-from-label" for="name">Đối tượng hiển thị<span class="text-danger">*</span> </label>
                <div class="col-sm-10">
                    <select class="form-control" name="type">
                        <option value="customer" @if($question->type == 'customer') selected @endif>Khách hàng</option>
                        <option value="kol" @if($question->type == 'kol') selected @endif>Cộng tác viên</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-from-label" for="">{{translate('Ưu tiên')}} <span class="text-danger">*</span></label>
                <div class="col-sm-10">
                    <input type="number" class="form-control" placeholder="" name="priority" value="{{ $question->priority }}" required>
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
                    >{!! $question->answer !!}</textarea>
                </div>
            </div>
        </div>
		<div class="card-body px-0">
			<div class="text-right">
				<button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
			</div>
		</div>
	</form>
</div>
@endsection
