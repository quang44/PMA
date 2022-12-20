@extends('backend.layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-6 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">Sửa thông báo</h5>
                </div>

                <form action="{{ route('notification.update',$notification->id)}}" method="POST" enctype="multipart/form-data">
                @method('PUT')
                    @csrf
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="name">{{translate('Title')}} <span
                                    class="text-danger"> *</span> </label>
                            <div class="col-sm-9">
                                <input type="text" placeholder="{{translate('Title')}}" id="title" name="title"
                                       value="{{ old('title',$notification->title) }}" class="form-control" required>
                                @error('type')
                                <div class="" style="color: red">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>


                        <div class="form-group mb-3 row">
                            <div class="col-3">
                                <label for="name">{{translate(' Ảnh ')}} <small>({{ translate('120x80') }}
                                        )</small></label>

                            </div>
                            <div class="col-9">
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div
                                            class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="image" class="selected-files" value="{{$notification->image}}">
                                </div>

                                <div class="file-preview box sm">

                                </div>
                            </div>

                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="group">Nhóm gửi<span class="text-danger"> *</span> </label>
                            <div class="col-sm-9">
                                <select name="group" class="aiz-selectpicker w-100"
                                        data-selected-text-format="count"
                                        data-live-search="true">
                                    <option value="0">Tất cả</option>
                                    <option value="1">Thợ</option>
                                    <option value="2">Đại lý</option>
                                    <option value="3">Tổng kho</option>
                                </select>
                                @error('text')
                                <div class="" style="color: red">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label">{{translate('Type')}} <span
                                    class="text-danger"> *</span> </label>
                            <div class="col-sm-9">
                                <select name="type" class="aiz-selectpicker w-100"
                                        data-selected-text-format="count"
                                        data-live-search="true">
                                    <option {{$notification->item_type=='maintain'?'selected':''}} value="maintain">Bảo trì</option>
                                    <option {{$notification->item_type=='event'?'selected':''}} value="event">Sự kiện</option>
                                </select>
                                @error('text')
                                <div class="" style="color: red">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="email">{{translate('Content',$notification->text)}} <span
                                    class="text-danger"> *</span> </label>
                            <div class="col-sm-9">
					<textarea
                        class=" form-control  "
                        data-buttons='[["font", ["bold", "underline", "italic", "clear"]],["para", ["ul", "ol", "paragraph"]],["style", ["style"]],["color", ["color"]],["table", ["table"]],["insert", ["link", "picture", "video"]],["view", ["fullscreen", "codeview", "undo", "redo"]]]'
                        placeholder="Nội dung ..."
                        data-min-height="500"
                        name="text"
                        required
                    >{{$notification->text}}</textarea>
                            </div>
                            @error('text')
                            <div class="" style="color: red">{{ $message }}</div>
                            @enderror
                        </div>
                        {{--                        <div class="form-group mb-3">--}}
                        {{--                            <label for="name">{{translate('Qr Code image ')}} <small>({{ translate('120x80') }})</small></label>--}}
                        {{--                            <div class="input-group" data-toggle="aizuploader" data-type="image">--}}
                        {{--                                <div class="input-group-prepend">--}}
                        {{--                                    <div--}}
                        {{--                                        class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>--}}
                        {{--                                </div>--}}
                        {{--                                <div class="form-control file-amount">{{ translate('Choose File') }}</div>--}}
                        {{--                                <input type="hidden" name="qr_code_image" class="selected-files">--}}
                        {{--                            </div>--}}
                        {{--                            <div class="file-preview box sm">--}}
                        {{--                            </div>--}}
                        {{--                            @error('qr_code_image')--}}
                        {{--                            <div class="" style="color: red">{{ $message }}</div>--}}
                        {{--                            @enderror--}}
                        {{--                        </div>--}}

                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
