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
if ($stop == 0) {print('Denoising of sound '.$nTest.'/'.$nTotalTest);} else {print 'End of test';} 
?>
</TITLE>
</HEAD>

<BODY>
<H2><CENTER>
<p><strong><font color="#006600" size="6" face="Arial, Helvetica, sans-serif">

<?php
if ($stop ==1) {
print 'End of test : thank you for your collaboration!';
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
  print '<H2> <strong> Step 1 : "Partial" Denoising </strong> </H2>';
  print '<H3> The noise is attenuated but is still present. '.$nSoundsP.' such denoised sounds are proposed.';
  print "<p></p>";
  print 'Your task is to compare the quality of each of them and rate it from  1 (very bad quality) to '.$nGrade.' (very good quality).</H3>';  
 }
 else if ($typeTest == 'M') {
  print '<H2> <strong> Step 2 : "Maximal" Denoising</strong> </H2>';
  print '<H3> The original noise is almost totally suppressed. However, most of the time, the denoising procedure creates some artifacts that you can hear in the resulting denoised sound. '.$nSoundsM.' such denoised sounds are proposed.';
  print "<p></p>";
  print 'Your task is to compare the quality of each of them and rate it from  1 (very bad quality) to '.$nGrade.' (very good quality).</H3>';  
 }
 else {
  print '<H2> <strong> Step 3 :  Comparison between Partial Denoising and Maximal denoising  </strong> </H2>';
  print "<H3> Here, you have to compare the maximal denoising you considered as the best (version ".$bestM.") with the partial denoising you considered as the best (version ".$bestP.").";
  print "<p></p>";
  print "Please, select the version which is the most pleasant to your ears. </H3>";
 }
 ?>  

<H3>
  <form action="test_english.php" method="post" name="Registration" id="Registration">
  <table width="605" border="0" align="center">

    <tr> 
    <td width="406">&nbsp;</td>
    <td width="189"><div align="center"></div></td>
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
    <td height="36"><strong>Sound before denoising : </strong></td>
    <?php
	 $file = 'sound.'.$nTest.$soundExt;
	 print '<td><div align="center">';
	 print '<APPLET CODE="PlaySoundAppletEnglish" WIDTH="90" HEIGHT="36">'; 
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
      print  '<td height="36"><strong>Denoising '.$tm.' '.$j.' :</strong></td>';
	  $file = 'sound.'.$nTest.'.'.$typeTest.'.'.$j.$soundExt;
 	 print '<td><div align="center">';
	 print '<APPLET CODE="PlaySoundAppletEnglish" WIDTH="90" HEIGHT="36">'; 
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
      print  '<td height="36"><strong>Best Partial Denoising ('.$bestP.') :</strong></td>';
	  $file = 'sound.'.$nTest.'.'.'P'.'.'.$bestP.$soundExt;
	 print '<td><div align="center">';
	 print '<APPLET CODE="PlaySoundAppletEnglish" WIDTH="90" HEIGHT="36">'; 
     print '<PARAM name="filename" value="'.$file.'">';
     print '</APPLET>';
	 print '</div></td>';
      print '<td><div align="center"> <input name="note" type="radio" value = "P"> </div></td>';
	  print '</tr>';
      print '<tr>'; 
      print  '<td height="36"><strong>Best Maximal Denoising ('.$bestM.') :</strong></td>';
	  $file = 'sound.'.$nTest.'.'.'M'.'.'.$bestM.$soundExt;
	 print '<td><div align="center">';
	 print '<APPLET CODE="PlaySoundAppletEnglish" WIDTH="90" HEIGHT="36">'; 
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
          <strong><input name="continuer" type="submit" id="continue" value="Next">
          </strong></p>
        <p>&nbsp;</p>
        <p><font size="2">In case of a problem, please send an email to <a href="mailto:yu@cmap.polytechnique.fr">Guoshen Yu</a></font>  </p>
  </div></td>
</form>

</H3>


</BODY>
</html>

<?php
  include_once "replaceObjEmbed.php";
  echo replaceObjEmbed(ob_get_clean());
?>
