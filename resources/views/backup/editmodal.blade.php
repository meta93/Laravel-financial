<div class="modal fade" id="postvoucherModal'.$trans->voucherNo.'" role="dialog" data-backdrop="false" style="background-color: rgba(0, 0, 0, 0.5);">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">close</button>
                <h4 class="modal-title">Post Voucher: "'.$trans->voucherNo.'" (You will not be able to update after post voucher)</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form" action="voucher.data.post/'.$trans->voucherNo.'" method="POST" >
                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" name="_token" value="'. csrf_token().'">
                    <input type="hidden" name="id" value="'.$trans->id.'">


                    <table class="table table-bordered table-hover" >
                        <thead style="background-color: #3278b3">
                        <th>Description</th>
                        <th>Acc Name</th>
                        <th style="text-align:right">Debit</th>
                        <th style="text-align:right">Credit</th>
                        <th style="text-align:right">User</th>
                        </thead>

                        <tbody>
                        <tr>
                            <td>"'.$transdetails[0]->transDesc1.'"</td>
                            <td>"'.$transdetails[0]->accDr.'"</td>
                            <td>"'.$transdetails[0]->accDr.'"</td>
                            <td>"'.$transdetails[0]->accDr.'"</td>
                            <td>"'.$transdetails[0]->accDr.'"</td>
                        </tr>
                        </tbody>

                    </table>

                    <button type="submit" class="btn btn-primary" id="update-data">Post Voucher</button>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-info" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>


<script>

            var oTable = $("#datakeluhan").DataTable({....});

    $('#modal-save').on('click', function(){
        $.ajax({
            method: 'POST',
            url: urlEdit,
            data: {boxkeluhan: $('#post-body').val(), postId: postId, _token: token}
        })
                .done(function (msg){
                    $(datakeluhan).text(msg['post_new']);
                    $('#edit-modal').modal('hide');
                    oTable.draw(false);
                });
    });




            $.get('voucher/details-data/' + voucherNo, function( data ) {


                                var accDr = $('select[name="accDr[]"]').eq(counter);
                                accDr.empty();
                                $.each(data, function (value) {
                                    $('#post-body').val(value);


                                    row = value.data()
                                    accDr.append($("<option></option>")
                                            .attr("value", key)
                                            .text(value));
                                    alert(value);
                                });

</script>


