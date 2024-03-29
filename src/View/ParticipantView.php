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

use award\Factory\AuthenticateFactory;
use award\Factory\CycleFactory;
use award\Factory\InvitationFactory;
use award\Factory\JudgeFactory;
use award\Factory\NominationFactory;
use award\Factory\ParticipantFactory;
use award\Factory\ReferenceFactory;
use award\AbstractClass\AbstractView;

class ParticipantView extends AbstractView
{

    public static function adminList()
    {
        $params['menu'] = self::adminMenu('participant');
        $params['script'] = self::scriptView('ParticipantList');
        return self::getTemplate('Admin/AdminForm', $params);
    }

    public static function adminLoggedWarning()
    {
        return parent::centerCard('Currently logged administrator',
                self::getTemplate('Admin/Error/LoggedWarning', ['logout' => parent::getLogoutUrl()]), 'danger');
    }

    public static function authorizeComplete()
    {
        return self::getTemplate('User/AuthorizeComplete');
    }

    public static function authorizeFailed()
    {
        return parent::centerCard('Authorization failed', self::getTemplate('User/AuthorizeFailed'), 'danger');
    }

    /**
     * Displays the sign up form for new participant accounts.
     *
     * @return string
     */
    public static function createAccount(string $email = null)
    {
        self::scriptView('SignUpForm', ['defaultEmail' => $email]);
        $vars['signinButtons'] = AuthenticateFactory::getSignInButtons();
        return self::getTemplate('User/CreateAccount', $vars, true);
    }

    /**
     * A view for users logged in to SSO.
     * @param string $email Email address of SSO participant
     * @return string
     */
    public static function createSignedInAccount(string $email)
    {
        $token = ParticipantFactory::loadCreateToken();
        return self::getTemplate('User/CreateSignedInAccount',
                [
                    'email' => $email,
                    'signUpLink' => "./award/User/Participant/saveNewAccount?token=$token"
                ]
        );
    }

    /**
     * View for participants to manage their award duties.
     */
    public static function dashboard()
    {
        $participant = ParticipantFactory::getCurrentParticipant();

        // prevents nominate button from appearing for judges
        $values['judgedCycles'] = [];
        $judged = CycleFactory::upcomingJudged($participant->id);
        if ($judged) {
            foreach ($judged as $j) {
                $values['judgedCycles'][] = $j['cycleId'];
            }
        }

        $nominations = NominationFactory::listing(['nominatorId' => $participant->id, 'includeNominated' => true, 'includeAward' => true, 'includeCycle' => true]);

        $values['nominations'] = [];
        if (!empty($nominations)) {
            $values['nominations'] = $nominations;
        }

        $values['references'] = ReferenceFactory::listing([
                'includeCycleEnd' => true,
                'participantId' => $participant->getId(),
                'includeParticipant' => true,
                'includeNominator' => true,
                'includeNominated' => true,
                'includeAward' => true
        ]);

        $values['judged'] = $judged;
        $values['trusted'] = (bool) ParticipantFactory::currentIsTrusted();
        $values['participant'] = $participant;

        $cycleOptions['upcoming'] = true;
        $cycleOptions['includeAward'] = true;
        $cycleOptions['dateFormat'] = true;
        $values['upcomingCycles'] = CycleFactory::listing($cycleOptions);
        $values['cycleFunction'] = function ($cycle) {
            if ($cycle['term'] === 'yearly') {
                return $cycle['awardYear'];
            } else {
                return DateTime::createFromFormat('!m', $cycle['awardMonth'])->format('F');
            }
        };

        $value['trusted'] = $participant->isTrusted();

        $values['allowNominateButton'] = function ($cycle) {
            return $cycle['startDate'] < time() && $cycle['endDate'] > time();
        };

        $values['participantInvitations'] = self::scriptView('ParticipantInvitations');
        return self::participantMenu('dashboard') . self::getTemplate('Participant/Dashboard', $values);
    }

    /**
     * Informs the user their account request is saved and to check
     * their email for confirmation.
     *
     * @return string
     */
    public static function emailSent()
    {
        return self::getTemplate('User/EmailSent');
    }

    public static function error()
    {
        return self::getTemplate('User/Error', ['contactEmail' => \phpws2\Settings::get('award', 'siteContactEmail')]);
    }

    public static function forgotPassword()
    {
        return self::scriptView('ForgotPassword');
    }

    public static function forgotPasswordPost($email)
    {
        return self::getTemplate('User/ForgotPost', ['email' => $email]);
    }

    public static function inaccessible()
    {
        return self::centerCard('Participant not accessible', self::getTemplate('Error/Inaccessible'), 'danger');
    }

    /**
     * A user attempted to perform a Participant action (create account, change password)
     * but the hash was incorrect or timed out.
     * @return type
     */
    public static function invalidHash()
    {
        return self::getTemplate('User/InvalidHash');
    }

    public static function notLoggedInError()
    {
        return self::getTemplate('User/NotLoggedIn', ['loginLink' => \award\Factory\Authenticate::getLoginLink()]);
    }

    public static function passwordChangeComplete()
    {
        return self::centerCard('Password change complete', self::getTemplate('User/PasswordChangeComplete'));
    }

    public static function resetPassword($participantId, $hash)
    {
        return self::scriptView('ResetPassword', ['participantId' => $participantId, 'hash' => $hash]);
    }

    /**
     * Show the sign in form for current participants.
     *
     * @return string
     */
    public static function signIn()
    {
        $participant = ParticipantFactory::getCurrentParticipant();
        if ($participant) {
            \Canopy\Server::forward('./award/Participant/Participant/dashboard');
        }
        if (\Current_User::isLogged()) {
            return self::adminLoggedWarning();
        }
        self::scriptView('SignInForm');
        return self::getTemplate('User/SignIn');
    }

    public static function signedOut()
    {
        return self::getTemplate('Participant/SignedOut');
    }

    /**
     * Shows an error if the attempt at creating a participant failed due
     * to not being logged in or the token was wrong.
     *
     * @return string
     */
    public static function signInCreateError()
    {

    }

}
