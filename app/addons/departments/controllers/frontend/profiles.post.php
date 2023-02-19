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

use Tygh\Registry;
use Tygh\Addons\Departments\ServiceProvider;

defined('BOOTSTRAP') or die('Access denied');

$profile_departments = ServiceProvider::getProfileDepartments();

/**
 * @var string $mode
 * @var array  $auth
 */
if ($mode == 'departments') {
    Tygh::$app['session']['continue_url'] = "profiles.departments";
    $params = $_REQUEST;
    $params['user_id'] = Tygh::$app['session']['auth']['user_id'];

    list($departments, $search) = $profile_departments->getList(array_merge([
        'items_per_page' => Registry::get('settings.Appearance.admin_elements_per_page')
    ], $_REQUEST));

    Tygh::$app['view']->assign([
        'departments' => $departments,
        'search' => $search,
        'columns' => 3
    ]);

    fn_add_breadcrumb(__('departments'));
}