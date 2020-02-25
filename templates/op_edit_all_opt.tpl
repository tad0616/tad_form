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
    function delete_tad_col_func(csn){
        var sure = window.confirm("<{$smarty.const._TAD_DEL_CONFIRM}>");
        if (!sure)  return;
        location.href="add.php?op=delete_tad_form_col&ofsn=<{$ofsn}>&csn=" + csn;
    }
</script>

<div id="save_msg"></div>

<table class="table table-striped">
    <tr>
        <th><{$smarty.const._MD_TADFORM_COL_SORT}></th>
        <th><{$smarty.const._MD_TADFORM_COL_TITLE}> (<{$smarty.const._MD_TADFORM_COL_DESCRIPT}>)</th>
        <th><{$smarty.const._MD_TADFORM_COL_KIND}></th>
        <th><{$smarty.const._MD_TADFORM_COL_SIZE}></th>
        <th><{$smarty.const._MD_TADFORM_COL_VAL}></th>
        <th><{$smarty.const._MD_TADFORM_COL_CHK}></th>
        <th><{$smarty.const._MD_TADFORM_COL_FUNC}></th>
        <th><{$smarty.const._MD_TADFORM_COL_PUBLIC}></th>
        <th><{$smarty.const._TAD_FUNCTION}></th>
    </tr>

    <tbody id="sort">
        <{foreach item=q from=$question}>
            <tr id="tr_<{$q.csn}>">
                <td><{$q.sort}> <img src="<{$xoops_url}>/modules/tadtools/treeTable/images/updown_s.png" style="cursor: s-resize;" alt="<{$smarty.const._MD_TREETABLE_SORT_PIC}>" title="<{$smarty.const._MD_TREETABLE_SORT_PIC}>"></td>
                <td><span class="question"><b><{$q.title}></b></span><br><span class="note"><{$q.descript}></span></td>
                <td><{$q.col_type}></td>
                <td><{$q.size}></td>
                <td><{$q.val}></td>
                <td><{$q.chk}></td>
                <td><{$q.func}></td>
                <td><{$q.public}></td>
                <td>
                    <a href="javascript:delete_tad_col_func(<{$q.csn}>);" class="btn btn-xs btn-danger"><{$smarty.const._TAD_DEL}></a>
                    <a href="add.php?op=edit_opt&ofsn=<{$ofsn}>&csn=<{$q.csn}>&mode=modify" class="btn btn-xs btn-warning"><{$smarty.const._TAD_EDIT}></a>
                </td>
            </tr>
        <{/foreach}>
    </tbody>
</table>

<div class="text-right">
    <a href="add.php?op=edit_opt&ofsn=<{$ofsn}>" class="btn btn-info"><{$smarty.const._MD_TADFORM_ADD_COL}></a>
</div>

<br style="clear:both">