INSERT INTO security_groups (group_name) VALUES('Friends');
INSERT INTO security_groups (group_name) VALUES('Family');
INSERT INTO security_groups (group_name) VALUES('Maintainers');

INSERT INTO recipe_ethnicity (ethnic_desc) VALUES ('Italiana');
INSERT INTO recipe_ethnicity (ethnic_desc) VALUES ('Cinese');
INSERT INTO recipe_ethnicity (ethnic_desc) VALUES ('Giapponese');
INSERT INTO recipe_ethnicity (ethnic_desc) VALUES ('Messicana');
INSERT INTO recipe_ethnicity (ethnic_desc) VALUES ('Americana');
INSERT INTO recipe_ethnicity (ethnic_desc) VALUES ('Australiana');
INSERT INTO recipe_ethnicity (ethnic_desc) VALUES ('Greca');
INSERT INTO recipe_ethnicity (ethnic_desc) VALUES ('Slava');
INSERT INTO recipe_ethnicity (ethnic_desc) VALUES ('Nessuna');

INSERT INTO recipe_units (unit_id,unit_desc,unit_abbr) VALUES (1,'Unità','u');
INSERT INTO recipe_units (unit_id,unit_desc,unit_abbr) VALUES (2,'Tazze','tz');
INSERT INTO recipe_units (unit_id,unit_desc,unit_abbr) VALUES (3,'Cucchiai','cuc');
INSERT INTO recipe_units (unit_id,unit_desc,unit_abbr) VALUES (4,'Cucchiaini','cucin');
INSERT INTO recipe_units (unit_id,unit_desc,unit_abbr) VALUES (5,'Prese','pr');
INSERT INTO recipe_units (unit_id,unit_desc,unit_abbr) VALUES (6,'Grammi','gr');
INSERT INTO recipe_units (unit_id,unit_desc,unit_abbr) VALUES (7,'Chilogrammi','kg');
INSERT INTO recipe_units (unit_id,unit_desc,unit_abbr) VALUES (8,'Millilitri','ml');
INSERT INTO recipe_units (unit_id,unit_desc,unit_abbr) VALUES (9,'Litri','l');
INSERT INTO recipe_units (unit_id,unit_desc,unit_abbr) VALUES (10,'Fette','ft');

INSERT INTO recipe_locations (location_desc) VALUES ('Alcolici');
INSERT INTO recipe_locations (location_desc) VALUES ('Panetteria');
INSERT INTO recipe_locations (location_desc) VALUES ('Scatolame');
INSERT INTO recipe_locations (location_desc) VALUES ('Prima colazione');
INSERT INTO recipe_locations (location_desc) VALUES ('Dolciumi');
INSERT INTO recipe_locations (location_desc) VALUES ('Frutta sciroppata');
INSERT INTO recipe_locations (location_desc) VALUES ('Caffe, Te &amp; Cacao');
INSERT INTO recipe_locations (location_desc) VALUES ('Condimanti e sughi');
INSERT INTO recipe_locations (location_desc) VALUES ('Cookies');
INSERT INTO recipe_locations (location_desc) VALUES ('Libero');
INSERT INTO recipe_locations (location_desc) VALUES ('Surgelati');
INSERT INTO recipe_locations (location_desc) VALUES ('Detersivi');
INSERT INTO recipe_locations (location_desc) VALUES ('Macelleria');
INSERT INTO recipe_locations (location_desc) VALUES ('Biologico');
INSERT INTO recipe_locations (location_desc) VALUES ('Olio/Aceto/Comdimenti');
INSERT INTO recipe_locations (location_desc) VALUES ('Pancakes &amp; Syrup');
INSERT INTO recipe_locations (location_desc) VALUES ('Pasta &amp;Riso');
INSERT INTO recipe_locations (location_desc) VALUES ('Snack &amp; Patatine');
INSERT INTO recipe_locations (location_desc) VALUES ('Pescheria');
INSERT INTO recipe_locations (location_desc) VALUES ('Spezie');

INSERT INTO recipe_bases (base_desc) VALUES ('Pasta');
INSERT INTO recipe_bases (base_desc) VALUES ('Pollo');
INSERT INTO recipe_bases (base_desc) VALUES ('Tacchino');
INSERT INTO recipe_bases (base_desc) VALUES ('Manzo');
INSERT INTO recipe_bases (base_desc) VALUES ('Maiale');
INSERT INTO recipe_bases (base_desc) VALUES ('Pesce');
INSERT INTO recipe_bases (base_desc) VALUES ('Verdura');
INSERT INTO recipe_bases (base_desc) VALUES ('Frutta');
INSERT INTO recipe_bases (base_desc) VALUES ('Altro');

INSERT INTO recipe_prep_time (time_desc) VALUES ('Lungo');
INSERT INTO recipe_prep_time (time_desc) VALUES ('Medio');
INSERT INTO recipe_prep_time (time_desc) VALUES ('Rapido');

INSERT INTO recipe_difficulty (difficult_desc) VALUES ('Semplice');
INSERT INTO recipe_difficulty (difficult_desc) VALUES ('Media');
INSERT INTO recipe_difficulty (difficult_desc) VALUES ('Difficile');
INSERT INTO recipe_difficulty (difficult_desc) VALUES ('Da Esperto');

INSERT INTO recipe_courses (course_desc) VALUES ('Colazione');
INSERT INTO recipe_courses (course_desc) VALUES ('Spuntini');
INSERT INTO recipe_courses (course_desc) VALUES ('Primi piatti');
INSERT INTO recipe_courses (course_desc) VALUES ('Secondi piatti');
INSERT INTO recipe_courses (course_desc) VALUES ('Piatti unici');
INSERT INTO recipe_courses (course_desc) VALUES ('Apperitivi');
INSERT INTO recipe_courses (course_desc) VALUES ('contorni');
INSERT INTO recipe_courses (course_desc) VALUES ('Antipasti');
INSERT INTO recipe_courses (course_desc) VALUES ('Dolci');
INSERT INTO recipe_courses (course_desc) VALUES ('Bevanda/drink');

INSERT INTO recipe_meals (meal_name) VALUES ('Colazione');
INSERT INTO recipe_meals (meal_name) VALUES ('Brunch');
INSERT INTO recipe_meals (meal_name) VALUES ('Pranzo');
INSERT INTO recipe_meals (meal_name) VALUES ('Snack');
INSERT INTO recipe_meals (meal_name) VALUES ('Cena');
INSERT INTO recipe_meals (meal_name) VALUES ('Dessert');

INSERT INTO recipe_prices (price_desc) VALUES ('EUR 0-EUR 10');
INSERT INTO recipe_prices (price_desc) VALUES ('EUR 10-EUR 15');
INSERT INTO recipe_prices (price_desc) VALUES ('EUR 15-EUR 20');
INSERT INTO recipe_prices (price_desc) VALUES ('EUR 20-EUR 25');
INSERT INTO recipe_prices (price_desc) VALUES ('EUR 25-EUR 30');
INSERT INTO recipe_prices (price_desc) VALUES ('EUR 30+');
