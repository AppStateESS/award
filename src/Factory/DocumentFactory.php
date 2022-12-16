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
use award\Resource\Document;
use phpws2\Database;
use Canopy\Request;
use award\Resource\Participant;
use award\Resource\Reason;

class DocumentFactory extends AbstractFactory
{

    protected static string $table = 'award_document';
    protected static string $resourceClassName = 'award\Resource\Document';

    public static function createDocument(array $fileArray, Reason $reason)
    {
        if (!$reason->getId()) {
            throw new \Exception('reason id may not be zero');
        }
        if ($fileArray['type'] !== 'application/pdf') {
            return ['success' => false, 'error' => 'document is not a PDF'];
        }

        if ($fileArray['size'] > DocumentFactory::maximumUploadSize()) {
            return ['success' => false, 'error' => 'uploaded file is too large'];
        }
        $reference = ReferenceFactory::build($reason->getReferenceId());
        $nomination = NominationFactory::build($reason->getNominationId());
        $nominated = ParticipantFactory::build($nomination->getNominatedId());

        $sourceFile = $fileArray['tmp_name'];
        $destinationDir = DocumentFactory::getFileDirectory();
        if ($reason->isReference()) {
            $destinationFileName = DocumentFactory::referenceFileName($reason->getReferenceId());
            $referenceParticipant = ParticipantFactory::build($reference->participantId);
            $documentTitle = DocumentFactory::referenceDocumentTitle($referenceParticipant, $nominated);
        } else {
            $destinationFileName = DocumentFactory::nominationFileName($nomination->getId());
            $nominator = ParticipantFactory::build($nomination->getNominatorId());
            $documentTitle = DocumentFactory::nominationDocumentTitle($nominator, $nominated);
        }

        if (!move_uploaded_file($sourceFile, $destinationDir . $destinationFileName)) {
            return ['success' => false, 'error' => 'failed to save uploaded file'];
        }

        $document = DocumentFactory::build();
        $document->setFilename($destinationFileName)->setReasonId($reason->getId())->setTitle($documentTitle);

        return DocumentFactory::save($document);
    }

    /**
     * Deletes the document from the directory and removes the row
     * from the award_document table. Does not update the reason resource.
     * @param Document $document
     * @return type
     */
    public static function delete(Document $document)
    {
        $path = self::getFileDirectory() . $document->getFilename();
        unlink($path);
        extract(self::getDBWithTable());
        $table->addFieldConditional('id', $document->getId());
        return $db->delete();
    }

    public static function deleteByNominationId(int $nominationId)
    {

        $document = self::getByNominationId($nominationId);
        if ($document === false) {
            throw new MissingDocument();
        }
        self::delete($document);
    }

    public static function deleteByReferenceId(int $referenceId)
    {
        $document = self::getByReferenceId($referenceId);
        if ($document === false) {
            throw new MissingDocument();
        }
        self::delete($document);
    }

    public static function download(Document $document)
    {
        $fullPath = self::getFileDirectory() . $document->getFilename();
        if (preg_match('/\.pdf$/', $document->getTitle())) {
            header('Content-Type: application/pdf');
        } else {
            header('Content-Type: application/octet-stream');
        }
        header("Content-Transfer-Encoding: Binary");
        header("Content-disposition: download; filename=\"" . $document->getTitle() . "\"");
        readfile($fullPath);
        exit;
    }

    public static function getByNominationId(int $nominationId)
    {
        extract(self::getDBWithTable());
        $table->addFieldConditional('nominationId', $nominationId);
        $row = $db->selectOneRow();
        if (empty($row)) {
            return false;
        } else {
            $document = self::build();
            $document->setValues($row);
            return $document;
        }
    }

    public static function getByReferenceId(int $referenceId)
    {
        extract(self::getDBWithTable());
        $table->addFieldConditional('referenceId', $referenceId);
        $row = $db->selectOneRow();
        if (empty($row)) {
            return false;
        } else {
            $document = self::build();
            $document->setValues($row);
            return $document;
        }
    }

    public static function getFileDirectory()
    {
        return PHPWS_HOME_DIR . 'files/award/';
    }

    /**
     * This function is a copy of of a function from Stackoverflow
     * https://stackoverflow.com/questions/13076480/php-get-actual-maximum-upload-size
     * Comments claim it is from Drupal.
     */
    public static function maximumUploadSize()
    {
        static $max_size = -1;

        if ($max_size < 0) {
            $post_max_size = self::parseSize(ini_get('post_max_size'));
            if ($post_max_size > 0) {
                $max_size = $post_max_size;
            }

            $upload_max = self::parseSize(ini_get('upload_max_filesize'));
            if ($upload_max > 0 && $upload_max < $max_size) {
                $max_size = $upload_max;
            }
        }
        return $max_size;
    }

    public static function nominationDocumentTitle(Participant $nominator, Participant $nominated)
    {
        $nominatorName = self::participantNameToFileName($nominator);
        $nominatedName = self::participantNameToFileName($nominated);
        return "{$nominatorName}-nomination-for-{$nominatedName}.pdf";
    }

    public static function nominationFileName($nominationId)
    {
        return "nomination_{$nominationId}.pdf";
    }

    public static function referenceDocumentTitle(Participant $reference, Participant $nominated)
    {
        $referenceName = self::participantNameToFileName($reference);
        $nominatedName = self::participantNameToFileName($nominated);
        return "{$referenceName}-reference-for-{$nominatedName}.pdf";
    }

    public static function referenceFileName($referenceId)
    {
        return "reference_{$referenceId}.pdf";
    }

    /**
     * This function is a copy of of a function from Stackoverflow
     * https://stackoverflow.com/questions/13076480/php-get-actual-maximum-upload-size
     * Comments claim it is from Drupal.
     */
    private static function parseSize($size)
    {
        $unit = preg_replace('/[^bkmgtpezy]/i', '', $size);
        $size = preg_replace('/[^0-9\.]/', '', $size);
        if ($unit) {
            return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
        } else {
            return round($size);
        }
    }

    private static function participantNameToFileName(Participant $participant)
    {
        return preg_replace('/\W/', '_', $participant->firstName . ' ' . $participant->lastName);
    }

}
