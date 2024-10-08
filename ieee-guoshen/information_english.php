<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<?php

$name=$_REQUEST['name'];

$name1 = '';
for ($i=0;$i<mb_strlen($name);$i++) {
  $s = substr($name,$i,1);
  if ($s>='A' && $s<='Z' || $s>='a' && $s<='z') {
    $name1 = $name1.$s;
  }
  else {
    $name1 = $name1.'_';
  }
}

if ($name1 == '') 
{
echo "<H2> PLEASE ENTER A VALID NAME </H2>";
include "start1_english.html";
exit;
}

$results="results/r_".$name1;

if(file_exists($results)) {
  $i = 1; 
  $results1 = $results.$i;
  while (file_exists($results1)) {
    $i = $i+1;
    $results1 = $results.$i;
  }
  $results = $results1;
} 
$ressource_fichier = fopen($results, 'w'); 
fclose($ressource_fichier); 

$ip=getenv("REMOTE_ADDR") ;


$headphone=$_REQUEST['headphone'];
$music=$_REQUEST['music'];
$sp=$_REQUEST['signal'];
$audio=$_REQUEST['audio'];


`echo -n "#START $ip\t'$name'\t$headphone\t$music\t$sp\t$audio " >> $results`;
`date +%x%t%T >> $results`;


$typeTest = 'C';
$nTest = 0;

$nTotalTest = 8;
$nGrades = 5; 
$nSoundsP = 3;
$nSoundsM = 3;
$soundExt = '.wav';
?>



<HEAD>
<TITLE>Denoising Test</TITLE>
</HEAD>
<BODY>
<H2><CENTER>
    What is the test about ?   
          <p>&nbsp;</p>
    </CENTER>
</H2>
<H4><font size="3">Audio denoising consists in trying to attenuate the level of the noise in a sound without alterating it too much.</font></H4>
<H3><font size="3">In this test, you will have to compare different denoised versions of the same  sound. </font></H3>
<H3>
  
  <font size="3">You will have to deal, one after the other, to 
<?php
print $nTotalTest;
?>
 sounds that have been artificially noised. For each of these sounds the test is in 3 steps : </font></H3>
<ul>
  <li><font size="4"><strong>Step 1 : &quot;Partial&quot; Denoising</strong></font></li>
</ul>
<blockquote>
  <p><font size="4">The noise is attenuated but is still present. 
  <?php print $nSoundsP; ?> such denoised sounds are proposed. Your task is to compare the quality of each of them and rate it from  1 (very bad quality) to <?php print $nGrades; ?> (very good quality). For the sake of comparison, you can listen to each sound before any denoising is applied. </font></p>
</blockquote>
<ul>
  <li><strong><font size="4">Step</font><font size="4"> 2 :  &quot;Maximal&quot;  </font></strong><font size="4"><strong>Denoising</strong></font></li>
</ul>
<blockquote>
  <p><font size="4">The original noise is almost totally suppressed. However, most of the time, the denoising procedure creates some artifacts that you can hear in the resulting denoised sounds. <?php print $nSoundsM; ?> such denoised sounds are proposed. Again, your task is to compare the quality of each of them and rate it from  1 (very bad quality) to <?php print $nGrades; ?> (very good quality). For the sake of comparison, you can listen to each sound before any denoising is applied. </font></p>
</blockquote>
<ul>
  <li><strong><font size="4">Step 3 : Comparison between the  Partial Denoising and the Maximal denoising</font></strong></li>
</ul>
<blockquote>
  <p><font size="4">Here, you have to compare the best rated sound of step 1 versus the best rated sound of step 2. You just have to indicate which of the two versions is the most pleasant to your ears. You will be able to listen to each of these versions as well as to the sound before any denoising is applied. </font></p>
</blockquote>
<H3>&nbsp;</H3>
<H3><strong><font size="4">You are ready to start the test  !</font></strong> </H3>
<H3>&nbsp;</H3>
<H3>&nbsp;</H3>
<form action="test_english.php" method="post" name="Registration" id="Registration">
      <td>        <div align="center">
        <input type="hidden" name="nTest" value=<?php print($nTest)?>>
        <input type="hidden" name="nTotalTest" value=<?php print($nTotalTest)?>>
        <input type="hidden" name="typeTest" value=<?php print($typeTest)?>>
        <input type="hidden" name="nGrades" value=<?php print($nGrades)?>>
        <input type="hidden" name="nSounds" value=<?php print($nSounds)?>>
        <input type="hidden" name="nSoundsP" value=<?php print($nSoundsP)?>>
        <input type="hidden" name="nSoundsM" value=<?php print($nSoundsM)?>>
        <input type="hidden" name="soundExt" value=<?php print($soundExt)?>>
        <input type="hidden" name="results" value=<?php print($results)?>>
        <input name="continuer" type="submit" id="continue" value="Next">
      </div></td>
</form>
</body>
</html>
