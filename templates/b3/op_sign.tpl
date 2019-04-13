
<script type="text/javascript" src="<{$xoops_url}>/modules/tadtools/My97DatePicker/WdatePicker.js"></script>

<script type="text/javascript">
    function check_data(){
        <{$chk_emeil_js}>
        return true;
    }
    </script>
<h1><{$form_title}></h1>

<form action="<{$xoops_URL}>/modules/tad_form/index.php" method="post" name="myForm" id="myForm" enctype="multipart/form-data" onSubmit="return check_data()" class="form-horizontal" role="form">
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
        <{$smarty.const._TADFORM_MAN_NAME}>
    </label>
    <div class="col-sm-2">
        <label for='tfman_name' style='display:none;'>tfman_name</label>
        <input type="text" name="man_name" id="tfman_name" class='form-control validate[required]' <{if $uid_name}>value="<{$uid_name}>"<{/if}>>
    </div>
    <label class="col-sm-2 control-label">
        <{$smarty.const._TADFORM_EMAIL}>
    </label>
    <div class="col-sm-4">
        <label for='tfemail' style='display:none;'>tfemail</label>
        <input type="text" name="email" id="tfemail"  class='form-control validate[required]' <{if $email}>value="<{$email}>"<{/if}>>
    </div>
    <div class="col-sm-2">
        <button type="submit" name="submit" class="btn btn-primary"><{$smarty.const._TADFORM_SUBMIT_FORM}></button>
    </div>
    </div>

</form>
<{$captcha_js}>
<div style="border-top:1px dotted gray;padding-top:6px;"><img src="<{$xoops_URL}>/modules/tad_form/images/star.png" alt="<{$smarty.const._TADFORM_NEED_SIGN}>" hspace=3 align="absmiddle"><{$smarty.const._TADFORM_IS_NEED_SIGN}></div>

<{if $history}>
    <script>
        function delete_fill(ssn){
        var sure = window.confirm("<{$smarty.const._TAD_DEL_CONFIRM}>");
        if (!sure)  return;
        location.href="<{$xoops_URL}>/modules/tad_form/index.php?op=delete_fill&ofsn=<{$ofsn}>&ssn=" + ssn;
        }
    </script>

    <div class="well" style="margin-top:30px;">
        <h3><{$smarty.const._TADFORM_HISTORY}></h3>
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