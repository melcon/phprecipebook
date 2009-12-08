<SCRIPT language="javascript">
<!--
var newwindow;

function submitIt() {
	var form = document.recipe_form;
	
	if (form.recipe_name.value.length < 3) {
		alert( "<?php echo $LangUI->_('Please enter a recipe name');?>" );
		form.recipe_name.focus();
	} else if (isNaN(form.recipe_serving_size.value)) {
		alert("<?php echo $LangUI->_('Please enter a valid Serving Size');?>");
		form.recipe_serving_size.focus();
	} else {
			// submit
			form.dosql.value = "update";
			form.submit();
			return true;
	}
}

function newPopupWindow(url)
{
	newwindow=window.open(url,'name','height=200,width=860');
	if (window.focus) {newwindow.focus()}
}

// called by onchange on the input box converts on the fly any faction's and reject's non numbers
function fractionConvert(id)
{
        var teststring = id.value;
        var a=teststring.indexOf(",")      // change "," to "." (in all languages)
        if ( a != -1 ) {                   //FIXME: bug - still displays "." for all languages
                id.value=teststring=teststring.substring(0,a)+"."+teststring.substring(a+1,teststring.length)
        }
        if (isNaN(teststring))
        {
			if (teststring.indexOf("/")>0)
                {
                        if (teststring.indexOf(" ")>0)
                        {
                                n = teststring.substring(0,teststring.indexOf(" ")+1);
                                f = teststring.substring(teststring.indexOf(" ")+1);
                        }else{
                                n = teststring.substring(0,teststring.indexOf("/")-1);
                                f = teststring.substring(teststring.indexOf("/")-1);
                        }//if(teststring.indexOf(" "))
                        if (isNaN(n)){alert("<?php echo $LangUI->_('Please enter Numbers');?>");return;}//Make shure we have a number
                        var newArray = f.split("/");
                        if (isNaN(newArray[0])){alert("<?php echo $LangUI->_('Please enter Numbers');?>");return;}//Make shure we have a number
                        if (isNaN(newArray[1])){alert("<?php echo $LangUI->_('Please enter Numbers');?>");return;}
                        id.value = eval((n*1)+(newArray[0]/newArray[1]));//write the new value to the calling box
                } else {
                        alert("<?php echo $LangUI->_('Please enter Numbers');?>")
                }
        }
}
// -->
</script>
