/**
 * Created by ubuntu on 1/12/17.
 */

$('select[name="grpDebit[]"]').eq(0).change(function () {

    $.get("{!! url('cp.debit.head')  !!}", {option: $('select[name="grpDebit[]"]').eq(0).val()},
        function (data) {
            var accDr = $('select[name="accDr[]"]').eq(0);
//                                alert("You have selected the - ");
            accDr.empty();
            $.each(data, function (key, value) {
                accDr.append($("<option></option>")
                    .attr("value", key)
                    .text(value));
            });
        });
});



$( "#transForm" ).submit(function( event ) {
    $("#transForm").find('select').each(function () { this.disabled=false

    });
});