<{$toolbar}>

<script>
    function delete_tad_form_main_func(ofsn){
        var sure = window.confirm("<{$smarty.const._TAD_DEL_CONFIRM}>");
        if (!sure)	return;
        location.href="manager.php?op=delete_tad_form_main&ofsn=" + ofsn;
    }
</script>

<table class="table table-striped">
    <tr>
        <th><{$smarty.const._MD_TADFORM_TITLE}></th>
        <th><{$smarty.const._MD_TADFORM_RESULT}></th>
        <th><{$smarty.const._MD_TADFORM_START_DATE}></th>
        <th><{$smarty.const._MD_TADFORM_END_DATE}></th>
        <th><{$smarty.const._TAD_FUNCTION}></th>
        <th><{$smarty.const._MD_TADFORM_COL_NUM}></th>
        <th><{$smarty.const._MD_TADFORM_OPTIONS}></th>
    </tr>
    <tbody>

    <{foreach item=form from=$form}>
        <{if $smarty.session.tad_form_adm or $form.uid==$now_uid}>
            <tr>
                <td>
                    <{$form.ofsn}><{$form.enable_tool}>
                    <a href="<{$xoops_url}>/modules/tad_form/index.php?op=sign&ofsn=<{$form.ofsn}>"><{$form.title}></a>
                    </td>
                <td>
                    <a href="<{$xoops_url}>/modules/tad_form/result.php?ofsn=<{$form.ofsn}>" class="btn btn-xs btn-info"><{$form.counter}></a>
                    <a href="<{$xoops_url}>/modules/tad_form/excel.php?ofsn=<{$form.ofsn}>" class="btn btn-xs btn-info">Excel</a>
                    <a href="<{$xoops_url}>/modules/tad_form/mail.php?ofsn=<{$form.ofsn}>" class="btn btn-xs btn-info">mail</a>
                    <{$form.show_result_pic}>
                    <{$form.multi_sign_pic}>
                </td>
                <td><{$form.start_date}></td>
                <td><{$form.end_date}></td>
                <td>
                    <a href="manager.php?op=copy&ofsn=<{$form.ofsn}>" class="btn btn-xs btn-success"><{$smarty.const._MD_TADFORM_COPY_FORM}></a>
                    <a href="javascript:delete_tad_form_main_func(<{$form.ofsn}>);" class="btn btn-xs btn-danger"><{$smarty.const._TAD_DEL}></a>
                    <a href="<{$xoops_url}>/modules/tad_form/add.php?op=tad_form_main_form&ofsn=<{$form.ofsn}>" class="btn btn-xs btn-warning"><{$smarty.const._TAD_EDIT}></a>
                </td>
                <td class="text-center"><{$form.cols_num}></td>
                <td>
                    <a href="<{$xoops_url}>/modules/tad_form/add.php?op=edit_opt&ofsn=<{$form.ofsn}>" class="btn btn-xs btn-info"><{$smarty.const._TAD_ADD}></a>
                <a href="<{$xoops_url}>/modules/tad_form/add.php?op=edit_all_opt&ofsn=<{$form.ofsn}>" class="btn btn-xs btn-warning"><{$smarty.const._TAD_EDIT}></a>
                </td>
            </tr>
        <{/if}>
    <{/foreach}>
    </tbody>
</table>
<{$bar}>