
<script type="text/javascript" src="<{$xoops_url}>/modules/tadtools/My97DatePicker/WdatePicker.js"></script>
<form action="add.php" method="post" id="myForm" enctype="multipart/form-data" role="form" class="form-horizontal">

    <div class="row">
        <div class="col-sm-5">

            <div class="form-group row mb-3">
                <label class="col-sm-4 control-label col-form-label text-sm-right">
                    <{$smarty.const._MD_TADFORM_TITLE}>
                </label>
                <div class="col-sm-8">
                    <input type="text" name="title" id="title" value="<{$title}>" class="validate[required] form-control" >
                </div>
            </div>


            <div class="form-group row mb-3">
                <label class="col-sm-4 control-label col-form-label text-sm-right">
                    <{$smarty.const._MD_TADFORM_ADM_EMAIL}>
                </label>
                <div class="col-sm-8">
                    <input type="text" name="adm_email" id="adm_email" value="<{$adm_email}>" class="validate[required] form-control" >
                </div>
            </div>


            <div class="form-group row mb-3">
                <label class="col-sm-4 control-label col-form-label text-sm-right">
                    <{$smarty.const._MD_TADFORM_START_DATE}>
                </label>
                <div class="col-sm-8">
                    <input type="text" name="start_date" id="start_date" class="form-control" value="<{$start_date}>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm'})">
                </div>
            </div>

            <div class="form-group row mb-3">
                <label class="col-sm-4 control-label col-form-label text-sm-right">
                    <{$smarty.const._MD_TADFORM_END_DATE}>
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
                        <{$smarty.const._MD_TADFORM_USE_CAPTCHA}>
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

                <div class="col-sm-4">
                    <label style="display: block;">
                        <{$smarty.const._MD_TADFORM_MULTI_SIGN}>
                    </label>
                    <div class="form-check form-check-inline radio-inline">
                        <input class="form-check-input" type="radio" name="multi_sign" id="multi_sign_1" value="1" <{if $multi_sign == "1"}>checked<{/if}>>
                        <label class="form-check-label" for="multi_sign_1"><{$smarty.const._YES}></label>
                    </div>
                    <div class="form-check form-check-inline radio-inline">
                        <input class="form-check-input" type="radio" name="multi_sign" id="multi_sign_0" value="0" <{if $multi_sign != "1"}>checked<{/if}>>
                        <label class="form-check-label" for="multi_sign_0"><{$smarty.const._NO}></label>
                    </div>
                </div>

                <div class="col-sm-4">
                    <label style="display: block;">
                        <{$smarty.const._MD_TADFORM_SHOW_RESULT}>
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
                    <label><{$smarty.const._MD_TADFORM_KIND}></label>
                    <select name="kind" class="form-control"><{$kind_menu}></select>
                </div>
                <div class="col-sm-4">
                    <label><{$smarty.const._MD_TADFORM_SIGN_GROUP}></label>
                    <{$sign_group}>
                </div>
                <div class="col-sm-4">
                    <label><{$smarty.const._MD_TADFORM_VIEW_RESULT_GROUP}></label>
                    <{$view_result_group}>
                </div>
            </div>
        </div>
    </div>


    <{$editor}>


    <div class="text-center">
        <input type="hidden" name="enable" value="<{$enable}>">
        <input type="hidden" name="ofsn" value="<{$ofsn}>">
        <input type="hidden" name="op" value="<{$op}>">

        <{$next}>
        <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
    </div>
</form>