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

namespace award\Factory;

use award\AbstractClass\AbstractFactory;
use award\Exception\UnknownReasonType;
use award\Resource\Document;
use award\Resource\Reason;
use Canopy\Request;

class ReasonFactory extends AbstractFactory
{

    protected static string $table = 'award_reason';
    protected static string $resourceClassName = 'award\Resource\Reason';

    public static function allowed(Reason $reason)
    {
        if ($reason->isNomination()) {
            return ParticipantFactory::currentOwnsNomination($reason->getNominationId());
        } else if ($reason->isReference()) {
            return ParticipantFactory::currentIsReference($reason->getReferenceId());
        } else {
            throw new UnknownReasonType;
        }
    }

    /**
     * Deletes the document associated with the reason and returns
     * the zeroed reason.
     * @param Reason $reason
     * @return \award\Factory\Reason
     */
    public static function clearCurrentDocument(Reason $reason)
    {
        if ($documentId = $reason->getDocumentId()) {
            $document = DocumentFactory::build($documentId);
            DocumentFactory::delete($document);
            $reason->setDocumentId(0);
        }
        return $reason;
    }

    /**
     * Deletes a reason from the table. If their is an associated
     * document, it is also deleted. The associated reference/nomination
     * is not updated - self::unlinkReason should be run to do so.
     * @param Reason $reason
     * @return null
     */
    public static function delete(Reason $reason)
    {
        $documentId = $reason->getDocumentId();
        if ($documentId) {
            $document = DocumentFactory::build($documentId);
            DocumentFactory::delete($document);
        }
        extract(parent::getDBWithTable());
        $table->addFieldConditional('id', $reason->getId());
        $db->delete();
    }

    public static function getByReferenceId(int $referenceId)
    {
        extract(parent::getDBWithTable());
        $table->addFieldConditional('referenceId', $referenceId);
        return parent::injectResult(self::build(), $db);
    }

    public static function getByNominationId(int $nominationId)
    {
        extract(parent::getDBWithTable());
        $table->addFieldConditional('nominationId', $nominationId);
        return parent::injectResult(self::build(), $db);
    }

    /**
     * Updates the appropriate reference or nomination
     * @param Reason $reason
     */
    public static function linkReason(Reason $reason)
    {
        if ($reason->isReference()) {
            $reference = ReferenceFactory::build($reason->getReferenceId());
            $reference->setReasonId($reason->getId());
            ReferenceFactory::save($reference);
        } elseif ($reason->isNomination()) {
            $nomination = NominationFactory::build($reason->getNominationId());
            $nomination->setReasonId($reason->getId);
            NominationFactory::save($nomination);
        } else {
            throw new UnknownReasonType;
        }
    }

    public static function postBasic(Request $request)
    {
        $reason = ReasonFactory::build();
        $reason->setReasonType($request->pullPostInteger('reasonType'));
        if ($reason->isReference()) {
            $referenceId = $request->pullPostInteger('referenceId');
            self::postReferenceDocument($reason, $referenceId);
        } elseif ($reason->isNomination()) {
            $nominationId = $request->pullPostInteger('nominationId');
            self::postNominationDocument($reason, $nominationId);
        } else {
            throw new UnknownReasonType;
        }
        return $reason;
    }

    private static function postNominationDocument(Reason $reason, int $nominationId)
    {
        if (!ParticipantFactory::currentOwnsNomination($nominationId)) {
            throw new ParticipantPrivilegeMissing();
        }
        $nomination = NominationFactory::build($nominationId);
        $reason->setNominationId($nominationId)
            ->setCycleId($nomination->getCycleId())
            ->setReasonType(AWARD_REASON_NOMINATION);
    }

    private static function postReferenceDocument(Reason $reason, int $referenceId)
    {
        if (!ParticipantFactory::currentIsReference($referenceId)) {
            throw new ParticipantPrivilegeMissing();
        }

        $reference = ReferenceFactory::build($referenceId);
        $reason->setReferenceId($referenceId)->setCycleId($reference->getCycleId())
            ->setNominationId($reference->getNominationId())
            ->setReasonType(AWARD_REASON_REFERENCE);
    }

    /**
     * Updates the appropriate reference or nomination with a zero. Should be run
     * before deletion.
     * @param Reason $reason
     */
    public static function unlinkReason(Reason $reason)
    {
        if ($reason->isReference()) {
            $reference = ReferenceFactory::build($reason->getReferenceId());
            $reference->setReasonId(0);
            ReferenceFactory::save($reference);
        } elseif ($reason->isNomination()) {
            $nomination = NominationFactory::build($reason->getNominationId());
            $nomination->setReasonId(0);
            NominationFactory::save($nomination);
        } else {
            throw new UnknownReasonType;
        }
    }

}
