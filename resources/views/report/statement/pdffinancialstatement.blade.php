@extends('report.layout.pdfmaster')
@section('report_title')
    <div style="text-align:center"><span style="line-height: 40%; text-align:center; font-family:times;font-weight:bold;font-size:15pt;color:black;">{!! $reportst->fileDesc !!} AS ON: {!! $ason->monthName !!} {!! $ason->year !!}</span></div>
    {{--<title>Invoice</title>--}}
@endsection


@section('content')



    @if(!empty($data))
        <div class="container" style="overflow-x:auto;">
            <table class="table order-bank" >

                <thead>
                <tr class="row-line" style="border-bottom: solid; line-height: 200%;">
                    <th colspan="3" width="60%" style="font-size:10pt;">Particulars</th>
                    <th width="10%" style="font-size:10pt;">Note</th>
                    <th width="30%" style="font-size:10pt; text-align:center">Amount</th>
                </tr>
                </thead>
                <tbody>
                @foreach($data as $i => $row)
                    <tr style="line-height: 200%;">

                        @if($row->textPosition == 5)
                            <td colspan="3" width="60%" style="border-bottom-width:0.2px; font-size:12pt;">{!! $row->texts !!}</td>
                            <td width="10%" style="border-bottom-width:0.2px; font-size:12pt;">{!! $row->note !!}</td>
                            @if($row->figrPosition ==60)
                                <td width="25%" align="right" style="border-bottom-width:0.2px; font-size:12pt;">{!! (number_format(abs($row->prntVal),2)) !!}</td>
                            @endif
                        @endif

                        @if($row->textPosition == 10)
                            <td  width="10%"></td>
                            <td colspan="2" width="50%" style="border-bottom-width:0.2px; font-size:10pt; font-weight: bold">{!! $row->texts !!}</td>
                            <td width="10%" style="border-bottom-width:0.2px; font-size:10pt; font-weight: bold">{!! $row->note !!}</td>
                            @if($row->figrPosition ==60)
                                <td width="25%" align="right" style="border-bottom-width:0.2px; font-size:10pt; font-weight: bold">{!! (number_format(abs($row->prntVal),2)) !!}</td>
                            @endif
                        @endif

                        @if($row->textPosition == 15)
                            <td colspan="2"  width="15%"></td>
                            <td width="45%" style="border-bottom-width:0.2px; font-size:10pt;">{!! $row->texts !!}</td>
                            <td width="10%" style="border-bottom-width:0.2px; font-size:10pt;">{!! $row->note !!}</td>
                            @if($row->figrPosition ==60)
                                <td width="25%" align="right" style="border-bottom-width:0.2px; font-size:10pt;">{!! (number_format(abs($row->prntVal),2)) !!}</td>
                            @endif
                        @endif

                        {{--@if($row->textPosition == 10)--}}
                            {{--<td></td>--}}
                            {{--<td style="font-size: 20px">{!! $row->texts !!}</td>--}}
                            {{--<td></td>--}}
                            {{--<td>{!! $row->note !!}</td>--}}
                            {{--@if($row->figrPosition ==60)--}}
                                {{--<td align="right">{!! (number_format(abs($row->prntVal),2)) !!}</td>--}}
                            {{--@endif--}}
                        {{--@endif--}}

                        {{--@if($row->textPosition == 15)--}}
                            {{--<td></td>--}}
                            {{--<td></td>--}}
                            {{--<td style="font-size: 15px">{!! $row->texts !!}</td>--}}
                            {{--<td>{!! $row->note !!}</td>--}}
                            {{--@if($row->figrPosition ==60)--}}
                                {{--<td align="right">{!! (number_format(abs($row->prntVal),2)) !!}</td>--}}
                            {{--@endif--}}
                        {{--@endif--}}



                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

    @endif

@stop

