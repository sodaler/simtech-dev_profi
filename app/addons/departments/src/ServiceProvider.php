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

namespace Tygh\Addons\Departments;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Tygh\Addons\Departments\Profile\Departments;
use Tygh\Addons\Departments\HookHandlers\DepartmentsHookHandler;
use Tygh\Registry;
use Tygh\Tygh;

/**
 * Class ServiceProvider is intended to register services and components of the "departments" add-on to the application
 * container.
 *
 * @package Tygh\Addons\Departments
 */
class ServiceProvider implements ServiceProviderInterface
{
    /**
     * @inheritDoc
     */
    public function register(Container $app)
    {
        $app['addons.departments.profile.departments'] = function (Container $app) {
            return new Departments($app, AREA, DESCR_SL);
        };

        $app['addons.departments.hook_handlers.departments'] = function (Container $app) {
            return new DepartmentsHookHandler($app);
        };
    }

    /**
     * @return Departments
     */
    public static function getProfileDepartments()
    {
        return Tygh::$app['addons.departments.profile.departments'];
    }
}