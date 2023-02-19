<?php
/***************************************************************************
 *                                                                          *
 *   (c) 2004 Vladimir V. Kalynyak, Alexey V. Vinokurov, Ilya M. Shalnev    *
 *                                                                          *
 * This  is  commercial  software,  only  users  who have purchased a valid *
 * license  and  accept  to the terms of the  License Agreement can install *
 * and use this program.                                                    *
 *                                                                          *
 ****************************************************************************
 * PLEASE READ THE FULL TEXT  OF THE SOFTWARE  LICENSE   AGREEMENT  IN  THE *
 * "copyright.txt" FILE PROVIDED WITH THIS DISTRIBUTION PACKAGE.            *
 ****************************************************************************/

namespace Tygh\Addons\Departments\HookHandlers;

use Tygh;
use Tygh\Application;
use Tygh\Registry;

/**
 * This class describes the hook handlers related to department management
 *
 * @package Tygh\Addons\Departments\HookHandlers
 */
class DepartmentsHookHandler
{
    protected $application;

    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    /**
     * The "get_departments" hook handler.
     */
    public function onGetDepartments(&$params, &$fields, $sortings, &$condition, &$join, $sorting, &$group_by, $lang_code, $having)
    {

    }
}