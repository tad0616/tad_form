<form action="add.php" method="post" id="myForm" enctype="multipart/form-data" class="form-horizontal" role="form">
    <div class="alert alert-info">
        <div class="form-group row">
            <label class="col-sm-2 control-label col-form-label text-sm-right">
                <{$smarty.const._MD_TADFORM_COL_TITLE}><{$sort}>
            </label>
            <div class="col-sm-10">
                <input type="text" name="title" id="title" value="<{$title}>" class="form-control">
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-2 control-label col-form-label text-sm-right">
                <{$smarty.const._MD_TADFORM_COL_DESCRIPT}>
            </label>
            <div class="col-sm-10">
                <textarea name="descript" id="descript" class="form-control" rows=4><{$descript}></textarea>
            </div>
        </div>
    </div>


    <div class="form-group row">
        <label class="col-sm-1 control-label col-form-label text-sm-right">
            <{$smarty.const._MD_TADFORM_COL_KIND}>
        </label>
        <div class="col-sm-2">
            <select name="kind" size=1 class="form-control">
                <option value="text" <{if $kind=="text"}>selected<{/if}>><{$smarty.const._MD_TADFORM_COL_TEXT}></option>
                <option value="radio" <{if $kind=="radio"}>selected<{/if}>><{$smarty.const._MD_TADFORM_COL_RADIO}></option>
                <option value="checkbox" <{if $kind=="checkbox"}>selected<{/if}>><{$smarty.const._MD_TADFORM_COL_CHECKBOX}></option>
                <option value="select" <{if $kind=="select"}>selected<{/if}>><{$smarty.const._MD_TADFORM_COL_SELECT}></option>
                <option value="textarea" <{if $kind=="textarea"}>selected<{/if}>><{$smarty.const._MD_TADFORM_COL_TEXTAREA}></option>
                <option value="date" <{if $kind=="date"}>selected<{/if}>><{$smarty.const._MD_TADFORM_COL_DATE}></option>
                <option value="datetime" <{if $kind=="datetime"}>selected<{/if}>><{$smarty.const._MD_TADFORM_COL_DATETIME}></option>
                <option value="show" <{if $kind=="show"}>selected<{/if}>><{$smarty.const._MD_TADFORM_COL_SHOW}></option>
            </select>
        </div>
        <label class="col-sm-2 control-label col-form-label text-sm-right">
            <{$smarty.const._MD_TADFORM_COL_SIZE}>
        </label>
        <div class="col-sm-7">
            <input type="text" name="size" id="size" value="<{$size}>" class="form-control" placeholder="<{$smarty.const._MD_TADFORM_COL_NOTE}>">
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-1 control-label col-form-label text-sm-right">
            <{$smarty.const._MD_TADFORM_COL_PUBLIC}>
        </label>
        <div class="col-sm-2">
            <select name="public" size=1 class="form-control">
                <option value="1"" <{if $public=="1"}>selected<{/if}>><{$smarty.const._MD_TADFORM_COL_PUBLIC1}></option>
                <option value="0" <{if $public!="1"}>selected<{/if}>><{$smarty.const._MD_TADFORM_COL_PUBLIC0}></option>
            </select>
            <input type="hidden" name="sort" value="<{$sort}>">
        </div>

        <label class="col-sm-1 control-label col-form-label text-sm-right">
            <{$smarty.const._MD_TADFORM_COL_CHK}>
        </label>
        <div class="col-sm-2">
            <select name="chk" size=1 class="form-control">
                <option value="1"" <{if $chk=="1"}>selected<{/if}>><{$smarty.const._YES}></option>
                <option value="0" <{if $chk!="1"}>selected<{/if}>><{$smarty.const._NO}></option>
            </select>
        </div>

        <label class="col-sm-1 control-label col-form-label text-sm-right">
            <{$smarty.const._MD_TADFORM_COL_FUNC}>
        </label>
        <div class="col-sm-2">
            <select name="func" id="func" size=1 class="form-control">
                <option value="" <{if $func==""}>selected<{/if}>><{$smarty.const._MD_TADFORM_COL_NO_FUN}></option>
                <option value="sum" <{if $func=="sum"}>selected<{/if}>><{$smarty.const._MD_TADFORM_COL_SUM}></option>
                <option value="avg" <{if $func=="avg"}>selected<{/if}>><{$smarty.const._MD_TADFORM_COL_AVG}></option>
                <option value="count" <{if $func=="count"}>selected<{/if}>><{$smarty.const._MD_TADFORM_COL_COUNT}></option>
            </select>
        </div>

        <label class="col-sm-1 control-label col-form-label text-sm-right">
            <{$smarty.const._MD_TADFORM_COL_VAL}>
        </label>
        <div class="col-sm-2">
            <input type="text" name="val" id="val" value="<{$val}>" class="form-control">
        </div>
    </div>

    <div class="text-center">
        <{$end_txt}>
        <input type="hidden" name="op" value="<{$next_op}>">
        <input type="hidden" name="csn" value="<{$csn}>">
        <input type="hidden" name="ofsn" value="<{$ofsn}>">
        <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
    </div>
</form>