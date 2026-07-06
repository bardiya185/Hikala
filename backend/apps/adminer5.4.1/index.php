<?php
/*******************************************************
** Adminer, since version 4.7.0 does not accept        *
** connections without a password.                     *
** For version 4.7.x to accept an empty password,      *
** in the adminer-4.7.x.php file, replace :            *
** login($Ae,$F){if($F=="") by login($Ae,$F){if(1===2) *
** This can be done automatically by replacing false   *
** with true in the line below.                        *
*******************************************************/
$AcceptEmptyPassword = false;

$files = glob('adminer-*.php');
if(!empty($files)) {
  $version = str_replace(array('adminer-','.php'),'',$files[0]);
  $file = 'adminer-'.$version.'.php';
  if(version_compare($version, '4.7.0', '>=')) {
    if(file_exists($file)) {
      /* original strings to be replaced are:
          4.7.0 login($_e,$F){if($F=="")
          4.7.1 login($ze,$F){if($F=="")
          4.7.2 login($ze,$F){if($F=="")
          4.7.3 login($Ae,$F){if($F=="")
          4.7.4 login($_e,$F){if($F=="")
          4.7.5 login($Ae,$E){if($E=="")
          4.7.6 login($Ce,$E){if($E=="")
          4.7.7 login($Ce,$E){if($E=="")
          4.7.8 login($Be,$F){if($F=="")
          4.7.9 login($Fe,$F){if($F=="")
          4.8.0 login($ze,$F){if($F=="")
          4.8.1 login($_e,$F){if($F=="")
          4.8.4 login($Ge,$F){if($F=="")
          4.16.0 login($ye,$F){if($F=="")
          4.17.0 idem 4.7.3
          4.17.1 idem 4.7.3
          5.0.1  idem 4.8.4
          5.0.2  login($Je,$F){if($F=="")
          5.0.4  login($Ee,$F){if($F=="")
          5.0.5  login($xe,$F){if($F=="")
          5.0.6  idem 4.16.0
          5.1.0  login($Ce,$G){if($G=="")
          5.1.1  idem 4.7.9
          5.2.0  idem 5.0.2
          5.2.1  login($Le,$F){if($F=="")
          5.3.0  login($We,$F){if($F=="")
          5.4.0  login($Xe,$F){if($F=="")
          5.4.0  idem 5.4.0
         must be replaced by
          4.7.0 login($_e,$F){if(1===2)
          4.7.1 login($ze,$F){if(1===2)
          4.7.2 login($ze,$F){if(1===2)
          4.7.3 login($Ae,$F){if(1===2)
          4.7.4 login($_e,$F){if(1===2)
          4.7.5 login($Ae,$E){if(1===2)
          4.7.6 login($Ce,$E){if(1===2)
          4.7.7 login($Ce,$E){if(1===2)
          4.7.8 login($Be,$F){if(1===2)
          4.7.9 login($Fe,$F){if(1===2)
          4.8.0 login($ze,$F){if(1===2)
          4.8.1 login($_e,$F){if(1===2)
          4.8.4 login($Ge,$F){if(1===2)
          4.16.0 login($ye,$F){if(1===2)
          4.17.0 idem 4.7.3
          4.17.1 idem 4.7.3
          5.0.1  idem 4.8.4
          5.0.2  login($Je,$F){if(1===2)
          5.0.4  login($Ee,$F){if(1===2)
          5.0.5  login($xe,$F){if(1===2)
          5.0.6  idem 4.16.0
          5.1.0  login($Ce,$G){if(1===2)
          5.1.1  idem 4.7.9
          5.2.0  idem 5.0.2
          5.2.1  login($Le,$F){if(1===2)
          5.3.0  login($We,$F){if(1===2)
          5.4.0  login($Xe,$F){if(1===2)
          5.4.1  idem 5.4.0
      */
      $AdminerContents = file_get_contents($file);
      if($AcceptEmptyPassword) {
        $searchpreg = '~(login\(\$[_|z|A|B|C|E|F|J|L|x|y|W|X]e,\$[F|E|G]\)\{if\()(\$[F|E|G]=="")(\))~';
        $replacepreg = '${1}'."1===2".'${3}';
      }
      else {
        $searchpreg = '~(login\(\$[_|z|A|B|C|E|F|J|L|x|y|W]e,\$([F|E|G])\)\{if\()(1===2)(\))~';
        $replacepreg = '${1}'.'$'.'${2}'.'==""'.'${4}';
      }
      if(preg_match($searchpreg,$AdminerContents,$matches) > 0 ) {
        $AdminerContents = preg_replace($searchpreg,$replacepreg,$AdminerContents,1,$count);
        if($count > 0){
          $fp = fopen($file,'wb');
          fwrite($fp,$AdminerContents);
          fclose($fp);
        }
      }
      unset($adminerContents);
    }
  }
  // include Adminer
  include $file;
}

?>
