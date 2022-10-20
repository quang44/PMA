@extends('backend.layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="card-header">
                    <h5 class="mb-0 h6">{{$work == 1 ? translate('Số tiền rút của các nhóm') : translate('Số point của các nhóm')}}</h5>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ $work == 1 ? route('customer_groups.update_withdraw') : route('customer_groups.update_point') }}" method="POST">
                        @csrf
                        @foreach($customer_groups as $key => $customer_group)
                            <div class="form-group row">
                                <div class="col-lg-3">
                                    <label class="control-label">{{$work == 1 ? 'Số tiền rút của nhóm' : 'Số point của nhóm'}} {{$customer_group->name}}</label>
                                </div>
                                <div class="col-lg-6">
                                    <input type="number" class="form-control" name="val[{{ $customer_group->id }}]" value="{{ $work == 1 ? $customer_group->bonus : $customer_group->point_number}}">
                                </div>
                            </div>
                        @endforeach
                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('script')
    <script type="text/javascript">
    </script>
@endsection
