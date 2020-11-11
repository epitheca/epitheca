<!--
Copyright Mathieu MONCOMBLE (contact@epitheca.fr) 2009-2020

This file is part of epitheca.

    epitheca is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License.

    epitheca is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with epitheca.  If not, see <https://www.gnu.org/licenses/>.
-->

<?php
?>
<HEAD>
<meta http-equiv="Content-Type" charset="utf-8" > 
<link rel='stylesheet' HREF='Css_iframe_fichier.css' TYPE='text/css'>
<link rel='stylesheet' HREF='Css_fenetre_nodal.css' TYPE='text/css'>
</HEAD>
<?php
//session_start ();
require("Util.php");

echo "<div style='height:40px;width:940px'>";
$target_dir = "temporaire/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$type = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
	
	if ($type=="png" || $type=="jpg" || $type=="jpeg")
	{
		$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
		if($check !== false) {
			$uploadOk = 1;
		} else {
			$uploadOk = 0;
		}
	}

	if($type=='audio/mp3')
     {
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
		if($check !== false) {
			$uploadOk = 1;
		} else {
			$uploadOk = 0;
         }
}}


// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
?>
			<script type="text/javascript">
			<!--
			window.alert("<?php echo "Le fichier est trop volumineux." ?>");
			window.location.replace("Ajout_fichier.html");
			//-->
			</script>    
			<?php    $uploadOk = 0;
}

// Allow certain file formats
if($type != "jpg" && $type != "png" && $type != "jpeg"
&& $type != "gif" && $type != "mp3" && $type != "JPG" && $type != "PNG" && $type != "JPEG"
&& $type != "GIF" && $type != "MP3") {
			?>
			<script type="text/javascript">
			<!--
			window.alert("<?php echo "Le fichier doit être au format mp3, gif, png, jpg ou jpeg" ?>");
			window.location.replace("Ajout_fichier.html");
			//-->
			</script>    
			<?php    $uploadOk = 0;
}


// Check if $uploadOk is set to 0 by an error
if ($uploadOk <> 0) {
	
	//Récupération d'un time stamp
	$timestamp= time ();
	
	    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "<br>Le fichier ". basename( $_FILES["fileToUpload"]["name"]). " a bien été ajouté.";
        
        //On renomme le fichier
        $anciennom =$target_dir;
        $anciennom .=basename( $_FILES["fileToUpload"]["name"]);
        $nouveaunom = "televersements/$timestamp.$type";
		rename ($anciennom, $nouveaunom);
	       
   ?>
   <!--On envoie le nom du fifhier dans le champ par javascript-->
   <script type="text/javascript">
	parent.window.document.getElementById("fichier_joint").value = "<?php echo "$timestamp.$type"; ?>";
	</script>
   
   <?php
    } else {
		?>
        <script type="text/javascript">
			<!--
			window.alert("<?php echo "Le fichier n a pu être transféré" ?>");
			window.location.replace("Ajout_fichier.html");
			//-->
			</script>
		<?php
    }
}
echo "</div>";
?>
