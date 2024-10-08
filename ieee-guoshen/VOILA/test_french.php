<?php
  ob_start();
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">



<?php
$nTest=$_REQUEST['nTest'];
$nTotalTest=$_REQUEST['nTotalTest'];
$typeTest=$_REQUEST['typeTest'];
$nGrades=$_REQUEST['nGrades'];
$results=$_REQUEST['results'];
$nSoundsP=$_REQUEST['nSoundsP'];
$nSoundsM=$_REQUEST['nSoundsM'];
$soundExt=$_REQUEST['soundExt'];

if ($nTest != 0) {
  `echo "$nTest\t$typeTest" >> $results`;
  if ($typeTest != 'C') {
    $nSounds=$_REQUEST['nSounds'];
    $bestSound = 0;
    $bestGrade = 0;
    for ($j=1;$j<=$nSounds;$j++) {
      $grade=$_REQUEST['note'.$j];
	  if ($grade > $bestGrade) {$bestSound = $j; $bestGrade = $grade;}
      `echo -n "$grade\t" >> $results`;
    }
    `echo "" >> $results`;
	if ($typeTest == 'M') {
	  $bestM = $bestSound;
      $bestP=$_REQUEST['bestP'];
	}
	else {
	  $bestP = $bestSound;
	  $betM = 0;
 	}
  }
  else {
  $grade=$_REQUEST['note'];
  `echo "$grade" >> $results`;
  }
}
else {
  $bestM = 0;
  $bestP = 0;
}


$stop = 0;
if ($typeTest == 'P') {
  $typeTest = 'M';
 }
else if ($typeTest == 'M') {
  $typeTest = 'C';
 }
else if ($typeTest == 'C') {
  if ($nTest == $nTotalTest) {
    $stop = 1;
  } 
  else {
    $typeTest = 'P';
    $nTest = $nTest+1;
  }
}
?>
	

<HEAD>

<TITLE>
<?php 
if ($stop == 0) {print('Débruitage son '.$nTest.'/'.$nTotalTest);} else {print 'Fin de session';} 
?>
</TITLE>
</HEAD>

<BODY>
<H2><CENTER>
<p><strong><font color="#006600" size="6" face="Arial, Helvetica, sans-serif">

<?php
if ($stop ==1) {
print 'Fin de session : merci pour votre collaboration!';
`echo -n "*END " >> $results`;
`date +%x%t%T >> $results`;
exit;
} 
print('Son '.$nTest.'/'.$nTotalTest);
if ($typeTest=='M') 
{
$tm = 'Maximal';
$nSounds = $nSoundsM;
}
else if ($typeTest == 'P')
{ 
$tm = 'Partiel';
$nSounds = $nSoundsP;
}
else
{ 
$nSounds = 0;
}
?>	

</font> 
</strong></p>
</CENTER>
</H2>

<?php
if ($typeTest == 'P') {
  print '<H2> <strong> Etape 1 : Débruitage "Partiel" </strong> </H2>';
  print '<H3> Le souffle est atténué mais partiellement conservé. '.$nSoundsP.' sons débruités sont proposés ainsi que le son avant débruitage.';
  print "<p></p>";
    print "Merci de noter la qualité de chacun des sons suivant l'échelle de 1 (très mauvaise qualité) à ".$nGrades." (très bonne qualité).</H3>";
 }
 else if ($typeTest == 'M') {
  print '<H2> <strong> Etape 2 : Débruitage "Maximal" </strong> </H2>';
  print '<H3> Le souffle est pratiquement supprim&eacute;. Par contre les artefacts sonores engendr&eacute;s par le d&eacute;bruitage sont souvent audibles. '.$nSoundsM.' sons d&eacute;bruit&eacute;s sont propos&eacute;s ainsi que le son avant débruitage.';
  print "<p></p>";
   print "Merci de noter la qualité de chacun des sons suivant l'échelle de 1 (très mauvaise qualité) à ".$nGrades." (très bonne qualité).</H3>";
 }
 else {
  print '<H2> <strong> Etape 3 : Comparaison débruitages Partiel/Maximal  </strong> </H2>';
  print "<H3> Dans ce test, vous &ecirc;tes invit&eacute;s &agrave; comparer le débruitage maximal que vous avez le mieux noté (en l'occurence la version ".$bestM.") avec le débruitage partiel que vous avez le mieux noté (en l'occurence la version ".$bestP.").";
  print "<p></p>";
  print "Merci de selectionner la version qui vous semble la plus agréable à l'oreille. </H3>";
 }
 ?>  

<H3>
  <form action="test_french.php" method="post" name="Registration" id="Registration">
  <table width="579" border="0" align="center">

    <tr> 
    <td width="384">&nbsp;</td>
    <td width="185"><div align="center"></div></td>
	<?php
if ($typeTest != 'C') {
    for ($i=1;$i<=$nGrades;$i++) 
	{
      print '<td width="37"><div align="center">'.$i.'</div></td>';
	}
}
	?>
    </tr>

    <tr> 
    <td height="36"><strong>Son avant d&eacute;bruitage : </strong></td>
    <?php
	 $file = 'sound.'.$nTest.$soundExt;
	 print '<td><div align="center">';
	 print '<APPLET CODE="PlaySoundApplet" WIDTH="90" HEIGHT="36">'; 
     print '<PARAM name="filename" value="'.$file.'">';
     print '</APPLET>';
	 print '</div></td>';
     ?>
    </tr>

	<?php
if ($typeTest != 'C') {
    for ($j=1;$j<=$nSounds;$j++) 
	{
      print '<tr>'; 
      print  '<td height="36"><strong>Débruitage '.$tm.' '.$j.' :</strong></td>';
	  $file = 'sound.'.$nTest.'.'.$typeTest.'.'.$j.$soundExt;
 	 print '<td><div align="center">';
	 print '<APPLET CODE="PlaySoundApplet" WIDTH="90" HEIGHT="36">'; 
     print '<PARAM name="filename" value="'.$file.'">';
     print '</APPLET>';
	 print '</div></td>';
	  for ($i=1;$i<=$nGrades;$i++) 
	    {
          print '<td><div align="center"> <input name="note'.$j.'" type="radio" value = '.$i.' ';
		  if ($i ==1) {print 'checked ';}
		  print '> </div></td>';
	    }
      print '</tr>';
   	}
 
}	
else {
      print '<tr>'; 
      print  '<td height="36"><strong>Meilleur Débruitage Partiel ('.$bestP.') :</strong></td>';
	  $file = 'sound.'.$nTest.'.'.'P'.'.'.$bestP.$soundExt;
	 print '<td><div align="center">';
	 print '<APPLET CODE="PlaySoundApplet" WIDTH="90" HEIGHT="36">'; 
     print '<PARAM name="filename" value="'.$file.'">';
     print '</APPLET>';
	 print '</div></td>';
      print '<td><div align="center"> <input name="note" type="radio" value = "P"> </div></td>';
	  print '</tr>';
      print '<tr>'; 
      print  '<td height="36"><strong>Meilleur Débruitage Maximal ('.$bestM.') :</strong></td>';
	  $file = 'sound.'.$nTest.'.'.'M'.'.'.$bestM.$soundExt;
	 print '<td><div align="center">';
	 print '<APPLET CODE="PlaySoundApplet" WIDTH="90" HEIGHT="36">'; 
     print '<PARAM name="filename" value="'.$file.'">';
     print '</APPLET>';
	 print '</div></td>';
      print '<td><div align="center"> <input name="note" type="radio" value = "M" checked> </div></td>';
	  print '</tr>';
}
	print '<tr>'; 
    print   '<td> </td>';
    print '<td> </td>';
    print '</tr>';
	?>
	
  </table>
  <td>        <div align="center">
        <p>
        <strong>
        <input type="hidden" name="nTest" value=<?php print($nTest)?>>
        <input type="hidden" name="nTotalTest" value=<?php print($nTotalTest)?>>
        <input type="hidden" name="typeTest" value=<?php print($typeTest)?>>
        <input type="hidden" name="nGrades" value=<?php print($nGrades)?>>
        <input type="hidden" name="nSounds" value=<?php print($nSounds)?>>
        <input type="hidden" name="nSoundsP" value=<?php print($nSoundsP)?>>
        <input type="hidden" name="nSoundsM" value=<?php print($nSoundsM)?>>
        <input type="hidden" name="results" value=<?php print($results)?>>
        <input type="hidden" name="bestM" value=<?php print($bestM)?>>
        <input type="hidden" name="bestP" value=<?php print($bestP)?>>
        <input type="hidden" name="soundExt" value=<?php print($soundExt)?>>
        </strong></p>
        <p>
          <strong><input name="continuer" type="submit" id="continue" value="Continuer">
          </strong></p>
        <p>&nbsp;</p>
        <p><font size="2">En cas de probl&egrave;me envoyez un mail à <a href="mailto:yu@cmap.polytechnique.fr">Guoshen Yu</a></font>  </p>
  </div></td>
</form>

</H3>


</BODY>
</html>

<?php
  include_once "replaceObjEmbed.php";
  echo replaceObjEmbed(ob_get_clean());
?>
