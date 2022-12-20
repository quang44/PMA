@extends('backend.layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-6 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{translate('Add New warranty code')}}</h5>
                </div>

                <form action="{{ route('warranty_codes.store')}}" method="POST">
                    @csrf
                    <div class="card-body">


                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="Seri">{{translate('code')}}<span
                                    class="text-danger"> *</span> </label>
                            <div class="col-sm-9">
                                <input type="text" placeholder="{{translate('code')}}" id="code" name="code"
                                       value="{{ old('code') }}" class="form-control" required>
                                @error('code')
                                <div class="" style="color: red">{{ $message }}</div>
                                @enderror
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
