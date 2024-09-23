
<{foreach from=$all_tad_form_main key=ofsn item=form}>
    <div class="well card card-body bg-light m-1">
        <h3><{$form.title}></h3>
        <div>
            <{if $form.multi_sign|default:false}>
                <img src="images/report_check.png" hspace=6 alt="<{$smarty.const._MD_TAD_FORM_MULTI_SIGN}>" title="<{$smarty.const._MD_TAD_FORM_MULTI_SIGN}>"><span class='badge badge-success bg-success'><{$smarty.const._MD_TAD_FORM_MULTI_SIGN}></span>
            <{/if}>
            <{$smarty.const._MD_TAD_FORM_FORMAT_SIGN_DATE|sprintf:$form.start_date:$form.end_date}>
        </div>
        <hr>
        <{$form.content}>
        <div class="row">
            <div class="col-lg-6 d-grid gap-2">
                <a href="index.php?op=tad_form_fill_create&ofsn=<{$ofsn|default:''}>" class="btn btn-lg btn-block btn-primary <{if !$form.can_fill}>disabled<{/if}>"><{if $form.can_fill|default:false}><{if $xoopsModuleConfig.show_amount && $form.fill_count}><{$smarty.const._MD_TAD_FORM_SIGN_NOW|sprintf:$form.title:$form.fill_count}><{else}><{$smarty.const._MD_TAD_FORM_SIGNNOW|sprintf:$form.title}><{/if}><{else}><{$smarty.const._MD_TAD_FORM_CANT_SIGN|sprintf:$form.title}><{/if}></a>
            </div>
            <div class="col-lg-6 d-grid gap-2">
                <a href="index.php?op=tad_form_fill_index&ofsn=<{$ofsn|default:''}>" class="btn btn-info btn-lg btn-block <{if !$form.can_view_result}>disabled<{/if}>"><{if $form.can_view_result|default:false}><{$smarty.const._MD_TAD_FORM_VIEW_RESULT}><{else}><{$smarty.const._MD_TAD_FORM_CANT_VIEW_RESULT}><{/if}></a>
            </div>
        </div>
    </div>
<{foreachelse}>
    <h3><{$smarty.const._MD_TAD_FORM_EMPTY}></h3>
<{/foreach}>
