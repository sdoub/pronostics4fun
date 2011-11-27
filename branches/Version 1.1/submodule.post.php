<?php
require_once("begin.file.php");

switch ($_GET['SubModule'])
{
  case "1":
    include('submodules/login.post.php');
    break;
  case "2":
    include('submodules/register.post.php');
    break;
  case "3":
    include('submodules/forecast.post.php');
    break;
  case "4":
    include('submodules/rules.php');
    break;
  case "5":
    include('submodules/contact.post.php');
    break;
  case "6":
    include('submodules/match.detail.php');
    break;
  case "7":
    include('submodules/account.post.php');
    break;
  case "8":
    include('submodules/player.detail.php');
    break;
  case "9":
    include('submodules/player.group.detail.php');
    break;
  case "10":
    include('submodules/manual.forecast.post.php');
    break;
  case "11":
    include('submodules/scorer.detail.php');
    break;
  case "12":
    include('submodules/score.detail.php');
    break;
  case "13":
    include('submodules/assist.detail.php');
    break;
  case "14":
    include('submodules/vote.post.php');
    break;

}

require_once("end.file.php");
?>
