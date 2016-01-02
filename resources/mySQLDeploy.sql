CREATE TABLE IF NOT EXISTS `buddyfactions_factions` (
			buddypress_gid INT, //TODO Check is INT
                        allies LONGTEXT,
                        enemies LONGTEXT
);

CREATE TABLE IF NOT EXISTS `buddyfactions_users` (
			`username` VARCHAR(50),
			`faction_id` INT,
			`faction_joindate` DATETIME,
			`power` FLOAT,
                        `money` BIGINT,
                        `perm_canBuild` INT,
                        `perm_canUnclaim` INT,
                        `perm_canClaim` INT,
                        `perm_isAdmin` INT
			`rankname` VARCHAR(50)
);

CREATE TABLE IF NOT EXISTS `buddyfactions_shop_broughtitems` (
			`username` VARCHAR(50),
			`item_id` INT,
                        `item_meta_id` INT,
                        `item_qty` BIGINT
);


CREATE TABLE IF NOT EXISTS `buddyfactions_plots` (
			X INT,
			Z INT,
                        level VARCHAR(50),
			AdminOnly INT,
                        owningFaction INT
);

CREATE TABLE IF NOT EXISTS `buddyfactions_events` (
                       eventTime TIMESTAMP,
                       eventType INT,
                       sourcePlayer VARCHAR(50),
                       sourceFactionId INT,
                       destPlayer VARCHAR(50),
                       destFactionId INT,
                       eventHandled INT
);