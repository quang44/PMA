@extends('backend.layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-6 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{translate('Update warranty code')}}</h5>
                </div>

                <form action="{{ route('warranty_codes.update',$warranty_code->id)}}" method="POST">
                    @method('PUT')
                    @csrf
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="Seri">{{translate('code')}}<span
                                    class="text-danger"> *</span> </label>
                            <div class="col-sm-9">
                                <input type="text" placeholder="{{translate('code')}}" id="code" name="code"
                                       value="{{ old('code',$warranty_code->code) }}" class="form-control"  required>
                                @error('code')
                                <div class="" style="color: red">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="Seri">Phục hồi<span
                                    class="text-danger"> *</span> </label>
                            <div  class="col-sm-9">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input  data-toggle="tooltip" name="status" value="{{$warranty_code->status}}" type="checkbox" @if($warranty_code->status==1) checked @else disabled  @endif onclick="ChangeStatus( {{$warranty_code->id}},{{$warranty_code->status}})" >
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            <i class="ml-3 mt-2 text-danger">phục hồi sẽ khôi phục lại mã sang chưa sử dụng  </i>
                        </div>


                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script !src="" type="text/javascript">
        function  ChangeStatus(id,status) {
            $.post('{{ route('warranty_code.update_status') }}', {_token:'{{ csrf_token() }}',
                id:id, status:status},
                function(data){
                if(data.result == true){
                    location.reload()
                    AIZ.plugins.notify('success', 'Mã bảo hành đã được phục hồi thành công');
                } else{
                    location.reload()
                    AIZ.plugins.notify('danger', 'Đã xảy ra sự cố');
                }
            });

        }
    </script>
@endsection
