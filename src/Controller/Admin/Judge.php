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
use award\View\JudgeView;
use award\Factory\JudgeFactory;
use award\Factory\CycleFactory;
use award\Factory\ParticipantFactory;
use award\Factory\EmailFactory;
use award\Factory\CycleLogFactory;
use Canopy\Request;

class Judge extends AbstractController
{

    protected function listJson(Request $request)
    {
        $options = [];
        $options['cycleId'] = $request->pullGetInteger('cycleId', true);
        $options['includeParticipant'] = true;
        return JudgeFactory::listing($options);
    }

    protected function remindHtml(Request $request)
    {
        $cycleId = $request->pullGetInteger('cycleId');
        $cycle = CycleFactory::build($cycleId);
        if (empty($cycle)) {
            throw new ResourceNotFound();
        }
        if (!JudgeFactory::canSendJudgeReminder($cycleId)) {
            return JudgeView::cannotSendReminder($cycle);
        }
        return JudgeView::remind($cycle);
    }

    protected function remindPost(Request $request)
    {
        $cycleId = $request->pullGetInteger('cycleId');
        $cycle = CycleFactory::build($cycleId);
        $header = JudgeView::remindHeader($cycle);
        $extra = trim($request->pullPostString('extra'));
        if (!empty($extra)) {
            if (!preg_match("/<\/\w/", $extra)) {
                $extra = '<p>' . nl2br($extra) . '</p>';
            }
        }
        $content = $header . $extra;
        try {
            EmailFactory::remindJudges($cycleId, $content);
            CycleLogFactory::stampJudgeRemind($cycle, \Current_User::getUsername());
            \Canopy\Server::forward("./award/Admin/Cycle/$cycleId/judgeReminderSent");
        } catch (\award\Exception\NoJudges $e) {
            return JudgeView::noJudges($cycle);
        }
    }

}
