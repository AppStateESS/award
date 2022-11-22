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
use award\Factory\JudgeFactory;
use award\Resource\Award;
use award\Resource\Cycle;
use award\Resource\Invitation;
use award\Resource\Nomination;
use award\Resource\Participant;
use award\Resource\Reference;
use award\View\EmailView;
use award\Exception\NoJudges;

class EmailFactory
{

    /**
     * Sends a confirmation email to the administators to let them know a judge
     * confirmed their invitation.
     *
     * @param Award $award
     * @param Cycle $cycle
     * @param Participant $participant
     * @return type
     */
    public static function judgeConfirmed(Award $award, Cycle $cycle, Participant $participant)
    {
        $email = self::getEmail();
        $email->to(Settings::get('award', 'siteContactEmail'))->html(EmailView::judgeConfirmed($award, $cycle, $participant))->subject('Award judge confirmed their participation');
        return self::send($email);
    }

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

    public static function referenceReminder(Reference $reference)
    {
        $referenceParticipant = ParticipantFactory::build($reference->participantId);
        $content = EmailView::referenceReminder($reference);
        $email = self::getEmail();
        $email->to($referenceParticipant->getEmail())->html($content)->subject('Reminder: please complete your award reference');
        self::send($email);
    }

    public static function referenceConfirmed(Award $award, Cycle $cycle, Participant $participant)
    {

    }

    public static function remindJudgeInvitation(Invitation $invitation)
    {

    }

    public static function remindReferenceInvitation(Invitation $invitation)
    {
        $invited = ParticipantFactory::build($invitation->invitedId);
        $content = EmailView::referenceInviteReminder($invitation);
        $email = self::getEmail();
        $email->to($invited->getEmail())->html($content)->subject('Reminder: please respond to your reference request');
        self::send($email);
    }

    public static function remindJudges($cycleId, $content)
    {
        $cycle = CycleFactory::build($cycleId);
        $judges = JudgeFactory::listing(['cycleId' => $cycleId, 'includeParticipant' => true]);
        if (empty($judges)) {
            throw new NoJudges();
        }
        foreach ($judges as $j) {
            $email = self::getEmail();
            $email->to($j['email'])->html($content)->subject('Time to Vote for Plemmons Medallion!');
            self::send($email);
        }
    }

    public static function sendForgotPassword(Participant $participant, string $hash)
    {
        $email = self::getEmail();
        $email->to($participant->getEmail())->html(EmailView::sendForgotPassword($participant, $hash))->subject('Award site password reset');
        return self::send($email);
    }

    public static function sendParticipantJudgeInvitation(Invitation $invitation)
    {
        $email = self::getEmail();
        $email->to($invitation->email)->html(EmailView::participantJudgeInvitation($invitation))->subject('Award site judge request');
        return self::send($email);
    }

    public static function sendParticipantReferenceInvitation(Invitation $invitation)
    {
        $email = self::getEmail();
        $email->to($invitation->email)->html(EmailView::participantReferenceInvitation($invitation))->subject('Award site reference request');
        return self::send($email);
    }

    /**
     * Returns an Email object with default from and reply-to settings.
     * @return Symfony\Component\Mime\Email
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
