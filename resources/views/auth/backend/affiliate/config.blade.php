@extends('backend.layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{translate('Thưởng giới thiệu khách hàng')}}</h5>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('business_settings.update') }}" method="POST">
                        @csrf
                        <div class="form-group row">
                            <div class="col-lg-3">
                                <label class="control-label">Số tiền thưởng nhân viên / đơn hàng</label>
                            </div>
                            <div class="col-lg-6">
                                <input type="hidden" name="types[]" value="affiliate_employee_value">
                                <input type="text" class="form-control" name="affiliate_employee_value" value="{{ get_setting('affiliate_employee_value') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-3">
                                <label class="control-label">Số tiền thưởng ctv / đơn hàng</label>
                            </div>
                            <div class="col-lg-6">
                                <input type="hidden" name="types[]" value="affiliate_kol_value">
                                <input type="text" class="form-control" name="affiliate_kol_value" value="{{ get_setting('affiliate_kol_value') }}">
                            </div>
                        </div>
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
