<?php

use XoopsModules\Tadtools\Utility;
if (!class_exists('XoopsModules\Tadtools\Utility')) {
    require XOOPS_ROOT_PATH . '/modules/tadtools/preloads/autoloader.php';
}
function xoops_module_install_tad_form(&$module)
{
    Utility::mk_dir(XOOPS_ROOT_PATH . '/uploads/tad_form');
    Utility::mk_dir(XOOPS_ROOT_PATH . '/uploads/tad_form/file');
    Utility::mk_dir(XOOPS_ROOT_PATH . '/uploads/tad_form/image');
    Utility::mk_dir(XOOPS_ROOT_PATH . '/uploads/tad_form/image/.thumbs');

    return true;
}
