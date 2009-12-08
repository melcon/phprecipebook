<?php
        $conv_mass = array(   // kilograms
                $LangUI->_('___METRIC etc.______')=>NULL,
                $LangUI->_('gram(s) [g]')=>.001,
                $LangUI->_('decagram(s)[dg]')=>.01,
                $LangUI->_('hectogram(s) [hg]')=>.1,
                $LangUI->_('kilogram(s) [kg]')=>1.0,
                $LangUI->_('livre (France)')=>.5,
                $LangUI->_('pfund (Germany)')=>.5,
                $LangUI->_('___USA and IMPERIAL_')=>NULL,
                $LangUI->_('ounce(s) (avoirdupois) [oz]')=>0.45359237/16,
                $LangUI->_('pound(s) (avoirdupois) [lb]')=>0.45359237,
                $LangUI->_('quarter(s) (US)')=>1.27

                //$LangUI->_('ounces (troy) [oz]')=>0.3732417/12, (not used since standardization
                //$LangUI->_('pounds (troy) [lb]')=>0.3732417      around 1959)
        );
        $conv_volume = array(  //litres
                $LangUI->_('___METRIC etc.______')=>NULL,
                $LangUI->_('millilitre(s) [ml]')=>1/1000,
                $LangUI->_('cubic centimeter(s) [cc]')=>1/1000,
                $LangUI->_('spice measure(s) (Sweden)')=>1/1000,
                $LangUI->_('teaspoon(s) [tsp]')=>5/1000,
                $LangUI->_('centilitre(s) [cl]')=>10/1000,
                $LangUI->_('tablespoon(s) [tbsp]')=>15/1000,
                $LangUI->_('tablespoon(s) [tbsp] (Australia)')=>20/1000,
                $LangUI->_('decilitre(s) [dl]')=>100/1000,
                $LangUI->_('cup(s) (Japan)')=>195/1000, //Approximate
                $LangUI->_('cup(s) (Australia)')=>250/1000,
                $LangUI->_('glass(es) (Poland)')=>250/1000,
                $LangUI->_('litre(s) [l]')=>1,

                $LangUI->_('___USA_____________')=>NULL,
                $LangUI->_('liquid minim(s)')=>3.785411784/61440,
                $LangUI->_('liquid dram(s)')=>3.785411784/1024,
                $LangUI->_('teaspoon(s) (US) [tsp]')=>3.785411784/768,
                $LangUI->_('tablespoon(s) (US) [tbsp]')=>3.785411784/256,
                $LangUI->_('fluid ounce(s) [fl oz]')=>3.785411784/128,
                $LangUI->_('liquid gill(s)')=>3.785411784/32,
                $LangUI->_('cup(s) (US)')=>3.785411784/16,
                $LangUI->_('liquid pint(s) [lq pt]')=>3.785411784/8,
                // $LangUI->_('pounds (of water)')=>3.785411784/8/1.043, this isn't a real unit is it???
                $LangUI->_('liquid quart(s)')=>3.785411784/4,
                $LangUI->_('liquid gallon(s) [gal]')=>3.785411784,
                $LangUI->_('dry pint(s) [dry pt]')=>35.23907012/64,
                $LangUI->_('dry quart(s)')=>35.23907012/32,
                $LangUI->_('dry gallon(s) [dry gal]')=>35.23907012/8,
                $LangUI->_('bushel(s)')=>35.23907012,
                
                $LangUI->_('___IMPERIAL_________')=>NULL,
              //  $LangUI->_('cubic inches')=>1/61023.7441,
                $LangUI->_('teaspoon(s) (same as metric) [tsp]')=>5/1000,
                $LangUI->_('dessertspoon(s) [dsp]')=>10/1000,
                $LangUI->_('tablespoon(s) (same as metric) [tbsp]')=>15/1000,
                $LangUI->_('fluid ounce(s) [fl oz]')=>4.54609/160,
                $LangUI->_('gill(s)')=>4.54609/32,
                $LangUI->_('cup(s) (Imperial)')=>4.54609/16,
                $LangUI->_('pint(s) [pt]')=>4.54609/8,
                $LangUI->_('quart(s)')=>4.54609/4,
                $LangUI->_('gallon(s) [gal]')=>4.54609,
        );

        $conv_temperature = array(
                $LangUI->_('Celsius')=>"0",
                $LangUI->_('Fahrenheit')=>"1",
        );
        
        $conv_density = array(  //  kilograms/litres (Values from "rec.food.cooking FAQ and conversion file")
                                // a better solution would be to store it in the db and pull the values from there
//                $LangUI->_('water')=>1.00,  special case to make it selected by default see below
                $LangUI->_('allspice')=>0.42,
                $LangUI->_('almonds, ground')=>0.36,
                $LangUI->_('almonds, whole')=>0.72,
                $LangUI->_('anchovies')=>1.02,
                $LangUI->_('apples, dried')=>0.38,
                $LangUI->_('apples, sliced')=>0.76,
                $LangUI->_('apricots, dried')=>0.64,
                $LangUI->_('arrowroot')=>0.95,
                $LangUI->_('bacon fat')=>0.76,
                $LangUI->_('baking powder')=>0.76,
                $LangUI->_('baking soda')=>0.87,
                $LangUI->_('bamboo shoots')=>1.14,
                $LangUI->_('bananas, mashed')=>0.97,
                $LangUI->_('bananas, sliced')=>0.76,
                $LangUI->_('barley, uncooked')=>0.78,
                $LangUI->_('basil, dried')=>0.11,
                $LangUI->_('beans, dried')=>0.85,
                $LangUI->_('beef, cooked')=>0.97,
                $LangUI->_('beef, raw')=>0.93,
                $LangUI->_('biscuit mix (Bisquick)')=>0.55,
                $LangUI->_('blue corn meal')=>0.51,
                $LangUI->_('bran, unsifted')=>0.23,
                $LangUI->_('brazil nuts, whole')=>0.64,
                $LangUI->_('bread crumbs, fresh')=>0.25,
                $LangUI->_('bread crumbs, packaged')=>0.51,
                $LangUI->_('buckwheat groats')=>0.72,
                $LangUI->_('butter')=>0.97,
                $LangUI->_('cabbage, shredded')=>1.44,
                $LangUI->_('cake crumbs, fresh')=>0.38,
                $LangUI->_('candied lemon peel')=>0.57,
                $LangUI->_('candied orange peel')=>0.53,
                $LangUI->_('cashews, oil roasted')=>0.47,
                $LangUI->_('cauliflower fleurets')=>0.97,
                $LangUI->_('celery seed')=>0.51,
                $LangUI->_('cereal, Rice Krispies')=>0.09,
                $LangUI->_('cheese, cheddar, grated')=>0.51,
                $LangUI->_('cheese, colby, grated')=>0.47,
                $LangUI->_('cheese, cottage')=>0.97,
                $LangUI->_('cheese, cream')=>1.02,
                $LangUI->_('cheese, grated parmesan')=>0.76,
                $LangUI->_('cheese, jack, grated')=>0.55,
                $LangUI->_('chives, chopped dried')=>0.03,
                $LangUI->_('chives, chopped fresh')=>0.21,
                $LangUI->_('chocolate chips')=>0.76,
                $LangUI->_('chocolate, cocoa powder')=>0.47,
                $LangUI->_('chocolate, grated')=>0.42,
                $LangUI->_('chocolate, melted')=>1.02,
                $LangUI->_('cinnamon, ground')=>0.51,
                $LangUI->_('cloves, ground')=>0.40,
                $LangUI->_('cloves, whole')=>0.38,
                $LangUI->_('coconut, shredded')=>0.32,
                $LangUI->_('coffee, ground')=>0.38,
                $LangUI->_('coffee, instant')=>0.23,
                $LangUI->_('cornmeal')=>0.72,
                $LangUI->_('cornstarch (cornflour)')=>0.64,
                $LangUI->_('cracker crumbs')=>0.25,
                $LangUI->_('cranberries')=>0.42,
                $LangUI->_('cream of tartar')=>0.64,
                $LangUI->_('cream of wheat')=>0.76,
                $LangUI->_('crisco, melted')=>0.89,
                $LangUI->_('crisco, solid')=>0.93,
                $LangUI->_('currants')=>0.64,
                $LangUI->_('dates, chopped')=>0.64,
                $LangUI->_('egg noodles')=>0.38,
                $LangUI->_('egg whites')=>0.93,
                $LangUI->_('egg yolks')=>1.14,
                $LangUI->_('eggs, beaten')=>0.97,
                $LangUI->_('evaporated milk')=>0.93,
                $LangUI->_('farina')=>0.76,
                $LangUI->_('figs, dried')=>0.70,
                $LangUI->_('flour, Deaf Smith')=>0.55,
                $LangUI->_('flour, U.K. self-raising')=>0.47,
                $LangUI->_('flour, U.S. all-purpose')=>0.42,
                $LangUI->_('flour, buckwheat')=>0.72,
                $LangUI->_('flour, cake')=>0.38,
                $LangUI->_('flour, legume')=>0.55,
                $LangUI->_('flour, potato')=>0.72,
                $LangUI->_('flour, rice')=>0.64,
                $LangUI->_('flour, rye')=>0.38,
                $LangUI->_('flour, semolina')=>0.74,
                $LangUI->_('flour, wheat bread')=>0.42,
                $LangUI->_('flour, whole wheat')=>0.55,
                $LangUI->_('fungus, wood ear')=>0.42,
                $LangUI->_('garlic')=>0.68,
                $LangUI->_('garlic, minced')=>0.64,
                $LangUI->_('gelatin')=>0.93,
                $LangUI->_('ginger, crystal')=>1.02,
                $LangUI->_('ginger, fresh')=>0.97,
                $LangUI->_('ginger, ground')=>0.51,
                $LangUI->_('graham cracker crumbs')=>0.38,
                $LangUI->_('grape nuts')=>0.51,
                $LangUI->_('gumdrops')=>0.68,
                $LangUI->_('gummi bears')=>0.64,
                $LangUI->_('hazelnuts, whole')=>0.72,
                $LangUI->_('honey')=>1.44,
                $LangUI->_('kasha')=>0.72,
                $LangUI->_('lard')=>0.93,
                $LangUI->_('lemon rind, grated')=>0.64,
                $LangUI->_('lentils')=>0.85,
                $LangUI->_('macaroni, uncooked')=>0.49,
                $LangUI->_('margarine')=>0.93,
                $LangUI->_('marshmallows, small')=>0.21,
                $LangUI->_('mashed potatoes')=>0.89,
                $LangUI->_('mayonnaise')=>0.93,
                $LangUI->_('milk, evaporated')=>0.93,
                $LangUI->_('milk, powdered')=>0.49,
                $LangUI->_('molasses')=>1.48,
                $LangUI->_('mushrooms, Chinese black')=>0.21,
                $LangUI->_('mushrooms, chopped')=>0.32,
                $LangUI->_('mushrooms, sliced')=>0.28,
                $LangUI->_('mushrooms, whole')=>0.25,
                $LangUI->_('mustard seed')=>0.64,
                $LangUI->_('mustard, dry')=>0.49,
                $LangUI->_('mustard, prepared')=>1.06,
                $LangUI->_('oatmeal, uncooked')=>0.34,
                $LangUI->_('oats, rolled')=>0.34,
                $LangUI->_('oats, steel-cut')=>0.68,
                $LangUI->_('oil, vegetable')=>0.89,
                $LangUI->_('olive oil')=>0.81,
                $LangUI->_('olives, chopped')=>0.76,
                $LangUI->_('onion, chopped')=>0.64,
                $LangUI->_('onion, minced')=>0.85,
                $LangUI->_('onion, sliced')=>0.55,
                $LangUI->_('orange rind, grated')=>0.38,
                $LangUI->_('oreo cookies, crushed')=>0.51,
                $LangUI->_('paprika')=>0.49,
                $LangUI->_('parsley, fresh')=>0.17,
                $LangUI->_('pasta, egg noodles')=>0.38,
                $LangUI->_('pasta, macaroni')=>0.49,
                $LangUI->_('peanut butter')=>0.76,
                $LangUI->_('peanuts, chopped')=>0.68,
                $LangUI->_('peanuts, oil roasted')=>0.64,
                $LangUI->_('peas, uncooked')=>0.64,
                $LangUI->_('pecans, chopped')=>0.51,
                $LangUI->_('pecans, ground')=>0.42,
                $LangUI->_('pecans, shelled')=>0.51,
                $LangUI->_('peppercorns, black')=>0.57,
                $LangUI->_('peppercorns, white')=>0.64,
                $LangUI->_('peppers, chopped chili')=>0.72,
                $LangUI->_('pignolias (pine nuts)')=>0.53,
                $LangUI->_('poppy seeds')=>0.57,
                $LangUI->_('potatoes, cooked diced')=>0.85,
                $LangUI->_('potatoes, mashed')=>0.89,
                $LangUI->_('potatoes, sliced raw')=>0.76,
                $LangUI->_('pumpkin, cooked')=>0.76,
                $LangUI->_('raisins')=>0.64,
                $LangUI->_('rice, steamed')=>0.68,
                $LangUI->_('rice, uncooked')=>0.89,
                $LangUI->_('rice, uncooked Basmati')=>0.83,
                $LangUI->_('rice, wild')=>0.61,
                $LangUI->_('salt')=>1.02,
                $LangUI->_('scallions (green onions)')=>0.21,
                $LangUI->_('sesame seeds')=>0.68,
                $LangUI->_('shallots')=>1.02,
                $LangUI->_('sour cream')=>0.51,
                $LangUI->_('spaghetti, uncooked')=>0.51,
                $LangUI->_('spinach, cooked')=>0.76,
                $LangUI->_('split peas')=>0.85,
                $LangUI->_('strawberries')=>0.64,
                $LangUI->_('sugar, brown')=>0.85,
                $LangUI->_('sugar, castor')=>0.81,
                $LangUI->_('sugar, confectioner\'s')=>0.55,
                $LangUI->_('sugar, granulated')=>0.81,
                $LangUI->_('sugar, powdered')=>0.55,
                $LangUI->_('sultanas')=>0.64,
                $LangUI->_('sweet potatoes, cooked')=>1.02,
                $LangUI->_('sweet potatoes, raw')=>0.76,
                $LangUI->_('syrup, corn')=>1.48,
                $LangUI->_('tea')=>0.32,
                $LangUI->_('tiger lily blossoms')=>0.17,
                $LangUI->_('tomatoes, chopped')=>0.68,
                $LangUI->_('tuna, canned')=>0.85,
                $LangUI->_('turmeric, ground')=>0.59,
                $LangUI->_('vanilla wafers, crushed')=>0.68,
                $LangUI->_('walnuts, chopped')=>0.49,
                $LangUI->_('walnuts, ground')=>0.36,
                $LangUI->_('walnuts, shelled')=>0.51,
                $LangUI->_('wheat germ')=>0.53,
                $LangUI->_('wild rice')=>0.61,
                $LangUI->_('yeast, active dry')=>1.23
        );


        $type = isset($_GET['type'])?$_GET['type']:'mass';
        if( $type == 'mass' ) {
                $strConvertTitle = $LangUI->_('Convert units of mass/weight');
                $conv1 = $conv2 = $conv_mass;
        }
        if( $type == 'volume' ) {
                $strConvertTitle =  $LangUI->_('Convert units of volume');
                $conv1 = $conv2 = $conv_volume;
        }
        if( $type == 'volume2mass' ) {
                $strConvertTitle =  $LangUI->_('Convert from volume to mass/weight');
                $conv1 = $conv_volume;
                $conv2 = $conv_mass;
        }
        if( $type == 'mass2volume' ) {
                $strConvertTitle =  $LangUI->_('Convert from mass/weight to volume');
                $conv1 = $conv_mass;
                $conv2 = $conv_volume;
        }
        if( $type == 'temperature' ) {
                $strConvertTitle =  $LangUI->_('Convert between Celcius and Fahrenheit degrees');
                $conv1 = $conv_temperature;
        }
?>
<style type="text/css" title="">
TABLE.convert {
        border: solid 2px #006699;
}
table.convert td,th { 
        font-family: verdana,helvetica,arial,sans-serif;
        font-size: 13px;
        background-color: #d6e6f5;
}
table.convert input { font-family: verdana,helvetica,arial,sans-serif; }
table.convert select { 
        font-family: verdana,helvetica,arial,sans-serif;
        font-size: 11px;
}
</style>
<script language="javascript">
<!--
        function doConvert(type) {
                var f=document.frmConvert;

                if( type != "temperature" && (f.unit_from.selectedIndex < 0 || f.unit_to.selectedIndex < 0)   )
                        return;
                        
                var unitFrom=parseFloat(f.unit_from.options[f.unit_from.selectedIndex].value);
                var valueFrom = parseFloat(f.value_from.value);

                if( isNaN(valueFrom) ) {
                        alert("<?php echo  $LangUI->_('The value of \'from\' is invalid.')?>");
                        return;
                }
                if( isNaN(unitFrom) ) {
                        alert("<?php echo  $LangUI->_('Choose a unit in the \'From\' field.')?>");
                        return;
                }
                switch (type) {
                        case "mass":
                        case "volume":
                                var unitTo=parseFloat(f.unit_to.options[f.unit_to.selectedIndex].value);
                                if( isNaN(unitTo) ) {
                                        alert("<?php echo  $LangUI->_('Choose a unit in the \'To\' field.')?>");
                                        return;
                                }
                                f.value_to.value = Math.round(valueFrom*unitFrom/unitTo*10000)/10000;
                        break;
                                
                        case "volume2mass":
                        case "mass2volume":
                                var unitTo=parseFloat(f.unit_to.options[f.unit_to.selectedIndex].value);
                                var unitDensity=parseFloat(f.unit_density.options[f.unit_density.selectedIndex].value);
                                if( isNaN(unitTo) ) {
                                        alert("<?php echo  $LangUI->_('Choose a unit in the \'To\' field.')?>");
                                        return;
                                }
                                f.value_to.value = Math.round(valueFrom*unitFrom*unitDensity/unitTo*10000)/10000;
                        break;
                        
                        case "temperature":
                                if( unitFrom == "1") {
                                        f.value_to.value = Math.round((valueFrom-32)*5/9) + "° C";
                                } else {
                                        f.value_to.value = Math.round((valueFrom*9/5+32)) + "° F";
                                }
                        break;
                        
                        default:
                                alert ("Internal: Error in argument (converter.php)");
                        break;
                }
        }
        
//-->
</script>
<h2><?php echo $strConvertTitle; ?></h2>
<p><a href="<?php echo $PHP_SELF ?>?m=utils&a=converter&type=mass"><?php echo $LangUI->_('Mass');?></a> |
        <a href="<?php echo $PHP_SELF ?>?m=utils&a=converter&type=volume"><?php echo $LangUI->_('Volume');?></a> |
        <a href="<?php echo $PHP_SELF ?>?m=utils&a=converter&type=volume2mass"><?php echo $LangUI->_('Volume to mass');?></a> |
        <a href="<?php echo $PHP_SELF ?>?m=utils&a=converter&type=mass2volume"><?php echo $LangUI->_('Mass to volume');?></a> |
        <a href="<?php echo $PHP_SELF ?>?m=utils&a=converter&type=temperature"><?php echo $LangUI->_('Temperature');?></a>
</p>
<table cellpadding="5" cellspacing="0" border="0" class="convert">
<form name="frmConvert" method="post" action="./index.php?m=utils&a=converter">
        <tr>
                <th>
                        <?php echo $LangUI->_('From');?>
                </th>
                <th>
                        &nbsp;
                </th>
                <th>
                        <?php echo $LangUI->_('To');?>
                </th>
        </tr>
        <tr>
                <td>
                        <input type="text" name="value_from" value="1" onKeyUp=doConvert("<?php echo $type ?>")>
                </td>
                <td>
                         &nbsp;
                </td>
                <td>
                        <input type="text" name="value_to" readonly value="1">
                </td>
        </tr>
        <tr>
                <td>
                        <select name="unit_from" size=15 onChange=doConvert("<?php echo $type ?>")>
<?php
                        while (list ($key, $val) = each ($conv1)) {
?>
                                <option value="<?php echo $val; ?>"><?php echo $key; ?></option>
<?php
        }
?>
                        </select>
                </td>
                <td>
<?php
                        if ( $type == "volume2mass" || $type == "mass2volume" ) {
?>
                                <select name="unit_density" size=15 onChange=doConvert("<?php echo $type ?>")>
                                <option selected value=1> <?php echo $LangUI->_('water');?></option>
<?php
                                while (list ($key, $val) = each ($conv_density)) {
?>
                                        <option value="<?php if( $type == 'mass2volume') echo 1/$val; else echo $val; ?>"><?php echo $key; ?></option>
<?php
                                }

                        }
?>
                                </select>
                </td>
                <td>
<?php
                        if( $type != "temperature" ) {
?>
                                <select name="unit_to" size=15 onChange=doConvert("<?php echo $type ?>")>
<?php
                                reset($conv1);

                                while (list ($key, $val) = each ($conv2)) {
?>
                                        <option value="<?php echo $val; ?>"><?php echo $key; ?></option>
<?php
                                }
?>
                                </select>
<?php
                        }
?>
                </td>
        </tr>
</form>
</table>
