<div class="row">
  <div class="col-sm-12">
    <{$toolbar}>
  </div>
</div>

<div class="row">
  <div class="col-sm-12">
    <{if $op=="sign"}>

      <{$jquery}>

      <{$formValidator_code}>
      <script type="text/javascript" src="<{$xoops_url}>/modules/tadtools/My97DatePicker/WdatePicker.js"></script>

      <script type="text/javascript">
      function check_data(){
        <{$chk_emeil_js}>
        return true;
      }
      </script>
      <h1><{$form_title}></h1>

      <form action="index.php" method="post" name="myForm" id="myForm" enctype="multipart/form-data" onSubmit="return check_data()" class="form-horizontal" role="form">
        <table class="table table-striped table-bordered">
        <tr><td><{$form_content}></td></tr>
        <{$apply_ok}>
        <{$main_form}>
        </table>

        <input type="hidden" name="ssn" value="<{$db_ans_ssn}>">
        <input type="hidden" name="ofsn" value="<{$ofsn}>">
        <input type="hidden" name="op" value="save_val">
        <p>
          <{$captcha_div}>
        </p>

        <div class="form-group">
          <label class="col-sm-2 control-label">
            <{$smarty.const._MD_TADFORM_MAN_NAME}>
          </label>
          <div class="col-sm-2">
            <label for='tfman_name' style='display:none;'>tfman_name</label>
            <input type="text" name="man_name" id="tfman_name" class='form-control validate[required]' <{if $uid_name}>value="<{$uid_name}>"<{/if}>>
          </div>
          <label class="col-sm-2 control-label">
            <{$smarty.const._MD_TADFORM_EMAIL}>
          </label>
          <div class="col-sm-4">
            <label for='tfemail' style='display:none;'>tfemail</label>
            <input type="text" name="email" id="tfemail"  class='form-control validate[required]' <{if $email}>value="<{$email}>"<{/if}>>
          </div>
          <div class="col-sm-2">
            <button type="submit" name="submit" class="btn btn-primary"><{$smarty.const._MD_TADFORM_SUBMIT_FORM}></button>
          </div>
        </div>

      </form>
      <{$captcha_js}>
      <div style="border-top:1px dotted gray;padding-top:6px;"><img src="images/star.png" alt="<{$smarty.const._MD_TADFORM_NEED_SIGN}>" hspace=3 align=absmiddle><{$smarty.const._MD_TADFORM_IS_NEED_SIGN}></div>

      <{if $history}>
        <script>
        function delete_fill(ssn){
          var sure = window.confirm("<{$smarty.const._TAD_DEL_CONFIRM}>");
          if (!sure)  return;
          location.href="index.php?op=delete_fill&ofsn=<{$ofsn}>&ssn=" + ssn;
        }
        </script>
      <div class="well" style="margin-top:30px;">
      <h3><{$smarty.const._MD_TADFORM_HISTORY}></h3>
      <table class="table table-striped">
      <{foreach item=history from=$history}>
      <tr>
        <td><{$history.fill_time}></td>
        <td><{$history.man_name}></td>
        <td class="text-right">
        <a href="javascript:delete_fill(<{$history.ssn}>)" class="btn btn-xs btn-danger"><{$smarty.const._TAD_DEL}></a>
        <a href="<{$xoops_url}>/modules/tad_form/index.php?op=sign&ssn=<{$history.ssn}>" class="btn btn-xs btn-warning"><{$smarty.const._TAD_EDIT}></a>
        </td>
      </tr>
      <{/foreach}>
      </table>
      </div>
      <{/if}>
      <div class="pull-right"><{$tool}></div>
    <{elseif $op=="error"}>

      <div class="jumbotron">
        <h3><{$title}></h3>
        <p><{$msg}></p>
      </div>
    <{elseif $op=="view"}>

      <h1><{$form_title}></h1>
      <table <{$tbl_set}>>
      <{$content}>
      <{foreach item=q from=$all}>
        <tr>
          <td <{$q.td_set}>><{$q.i}>. <b><{$q.title}></b></td>
          <td><{$q.val}></td>
        </tr>
      <{/foreach}>
      </table>
      <div class="text-center">
      <{if $show_report}>
        <a href="report.php?ofsn=<{$ofsn}>" class="btn btn-info"><{$smarty.const._MD_TADFORM_VIEW_FORM}></a>
      <{/if}>
      <a href="index.php?op=sign&ofsn=<{$ofsn}>" class="btn btn-success"><{$smarty.const._MD_TADFORM_BACK_TO_FORM}></a>
      </div>
    <{else}>

      <{foreach item=form from=$all}>
        <div class="well">
          <div class="pull-right"><{$form.multi_sign}><span class="label label-info"><{$form.date}></span></div>
          <h3><{$form.title}></h3>

          <{$form.content}>

          <div class="text-center">

            <a href="index.php?op=sign&ofsn=<{$form.ofsn}>" class="btn btn-lg btn-block <{if $form.sign_ok}>btn-primary<{else}>disabled<{/if}>"><{$form.button}></a>

            <{if $form.view_ok}>
              <a href="report.php?ofsn=<{$form.ofsn}>" class="btn btn-info btn-lg btn-block"><{$smarty.const._MD_TADFORM_VIEW_FORM}></a>
            <{/if}>

          </div>
        </div>
      <{/foreach}>
    <{/if}>
  </div>
</div>