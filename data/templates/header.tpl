<!DOCTYPE html>
<html dir="ltr">
	<head>
		<meta http-equiv="content-type" content="text/xhtml; charset=UTF-8" />
		<link href="https://static.junge-piraten.de/bootstrap-jupis.css" rel="stylesheet" />
		<link href="https://static.junge-piraten.de/bootstrap/css/bootstrap.css" rel="stylesheet" />
		<link href="https://static.junge-piraten.de/bootstrap/css/bootstrap-responsive.css" rel="stylesheet" />
		<script src="https://static.junge-piraten.de/jquery.min.js"></script>
		<script src="https://static.junge-piraten.de/bootstrap/js/bootstrap.min.js"></script>
		<link rel="icon" type="image/png" href="https://static.junge-piraten.de/favicon.png" />

		<meta name="viewport" content="width=device-width, initial-scale=1.0" />

		<style type="text/css">
		{literal}
                        body {
                                padding-top: 60px;
                                padding-bottom: 40px;
                        }
        
                        .no-padding {
                                padding:0px;
                        }

                        .no-top-bottom-margin {
                                margin-top:0px;
                                margin-bottom:0px;
                        }
		{/literal}
		</style>

                <!--[if lt IE 9]>
                        <script src="https://static.junge-piraten.de/ie-html5.js"></script>
                <![endif]-->

                <title>Junge Piraten &bull; {$title|escape:html}</title>
	</head>
	<body>
		<div class="navbar navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container-fluid">
					<a class="brand" href="index.php">
						Pads
					</a>
					<ul class="nav">
						<li class="active"><a href="index.php">Padliste</a></li>
						<li class="dropdown hidden-phone">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">Junge Piraten <b class="caret"></b></a>
							<ul class="dropdown-menu">
								<li><a href="https://www.junge-piraten.de/">Homepage</a></li>
								<li><a href="https://www.junge-piraten.de/community/">Mitmachen</a></li>
								<li><a href="https://forum.junge-piraten.de/">Forum</a></li>
								<li><a href="https://wiki.junge-piraten.de/">Wiki</a></li>
								<li><a href="https://ucp.junge-piraten.de/">UCP</a></li>
								<li class="active"><a href="https://pad.junge-piraten.de/">Pads</a></li>
								<li><a href="https://www.junge-piraten.de/presse">Presse</a></li>
							</ul>
						</li>
					</ul>

					{if !$user}
						<form class="navbar-form pull-right form-inline" action="{$root}" method="post">
							<input type="hidden" name="do" value="login" />
							<input type="text" name="user" class="span2" placeholder="Loginname" />
							<input type="password" name="pass" class="span2" placeholder="Passwort" />
							<button type="submit" class="btn btn-primary">Anmelden</button>
						</form>
					{else}
						<a href="{$root}?logout" class="btn btn-danger pull-right"><i class="icon-off icon-white"></i> Abmelden</a>
					{/if}
				</div>
			</div>
		</div>

		<div class="container-fluid">
			<div class="row-fluid">
				<div class="span12">
					<h1 style="margin-bottom: 20px;">{$title|escape:html}</h1>
