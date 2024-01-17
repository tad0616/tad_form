<h2><{$form.title}></h2>
<{$form.content}>


<{foreach from=$form.col key=csn item=col name=form_col}>
    <div class="p-2 q_col rounded">
        <{if $smarty.session.tad_form_manager}>
            <a href="<{$xoops_url}>/modules/tad_form/manager.php?op=tad_form_col_create&ofsn=<{$form.ofsn}>&csn=<{$csn}>&mode=update" class="btn btn-sm btn-warning pull-right float-right pull-end"><{$smarty.const._TAD_EDIT}></a>
        <{/if}>
        <{if $col.kind=="show"}>
            <div><b><{$col.title}></b></div>
            <{$col.descript}>
        <{else}>
            <span class="question">
                <{$smarty.foreach.form_col.iteration}>.
                <{if $col.chk}>
                    <img src="<{$xoops_url}>/modules/tad_form/images/star.png" alt="<{$smarty.const._MD_TAD_FORM_NEED_SIGN}>" hspace=3>
                <{/if}>
                <{$col.title}>
            </span>
            <{if $col.descript}><span class="note">(<{$col.descript}>)</span><{/if}>
        <{/if}>
    </div>
    <div class="p-2 mb-3">
        <{if $col.kind!=""}>
            <td style="max-width:20rem; white-space: unset;">
            <{if $col.kind=="checkbox"}>
                <{$ans.$csn|replace:';':'<br>'}>
            <{elseif $col.kind=="textarea"}>
                <{$ans.$csn|nl2br}>
            <{else}>
                <{$ans.$csn}>
            <{/if}>
            </td>
        <{/if}>
    </div>
<{/foreach}>


<div class="bar">
    <a href="index.php?op=tad_form_fill_create&ofsn=<{$smarty.get.ofsn}>&ssn=<{$smarty.get.ssn}>&code=<{$smarty.get.code}>" class="btn btn-success"><{$smarty.const._MD_TAD_FORM_BACK_TO_FORM}></a>
</div>


<{includeq file="$xoops_rootpath/modules/tad_form/templates/tad_form_fill_history.tpl"}>
