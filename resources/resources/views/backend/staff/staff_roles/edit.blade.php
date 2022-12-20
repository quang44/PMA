@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <h5 class="mb-0 h6">{{translate('Role Information')}}</h5>
    </div>


    <div class="col-lg-7 mx-auto">
        <div class="card">
            <div class="card-body p-0">
                <ul class="nav nav-tabs nav-fill border-light">
                    @foreach (\App\Models\Language::all() as $key => $language)
                        <li class="nav-item">
                            <a class="nav-link text-reset @if ($language->code == $lang) active @else bg-soft-dark border-light border-left-0 @endif py-3"
                               href="{{ route('roles.edit', ['id'=>$role->id, 'lang'=> $language->code] ) }}">
                                <img src="{{ static_asset('assets/img/flags/'.$language->code.'.png') }}" height="11"
                                     class="mr-1">
                                <span>{{$language->name}}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
                <form class="p-4" action="{{ route('roles.update', $role->id) }}" method="POST">
                    <input name="_method" type="hidden" value="PATCH">
                    <input type="hidden" name="lang" value="{{ $lang }}">
                    @csrf
                    <div class="form-group row">
                        <label class="col-md-3 col-from-label" for="name">{{translate('Name')}} <i
                                class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                        <div class="col-md-9">
                            <input type="text" placeholder="{{translate('Name')}}" id="name" name="name"
                                   class="form-control" value="{{ $role->getTranslation('name', $lang) }}" required>
                        </div>
                    </div>
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Permissions') }}</h5>
                    </div>
                    <br>
                    @php
                        $permissions = json_decode($role->permissions);
                    @endphp
                    <div class="form-group row">
                        <label class="col-md-2 col-from-label" for="banner"></label>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">{{ translate('Dashboard') }}</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input @php if(in_array(25, $permissions)) echo "checked"; @endphp type="checkbox" name="permissions[]" class="form-control demo-sw" value="25">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">{{ translate('Products') }}</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input @php if(in_array(2, $permissions)) echo "checked"; @endphp type="checkbox" name="permissions[]" class="form-control demo-sw" value="2">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">{{ translate('Warranty') }}</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw"
                                               value="9" @php if(in_array(9, $permissions)) echo "checked"; @endphp>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">{{ translate('Customer') }}</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw"
                                               value="8" @php if(in_array(8, $permissions)) echo "checked"; @endphp>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">{{ translate('Depot') }} / {{ translate('Agent') }}</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox"   @php if(in_array(29, $permissions)) echo "checked"; @endphp name="permissions[]" class="form-control demo-sw" value="29">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">{{ translate('Gift') }}</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" @php if(in_array(24, $permissions)) echo "checked"; @endphp   name="permissions[]" class="form-control demo-sw" value="24">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">{{ translate('Banner') }}</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw"
                                               value="11" @php if(in_array(11, $permissions)) echo "checked"; @endphp>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">{{ translate('Cấu hình') }}</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw"
                                               value="14" @php if(in_array(14, $permissions)) echo "checked"; @endphp>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">{{ translate('Staffs') }}</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw"
                                               value="20" @php if(in_array(20, $permissions)) echo "checked"; @endphp>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">{{ translate('Uploaded Files') }}</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw"
                                               value="22" @php if(in_array(22, $permissions)) echo "checked"; @endphp>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">{{ translate('Bank') }}</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" @php if(in_array(26, $permissions)) echo "checked"; @endphp name="permissions[]" class="form-control demo-sw" value="26">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">{{ translate('Pages') }}</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" @php if(in_array(27, $permissions)) echo "checked"; @endphp   class="form-control demo-sw" value="27">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">{{ translate('News') }}</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" @php if(in_array(28, $permissions)) echo "checked"; @endphp  name="permissions[]" class="form-control demo-sw" value="28">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">{{ translate('Notification') }}</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" @php if(in_array(30, $permissions)) echo "checked"; @endphp  name="permissions[]" class="form-control demo-sw" value="30">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
                    </div>
            </div>
            </form>
        </div>
    </div>

@endsection
