<h2><{$form.title}></h2>
<div><{$form.content}></div>

<div class="my-2">
    <{if $smarty.session.tad_form_manager}>
        <a href="<{$xoops_url}>/modules/tad_form/manager.php?op=tad_form_main_create&ofsn=<{$form.ofsn}>" class="btn btn-warning"><{$smarty.const._MD_TAD_FORM_MANAGER}></a>
        <a href="<{$xoops_url}>/modules/tad_form/manager.php?op=tad_form_col_index&ofsn=<{$form.ofsn}>" class="btn btn-info"><{$smarty.const._MD_TAD_FORM_EDIT_ALL}></a>
        <a href="<{$xoops_url}>/modules/tad_form/manager.php?op=tad_form_fill_index&ofsn=<{$form.ofsn}>" class="btn btn-primary"><{$smarty.const._MD_TAD_FORM_VIEW_RESULT}></a>
    <{elseif $form.can_view_result && $code}>
        <a href="index.php?op=tad_form_fill_show&ofsn=<{$form.ofsn}>&code=<{$code}>" class="btn btn-info"><{$smarty.const._MD_TAD_FORM_VIEW_RESULT}></a>
    <{/if}>
</div>


<form action="<{$xoops_url}>/modules/tad_form/index.php" method="post" name="myForm" id="myForm" enctype="multipart/form-data" class="form-horizontal" role="form">
    <{if $form.kind=="application" || $form.show_result==1 && $form.can_view_result}>

        <{if $form.kind=="application"}>
            <b><{$smarty.const._MD_TAD_FORM_OK_LIST}></b>
            <div class="row">
            <{foreach from=$form.all_apply key=email item=fill}>
                <div class="col-sm-4 mb-2"><{$fill.email}>@<{$fill.fill_time}></div>
            <{/foreach}>
            </div>
        <{/if}>
    <{/if}>

    <div style="border-bottom:1px dotted gray;padding-bottom:6px;"><img src="<{$xoops_url}>/modules/tad_form/images/star.png" alt="<{$smarty.const._MD_TAD_FORM_NEED_SIGN}>" hspace=3><{$smarty.const._MD_TAD_FORM_IS_NEED_SIGN}></div>

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
            <{$col.col_form}>
        </div>
    <{/foreach}>

    <{$token_form}>
    <input type="hidden" name="ssn" value="<{$db_ans_ssn}>">
    <input type="hidden" name="ofsn" value="<{$form.ofsn}>">
    <input type="hidden" name="op" value="tad_form_value_save">

    <{if $form.captcha}>
        <div class="form-group row mb-3">
            <label class="col-sm-6 col-form-label text-sm-right text-sm-end control-label">
                <{$smarty.const._MD_TAD_FORM_CAPTCHA}>
            </label>
            <div class="col-sm-2">
                    <img src="<{$xoops_url}>/modules/tad_form/mkpic.php?ofsn=<{$form.ofsn}>" alt="captcha">
            </div>
            <div class="col-sm-2">
                <input type="text" name="security_images_<{$form.ofsn}>" class="form-control">
            </div>
        </div>
    <{/if}>

    <div class="form-group row mb-3">
        <label class="col-sm-2 col-form-label text-sm-right text-sm-end control-label">
            <{$smarty.const._MD_TAD_FORM_MAN_NAME}>
        </label>
        <div class="col-sm-2">
            <label for="man_name" style="display:none;">man_name</label>
                <input type="text" name="man_name" id="man_name" class="form-control validate[required]" value="<{if $man_name}><{$man_name}><{else}><{$smarty.session.now_user.name}><{/if}>">
        </div>
        <label class="col-sm-2 col-form-label text-sm-right text-sm-end control-label">
            <{$smarty.const._MD_TAD_FORM_EMAIL}>
        </label>
        <div class="col-sm-4">
            <label for="tfemail" style="display:none;">tfemail</label>
            <input type="text" name="email" id="tfemail"  class="form-control validate[required]" value="<{if $email}><{$email}><{else}><{$smarty.session.now_user.email}><{/if}>">
            <div class="text-danger"><{$smarty.const._MD_TAD_FORM_EMAIL_TIP}></div>
        </div>
        <div class="col-sm-2">
            <button type="submit" name="submit" class="btn btn-primary"><{$smarty.const._MD_TAD_FORM_SUBMIT_FORM}></button>
        </div>
    </div>
</form>

<{includeq file="$xoops_rootpath/modules/tad_form/templates/tad_form_fill_history.tpl"}>
