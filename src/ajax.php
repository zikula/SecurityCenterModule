<?php
/**
 * Copyright Zikula Foundation 2009 - Zikula Application Framework
 *
 * This work is contributed to the Zikula Foundation under one or more
 * Contributor Agreements and licensed to You under the following license:
 *
 * @license GNU/LGPLv2.1 (or at your option, any later version).
 * @package Zikula
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */

include 'lib/ZLoader.php';
ZLoader::register();

System::init(System::CORE_STAGES_ALL & ~System::CORE_STAGES_TOOLS & ~System::CORE_STAGES_DECODEURLS);

// Get variables
$module = filter_input(INPUT_GET, 'module', FILTER_SANITIZE_STRING);
$type = filter_input(INPUT_GET, 'type', FILTER_SANITIZE_STRING);
$type = ($type) ? $type : 'ajax';
$func = filter_input(INPUT_GET, 'func', FILTER_SANITIZE_STRING);

// Check for site closed
if (System::getVar('siteoff') && !SecurityUtil::checkPermission('Settings::', 'SiteOff::', ACCESS_ADMIN) && !($module == 'Users' && $func == 'siteofflogin')) {
    if (SecurityUtil::checkPermission('Users::', '::', ACCESS_OVERVIEW) && UserUtil::isLoggedIn()) {
        UserUtil::logout();
    }
    AjaxUtil::error(__('The site is currently off-line.'));
}

if (empty($func)) {
    AjaxUtil::error(__f("Missing parameter '%s'", 'func'));
}

// get module information
$modinfo = ModUtil::getInfo(ModUtil::getIdFromName($module));
if ($modinfo == false) {
    AjaxUtil::error(__f("Error! The '%s' module is unknown.", DataUtil::formatForDisplay($module)));
}

if (!ModUtil::available($modinfo['name'])) {
    AjaxUtil::error(__f("Error! The '%s' module is not available.", DataUtil::formatForDisplay($module)));
}

$arguments = array(); // this is entirely ununsed? - drak 

if (ModUtil::load($modinfo['name'], $type)) {
    if (System::getVar('Z_CONFIG_USE_TRANSACTIONS')) {
        $dbConn = System::dbGetConn(true);
        $dbConn->beginTransaction();
    }

    // Run the function
    try {
        $return = ModUtil::func($modinfo['name'], $type, $func, $arguments);
    } catch (Exception $e) {
        $return = false;
    }

    if (System::getVar('Z_CONFIG_USE_TRANSACTIONS')) {
        if ($return === false && $e instanceof PDOException) {
            $return = __('Error! The transaction failed. Performing rollback.') . "\n" . $return;
            $dbConn->rollback();
            AjaxUtil::error($return);
            $return == true;
        }
        $dbConn->commit();
    }
} else {
    $return = false;
}

// Sort out return of function.  Can be
// true - finished
// false - display error msg
// text - return information
if ($return === true) {
    // Nothing to do here everything was done in the module
} elseif ($return === false) {
    // Failed to load the module
    AjaxUtil::error(__f("Could not load the '%s' module (at '%s' function).", array(DataUtil::formatForDisplay($module), DataUtil::formatForDisplay($func))));
} else {
    AjaxUtil::output($return, true, false);
}

System::shutdown();
