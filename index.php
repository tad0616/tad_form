<?php
/*-----------引入檔案區--------------*/
include "header.php";
$xoopsOption['template_main'] = "tad_form_index.html";

include XOOPS_ROOT_PATH."/header.php";
/*-----------function區--------------*/

//列出所有tad_form_main資料
function list_tad_form_main(){
  global $xoopsDB,$xoopsTpl,$xoopsUser,$xoopsModule;
  $today=date("Y-m-d H:i:s" , xoops_getUserTimestamp(time()));
  if ($xoopsUser) {
    $User_Groups=$xoopsUser->getGroups();
  }else{
    $User_Groups=array(3);
  }
  $sql = "select * from ".$xoopsDB->prefix("tad_form_main")." where enable='1' and start_date < '{$today}'  and end_date > '{$today}'";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  $i=0;
  $all="";
  while($data=$xoopsDB->fetchArray($result)){
    foreach($data as $k=>$v){
      $$k=$v;
    }

    $sql2 = "select count(*) from ".$xoopsDB->prefix("tad_form_fill")." where ofsn='{$ofsn}'";
    $result2 = $xoopsDB->query($sql2);
    list($counter)=$xoopsDB->fetchRow($result2);

    $start_date=date("Y-m-d",xoops_getUserTimestamp(strtotime($start_date)));
    $end_date=date("Y-m-d",xoops_getUserTimestamp(strtotime($end_date)));

    $multi_sign_pic=($multi_sign=='1')?"<img src='images/report_check.png' align='absmiddle' hspace=6 alt='"._MD_TADFORM_MULTI_SIGN."' title='"._MD_TADFORM_MULTI_SIGN."'><span class='label label-success'>"._MD_TADFORM_MULTI_SIGN."</span> ":"";

    $sign_group_arr=(empty($sign_group))?"":explode(",",$sign_group);
    $sign_ok=false;
    if(!empty($sign_group_arr)){
      foreach($sign_group_arr as $group){
        if(in_array($group,$User_Groups)){
          $sign_ok=true;
          break;
        }
      }
    }
    $view_result_group_arr=(empty($view_result_group))?"":explode(",",$view_result_group);
    $view_ok=false;
    if(!empty($view_result_group_arr)){
      foreach($view_result_group_arr as $group){
        if(in_array($group,$User_Groups)){
          $view_ok=true;
          break;
        }
      }
    }


    $all[$i]['sign_ok']=$sign_ok;
    $all[$i]['view_ok']=$view_ok;
    $all[$i]['ofsn']=$ofsn;
    $all[$i]['title']=$title;
    $all[$i]['counter']=$counter;
    $all[$i]['start_date']=$start_date;
    $all[$i]['end_date']=$end_date;
    $all[$i]['content']=$content;
    $all[$i]['uid']=$uid;
    $all[$i]['post_date']=$post_date;
    $all[$i]['enable']=$enable;
    $all[$i]['multi_sign']=$multi_sign_pic;
    $all[$i]['button']=$xoopsModuleConfig['show_amount']?_MD_TADFORM_SIGNNOW:sprintf(_MD_TADFORM_SIGN_NOW,$title,$counter);
    $all[$i]['date']=sprintf(_MD_TADFORM_SIGN_DATE,$start_date,$end_date);
    $i++;
  }

  if(empty($all)){
    $xoopsTpl->assign('op',"error");
    $xoopsTpl->assign('title',"");
    $xoopsTpl->assign('msg',_MD_TADFORM_EMPTY);
  }else{
    $xoopsTpl->assign( "jquery" , get_jquery(true)) ;
    $xoopsTpl->assign('all',$all);
  }
}



//填寫表單
function sign_form($ofsn="",$ssn=""){
  global $xoopsDB,$xoopsModule,$xoopsUser,$xoopsTpl;


  $today=date("Y-m-d H:i:s" , xoops_getUserTimestamp(time()));
  $form=get_tad_form_main($ofsn,$ssn);
  $ofsn=$form['ofsn'];

  $sign_group=(empty($form['sign_group']))?"":explode(",",$form['sign_group']);

  if ($xoopsUser) {
    $module_id = $xoopsModule->getVar('mid');
    $isAdmin=$xoopsUser->isAdmin($module_id);
    $email=$xoopsUser->getVar('email');

    $User_Groups=$xoopsUser->getGroups();
    $ugroup=implode(",",$User_Groups);

    if(!empty($sign_group) and !in_array(1,$User_Groups)){
      $ok=false;
      foreach($sign_group as $group){
        if(in_array($group,$User_Groups)){
          $ok=true;
        }
      }
      if(!$ok){
        $xoopsTpl->assign('op','error');
        $xoopsTpl->assign('title',$form['title']);
        $xoopsTpl->assign('msg',_MD_TADFORM_ONLY_MEM);
        return;
      }
    }

    $uid=$xoopsUser->getVar('uid');
    $uid_name=$xoopsUser->getVar('name');
    if(empty($uid_name)) $uid_name=$xoopsUser->getVar('uname');
    if(empty($uid_name)) $uid_name=$xoopsUser->getVar('loginname');

    if($ssn){
      $db_ans=get_somebody_ans($ofsn,$uid,$ssn);
    }else{
      $db_ans=($form['multi_sign']=='1')?array():get_somebody_ans($ofsn,$uid,$ssn);
    }
    $history=($form['multi_sign']=='1')?get_history($ofsn,$uid):"";
  }else{
    $uid_name="";
    $email=$history="";
    $isAdmin=false;
    $db_ans=array();
    if(!empty($sign_group) and !in_array(3,$sign_group)){
      $xoopsTpl->assign('op','error');
      $xoopsTpl->assign('title',$form['title']);
      $xoopsTpl->assign('msg',_MD_TADFORM_ONLY_MEM);
      return;
    }
  }



  if(!$isAdmin){
    if($form['enable']!='1'){
      $xoopsTpl->assign('op','error');
      $xoopsTpl->assign('title',$form['title']);
      $xoopsTpl->assign('msg',sprintf(_MD_TADFORM_UNABLE,$form['title']));
      return;
    }

    $form['start_date']=date("Y-m-d H:i",xoops_getUserTimestamp(strtotime($form['start_date'])));
    if($today < $form['start_date']){
        $xoopsTpl->assign('op','error');
        $xoopsTpl->assign('title',$form['title']);
        $xoopsTpl->assign('msg',sprintf(_MD_TADFORM_NOT_START,$form['title'],$form['start_date']));
        return;
    }

    $form['end_date']=date("Y-m-d H:i",xoops_getUserTimestamp(strtotime($form['end_date'])));
    if($today > $form['end_date']){
        $xoopsTpl->assign('op','error');
        $xoopsTpl->assign('title',$form['title']);
        $xoopsTpl->assign('msg',sprintf(_MD_TADFORM_OVERDUE,$form['title'],$form['end_date']));
        return;
    }
  }



  //若是用來報名的
  if($form['kind']=="application"){
    $man_name_list="<table><caption>"._MD_TADFORM_OK_LIST."</caption>";
    $sql = "select email,fill_time from ".$xoopsDB->prefix("tad_form_fill")." where ofsn='{$ofsn}' and result_col='1'";
    $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
    $n=$i=3;
    while(list($email,$fill_time)=$xoopsDB->fetchRow($result)){
      $fill_time=date("Y-m-d H:i:s",xoops_getUserTimestamp(strtotime($fill_time)));
      $email_data=explode("@",$email);
      $man_name_list.=($n % $i == 0)?"<tr>":"";
      $man_name_list.="<td>{$email_data[0]}@{$fill_time}</td> ";
      $man_name_list.=($n % $i == $i-1)?"</tr>":"";
      $n++;
    }
    $man_name_list.="</table>";

    $apply_ok="<tr><td>{$man_name_list}</td></tr>";
  }elseif($form['show_result'] and can_view_report($ofsn)){
    $apply_ok="<tr><td><a href='report.php?ofsn=$ofsn' class='btn btn-info'>"._MD_TADFORM_VIEW_FORM."</a></td></tr>";

  }else{
    $apply_ok="";
  }


  $main_form="";

  $sql = "select * from ".$xoopsDB->prefix("tad_form_col")." where ofsn='{$ofsn}' order by sort";

  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  $i=1;
  while($data=$xoopsDB->fetchArray($result)){
    foreach($data as $k=>$v){
      $$k=$v;
    }

    $edit_btn=($isAdmin)?"<a href='admin/add.php?op=edit_opt&ofsn=$ofsn&csn=$csn&mode=update' class='btn btn-mini btn-warning pull-right'>"._TAD_EDIT."</a>":"";
    $db_ans_csn=isset($db_ans[$csn])?$db_ans[$csn]:"";
    $col_form=col_form($csn,$kind,$size,$val,$db_ans_csn,$chk);

    $chk_txt=($chk=='1')?"<img src='images/star.png' alt='"._MD_TADFORM_NEED_SIGN."' hspace=3 align=absmiddle>":"";
    $note=(empty($descript))?"":"<span class='note'>({$descript})</span>";
    if($kind=='show'){
      $show_title=$descript;
      $show_col="";
    }else{
      $show_title="
      <div class='q_col'>
        $edit_btn
        <span class='question'>{$i}. $chk_txt<b>$title</b></span>
        $note
      </div>";
      $show_col="<tr><td class='show_col'>$col_form</td></tr>";
    }
    $main_form.="
    <tr>
      <td>
      $show_title
      </td>
    </tr>
    $show_col
    ";

    if($kind!='show')$i++;
  }

  $chk_emeil_js=chk_emeil_js("email","myForm");


  $jquery=get_jquery(true);


  $captcha_js="";
  $captcha_div="";
  if($form['captcha']=='1'){
    $captcha_js="
    <link rel='stylesheet' type='text/css' href='class/Qaptcha_v3.0/jquery/QapTcha.jquery.css' media='screen' />
    <script type='text/javascript' src='class/Qaptcha_v3.0/jquery/jquery.ui.touch.js'></script>
    <script type='text/javascript' src='class/Qaptcha_v3.0/jquery/QapTcha.jquery.js'></script>
    <script type='text/javascript'>
      $(document).ready(function(){
       $('.QapTcha').QapTcha({disabledSubmit:true , autoRevert:true , PHPfile:'class/Qaptcha_v3.0/php/Qaptcha.jquery.php', txtLock:'"._MD_TADFORM_TXTLOCK."' , txtUnlock:'"._MD_TADFORM_TXTUNLOCK."'});
      });
    </script>";
    $captcha_div="<div class='QapTcha'></div>";
  }


  $tool="";
  if($isAdmin){
    $tool="
    <a href='admin/add.php?op=tad_form_main_form&ofsn={$ofsn}' class='btn btn-warning'>".sprintf(_MD_TADFORM_EDIT_FORM,$form['title'])."</a>
    <a href='admin/add.php?op=edit_all_opt&ofsn={$ofsn}' class='btn btn-warning'>"._MD_TADFORM_EDIT_ALL."</a>
    <a href='admin/result.php?ofsn={$ofsn}' class='btn btn-primary'>"._MD_TADFORM_VIEW_FORM."</a>";
  }

  $db_ans_ssn=isset($db_ans['ssn'])?$db_ans['ssn']:"";

  $xoopsTpl->assign('op','sign');
  $xoopsTpl->assign('jquery',$jquery);
  //$xoopsTpl->assign('needfill_js',$needfill_js);
  $xoopsTpl->assign('chk_emeil_js',$chk_emeil_js);
  $xoopsTpl->assign('form_title',$form['title']);
  $xoopsTpl->assign('form_content',$form['content']);
  $xoopsTpl->assign('apply_ok',$apply_ok);
  $xoopsTpl->assign('main_form',$main_form);
  $xoopsTpl->assign('db_ans_ssn',$db_ans_ssn);
  $xoopsTpl->assign('ofsn',$ofsn);
  $xoopsTpl->assign('captcha_div',$captcha_div);
  $xoopsTpl->assign('uid_name',$uid_name);
  $xoopsTpl->assign('email',$email);
  $xoopsTpl->assign('captcha_js',$captcha_js);
  $xoopsTpl->assign('tool',$tool);
  $xoopsTpl->assign('history',$history);


  //表單驗證
  if(!file_exists(TADTOOLS_PATH."/formValidator.php")){
   redirect_header("index.php",3, _MA_NEED_TADTOOLS);
  }
  include_once TADTOOLS_PATH."/formValidator.php";
  $formValidator= new formValidator("#myForm");
  $formValidator_code=$formValidator->render();
  $xoopsTpl->assign( "formValidator_code" , $formValidator_code);
}


//儲存問卷
function save_val($ofsn='',$ans=array()){
  global $xoopsDB,$xoopsUser;

  if($xoopsUser){
    $uid=$xoopsUser->getVar('uid');
  }else{
    $uid="0";
  }
  $myts =& MyTextSanitizer::getInstance();
  $form=get_tad_form_main($ofsn);

  if($form['captcha']=='1'){

    if(isset($_POST['iQapTcha']) && empty($_POST['iQapTcha']) && isset($_SESSION['iQaptcha']) && $_SESSION['iQaptcha']) {

    } else {
      redirect_header($_SERVER['PHP_SELF'],3, _MD_TADFORM_CAPTCHA_ERROR);
      exit;
    }
  }

  $now=date("Y-m-d H:i:s" , xoops_getUserTimestamp(time()));

  $_POST['ssn']=intval($_POST['ssn']);
  //先存基本資料
  $sql = "replace into ".$xoopsDB->prefix("tad_form_fill")." (`ssn`,`ofsn`,`uid`,`man_name`,`email`,`fill_time`,`result_col`,`code`) values('{$_POST['ssn']}','{$_POST['ofsn']}','{$uid}','{$_POST['man_name']}','{$_POST['email']}', '{$now}','','')";
  $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  $ssn=$xoopsDB->getInsertId();


  $need_csn_arr=$_POST['need_csn'];


  //再存填寫資料
  foreach($ans as $csn => $val){
    $value=(is_array($val))?implode(";",$val):$val;
    $value=$myts->addSlashes($value);
    $ssn=intval($ssn);
    $sql = "replace into ".$xoopsDB->prefix("tad_form_value")." (`ssn`,`csn`,`val`) values('{$ssn}','{$csn}','{$value}')";
    $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
    unset($need_csn_arr[$csn]);
  }


  //把一些沒填的欄位也補上空值
  foreach($need_csn_arr as $csn){
    $ssn=intval($ssn);
    $sql = "replace into ".$xoopsDB->prefix("tad_form_value")." (`ssn`,`csn`,`val`) values('{$ssn}','{$csn}','')";
    $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, $sql);
  }

  //產生code
  $sql="update ".$xoopsDB->prefix("tad_form_fill")." set `code`=md5(CONCAT(`ofsn`,`uid`, `man_name`, `email`, `fill_time`)) where ssn='{$ssn}'";
  $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL,3,  mysql_error());


  $sql = "select `code` from ".$xoopsDB->prefix("tad_form_fill")." where ssn='{$ssn}'";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  list($code)=$xoopsDB->fetchRow($result);

  return $code;
}


//製作表單
function col_form($csn="",$kind="",$size="",$default_val="",$db_ans=array(),$chk=""){


  switch($kind){
    case "text":
    $default_val=(empty($db_ans))?$default_val:$db_ans;
    $chktxt=($chk)?"class='span{$span} validate[required]'":"class='span{$span}'";
    $span=empty($size)?6:round($size/10,0);
    $main="<input type='text' name='ans[$csn]' id='tf{$csn}' $chktxt value='{$default_val}'><input type='hidden' name='need_csn[{$csn}]' value='{$csn}'>";
    break;

    case "radio":
    $default_val=(empty($db_ans))?$default_val:$db_ans;
    $opt=explode(";",$size);
    $i=0;
    $main="<input type='hidden' name='need_csn[{$csn}]' value='{$csn}'>";
    foreach($opt as $val){
      $checked=($default_val==$val)?"checked='checked'":"";
      $chktxt=($chk)?"class='validate[required] radio'":"";
      $main.="
      <label class='radio inline'>
      <input type='radio' name='ans[$csn]' value='{$val}' $checked $chktxt>{$val}
      </label>";
      $i++;
    }
    break;

    case "checkbox":
    $default_val=(empty($db_ans))?$default_val:$db_ans;
    $db=explode(";",$default_val);

    $opt=explode(";",$size);
    $i=0;
    $main="<input type='hidden' name='need_csn[{$csn}]' value='{$csn}'>";
    foreach($opt as $val){
      $checked=(in_array($val,$db))?"checked='checked'":"";
      $chktxt=($chk)?"class='validate[required] checkbox'":"";
      $main.="
      <label class='checkbox inline'>
      <input type='checkbox' name='ans[$csn][]' value='{$val}' $checked $chktxt>{$val}
      </label>";
      $i++;
    }
    break;

    case "select":
    $default_val=(empty($db_ans))?$default_val:$db_ans;
    $chktxt=($chk)?"class='validate[required]'":"";
    $opt=explode(";",$size);
    $main="<select name='ans[$csn]' id='tf{$csn}' $chktxt>";
    foreach($opt as $val){
      $selected=($default_val==$val)?"selected":"";
      $main.="<option value='{$val}' $selected>{$val}</option>";
    }
    $main.="</select><input type='hidden' name='need_csn[{$csn}]' value='{$csn}'>";
    break;

    case "textarea":
    $default_val=(empty($db_ans))?$default_val:$db_ans;
    $chktxt=($chk)?"class='span12 validate[required]'":"class='span12'";
    if(empty($size))$size=60;
    $main="<textarea name='ans[$csn]' id='tf{$csn}' $chktxt style='height:{$size}px;'>{$default_val}</textarea><input type='hidden' name='need_csn[{$csn}]' value='{$csn}'>";
    break;

    case "date":
    $default_val=(empty($db_ans))?$default_val:$db_ans;
    $span=empty($size)?6:round($size/10,0);
    $chktxt=($chk)?"class='validate[required] span{$span}'":"class='span{$span}'";
    $main="<input type='text' name='ans[$csn]' id='tf{$csn}' value='{$default_val}' $chktxt onClick=\"WdatePicker({dateFmt:'yyyy-MM-dd' , startDate:'%y-%M-%d}'})\">
    <input type='hidden' name='need_csn[{$csn}]' value='{$csn}'>";
    break;

    case "datetime":
    $default_val=(empty($db_ans))?$default_val:$db_ans;
    $span=empty($size)?6:round($size/10,0);
    $chktxt=($chk)?"class='span{$span} validate[required]'":"class='span{$span}'";
    $main="<input type='text' name='ans[$csn]' id='tf{$csn}' value='{$default_val}' $chktxt onClick=\"WdatePicker({dateFmt:'yyyy-MM-dd HH:mm' , startDate:'%y-%M-%d %H:%m}'})\">
    <input type='hidden' name='need_csn[{$csn}]' value='{$csn}'>";
    break;

    case "show":
    $main="";
    break;
  }
  return $main;
}



//取代/新增tad_form_fill現有資料
function replace_tad_form_fill(){
  global $xoopsDB;
  $sql = "replace into ".$xoopsDB->prefix("tad_form_fill")." (`ofsn`,`uid`,`man_name`,`email`,`fill_time` , `result_col` , `code`) values('{$_POST['ofsn']}','{$_POST['uid']}','{$_POST['man_name']}','{$_POST['email']}','{$_POST['fill_time']}' , '' , '')";
  $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  //取得最後新增資料的流水編號
  $ofsnuid=$xoopsDB->getInsertId();
  return $ofsnuid;
}

//立即寄出
function send_now($code=""){
  global $xoopsConfig,$xoopsDB;


  $xoopsMailer =& getMailer();
  $xoopsMailer->multimailer->ContentType="text/html";

  $sql = "select a.`ofsn`,a.`man_name`,a.`email`, a.`fill_time`,a.`code`,b.`title`,b.`adm_email`  from ".$xoopsDB->prefix("tad_form_fill")." as a left join ".$xoopsDB->prefix("tad_form_main")." as b on a.ofsn=b.ofsn where a.code='$code'";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  list($ofsn,$man_name,$email,$fill_time,$code,$title,$adm_email)=$xoopsDB->fetchRow($result);

  $xoopsMailer->addHeaders("MIME-Version: 1.0");

  $all=view($code,"mail");

  $fill_time=date("Y-m-d H:i:s",xoops_getUserTimestamp(strtotime($fill_time)));
  $content= sprintf(_MD_TADFORM_MAIL_CONTENT , $man_name , $fill_time , $title , $all , XOOPS_URL."/modules/tad_form/view.php?code={$code}");

  if(!empty($email)){
   $xoopsMailer->sendMail($email, sprintf(_MD_TADFORM_MAIL_TITLE,$title,$man_name,$fill_time), $content,$headers);
  }

  $email_arr=explode(";",$adm_email);
  foreach($email_arr as $email){
    //$email=trim($email);
    if(!empty($email)){
     $xoopsMailer->sendMail($email, sprintf(_MD_TADFORM_MAIL_TITLE,$title,$man_name,$fill_time), $content,$headers);
    }
  }

}


/*-----------執行動作判斷區----------*/
$op=(empty($_REQUEST['op']))?"":$_REQUEST['op'];
$ofsn=(empty($_REQUEST['ofsn']))?"":intval($_REQUEST['ofsn']);
$ssn=(empty($_REQUEST['ssn']))?"":intval($_REQUEST['ssn']);
$ans=(empty($_REQUEST['ans']))?"":$_REQUEST['ans'];
$code=(empty($_REQUEST['code']))?"":$_REQUEST['code'];


$xoopsTpl->assign( "toolbar" , toolbar_bootstrap($interface_menu)) ;
$xoopsTpl->assign( "bootstrap" , get_bootstrap()) ;

switch($op){
  case "sign":
  sign_form($ofsn,$ssn);
  break;

  case "delete_fill":
  delete_tad_form_ans($ssn);
  header("location:index.php?op=sign&ofsn={$ofsn}");
  break;

  case "save_val":
  $code=save_val($ofsn,$ans);
  send_now($code);
  redirect_header("index.php?op=view&code={$code}",3, _MD_TADFORM_SAVE_OK);
  break;

  case "view":
  view($code);
  break;

  default:
  list_tad_form_main();
  break;
}

/*-----------秀出結果區--------------*/
include_once XOOPS_ROOT_PATH.'/footer.php';
?>
