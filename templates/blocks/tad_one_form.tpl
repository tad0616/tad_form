
<{if $block.fill|default:false}>
    <{foreach from=$block.sign_form key=k item=v}>
        <{assign var="$k" value=$v}>
    <{/foreach}>

    <{if $error|default:false}>
        <h3><{$title|default:''}></h3>
        <div class="alert alert-danger">
            <p><{$error|default:''}></p>
        </div>
    <{else}>
        <{include file="$xoops_rootpath/modules/tad_form/templates/op_tad_form_fill_create.tpl"}>
    <{/if}>
<{else}>
    <div class="well well-small card card-body bg-light m-1-small">
        <h3><{$block.form.title}></h3>
        <{$block.form.content}>
        <div class="text-center d-grid gap-2">
            <a href="<{$xoops_url}>/modules/tad_form/index.php?op=tad_form_fill_create&ofsn=<{$block.form.ofsn}>" class="btn btn-block btn-primary"><{$smarty.const._MB_TAD_FORM_SIGN_NOW|sprintf:$block.form.title:$block.form.fill_count}></a>
            <div class="date"><{$smarty.const._MB_TAD_FORM_SIGN_DATE|sprintf:$block.form.start_date:$block.form.end_date}></div>
        </div>
    </div>
<{/if}>