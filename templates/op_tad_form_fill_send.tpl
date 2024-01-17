<{include file="$xoops_rootpath/modules/tad_form/templates/op_tad_form_fill_mail.tpl"}>

<div class="row">
    <{foreach from=$mail_test item=test}>
        <div class="col-sm-6">
            <div class="rounded my-2 p-3 bg-light border">
                <span class="badge badge-info bg-info">To: <{$test.mail}></span>
                <span class="badge badge-success bg-success"><{$test.title}></span>
                <div><{$test.content}></div>
            </div>
        </div>
    <{/foreach}>
</div>
