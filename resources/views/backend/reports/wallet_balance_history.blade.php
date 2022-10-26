@extends('backend.layouts.app')

@section('content')

    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class=" align-items-center">
            <h1 class="h3">Danh sách biến động số dư người dùng </h1>
        </div>
    </div>

    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card">
                <form action="{{ route('wallet-balance.balance',$user_id) }}" method="GET">
                    <div class="card-header row gutters-5">
                        <div class="col text-center text-md-left">
                            <h5 class="mb-md-0 h6">Lịch sử số dư ví</h5>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group mb-0">
                                <input type="text" class="form-control form-control-sm aiz-date-range" id="search"
                                       name="date_range" @isset($date_range) value="{{ $date_range }}"
                                       @endisset placeholder="{{ translate('Daterange') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-md btn-primary" type="submit">
                                {{ translate('Filter') }}
                            </button>
                        </div>
                    </div>
                </form>
                <div class="card-body">

                    <table class="table aiz-table mb-0">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>{{translate('Customer')}}</th>
                            <th> {{translate('Point')}}</th>
                            <th>{{translate('Date')}}</th>
                            <th>Số điểm trước</th>
                            <th>Số điểm sau</th>
                            <th>Người xác nhận</th>
                            <th>{{translate('Content')}}</th>
                        </thead>
                        <tbody>
                        @foreach ($logs as $key => $log)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                @if ($log->user != null)
                                    <td>{{ $log->user->name }}</td>
                                @else
                                    <td>{{ translate('User Not found') }}</td>
                                @endif
                                <td>  @if($log->point>0)
                                        <span class="text-success">
                                        +{{ single_price($log->point)}}
                                      </span>
                                    @else
                                        <span class="text-danger">
                                         {{ single_price($log->point)}}
                                     </span>
                                    @endif</td>
                                <td>{{ date('d/m/Y',strtotime($log->created_at))}}</td>
                                <td>
                                    {{ single_price($log->amount_first)}}
                                </td>
                                <td>{{ single_price($log->amount_later)}}</td>
                                <td>
                                    @if($log->acceptor!=null )
                                        @if($log->acceptor->user_type=="admin" )
                                            <span class="badge badge-inline badge-success">Admin</span>
                                        @endif
                                        @if($log->acceptor->user_type == 'accountant')
                                            <span class="badge badge-inline badge-info">CTV</span>
                                        @endif
                                    @endif
                                </td>
                                <td>{{ $log->content}}</td>

                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="aiz-pagination mt-4">
                        {{ $logs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
