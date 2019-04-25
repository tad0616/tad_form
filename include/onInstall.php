<?php

use XoopsModules\Tadtools\Utility;


include dirname(__DIR__) . '/preloads/autoloader.php';

function xoops_module_install_tad_form(&$module)
{
    Utility::mk_dir(XOOPS_ROOT_PATH . '/uploads/tad_form');
    Utility::mk_dir(XOOPS_ROOT_PATH . '/uploads/tad_form/file');
    Utility::mk_dir(XOOPS_ROOT_PATH . '/uploads/tad_form/image');
    Utility::mk_dir(XOOPS_ROOT_PATH . '/uploads/tad_form/image/.thumbs');

    return true;
}
