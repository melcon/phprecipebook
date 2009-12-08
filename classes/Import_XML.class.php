<?php
/**
	Provides the logic to import a XML Recipe file created by PHPRecipeBook
*/
class Import_XML extends Import {
	var $currentRecipe = NULL;
	var $currentIngredient = NULL;
	var $ingredientList = array();
	var $readingElement = NULL;
	var $subElement = NULL;
	
	/**
		Entry point of XML Parsing
		@param $fp file pointer to the XML file to parse
		@return true, if successfully imported all recipes, false if failed
	*/
	function parseDataFileImpl($fp) {		
		$xml_parser = xml_parser_create();
		xml_set_object($xml_parser, $this);
		xml_set_element_handler($xml_parser, "startElement", "endElement");
		xml_set_character_data_handler($xml_parser, "charElement");
		while ($data = fread($fp, 4096)) {
			if (!xml_parse($xml_parser, $data, feof($fp))) {
				die(sprintf("XML error: %s at line %d",
					xml_error_string(xml_get_error_code($xml_parser)),
					xml_get_current_line_number($xml_parser)));
			}
		}
		xml_parser_free($xml_parser);
	}
	
	/**
		Function handles the Start of an XML tag, this function is only called by the XML parser
		@param $parser the parser
		@param $name The name of the TAG
		@param $attrs An array of attributes for this tag
	*/
	function startElement($parser, $element, $attrs) {
		global $DB_LINK;
		$element = strtolower($element);
    	if ($element == "recipe") {
			$this->currentRecipe = new Recipe();
			$this->readingElement = $element;
		} else if ($element == "ingredient") {
			$this->currentIngredient = new Ingredient();
			$this->readingElement = $element;
		} else if ($element == "related") {
			// Save all related items into a global array to be added at the very end
			//echo "Required: " . $attrs['REQUIRED'] . "<br />";
			if ($attrs['REQUIRED'])	$req = $DB_LINK->true;
			else $req = $DB_LINK->false;
			// Save the value
			$this->relatedRecipes[] = array( $this->currentRecipe->name, htmlspecialchars(trim($attrs['VALUE']), ENT_QUOTES), $req); // (parent, child, required)
		} else {
			if ($this->readingElement == "ingredient") {
				// Save the ingredient information, a new ingredient will be create later if needed
				if ($element == "name" && $attrs['VALUE'] != NULL) $this->currentIngredient->name = strtolower(htmlspecialchars(trim($attrs['VALUE']), ENT_QUOTES));
				else if ($element == "qualifier" && $attrs['VALUE'] != NULL) $this->currentIngredient->qualifier = htmlspecialchars(trim($attrs['VALUE']), ENT_QUOTES);
				else if ($element == "quantity" && $attrs['VALUE'] != NULL) $this->currentIngredient->quantity = $attrs['VALUE'];
				else if ($element == "location" && $this->locations[htmlspecialchars(trim($attrs['VALUE']), ENT_QUOTES)] != NULL)
					$this->currentIngredient->location = $this->locations[htmlspecialchars(trim($attrs['VALUE']), ENT_QUOTES)];
				else if ($element == "unit" && $attrs['VALUE'] != NULL) {
					// Set all the units the same for simplicity
					$this->currentIngredient->unit = $this->units[($attrs['VALUE'])];
					$this->currentIngredient->unitMap = $this->currentIngredient->unit;
				}
			} else {
				// Save the recipe information
				if ($element == "name" && $attrs['VALUE'] != NULL) $this->currentRecipe->name = htmlspecialchars(trim($attrs['VALUE']), ENT_QUOTES);
				else if ($element == "ethnicity" && $this->ethnicity[($attrs['VALUE'])] != NULL) $this->currentRecipe->ethnic = $this->ethnicity[($attrs['VALUE'])];
				else if ($element == "base" && $this->bases[($attrs['VALUE'])] != NULL) $this->currentRecipe->base = $this->bases[($attrs['VALUE'])];
				else if ($element == "course" && $this->courses[($attrs['VALUE'])] != NULL) $this->currentRecipe->course = $this->courses[($attrs['VALUE'])];
				else if ($element == "prep_time" && $this->prep_times[($attrs['VALUE'])] != NULL) $this->currentRecipe->prep_time = $this->prep_times[($attrs['VALUE'])];
				else if ($element == "cost" && $this->prep_times[($attrs['VALUE'])] != NULL) $this->currentRecipe->cost = $attrs['VALUE'];
				else if ($element == "difficulty" && $this->difficulty[($attrs['VALUE'])] != NULL) $this->currentRecipe->difficulty = $this->difficulty[($attrs['VALUE'])];
				else if ($element == "serving_size" && $attrs['VALUE'] != NULL) $this->currentRecipe->serving_size = $attrs['VALUE'];
				else if ($element == "owner" && $attrs['VALUE'] != NULL) $this->currentRecipe->owner = $attrs['VALUE'];
				else $this->subElement = $element; //the charElement function will catch it.
			}
		}
	}

	/**
		Function called when an end Element is found (Part of SAX parsing)
	*/
	function endElement($parser, $element) {
		$element = strtolower($element);
		if ($element == "recipe") {
			$this->importRecipes[] = array( $this->currentRecipe, $this->ingredientList);
			$this->ingredientList = array(); // reset the list of ingredients for the next recipe
		} else if ($element == "ingredient") {
			// Save the current ingredient
			$this->ingredientList[] = $this->currentIngredient;
		}
	}

	/**
		Function called when a character element is found (Part of SAX parsing).
	*/
	function charElement($parser, $string) {
		$string = trim($string);
		if ($string != "") {
			if ($this->readingElement == "recipe") {
				if ($this->subElement == "source") $this->currentRecipe->source = htmlspecialchars(trim($string), ENT_QUOTES);
				else if ($this->subElement == "comments") $this->currentRecipe->comments = htmlspecialchars(trim($string), ENT_QUOTES);
				else if ($this->subElement == "directions")	$this->currentRecipe->directions .= htmlspecialchars(trim($string), ENT_QUOTES) . "\n";
			} // we should not see free text anywhere else...
		}
	}
}
?>
