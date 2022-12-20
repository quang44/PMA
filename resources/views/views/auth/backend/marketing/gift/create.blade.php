@extends('backend.layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{translate('Add New Gift')}}</h5>
                </div>

                <form action="{{ route('gift.store')}}" method="POST">
                    @csrf
                    <div class="card-body">


                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="Seri">{{translate('Name')}}<span
                                    class="text-danger"> *</span> </label>
                            <div class="col-sm-9">
                                <input type="text" placeholder="{{translate('Name')}}" id="name" name="name"
                                       value="{{ old('name') }}" class="form-control" required>
                                @error('name')
                                <div class="" style="color: red">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-sm-2 col-form-label" >{{translate('Logo')}}
                                <small>({{ translate('120x80') }})</small>
                            </label>
                            <div class="col-sm-10">
                            <div class="input-group" data-toggle="aizuploader" data-type="image">
                                <div class="input-group-prepend">
                                    <div
                                        class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                                </div>
                                <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                <input type="hidden" name="image" class="selected-files">
                            </div>
                                <div class="file-preview box sm">
                                </div>
                            </div>

                        </div>


                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="point">{{translate('Point')}}<span
                                    class="text-danger"> *</span> </label>
                            <div class="col-sm-9">
                                <input type="number" placeholder="{{translate('Point')}}" id="point" name="point"
                                       value="{{ old('point') }}" class="form-control" required>
                                @error('point')
                                <div class="" style="color: red">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>




                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">{{translate('Description')}} <span class="text-danger">*</span></label>
                            <div class="col-sm-10">
					<textarea
                        class="aiz-text-editor form-control"
                        data-buttons='[["font", ["bold", "underline", "italic", "clear"]],["para", ["ul", "ol", "paragraph"]],["style", ["style"]],["color", ["color"]],["table", ["table"]],["insert", ["link", "picture", "video"]],["view", ["fullscreen", "codeview", "undo", "redo"]]]'
                        placeholder="Nội dung ..."
                        data-min-height="300"
                        name="description"
                        required
                    ></textarea>
                            </div>
                        </div>

                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
