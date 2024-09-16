<{if $history_fill|default:false}>
    <div class="well card card-body bg-light" style="margin-top:30px;">
        <h3><{$smarty.const._MD_TAD_FORM_HISTORY}></h3>
        <table class="table table-striped">
        <{foreach from=$history_fill item=history}>
            <tr>
                <td><a href="index.php?op=tad_form_fill_show&ofsn=<{$history.ofsn}>&ssn=<{$history.ssn}>&code=<{$history.code}>"><{$history.fill_time}></a></td>
                <td><{$history.man_name}></td>
                <td><{$history.email}></td>
                <td class="text-right text-end">
                    <a href="javascript:tad_form_fill_destroy_func(<{$history.ssn}>)" class="btn btn-sm btn-danger"><{$smarty.const._TAD_DEL}></a>
                    <a href="<{$xoops_url}>/modules/tad_form/index.php?op=tad_form_fill_create&ofsn=<{$history.ofsn}>&ssn=<{$history.ssn|default:0}>&code=<{$history.code|default:''}>" class="btn btn-sm btn-warning"><{$smarty.const._TAD_EDIT}></a>
                </td>
            </tr>
        <{/foreach}>
        </table>
    </div>
<{/if}>
