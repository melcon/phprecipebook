INSERT INTO security_groups (group_name) VALUES('Amis');
INSERT INTO security_groups (group_name) VALUES('Famille');
INSERT INTO security_groups (group_name) VALUES('Mainteneurs');

INSERT INTO recipe_ethnicity (ethnic_desc) VALUES ('Am�ricain');
INSERT INTO recipe_ethnicity (ethnic_desc) VALUES ('Chinois');
INSERT INTO recipe_ethnicity (ethnic_desc) VALUES ('Allemand');
INSERT INTO recipe_ethnicity (ethnic_desc) VALUES ('Grec');
INSERT INTO recipe_ethnicity (ethnic_desc) VALUES ('Italien');
INSERT INTO recipe_ethnicity (ethnic_desc) VALUES ('Japonais');
INSERT INTO recipe_ethnicity (ethnic_desc) VALUES ('Mexicain');
INSERT INTO recipe_ethnicity (ethnic_desc) VALUES ('Middle Eastern');
INSERT INTO recipe_ethnicity (ethnic_desc) VALUES ('Aucune');
INSERT INTO recipe_ethnicity (ethnic_desc) VALUES ('Slave');

INSERT INTO recipe_units (unit_id,unit_desc,unit_abbr) VALUES (1,'Unit�','u');
INSERT INTO recipe_units (unit_id,unit_desc,unit_abbr) VALUES (2,'Tranche','tr');
INSERT INTO recipe_units (unit_id,unit_desc,unit_abbr) VALUES (3,'Gousse','gs');
INSERT INTO recipe_units (unit_id,unit_desc,unit_abbr) VALUES (4,'Pinc�e','pi');
INSERT INTO recipe_units (unit_id,unit_desc,unit_abbr) VALUES (5,'Paquet','pq');
INSERT INTO recipe_units (unit_id,unit_desc,unit_abbr) VALUES (6,'Bo�te','bt');
INSERT INTO recipe_units (unit_id,unit_desc,unit_abbr) VALUES (7,'Goutte','gt');
INSERT INTO recipe_units (unit_id,unit_desc,unit_abbr) VALUES (8,'Bouquet','bq');
INSERT INTO recipe_units (unit_id,unit_desc,unit_abbr) VALUES (9,'Larme','lr');
INSERT INTO recipe_units (unit_id,unit_desc,unit_abbr) VALUES (10,'Carton','ct');
INSERT INTO recipe_units (unit_id,unit_desc,unit_abbr) VALUES (11,'Coupe','cp');
INSERT INTO recipe_units (unit_id,unit_desc,unit_abbr) VALUES (12,'Cuill�re � soupe (am.)','cs');
INSERT INTO recipe_units (unit_id,unit_desc,unit_abbr) VALUES (13,'Cuill�re � caf� (am.)','cc');
INSERT INTO recipe_units (unit_id,unit_desc,unit_abbr) VALUES (14,'Livre','lb');
INSERT INTO recipe_units (unit_id,unit_desc,unit_abbr) VALUES (15,'Once','oz');
INSERT INTO recipe_units (unit_id,unit_desc,unit_abbr) VALUES (16,'Pinte','pt');
INSERT INTO recipe_units (unit_id,unit_desc,unit_abbr) VALUES (17,'Quart','qt');
INSERT INTO recipe_units (unit_id,unit_desc,unit_abbr) VALUES (18,'Gallon','gl');
INSERT INTO recipe_units (unit_id,unit_desc,unit_abbr) VALUES (19,'Milligramme','mg');
INSERT INTO recipe_units (unit_id,unit_desc,unit_abbr) VALUES (20,'Centigramme','cg');
INSERT INTO recipe_units (unit_id,unit_desc,unit_abbr) VALUES (21,'Gramme','g');
INSERT INTO recipe_units (unit_id,unit_desc,unit_abbr) VALUES (22,'Kilogramme','kg');
INSERT INTO recipe_units (unit_id,unit_desc,unit_abbr) VALUES (23,'Millilitre','ml');
INSERT INTO recipe_units (unit_id,unit_desc,unit_abbr) VALUES (24,'Centilitre','cl');
INSERT INTO recipe_units (unit_id,unit_desc,unit_abbr) VALUES (25,'Litre','l');
INSERT INTO recipe_units (unit_id,unit_desc,unit_abbr) VALUES (26,'D�cilitre','dl');
INSERT INTO recipe_units (unit_id,unit_desc,unit_abbr) VALUES (27,'Cuill�re � soupe','');
INSERT INTO recipe_units (unit_id,unit_desc,unit_abbr) VALUES (28,'Cuill�re � caf�','');



INSERT INTO recipe_locations (location_desc) VALUES ('Alcools');
INSERT INTO recipe_locations (location_desc) VALUES ('Boulangerie');
INSERT INTO recipe_locations (location_desc) VALUES ('Boucherie');
INSERT INTO recipe_locations (location_desc) VALUES ('Volailles');
INSERT INTO recipe_locations (location_desc) VALUES ('Fruits de mer');
INSERT INTO recipe_locations (location_desc) VALUES ('Poissonnerie');
INSERT INTO recipe_locations (location_desc) VALUES ('Riz, P�tes &amp; sauces');
INSERT INTO recipe_locations (location_desc) VALUES ('Fruits & l�gumes');
INSERT INTO recipe_locations (location_desc) VALUES ('Soupes');
INSERT INTO recipe_locations (location_desc) VALUES ('Cr�merie');
INSERT INTO recipe_locations (location_desc) VALUES ('Caf�, th�, chocolat');
INSERT INTO recipe_locations (location_desc) VALUES ('Conserves');
INSERT INTO recipe_locations (location_desc) VALUES ('Surgel�s');
INSERT INTO recipe_locations (location_desc) VALUES ('Hygi�ne');
INSERT INTO recipe_locations (location_desc) VALUES ('Jus de fruits');
INSERT INTO recipe_locations (location_desc) VALUES ('Produits d\'entretien');
INSERT INTO recipe_locations (location_desc) VALUES ('En-cas sal�s');

INSERT INTO recipe_bases (base_desc) VALUES ('Autre');
INSERT INTO recipe_bases (base_desc) VALUES ('Boeuf');
INSERT INTO recipe_bases (base_desc) VALUES ('Poisson');
INSERT INTO recipe_bases (base_desc) VALUES ('Pain');
INSERT INTO recipe_bases (base_desc) VALUES ('Oeufs');
INSERT INTO recipe_bases (base_desc) VALUES ('Fruits');
INSERT INTO recipe_bases (base_desc) VALUES ('Agneau');
INSERT INTO recipe_bases (base_desc) VALUES ('P�tes');
INSERT INTO recipe_bases (base_desc) VALUES ('Porc');
INSERT INTO recipe_bases (base_desc) VALUES ('Volaille');
INSERT INTO recipe_bases (base_desc) VALUES ('Fruits de mer');
INSERT INTO recipe_bases (base_desc) VALUES ('L�gumes');

INSERT INTO recipe_prep_time (time_desc) VALUES ('0 Minutes');
INSERT INTO recipe_prep_time (time_desc) VALUES ('1-10 Minutes');
INSERT INTO recipe_prep_time (time_desc) VALUES ('10-30 Minutes');
INSERT INTO recipe_prep_time (time_desc) VALUES ('30-60 Minutes');
INSERT INTO recipe_prep_time (time_desc) VALUES ('60+ Minutes');

INSERT INTO recipe_difficulty (difficult_desc) VALUES ('Facile');
INSERT INTO recipe_difficulty (difficult_desc) VALUES ('Moyen');
INSERT INTO recipe_difficulty (difficult_desc) VALUES ('Difficile');
INSERT INTO recipe_difficulty (difficult_desc) VALUES ('Expert');

INSERT INTO recipe_courses (course_desc) VALUES ('Petit d�jeuner');
INSERT INTO recipe_courses (course_desc) VALUES ('En-cas');
INSERT INTO recipe_courses (course_desc) VALUES ('D�jeuner');
INSERT INTO recipe_courses (course_desc) VALUES ('Entr�e');
INSERT INTO recipe_courses (course_desc) VALUES ('Accompagnement');
INSERT INTO recipe_courses (course_desc) VALUES ('Plat principal');
INSERT INTO recipe_courses (course_desc) VALUES ('Dessert');
INSERT INTO recipe_courses (course_desc) VALUES ('Boisson');

INSERT INTO recipe_meals (meal_name) VALUES ('Petit d�jeuner');
INSERT INTO recipe_meals (meal_name) VALUES ('En-cas');
INSERT INTO recipe_meals (meal_name) VALUES ('D�jeuner');
INSERT INTO recipe_meals (meal_name) VALUES ('4 heures');
INSERT INTO recipe_meals (meal_name) VALUES ('D�ner');
INSERT INTO recipe_meals (meal_name) VALUES ('Dessert');

INSERT INTO recipe_prices (price_desc) VALUES ('$0-$10');
INSERT INTO recipe_prices (price_desc) VALUES ('$10-$15');
INSERT INTO recipe_prices (price_desc) VALUES ('$15-$20');
INSERT INTO recipe_prices (price_desc) VALUES ('$20-$25');
INSERT INTO recipe_prices (price_desc) VALUES ('$25-$30');
INSERT INTO recipe_prices (price_desc) VALUES ('$30+');

