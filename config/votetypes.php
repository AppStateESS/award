<?php

/**
 * MIT License
 * Copyright (c) 2022 Electronic Student Services @ Appalachian State University
 *
 * See LICENSE file in root directory for copyright and distribution permissions.
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 *
 * $votetypes is a list of voting methods within the src/VoteTypes directory.
 */
$votetypes = [
    [
        'title' => 'Single vote',
        'description' => 'Judges (or other participants) get a single vote on a nomination.',
        'className' => 'SingleVote',
        'allowParticipantVoting' => true
    ],
    [
        'title' => 'Multiple votes',
        'description' => 'Judges vote for multiple nominations. All votes are equal',
        'className' => 'MultipleVote',
        'allowParticipantVoting' => true
    ]
];
