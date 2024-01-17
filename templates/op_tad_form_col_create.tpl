<h2><{$form.title}></h2>
<div class="my-3">
    <a href="<{$xoops_url}>/modules/tad_form/manager.php?op=tad_form_main_create&ofsn=<{$form.ofsn}>" class="btn btn-warning"><{$smarty.const._MD_TAD_FORM_MANAGER}></a>
    <a href="<{$xoops_url}>/modules/tad_form/manager.php?op=tad_form_col_index&ofsn=<{$form.ofsn}>" class="btn btn-info"><{$smarty.const._MD_TAD_FORM_EDIT_ALL}></a>
    <a href="<{$xoops_url}>/modules/tad_form/manager.php?op=tad_form_fill_index&ofsn=<{$form.ofsn}>" class="btn btn-primary"><{$smarty.const._MD_TAD_FORM_VIEW_RESULT}></a>
    <a href="<{$xoops_url}>/modules/tad_form/index.php?op=tad_form_fill_create&ofsn=<{$smarty.get.ofsn}>&ssn=<{$smarty.get.ssn}>&code=<{$smarty.get.code}>" class="btn btn-success"><{$smarty.const._MD_TAD_FORM_BACK_TO_FORM}></a>
</div>

<form action="manager.php" method="post" id="myForm" enctype="multipart/form-data" class="form-horizontal" role="form">
    <div class="alert alert-info">
        <div class="form-group row mb-3">
            <label class="col-sm-2 control-label col-form-label text-sm-right text-sm-end">
                <{$smarty.const._MD_TAD_FORM_COL_TITLE}><{$sort}>
            </label>
            <div class="col-sm-10">
                <input type="text" name="title" id="title" value="<{$title}>" class="form-control">
            </div>
        </div>

        <div class="form-group row mb-3">
            <label class="col-sm-2 control-label col-form-label text-sm-right text-sm-end">
                <{$smarty.const._MD_TAD_FORM_COL_DESCRIPT}>
            </label>
            <div class="col-sm-10">
                <textarea name="descript" id="descript" class="form-control" rows=4><{$descript}></textarea>
            </div>
        </div>
    </div>


    <div class="form-group row mb-3">
        <label class="col-sm-1 control-label col-form-label text-sm-right text-sm-end">
            <{$smarty.const._MD_TAD_FORM_COL_KIND}>
        </label>
        <div class="col-sm-2">
            <select name="kind" id="kind" size=1 class="form-control" onchange="toggleInput()">
                <option value="text" <{if $kind=="text"}>selected<{/if}>><{$smarty.const._MD_TAD_FORM_COL_TEXT}></option>
                <option value="radio" <{if $kind=="radio"}>selected<{/if}>><{$smarty.const._MD_TAD_FORM_COL_RADIO}></option>
                <option value="checkbox" <{if $kind=="checkbox"}>selected<{/if}>><{$smarty.const._MD_TAD_FORM_COL_CHECKBOX}></option>
                <option value="select" <{if $kind=="select"}>selected<{/if}>><{$smarty.const._MD_TAD_FORM_COL_SELECT}></option>
                <option value="textarea" <{if $kind=="textarea"}>selected<{/if}>><{$smarty.const._MD_TAD_FORM_COL_TEXTAREA}></option>
                <option value="upload" <{if $kind=="upload"}>selected<{/if}>><{$smarty.const._MD_TAD_FORM_COL_UPLOAD}></option>
                <option value="date" <{if $kind=="date"}>selected<{/if}>><{$smarty.const._MD_TAD_FORM_COL_DATE}></option>
                <option value="datetime" <{if $kind=="datetime"}>selected<{/if}>><{$smarty.const._MD_TAD_FORM_COL_DATETIME}></option>
                <option value="show" <{if $kind=="show"}>selected<{/if}>><{$smarty.const._MD_TAD_FORM_COL_SHOW}></option>
            </select>
        </div>
        <label class="col-sm-2 control-label col-form-label text-sm-right text-sm-end options" <{if $kind != 'radio' && $kind != 'checkbox' && $kind != 'select' && $kind != 'upload'}>style="display: none;"<{/if}>>
            <{$smarty.const._MD_TAD_FORM_COL_OPTIONS}>
        </label>
        <div class="col-sm-7 options" <{if $kind != 'radio' && $kind != 'checkbox' && $kind != 'select' && $kind != 'upload'}>style="display: none;"<{/if}>>
            <input type="text" name="size" value="<{if $size}><{$size}><{else}>.pdf,.jpg,.png,.gif,.docx,.pptx,.xlsx,.odt,.zip<{/if}>" class="form-control" placeholder="<{$smarty.const._MD_TAD_FORM_COL_NOTE}>" data-toggle="tooltip" data-bs-toggle="tooltip" title="<{$smarty.const._MD_TAD_FORM_COL_NOTE}>">
        </div>
    </div>

    <div class="form-group row mb-3">
        <label class="col-sm-1 control-label col-form-label text-sm-right text-sm-end">
            <{$smarty.const._MD_TAD_FORM_COL_PUBLIC}>
        </label>
        <div class="col-sm-2">
            <select name="public" size=1 class="form-control">
                <option value="1"" <{if $public=="1"}>selected<{/if}>><{$smarty.const._MD_TAD_FORM_COL_PUBLIC1}></option>
                <option value="0" <{if $public!="1"}>selected<{/if}>><{$smarty.const._MD_TAD_FORM_COL_PUBLIC0}></option>
            </select>
            <input type="hidden" name="sort" value="<{$sort}>">
        </div>

        <label class="col-sm-1 control-label col-form-label text-sm-right text-sm-end">
            <{$smarty.const._MD_TAD_FORM_COL_CHK}>
        </label>
        <div class="col-sm-2">
            <select name="chk" size=1 class="form-control">
                <option value="1"" <{if $chk=="1"}>selected<{/if}>><{$smarty.const._YES}></option>
                <option value="0" <{if $chk!="1"}>selected<{/if}>><{$smarty.const._NO}></option>
            </select>
        </div>

        <label class="col-sm-1 control-label col-form-label text-sm-right text-sm-end">
            <{$smarty.const._MD_TAD_FORM_COL_FUNC}>
        </label>
        <div class="col-sm-2">
            <select name="func" id="func" size=1 class="form-control">
                <option value="" <{if $func==""}>selected<{/if}>><{$smarty.const._MD_TAD_FORM_COL_NO_FUN}></option>
                <option value="sum" <{if $func=="sum"}>selected<{/if}>><{$smarty.const._MD_TAD_FORM_COL_SUM}></option>
                <option value="avg" <{if $func=="avg"}>selected<{/if}>><{$smarty.const._MD_TAD_FORM_COL_AVG}></option>
                <option value="count" <{if $func=="count"}>selected<{/if}>><{$smarty.const._MD_TAD_FORM_COL_COUNT}></option>
            </select>
        </div>

        <label class="col-sm-1 control-label col-form-label text-sm-right text-sm-end">
            <{$smarty.const._MD_TAD_FORM_COL_VAL}>
        </label>
        <div class="col-sm-2">
            <input type="text" name="val" id="val" value="<{$val}>" class="form-control">
        </div>
    </div>

    <div class="text-center">
        <{$token_form}>
        <{if $smarty.get.mode}>
            <input type="hidden" name="mode" value="<{$smarty.get.mode}>">
        <{else}>
            <label class="checkbox inline"><input type="checkbox" name="stop_tad_col_create" value="1"><{$smarty.const._MD_TAD_FORM_COL_END}></label>
        <{/if}>
        <input type="hidden" name="op" value="<{$next_op}>">
        <input type="hidden" name="csn" value="<{$csn}>">
        <input type="hidden" name="ofsn" value="<{$ofsn}>">

        <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
    </div>
</form>

<script>
    function toggleInput() {
        var kindSelect = document.getElementById('kind');
        var optionsInputs = document.getElementsByClassName('options');

        // 取得所選擇的值
        var selectedValue = kindSelect.value;

        // 遍歷所有 optionsInputs 元素，根據選擇的值來決定是否顯示或隱藏
        for (var i = 0; i < optionsInputs.length; i++) {
            if (selectedValue === 'radio' || selectedValue === 'checkbox' || selectedValue === 'select' || selectedValue === 'upload') {
                optionsInputs[i].style.display = 'block';  // 顯示 input 欄位
            } else {
                optionsInputs[i].style.display = 'none';   // 隱藏 input 欄位
            }
        }
    }
</script>