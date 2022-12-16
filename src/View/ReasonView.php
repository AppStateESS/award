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

namespace award\View;

use award\AbstractClass\AbstractView;
use award\Factory\DocumentFactory;
use award\Factory\ReasonFactory;
use award\Factory\NominationFactory;
use award\Factory\ParticipantFactory;
use award\Factory\ReferenceFactory;
use award\Resource\Award;
use award\Resource\Cycle;
use award\Resource\Nomination;
use award\Resource\Reason;
use award\Resource\Reference;

class ReasonView extends AbstractView
{

    public static function referenceForm(Reason $reason, Nomination $nomination, Award $award, Cycle $cycle)
    {
        $values['awardTitle'] = self::getFullAwardTitle($award, $cycle);
        $values['currentDocument'] = DocumentFactory::build($reason->getDocumentId())->getValues();
        $values['maxsize'] = DocumentFactory::maximumUploadSize();
        $values['nominatedName'] = NominationFactory::getNominated($nomination)->getFullName();
        $values['reason'] = $reason->getValues();

        return self::scriptView('ReferenceReasonForm', $values);
    }

    public static function referenceReasonNotRequired()
    {
        return self::centerCard('Reference endorsement not required', self::getTemplate('Error/ReferenceReasonNotRequired'), 'danger');
    }

}
