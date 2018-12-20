<link href="<{$xoops_url}>/modules/tadtools/css/font-awesome/css/font-awesome.css" rel="stylesheet">
<div class="container-fluid">
  <{if $op=="edit_opt"}>

    <form action="add.php" method="post" id="myForm" enctype="multipart/form-data" class="form-horizontal" role="form">

      <div class="form-group">
        <label class="col-sm-2 control-label">
          <{$smarty.const._MA_TADFORM_COL_TITLE}><{$sort}>
        </label>
        <div class="col-sm-10">
          <input type="text" name="title" id="title" value="<{$title}>" class="form-control">
        </div>
      </div>


      <div class="form-group">
        <label class="col-sm-2 control-label">
          <{$smarty.const._MA_TADFORM_COL_DESCRIPT}>
        </label>
        <div class="col-sm-10">
          <textarea name="descript" id="descript" class="form-control" rows=4><{$descript}></textarea>
        </div>
      </div>


      <div class="form-group">
        <label class="col-sm-2 control-label">
          <{$smarty.const._MA_TADFORM_COL_KIND}>
        </label>
        <div class="col-sm-2">
          <select name="kind" size=1 class="form-control">
            <option value="text" <{if $kind=="text"}>selected<{/if}>><{$smarty.const._MA_TADFORM_COL_TEXT}></option>
            <option value="radio" <{if $kind=="radio"}>selected<{/if}>><{$smarty.const._MA_TADFORM_COL_RADIO}></option>
            <option value="checkbox" <{if $kind=="checkbox"}>selected<{/if}>><{$smarty.const._MA_TADFORM_COL_CHECKBOX}></option>
            <option value="select" <{if $kind=="select"}>selected<{/if}>><{$smarty.const._MA_TADFORM_COL_SELECT}></option>
            <option value="textarea" <{if $kind=="textarea"}>selected<{/if}>><{$smarty.const._MA_TADFORM_COL_TEXTAREA}></option>
            <option value="date" <{if $kind=="date"}>selected<{/if}>><{$smarty.const._MA_TADFORM_COL_DATE}></option>
            <option value="datetime" <{if $kind=="datetime"}>selected<{/if}>><{$smarty.const._MA_TADFORM_COL_DATETIME}></option>
            <option value="show" <{if $kind=="show"}>selected<{/if}>><{$smarty.const._MA_TADFORM_COL_SHOW}></option>
          </select>
        </div>
        <label class="col-sm-2 control-label">
          <{$smarty.const._MA_TADFORM_COL_SIZE}>
        </label>
        <div class="col-sm-6">
          <input type="text" name="size" id="size" value="<{$size}>" class="form-control" placeholder="<{$smarty.const._MA_TADFORM_COL_NOTE}>">
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-2 control-label">
          <{$smarty.const._MA_TADFORM_COL_PUBLIC}>
        </label>
        <div class="col-sm-2">
          <label class="radio-inline">
            <input type="radio" name="public" id="public1" value="1" <{if $public=="1"}>checked<{/if}>><{$smarty.const._YES}>
          </label>
          <label class="radio-inline">
            <input type="radio" name="public" id="public0" value="0" <{if $public!="1"}>checked<{/if}>><{$smarty.const._NO}>
          </label>
          <input type="hidden" name="sort" value="<{$sort}>">
        </div>

        <label class="col-sm-1 control-label">
          <{$smarty.const._MA_TADFORM_COL_CHK}>
        </label>
        <div class="col-sm-2">
          <label class="radio-inline">
            <input type="radio" name="chk" id="chk1" value="1" <{if $chk=="1"}>checked<{/if}>><{$smarty.const._YES}>
          </label>
          <label class="radio-inline">
            <input type="radio" name="chk" id="chk0" value="0"  <{if $chk!="1"}>checked<{/if}>><{$smarty.const._NO}>
          </label>
        </div>

        <label class="col-sm-1 control-label">
          <{$smarty.const._MA_TADFORM_COL_FUNC}>
        </label>
        <div class="col-sm-1">
          <select name="func" id="func" size=1 class="form-control">
            <option value="" <{if $func==""}>selected<{/if}>><{$smarty.const._MA_TADFORM_COL_NO_FUN}></option>
            <option value="sum" <{if $func=="sum"}>selected<{/if}>><{$smarty.const._MA_TADFORM_COL_SUM}></option>
            <option value="avg" <{if $func=="avg"}>selected<{/if}>><{$smarty.const._MA_TADFORM_COL_AVG}></option>
            <option value="count" <{if $func=="count"}>selected<{/if}>><{$smarty.const._MA_TADFORM_COL_COUNT}></option>
          </select>
        </div>

        <label class="col-sm-1 control-label">
          <{$smarty.const._MA_TADFORM_COL_VAL}>
        </label>
        <div class="col-sm-2">
          <input type="text" name="val" id="val" value="<{$val}>" class="form-control">
        </div>
      </div>



      <div class="row">
        <div class="col-sm-12 text-center">
          <{$end_txt}>

          <input type="hidden" name="op" value="<{$next_op}>">
          <input type="hidden" name="csn" value="<{$csn}>">
          <input type="hidden" name="ofsn" value="<{$ofsn}>">
          <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
        </div>
      </div>

    </form>
  <{elseif $op=="edit_all_opt"}>

    <{$jquery}>
    <script>
    $().ready(function(){
        $("#sort").sortable({ opacity: 0.6, cursor: "move", update: function() {
            var order = $(this).sortable("serialize");
            $.post("save_sort.php", order, function(theResponse){
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
      <th><{$smarty.const._MA_TADFORM_COL_SORT}></th>
      <th><{$smarty.const._MA_TADFORM_COL_TITLE}> (<{$smarty.const._MA_TADFORM_COL_DESCRIPT}>)</th>
      <th><{$smarty.const._MA_TADFORM_COL_KIND}></th>
      <th><{$smarty.const._MA_TADFORM_COL_SIZE}></th>
      <th><{$smarty.const._MA_TADFORM_COL_VAL}></th>
      <th><{$smarty.const._MA_TADFORM_COL_CHK}></th>
      <th><{$smarty.const._MA_TADFORM_COL_FUNC}></th>
      <th><{$smarty.const._MA_TADFORM_COL_PUBLIC}></th>
      <th><{$smarty.const._TAD_FUNCTION}></th>
      </tr>
      <tbody id="sort">
      <{foreach item=q from=$question}>
        <tr id="tr_<{$q.csn}>">
          <td><{$q.sort}> <img src="<{$xoops_url}>/modules/tadtools/treeTable/images/updown_s.png" style="cursor: s-resize;" alt="<{$smarty.const._MA_TREETABLE_SORT_PIC}>" title="<{$smarty.const._MA_TREETABLE_SORT_PIC}>"></td>
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
      <a href="add.php?op=edit_opt&ofsn=<{$ofsn}>" class="btn btn-info"><{$smarty.const._MA_TADFORM_ADD_COL}></a>
    </div>
    <br style="clear:both" />



  <{else}>
    <{$formValidator_code}>
    <script type="text/javascript" src="<{$xoops_url}>/modules/tadtools/My97DatePicker/WdatePicker.js"></script>
    <form action="add.php" method="post" id="myForm" enctype="multipart/form-data" class="form-horizontal" role="form">


      <div class="row">
        <div class="col-sm-6">

          <div class="form-group">
            <label class="col-sm-2 control-label">
              <{$smarty.const._MA_TADFORM_TITLE}>
            </label>
            <div class="col-sm-10">
              <input type="text" name="title" id="title" value="<{$title}>" class="validate[required] form-control" >
            </div>
          </div>


          <div class="form-group">
            <label class="col-sm-2 control-label">
              <{$smarty.const._MA_TADFORM_ADM_EMAIL}>
            </label>
            <div class="col-sm-10">
              <input type="text" name="adm_email" id="adm_email" value="<{$adm_email}>" class="validate[required] form-control" >
            </div>
          </div>


          <div class="form-group">
            <label class="col-sm-2 control-label">
              <{$smarty.const._MA_TADFORM_START_DATE}>
            </label>
            <div class="col-sm-10">
              <input type="text" name="start_date" id="start_date" class="form-control" value="<{$start_date}>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm'})">
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label">
              <{$smarty.const._MA_TADFORM_END_DATE}>
            </label>
            <div class="col-sm-10">
              <input type="text" name="end_date" id="end_date" class="form-control" value="<{$end_date}>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm'})">
            </div>
          </div>

        </div>

        <div class="col-sm-6">

          <div class="row">
            <div class="col-sm-4">
              <label style="display: block;">
                <{$smarty.const._MA_TADFORM_USE_CAPTCHA}>
              </label>
              <label class="radio-inline">
                <input type="radio" name="captcha" id="captcha_1" value="1" <{if $captcha == "1"}>checked="checked"<{/if}>><{$smarty.const._YES}>
              </label>
              <label class="radio-inline">
                <input type="radio" name="captcha" id="captcha_0" value="0" <{if $captcha != "1"}>checked="checked"<{/if}>><{$smarty.const._NO}>
              </label>
            </div>

            <div class="col-sm-4">
              <label style="display: block;">
                <{$smarty.const._MA_TADFORM_MULTI_SIGN}>
              </label>
              <label class="radio-inline">
                <input type="radio" name="multi_sign" id="multi_sign_1" value="1" <{if $multi_sign == "1"}>checked="checked"<{/if}>><{$smarty.const._YES}>
              </label>
              <label class="radio-inline">
                <input type="radio" name="multi_sign" id="multi_sign_0" value="0" <{if $multi_sign != "1"}>checked="checked"<{/if}>><{$smarty.const._NO}>
              </label>
            </div>

            <div class="col-sm-4">
              <label style="display: block;">
                <{$smarty.const._MA_TADFORM_SHOW_RESULT}>
              </label>
              <label class="radio-inline">
                <input type="radio" name="show_result" id="show_result_1" value="1" <{if $show_result == "1"}>checked="checked"<{/if}>><{$smarty.const._YES}>
              </label>
              <label class="radio-inline">
                <input type="radio" name="show_result" id="show_result_0" value="0" <{if $show_result != "1"}>checked="checked"<{/if}>><{$smarty.const._NO}>
              </label>
            </div>
          </div>

          <hr>

          <div class="row">
            <div class="col-sm-4">
              <label><{$smarty.const._MA_TADFORM_KIND}></label>
              <select name="kind" class="form-control"><{$kind_menu}></select>
            </div>
            <div class="col-sm-4">
              <label><{$smarty.const._MA_TADFORM_SIGN_GROUP}></label>
              <{$sign_group}>
            </div>
            <div class="col-sm-4">
              <label><{$smarty.const._MA_TADFORM_VIEW_RESULT_GROUP}></label>
              <{$view_result_group}>
            </div>
          </div>

        </div>
      </div>


      <div class="row">
        <div class="col-sm-12">
          <{$editor}>
        </div>
      </div>


      <div class="row">
        <div class="col-sm-12 text-center">
          <input type="hidden" name="enable" value="<{$enable}>">
          <input type="hidden" name="ofsn" value="<{$ofsn}>">
          <input type="hidden" name="op" value="<{$op}>">

          <{$next}>
          <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
        </div>
      </div>

    </form>
  <{/if}>
</div>