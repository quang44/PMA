<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>
    <meta http-equiv="Content-Type" content="text/html;"/>
    <meta charset="UTF-8">
    <style media="all">
        @font-face {
            font-family: 'Roboto';
            src: url("{{ static_asset('fonts/Roboto-Regular.ttf') }}") format("truetype");
            font-weight: normal;
            font-style: normal;
        }
        *{
            margin: 0;
            padding: 0;
            line-height: 1.3;
            font-family: 'Roboto';
            color: #333542;
        }
        body{
            font-size: .875rem;
        }
        .gry-color *,
        .gry-color{
            color:#878f9c;
        }
        table{
            width: 100%;
        }
        table th{
            font-weight: normal;
        }
        table.padding th{
            padding: .5rem .7rem;
        }
        table.padding td{
            padding: .7rem;
        }
        table.sm-padding td{
            padding: .2rem .7rem;
        }
        .border-bottom td,
        .border-bottom th{
            border-bottom:1px solid #eceff4;
        }
        .text-left{
            text-align:left;
        }
        .text-right{
            text-align:right;
        }
        .small{
            font-size: .85rem;
        }
        .currency{

        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
@foreach($orders as $key => $order)
<div>
    @if(get_setting('system_logo_white') != null)
        @php
            $logo = uploaded_asset(get_setting('system_logo_white'));
        @endphp
    @else
        @php
            $logo = static_asset('assets/img/logo.png');
        @endphp
    @endif
    <div style="padding: 1rem;">
        <table>
            <tr>
                <td style="width: 70%">
                    <img src="{{ $logo }}" height="40" style="display:inline-block;">
                </td>
                <td style="width: 30%;text-align: center">
                    @if($order->collect_amount > 0)
                        <p><b>COD</b></p>
                        <p><b>{{ number_format($order->collect_amount, 0, '.', '.') }}</b></p>
                    @endif
                </td>
            </tr>
        </table>
    </div>
    <div style="padding: 1rem;border-top: 1px solid #000000">
        <table>
            <tr>
                <td style="text-align: center">
                    <h1>{{ $order->id }}</h1>
                </td>
            </tr>
        </table>
    </div>
    <div style="padding: 1rem;border-top: 1px solid #000000">
        <table>
            <tr>
                <td style="padding: 2px 0">
                    <b style="font-size: 15px;">NGƯỜI GỬI</b>
                </td>
            </tr>
            <tr>
                <td style="padding: 2px 0">
                    <b>{{ $order->source_name }}</b> - <b>{{ substr_replace($order->source_phone, '*****', 3, 5) }}</b>
                </td>
            </tr>
            <tr>
                <td style="padding: 2px 0">
                    {{ implode(', ', [$order->source_address,$order->source_ward, $order->source_district, $order->source_province]) }}
                </td>
            </tr>
        </table>
    </div>
    <div style="padding: 1rem;border-top: 1px solid #000000">
        <table>
            <tr>
                <td style="padding: 2px 0">
                    <b style="font-size: 15px;">NGƯỜI NHẬN</b>
                </td>
            </tr>
            <tr>
                <td style="padding: 2px 0">
                    <b>{{ $order->dest_name }}</b> - <b>{{ substr_replace($order->dest_phone, '*****', 3, 5) }}</b>
                </td>
            </tr>
            <tr>
                <td style="padding: 2px 0">
                    {{ implode(', ', [$order->dest_address,$order->dest_ward, $order->dest_district, $order->dest_province]) }}
                </td>
            </tr>
        </table>
    </div>
    <div style="padding: 1rem;border-top: 1px solid #000000">
        <table>
            <tr>
                <td style="text-align: center;padding: 2px 0;width: 75%;background: none">
                    <div style="width: 90%;overflow: hidden;background: none">
                        <img src="data:image/png;base64,{{ \Milon\Barcode\DNS1D::getBarcodePNG($order->partner_code, 'C128', 2.2 , 80, [0,0,0], true) }}" alt="barcode"   />
                    </div>
                </td>
                <td style="vertical-align: text-top;text-align:center;border-left:1px solid #000000;height: 100px">
                    Chữ ký
                </td>
            </tr>

        </table>
    </div>
    <div style="padding: 1rem;border-top: 1px solid #000000">
        <table>
            <tr>
                <td >
                    <span>
                        <b>Sản phẩm : </b>
                        {{ $order->product_name }}
                    </span>
                </td>
            </tr>
            <tr>
                <td >
                    <span>
                        <b>Ghi chú : </b>
                        {{ $order->note }}
                    </span>
                </td>
            </tr>
        </table>
    </div>
</div>
@if($key+1 < count($orders))
<div class="page-break"></div>
@endif
@endforeach
</body>
</html>
