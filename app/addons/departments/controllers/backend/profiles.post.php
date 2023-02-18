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

$profile_departments = ServiceProvider::getProfileDepartments();

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