<?php
if($_isAuthenticated)
{
  // @ session already active
  echo "{'status':true,'message':'" .__encode("Vous êtes déjà connecté"). "','url':'index.php'}";
}
else
{
  //@ session not active
  if($_SERVER['REQUEST_METHOD']=='GET')
  {
    //@ first load
    $arr["status"] = false;
    $arr["message"] = str_replace('"',"'",$_authorisation->form());
  }
  else
  {
    //@ form submission
    $u = (!empty($_POST['u']))?trim($_POST['u']):false;	// retrive user var
    $pf = (!empty($_POST['pf']))?trim($_POST['pf']):false;	// retrive user var
    if ($pf) {
      // New Password requested
      $is_reset = $_authorisation->resetPassword($u);
      if ($is_reset) {
        //@ success
        $arr["status"] = true;
        $arr["message"] = __encode("Un email vous a été envoyé avec la procédure à suivre.");
      }
      else {
        $arr["status"] = false;
        $arr["message"] = __encode("Une erreur s'est produite, veuillez contacter l'administrateur : <a href='mailto:admin@pronostics4fun.com'>admin@pronostics4fun.com</a>.");
      }
    } else {
      $p = (!empty($_POST['p']))?trim($_POST['p']):false;	// retrive password var
      $kc = $_POST['kc'];
      // @ try to signin
      $is_auth = $_authorisation->signin($u,$p,$kc);

      if($is_auth)
      {
        //@ success
        $arr["status"] = true;
        $arr["message"] = __encode("Connexion autorisée");
        $arr["url"] = "index.php";
      }
      else
      {
        //@ failed
        $arr["status"] = false;
        $arr["message"] = __encode("Echec de connexion : veuillez vérifier vos informations de connexion.");
      }
    }
  }
  echo json_encode($arr);

}

?>