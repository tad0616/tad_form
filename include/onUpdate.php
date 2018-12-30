<?php
function xoops_module_update_tad_form(&$module, $old_version)
{
    global $xoopsDB;

    if (!chk_chk1()) {
        go_update1();
    }

    if (!chk_chk2()) {
        go_update2();
    }

    if (!chk_chk3()) {
        go_update3();
    }

    if (!chk_chk4()) {
        go_update4();
    }

    if (!chk_chk5()) {
        go_update5();
    }

    if (!chk_chk6()) {
        go_update6();
    }

    if (!chk_chk7()) {
        go_update7();
    }

    if (!chk_chk8()) {
        go_update8();
    }

    if (chk_uid()) {
        go_update_uid();
    }

    if (!chk_chk9()) {
        go_update9();
    }

    $old_fckeditor = XOOPS_ROOT_PATH . "/modules/tad_form/fckeditor";
    if (is_dir($old_fckeditor)) {
        delete_directory($old_fckeditor);
    }

    chk_tad_form_block();
    return true;
}

//刪除錯誤的重複欄位及樣板檔
function chk_tad_form_block()
{
    global $xoopsDB;
    //die(var_export($xoopsConfig));
    include XOOPS_ROOT_PATH . '/modules/tad_form/xoops_version.php';

    //先找出該有的區塊以及對應樣板
    foreach ($modversion['blocks'] as $i => $block) {
        $show_func                = $block['show_func'];
        $tpl_file_arr[$show_func] = $block['template'];
        $tpl_desc_arr[$show_func] = $block['description'];
    }

    //找出目前所有的樣板檔
    $sql = "SELECT bid,name,visible,show_func,template FROM `" . $xoopsDB->prefix("newblocks") . "`
    WHERE `dirname` = 'tad_form' ORDER BY `func_num`";
    $result = $xoopsDB->query($sql);
    while (list($bid, $name, $visible, $show_func, $template) = $xoopsDB->fetchRow($result)) {
        //假如現有的區塊和樣板對不上就刪掉
        if ($template != $tpl_file_arr[$show_func]) {
            $sql = "delete from " . $xoopsDB->prefix("newblocks") . " where bid='{$bid}'";
            $xoopsDB->queryF($sql);

            //連同樣板以及樣板實體檔案也要刪掉
            $sql = "delete from " . $xoopsDB->prefix("tplfile") . " as a
            left join " . $xoopsDB->prefix("tplsource") . "  as b on a.tpl_id=b.tpl_id
            where a.tpl_refid='$bid' and a.tpl_module='tad_form' and a.tpl_type='block'";
            $xoopsDB->queryF($sql);
        } else {
            $sql = "update " . $xoopsDB->prefix("tplfile") . "
            set tpl_file='{$template}' , tpl_desc='{$tpl_desc_arr[$show_func]}'
            where tpl_refid='{$bid}'";
            $xoopsDB->queryF($sql);
        }
    }

}

function chk_chk1()
{
    global $xoopsDB;
    $sql    = "SELECT count(`kind`) FROM " . $xoopsDB->prefix("tad_form_main");
    $result = $xoopsDB->query($sql);
    if (empty($result)) {
        return false;
    }

    return true;
}

function go_update1()
{
    global $xoopsDB;
    $sql = "ALTER TABLE " . $xoopsDB->prefix("tad_form_main") . " ADD `kind` VARCHAR(255) NOT NULL";
    $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);
    return true;
}

function chk_chk2()
{
    global $xoopsDB;
    $sql    = "SELECT count(`result_col`) FROM " . $xoopsDB->prefix("tad_form_fill");
    $result = $xoopsDB->query($sql);
    if (empty($result)) {
        return false;
    }

    return true;
}

function go_update2()
{
    global $xoopsDB;
    $sql = "ALTER TABLE " . $xoopsDB->prefix("tad_form_fill") . " ADD `result_col` VARCHAR(255) NOT NULL";
    $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);
    return true;
}

function chk_chk3()
{
    global $xoopsDB;
    $sql    = "SELECT count(`adm_email`) FROM " . $xoopsDB->prefix("tad_form_main");
    $result = $xoopsDB->query($sql);
    if (empty($result)) {
        return false;
    }

    return true;
}

function go_update3()
{
    global $xoopsDB;
    $sql = "ALTER TABLE " . $xoopsDB->prefix("tad_form_main") . " ADD `adm_email` VARCHAR(255) NOT NULL";
    $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);
    return true;
}

function chk_chk4()
{
    global $xoopsDB;
    $sql    = "SELECT count(`captcha`) FROM " . $xoopsDB->prefix("tad_form_main");
    $result = $xoopsDB->query($sql);
    if (empty($result)) {
        return false;
    }

    return true;
}

function go_update4()
{
    global $xoopsDB;
    $sql = "ALTER TABLE " . $xoopsDB->prefix("tad_form_main") . " ADD `captcha` ENUM('1','0') NOT NULL DEFAULT '1'";
    $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);
    return true;
}

function chk_chk5()
{
    global $xoopsDB;
    $sql    = "SELECT count(`show_result`) FROM " . $xoopsDB->prefix("tad_form_main");
    $result = $xoopsDB->query($sql);
    if (empty($result)) {
        return false;
    }

    return true;
}

function go_update5()
{
    global $xoopsDB;
    $sql = "ALTER TABLE " . $xoopsDB->prefix("tad_form_main") . " ADD `show_result` ENUM('1','0') NOT NULL DEFAULT '1'";
    $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);
    return true;
}

function chk_chk6()
{
    global $xoopsDB;
    $sql    = "SELECT count(`view_result_group`) FROM " . $xoopsDB->prefix("tad_form_main");
    $result = $xoopsDB->query($sql);
    if (empty($result)) {
        return false;
    }

    return true;
}

function go_update6()
{
    global $xoopsDB;
    $sql = "ALTER TABLE " . $xoopsDB->prefix("tad_form_main") . " ADD `view_result_group` VARCHAR(255) NOT NULL DEFAULT ''";
    $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);
    return true;
}

function chk_chk7()
{
    global $xoopsDB;
    $sql    = "SELECT count(`multi_sign`) FROM " . $xoopsDB->prefix("tad_form_main");
    $result = $xoopsDB->query($sql);
    if (empty($result)) {
        return false;
    }

    return true;
}

function go_update7()
{
    global $xoopsDB;
    $sql = "ALTER TABLE " . $xoopsDB->prefix("tad_form_main") . " ADD `multi_sign` ENUM('0','1') NOT NULL DEFAULT '0'";
    $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);
    return true;
}

function chk_chk8()
{
    global $xoopsDB;
    $sql    = "SELECT count(`public`) FROM " . $xoopsDB->prefix("tad_form_col");
    $result = $xoopsDB->query($sql);
    if (empty($result)) {
        return false;
    }

    return true;
}

function go_update8()
{
    global $xoopsDB;
    $sql = "ALTER TABLE " . $xoopsDB->prefix("tad_form_col") . " ADD `public`  ENUM('0','1') NOT NULL DEFAULT '0'";
    $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);
    return true;
}

//修正uid欄位
function chk_uid()
{
    global $xoopsDB;
    $sql = "SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS
  WHERE table_name = '" . $xoopsDB->prefix("tad_form_main") . "' AND COLUMN_NAME = 'uid'";
    $result     = $xoopsDB->query($sql);
    list($type) = $xoopsDB->fetchRow($result);
    if ($type == 'smallint') {
        return true;
    }

    return false;
}

//執行更新
function go_update_uid()
{
    global $xoopsDB;
    $sql = "ALTER TABLE `" . $xoopsDB->prefix("tad_form_main") . "` CHANGE `uid` `uid` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT 0";
    $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);
    $sql = "ALTER TABLE `" . $xoopsDB->prefix("tad_form_fill") . "` CHANGE `uid` `uid` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT 0";
    $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);
    return true;
}

function chk_chk9()
{
    global $xoopsDB;
    $sql    = "SELECT count(`code`) FROM " . $xoopsDB->prefix("tad_form_fill");
    $result = $xoopsDB->query($sql);
    if (empty($result)) {
        return false;
    }

    return true;
}

function go_update9()
{
    global $xoopsDB;
    $sql = "ALTER TABLE " . $xoopsDB->prefix("tad_form_fill") . " ADD `code` VARCHAR(255) NOT NULL";
    $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);

    $sql = "update " . $xoopsDB->prefix("tad_form_fill") . " set code=md5(CONCAT(`ofsn`,`uid`, `man_name`, `email`, `fill_time`)) ";
    $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);

    return true;
}



//做縮圖
function thumbnail($filename = "", $thumb_name = "", $type = "image/jpeg", $width = "120")
{

    ini_set('memory_limit', '50M');
    // Get new sizes
    list($old_width, $old_height) = getimagesize($filename);

    $percent = ($old_width > $old_height) ? round($width / $old_width, 2) : round($width / $old_height, 2);

    $newwidth  = ($old_width > $old_height) ? $width : $old_width * $percent;
    $newheight = ($old_width > $old_height) ? $old_height * $percent : $width;

    // Load
    $thumb = imagecreatetruecolor($newwidth, $newheight);
    if ($type == "image/jpeg" or $type == "image/jpg" or $type == "image/pjpg" or $type == "image/pjpeg") {
        $source = imagecreatefromjpeg($filename);
        $type   = "image/jpeg";
    } elseif ($type == "image/png") {
        $source = imagecreatefrompng($filename);
        $type   = "image/png";
    } elseif ($type == "image/gif") {
        $source = imagecreatefromgif($filename);
        $type   = "image/gif";
    }

    // Resize
    imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $old_width, $old_height);

    header("Content-type: image/png");
    imagepng($thumb, $thumb_name);

    return;
    exit;
}



//建立目錄
if (!function_exists('mk_dir')) {
    function mk_dir($dir = "")
    {
        //若無目錄名稱秀出警告訊息
        if (empty($dir)) {
            return;
        }

        //若目錄不存在的話建立目錄
        if (!is_dir($dir)) {
            umask(000);
            //若建立失敗秀出警告訊息
            mkdir($dir, 0777);
        }
    }
}

//拷貝目錄
if (!function_exists('full_copy')) {
    function full_copy($source = "", $target = "")
    {
        if (is_dir($source)) {
            @mkdir($target);
            $d = dir($source);
            while (false !== ($entry = $d->read())) {
                if ($entry == '.' || $entry == '..') {
                    continue;
                }

                $Entry = $source . '/' . $entry;
                if (is_dir($Entry)) {
                    full_copy($Entry, $target . '/' . $entry);
                    continue;
                }
                copy($Entry, $target . '/' . $entry);
            }
            $d->close();
        } else {
            copy($source, $target);
        }
    }
}

if (!function_exists('rename_win')) {
    function rename_win($oldfile, $newfile)
    {
        if (!rename($oldfile, $newfile)) {
            if (copy($oldfile, $newfile)) {
                unlink($oldfile);
                return true;
            }
            return false;
        }
        return true;
    }
}

if (!function_exists('delete_directory')) {
    function delete_directory($dirname)
    {
        if (is_dir($dirname)) {
            $dir_handle = opendir($dirname);
        }

        if (!$dir_handle) {
            return false;
        }

        while ($file = readdir($dir_handle)) {
            if ($file != "." && $file != "..") {
                if (!is_dir($dirname . "/" . $file)) {
                    unlink($dirname . "/" . $file);
                } else {
                    delete_directory($dirname . '/' . $file);
                }
            }
        }
        closedir($dir_handle);
        rmdir($dirname);
        return true;
    }
}
