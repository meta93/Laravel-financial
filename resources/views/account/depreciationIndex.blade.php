@extends('layouts.master')
@section('title')
    <title>Fixed Asset Depriciation</title>
@endsection
@section('banner')
    @include('layouts.banner')
@endsection

@section('menu')
    @include('layouts.menu')
@endsection

@section('content')
    <div class="col-sm-12 text-left">
        <div class="well text-center">
            <p>FIXED ASSET DEPRICIATION SETUP FOR {!! $fpData->monthName !!} {!! $fpData->year !!}</p>
        </div>
    </div>
    <br>

@include('partials.flashmessage');

    <div class="row" style="margin-left: 25px">
        <div class="col-lg-10 margin-tb">
            <div class="pull-left">
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-new">Add New Account</button>
            </div>
        </div>
    </div>

    <!-- Create Item Modal -->

    <div class="modal fade" id="modal-new" tabindex="-1" role="dialog" data-backdrop="false" aria-labelledby="modal-register-label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h3 class="modal-title" id="modal-register-label">Add New Account</h3>
                </div>



                    <div class="modal-body">

                        <form class="form-horizontal" role="form" method="POST" action="{{ url('dep.rate.save') }}">
                            {{ csrf_field() }}

                            {{--<label for="accNo" class="col-md-3 control-label">Account </label>--}}
                            {{--<div class="col-md-8">--}}
                                {{--{!! Form::select('accNo', $glHead,null, array('id' => 'accNo', 'class' => 'form-control', 'required')) !!}--}}
                            {{--</div>--}}


                            <div class="form-group{{ $errors->has('accNo') ? ' has-error' : '' }}">
                                <label for="accNo" class="col-md-3 control-label">Account</label>

                                <div class="col-md-8">
                                    {!! Form::select('accNo',$glHead, null , array('id' => 'accNo', 'class' => 'form-control')) !!}
                                    @if ($errors->has('accNo'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('accNo') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('rate') ? ' has-error' : '' }}">
                                <label for="rate" class="col-md-3 control-label">Rate </label>
                                <div class="col-md-8">
                                    {!! Form::text('rate', null , array('id' => 'rate', 'class' => 'form-control', 'required')) !!}
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('contraAcc') ? ' has-error' : '' }}">

                                <label for="contraAcc" class="col-md-3 control-label">Depreciation Acc </label>
                                <div class="col-md-8">
                                    {!! Form::select('contraAcc', $contraAcc,null, array('id' => 'contraAcc', 'class' => 'form-control','required')) !!}
                                </div>

                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </form>

                    </div>

                    <div class="modal-footer">

                        <button class="btn btn-info pull-right" data-dismiss="modal">Cancel</button>
                    </div>


            </div>
        </div>
    </div>


    <div class="row" style="margin-left: 25px; margin-right: 25px">
        <div class="col-sm-12" style="overflow-x:auto;">
            <table class="table table-bordered table-hover" id="data-table" >
                <thead>
                    <th>Acc No</th>
                    <th>Particulars</th>
                    <th>Contra</th>
                    <th>Opening</th>
                    <th>Additional</th>
                    <th>Total</th>
                    <th>Rate</th>
                    <th>Depr Amt</th>
                    <th>After Depr</th>
                    <th>Action</th>
                </thead>

                <tfoot>
                <tr>
                    <th colspan="3" style="text-align:right">Total : </th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                </tfoot>


            </table>
        </div>

    </div>




    <br>
    <br>


    {!! Form::open(['url'=>'postFixedAssetVoucher']) !!}

    <div class="col-md-10 col-md-offset-1">
        {!! Form::submit('POST',['class'=>'btn btn-primary pull-left']) !!}
    {{--</div>--}}

        {!! Form::close() !!}

        {!! Form::open(['url'=>'home', 'method' => 'GET']) !!}

    {{--<div class="col-md-4">--}}
        {!! Form::submit('EXIT',['class'=>'btn btn-danger pull-right']) !!}

        {!! Form::close() !!}
    </div>

@endsection


@push('scripts')

<script>
    $(function() {
        var table= $('#data-table').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            responsive: true,
            pageLength: 100,
            ajax: 'depreciation.data',
            columns: [
                { data: 'accNo', name: 'accNo' },
                { data: 'acc_no.accName', name: 'acc_no.accName' },
                { data: 'contraAcc', name: 'contraAcc' },
                { data: 'openBall', name: 'openBall' },
                { data: 'Addition', name: 'Addition' },
                { data: 'totalVal', name: 'totalVal' },
                { data: 'depRate', className: 'dt-right',render: $.fn.dataTable.render.number( ',', '.', 2 ), name: 'depRate' },
                { data: 'deprAmt', className: 'dt-right',render: $.fn.dataTable.render.number( ',', '.', 2 ), name: 'deprAmt' },
                { data: 'finalval', className: 'dt-right',render: $.fn.dataTable.render.number( ',', '.', 2 ), name: 'finalval' },
                { data: 'action', name: 'action', orderable: false, searchable: false, printable: false}
            ],

            "footerCallback": function ( row, data, start, end, display ) {
                var api = this.api(), data;
                var numFormat = $.fn.dataTable.render.number( '\,', '.', 2 ).display;

                // Remove the formatting to get integer data for summation
                var intVal = function ( i ) {
                    return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                            typeof i === 'number' ?
                                    i : 0;
                };

                // Total over all pages
                opntotal = api.column( 3 ).data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );

                adtntotal = api.column( 4 ).data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );

                bfrtotal = api.column( 5 ).data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );

                dprntotal = api.column( 7 ).data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );

                aftrtotal = api.column( 8 ).data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );


                // Update footer
                $( api.column( 3 ).footer() ).html(opntotal);
                $( api.column( 4 ).footer() ).html(adtntotal);
                $( api.column( 5 ).footer() ).html(numFormat(bfrtotal,2));

                $( api.column( 7 ).footer() ).html(dprntotal);
                $( api.column( 8 ).footer() ).html(numFormat(aftrtotal,2));

            }
        });
    });


    $('#data-table').on('click', '.btn-delete[data-remote]', function (e) {
        e.preventDefault();
        if(confirm("Are you sure you want to delete '"))
        {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var url = $(this).data('remote');
            // confirm then
            $.ajax({
                url: url,
                type: 'DELETE',
                dataType: 'json',
                data: {method: '_DELETE', submit: true}
            }).always(function (data) {
                $('#data-table').DataTable().draw(false);
            });
        }
        else
        {
            alert('Failed to Delete');
        }

    });


</script>

@endpush