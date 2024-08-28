<?php
use Xmf\Request;
use XoopsModules\Tadtools\BootstrapTable;
use XoopsModules\Tadtools\TadUpFiles;
use XoopsModules\Tadtools\Utility;
use XoopsModules\Tad_form\Tad_form_fill;
use XoopsModules\Tad_form\Tad_form_main;
use XoopsModules\Tad_form\Tools;

/*-----------引入檔案區--------------*/
require __DIR__ . '/header.php';
$xoopsOption['template_main'] = 'tad_form_index.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';
Utility::test($_SESSION, 'session', 'dd');

/*-----------執行動作判斷區----------*/
$op = Request::getString('op');
$ofsn = Request::getInt('ofsn');
$ssn = Request::getInt('ssn');
$ans = Request::getArray('ans');
$code = Request::getString('code');
$files_sn = Request::getInt('files_sn');

switch ($op) {

    //下載檔案
    case "tufdl":
        $TadUpFiles = new TadUpFiles("tad_form");
        $TadUpFiles->add_file_counter($files_sn);
        exit;

    case 'tad_form_fill_create':
    case 'sign':
        // sign_form($ofsn, $ssn);
        Tad_form_fill::create($ofsn, $ssn, $code);
        $op = 'tad_form_fill_create';
        break;

    case 'tad_form_fill_destroy':
        Tad_form_fill::destroy($ofsn, ['ssn' => $ssn]);
        header("location:index.php?op=tad_form_fill_create&ofsn={$ofsn}");
        exit;

    case 'send_now':
        Tools::send_now($ofsn, $code);
        exit;

    case 'tad_form_value_save':
    case 'save_val':
        list($ssn, $code) = Tad_form_fill::save($ofsn, $ssn);

        Tools::send_now($ofsn, $code);

        // $curl = curl_init();
        // curl_setopt($curl, CURLOPT_URL, XOOPS_URL . '/modules/tad_form/index.php'); // 通知信處理腳本的 URL
        // curl_setopt($curl, CURLOPT_POST, 1);
        // curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        // curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(['op' => 'send_now', 'ofsn' => $ofsn, 'code' => $code]));
        // curl_setopt($curl, CURLOPT_TIMEOUT, 5); // 设置超时时间，确保不等待太长时间
        // curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
        // curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        // $response = curl_exec($curl);
        // curl_close($curl);

        redirect_header("index.php?op=tad_form_fill_show&ofsn={$ofsn}&ssn={$ssn}&code={$code}", 3, _MD_TAD_FORM_SAVE_OK);
        break;

    case 'tad_form_fill_show':
    case 'view':
        // view($code);
        Tad_form_fill::show($ofsn, ['ofsn' => $ofsn, 'code' => $code], ['form', 'ans']);
        $op = 'tad_form_fill_show';
        break;

    //觀看所有結果
    case 'tad_form_fill_index':
        Tad_form_fill::index($ofsn, ['ofsn' => $ofsn], ['ans', 'form'], [], ['ssn' => 'asc'], 'ssn');
        $BootstrapTable = BootstrapTable::render();
        break;

    default:
        $today = date('Y-m-d H:i:s', xoops_getUserTimestamp(time()));
        Tad_form_main::index(['enable' => 1, "start_date < '{$today}'", "end_date > '{$today}'"], ['fill_count', 'can_fill', 'can_view_result'], [], ['post_date' => 'desc'], 'ofsn');
        $op = 'tad_form_main_index';
        break;
}
/*-----------秀出結果區--------------*/
$xoopsTpl->assign('now_op', $op);
$xoopsTpl->assign('xoopsModuleConfig', $xoopsModuleConfig);
$xoopsTpl->assign('toolbar', Utility::toolbar_bootstrap($interface_menu));
$xoTheme->addStylesheet(XOOPS_URL . '/modules/tad_form/css/module.css');

require_once XOOPS_ROOT_PATH . '/footer.php';
