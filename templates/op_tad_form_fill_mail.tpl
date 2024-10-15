<form action="<{$xoops_url}>/modules/tad_form/manager.php" method="post" id="myForm" class="form-horizontal" role="form">
    <div class="form-group row mb-3">
        <div class="col-sm-12">
        <input type="text" name="title" class="form-control" value="<{$form.title|sprintf:$smarty.const._MD_TAD_FORM_MAIL_FORMAT_TITLE}>" placeholder="<{$smarty.const._MD_TAD_FORM_MAIL_TITLE}>">
        </div>
    </div>

    <div class="form-group row mb-3">
        <div class="col-sm-10">
            <{$editor|default:''}>
        </div>
        <div class="col-sm-2">
            <span class="badge badge-danger bg-danger"><{$smarty.const._MD_TAD_FORM_SEND_TAG}></span>
            <div>{name}</div>
            <{foreach from=$form.col key=csn item=col}>
                <div>{<{$col.title}>}</div>
            <{/foreach}>
        </div>
    </div>

    <div class="form-group row mb-3">
        <div class="col-sm-12">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="checkAll" checked>
            <label class="form-check-label" for="checkAll">全選</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="send_test" id="test" value="1"  class="warning">
            <label class="form-check-label" for="test"><{$smarty.const._MD_TAD_FORM_MAIL_TEST}></label>
        </div>
        <{foreach from=$form.all_apply key=ssn item=fill}>
            <div class="form-check form-check-inline">
            <input class="form-check-input email" type="checkbox" name="email_ssn[<{$ssn|default:''}>]" id="email-<{$ssn|default:''}>" value="<{$fill.email}>" checked>
            <label class="form-check-label" for="email-<{$fill.man_name}>"><{$fill.man_name}></label>
            </div>
        <{/foreach}>
        </div>
    </div>

    <div class="text-center">
        <input type="hidden" name="op" value="tad_form_fill_send">
        <input type="hidden" name="ofsn" value="<{$form.ofsn}>">
        <button type="submit" class="btn btn-primary"><i class="fa fa-paper-plane-o" aria-hidden="true"></i> <{$smarty.const._MD_TAD_FORM_SEND}></button>
    </div>
</form>

<script type='text/javascript'>
    $(document).ready(function(){
        $("#checkAll").change(function() {
            console.log($("#checkAll").prop('checked'));
            $(".email").each(function() {
                $(this).prop("checked", $("#checkAll").prop('checked'));
            });
        });

    });
</script>
