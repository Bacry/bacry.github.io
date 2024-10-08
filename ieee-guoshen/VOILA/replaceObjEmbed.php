<?php

# -------------------------------------------------------------------------------------------------
#
# replaceObjEmbed.php / replaceObjEmbed.js / 2007-06-18
# (C) Gero Zahn, gero@gerozahn.de
#
# Tiny Bugfix: More than 9 embeddings were not displayed correctly.
#
# -------------------------------------------------------------------------------------------------
#
# Replaces <object ...>...</object> / <applet ...>...</applet> / <embed ...>..</embed> areas
# on the fly by a JavaScript wrapper to prevent "Click to activate" in MSIE 6+ and Opera 9+.
# Can be used with existing PHP files -- with a proper .htaccess also with HTML files.
#
# May be used and copied for free in any environment (free or commercial).
#
# If you like to update or change this code, please keep the original copyright intact --
# and inform me via email about your enhancement.
#
# -------------------------------------------------------------------------------------------------
#
# ========== Usage on PHP files:
#
# 1. Copy replaceObjEmbed.php and replaceObjEmbed.js next to your PHP file.
#
# 2. Add this to the beginning of your PHP file
#    <?php
#      ob_start();
#    ? >
# (remove blank before last ">")
#
# 3. Add this to the eng of your PHP file
#    <?
#      include_once "replaceObjEmbed.php";
#      echo replaceObjEmbed(ob_get_clean());
#    ? >
# (remove blank before last ">")
#
# If you plan to put replaceObjEmbed.php and replaceObjEmbed.js in a subfolder, use this instead:
#
#    <?
#      include_once "yoursubfolder/replaceObjEmbed.php";
#      echo replaceObjEmbed(ob_get_clean(),"yoursubfolder");
#    ? >
# (remove blank before last ">")
#
# ========== Usage on HTML files:
#
# Basically the same as above, but you need to persuade your Apache to parse plain HTML files for
# PHP. This can be done easily with a proper .htaccess file.
#
# Add the following line to your .htaccess file:
#
# AddType application/x-httpd-php .html
#
# If this doesn't work, try this:
#
# AddType x-mapp-php4 .html
#
# This last one works for 1&1 and Schlund+Partner servers in German. You may want to contact
# the tech-support of your server provider for closer information how to achieve this.
#
# -------------------------------------------------------------------------------------------------

# 2006-06-30 - First release: Could just handle <object ...>...</object> areas
# 2006-07-01 - Adaptation: Can handle object/applet areas as well as single embed tags

function replaceObjEmbed($x,$jsdirprefix=".") {

# @param $x:
#   Code to be re-formatted - usually the buffered output ob_get_clean() at the very end of the file
# @param $jsdirprefix
#   Path prefix to replaceObjEmbed.js - defaults to "." if omitted

  # All tag areas or tags to be processed. Important: Start with <object ...>...</object> areas
  # as it could contain an <embed ...>..</embed> area or (perhaps) an unclosed <embed ...> tags

  $tags=array('`<object[^>]*>(.*)</object>`isU', # <object ...>...</object> areas
              '`<applet[^>]*>(.*)</applet>`isU', # <applet ...>...</applet> areas
              '`<embed[^>]*>(.*)</embed>`isU',   # <embed ...>..</embed> areas
              '`<embed[^>]*>`isU');              # single, unclosed <embed ...> tags outsite object areas

  $replacements=array(); # Storage for the elements found to be processed

  foreach(array_keys($tags) as $idx) { # Handle all kings of tag areas and tags, one by one

    $tmptags=array(); # Storage for the found occurrences
    preg_match_all($tags[$idx],$x,$tmptags); # And here they are

    if ($tmptags) { # Found some?

      foreach(array_keys($tmptags[0]) as $secidx) { # Deal with them, one by one

        # We have to move them apart -- especially <object ...>...</object> areas with an internal
        # <embed ...>..</embed> area or an unclosed <embed ...> tag -- otherwise they'd be found again.

        $tagval=$tmptags[0][$secidx]; # This is the current occurrence to be processed later on
        $tagkey="replacetag_".$idx."_".$secidx."_"; # Temporarily replace it by "replacetag_x_y_"
        # ... where x is 0..3 (object/applet/embed/s.embed) and y is the corresponding number.
				# The "_" suffix is necessary as otherwise "replacetag_x_10" would be replaced by "replacetag_x_1" later

        $replacements[$tagkey]=$tagval; # Store the occurrence beside it's unique key ...
        $x=str_replace($tagval,$tagkey,$x); # ... and actually replace the occurrence with the key
      }
    }

    unset($tmptags); # A bit of dirty work

  }

  foreach($replacements as $tagkey => $tagval) { # Handle all occurrences, one by one

    $jsval=addslashes($tagval); # Handle special characters properly
    $jsval=str_replace(chr(13),"",$jsval); # remove CRs - all in one line
    $jsval=str_replace(chr(10),"",$jsval); # remove LFs - all in one line

    # 1. Embed that tiny little external JS to work as actual embedder.
    # 2. Embed the original occurrence inside a JS variable -- 
    # 3. Call the tiny little embedder to dynamically output the variable
    # 4. Embed the original, unchanged occurrence in a <noscript>...</noscript> area as fall-back
    $jsval= "<script src=\"$jsdirprefix/replaceObjEmbed.js\" type=\"text/javascript\"></script>\n".
            "<script language=\"JavaScript\">\n".
            "<!--\n".
            "var jsval = '$jsval';\n".
#           "//document.write(jsval);". # This doesn't work as it's an internal document.write(...)
            "writethis(jsval);". # So: Use the external one-liner function to perform the trick
            "//-->\n".
            "</script>\n".
            "<noscript>$tagval</noscript>";

    # The original occurrence has been replaced with its unique "key" beforehanded,
		# now replace this stored key with is JS wrapper and noscript fallback.
    $x=str_replace($tagkey,$jsval,$x);

  }

  unset($replacements); # A bit of dirty work

  return $x; # And we're out!
}
?>
