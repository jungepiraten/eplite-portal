<?php
// PadPortal

require_once(dirname(__FILE__) . "/config.inc.php");
require_once(dirname(__FILE__) . "/functions.inc.php");

require_once(dirname(__FILE__) . "/class/UserDatabase.class.php");
require_once(dirname(__FILE__) . "/lib/etherpad-lite-client.php");

require_once("Smarty/Smarty.class.php");

session_start();

// Establish the LDAP connection and set some options
$userdb = new UserDatabase(
		$config["admins"],
		$config["ldap"]["server"], $config["ldap"]["rdn"], $config["ldap"]["pass"], $config["ldap"]["base_dn"]);
$userdb->open();

// Create the smarty object (templating engine)
$smarty = new Smarty();
$smarty->template_dir = "data/templates";
$smarty->compile_dir = "data/templates_c";
$smarty->assign("root", $config["root"]);

// If we are authenticated, load User-informations from UserDB
$user = null;
if (isset($_SESSION["authenticated"]) && $_SESSION["authenticated"]) {
        $user = $userdb->getUser($_SESSION["user"]);
}

// Handle Login
if (isset($_POST["user"]) && isset($_POST["pass"])) {
	if (($user = $userdb->authenticate($_POST["user"], $_POST["pass"])) instanceof User) {
		$_SESSION["authenticated"] = true;
		$_SESSION["user"] = $user->getUid();
	} else {
		$smarty->assign("loginfailed", 1);
		$smarty->display("login.tpl");
		exit;
	}
}

// Handle Logout
if (isset($_REQUEST["logout"])) {
	$_SESSION["authenticated"] = false;
	session_destroy();
	header("Location: " . $config["root"]);
	exit;
}

$smarty->assign("user", $user);

// Configure EP-Lite-API
$eplite = new EtherpadLiteClient($config["eplite"]["apikey"], $config["eplite"]["apiurl"]);
$groupName = $_SERVER["HTTP_HOST"];
$result = $eplite->createGroupIfNotExistsFor($groupName);
if ($result == "_RESTART_DONE") {
	header("Refresh: 5");
	print("<h1>Please wait while we restart Etherpad Lite</h1>");
	exit;
}

$groupID = $result->groupID;

$padID = null;
if (isset($_REQUEST["padName"])) {
	$padName = stripslashes($_REQUEST["padName"]);
	$padID = $groupID . '$' . $padName;
}

if ($padID != null) {
	switch (isset($_REQUEST["do"]) ? stripslashes($_REQUEST["do"]) : "") {
	case "setPublic":
		if (!$user->isAdmin()) {
			return;
		}

		$eplite->setPublicStatus($padID, $_REQUEST["public"] == "1" ? "true" : "false");
		header("Location: " . $config["root"]);
		exit;
	case "setPassword":
		if (!$user->isAdmin()) {
			return;
		}

		$eplite->setPassword($padID, $_REQUEST["password"]);
		header("Location: " . $config["root"]);
		exit;
	case "delete":
		if (!$user->isAdmin()) {
			return;
		}

		$eplite->deletePad($padID);
		header("Location: " . $config["root"]);
		exit;
	default:
	case "show":
		// Check if Pad is public, maybe create it now
		try {
			$publicStatus = $eplite->getPublicStatus($padID)->publicStatus;
		} catch (InvalidArgumentException $e) {
			$eplite->createGroupPad($groupID, $padName, $config["defaultpadtext"]);
			$eplite->setPublicStatus($padID, "true");
			$publicStatus = true;
		}

		if ($user != null) {
			$authorID = $eplite->createAuthorIfNotExistsFor($user->getUid(), $user->getUid())->authorID;
			$sessionID = $eplite->createSession($groupID, $authorID, time() + 24*60*60)->sessionID;
			setcookie("sessionID", $sessionID, 0);
		} else if (!$publicStatus) {
			$smarty->display("login.tpl");
			exit;
		}

		$padURL = sprintf($config["eplite"]["padurl"], $groupID, $padName);
		print <<<EOT
<html>
<head><title>{$padName} &bull; {$groupName}</title></head>
<body style="margin:0px;padding:0px"><iframe src="{$padURL}" style="width:100%; height:100%; border:0px; margin:0px;"></iframe></body>
</html>
EOT;
	}
} else {
	$pads = array();
	foreach ($eplite->listPads($groupID)->padIDs as $padID) {
		list($groupID, $padName) = explode('$', $padID, 2);
		$pad = array();
		$pad["pad"] = $padName;
		$pad["isProtected"] = $eplite->isPasswordProtected($padID)->isPasswordProtected;
		$pad["isPublic"] = $eplite->getPublicStatus($padID)->publicStatus;
		if ($user != null || $pad["isPublic"]) {
			$pads[] = $pad;
		}
	}
	$smarty->assign("pads", $pads);
	$smarty->assign("showPadOptions", ($user != null && $user->isAdmin()) );

	$smarty->display("pads.tpl");
}

?>
