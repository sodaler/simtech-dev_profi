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
 * @var string $suffix
 * @var array $auth
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    fn_trusted_vars(
        'department_data'
    );

    if ($mode == 'update_department') {
        $department_id = !empty($_REQUEST['department_id']) ? $_REQUEST['department_id'] : 0;
        $data = !empty($_REQUEST['department_data']) ? $_REQUEST['department_data'] : [];

        $department_id = $profile_departments->upsert($data, $department_id);

        if ($department_id) {
            $suffix = "update_department?department_id={$department_id}";
        } else {
            $suffix = 'add_department';
        }

        return [CONTROLLER_STATUS_OK, "profiles.{$suffix}"];
    } elseif ($mode == 'update_departments') {

        if (!empty($_REQUEST['departments_data'])) {
            foreach ($_REQUEST['departments_data'] as $department_id => $data) {
                $profile_departments->upsert($data, $department_id);
            }
        }

        return [CONTROLLER_STATUS_OK, 'profiles.manage_departments'];
    } elseif ($mode == 'delete_department') {
        $department_id = !empty($_REQUEST['department_id']) ? $_REQUEST['department_id'] : 0;
        $profile_departments->delete($department_id);

        return [CONTROLLER_STATUS_OK, 'profiles.manage_departments'];
    } elseif ($mode == 'delete_departments') {
        if (!empty($_REQUEST['departments_ids'])) {
            foreach ($_REQUEST['departments_ids'] as $department_id) {
                $profile_departments->delete($department_id);
            }
        }
        return [CONTROLLER_STATUS_OK, 'profiles.manage_departments'];
    }

    return [CONTROLLER_STATUS_OK, 'profiles.' . $suffix];
}

if ($mode == 'manage_departments') {
    $image_width = Registry::get('settings.Thumbnails.product_admin_mini_icon_width');
    $image_height = Registry::get('settings.Thumbnails.product_admin_mini_icon_height');

    $image_width = $image_width ?: $image_height;
    $image_height = $image_height ?: $image_width;

    list($departments, $search) = $profile_departments->getList(array_merge([
        'items_per_page' => Registry::get('settings.Appearance.admin_elements_per_page')
    ], $_REQUEST));

    Tygh::$app['view']->assign([
        'departments' => $departments,
        'image_width' => $image_width,
        'image_height' => $image_height,
        'search' => $search,
    ]);
}

if ($mode == 'add_department' || $mode == 'update_department') {
    $department_id = !empty($_REQUEST['department_id']) ? $_REQUEST['department_id'] : 0;
    $department_data = $profile_departments->getById($department_id);

    if (empty($department_data) && $mode == 'update_department') {
        return array(CONTROLLER_STATUS_NO_PAGE);
    }

    Tygh::$app['view']->assign([
        'department_data' => $department_data,
        'u_info' => !empty($department_data['director_id']) ? fn_get_user_short_info($department_data['director_id']) : [],
        'department_users' => db_get_fields("SELECT user_id FROM ?:users WHERE user_id IN(?n) ", explode(',', $department_data['users'])),
        'update_mode' => ($mode == 'update_department') ? $mode : '',
    ]);
}