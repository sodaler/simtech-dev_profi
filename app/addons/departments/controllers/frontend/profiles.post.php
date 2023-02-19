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
        'items_per_page' => Registry::get('settings.Appearance.products_per_page'),
    ], $params));

    Tygh::$app['view']->assign([
        'departments' => $departments,
        'search' => $search,
        'columns' => 3
    ]);

    fn_add_breadcrumb(__('departments'));
} elseif ($mode == 'department') {
    $department_data = [];
    $department_id = !empty($_REQUEST['department_id']) ? $_REQUEST['department_id'] : 0;
    $department_data = $profile_departments->getById($department_id);

    if (empty($department_data)) {
        return [CONTROLLER_STATUS_NO_PAGE];
    }

    $params = $_REQUEST;
    $params['extend'] = ['description'];
    $params['item_ids'] = !empty($department_data['employee_ids']) ? implode(',', $department_data['employee_ids']) : -1;
    $params['sort_by'] = $department_data['users'];
    $params['items_per_page'] = Registry::get('settings.Appearance.products_per_page');

    $users = $department_data['users'] ? explode(',', $department_data['users']) : [];
    $director = $department_data['director_id'] ? fn_get_user_short_info($department_data['director_id']) : -1;
    $res_users = [];

    list($res_users, $search) = fn_get_users($params, $auth, Registry::get('settings.Appearance.users_per_page'));
    $search['total_items'] = count($users);
    $res_users = fn_sort_by_ids($res_users, explode(',', $department_data['users']), 'user_id');

    Tygh::$app['view']->assign([
        'search' => $search,
        'director' => $director,
        'users' => $res_users,
        'department_data' => $department_data
    ]);

    fn_add_breadcrumb(__('departments'), "profiles.departments");
    fn_add_breadcrumb($department_data['department']);
}