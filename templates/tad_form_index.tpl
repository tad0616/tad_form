
<{$toolbar}>

<{if $op=="sign"}>
    <{includeq file="$xoops_rootpath/modules/$xoops_dirname/templates/op_`$op`.tpl"}>
<{elseif $op=="error"}>
    <div class="jumbotron">
        <h3><{$msg}></h3>
    </div>
<{elseif $now_op=="view"}>
    <h1><{$form_title}></h1>
    <table <{$tbl_set}>>
    <{$content}>
    <{foreach item=q from=$all}>
        <tr>
        <td <{$q.td_set}>><{$q.i}>. <b><{$q.title}></b></td>
        <td><{$q.val}></td>
        </tr>
    <{/foreach}>
    </table>
    <div class="text-center">
    <{if $show_report}>
        <a href="report.php?ofsn=<{$ofsn}>" class="btn btn-info"><{$smarty.const._TADFORM_VIEW_FORM}></a>
    <{/if}>
    <a href="index.php?op=sign&ofsn=<{$ofsn}>" class="btn btn-success"><{$smarty.const._MD_TADFORM_BACK_TO_FORM}></a>
    </div>
<{else}>
    <{foreach item=form from=$all}>
        <div class="well card card-body bg-light m-1">
        <h3><{$form.title}></h3>
        <div><{$form.multi_sign}><{$form.date}></div>
            <hr>
            <{$form.content}>

            <div class="text-center">

                <a href="index.php?op=sign&ofsn=<{$form.ofsn}>" class="btn btn-lg btn-block <{if $form.sign_ok}>btn-primary<{else}>disabled<{/if}>"><{$form.button}></a>

                <{if $form.view_ok}>
                <a href="report.php?ofsn=<{$form.ofsn}>" class="btn btn-info btn-lg btn-block"><{$smarty.const._TADFORM_VIEW_FORM}></a>
                <{/if}>

            </div>
        </div>
    <{foreachelse}>
        <h3><{$smarty.const._MD_TADFORM_EMPTY}></h3>
    <{/foreach}>
<{/if}>