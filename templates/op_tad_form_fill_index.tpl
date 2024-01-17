<script>
    $(document).ready(function(){
        $("#GridView1").toSuperTable({ width:"98%" , height: "600px", fixedCols: 1 });
    });
</script>

<{if $form.title}>
    <h2><{$form.title}></h2>
<{else}>
    <h2 class="sr-only visually-hidden">report</h2>
<{/if}>
<div class="my-3">
    <{if $smarty.session.tad_form_manager}>
        <a href="<{$xoops_url}>/modules/tad_form/manager.php?op=tad_form_main_create&ofsn=<{$form.ofsn}>" class="btn btn-warning"><{$smarty.const._MD_TAD_FORM_MANAGER}></a>
        <a href="<{$xoops_url}>/modules/tad_form/manager.php?op=tad_form_col_index&ofsn=<{$form.ofsn}>" class="btn btn-info"><{$smarty.const._MD_TAD_FORM_EDIT_ALL}></a>
    <{/if}>
    <a href="<{$xoops_url}>/modules/tad_form/index.php?op=tad_form_fill_create&ofsn=<{$smarty.get.ofsn}>&ssn=<{$smarty.get.ssn}>&code=<{$smarty.get.code}>" class="btn btn-success"><{$smarty.const._MD_TAD_FORM_BACK_TO_FORM}></a>
</div>
<table class="table table-striped " id="GridView1">
    <tr>
        <th><{$smarty.const._MD_TAD_FORM_COL_WHO}></th>
        <{foreach from=$form.col key=csn item=col}>
            <{if $col.public || $smarty.session.tad_form_manager}>
                <th style="max-width:20rem; overflow: hidden;"><{$col.title}></th>
            <{/if}>
        <{/foreach}>
    </tr>
    <{foreach from=$all_tad_form_fill key=ssn item=fill}>
        <tr>
            <td style="text-align:center;">
                <{if $smarty.session.tad_form_manager}>
                    <a href="index.php?op=tad_form_fill_show&ofsn=<{$fill.ofsn}>&code=<{$fill.code}>" target="_blank"><{$fill.man_name}></a>
                <{else}>
                    <{$fill.man_name}>
                <{/if}>
                <div style='font-size: 0.8rem'><{$fill.fill_time}></div>
            </td>
            <{foreach from=$fill.form.col key=csn item=col}>
                <{if $col.kind!="" && ($col.public || $smarty.session.tad_form_manager)}>
                    <td style="max-width:20rem; white-space: unset;">
                    <{if $col.kind=="checkbox"}>
                        <{$fill.ans.$csn|replace:';':'<br>'}>
                    <{elseif $col.kind=="textarea"}>
                        <{$fill.ans.$csn|nl2br}>
                    <{else}>
                        <{$fill.ans.$csn}>
                    <{/if}>
                    </td>
                <{/if}>
            <{/foreach}>
        </tr>
    <{/foreach}>
</table>


<{if $analysis}>
    <h2><{$smarty.const._MD_TAD_FORM_ANALYSIS}></h2>
    <table class="table table-striped">
        <tr>
            <th><{$smarty.const._MD_TAD_FORM_COL_TITLE}></th>
            <th><{$smarty.const._MD_TAD_FORM_COL_FUNC}></th>
            <th><{$smarty.const._MD_TAD_FORM_ANALYSIS_RESULT}></th>
        </tr>
        <{foreach from=$analysis item=data}>
            <{if $data.func}>
                <tr>
                    <td><{$data.title}></td>
                    <td><{$data.func}></td>
                    <td>
                    <{if $data.func=="count"}>
                        <{foreach from=$data.val key=option item=val}>
                            <li><{$option}> = <{$val}></li>
                        <{/foreach}>
                    <{else}>
                        <{$data.val}>
                    <{/if}>
                    </td>
                </tr>
            <{/if}>
        <{/foreach}>
    </table>
<{/if}>