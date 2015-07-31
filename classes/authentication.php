<?php
//@ validate inclusion
if(!defined('VALID_ACCESS_AUTHENTICATION_')) exit(basename(__FILE__) . ' -> direct access is not allowed.');

define('VALID_ACCESS_SENDEMAIL_', true);
define('SESSION_DURATION', 10);

require_once("sendemail.php");
require_once(dirname(__FILE__)."/../lib/p4fmailer.php");
include_once (dirname(__FILE__)."/../lib/safeIO.php");

class Authorization
{
  private $_alreadyRefresh   = false;
  public function IsAuthenticated()
  {
    if (isset($_COOKIE["UserToken"]) && $_COOKIE["UserToken"] !="") {
      $this->refreshUserData();
      setcookie("SessionHasBeenRefreshed", "true", time() + 90 * 24 * 60 * 60, "/");
    } else {
      setcookie("SessionHasBeenRefreshed", "false", time() + 90 * 24 * 60 * 60, "/");
    }

    if(empty($_SESSION['exp_user']) || @$_SESSION['exp_user']['expires'] < time())
    {
      setcookie("SessionIsEmpty", "true", time() + 90 * 24 * 60 * 60, "/");
			if (array_key_exists('exp_user',$_SESSION) && array_key_exists('expires',$_SESSION['exp_user'])) {
				if ($_SESSION['exp_user']['expires'] < time())
				{ 
					setcookie("SessionHasExpired", $_SESSION['exp_user']['expires'], time() + 90 * 24 * 60 * 60, "/");
					setcookie("ServerTime", time(), time() + 90 * 24 * 60 * 60, "/");
				}
				else {
					setcookie("SessionHasExpired", "false", time() + 90 * 24 * 60 * 60, "/");
				}
			} else {
				setcookie("SessionHasExpired", "true", time() + 90 * 24 * 60 * 60, "/");
			} 
				
      return false;
    }
    else
    {
      setcookie("SessionIsEmpty", "false", time() + 90 * 24 * 60 * 60, "/");
      return true;
    }
  }

  public function form()
  {
    try {
      $nickName= "";
      if (isset($_COOKIE["NickName"])) {
        $nickName=$_COOKIE["NickName"];
      }

      $htmlForm =	'<form id="frmlogin">'.
						'<label>Pseudo / Email:</label>'.
						 '<input type="text" name="u" id="u" class="textfield" value="'.$nickName.'"/>'.
						 '<label>Mot de passe</label>'.
						 '<input type="password" name="p" id="p" class="textfield" />'.
                         '<label id="passwordForgotten">Mot de passe oublié ?</label>' .
						 '<span id="keepConnectionContainer"><input type="checkbox" name="keepConnection" id="keepConnection" class="checkboxfield" /><label for="keepConnection" class="checkboxlabel">Connexion automatique</label></span>'.
						 '<input type="submit" name="btn" id="btn" class="buttonfield" value="Se connecter" />'.
						 '</form>';
      return $htmlForm;
    }
    catch (Exception $e) {
      return $_COOKIE["NickName"];
    }
  }


  public function registerForm()
  {
    $htmlForm =	'
<form id="frmRegister">
<div>
<label >Pseudo : </label>
<input	name="nickname" id="nickName" class="textfield" type="text">
<label>Nom: </label>
<input name="lastName" id="lastName" class="textfield" type="text">
<label>Prénom : </label>
<input name="firstName" id="firstName" class="textfield" type="text">
<label>Email: </label>
<input name="email" id="email" class="textfield" type="text">
<label>Mot de passe : </label>
<input name="password" id="password" class="textfield"	type="password">
<label>Confirmer votre mot de passe : </label>
<input name="pbis" id="pbis" class="textfield" type="password">
<input name="btn" id="btn" class="buttonfield" value="Cr&eacute;er" type="submit">
</div>
</form>';
    return $htmlForm;
  }

  public function accountForm()
  {
    $defaultView = $_SESSION['exp_user']['IsCalendarDefaultView'];
    $receiveAlert = $_SESSION['exp_user']['ReceiveAlert'];
    $receiveResult = $_SESSION['exp_user']['ReceiveResult'];
    $originalAvatarPath = "DefaultAvatar.jpg";
    $fileExt = "";
    if (!empty($_SESSION['exp_user']['AvatarName']))
    {
      $fileExt = substr($_SESSION['exp_user']['AvatarName'],-3);
      $originalAvatarPath  = 'avatars/' . $this->getConnectedUserKey() . 'original.'.$fileExt;
    }


    $htmlForm =	'
<div id="accountAvatarDiv" >
<div class="avatarDiv">
<img src="images/'.$originalAvatarPath.'" id="OriginalAvatar" />

</div>
<div  id="file-uploader">
    <noscript>
        <p>Please enable JavaScript to use file uploader.</p>
        <!-- or put a simple form for upload here -->
    </noscript>
</div>

</div>
    <form id="frmAccount">
<div id="accountDiv">
<label class="title">Pseudo : </label>
<label class="read title">' . $_SESSION['exp_user']['NickName'] . '</label>
<label class="title">Nom: </label>
<input name="lastName" id="lastName" class="textfield" type="text" value="'. $_SESSION['exp_user']['LastName'] .'">
<label class="title">Prénom: </label>
<input name="firstName" id="firstName" class="textfield" type="text" value="'. $_SESSION['exp_user']['FirstName'] .'">
<label class="title">Email: </label>
<input name="email" id="email" class="textfield" type="text" value="'. $_SESSION['exp_user']['EmailAddress'] .'">
<label class="title">Mot de passe : </label>
<input name="password" id="password" class="textfield"	type="password">
<label class="title">Confirmer votre mot de passe : </label>
<input name="pbis" id="pbis" class="textfield" type="password">
<div><label class="titleCheckbox" for="DefaultForecastView">Vue par défaut des pronostics : </label>
<input type="checkbox" name="DefaultForecastView" id="DefaultForecastView" ';
    if ($defaultView=="1")
    $htmlForm .=	' checked';

    $htmlForm .= '/></div><div><label class="titleCheckbox" for="ReceiveAlert">Recevoir un rappel par email (1 jour avant) : </label>
<input type="checkbox" name="ReceiveAlert" id="ReceiveAlert" ';
    if ($receiveAlert=="1")
    $htmlForm .=	' checked';

    $htmlForm .= '/></div><div><label class="titleCheckbox" for="ReceiveResult">Recevoir les Résultats par email : </label>
<input type="checkbox" name="ReceiveResult" id="ReceiveResult" ';
    if ($receiveResult=="1")
    $htmlForm .=	' checked';
    $htmlForm .= '/></div>';

    $htmlForm .= '<input name="btn" id="btn" class="buttonfield" value="Valider" type="submit"/>
</div>
<div class="avatarContainer">
		<div >
<img src="' . $this->getAvatarPath() . '" id="avatar"/>
		</div>
<a id="AvatarLink" href="javascript:void(0);" >Modifier</a>

<input name="avatarName" id="avatarName" value="' . $_SESSION['exp_user']['AvatarName'] . '" type="hidden"/>

</div>
</form>';
    return $htmlForm;
  }

  public function getConnectedUserInfo($value)
  {
    return $_SESSION['exp_user'][$value];
  }

  public function getConnectedUser()
  {
    if (isset($_SESSION['exp_user'])) {
      return $_SESSION['exp_user']['NickName'];
    } else {
      return "";
    }
  }
  public function getConnectedUserKey()
  {
    if (isset($_SESSION['exp_user'])) {
      return $_SESSION['exp_user']['PrimaryKey'];
    } else {
      return 0;
    }
  }

  public function getConnectedUserToken()
  {
    if (isset($_SESSION['exp_user'])) {
      return $_SESSION['exp_user']['Token'];
    } else {
      return 0;
    }
  }

  public function getAvatarPath()
  {
    $avatarPath = ROOT_SITE. '/images/DefaultAvatar.jpg';
    $avatarName = $_SESSION['exp_user']['AvatarName'];
    if (!empty($avatarName)) {
      $avatarPath= ROOT_SITE. '/images/avatars/'.$avatarName.'?time='.time();
    }

    return $avatarPath;
  }
  public function isNickNameAvailable ($nickName)
  {
    global $_databaseObject;
    $return = false;

    if($nickName)
    {
      $nickName = $nickName;
      $nickName = strtolower($nickName);
      $sql = "SELECT * FROM players WHERE ";
      $sql .= "lower(NickName)='".mysql_real_escape_string(__encode($nickName))."'";

      $resultSet = $_databaseObject->queryPerf($sql,"Check if the nickname is available");

      if(!$resultSet) return true;
      $return = true;
      while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
      {
        $return = false;
      }
      unset($rowSet,$resultSet,$sql);
      return $return;

    }
    return $return;
  }

  public function isEmailAlreadyUsed ($email)
  {
    global $_databaseObject;
    $return = false;

    if($email)
    {
      $email = $email;
      $email = strtolower($email);
      $sql = "SELECT * FROM players WHERE ";
      $sql .= "lower(EmailAddress)='".mysql_real_escape_string(__encode($email))."'";

      $resultSet = $_databaseObject->queryPerf($sql,"Check if the email is already use");

      if(!$resultSet) return false;
      //TODO: Check if the account is enabled
      while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet))
      {
        $return = true;
      }
      unset($rowSet,$resultSet,$sql);
      return $return;

    }
    return $return;
  }

  public function register ($nickName, $password, $firstName, $lastName, $email)
  {
    global $_databaseObject;
    $return = false;
    if($nickName&&$password&&$firstName&&$lastName&&$email)
    {
      $activationKey = generatePassword(15,4);
      //INSERT INTO `pronostics4fun`.`players` (`NickName`, `FirstName`, `LastName`, `EmailAddress`, `Password`, `IsAdministrator`) VALUES (NULL, 'sdoub', 'S?bastien', 'Dubuc', 'sebastien.dubuc@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', '0');
      $sql = "INSERT INTO players (`NickName`, `FirstName`, `LastName`, `EmailAddress`, `Password`, `IsAdministrator`, AvatarName, ActivationKey, CreationDate)";
      $sql .= "VALUES ('". mysql_real_escape_string(__encode($nickName))."',
      '".mysql_real_escape_string($firstName)."',
      '".mysql_real_escape_string($lastName)."',
      '".mysql_real_escape_string($email)."',
      '".md5(mysql_real_escape_string($password))."', '0', '', '" . $activationKey . "',CURRENT_DATE())";
      if(!$_databaseObject->queryPerf($sql,"Account creation"))
      {
        return false;
      }
      else
      {
        //TODO: Send email in order to activate the new account
        $this->signin($nickName, $password, false);
        $this->SendEmailToAdminNewAccount($nickName, $email, $activationKey);
        $this->SendEmailNewAccount($nickName, $email, $activationKey);
        $return = true;
        $userId=$this->getConnectedUserKey();
        $sqlFixRank ="INSERT INTO playerranking  (CompetitionKey, PlayerKey, RankDate, Rank)
 SELECT " . COMPETITION . ",$userId, RankDate,MAX(Rank) FROM playerranking
 WHERE CompetitionKey=" . COMPETITION . "
 AND NOT EXISTS (SELECT 1 FROM playerranking PR WHERE PR.RankDate =playerranking.RankDate AND PR.PlayerKey=$userId)
 GROUP BY RankDate";
        $_databaseObject->queryPerf($sqlFixRank,"Account fix Rank");

        $sqlFixGroupRank ="INSERT INTO playergroupranking  (GroupKey, PlayerKey, RankDate, Rank)
 SELECT GroupKey,$userId, RankDate,MAX(Rank) FROM playergroupranking
 WHERE GroupKey IN (SELECT groups.PrimaryKey FROM groups WHERE groups.CompetitionKey=" . COMPETITION . ")
 AND NOT EXISTS (SELECT 1 FROM playergroupranking PR WHERE PR.RankDate =playergroupranking.RankDate AND PR.GroupKey =playergroupranking.GroupKey AND PR.PlayerKey=$userId)
 GROUP BY RankDate";
        $_databaseObject->queryPerf($sqlFixGroupRank,"Account fix Rank");
      }

      unset($sql);
    }
    return $return;
  }

  public function changePassword ($password, $activationKey,$nickName,$email)
  {
    global $_databaseObject;
    $return = false;
    if($password && $activationKey)
    {
      $newActivationKey = generatePassword(15,4);
      if ($password) {
        $sql = "UPDATE players SET `Password`='".md5(mysql_real_escape_string($password))."' , ActivationKey='".$newActivationKey."' WHERE ActivationKey='" . $activationKey . "'";
        if(!$_databaseObject->queryPerf($sql,"Update Account"))
        {
          return false;
        }
        else
        {
          $this->SendEmailChangeAccountPassword($nickName, $email, $activationKey);
          $return = true;
        }
      }
      else {
        $return = true;
      }
      unset($sql);
    }
    return $return;
  }

  public function update ($password, $firstName, $lastName, $email, $defaultView, $avatar, $receiveAlert,$receiveResult)
  {
    global $_databaseObject;
    $return = false;
    if($firstName&&$lastName&&$email)
    {
      $activationKey = generatePassword(15,4);
      //INSERT INTO `pronostics4fun`.`players` (`NickName`, `FirstName`, `LastName`, `EmailAddress`, `Password`, `IsAdministrator`) VALUES (NULL, 'sdoub', 'S?bastien', 'Dubuc', 'sebastien.dubuc@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', '0');
			$resetEmailAddress ='';
			if ($_SESSION['exp_user']['EmailAddress']!=$email)
				$resetEmailAddress =' , IsEmailValid=0';
      $sql = "UPDATE players SET `FirstName`='".mysql_real_escape_string($firstName)."',
      `LastName`='".mysql_real_escape_string($lastName)."',
      `EmailAddress`='".mysql_real_escape_string($email)."',
      ActivationKey='$activationKey',
      IsCalendarDefaultView = $defaultView,
      ReceiveAlert = $receiveAlert,
      ReceiveResult = $receiveResult,
      AvatarName='$avatar'
			$resetEmailAddress
      WHERE PrimaryKey=" . $_SESSION['exp_user']['PrimaryKey'];
      if(!$_databaseObject->queryPerf($sql,"Update Account"))
      {
        return false;
      }
      else
      {
        if ($password) {
          $return = $this->changePassword($password, $activationKey, $_SESSION['exp_user']['NickName'],$email);
        }
        else {
          $return = true;
        }
				if ($_SESSION['exp_user']['EmailAddress']!=$email)
					$this->SendEmailEmailChanged($_SESSION['exp_user']['NickName'], $email, $activationKey);
        $_SESSION['exp_user']['LastName']=$lastName;
        $_SESSION['exp_user']['FirstName']=$firstName;
        $_SESSION['exp_user']['EmailAddress']=$email;
        $_SESSION['exp_user']['IsCalendarDefaultView']=$defaultView;
        $_SESSION['exp_user']['ReceiveAlert']=$receiveAlert;
        $_SESSION['exp_user']['ReceiveResult']=$receiveResult;
        $_SESSION['exp_user']['AvatarName']=$avatar;

      }

      unset($sql);
    }
    return $return;
  }

  private function SendEmailToAdminNewAccount ($pseudo, $email, $activationKey)
  {

    $mail = new P4FMailer();
    $return = false;
    try {
      $mail->SetFrom($email, $pseudo);
      $mail->AddReplyTo('admin@pronostics4fun.com', 'Pronostics4Fun - Administrateur');
      $mail->Subject    = "Pronostics4Fun - $pseudo vient de s'inscrire!";

      $mail->AltBody    = "Pour visualiser le contenu de cet email, votre messagerie doit permettre la visualisation des emails au format HTML!"; // optional, comment out and test
      $emailBody = "<h3>" . $pseudo . " <".$email."> a créé un nouveau compte</h3>";
      $emailBody .= "</br>";
      $emailBody .= "<p>L'administrateur de Pronostics4Fun.</p>";

      $mail->MsgHTML($emailBody);

      $mail->AddAddress('admin@pronostics4fun.com', 'Pronostics4Fun - Administrateur');

      $mail->AddAttachment("images/Logo.png");      // attachment

      $mail->Send();
      $return = true;
    } catch (phpmailerException $e) {
      echo $e->errorMessage(); //Pretty error messages from PHPMailer
    } catch (Exception $e) {
      echo $e->getMessage(); //Boring error messages from anything else!
    }

    unset($mail);
    return $return;

  }

  private function SendEmailChangeAccountPassword ($pseudo, $email, $activationKey)
  {
    $mail = new P4FMailer();
    $return = false;

    try {
      $mail->SetFrom($email, $pseudo);
      $mail->AddReplyTo('admin@pronostics4fun.com', 'Pronostics4Fun - Administrateur');
      $mail->Subject    = "Pronostics4Fun - Changement de mot de passe";

      $mail->AltBody    = "Pour visualiser le contenu de cet email, votre messagerie doit permettre la visualisation des emails au format HTML!"; // optional, comment out and test
      $emailBody = "<h3>$pseudo a changé son mot de passe</h3>";
      $emailBody .= "</br>";
      $emailBody .= "<p>L'administrateur de Pronostics4Fun.</p>";

      $mail->MsgHTML($emailBody);

      $mail->AddAddress('admin@pronostics4fun.com', 'Pronostics4Fun - Administrateur');

      $mail->AddAttachment("images/Logo.png");      // attachment

      $mail->Send();
      $return = true;

    } catch (phpmailerException $e) {
      echo $e->errorMessage(); //Pretty error messages from PHPMailer
    } catch (Exception $e) {
      echo $e->getMessage(); //Boring error messages from anything else!
    }

    unset($mail);
    return $return;

  }

  private function SendEmailNewAccount ($pseudo, $email, $activationKey)
  {

    $mail = new P4FMailer();
    $return = false;

    try {
      $mail->SetFrom('admin@pronostics4fun.com', 'Pronostics4Fun - Administrateur');
      $mail->AddReplyTo('admin@pronostics4fun.com', 'Pronostics4Fun - Administrateur');
      $mail->Subject    = "Pronostics4Fun - Bienvenue !";

      $mail->AltBody    = "Pour visualiser le contenu de cet email, votre messagerie doit permettre la visualisation des emails au format HTML!"; // optional, comment out and test
      $emailBody = "<h3>Bienvenue $pseudo et merci de vous êtes inscrit sur Pronostics4Fun</h3>";
      $emailBody .= "<br/>";
      $emailBody .= "<p>Afin de pouvoir recevoir les notifications (Alertes, Résultats, ...) de Pronostics4Fun, veuillez cliquer sur le lien ci-dessous :</p>";
      $emailBody .= "<a href='" . ROOT_SITE . "/email.validation.php?key=" . $activationKey . "'>Validation de l'adresse email</a>";
      $emailBody .= "</br>";
      $emailBody .= "<p>L'administrateur de Pronostics4Fun.</p>";

      $mail->MsgHTML($emailBody);

      $mail->AddAddress($email, $pseudo);

      $mail->AddAttachment("images/Logo.png");      // attachment

      $mail->Send();
      $return = true;

    } catch (phpmailerException $e) {
      echo $e->errorMessage(); //Pretty error messages from PHPMailer
    } catch (Exception $e) {
      echo $e->getMessage(); //Boring error messages from anything else!
    }

    unset($mail);
    return $return;
  }

  private function SendEmailEmailChanged ($pseudo, $email, $activationKey)
  {

    $mail = new P4FMailer();
    $return = false;

    try {
      $mail->SetFrom('admin@pronostics4fun.com', 'Pronostics4Fun - Administrateur');
      $mail->AddReplyTo('admin@pronostics4fun.com', 'Pronostics4Fun - Administrateur');
      $mail->Subject    = "Pronostics4Fun - Changement d'adresse email";

      $mail->AltBody    = "Pour visualiser le contenu de cet email, votre messagerie doit permettre la visualisation des emails au format HTML!"; // optional, comment out and test
      $emailBody = "<h3>Bonjour $pseudo,</h3>";
      $emailBody .= "<br/>";
      $emailBody .= "<p>Vous avez changé d'adresse email, afin de continuer à recevoir les notifications (Alertes, Résultats, ...) de Pronostics4Fun, veuillez cliquer sur le lien ci-dessous :</p>";
      $emailBody .= "<a href='" . ROOT_SITE . "/email.validation.php?key=" . $activationKey . "'>Validation de l'adresse email</a>";
      $emailBody .= "</br>";
      $emailBody .= "<p>L'administrateur de Pronostics4Fun.</p>";

      $mail->MsgHTML($emailBody);

      $mail->AddAddress($email, $pseudo);

      $mail->AddAttachment("images/Logo.png");      // attachment

      $mail->Send();
      $return = true;

    } catch (phpmailerException $e) {
      echo $e->errorMessage(); //Pretty error messages from PHPMailer
    } catch (Exception $e) {
      echo $e->getMessage(); //Boring error messages from anything else!
    }

    unset($mail);
    return $return;
  }

  public function signin($u,$p,$KeepConnection)
  {
    global $_databaseObject;

    $return = false;

    if($u&&$p)
    {
      $sql = "SELECT * FROM players WHERE ";
      $sql .= "(NickName='".mysql_real_escape_string(__encode($u))."' OR EmailAddress='".mysql_real_escape_string(__encode($u))."')";
      $sql .= " AND Password = '".md5($p)."'";

      $resultSet = $_databaseObject->queryPerf($sql,"Recuperation des infos du match");

      if(!$resultSet) return false;

      $rowSet = $_databaseObject -> fetch_assoc ($resultSet);
      if ($rowSet["PrimaryKey"]) {
        $this->set_session(array_merge($rowSet,array('expires'=>time()+(60*SESSION_DURATION))));
        $return = true;
      } else {
        $return = false;
      }

      unset($rowSet,$resultSet,$sql);
      if ($return) {
        // D�finition du temps d'expiration des cookies
        $expiration = $KeepConnection == "false" ? time() + 60*SESSION_DURATION : time() + 90 * 24 * 60 * 60;
        $keepConnection = $KeepConnection;
        //   Cr�ation des cookies
        $userToken = generatePassword(50,7);
        setcookie("UserToken", $userToken, $expiration, "/");
        setcookie("NickName", $this->getConnectedUser(), time() + 90 * 24 * 60 * 60, "/");
        setcookie("keepConnection", $keepConnection, time() + 90 * 24 * 60 * 60, "/");
        $_SESSION['exp_user']['Token'] = $userToken;
	      $this->updateLastConnection($userToken);
      }
      return $return;
    }


    return $return;
  }

  public function resetPassword($u)
  {
    global $_databaseObject;

    $return = false;

    if($u)
    {
      $sql = "SELECT * FROM players WHERE ";
      $sql .= "players.IsEmailValid=1 AND (NickName='".mysql_real_escape_string(__encode($u))."' OR EmailAddress='".mysql_real_escape_string(__encode($u))."')";

      $resultSet = $_databaseObject->queryPerf($sql,"Check if user exists or not");

      if(!$resultSet) return false;
      $rowSet = $_databaseObject -> fetch_assoc ($resultSet);
      $playerKey = $rowSet["PrimaryKey"];
      $nickName = $rowSet["NickName"];
      $emailAddress = $rowSet["EmailAddress"];
      //$activationKey = $rowSet["ActivationKey"];

      $activationKey = generatePassword(15,4);
      $sqlReset = "UPDATE players SET ActivationKey = '".$activationKey."' WHERE ";
      $sqlReset .= "PrimaryKey=". $playerKey;

      if ($_databaseObject->queryPerf($sqlReset,"Reset password with new one generated")) {

        $mail = new P4FMailer();

        try {
          $mail->SetFrom('admin@pronostics4fun.com', 'Pronostics4Fun - Administrateur');
          $mail->AddReplyTo('admin@pronostics4fun.com', 'Pronostics4Fun - Administrateur');
          $mail->Subject    = "Pronostics4Fun - Réinitialisation du mot de passe";

          $mail->AltBody    = "Pour visualiser le contenu de cet email, votre messagerie doit permettre la visualisation des emails au format HTML!"; // optional, comment out and test

          $mail->MsgHTML(file_get_contents(ROOT_SITE.'/email.reset.password.php?NickName='.$nickName.'&Key='.$activationKey));
          $address= $emailAddress;

          $mail->AddAddress($address, $nickName);

          $mail->AddAttachment("images/Logo.png");      // attachment

          $mail->Send();
          //echo "Message sent to $nickName!<br/>";
          $currentFormattedDate = strftime("%A %d %B %Y, %H:%M",time());

        } catch (phpmailerException $e) {
          echo $e->errorMessage(); //Pretty error messages from PHPMailer
        } catch (Exception $e) {
          echo $e->getMessage(); //Boring error messages from anything else!
        }

        unset($mail);
        $return = true;


      }
      unset($rowSet,$resultSet,$sql);
      return $return;
    }

    return $return;
  }
  private function refreshUserData()
  {
    global $_databaseObject;

    $return = false;

    if (!$this->_alreadyRefresh) {
      if((empty($_SESSION['exp_user']) || @$_SESSION['exp_user']['expires'] < time()))
      {
        $sql = "SELECT * FROM players WHERE ";
        $sql .= "Token='".$_COOKIE["UserToken"] ."'";
        $resultSet = $_databaseObject->queryPerf($sql,"Recuperation des infos du user");

        //TODO: Check if the account is enabled
        while ($rowSet = $_databaseObject -> fetch_assoc ($resultSet)) {

          $this->set_session($rowSet);
          $_SESSION['exp_user']['expires'] = time()+(60*SESSION_DURATION);	//@ renew 45 minutes
          // D�finition du temps d'expiration des cookies
          if (isset($_COOKIE["keepConnection"])){
            $expiration = $_COOKIE["keepConnection"]=="false" ? time() + (60*SESSION_DURATION) : time() + 90 * 24 * 60 * 60;
          }
          else {
            $expiration =  time() + (60*SESSION_DURATION);
          }
          // Cr�ation des cookies
          $userToken = generatePassword(50,7);
          setcookie("UserToken", $userToken, $expiration, "/");
          setcookie("UserToken2", $userToken, $expiration, "/");
          setcookie("NickName", $this->getConnectedUser(), time() + 90 * 24 * 60 * 60, "/");
          if (isset($_COOKIE["keepConnection"])){
            setcookie("keepConnection", $_COOKIE["keepConnection"], time() + 90 * 24 * 60 * 60, "/");
          }
          else {
            setcookie("keepConnection", "false", time() + 90 * 24 * 60 * 60, "/");
          }

          unset($rowSet,$resultSet,$sql);
          $this->updateLastConnection($userToken);
          $_SESSION['exp_user']['Token'] = $userToken;
          $this->_alreadyRefresh = true;
          setcookie("refreshUserData", "true", time() + 90 * 24 * 60 * 60, "/");
          $return = true;
        }
      } else {
        setcookie("refreshUserData", "false", time() + 60 * 60, "/");
        $return =true;

      }
    } else {
      setcookie("refreshUserData", "false * in same session", time() + 90 * 24 * 60 * 60, "/");
      $return =true;
    }

    return $return;
  }

  private function updateLastConnection ($userToken) {
    global $_databaseObject;
    $sql = "UPDATE players SET IsEnabled=1, LastConnection=NOW(), Token='$userToken' WHERE ";
    $sql .= "PrimaryKey=". $this->getConnectedUserKey();

    $_databaseObject->queryPerf($sql,"Refresh last connection date");

  }

  public function signout()
  {
    global $_databaseObject;

    $_databaseObject -> queryPerf ("DELETE FROM connectedusers WHERE PlayerKey=" . $this->getConnectedUserKey() );

    setcookie("UserToken", "", time() - 60, "/");
    if (isset($_COOKIE["UserToken"]))
    unset($_COOKIE["UserToken"]);
    if (isset($_SESSION['exp_user']))
    unset($_SESSION['exp_user']);
  }

  public function renew_session()
  {
    if (isset($_COOKIE["keepConnection"]) && $_COOKIE["keepConnection"]=="true"){
      setcookie("UserToken", $_SESSION['exp_user']['Token'], time() + 90 * 24 * 60 * 60, "/");
    }
    else {
      setcookie("UserToken", $_SESSION['exp_user']['Token'], time() + (60*SESSION_DURATION), "/");
    }

    $_SESSION['exp_user']['expires'] = time()+(60*SESSION_DURATION);	//@ renew 45 minutes
  }

  private function set_session($a)
  {
    if(!empty($a))
    {
      setcookie("exp_user", "true", time() + 90 * 24 * 60 * 60, "/");
      $_SESSION['exp_user'] = $a;
    } else {
      setcookie("exp_user", "false because array is empty", time() + 60 * 60, "/");
    }
  }
}
?>