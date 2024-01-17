<{$jquery}>
<script>
    $().ready(function(){
        $("#sort").sortable({ opacity: 0.6, cursor: "move", update: function() {
            var order = $(this).sortable("serialize");
            $.post("<{$xoops_url}>/modules/tad_form/save_sort.php", order, function(theResponse){
                $("#save_msg").html(theResponse);
            });
        }
        });
    });

</script>

<h2><{$form.title}></h2>

<div><{$smarty.const._MD_TAD_FORM_SIGN_GROUP}>: <{"、"|implode:$form.sign_group_title}><{$form.title}></div>
<div><{$smarty.const._MD_TAD_FORM_VIEW_RESULT_GROUP}>: <{"、"|implode:$form.view_result_group_title}></div>
<div class="my-3">
    <a href="<{$xoops_url}>/modules/tad_form/manager.php?op=tad_form_main_create&ofsn=<{$form.ofsn}>" class="btn btn-warning"><{$smarty.const._MD_TAD_FORM_MANAGER}></a>
    <a href="<{$xoops_url}>/modules/tad_form/manager.php?op=tad_form_fill_index&ofsn=<{$form.ofsn}>" class="btn btn-primary"><{$smarty.const._MD_TAD_FORM_VIEW_RESULT}></a>
</div>

<div id="save_msg"></div>
<table class="table table-striped">
    <tr>
        <th nowrap><{$smarty.const._MD_TAD_FORM_COL_SORT}></th>
        <th><{$smarty.const._MD_TAD_FORM_COL_TITLE}> (<{$smarty.const._MD_TAD_FORM_COL_DESCRIPT}>)</th>
        <th><{$smarty.const._MD_TAD_FORM_COL_OPTIONS}></th>
        <th><{$smarty.const._MD_TAD_FORM_COL_VAL}></th>
        <th nowrap><{$smarty.const._MD_TAD_FORM_COL_CHK}></th>
        <th nowrap><{$smarty.const._MD_TAD_FORM_COL_PUBLIC}></th>
        <th nowrap><{$smarty.const._TAD_FUNCTION}></th>
    </tr>

    <tbody id="sort">
        <{foreach from=$all_tad_form_col item=col}>
            <tr id="tr_<{$col.csn}>">
                <td nowrap>
                    <a href="javascript:tad_form_col_destroy_func(<{$col.csn}>);" class="text-danger" title="<{$smarty.const._TAD_DEL}>"><i class="fa fa-times" aria-hidden="true"></i></a>
                    <{$col.sort}>
                </td>
                <td>
                    <span class="question"><b><{$col.title}></b></span>
                    <span class="badge badge-primary bg-primary text-white p-1"><{$col.kind}></span>
                    <{if $col.func}><span class="badge badge-success bg-success text-white p-1"><{$col.func}></span><{/if}>
                    <br>
                    <span class="note"><{$col.descript}></span>
                </td>
                <td><{$col.size}></td>
                <td><{$col.val}></td>
                <td nowrap>
                    <{if $col.chk}>
                        <a href='manager.php?op=change_chk&chk=0&csn=<{$col.csn}>&ofsn=<{$col.ofsn}>'><img src='<{$xoops_url}>/modules/tad_form/images/001_06.gif'></a>
                    <{else}>
                        <a href='manager.php?op=change_chk&chk=1&csn=<{$col.csn}>&ofsn=<{$col.ofsn}>'><img src='<{$xoops_url}>/modules/tad_form/images/001_05.gif'></a>
                    <{/if}>
                </td>
                <td nowrap>
                    <{if $col.public}>
                        <a href='manager.php?op=change_public&public=0&csn=<{$col.csn}>&ofsn=<{$col.ofsn}>'><img src='<{$xoops_url}>/modules/tad_form/images/001_06.gif'></a>
                    <{else}>
                        <a href='manager.php?op=change_public&public=1&csn=<{$col.csn}>&ofsn=<{$col.ofsn}>'><img src='<{$xoops_url}>/modules/tad_form/images/001_05.gif'></a>
                    <{/if}>
                </td>
                <td nowrap>
                    <img src="<{$xoops_url}>/modules/tadtools/treeTable/images/updown_s.png" style="cursor: s-resize;" alt="<{$smarty.const._MD_TREETABLE_SORT_PIC}>" title="<{$smarty.const._MD_TREETABLE_SORT_PIC}>">
                    <a href="manager.php?op=tad_form_col_create&ofsn=<{$ofsn}>&csn=<{$col.csn}>&mode=modify" class="btn btn-sm btn-warning"><{$smarty.const._TAD_EDIT}></a>
                </td>
            </tr>
        <{/foreach}>
    </tbody>
</table>

<div class="text-right text-end">
    <a href="manager.php?op=tad_form_col_create&ofsn=<{$ofsn}>" class="btn btn-info"><{$smarty.const._MD_TAD_FORM_ADD_COL}></a>
</div>

<br style="clear:both">