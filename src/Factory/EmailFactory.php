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

require_once PHPWS_SOURCE_DIR . 'mod/award/vendor/autoload.php';

use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;
use phpws2\Settings;
use award\Resource\Participant;
use award\View\EmailView;

class EmailFactory
{

    public static function createWarningOnExisting(Participant $participant)
    {
        $email = self::getEmail();
        $email->to($participant->getEmail())
            ->html(EmailView::existParticipantWarning($participant))->subject('Award account request ignored');
        return self::send($email);
    }

    public static function inviteNewParticipant($invitation, string $from)
    {
        $email = self::getEmail();
        $email->to($invitation->email)
            ->html(EmailView::inviteNewParticipant($invitation, $from))->subject('Award site participant invitation');
        return self::send($email);
    }

    /**
     * Sends an email notifying a user that a participant account has
     * been created for them. It also asks for confirmation via a link.
     * The link is formed in the template.
     *
     * @param Participant $participant
     * @param string $hash
     */
    public static function newParticipant(Participant $participant, string $hash)
    {
        $email = self::getEmail();
        $email->to($participant->getEmail())->html(EmailView::newParticipant($participant, $hash))->subject('New Award account confirmation');
        return self::send($email);
    }

    public static function sendForgotPassword(Participant $participant, string $hash)
    {
        $email = self::getEmail();
        $email->to($participant->getEmail())->html(EmailView::sendForgotPassword($participant, $hash))->subject('Award site password reset');
        return self::send($email);
    }

    /**
     * Returns an Email object with default from and reply-to settings.
     * @return Email
     */
    private static function getEmail()
    {
        $contactName = Settings::get('award', 'siteContactName');
        $contactEmail = Settings::get('award', 'siteContactEmail');
        $from = "$contactName <$contactEmail>";
        $email = new Email;
        $email->from($from);
        $email->replyTo($from);
        return $email;
    }

    private static function send(Email $email)
    {
        $transport = Transport::fromDsn('sendmail://default');
        $mailer = new Mailer($transport);
        $mailer->send($email);
        return true;
    }

}
