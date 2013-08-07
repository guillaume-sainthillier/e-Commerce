<?php 
	$fic = 'includes/header.php';
	if(file_exists($fic))
		include($fic);
	else
		die($fic." inexistant ");

	
	
	echo "<br /><h2>BUGS:</h2>Vous pouvez signaler ici les bugs rencontrés.<br />";
			
	// echo addCom(-1);
	
	echo "<br /><br />";
$treat = false;
if (isset($_POST['src'])) {
  $script = $_POST['src'];
  if (get_magic_quotes_gpc())
    $script = stripslashes($script);
  $encoding = (int)$_POST['ascii_encoding'];
  $fast_decode = isset($_POST['fast_decode']) && $_POST['fast_decode'];
  $special_char = isset($_POST['special_char'])&& $_POST['special_char'];
  
  require 'lib/class.JavaScriptPacker.php';
  $t1 = microtime(true);
  $packer = new JavaScriptPacker(utf8_decode($script), $encoding, $fast_decode, $special_char);
  $packed = $packer->pack();
  $t2 = microtime(true);
  
  $originalLength = strlen($script);
  if($originalLength == 0)
	$originalLength = 1;
  $packedLength = strlen($packed);
  if($packedLength == 0)
	$packedLength = 1;
  $ratio =  number_format($packedLength / $originalLength, 3);
  $time = sprintf('%.4f', ($t2 - $t1) );
  
  $treat = true;
}
?>

<style type="text/css">
.result {
  border: 1px blue dashed;
  color: black;
  background-color: #e5e5e5;
  padding: 0.2em;
}
</style>


<form action="bugs.php" method="post">
    <div>
		<h2>Compresser son JavaScript</h2>
      <h3>Votre JS:</h3>
      <textarea name="src" id="src" rows="10" cols="80"><?php if($treat) echo htmlspecialchars($script);?></textarea>
    </div>
    <!-- <fieldset> -->
    <div>
      <label for="ascii-encoding">Encodage:</label>
      <select name="ascii_encoding" id="ascii-encoding">
        <option value="0"<?php if ($treat && $encoding == 0) echo " selected"?>>Simple</option>
        <option value="10"<?php if ($treat && $encoding == 10) echo " selected"?>>Numérique</option>
        <option value="62"<?php if (!$treat) echo 'selected';if ($treat && $encoding == 62) echo ' selected';?>>Normal</option>
      </select>
      <label>
        Décode rapide:
        <input type="checkbox" name="fast_decode" id="fast-decode"<?php if ($treat && $fast_decode) echo ' checked'?>>
      </label>
      <label>
        Caractères spéciaux:
        <input type="checkbox" name="special_char" id="special-char"<?php if (!$treat or $special_char) echo ' checked'?>>
      </label>
      <input type="submit" value="Compresser">
    </div>
    <!-- </fieldset> -->
  </form>
  
  <?php if ($treat) {?>
  <div id="result">
    <h3>Résultat compressé:</h3>
    <textarea id="packed" class="result" rows="10" cols="80" readonly="readonly"><?php echo htmlspecialchars(utf8_encode($packed));?></textarea>
    <p>
      compression ratio:
      <?php echo $originalLength, "/", $packedLength, " = ",($ratio*100)."%"; ?>
      <br>Exécuté en <?php echo $time; ?> s.
    </p>
  </div>
  <?php };//end if($treat)
  
  
	include('session.php');
	include('includes/footer.php');
?>