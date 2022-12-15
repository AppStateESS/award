CREATE TABLE award_award (
    id int PRIMARY KEY AUTO_INCREMENT,
    active bool DEFAULT 0,
    approvalRequired bool default 0,
    creditNominator bool DEFAULT 0,
    cycleTerm varchar(20) default 'yearly',
    defaultVoteType varchar(255) NOT NULL,
    deleted bool DEFAULT 0,
    description text DEFAULT NULL,
    judgeMethod int DEFAULT 1,
    nominationReasonRequired bool DEFAULT 0,
    participantId int,
    publicView bool DEFAULT 0,
    referenceReasonRequired bool DEFAULT 0,
    referencesRequired smallint DEFAULT 1,
    selfNominate bool DEFAULT 0,
    tipNominated bool DEFAULT 0,
    title varchar(255) NOT NULL,
    winnerAmount smallint DEFAULT 1
);

CREATE TABLE award_cycle (
    id int PRIMARY KEY AUTO_INCREMENT,
    awardId int,
    awardMonth smallint DEFAULT 0,
    awardYear smallint DEFAULT 0,
    completed smallint DEFAULT 0,
    deleted smallint DEFAULT 0,
    endDate int default 0,
    lastEndDate int default 0,
    startDate int default 0,
    term varchar(20) default 'yearly',
    voteAllowed bool DEFAULT 0,
    voteType varchar(100) not null,
    INDEX awdIdx(awardId),
    UNIQUE KEY cyclediff (awardId, awardMonth, awardYear),
    FOREIGN KEY(awardId) REFERENCES award_award(id)
);

CREATE TABLE award_badge (
    id int PRIMARY KEY AUTO_INCREMENT,
    awardId int,
    cycleId int,
    filePath varchar(255),
    shortDescription varchar(255) DEFAULT NULL,
    FOREIGN KEY(awardId) REFERENCES award_award(id)
);

CREATE TABLE award_invitation (
    id int PRIMARY KEY AUTO_INCREMENT,
    awardId int DEFAULT 0,
    confirm smallint DEFAULT 0,
    cycleId int DEFAULT 0,
    created DateTime,
    email varchar(255) not null,
    invitedId int DEFAULT 0,
    inviteType smallint DEFAULT 0,
    lastReminder DateTime,
    nominationId int DEFAULT 0,
    nominatedId int DEFAULT 0,
    senderId int DEFAULT 0,
    updated DateTime
);

CREATE TABLE award_participant (
    id int PRIMARY KEY AUTO_INCREMENT,
    active bool default 0,
    authType smallint default 0,
    banned bool default 0,
    created DateTime,
    email varchar(255),
    firstName varchar(255),
    lastName varchar(255),
    password varchar(255) default null,
    trusted smallint default 0,
    updated DateTime,
    UNIQUE KEY uemail (email)
);

CREATE TABLE award_participant_hash (
    participantId int NOT NULL,
    hash varchar(255) NOT NULL,
    timeout int NOT NULL,
    UNIQUE KEY onep (participantId),
    FOREIGN KEY(participantId) REFERENCES award_participant(id)
);

CREATE TABLE award_nomination (
    id int PRIMARY KEY AUTO_INCREMENT,
    allowVote bool default 1,
    approved bool default 0,
    awardId int,
    completed bool default 0,
    cycleId int,
    nominatedId int default 0,
    nominatorId int default 0,
    reasonId int default 0,
    referencesComplete bool default 0,
    referencesSelected bool default 0,
    UNIQUE KEY part_cyc (nominatedId, cycleId),
    FOREIGN KEY(awardId) REFERENCES award_award(id),
    FOREIGN KEY(cycleId) REFERENCES award_cycle(id),
    FOREIGN KEY(nominatorId) REFERENCES award_participant(id),
    FOREIGN KEY(nominatedId) REFERENCES award_participant(id),
);

CREATE TABLE award_document (
    id int PRIMARY KEY AUTO_INCREMENT,
    created DateTime,
    filename varchar(255),
    reasonId int,    
    title varchar(255)
);

CREATE TABLE award_reason (
    id int PRIMARY KEY AUTO_INCREMENT,
    cycleId int default 0,
    documentId int default 0,
    nominationId int default 0,
    reasonText text default '',
    referenceId int default 0,
    reasonType tinyint default 0,
    FOREIGN KEY(cycleId) REFERENCES award_cycle(id),
    FOREIGN KEY(nominationId) REFERENCES award_nomination(id)
);

CREATE TABLE award_cyclelog (
    id int PRIMARY KEY AUTO_INCREMENT,
    action varchar(255) NOT NULL,
    awardId int NOT NULL,
    cycleId int NOT NULL,
    documentId int NULL,
    participantId int NULL,
    stamped TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY(awardId) REFERENCES award_award(id),
    FOREIGN KEY(cycleId) REFERENCES award_cycle(id),
    FOREIGN KEY(documentId) REFERENCES award_document(id),
    FOREIGN KEY(participantId) REFERENCES award_participant(id)
);

CREATE TABLE award_cyclewinner (
    id int PRIMARY KEY AUTO_INCREMENT,
    awardId int,
    cycleId int,
    description text,
    image varchar(255),
    nominationId int,
    FOREIGN KEY(awardId) REFERENCES award_award(id),
    FOREIGN KEY(cycleId) REFERENCES award_cycle(id),
    FOREIGN KEY(nominationId) REFERENCES award_nomination(id)
);

CREATE TABLE award_email (
    id int PRIMARY KEY AUTO_INCREMENT,
    message text,
    replyto varchar(255),
    subject varchar(255)
);

CREATE TABLE award_emaillog (
    id int PRIMARY KEY AUTO_INCREMENT,
    dateSent timestamp DEFAULT CURRENT_TIMESTAMP,
    emailId int,
    participantId int,
    FOREIGN KEY(emailId) REFERENCES award_email(id),
    FOREIGN KEY(participantId) REFERENCES award_participant(id)
);

CREATE TABLE award_emailtemplate (
    id int PRIMARY KEY AUTO_INCREMENT,
    awardId int,
    message text,
    subject varchar(255),
    title varchar(255),
    FOREIGN KEY(awardId) REFERENCES award_award(id)
);

CREATE TABLE award_judge  (
    id int PRIMARY KEY AUTO_INCREMENT,
    cycleId int,
    participantId int,
    voteComplete bool default 0,
    UNIQUE KEY cycpar (cycleId, participantId),
    FOREIGN KEY(cycleId) REFERENCES award_cycle(id),
    FOREIGN KEY(participantId) REFERENCES award_participant(id)
);

CREATE TABLE award_reference (
    id int PRIMARY KEY AUTO_INCREMENT,
    cycleId int,
    nominationId int,
    participantId int,
    reasonDocument int,
    reasonText text default '',
    lastReminder DateTime,
    UNIQUE KEY nompart (nominationId, participantId),
    FOREIGN KEY(cycleId) REFERENCES award_cycle(id),
    FOREIGN KEY(nominationId) REFERENCES award_nomination(id),
    FOREIGN KEY(participantId) REFERENCES award_participant(id)
);

CREATE TABLE award_vote (
    id int PRIMARY KEY AUTO_INCREMENT,
    awardId int,
    cycleId int,
    judgeId int,
    nominationId int,
    score int,
    FOREIGN KEY(awardId) REFERENCES award_award(id),
    FOREIGN KEY(cycleId) REFERENCES award_cycle(id),
    FOREIGN KEY(judgeId) REFERENCES award_judge(id),
    FOREIGN KEY(nominationId) REFERENCES award_nomination(id)
);