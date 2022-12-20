@extends('backend.layouts.app')

@section('content')

    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{translate('Danh sách thanh toán bảo hành')}}</h1>
            </div>
        </div>
    </div>


    <div class="card">
        <form class="" id="sort_payments" action="" method="GET">
            <div class="card-header row gutters-5">
                <div class="col">
                </div>

                <div class="mb-2 mb-md-0">
                    <div class="form-group mb-0">
                        <select name="status" id="status" class="form-control">
                            <option value="-1" > {{translate('Trạng thái thanh toán')}} </option>
                            <option value="0" @if(request('status') != null &&request('status')==0 ) selected @endif>{{translate('Chờ duyệt')}} </option>
                            <option value="1" @if(request('status') != null &&request('status')==1) selected @endif>{{translate('Đã duyệt')}} </option>
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group mb-0">
                        <input type="text" class="form-control" id="search" name="search" @isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="{{ translate('Nhập tên hoặc số điện thoại') }}">
                    </div>
                </div>
            </div>

            <div class="card-body">
                <table class="table aiz-table mb-0">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>{{translate('Tên')}}</th>
                        <th data-breakpoints="lg">{{translate('Email')}}</th>
                        <th data-breakpoints="lg">{{translate('Số điện thoại')}}</th>
                        <th data-breakpoints="lg" class="text-center">Trạng thái</th>
                        <th class="text-right">{{translate('Options')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($payment_guarantees as $key => $payment_guarantee)
                        @if ($payment_guarantee != null)
                            <tr>
                                <td>{{ $key+1 }}</td>

                                <td> {{$payment_guarantee->name}}</td>
                                <td>{{$payment_guarantee->email}}</td>
                                <td><a href="{{ route('customers.index', ['referred_by' => $payment_guarantee->user_id])}}">{{$payment_guarantee->phone}}</a></td>
                                <td class="text-center">
                                    @if($payment_guarantee->status ==\App\Utility\WarrantyCardUtility::STATUS_NEW)
                                        <span class="badge badge-inline badge-warning ">  {{$status[$payment_guarantee->status]}}</span>
                                    @elseif($payment_guarantee->status ==\App\Utility\WarrantyCardUtility::STATUS_SUCCESS)
                                        <span class="badge badge-inline badge-success">  {{$status[$payment_guarantee->status]}}</span>
                                    @else
                                        <span class="badge badge-inline badge-danger">  {{$status[$payment_guarantee->status]}}</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('warranty_bill.show',$payment_guarantee->id)}}" title="View">
                                        <i class="las la-eye"></i>
                                    </a>
                                  @if($payment_guarantee->status==\App\Utility\WarrantyCardUtility::STATUS_NEW)
                                        <button type="button" class="btn btn-soft-info btn-icon btn-circle btn-sm " onclick="confirmUpdate(`{{ $payment_guarantee->id }}`)" title="{{ translate('Update payment bill') }}">
                                            <i class="las la-money-bill"></i>
                                        </button>
                                        <button type="button" class="btn btn-soft-danger btn-icon btn-circle btn-sm " onclick="openCancelPayment(`{{ $payment_guarantee->id }}`)" title="{{ translate('Hủy yêu cầu') }}">
                                            <i class="las la-trash"></i>
                                        </button>
                                      @endif
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
                <div class="aiz-pagination">
                    {{ $payment_guarantees->appends(request()->input())->links() }}
                </div>
            </div>
        </form>
    </div>
    <div id="cancel-payment-modal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title h6">Hủy yêu cầu thanh toán</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="reason">Lý do hủy</label>
                        <input type="text" name="reason" class="form-control">
                        <input type="hidden" name="id" class="form-control">
                    </div>
                    <div class="text-center">
                        <button type="button" class="btn btn-link mt-2" data-dismiss="modal">{{translate('Cancel')}}</button>
                        <a href="" id="cancel_payment" class="btn btn-primary mt-2">Xác nhận</a>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection

@section('script')
    <script src="{{ asset('public/assets/js/sweetalert2@11.js') }}"></script>

    <script type="text/javascript">

        function confirmUpdate(id){
            Swal.fire({
                title: 'Bạn muốn cập nhật trạng thái thanh toán cho yêu cầu này ?',
                showCancelButton: true,
                confirmButtonText: 'Ok',
                cancelButtonText: `Cancel`,
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    change_status(id)
                }
            })
        }

        function change_status(id) {

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/admin/warranty_bill/' + id,
                type: 'POST',
                success: function (response) {
                    if(response.result === true) {
                        Swal.fire('Cập nhật thành công')
                        location.reload();
                    }else {
                        Swal.fire(response.message, '', 'error')
                    }
                }
            });
        }


        function openCancelPayment(id){
            let $_this = $('#cancel-payment-modal')
            $_this.modal('show');
            $_this.find('input[name=id]').val(id);
        }

        $('#cancel_payment').on('click', function (e){
          e.preventDefault()
            let $_this = $('#cancel-payment-modal')
            let id = $_this.find('input[name=id]').val();
            let reason = $_this.find('input[name=reason]').val();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/admin/cancel_warranty_bill/' + id,
                type: 'POST',
                data: {
                    reason:reason
                },
                success: function (response) {
                    if(response.result == true) {
                        $('#cancel-payment-modal').modal('hide')
                        Swal.fire(response.message)
                        location.reload();
                    }else {
                        $('#cancel-payment-modal').modal('hide')
                        Swal.fire(response.message, '', 'error')
                    }
                }
            });
        })


        $('#status').on('change',function (){
            $('#sort_payments').submit();
        })





    </script>
@endsection
