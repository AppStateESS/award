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
use award\Factory\ReferenceFactory;
use award\Factory\DocumentFactory;
use award\Resource\Reference;
use award\Resource\Participant;

class ReferenceView extends AbstractView
{

    public static function passedUpdate()
    {
        return self::centerCard('Award deadline has passed', self::getTemplate('Error/ReferencePassedUpdate'), 'danger');
    }

    public static function reasonForm(Reference $reference)
    {
        $values = ReferenceFactory::getAssociated($reference, ['nomination'], true);
        if (!$values['award']['referenceReasonRequired']) {
            return self::referenceReasonNotRequired();
        }
        $values['reference'] = $reference->getValues();
        $values['maxsize'] = \award\Factory\DocumentFactory::maximumUploadSize();
        $document = DocumentFactory::build();

        $reasonDocument = $reference->getReasonDocument();
        $values['currentReasonDocument'] = DocumentFactory::build($reasonDocument)->getValues();

        return self::scriptView('ReferenceReasonForm', $values);
    }

    public static function referencesNotRequired()
    {
        return self::centerCard('References not required', self::getTemplate('Error/ReferencesNotRequired'), 'danger');
    }

    public static function referenceReasonNotRequired()
    {
        return self::centerCard('Reference endorsement not required', self::getTemplate('Error/ReferenceReasonNotRequired'), 'danger');
    }

}
