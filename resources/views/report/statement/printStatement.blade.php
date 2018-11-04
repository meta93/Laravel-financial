@extends('layouts.master')

@section('banner')
    @include('layouts.banner')
@endsection
@section('menu')
    @include('layouts.menu')
@endsection

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <br/>

    <div class="container spark-screen">
        <div class="row">
            <div class="col-md-6 col-md-offset-2" >
                <br/>
                <div><h3>Financial Statements</h3></div>
                <div style="background-color: #ff0000;height: 2px">&nbsp;</div>
                <br/>


                <div class="div">
                    <br/>
                    {!! Form::open(['url'=>'printStatementIndex', 'method' => 'GET']) !!}

                    <table width="50%" class="table table-responsive table-hover" >

                        <tr>
                            <td width="5%"><label for="type" class="control-label">Select Statement</label></td>
                            <td width="10%">{!! Form::select('fileNo', $fileList, array('id' => 'fileNo', 'class' => 'form-control')) !!}</td>
                        </tr>

                        <tr>
                            <td width="5%"><label for="type" class="control-label">Select Month</label></td>
                            <td width="10%">{!! Form::selectMonth('month', 1, ['class' => 'col-md-4 form-control']) !!}</td>
                        </tr>
                        <tr>
                            <td width="10%"><button name="submittype" type="submit" value="preview" class="btn btn-info btn-reject pull-left">Preview</button></td>
                            <td width="10%"><button name="submittype" type="submit" value="print" class="btn btn-primary btn-approve pull-right">Print</button></td>
                        </tr>

                    </table>

                    {!! Form::close() !!}

                </div>
            </div>

            <div style="width: 5px"></div>

        </div>
    </div>


    {{--<div class="row remove">--}}

        {{--{!! Form::open(array('action' => 'Report\Statements\FinancialStatementController@createStatementData','method' => 'GET')) !!}--}}

        {{--{!! Form::open(['url'=>'printStatementIndex', 'method' => 'GET']) !!}--}}

        {{--<div class = "col-md-5 col-md-offset-2 form-inline">--}}

            {{--{!! Form::label('fileNo', 'Select Statement', array('class' => 'col-sm-2 control-label')) !!}--}}
            {{--{!! Form::select('fileNo', $fileList, array('id' => 'fileNo', 'class' => 'col-sm-3 form-controll')) !!}--}}
        {{--</div>--}}
        {{--<div class = "col-md-5 form-inline">--}}
            {{--{!! Form::label('month', 'Select Month', array('class' => 'col-sm-2 control-label')) !!}--}}
            {{--{!! Form::selectMonth('month', 1, array('class' => 'col-sm-3 form-control')) !!}--}}
        {{--</div>--}}
        {{--<br>--}}
        {{--<br>--}}
        {{--<div class="col-md-6 col-md-offset-3">--}}
            {{--{!! Form::submit('Print',['id' => 'submit', 'class'=>'btn btn-primary form-control']) !!}--}}
        {{--</div>--}}

        {{--{!! Form::close() !!}--}}
    {{--</div>--}}

    <div class="flash-message">
        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
            @if(Session::has('alert-' . $msg))
                <p style="text-align: center" class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
            @endif
        @endforeach
    </div> <!-- end .flash-message -->

    @if(!empty($data))

        <div class="row">
            {{--<div class="col-sm-1 sidenav"></div>--}}
            <div class="col-sm-8 col-sm-offset-2 panel panel-default padding-left">
                <div class="panel-heading"><strong>{!! $data[0]->fileNo !!}</strong> </div>


                <table class="table table-bordered table-hover padding" >

                    <colgroup>
                        {{--<col style="width: 40px"/>--}}
                        {{--<col style="width: 50px"/>--}}
                        {{--<col style="width: 100px"/>--}}
                        {{--<col style="width: 50px"/>--}}
                        {{--<col style="width: 50px"/>--}}
                        {{--<col style="width: 50px"/>--}}
                        {{--<col style="width: 50px"/>--}}
                    </colgroup>
                    <thead style="background-color: #66afe9">
                        <th colspan="3">PARTICULARS</th>
                        <th>NOTE</th>
                        <th style="text-align: right">AMOUNT</th>
                    </thead>
                    <tbody>
                    @foreach ($data as $i => $row)
                        <tr>
                            @if($row->textPosition == 5)
                                <td style="font-size: 25px">{!! $row->texts !!}</td>
                                <td>{!! $row->note !!}</td>
                                @if($row->figrPosition ==60)
                                    <td align="right">{!! (number_format(abs($row->prntVal),2)) !!}</td>
                                @endif
                            @endif

                            @if($row->textPosition == 10)
                                <td></td>
                                <td style="font-size: 20px">{!! $row->texts !!}</td>
                                <td></td>
                                <td>{!! $row->note !!}</td>
                                    @if($row->figrPosition ==60)
                                        <td align="right">{!! (number_format(abs($row->prntVal),2)) !!}</td>
                                        @endif
                            @endif

                            @if($row->textPosition == 15)
                                <td></td>
                                <td></td>
                                <td style="font-size: 15px">{!! $row->texts !!}</td>
                                <td>{!! $row->note !!}</td>
                                    @if($row->figrPosition ==60)
                                        <td align="right">{!! (number_format(abs($row->prntVal),2)) !!}</td>
                                        @endif
                            @endif

                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <br>

                {{--<div class="form-group">--}}
                    {{--{!! Form::submit('SUBMIT',['class'=>'btn btn-primary form-control']) !!}--}}
                    {{--{!! Form::close() !!}--}}
                {{--</div>--}}
            </div>
        </div>


    @endif


    <script>
        $(function() {
            setInterval(function(){
                $('.alert').slideUp(500);
            }, 10000);
        });
    </script>

    {{--<script>--}}

        {{--$("#submit").click(function(e){--}}

            {{--e.preventDefault();--}}
            {{--$(this).closest('div.remove').hide();--}}
            {{--return true;--}}

        {{--});--}}

    {{--</script>--}}


@stop


