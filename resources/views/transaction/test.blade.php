<script type='text/javascript'>

//    $('.xxx').select2();

//    var counter = 1;
    $("#add-form").click(function() {
        if (counter > 10) {
            alert("Only 10 textboxes allow");
            return false;
        }
        $(".form-repaet:last").find('.xxx').select2('destroy');
        var clone = $(".form-repaet:last").clone();
        $('.form-wrapper').append(clone);
        $('.xxx').select2();

        counter++;
    });

    $("#removebtn").click(function() {
        if (counter == 1) {
            return false;
        }
        $(".form-repaet").last().remove();
        counter--;
    });

</script>

<button id="add-form">Add</button>

<div class="form-wrapper">
    <div class="form-repaet">
        <div class="form-group">
            <select id="selecttag" name="book[0].tag" class="form-control xxx select2 input-lg" style="width: 100%;">
                <option selected="selected">Tag</option>
                <option>1</option>
                <option>2</option>
            </select>
        </div>
        <div class="form-group">
            <select class="form-control xxx select2" name="book[0].is" style="width: 100%;">
                <option selected="selected">is</option>
                <option>1</option>
                <option>2</option>
            </select>
        </div>
        <div class="form-group">
            <select class="form-control xxx select2 test1" name="book[0].select_tag" style="width: 100%;">
                <option selected="selected">Select a Tag</option>
                <option>1</option>
                <option>2</option>
            </select>
        </div>
        <div class="clearfix"></div>
        <div class="line line-dashed line-lg pull-in"></div>
    </div>
</div>