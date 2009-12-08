<?php
/*
	This class is the RecipeML filter for exporting a Recipe. 
*/
class Export_RecipeML extends Export {
	/**
		Converts the recipe(s) into XML
	*/
	function exportData() {
		$out = "<?xml version='1.0'?>\n";
		$out .= "<!DOCTYPE recipeml PUBLIC \"-//FormatData//DTD RecipeML 0.5//EN\"\n";
		$out .= "\"http://www.formatdata.com/recipeml/recipeml.dtd\">\n";
		$out .= "<recipeml version=\"0.5\">\n";
		foreach ($this->exportRecipes as $recipeObj) {
			$out .= $this->getBody($recipeObj);
		}
		$out .= "</recipeml>\n";
		// Save the XML data to a session variable for later download
		$_SESSION[$this->sessionVar] = $out;
		return $out;
	}
	
	/**
		Creates an XML body for this recipe
		@param $recipeObj Instance of a Recipe class
		@return text representation of a recipe in XML
	*/
	function getBody($recipeObj) {
		// TODO: figure out what to do with images...
		$text = "  <recipe>
	<head>
		<title>" . trim($recipeObj->name) . "</title>
	</head>
	<ingredients>\n";
		/*
			Put enough information in the ingredient enty to recreate an ingredient
			master entry if needed.
		*/
		foreach($recipeObj->getIngredients() as $ingObj) {
			$ingObj->loadIngredient(); // Load up the info
			$text .= "\t  <ing>\n";
			$text .= "\t\t<amt><qty>" . trim($ingObj->quantity) . "</qty><unit>" . trim($this->units[($ingObj->unitMap)]) . "</unit></amt>\n";
			$text .= "\t\t<item>" . $ingObj->name . "</item>\n";
			$text .= "\t  </ing>\n";
		}
		$text .= "	</ingredients>
	<directions>
	  <step>" . trim($recipeObj->directions) . "</step>
	</directions>\n";
		// Recipes may have related Recipes and we want to preserve that.
		foreach ($recipeObj->getRelated(false) as $related) {
			$text .= "\t\t<RELATED value=\"" . $related->name . "\"/>\n";
		}
		$text .= "  </recipe>\n";
		return ($text);
	}
}
?>
