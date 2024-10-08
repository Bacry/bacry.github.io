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
echo "<H2> MERCI D'ENTRER UN NOM VALIDE </H2>";
include "start1_french.html";
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
<TITLE>Test de débruitage</TITLE>
</HEAD>
<BODY>
<H2><CENTER>
    <p><strong><font color="#006600" size="6" face="Arial, Helvetica, sans-serif">D&eacute;roulement du test </font> 
      </strong>    </p>
    <p>&nbsp;</p>
    </CENTER>
</H2>
<H4><font size="3">Le <em>d&eacute;bruitage audio</em> consiste &agrave;  essayer d'att&eacute;nuer le bruit (souffle) pr&eacute;sent dans un son sans trop le d&eacute;grader. </font></H4>
<H3><font size="3">Dans ce test d'&eacute;valuation, vous &ecirc;tes invit&eacute;s &agrave; comparer plusieurs versions d&eacute;bruit&eacute;es d'un m&ecirc;me son  bruit&eacute;. </font></H3>
<H3>
  
  <font size="3">
<?php
print $nTotalTest;
?>
 sons artificiellement bruit&eacute;s seront trait&eacute;s s&eacute;quentiellement. Pour chacun de ces sons le test se fait en 3 &eacute;tapes :</font></H3>
<ul>
  <li><font size="4"><strong>Etape 1 : D&eacute;bruitage &quot;Partiel&quot;</strong></font></li>
</ul>
<blockquote>
  <p><font size="4">Le souffle est att&eacute;nu&eacute; mais partiellement conserv&eacute;. 
  <?php print $nSoundsP; ?> sons d&eacute;bruit&eacute;s sont propos&eacute;s. Vous devez comparer la qualit&eacute; de chacun suivant une &eacute;chelle de 1 (tr&egrave;s mauvaise qualit&eacute;) &agrave; <?php print $nGrades; ?> (tr&egrave;s bonne qualit&eacute;). A titre comparatif, vous pouvez &eacute;couter le son avant d&eacute;bruitage. </font></p>
</blockquote>
<ul>
  <li><strong><font size="4">Etape 2 : D&eacute;bruitage &quot;Maximal&quot;  </font></strong></li>
</ul>
<blockquote>
  <p><font size="4">Le souffle est pratiquement supprim&eacute;. Par contre les artefacts sonores engendr&eacute;s par le d&eacute;bruitage sont souvent audibles. <?php print $nSoundsM; ?>  sons d&eacute;bruit&eacute;s sont propos&eacute;s. A nouveau, vous devez comparer la qualit&eacute; de chacun suivant une &eacute;chelle de 1 (tr&egrave;s mauvaise qualit&eacute;) &agrave; <?php print $nGrades; ?> (tr&egrave;s bonne qualit&eacute;). A titre comparatif, vous pouvez &eacute;couter le son avant d&eacute;bruitage. </font></p>
</blockquote>
<ul>
  <li><strong><font size="4">Etape 3 : Comparaison entre les 2 premiers d&eacute;bruitage </font></strong></li>
</ul>
<blockquote>
  <p><font size="4">Dans ce test, vous &ecirc;tes invit&eacute;s &agrave; comparer le son le mieux not&eacute; &agrave; l'&eacute;tape 1 avec le son le mieux not&eacute; de l'&eacute;tape 2. Un simple vote pour l'une des deux versions vous est demand&eacute; (lequel des 2 sons est le plus plaisant &agrave; l'oreille?) . Vous pourrez r&eacute;&eacute;couter chacune des versions ainsi que le son avant d&eacute;bruitage. </font></p>
</blockquote>
<H3>&nbsp;</H3>
<H3><strong><font size="4">Le test peut commencer !</font></strong> </H3>
<H3>&nbsp;</H3>
<H3>&nbsp;</H3>
<form action="test_french.php" method="post" name="Registration" id="Registration">
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
        <input name="continuer" type="submit" id="continue" value="Continuer">
      </div></td>
</form>
</body>
</html>
