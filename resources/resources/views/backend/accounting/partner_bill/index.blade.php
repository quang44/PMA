@extends('backend.layouts.app')

@section('content')

<div class="card">
    <form class="" action="" id="sort_orders" method="GET">
        <div class="card-header row gutters-5">
            <div class="col">
                <h5 class="mb-md-0 h6">{{ translate('Phiếu thanh toán đối tác') }}</h5>
            </div>
            <div class="col-lg-2">
                <div class="form-group mb-0">
                    <input type="text" class="aiz-date-range form-control" value="{{ request('date') }}" name="date" placeholder="{{ translate('Filter by date') }}" data-format="DD-MM-Y" data-separator=" to " data-advanced-range="true" autocomplete="off">
                </div>
            </div>
            <div class="col-lg-2">
                <div class="form-group mb-0">
                    <input type="text" class="form-control" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="{{ translate('Mã đối soát đối tác') }}">
                </div>
            </div>
            <div class="col-auto">
                <div class="form-group mb-0">
                    <button type="submit" class="btn btn-primary">{{ translate('Tìm kiếm') }}</button>
                </div>
            </div>
        </div>

        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th data-breakpoints="md">{{ translate('Mã đối soát') }}</th>
                        <th data-breakpoints="md">{{ translate('Thời gian tạo') }}</th>
                        <th data-breakpoints="md">{{ translate('Total') }}</th>
                        <th class="text-right">{{translate('options')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $key => $order)
                    <tr>
                        <td>
                            {{ $order->partner_bill_id }}
                        </td>
                        <td>
                            {{ date('d-m-Y H:i:s', $order->created_time) }}
                        </td>
                        <td>
                            {{ single_price($order->total_cod - $order->total_fee) }}
                        </td>
                        <td class="text-right">
                            <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('partner_bill.show', encrypt($order->id))}}" title="{{ translate('View') }}">
                                <i class="las la-eye"></i>
                            </a>
                            <button type="button" class="btn btn-soft-danger btn-icon btn-circle btn-sm" onclick="confirmCancel(`{{ $order->id }}`)"  title="{{ translate('Hủy phiếu thanh toán') }}">
                                <i class="las la-trash"></i>
                            </button>
<!--                            <a class="btn btn-soft-info btn-icon btn-circle btn-sm" href="{{ route('invoice.download', $order->id) }}" title="{{ translate('Download Invoice') }}">
                                <i class="las la-download"></i>
                            </a>
                            <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('orders.destroy', $order->id)}}" title="{{ translate('Delete') }}">
                                <i class="las la-trash"></i>
                            </a>-->
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="aiz-pagination">
                {{ $orders->appends(request()->input())->links() }}
            </div>

        </div>
    </form>
</div>

@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection

@section('script')
    <script src="{{ asset('public/assets/js/sweetalert2@11.js') }}"></script>
    <script type="text/javascript">


        function confirmCancel(id){
            Swal.fire({
                title: 'Bạn muốn hủy phiếu thanh toán này ?',
                showCancelButton: true,
                confirmButtonText: 'Ok',
                cancelButtonText: `Cancel`,
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    cancel(id)
                }
            })
        }

        function cancel(id) {

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: 'partner_bill/cancel/' + id,
                type: 'POST',
                /*data: {
                    id:id
                },*/
                success: function (response) {
                    if(response.result === true) {
                        location.reload();
                    }else {
                        Swal.fire(response.message, '', 'error')
                    }
                }
            });
        }
    </script>
@endsection
