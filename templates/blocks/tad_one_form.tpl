
<{if $block.show_form=='1'}>
    <{foreach from=$block.sign_form key=k item=v}>
        <{assign var=$k value=$v}>
    <{/foreach}>

    <{if $error}>
        <h3><{$title}></h3>
        <div class="alert alert-danger">
            <p><{$error}></p>
        </div>
    <{else}>
        <{includeq file="$xoops_rootpath/modules/tad_form/templates/op_sign.tpl"}>
    <{/if}>
<{else}>
    <div class="well well-small card card-body bg-light m-1-small">
        <h3><{$block.title}></h3>
        <{$block.content}>
        <div class="text-center d-grid gap-2">
            <a href="<{$xoops_url}>/modules/tad_form/index.php?op=sign&ofsn=<{$block.ofsn}>" class="btn btn-block btn-primary"><{$block.sign_now}></a>
            <div class="date"><{$block.date}></div>
        </div>
    </div>
<{/if}>