CREATE TABLE recipe_sources (
	source_id INTEGER DEFAULT nextval('recipe_source_id_seq'),
	source_title VARCHAR(64),
	source_desc TEXT,
	PRIMARY KEY (source_id)
);

ALTER TABLE recipe_recipes RENAME COLUMN recipe_source TO recipe_source_desc;
ALTER TABLE recipe_recipes ADD COLUMN recipe_source INTEGER REFERENCES recipes_sources(source_id) ON DELETE SET NULL;

INSERT INTO recipe_sources(source_desc) SELECT setting_sources FROM recipe_settings;
UPDATE recipe_sources SET source_title='Default Source';

ALTER TABLE recipe_settings DROP COLUMN setting_sources;
UPDATE recipe_settings SET setting_version='2.33';


