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
  $defaultView = $_POST['defaultview'];
  $receiveAlert = $_POST['receiveAlert'];
  $receiveResult = $_POST['receiveResult'];
  $avatar = $_POST['avatarName'];
  $isUpdated = $_authorisation->update($password,$firstName,$lastName,$email,$defaultView,$avatar,$receiveAlert,$receiveResult);

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