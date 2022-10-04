@extends('backend.layouts.app')
@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="align-items-center">
			<h1 class="h3">{{translate('Update Package Information')}}</h1>
	</div>
</div>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-body p-0">
                <ul class="nav nav-tabs nav-fill border-light">
                  @foreach (\App\Models\Language::all() as $key => $language)
                    <li class="nav-item">
                      <a class="nav-link text-reset @if ($language->code == $lang) active @else bg-soft-dark border-light border-left-0 @endif py-3" href="{{ route('customer_packages.edit', ['id'=>$customer_package->id, 'lang'=> $language->code] ) }}">
                        <img src="{{ static_asset('assets/img/flags/'.$language->code.'.png') }}" height="11" class="mr-1">
                        <span>{{$language->name}}</span>
                      </a>
                    </li>
                      @endforeach
                </ul>
                <form class="p-4" action="{{ route('customer_packages.update', $customer_package->id) }}" method="POST">
                    <input type="hidden" name="_method" value="PATCH">
                    <input type="hidden" name="lang" value="{{ $lang }}">
                  	@csrf
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label">{{translate('Package Name')}} <i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="{{translate('Name')}}" id="name" name="name" value="{{ $customer_package->getTranslation('name', $lang) }}" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label">{{translate('Fee')}}</label>
                        <div class="col-sm-9">
                            <input type="number" lang="en" min="0" step="1" placeholder="{{translate('Fee')}}" value="{{ $customer_package->fee }}" id="fee" name="fee" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
