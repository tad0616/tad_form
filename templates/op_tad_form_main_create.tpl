<{if $smarty.get.ofsn}>
    <h2><{$form.title}></h2>
    <div class="my-3">
        <a href="<{$xoops_url}>/modules/tad_form/manager.php?op=tad_form_col_index&ofsn=<{$form.ofsn}>" class="btn btn-sm btn-info"><{$smarty.const._MD_TAD_FORM_EDIT_ALL}></a>
        <a href="<{$xoops_url}>/modules/tad_form/manager.php?op=tad_form_fill_index&ofsn=<{$form.ofsn}>" class="btn btn-sm btn-primary"><{$smarty.const._MD_TAD_FORM_VIEW_RESULT}></a>
        <a href="<{$xoops_url}>/modules/tad_form/index.php?op=tad_form_fill_create&ofsn=<{$smarty.get.ofsn}>&ssn=<{$smarty.get.ssn}>&code=<{$smarty.get.code}>" class="btn btn-sm btn-success"><{$smarty.const._MD_TAD_FORM_BACK_TO_FORM}></a>
    </div>
<{else}>
    <h2><{$smarty.const._MD_TAD_FORM_ADD}></h2>
<{/if}>


<form action="manager.php" method="post" id="myForm" enctype="multipart/form-data" role="form" class="form-horizontal">

    <div class="row">
        <div class="col-sm-5">

            <div class="form-group row mb-3">
                <label class="col-sm-4 control-label col-form-label text-sm-right text-sm-end">
                    <{$smarty.const._MD_TAD_FORM_TITLE}>
                </label>
                <div class="col-sm-8">
                    <input type="text" name="title" id="title" value="<{$title}>" class="validate[required] form-control" >
                </div>
            </div>


            <div class="form-group row mb-3">
                <label class="col-sm-4 control-label col-form-label text-sm-right text-sm-end">
                    <{$smarty.const._MD_TAD_FORM_ADM_EMAIL}>
                </label>
                <div class="col-sm-8">
                    <input type="text" name="adm_email" id="adm_email" value="<{$adm_email}>" class="validate[required] form-control" >
                </div>
            </div>


            <div class="form-group row mb-3">
                <label class="col-sm-4 control-label col-form-label text-sm-right text-sm-end">
                    <{$smarty.const._MD_TAD_FORM_START_DATE}>
                </label>
                <div class="col-sm-8">
                    <input type="text" name="start_date" id="start_date" class="form-control" value="<{$start_date}>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm'})">
                </div>
            </div>

            <div class="form-group row mb-3">
                <label class="col-sm-4 control-label col-form-label text-sm-right text-sm-end">
                    <{$smarty.const._MD_TAD_FORM_END_DATE}>
                </label>
                <div class="col-sm-8">
                    <input type="text" name="end_date" id="end_date" class="form-control" value="<{$end_date}>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm'})">
                </div>
            </div>

        </div>

        <div class="col-sm-7">
            <div class="row">
                <div class="col-sm-4">
                    <label style="display: block;">
                        <{$smarty.const._MD_TAD_FORM_USE_CAPTCHA}>
                    </label>
                    <div class="form-check form-check-inline radio-inline">
                        <input class="form-check-input" type="radio" name="captcha" id="captcha_1" value="1" <{if $captcha == "1"}>checked<{/if}>>
                        <label class="form-check-label" for="captcha_1"><{$smarty.const._YES}></label>
                    </div>
                    <div class="form-check form-check-inline radio-inline">
                        <input class="form-check-input" type="radio" name="captcha" id="captcha_0" value="0" <{if $captcha != "1"}>checked<{/if}>>
                        <label class="form-check-label" for="captcha_0"><{$smarty.const._NO}></label>
                    </div>
                </div>

                <div class="col-sm-4" data-bs-toggle="tooltip" data-toggle="tooltip" title="<{$smarty.const._MD_TAD_FORM_MULTI_SIGN_TIP}>">
                    <label style="display: block;">
                        <{$smarty.const._MD_TAD_FORM_MULTI_SIGN}>
                    </label>
                    <{if '3'|in_array:$sign_group}>
                        訪客可填時，不開放多次填寫
                        <input type="hidden" name="multi_sign" value="0">
                    <{else}>
                        <div class="form-check form-check-inline radio-inline">
                            <input class="form-check-input" type="radio" name="multi_sign" id="multi_sign_1" value="1" <{if $multi_sign == "1"}>checked<{/if}>>
                            <label class="form-check-label" for="multi_sign_1"><{$smarty.const._YES}></label>
                        </div>
                        <div class="form-check form-check-inline radio-inline">
                            <input class="form-check-input" type="radio" name="multi_sign" id="multi_sign_0" value="0" <{if $multi_sign != "1"}>checked<{/if}>>
                            <label class="form-check-label" for="multi_sign_0"><{$smarty.const._NO}></label>
                        </div>
                    <{/if}>
                </div>

                <div class="col-sm-4">
                    <label style="display: block;">
                        <{$smarty.const._MD_TAD_FORM_SHOW_RESULT}>
                    </label>
                    <div class="form-check form-check-inline radio-inline">
                        <input class="form-check-input" type="radio" name="show_result" id="show_result_1" value="1" <{if $show_result == "1"}>checked<{/if}>>
                        <label class="form-check-label" for="show_result_1"><{$smarty.const._YES}></label>
                    </div>
                    <div class="form-check form-check-inline radio-inline">
                        <input class="form-check-input" type="radio" name="show_result" id="show_result_0" value="0" <{if $show_result != "1"}>checked<{/if}>>
                        <label class="form-check-label" for="show_result_0"><{$smarty.const._NO}></label>
                    </div>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-sm-4">
                    <label><{$smarty.const._MD_TAD_FORM_KIND}></label>
                    <select name="kind" class="form-control">
                        <option value=""><{$smarty.const._MD_TAD_FORM_KIND0}></option>
                        <option value="application" <{if $kind=="application"}>selected<{/if}>><{$smarty.const._MD_TAD_FORM_KIND1}></option>
                    </select>
                </div>
                <div class="col-sm-4">
                    <label><{$smarty.const._MD_TAD_FORM_SIGN_GROUP}></label>
                    <{$sign_group_form}>
                </div>
                <div class="col-sm-4">
                    <label><{$smarty.const._MD_TAD_FORM_VIEW_RESULT_GROUP}></label>
                    <{$view_result_group_form}>
                </div>
            </div>
        </div>
    </div>


    <{$content_editor}>


    <div class="bar">
        <{$token_form}>
        <input type="hidden" name="enable" value="<{$enable}>">
        <input type="hidden" name="ofsn" value="<{$ofsn}>">
        <input type="hidden" name="op" value="<{$next_op}>">

        <{if !$ofsn}>
            <div class="text-center mb-2">
            <label class="checkbox inline"><input type="checkbox" name="edit_option" value="1" checked><{$smarty.const._MD_TAD_FORM_EDIT_OPTION}></label>
            </div>
        <{/if}>
        <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
    </div>
</form>