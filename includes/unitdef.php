<?php
/**
        This file defines the conversion coeff for each type of unit in U.S. Standard, Metric, and Imperial
        for either wet or dry ingredients.
        The format is:
                $g_rb_units["system"]["wet|dry"][unit_id] = coeff; //Note on Unit Type
*/
global $g_rb_units, $g_rb_unitmap;

// Setup an array with unit names to unique id's just in case we need to change them later
$g_rb_unitmap = array(
        'unit'=> 1,
        'slice' => 2,
        'clove' => 3,
        'pinch' => 4,
        'package' => 5,
        'can' => 6,
        'drop' => 7,
        'bunch' => 8,
        'dash' => 9,
        'carton' => 10,
        'cup' => 11,
        'tablespoon' => 12,
        'teaspoon' => 13,
        'pound' => 14,
        'ounce' => 15,
        'pint' => 16,
        'quart' => 17,
        'gallon' => 18,
        'milligram' => 19,
        'centigram' => 20,
        'gram' => 21,
        'kilogram' => 22,
        'milliliter' => 23,
        'centiliter' => 24,
        'liter' => 25,
        'deciliter' => 26,
        'tablespoon_m' => 27,
        'teaspoon_m' => 28
);

// Units that never get converted
$g_rb_units['static'] = array(
                                                                $g_rb_unitmap['unit'],
                                                                $g_rb_unitmap['slice'],
                                                                $g_rb_unitmap['clove'],
                                                                $g_rb_unitmap['pinch'],
                                                                $g_rb_unitmap['package'],
                                                                $g_rb_unitmap['can'],
                                                                $g_rb_unitmap['drop'],
                                                                $g_rb_unitmap['bunch'],
                                                                $g_rb_unitmap['dash'],
                                                                $g_rb_unitmap['carton']                                                                
                                                        );

// -------------- U.S Section ----------------- //
// Wet
$g_rb_units["usa"]["wet"][($g_rb_unitmap['gallon'])] = array( 3.785411784, 3.785411784);                //Gallon
$g_rb_units["usa"]["wet"][($g_rb_unitmap['liter'])] = array ( 1, 3.785411784/2 );                       //Liter
$g_rb_units["usa"]["wet"][($g_rb_unitmap['quart'])] = array( 3.785411784/4, 3.785411784/4);             //Quart
$g_rb_units["usa"]["wet"][($g_rb_unitmap['pint'])] = array( 3.785411784/8, 3.785411784/8);              //Pint
$g_rb_units["usa"]["wet"][($g_rb_unitmap['cup'])] = array( 3.785411784/16, 3.785411784/16/4);           //Cup
$g_rb_units["usa"]["wet"][($g_rb_unitmap['ounce'])] = array( 3.785411784/128, 3.785411784/128);         //Fluid Ounce
$g_rb_units["usa"]["wet"][($g_rb_unitmap['tablespoon'])] = array( 3.785411784/256, 3.785411784/256);	//Tablespoon
$g_rb_units["usa"]["wet"][($g_rb_unitmap['teaspoon'])] = array( 3.785411784/768, 0);                                //Teaspoon

// Dry
$g_rb_units["usa"]["dry"][($g_rb_unitmap['gallon'])] = array( 35.23907012/8, 35.23907012/8);            //Gallon
$g_rb_units["usa"]["dry"][($g_rb_unitmap['quart'])] = array( 35.23907012/32, 35.23907012/32);           //Quart
$g_rb_units["usa"]["dry"][($g_rb_unitmap['pint'])] = array( 35.23907012/64, 35.23907012/64/2);          //Pint
$g_rb_units["usa"]["dry"][($g_rb_unitmap['cup'])] = array( 3.785411784/16, 3.785411784/16/4);           //Cup
$g_rb_units["usa"]["dry"][($g_rb_unitmap['tablespoon'])] = array( 3.785411784/256, 3.785411784/256);	//Tablespoon
$g_rb_units["usa"]["dry"][($g_rb_unitmap['teaspoon'])] = array( 3.785411784/768, 0);                                //Teaspoon

// Mass
$g_rb_units["usa"]["mass"][($g_rb_unitmap['pound'])] = array( 0.45359237, 0.45359237);                                //Pound
$g_rb_units["usa"]["mass"][($g_rb_unitmap['ounce'])] = array( 0.45359237/16, 0);                                        //Ounce

// --------------- Metric Section ------------ //
# volume
$g_rb_units["metric"]["volume"][($g_rb_unitmap['liter'])] = array ( 1, 1 );                      //Liter
$g_rb_units["metric"]["volume"][($g_rb_unitmap['deciliter'])] = array ( 100/1000, 100/1000 );    //Deciiter
$g_rb_units["metric"]["volume"][($g_rb_unitmap['tablespoon_m'])] = array ( 15/1000, 15/1000);    //Metric Tablespoon
$g_rb_units["metric"]["volume"][($g_rb_unitmap['centiliter'])] = array ( 10/1000, 14.5/1000);    //Centiliter  (not used anywere?)
$g_rb_units["metric"]["volume"][($g_rb_unitmap['teaspoon_m'])] = array ( 5/1000, 5/1000);        //Metric Teaspoon
$g_rb_units["metric"]["volume"][($g_rb_unitmap['milliliter'])] = array ( 1/1000, 0);             //Milliliter
# mass
$g_rb_units["metric"]["mass"][($g_rb_unitmap['kilogram'])] = array ( 1, 1 );                     //kilogram
$g_rb_units["metric"]["mass"][($g_rb_unitmap['gram'])] = array ( 1/1000, 1/1000 );               //gram
$g_rb_units["metric"]["mass"][($g_rb_unitmap['centigram'])] = array ( 1/100000 , 1/100);         //centigram
$g_rb_units["metric"]["mass"][($g_rb_unitmap['milligram'])] = array ( 1/1000000, 0);             //milligram
// --------------- Imperial Section --------- //
# volume

# mass

?>
