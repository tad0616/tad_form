<table class="table table-striped">
    <tr>
        <th><{$smarty.const._MD_TAD_FORM_TITLE}></th>
        <th nowrap><{$smarty.const._MD_TAD_FORM_RESULT}></th>
        <th nowrap><{$smarty.const._MD_TAD_FORM_DATE}></th>
        <th nowrap><{$smarty.const._TAD_FUNCTION}></th>
        <th nowrap><{$smarty.const._MD_TAD_FORM_COL_NUM}></th>
        <th nowrap><{$smarty.const._MD_TAD_FORM_OPTIONS}></th>
    </tr>
    <tbody>

    <{foreach from=$all_tad_form_main key=ofsn item=form}>
        <tr>
            <td>
                <{$ofsn|default:''}>
                <{if $form.enable|default:false}>
                    <a href="manager.php?op=tad_form_main_update_enable&ofsn=<{$ofsn|default:''}>&enable=0"><img src="<{$xoops_url}>/modules/tad_form/images/001_06.gif" hspace=2 alt="<{$smarty.const._MD_TAD_FORM_COL_ENABLE}>" title="<{$smarty.const._MD_TAD_FORM_COL_ENABLE}>"></a>
                <{else}>
                    <a href="manager.php?op=tad_form_main_update_enable&ofsn=<{$ofsn|default:''}>&enable=1"><img src="<{$xoops_url}>/modules/tad_form/images/001_05.gif" hspace=2 alt="<{$smarty.const._MD_TAD_FORM_COL_ACTIVE}>" title="<{$smarty.const._MD_TAD_FORM_COL_ACTIVE}>"></a>
                <{/if}>
                <a href="<{$xoops_url}>/modules/tad_form/index.php?op=tad_form_fill_create&ofsn=<{$ofsn|default:''}>" data-toggle="tooltip" data-bs-toggle="tooltip" title="<{$smarty.const._MD_TAD_FORM_SIGN_GROUP}>: <{if $form.sign_group_title|default:false}><{"、"|implode:$form.sign_group_title}><{/if}>"><{$form.title}></a>
            </td>
            <td>
                <a href="<{$xoops_url}>/modules/tad_form/manager.php?op=tad_form_fill_index&ofsn=<{$ofsn|default:''}>" class="btn btn-sm btn-info"><{$form.fill_count|intval}></a>
                <a href="<{$xoops_url}>/modules/tad_form/excel.php?ofsn=<{$ofsn|default:''}>" class="btn btn-sm btn-info">Excel</a>
                <a href="<{$xoops_url}>/modules/tad_form/manager.php?op=tad_form_fill_mail&ofsn=<{$ofsn|default:''}>" class="btn btn-sm btn-info">mail</a>
                <{if $form.show_result|default:false}>
                    <img src="<{$xoops_url}>/modules/tad_form/images/show_result.png" hspace=2 alt="<{$smarty.const._MD_TAD_FORM_SHOW_RESULT}>" data-toggle="tooltip" data-bs-toggle="tooltip" title="<{$smarty.const._MD_TAD_FORM_VIEW_RESULT_GROUP}>: <{if $form.view_result_group_title|default:false}><{"、"|implode:$form.view_result_group_title}><{/if}>">
                <{/if}>

                <{if $form.multi_sign|default:false}>
                    <img src="<{$xoops_url}>/modules/tad_form/images/multi_sign.png" hspace=2 alt="<{$smarty.const._MD_TAD_FORM_MULTI_SIGN}>" data-toggle="tooltip" data-bs-toggle="tooltip"  title="<{$smarty.const._MD_TAD_FORM_MULTI_SIGN}>">
                <{/if}>

                <{if '3'|in_array:$form.sign_group}>
                    <img src="<{$xoops_url}>/modules/tad_form/images/anonymous.png" hspace=2 alt="<{$smarty.const._MD_TAD_FORM_VISITORS_FILL}>" data-toggle="tooltip" data-bs-toggle="tooltip"  title="<{$smarty.const._MD_TAD_FORM_VISITORS_FILL}>">
                <{/if}>

            </td>
            <td nowrap><{$form.start_date|substr:0:16}><br><{$form.end_date|substr:0:16}></td>
            <td>
                <a href="<{$xoops_url}>/modules/tad_form/manager.php?op=tad_form_main_create&ofsn=<{$ofsn|default:''}>" class="btn btn-sm btn-warning"><i class="fa fa-pencil-square" aria-hidden="true"></i> <{$smarty.const._MD_TAD_FORM_EDIT}></a>
                <a href="manager.php?op=copy&ofsn=<{$ofsn|default:''}>" class="btn btn-sm btn-success"><{$smarty.const._MD_TAD_FORM_COPY_FORM}></a>
                <{if !$form.enable}>
                    <a href="javascript:tad_form_main_destroy_func(<{$ofsn|default:''}>);" class="btn btn-sm btn-danger"><{$smarty.const._TAD_DEL}></a>
                <{/if}>
            </td>
            <td class="text-center"><{$form.col_count}></td>
            <td>
                <a href="<{$xoops_url}>/modules/tad_form/manager.php?op=tad_form_col_index&ofsn=<{$ofsn|default:''}>" class="btn btn-sm btn-info"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> <{$smarty.const._MD_TAD_FORM_EDIT_ALL}></a>
                <a href="<{$xoops_url}>/modules/tad_form/manager.php?op=tad_form_col_create&ofsn=<{$ofsn|default:''}>" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-toggle="tooltip" title="<{$smarty.const._MD_TAD_FORM_ADD_COL}>"><i class="fa fa-plus" aria-hidden="true"></i>
                </a>
            </td>
        </tr>
    <{/foreach}>
    </tbody>
</table>
<{$bar|default:''}>