@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="h3">{{ translate('Quản trị câu hỏi') }}</h1>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h6 class="mb-0 fw-600">{{ translate('Danh sách câu hỏi') }}</h6>
            <a href="{{ route('questions.create') }}" class="btn btn-primary">{{ translate('Thêm câu hỏi') }}</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table aiz-table mb-0">
                    <thead>
                    <tr>
                        <th data-breakpoints="lg">#</th>
                        <th>{{translate('Đối tượng')}}</th>
                        <th>{{translate('Câu hỏi')}}</th>
                        <th data-breakpoints="md">{{translate('Câu trả lời')}}</th>
                        <th data-breakpoints="md">Ưu tiên</th>
                        <th class="text-right">{{translate('Actions')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($questions as $key => $question)

                        <tr>
                            <td>{{ $question->id }}</td>
                            <td>{{ $question->type == 'customer' ?  'Khách hàng' : 'Cộng tác viên'}}</td>

                            <td>{{ $question->question }}</td>
                            <td>
                                @php
                                    $text = new \Html2Text\Html2Text($question->answer);
                                    $text = $text->getText();
                                @endphp
                                {!! $text !!}
                            </td>
                            <td>{{ $question->priotity }}</td>
                            <td class="text-right">

                                <a href="{{route('questions.edit', ['id'=>$question->id] )}}" class="btn btn-icon btn-circle btn-sm btn-soft-primary" title="Edit">
                                    <i class="las la-pen"></i>
                                </a>

                                <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{ route('questions.destroy', $question->id)}} " title="{{ translate('Delete') }}">
                                    <i class="las la-trash"></i>
                                </a>

                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="aiz-pagination">
                {{ $questions->appends(request()->input())->links() }}
            </div>
        </div>



    </div>
@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection


