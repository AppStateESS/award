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

namespace award\Controller\Participant;

use Canopy\Request;
use award\AbstractClass\AbstractController;
use award\Factory\DocumentFactory;
use award\Exception\MissingDocument;
use award\Exception\ParticipantPrivilegeMissing;
use award\Factory\DocumentFactory;
use award\Factory\ParticipantFactory;

class Document extends AbstractController
{

    protected function delete()
    {
        $document = DocumentFactory::build($this->id);
        if (!ParticipantFactory::currentOwnsDocument($document)) {
            throw new ParticipantPrivilegeMissing();
        }
        DocumentFactory::delete($document);

        return ['success' => true];
    }

    protected function downloadHtml()
    {
        $document = DocumentFactory::build($this->id);
        if ($document === false) {
            throw new MissingDocument();
        }
        if (ParticipantFactory::currentOwnsDocument($document)) {
            DocumentFactory::download($document);
        } else {
            throw new ParticipantPrivilegeMissing();
        }
    }

}
