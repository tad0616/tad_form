<{$jquery|default:''}>
<script>
    $().ready(function(){
        $("#sort").sortable({ opacity: 0.6, cursor: "move", update: function() {
            var order = $(this).sortable("serialize");
            $.post("<{$xoops_url}>/modules/tad_form/save_sort.php?ofsn=<{$smarty.get.ofsn|intval}>", order, function(theResponse){
                $("#save_msg").html(theResponse);
            });
        }
        });
    });

</script>

<h2><{$form.title}></h2>

<div><{$smarty.const._MD_TAD_FORM_SIGN_GROUP}>: <{if $form.sign_group_title|default:false}><{"、"|implode:$form.sign_group_title}><{/if}></div>
<div><{$smarty.const._MD_TAD_FORM_VIEW_RESULT_GROUP}>: <{if $form.view_result_group_title|default:false}><{"、"|implode:$form.view_result_group_title}><{/if}></div>
<div class="my-3">
    <a href="<{$xoops_url}>/modules/tad_form/manager.php?op=tad_form_main_create&ofsn=<{$smarty.get.ofsn|intval}>" class="btn btn-sm btn-warning"><i class="fa fa-pen-to-square" aria-hidden="true"></i> <{$smarty.const._MD_TAD_FORM_EDIT}></a>
    <a href="<{$xoops_url}>/modules/tad_form/manager.php?op=tad_form_fill_index&ofsn=<{$smarty.get.ofsn|intval}>" class="btn btn-sm btn-primary"><i class="fa fa-list-alt" aria-hidden="true"></i> <{$smarty.const._MD_TAD_FORM_VIEW_RESULT}></a>
    <a href="<{$xoops_url}>/modules/tad_form/index.php?op=tad_form_fill_create&ofsn=<{$smarty.get.ofsn|intval}>&ssn=<{$smarty.get.ssn|default:0}>&code=<{$smarty.get.code|intval}>" class="btn btn-sm btn-success"><i class="fa fa-undo" aria-hidden="true"></i> <{$smarty.const._MD_TAD_FORM_BACK_TO_FORM}></a>
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
                    <{if $col.func|default:false}><span class="badge badge-success bg-success text-white p-1"><{$col.func}></span><{/if}>
                    <br>
                    <span class="note"><{$col.descript}></span>
                </td>
                <td><{$col.size}></td>
                <td><{$col.val}></td>
                <td nowrap>
                    <{if $col.chk|default:false}>
                        <a href='manager.php?op=change_chk&chk=0&csn=<{$col.csn}>&ofsn=<{$col.ofsn}>'><img src='<{$xoops_url}>/modules/tad_form/images/001_06.gif'></a>
                    <{else}>
                        <a href='manager.php?op=change_chk&chk=1&csn=<{$col.csn}>&ofsn=<{$col.ofsn}>'><img src='<{$xoops_url}>/modules/tad_form/images/001_05.gif'></a>
                    <{/if}>
                </td>
                <td nowrap>
                    <{if $col.public|default:false}>
                        <a href='manager.php?op=change_public&public=0&csn=<{$col.csn}>&ofsn=<{$col.ofsn}>'><img src='<{$xoops_url}>/modules/tad_form/images/001_06.gif'></a>
                    <{else}>
                        <a href='manager.php?op=change_public&public=1&csn=<{$col.csn}>&ofsn=<{$col.ofsn}>'><img src='<{$xoops_url}>/modules/tad_form/images/001_05.gif'></a>
                    <{/if}>
                </td>
                <td nowrap>
                    <img src="<{$xoops_url}>/modules/tadtools/treeTable/images/updown_s.png" style="cursor: s-resize;" alt="<{$smarty.const._MD_TREETABLE_SORT_PIC}>" title="<{$smarty.const._MD_TREETABLE_SORT_PIC}>">
                    <a href="manager.php?op=tad_form_col_create&ofsn=<{$ofsn|default:''}>&csn=<{$col.csn}>&mode=modify" class="btn btn-sm btn-warning"><i class="fa fa-pencil" aria-hidden="true"></i> <{$smarty.const._TAD_EDIT}></a>
                </td>
            </tr>
        <{/foreach}>
    </tbody>
</table>

<div class="text-right text-end">
    <a href="manager.php?op=tad_form_col_create&ofsn=<{$ofsn|default:''}>" class="btn btn-info"><i class="fa fa-plus" aria-hidden="true"></i> <{$smarty.const._MD_TAD_FORM_ADD_COL}></a>
</div>

<br style="clear:both">