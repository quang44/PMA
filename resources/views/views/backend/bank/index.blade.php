@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="h3">{{ translate('Danh sách ngân hàng') }}</h1>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h6 class="mb-0 fw-600">{{ translate('Tất cả') }}</h6>
            <a href="{{ route('banks.create') }}" class="btn btn-primary">{{ translate('Thêm mới') }}</a>
        </div>
        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                <tr>
                    <th data-breakpoints="lg">#</th>
                    <th>{{translate('Name')}}</th>
                    <th data-breakpoints="md">{{translate('Icon')}}</th>
                    <th class="text-right">{{translate('Actions')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($banks as $key => $bank)

                    <tr>
                        <td>{{ $key+1 }}</td>

                        <td>{{ $bank->name }}</td>
                        <td>
                            <img src="{{ uploaded_asset($bank->icon) }}" alt="icon" class="h-50px">
                        </td>
                        <td class="text-right">

                            <a href="{{route('banks.edit', $bank->id )}}"
                               class="btn btn-icon btn-circle btn-sm btn-soft-primary" title="Edit">
                                <i class="las la-pen"></i>
                            </a>
                            <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                               data-href="{{ route('banks.destroy', $bank->id)}} " title="{{ translate('Delete') }}">
                                <i class="las la-trash"></i>
                            </a>

                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection
