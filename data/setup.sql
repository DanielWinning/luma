DROP SCHEMA IF EXISTS Application;
DROP SCHEMA IF EXISTS Security;

CREATE SCHEMA Application;
CREATE SCHEMA Security;

USE Security;

CREATE TABLE tblUser (
    intUserId INT UNSIGNED AUTO_INCREMENT NOT NULL,
    strUsername VARCHAR(60) NOT NULL,
    strEmailAddress VARCHAR(255) NOT NULL,
    strPassword VARCHAR(255) NOT NULL,
    PRIMARY KEY (intUserId),
    UNIQUE KEY (strUsername),
    UNIQUE KEY (strEmailAddress)
);

CREATE TABLE ublPermission (
    intPermissionId INT UNSIGNED AUTO_INCREMENT NOT NULL,
    strPermissionName VARCHAR(255) NOT NULL,
    strPermissionHandle VARCHAR(255) NOT NULL,
    PRIMARY KEY (intPermissionId),
    UNIQUE KEY (strPermissionHandle)
);

CREATE TABLE ublRole (
    intRoleId INT UNSIGNED AUTO_INCREMENT NOT NULL,
    strRoleName VARCHAR(255) NOT NULL,
    strRoleHandle VARCHAR(255) NOT NULL UNIQUE,
    PRIMARY KEY (intRoleId),
    UNIQUE KEY (strRoleHandle)
);

CREATE TABLE tblRoleUser (
    intRoleId INT UNSIGNED NOT NULL,
    intUserId INT UNSIGNED NOT NULL,
    FOREIGN KEY (intRoleId) REFERENCES ublRole(intRoleId),
    FOREIGN KEY (intUserId) REFERENCES tblUser(intUserId),
    UNIQUE KEY (intRoleId, intUserId)
);

CREATE TABLE tblPermissionRole (
    intPermissionId INT UNSIGNED NOT NULL,
    intRoleId INT UNSIGNED NOT NULL,
    FOREIGN KEY (intPermissionId) REFERENCES ublPermission(intPermissionId),
    FOREIGN KEY (intRoleId) REFERENCES ublRole(intRoleId),
    UNIQUE KEY (intPermissionId, intRoleId)
);