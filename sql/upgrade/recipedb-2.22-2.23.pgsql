ALTER TABLE recipe_settings ADD COLUMN setting_mp_day INTEGER;
UPDATE recipe_settings SET setting_version='2.23',setting_mp_day=5;
