<?php
if($_SERVER['REQUEST_METHOD']=='GET')
{
  //@ first load
  $arr["status"] = false;
  $arr["message"] = $_authorisation->accountForm();
}
else
{
  //@ form submission
  $password = (!empty($_POST['password']))?trim($_POST['password']):false;	// retrive password var
  $firstName = (!empty($_POST['firstName']))?trim($_POST['firstName']):false;	// retrive password var
  $lastName = (!empty($_POST['lastName']))?trim($_POST['lastName']):false;	// retrive password var
  $email = (!empty($_POST['email']))?trim($_POST['email']):false;	// retrive password var
  $defaultView = (!empty($_POST['defaultview']))?trim($_POST['defaultview']):false;
  $receiveAlert = (!empty($_POST['receiveAlert']))?trim($_POST['receiveAlert']):false;
  $receiveResult = (!empty($_POST['receiveResult']))?trim($_POST['receiveResult']):false;
  $avatar = (!empty($_POST['avatarName']))?trim($_POST['avatarName']):false;
  $activationKey = (!empty($_POST['key']))?trim($_POST['key']):false;
  $nickName = (!empty($_POST['nickName']))?trim($_POST['nickName']):false;
  if ($activationKey){
    $arr["ChangePassword"] = true;
    $isUpdated = $_authorisation->changePassword($password,$activationKey,$nickName,$email);
  } else {
    $arr["ChangePassword"] = false;
    $isUpdated = $_authorisation->update($password,$firstName,$lastName,$email,$defaultView,$avatar,$receiveAlert,$receiveResult);
  }

  if($isUpdated)
  {
    $arr["status"] = true;
    $arr["message"] = __encode("Compte mis à jour.");
    $arr["url"] = "index.php";
  }
  else
  {
    //@ failed
    $arr["status"] = false;
    $arr["message"] = __encode("Erreur durant la mise à jour du compte.");
  }
}


writeJsonResponse($arr);
?>