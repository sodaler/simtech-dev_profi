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

use Tygh\Addons\InstallerInterface;
use Tygh\Core\ApplicationInterface;

/**
 * This class describes the instructions for installing and uninstalling the departments add-on
 *
 * @package Tygh\Addons\Departments
 */
class Installer implements InstallerInterface
{
    /**
     * @inheritDoc
     */
    public static function factory(ApplicationInterface $app)
    {
        return new self();
    }

    /**
     * @inheritDoc
     */
    public function onInstall()
    {

    }

    /**
     * @inheritDoc
     */
    public function onUninstall()
    {

    }

    /**
     * @inheritDoc
     */
    public function onBeforeInstall()
    {

    }
}