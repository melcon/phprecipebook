CREATE SEQUENCE security_group_id_seq start 1 increment 1 maxvalue 9223372036854775807 minvalue 1 cache 1;

CREATE TABLE security_groups (
	group_id INTEGER DEFAULT nextval('security_group_id_seq') PRIMARY KEY,
	group_name VARCHAR(32) NOT NULL DEFAULT '');

CREATE TABLE security_users (
	user_login VARCHAR(32) NOT NULL PRIMARY KEY UNIQUE,
	user_password VARCHAR(64) NOT NULL DEFAULT '',
	user_name VARCHAR(32) NOT NULL DEFAULT '',
	user_access_level INTEGER NOT NULL DEFAULT '0',
	user_language VARCHAR(8) DEFAULT 'en' NOT NULL,
	user_date_created DATE DEFAULT current_date(),
	user_last_login DATE,
	user_email VARCHAR(80));
	
CREATE TABLE security_members (
	member_group INTEGER REFERENCES security_groups,
	member_login VARCHAR(32) REFERENCES security_users);


INSERT INTO security_users (user_login,user_password,user_name,user_access_level) VALUES ('admin', '76a2173be6393254e72ffa4d6df1030a', 'Administrator', '99');


INSERT INTO security_groups (group_name) VALUES('Friends');
INSERT INTO security_groups (group_name) VALUES('Family');
INSERT INTO security_groups (group_name) VALUES('Maintainers');
