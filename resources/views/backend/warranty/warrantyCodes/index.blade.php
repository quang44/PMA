@extends('backend.layouts.app')

@section('content')

    <div class="aiz-titlebar text-left mt-2 mb-3 row">
        <div class=" col-md-4 align-items-center">
            <h1 class="h3">{{translate('List of warranty codes')}}</h1>
        </div>
        <div class="col-md-5 ">
            <form class="form-horizontal" action="{{ route('warranty_codes.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group row">
                    <div class="col-sm-8">
                        <div class="custom-file">
                            <label class="custom-file-label">
                                <input type="file" name="bulk_file" class="custom-file-input" required>
                                <span class="custom-file-name">Chọn file excel</span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group col-sm-4">
                        <button type="submit" class="btn btn-info">Tải file excel</button>
                    </div>
                </div>
              @error('bulk_file')
                <p class="text-danger">
              {{$message}}
                </p>
                @enderror
            </form>
        </div>
        <div class="col-md-3 text-md-right">
            <a href="{{route('warranty_codes.create')}}" class="btn btn-circle btn-info">
                <span>{{translate('Add New warranty code')}}</span>
            </a>
        </div>
    </div>

    <div class="card">
        <form class="" id="sort_Card" action="" method="GET">
            <div class="card-header row gutters-5">
                <div class="col">
                    <h5 class="mb-0 h6">{{translate('Warranty Code')}}</h5>
                </div>
                <div class="dropdown mb-2 mb-md-0">
                    <button class="btn border dropdown-toggle" type="button" data-toggle="dropdown">
                        {{translate('Bulk Action')}}
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="#" onclick="bulk_delete()"> {{translate('Delete selection')}}</a>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group mb-0">
                        <select name="sort_status" id="sort_selectCart" class="form-control aiz-selectpicker"
                                data-selected-text-format="count"
                                data-live-search="true"
                        >
                            <option value="-1">{{translate('warranty code status')}}</option>
                            <option value="0"
                                    @if(request('sort_status',-1)==0) selected @endif>{{translate('Unused')}}</option>
                            <option value="1"
                                    @if(request('sort_status',-1)==1) selected @endif>{{translate('used')}}</option>

                        </select>
                    </div>
                </div>
                {{--                <div class="col-md-3">--}}
                {{--                    <div class="form-group mb-0">--}}

                {{--                        <input type="text" class="form-control" id="search" name="search"--}}
                {{--                               @isset($search) value="{{ $search }}"--}}
                {{--                               @endisset placeholder="{{ translate('Nhập tên khách hàng hoặc số sê-ri') }}">--}}
                {{--                    </div>--}}
                {{--                </div>--}}
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table aiz-table mb-0">
                        <thead>
                        <tr>
                            <th data-breakpoints="lg"><div class="form-group">
                                    <div class="aiz-checkbox-inline">
                                        <label class="aiz-checkbox">
                                            <input type="checkbox" class="check-all">
                                            <span class="aiz-square-check"></span>
                                        </label>
                                    </div>
                                </div></th>
                            <th data-breakpoints="lg">{{translate('Code')}}</th>
                            <th data-breakpoints="lg">{{translate('Status')}}</th>
                            <th data-breakpoints="lg">{{translate('Created_at')}}</th>
                            <th data-breakpoints="lg">{{translate('Use at')}}</th>
                            <th class="text-right">{{translate('Tùy chọn')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($warranty_codes as $key => $warranty_code)
                            @if ($warranty_code != null)
                                <tr>

                                    <td>
                                        <div class="form-group d-inline-block">
                                            <label class="aiz-checkbox">
                                                <input type="checkbox" class="check-one" name="id[]"
                                                       value="{{$warranty_code->id}}">
                                                <span class="aiz-square-check"></span>
                                            </label>
                                        </div>
                                    </td>

                                    <td>{{$warranty_code->code}}</td>
                                    <td>
                                        @if($warranty_code->status==0)
                                            <span
                                                class="badge badge-inline badge-success">{{translate('Unused')}}</span>
                                        @else
                                            <span class="badge badge-inline badge-danger">{{translate('Used')}}</span>

                                        @endif
                                    </td>

                                    <td>{{date('d-m-Y H:i:s',strtotime($warranty_code->created_at))}}</td>
                                    <td>
                                        @if($warranty_code->use_at==null)
                                          ---
                                            @else
                                            {{date('d-m-Y H:i:s',strtotime($warranty_code->use_at))}}</td>

                                    @endif
                                    <td class="text-right">
                                        <a href="{{ route('warranty_codes.edit',[ encrypt($warranty_code->id) ]) }}"
                                           class="btn btn-soft-warning btn-icon btn-circle btn-sm"
                                           title="{{ translate('Cập nhật thông tin thẻ') }}">
                                            <i class="las la-edit"></i>
                                        </a>

                                        <a href="#"
                                           class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                           data-href="{{route('warranty_codes.destroy', $warranty_code->id)}}"
                                           title="{{ translate('Xóa') }}">
                                            <i class="las la-trash"></i>
                                        </a>

                                    </td>
                                </tr>
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="aiz-pagination">
                    {{ $warranty_codes->appends(request()->input())->links() }}
                </div>
            </div>
        </form>
    </div>


    <div class="modal fade" id="confirm-ban">
        <form action="" id="confirmation" method="GET">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title h6">{{translate('Nhập lý do hủy')}}</h5>
                        <button type="button" class="close" data-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="text" class="form-control" name="reason" placeholder="Lý do hủy">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light"
                                data-dismiss="modal">{{translate('Hủy')}}</button>
                        <button type="submit" class="btn btn-primary">{{translate('Tiếp tục')}}</button>
                    </div>
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
        $(document).on("change", ".check-all", function() {
            if(this.checked) {
                // Iterate each checkbox
                $('.check-one:checkbox').each(function() {
                    this.checked = true;
                });
            } else {
                $('.check-one:checkbox').each(function() {
                    this.checked = false;
                });
            }

        });

        $('#sort_selectCart').on('change', function () {
            $('#sort_Card').submit();
        })

        function confirm_ban(url, status) {
            $('#confirm-ban').modal('show', {backdrop: 'static'});
            document.getElementById('confirmation').setAttribute('action', url + '?status=' + status);
        }

        function updateCard(url, status) {
            $('#confirm-update-bank').modal('show', {backdrop: 'static'});
            document.getElementById('updateCard').setAttribute('href', url + '?status=' + status);
        }


        function bulk_delete() {
            var data = new FormData($('#sort_Card')[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('warranty_codes.bulk-delete')}}",
                type: 'POST',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    if (response == 1) {
                        Swal.fire('Xóa thành công')
                        location.reload();
                    }
                }
            });
        }
    </script>
@endsection
