CREATE TABLE award_award (
    id int PRIMARY KEY AUTO_INCREMENT,
    active bool DEFAULT 0,
    creditNominator bool DEFAULT 0,
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
    endDate DATETIME DEFAULT CURRENT_TIMESTAMP,
    startDate DATETIME DEFAULT CURRENT_TIMESTAMP,
    voteAllowed bool DEFAULT 0,
    voteType smallint DEFAULT 0,
    INDEX awdIdx(awardId),
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

CREATE TABLE award_participant (
    id int PRIMARY KEY AUTO_INCREMENT,
    active bool default 0,
    authType smallint default 0,
    banned bool default 0,
    email varchar(255),
    firstName varchar(255),
    hash varchar(255),
    lastName varchar(255),
    password varchar(255) default null,
    created DateTime,
    updated DateTime,
    UNIQUE KEY uemail (email)
);

CREATE TABLE award_nomination (
    id int PRIMARY KEY AUTO_INCREMENT,
    awardId int,
    bannerId int,
    completed bool default 0,
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
    id int PRIMARY KEY AUTO_INCREMENT,
    filename varchar(255),
    nominationId int,
    title varchar(255),
    FOREIGN KEY(nominationId) REFERENCES award_nomination(id)
);

CREATE TABLE award_cyclelog (
    id int PRIMARY KEY AUTO_INCREMENT,
    action varchar(255) NOT NULL,
    documentId int,
    participantId int,
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
    FOREIGN KEY(cycleId) REFERENCES award_cycle(id)
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
    FOREIGN KEY(cycleId) REFERENCES award_cycle(id),
    FOREIGN KEY(participantId) REFERENCES award_participant(id)
);

CREATE TABLE award_reference (
    id int PRIMARY KEY AUTO_INCREMENT,
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