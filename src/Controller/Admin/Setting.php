<?php

declare(strict_types=1);
/**
 * MIT License
 * Copyright (c) 2022 Electronic Student Services @ Appalachian State University
 *
 * See LICENSE file in root directory for copyright and distribution permissions.
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace award\Controller\Admin;

use award\AbstractClass\AbstractController;
use award\View\SettingView;
use Canopy\Request;
use award\Factory\AuthenticateFactory;
use award\Factory\SettingFactory;

class Setting extends AbstractController
{

    public function listHtml()
    {
        return SettingView::dashboard();
    }

    public function listJson()
    {
        $settings['authAvailable'] = AuthenticateFactory::getAuthtypeList();
        $settings['useWarehouse'] = (bool) SettingFactory::useWarehouse();
        $settings['trustedDefault'] = SettingFactory::getTrustedDefault();
        return $settings;
    }

    public function authenticatorTogglePost(Request $request)
    {
        $filename = $request->pullPostString('filename');
        $toggle = $request->pullPostBoolean('toggle');
        if ($toggle) {
            SettingFactory::addEnabledAuthenticators($filename);
        } else {
            SettingFactory::removeEnabledAuthenticators($filename);
        }
        return ['success' => true];
    }

    public function trustedTogglePost(Request $request)
    {
        $trustedDefault = $request->pullPostBoolean('trusted');
        SettingFactory::setTrustedDefault($trustedDefault);
        return ['success' => true];
    }

    public function warehouseTogglePost(Request $request)
    {
        $useWarehouse = $request->pullPostBoolean('useWarehouse');
        SettingFactory::setUseWarehouse($useWarehouse);
        return ['success' => true];
    }

}
