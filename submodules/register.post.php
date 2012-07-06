<?php
if($_SERVER['REQUEST_METHOD']=='GET')
{
  //@ first load
  $arr["status"] = false;
  $arr["message"] = $_authorisation->registerForm();
}
else
{
  //@ form submission
  $nickName = (!empty($_POST['nickName']))?trim($_POST['nickName']):false;	// retrive user var
  $password = (!empty($_POST['password']))?trim($_POST['password']):false;	// retrive password var
  $firstName = (!empty($_POST['firstName']))?trim($_POST['firstName']):false;	// retrive password var
  $lastName = (!empty($_POST['lastName']))?trim($_POST['lastName']):false;	// retrive password var
  $email = (!empty($_POST['email']))?trim($_POST['email']):false;	// retrive password var

  if (!$_authorisation->isNickNameAvailable($nickName)) {

    $arr["status"] = false;
    $arr["message"] = "Le pseudo " . $nickName . " est déjà utilisé, veuillez en choisir un autre.";

  }
  else
  {
    if ($_authorisation->isEmailAlreadyUsed($email)) {
      $arr["status"] = false;
      $arr["message"] = "Cet adresse email est déjà liée à un autre compte, veuillez en saisir une autre.";
    }
    else {
      $isRegistered = $_authorisation->register($nickName,$password,$firstName,$lastName,$email);

      if($isRegistered)
      {
        $arr["status"] = true;
        $arr["message"] = "Compte créé.";
        $arr["url"] = "index.php";
      }
      else
      {
        //@ failed
        $arr["status"] = false;
        $arr["message"] = "Erreur durant la création du compte.";
      }
    }
  }
}
$arr["perfAndError"] = $_databaseObject -> get ('sQueryPerf', '_totalTime', 'errorLog');
writeJsonResponse($arr);
?>