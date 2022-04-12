CREATE TABLE award_award (
    id int PRIMARY KEY,
    description text DEFAULT NULL,
    title varchar(255) NOT NULL,
    nominatedDocRequired bool DEFAULT 0,
    publicView bool DEFAULT 0,
    referenceDocRequired bool DEFAULT 0,
    referencesAmount smallint DEFAULT 1,
    selfNominate bool DEFAULT 0,
    winnerAmount smallint DEFAULT 1
);

CREATE TABLE award_cycle (
    id int PRIMARY KEY,
    awardId int,
    awardMonth smallint DEFAULT 0,
    awardYear smallint DEFAULT 0,
    endDate DATETIME DEFAULT CURRENT_TIMESTAMP,
    startDate DATETIME DEFAULT CURRENT_TIMESTAMP,
    voteAllowed bool DEFAULT 0,
    voteType smallint DEFAULT 0,
    INDEX awdIdx(awardId),
    FOREIGN KEY(awardId) REFERENCES award_award(id)
);

CREATE TABLE award_participant (
    id int PRIMARY KEY,
    email varchar(255),
    firstName varchar(255),
    lastName varchar(255),
    password varchar(255)
);

CREATE TABLE award_nomination (
    id int PRIMARY KEY,
    awardId int,
    bannerId int,
    completed bool,
    cycleId int,
    email varchar(255),
    firstName varchar(255),
    lastName varchar(255),
    participantId int,
    FOREIGN KEY(awardId) REFERENCES award_award(id),
    FOREIGN KEY(cycleId) REFERENCES award_cycle(id),
    FOREIGN KEY(participantId) REFERENCES award_participant(id)
);

CREATE TABLE award_document (
    id int PRIMARY KEY,
    filename varchar(255),
    nominationId int,
    title varchar(255),
    FOREIGN KEY(nominationId) REFERENCES award_nomination(id)
);

CREATE TABLE award_cyclelog (
    id int PRIMARY KEY,
    action varchar(255) NOT NULL,
    documentId int,
    participantId int,
    FOREIGN KEY(documentId) REFERENCES award_document(id),
    FOREIGN KEY(participantId) REFERENCES award_participant(id)
);

CREATE TABLE award_cyclewinner (
    id int PRIMARY KEY,
    awardId int,
    cycleId int,
    description text,
    image varchar(255),
    nominationId int,
    FOREIGN KEY(awardId) REFERENCES award_award(id),
    FOREIGN KEY(cycleId) REFERENCES award_cycle(id)
);

CREATE TABLE award_email (
    id int PRIMARY KEY,
    message text,
    replyto varchar(255),
    subject varchar(255)
);

CREATE TABLE award_emaillog (
    id int PRIMARY KEY,
    dateSent timestamp DEFAULT CURRENT_TIMESTAMP,
    emailId int,
    participantId int,
    FOREIGN KEY(emailId) REFERENCES award_email(id),
    FOREIGN KEY(participantId) REFERENCES award_participant(id)
);

CREATE TABLE award_emailtemplate (
    id int PRIMARY KEY,
    awardId int,
    message text,
    subject varchar(255),
    title varchar(255),
    FOREIGN KEY(awardId) REFERENCES award_award(id)
);

CREATE TABLE award_judge  (
    id int PRIMARY KEY,
    cycleId int,
    participantId int,
    FOREIGN KEY(cycleId) REFERENCES award_cycle(id),
    FOREIGN KEY(participantId) REFERENCES award_participant(id)
);

CREATE TABLE award_reference (
    id int PRIMARY KEY,
    cycleId int,
    documentId int,
    nominationId int,
    participantId int,
    FOREIGN KEY(cycleId) REFERENCES award_cycle(id),
    FOREIGN KEY(documentId) REFERENCES award_document(id),
    FOREIGN KEY(nominationId) REFERENCES award_nomination(id),
    FOREIGN KEY(participantId) REFERENCES award_participant(id)
);

CREATE TABLE award_vote (
    id int PRIMARY KEY,
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