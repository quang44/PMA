@extends('backend.layouts.app')

@section('content')

    <div class="aiz-titlebar text-left mt-2 mb-3 row">
        <div class=" col-md-6 align-items-center">
            <h1 class="h3">{{translate('List of gift')}}</h1>
        </div>
        <div class="col-md-6 text-md-right">
            <a href="{{route('gift.create')}}" class="btn btn-circle btn-info">
                <span>{{translate('Add New Gift')}}</span>
            </a>
        </div>
    </div>

    <div class="card">
        <form class="" id="sort_Gift" action="" method="GET">
            <div class="card-header row gutters-5">
                <div class="col">
                    <h5 class="mb-0 h6">{{translate('Gift')}}</h5>
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
                        <select name="sort_status" id="sort_selectGift" class="form-control aiz-selectpicker"
                                data-selected-text-format="count"
                                data-live-search="true"
                        >
                            <option value="-1">{{translate('gift status')}}</option>
                            <option value="0"
                                    @if(request('sort_status',-1)==0) selected @endif>{{translate('Show')}}</option>
                            <option value="1"
                                    @if(request('sort_status',-1)==1) selected @endif>{{translate('Hidden')}}</option>

                        </select>
                    </div>
                </div>
                                <div class="col-md-3">
                                    <div class="form-group mb-0">
                                        <input type="text" class="form-control form-control-sm" id="search" name="search" @isset($search) value="{{ $search }}" @endisset placeholder="Tìm kiếm quà tặng">
                                    </div>
                                </div>
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
                            <th data-breakpoints="lg">{{translate('Name')}}</th>
                            <th data-breakpoints="lg">{{translate('Image')}}</th>
                            <th data-breakpoints="lg">{{translate('Point')}}</th>
                            <th data-breakpoints="lg">{{translate('Status')}}</th>
                            <th data-breakpoints="lg">{{translate('Created_at')}}</th>
                            <th class="text-right">{{translate('Tùy chọn')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($gifts as $key => $gift)
                            @if ($gift != null)
                                <tr>

                                    <td>
                                        <div class="form-group d-inline-block">
                                            <label class="aiz-checkbox">
                                                <input type="checkbox" class="check-one" name="id[]"
                                                       value="{{$gift->id}}">
                                                <span class="aiz-square-check"></span>
                                            </label>
                                        </div>
                                    </td>

                                    <td>{{$gift->name}}</td>
                                    <td><img width="110px" src="{{uploaded_asset($gift->image)}}" alt="{{translate('Gift')}}"></td>
                                    <td>{{$gift->point}}</td>
                                    <td>
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input value="4" type="checkbox" @if($gift->status==0) checked @endif onclick="ChangeStatus( {{$gift->id}},{{$gift->status}})" >
                                            <span class="slider round"></span>
                                        </label>
                                    </td>
                                    <td>{{date('d-m-Y',strtotime($gift->created_at))}}</td>
                                    <td class="text-right">
                                        <a href="{{ route('gift.edit', [ encrypt($gift->id) ]) }}"
                                           class="btn btn-soft-warning btn-icon btn-circle btn-sm"
                                           title="{{ translate('Cập nhật thông tin thẻ') }}">
                                            <i class="las la-edit"></i>
                                        </a>

                                        <a href="#"
                                           class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                           data-href="{{route('gift.destroy', $gift->id)}}"
                                           title="{{ translate('Delete') }}">
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
                    {{ $gifts->appends(request()->input())->links() }}
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
    @include('modals.confirm_modal')
    @include('modals.delete_modal')
@endsection

@section('script')
    <script src="{{ asset('public/assets/js/sweetalert2@11.js') }}"></script>
    <script type="text/javascript">


      $('#sort_selectGift').on('change',function () {
          $('#sort_Gift').submit();
      })

      $('#search').on('change',function () {
          console.log($(this).val())
          $('#sort_Gift').submit();
      })

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


        function  ChangeStatus(id,status) {

            if(status==0){
                status=1;
            }else{
                status=0;
            }
            $.post('{{ route('gift.update_status') }}', {_token:'{{ csrf_token() }}',
                id:id, status:status}, function(data){
                if(data.result == 1){
                    location.reload()
                    AIZ.plugins.notify('success', '{{ translate('update status successfully') }}');
                } else{
                    location.reload()
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });

        }


        function bulk_delete() {
            var data = new FormData($('#sort_Gift')[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('gift.bulk-delete')}}",
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
