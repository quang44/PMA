@extends('backend.layouts.app')

@section('content')

@if(Auth::user()->user_type == 'admin' || in_array('25', json_decode(Auth::user()->staff->role->permissions)))
<div class="row gutters-12">
    <div class="col-lg-12">
        <div class="row gutters-10">

            <div class="col-lg-4 col-md-6 sm-12">
                <div class="bg-grad-2 text-white rounded-lg mb-4 overflow-hidden">
                    <div class="px-3 pt-3">
                        <div class="opacity-50">
                            <span class="fs-12 d-block">{{ translate('Total') }}</span>
                            {{ translate('Number of workers in the whole system') }}
                        </div>
                        <div class="h3 fw-700 mb-3">
                            {{ \App\Models\User::where('user_type', 'customer')->count() }} {{translate('Worker')}}
                        </div>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                        <path fill="rgba(255,255,255,0.3)" fill-opacity="1" d="M0,128L34.3,112C68.6,96,137,64,206,96C274.3,128,343,224,411,250.7C480,277,549,235,617,213.3C685.7,192,754,192,823,181.3C891.4,171,960,149,1029,117.3C1097.1,85,1166,43,1234,58.7C1302.9,75,1371,149,1406,186.7L1440,224L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z"></path>
                    </svg>
                </div>
            </div>


    <div class="col-lg-4 col-md-6 sm-12">
        <div class="bg-grad-4 text-white rounded-lg mb-4 overflow-hidden">
            <div class="px-3 pt-3">
                <div class="opacity-50">
                    <span class="fs-12 d-block">{{ translate('Total') }}</span>
                    {{ translate('All agents are on the system') }}
                </div>
                <div class="h3 fw-700 mb-3">{{ $agents }} {{ translate('Agent') }}</div>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                <path fill="rgba(255,255,255,0.3)" fill-opacity="1" d="M0,128L34.3,112C68.6,96,137,64,206,96C274.3,128,343,224,411,250.7C480,277,549,235,617,213.3C685.7,192,754,192,823,181.3C891.4,171,960,149,1029,117.3C1097.1,85,1166,43,1234,58.7C1302.9,75,1371,149,1406,186.7L1440,224L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z"></path>
            </svg>
        </div>
    </div>

    <div class="col-lg-4 col-md-6 sm-12">
        <div class="bg-grad-3 text-white rounded-lg mb-4 overflow-hidden">
            <div class="px-3 pt-3">
                <div class="opacity-50">
                    <span class="fs-12 d-block">{{ translate('Total') }}</span>
                    {{ translate('The entire depot is available on the system') }}
                </div>
                <div class="h3 fw-700 mb-3">{{ $depots }} {{ translate('depot') }}</div>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                <path fill="rgba(255,255,255,0.3)" fill-opacity="1" d="M0,128L34.3,112C68.6,96,137,64,206,96C274.3,128,343,224,411,250.7C480,277,549,235,617,213.3C685.7,192,754,192,823,181.3C891.4,171,960,149,1029,117.3C1097.1,85,1166,43,1234,58.7C1302.9,75,1371,149,1406,186.7L1440,224L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z"></path>
            </svg>
        </div>
    </div>

          <div class="col-lg-4 col-md-6 sm-12">
                <div class="bg-grad-1 text-white rounded-lg mb-4 overflow-hidden">
                    <div class="px-3 pt-3">
                        <div class="opacity-50">
                            <span class="fs-12 d-block">{{ translate('Total') }}</span>
                            {{ translate('Number of Gifts approved on the system') }}
                        </div>
                        <div class="h3 fw-700 mb-3">{{ $gifts }} {{translate('Gift')}}</div>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                        <path fill="rgba(255,255,255,0.3)" fill-opacity="1" d="M0,128L34.3,112C68.6,96,137,64,206,96C274.3,128,343,224,411,250.7C480,277,549,235,617,213.3C685.7,192,754,192,823,181.3C891.4,171,960,149,1029,117.3C1097.1,85,1166,43,1234,58.7C1302.9,75,1371,149,1406,186.7L1440,224L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z"></path>
                    </svg>
                </div>
            </div>
         <div class="col-lg-4 col-md-6 sm-12">
                <div class="bg-grad-4 text-white rounded-lg mb-4 overflow-hidden">
                    <div class="px-3 pt-3">
                        <div class="opacity-50">
                            <span class="fs-12 d-block">{{ translate('Total') }}</span>
                            {{ translate('Number of devices that have activated the whole system') }}
                        </div>
                        <div class="h3 fw-700 mb-3">{{ $total_active ? $total_active : 0 }} {{ translate('Thiết bị') }}</div>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                        <path fill="rgba(255,255,255,0.3)" fill-opacity="1" d="M0,128L34.3,112C68.6,96,137,64,206,96C274.3,128,343,224,411,250.7C480,277,549,235,617,213.3C685.7,192,754,192,823,181.3C891.4,171,960,149,1029,117.3C1097.1,85,1166,43,1234,58.7C1302.9,75,1371,149,1406,186.7L1440,224L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z"></path>
                    </svg>
                </div>
            </div>

        </div>
    </div>
</div>
@endif



@endsection
@section('script')
<script type="text/javascript">
    AIZ.plugins.chart('#pie-1',{
        type: 'doughnut',
        data: {
            labels: [
                '{{translate('Total published products')}}',
                '{{translate('Total sellers products')}}',
                '{{translate('Total admin products')}}'
            ],
            datasets: [
                {
                    data: [
                        {{ \App\Models\Product::where('published', 1)->count() }},
                        {{ \App\Models\Product::where('published', 1)->where('added_by', 'seller')->count() }},
                        {{ \App\Models\Product::where('published', 1)->where('added_by', 'admin')->count() }}
                    ],
                    backgroundColor: [
                        "#fd3995",
                        "#34bfa3",
                        "#5d78ff",
                        '#fdcb6e',
                        '#d35400',
                        '#8e44ad',
                        '#006442',
                        '#4D8FAC',
                        '#CA6924',
                        '#C91F37'
                    ]
                }
            ]
        },
        options: {
            cutoutPercentage: 70,
            legend: {
                labels: {
                    fontFamily: 'Poppins',
                    boxWidth: 10,
                    usePointStyle: true
                },
                onClick: function () {
                    return '';
                },
                position: 'bottom'
            }
        }
    });

    AIZ.plugins.chart('#pie-2',{
        type: 'doughnut',
        data: {
            labels: [
                '{{translate('Total sellers')}}',
                '{{translate('Total approved sellers')}}',
                '{{translate('Total pending sellers')}}'
            ],
            datasets: [
                {
                    data: [
                        {{ \App\Models\Shop::count() }},
                        {{ \App\Models\Shop::where('verification_status', 1)->count() }},
                        {{ \App\Models\Shop::where('verification_status', 0)->count() }}
                    ],
                    backgroundColor: [
                        "#fd3995",
                        "#34bfa3",
                        "#5d78ff",
                        '#fdcb6e',
                        '#d35400',
                        '#8e44ad',
                        '#006442',
                        '#4D8FAC',
                        '#CA6924',
                        '#C91F37'
                    ]
                }
            ]
        },
        options: {
            cutoutPercentage: 70,
            legend: {
                labels: {
                    fontFamily: 'Montserrat',
                    boxWidth: 10,
                    usePointStyle: true
                },
                onClick: function () {
                    return '';
                },
                position: 'bottom'
            }
        }
    });
    AIZ.plugins.chart('#graph-1',{
        type: 'bar',
        data: {
            labels: [
                @foreach ($root_categories as $key => $category)
                '{{ $category->getTranslation('name') }}',
                @endforeach
            ],
            datasets: [{
                label: '{{ translate('Number of sale') }}',
                data: [
                    {{ $cached_graph_data['num_of_sale_data'] }}
                ],
                backgroundColor: [
                    @foreach ($root_categories as $key => $category)
                        'rgba(55, 125, 255, 0.4)',
                    @endforeach
                ],
                borderColor: [
                    @foreach ($root_categories as $key => $category)
                        'rgba(55, 125, 255, 1)',
                    @endforeach
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    gridLines: {
                        color: '#f2f3f8',
                        zeroLineColor: '#f2f3f8'
                    },
                    ticks: {
                        fontColor: "#8b8b8b",
                        fontFamily: 'Poppins',
                        fontSize: 10,
                        beginAtZero: true
                    }
                }],
                xAxes: [{
                    gridLines: {
                        color: '#f2f3f8'
                    },
                    ticks: {
                        fontColor: "#8b8b8b",
                        fontFamily: 'Poppins',
                        fontSize: 10
                    }
                }]
            },
            legend:{
                labels: {
                    fontFamily: 'Poppins',
                    boxWidth: 10,
                    usePointStyle: true
                },
                onClick: function () {
                    return '';
                },
            }
        }
    });
    AIZ.plugins.chart('#graph-2',{
        type: 'bar',
        data: {
            labels: [
                @foreach ($root_categories as $key => $category)
                '{{ $category->getTranslation('name') }}',
                @endforeach
            ],
            datasets: [{
                label: '{{ translate('Number of Stock') }}',
                data: [
                    {{ $cached_graph_data['qty_data'] }}
                ],
                backgroundColor: [
                    @foreach ($root_categories as $key => $category)
                        'rgba(253, 57, 149, 0.4)',
                    @endforeach
                ],
                borderColor: [
                    @foreach ($root_categories as $key => $category)
                        'rgba(253, 57, 149, 1)',
                    @endforeach
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    gridLines: {
                        color: '#f2f3f8',
                        zeroLineColor: '#f2f3f8'
                    },
                    ticks: {
                        fontColor: "#8b8b8b",
                        fontFamily: 'Poppins',
                        fontSize: 10,
                        beginAtZero: true
                    }
                }],
                xAxes: [{
                    gridLines: {
                        color: '#f2f3f8'
                    },
                    ticks: {
                        fontColor: "#8b8b8b",
                        fontFamily: 'Poppins',
                        fontSize: 10
                    }
                }]
            },
            legend:{
                labels: {
                    fontFamily: 'Poppins',
                    boxWidth: 10,
                    usePointStyle: true
                },
                onClick: function () {
                    return '';
                },
            }
        }
    });
</script>
@endsection
