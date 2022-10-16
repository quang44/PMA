@extends('backend.layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-6 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{translate('Thêm thẻ bảo hành')}}</h5>
                </div>

                <form action="{{ route('warranty_card.store')}}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="name">{{translate('User name')}} <span
                                    class="text-danger"> *</span> </label>
                            <div class="col-sm-9">
                                <input type="text" placeholder="{{translate('User name')}}" id="name" name="user_name"
                                       value="{{ old('user_name') }}" class="form-control" required>
                                @error('user_name')
                                <div class="" style="color: red">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="email">{{translate('Address')}} <span
                                    class="text-danger"> *</span> </label>
                            <div class="col-sm-9">
                                <input type="text" placeholder="{{translate('Address')}}" id="address" name="address"
                                       value="{{ old('address') }}" class="form-control" required>
                                @error('address')
                                <div class="" style="color: red">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="Seri">{{translate('Seri')}}<span
                                    class="text-danger"> *</span> </label>
                            <div class="col-sm-9">
                                <input type="number" placeholder="{{translate('seri')}}" id="seri" name="seri"
                                       value="{{ old('seri') }}" class="form-control" required>
                                @error('seri')
                                <div class="" style="color: red">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="package_id">{{translate('Hãng ')}} <span
                                    class="text-danger"> *</span> </label>
                            <div class="col-sm-9">
                                <select name="brand_id" id="" class="form-control" required>
                                    <option value="">chọn hãng sản xuất....</option>

                                    @foreach($brands as $brand)
                                        @if($brand->status==1)
                                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('brand_id')
                                <div class="" style="color: red">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="name">{{translate('Qr Code image ')}} <small>({{ translate('120x80') }})</small></label>
                            <div class="input-group" data-toggle="aizuploader" data-type="image">
                                <div class="input-group-prepend">
                                    <div
                                        class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                                </div>
                                <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                <input type="hidden" name="qr_code_image" class="selected-files">
                            </div>
                            <div class="file-preview box sm">
                            </div>
                            @error('qr_code_image')
                            <div class="" style="color: red">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="name">{{translate('Seri image ')}} <small>({{ translate('120x80') }}
                                    ) </small><span class="text-danger">*</span> </label>
                            <div class="input-group" data-toggle="aizuploader" data-type="image">
                                <div class="input-group-prepend">
                                    <div
                                        class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                                </div>
                                <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                <input type="hidden" name="seri_image" class="selected-files">
                            </div>
                            <div class="file-preview box sm">
                            </div>
                            @error('seri_image')
                            <div class="" style="color: red">{{ $message }}</div>
                            @enderror
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
