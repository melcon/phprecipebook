<?php
/*
	This class is the default filter for exporting an Recipe.  The XML DTD will be saved
	in includes later
*/
class Export_XML extends Export {
	/**
		Converts the recipe(s) into XML
	*/
	function exportData() {
		$out = "<?xml version='1.0'?>\n";
		$out .= "<!DOCTYPE recipe SYSTEM \"phprecipebook.dtd\">\n";
		$out .= "<RECIPES>\n";
		foreach ($this->exportRecipes as $recipeObj) {
			$out .= $this->getBody($recipeObj);
		}
		$out .= "</RECIPES>\n";
		// Save the XML data to a session variable for later download
		$_SESSION[$this->sessionVar] = $out;
		// Translate the special characters to HTML save types so it can be printed
		$out = str_replace( "<", '&lt;', $out);
		$out = str_replace( ">", '&gt;', $out);
		$out = str_replace( "&", '&amp;', $out);
		return $out;
	}
	
	/**
		Creates an XML body for this recipe
		@param $recipeObj Instance of a Recipe class
		@return text representation of a recipe in XML
	*/
	function getBody($recipeObj) {
		// TODO: figure out what to do with images...
		$text = "\t<RECIPE>
		<NAME value=\"" . trim($recipeObj->name) . "\"/>
		<ETHNICITY value=\"" . trim($this->ethnicity[($recipeObj->ethnic)]) . "\"/>
		<BASE value=\"" . trim($this->bases[($recipeObj->base)]) . "\"/>
		<COURSE value=\"" . trim($this->courses[($recipeObj->course)]) . "\"/>
		<PREP_TIME value=\"" . trim($this->prep_times[($recipeObj->prep_time)]) . "\"/>
		<COST value=\"" . trim($recipeObj->cost) . "\"/>
		<DIFFICULTY value=\"" . trim($this->difficulty[($recipeObj->difficulty)]) . "\"/>
		<SERVING_SIZE value=\"" . trim($recipeObj->serving_size) . "\"/>
		<OWNER value=\"" . trim($recipeObj->owner) . "\"/>
		<SOURCE>" . trim($recipeObj->source) . "</SOURCE>
		<COMMENTS>" . htmlspecialchars(trim($recipeObj->comments), ENT_QUOTES) . "</COMMENTS>
		<DIRECTIONS>" . trim($recipeObj->directions) . "</DIRECTIONS>
		<INGREDIENTS>\n";
		/*
			Put enough information in the ingredient enty to recreate an ingredient
			master entry if needed.
		*/
		foreach($recipeObj->getIngredients() as $ingObj) {
			$ingObj->loadIngredient(); // Load up the info
			$text .= "\t\t\t<INGREDIENT>\n";
			$text .= "\t\t\t\t<NAME value=\"" . $ingObj->name . "\"/>\n";
			$text .= "\t\t\t\t<QUALIFIER value=\"" . trim($ingObj->qualifier) . "\"/>\n";
			$text .= "\t\t\t\t<QUANTITY value=\"" . trim($ingObj->quantity) . "\"/>\n";
			$text .= "\t\t\t\t<UNIT value=\"" . trim($this->units[($ingObj->unitMap)]) . "\"/>\n";
			$text .= "\t\t\t\t<LOCATION value=\"" . trim(isset($this->locations[($ingObj->location)]) ? $this->locations[($ingObj->location)] : '') . "\"/>\n";
			$text .= "\t\t\t</INGREDIENT>\n";
		}
		$text .= "\t\t</INGREDIENTS>\n";
		// Recipes may have related Recipes and we want to preserve that.
		foreach ($recipeObj->getRelated(false) as $related) {
			$text .= "\t\t<RELATED value=\"" . $related->name . "\"/>\n";
		}
		$text .= "\t</RECIPE>\n";
		return ($text);
	}
}
?>
