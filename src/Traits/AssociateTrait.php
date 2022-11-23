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

namespace award\Traits;

use award\AbstractClass\AbstractResource;

trait AssociateTrait
{

    /**
     * Returns an array of resources associated to the argument resource.
     * @param AbstractResource $resource
     * @return array
     */
    public static function getAssociated(AbstractResource $resource)
    {
        $resourceIdList = [
            'award' => 'award\Factory\AwardFactory',
            'cycle' => 'award\Factory\CycleFactory',
            'invited' => 'award\Factory\ParticipantFactory',
            'nominator' => 'award\Factory\ParticipantFactory',
            'participant' => 'award\Factory\ParticipantFactory',
            'nominated' => 'award\Factory\ParticipantFactory',
            'nomination' => 'award\Factory\NominationFactory',
        ];
        $resourceValues = $resource->getValues();
        $resources = [];
        foreach ($resourceIdList as $parameter => $factory) {
            $idParam = $parameter . 'Id';
            if (isset($resourceValues[$idParam]) && $resourceValues[$idParam] > 0) {
                $resources[$parameter] = $factory::build($resource->$idParam);
            }
        }
        return $resources;
    }

}
