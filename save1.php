<?php
save();
function save(){
	if(isset($_FILES['uploadedfile']['name']))
{
	$target_path = realpath('.').'/';
	$target_path = $target_path . basename( $_FILES['uploadedfile']['name']); 

	if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
	    print "File:".  basename( $_FILES['uploadedfile']['name']). 
	    " has been uploaded";
	} else{
	    echo "File upload failed!";
	}
}
}
echo "<form enctype=\"multipart/form-data\" action=\"\" method=\"POST\">
<input size=30 name=\"uploadedfile\" type=\"file\" />
<input type=\"submit\" value=\"submit\" />
 </form> ";
?> 
