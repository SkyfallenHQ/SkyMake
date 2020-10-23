<?php
function singlewidget($lesson,$teacher,$time,$topic,$unit,$backgorundimage,$lessonid){
return "<!--Begin single SkyMake Widget-->
<div class=\"container centered-container\">
  <div class=\"row\">
	<div class=\"col\">
	  <div class=\"lesson-card\" style=\" background: url('".$backgorundimage."') no-repeat;\">
		<div class=\"top\">
		  <div class=\"wrapper\">
			<div class=\"mynav\">
			  <a href=\"javascript:;\"><span class=\"lnr lnr-chevron-left\"></span></a>
			  <a href=\"javascript:;\"><span class=\"lnr lnr-cog\"></span></a>
			</div>
			<h1 class=\"heading\">".$lesson."</h1>
			<h3 class=\"location\">".$teacher."</h3>
			<p class=\"temp\">
			  <span class=\"temp-value\">".$time."</span>
			</p>
		  </div>
		</div>
		<div class=\"bottom\">
		  <div class=\"wrapper\">
			<ul class=\"forecast\">
			  <a href=\"javascript:;\"><span class=\"lnr lnr-chevron-up go-up\"></span></a>
			  <li class=\"active\">
				<span class=\"date\">".$lesson."</span>
				<span class=\"lnr lnr-sun condition\">
				  <span class=\"temp\">".$time."</span>
				</span>
			  </li>
			  <li>
				<span class=\"date\">".$topic."</span>
				<span class=\"lnr lnr-cloud condition\">
				  <span class=\"temp\"><a href='/lesson/".$lessonid."'>"._("Visit Lesson")."</a></span>
				</span>
			  </li>
			</ul>
		  </div>
		</div>
	  </div>
	</div>
  </div>
</div>
<!--End single SkyMake Widget-->";
}
function overview($lesson,$teacher,$time,$topic,$unit,$backgorundimage,$lessonid,$content){
    if($_SESSION["dm"] == "off") {
    return "<!--End single SkyMake Widget-->
<div class=\"container centered-container\">
  <div class=\"row\">
	<div class=\"col\">
	  <div class=\"lesson-card\" style=\" background: url('".$backgorundimage."') no-repeat;\">
	  </div>
	</div>
	<div class=\"col\">
	  <div class=\"lesson-card\" style='background-color: white;'>
	  <div style='margin: 35px;'>
			<h1>".$lesson."</h1>
			<h3>".$teacher."</h3>
			<h4>".$time."</h4>
	  </div>
		<div class=\"bottom\">
		  <div class=\"wrapper\">
			<ul class=\"forecast\">
			  <a href=\"javascript:;\"><span class=\"lnr lnr-chevron-up go-up\"></span></a>
			  <li>
				<span class=\"date\">".$topic."</span>
			  </li>
			</ul>
		  </div>
		</div>
		<div style='margin: 35px;'>
			    ".$content."
        </div>
	  </div>
	</div>
  </div>
</div>
<!--End single SkyMake Widget-->";
} else {
        return "<!--End single SkyMake Widget-->
<div class=\"container centered-container\">
  <div class=\"row\">
	<div class=\"col\">
	  <div class=\"lesson-card\" style=\" background: url('".$backgorundimage."') no-repeat;\">
	  </div>
	</div>
	<div class=\"col\">
	  <div class=\"lesson-card\" style='background-color: #63635f;'>
	  <div style='margin: 35px;'>
			<h1>".$lesson."</h1>
			<h3>".$teacher."</h3>
			<h4>".$time."</h4>
	  </div>
		<div class=\"bottom\">
		  <div class=\"wrapper\">
			<ul class=\"forecast\">
			  <a href=\"javascript:;\"><span class=\"lnr lnr-chevron-up go-up\"></span></a>
			  <li>
				<span class=\"date\">".$topic."</span>
			  </li>
			</ul>
		  </div>
		</div>
		<div style='margin: 35px;'>
			    ".$content."
        </div>
	  </div>
	</div>
  </div>
</div>
<!--End single SkyMake Widget-->";
    }

}

function doublewidget($lesson,$teacher,$time,$topic,$unit,$backgorundimage,$lessonid,$lesson1,$teacher1,$time1,$topic1,$unit1,$backgorundimage1,$lessonid1){
 return "<!--Begin double SkyMake Widgets-->
 <div class=\"container\">
	 <div class=\"row\">
		 <div class=\"col\">
			 <div class=\"lesson-card\" style=\" background: url('".$backgorundimage."') no-repeat;\">
				 <div class=\"top\">
					 <div class=\"wrapper\">
						 <div class=\"mynav\">
							 <a href=\"javascript:;\"><span class=\"lnr lnr-chevron-left\"></span></a>
							 <a href=\"javascript:;\"><span class=\"lnr lnr-cog\"></span></a>
						 </div>
						 <h1 class=\"heading\">".$lesson."</h1>
						 <h3 class=\"location\">".$teacher."</h3>
						 <p class=\"temp\">
							 <span class=\"temp-value\">".$time."</span>
						 </p>
					 </div>
				 </div>
				 <div class=\"bottom\">
					 <div class=\"wrapper\">
						 <ul class=\"forecast\">
							 <a href=\"javascript:;\"><span class=\"lnr lnr-chevron-up go-up\"></span></a>
							 <li class=\"active\">
								 <span class=\"date\">".$lesson."</span>
								 <span class=\"lnr lnr-sun condition\">
									 <span class=\"temp\">".$time."</span>
								 </span>
							 </li>
							 <li>
								 <span class=\"date\">".$topic."</span>
								 <span class=\"lnr lnr-cloud condition\">
									 <span class=\"temp\"><a href='/lesson/".$lessonid."'>"._("Visit lesson")."</a></span>
								 </span>
							 </li>
						 </ul>
					 </div>
				 </div>
			 </div>
		 </div>
			 <div class=\"col\">
				 <div class=\"lesson-card\" style=\" background: url('".$backgorundimage1."') no-repeat;\">
				 <div class=\"top\">
					 <div class=\"wrapper\">
						 <div class=\"mynav\">
							 <a href=\"javascript:;\"><span class=\"lnr lnr-chevron-left\"></span></a>
							 <a href=\"javascript:;\"><span class=\"lnr lnr-cog\"></span></a>
						 </div>
						 <h1 class=\"heading\">".$lesson1."</h1>
						 <h3 class=\"location\">".$teacher1."</h3>
						 <p class=\"temp\">
							 <span class=\"temp-value\">".$time1."</span>
						 </p>
					 </div>
				 </div>
				 <div class=\"bottom\">
					 <div class=\"wrapper\">
						 <ul class=\"forecast\">
							 <a href=\"javascript:;\"><span class=\"lnr lnr-chevron-up go-up\"></span></a>
							 <li class=\"active\">
								 <span class=\"date\">".$lesson1."</span>
								 <span class=\"lnr lnr-sun condition\">
									 <span class=\"temp\">".$time1."</span>
								 </span>
							 </li>
							 <li>
								 <span class=\"date\">".$topic1."</span>
								 <span class=\"lnr lnr-cloud condition\">
									 <span class=\"temp\"><a href='/lesson/".$lessonid1."'>"._("Visit lesson")."</a></span>
								 </span>
								 </li>
							 </ul>
						 </div>
		               </div>
	                 </div>
	               </div>
                  </div>
                 </div>
		   <!--End double SkyMake Widgets-->";
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Skyfallen:SkyMake - Platform</title>
    <link rel="stylesheet" href="/nps/widgets/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/nps/widgets/assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="/nps/widgets/assets/css/<?php if($_SESSION["dm"] == "on"){ echo "dark/"; } ?>Animated-Type-Heading.css">
    <link rel="stylesheet" href="/nps/widgets/assets/css/<?php if($_SESSION["dm"] == "on"){ echo "dark/"; } ?>styles.css">
    <link rel="stylesheet" href="/nps/widgets/assets/css/<?php if($_SESSION["dm"] == "on"){ echo "dark/"; } ?>Widgets.css">
</head>

<body>
    <nav class="navbar navbar-dark navbar-expand-md fixed-top bg-dark">
        <div class="container"><button data-toggle="collapse" class="navbar-toggler" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navcol-1">
                <ul class="nav navbar-nav flex-grow-1 justify-content-between">
                  <?php if($_SESSION["user_role"] == "student" or $_SESSION["user_role"] == "teacher"){ ?>
                    <li class="nav-item" role="presentation"><a class="nav-link" href="/"><img src="<?php if($_SESSION["dm"] == "off"){ echo "/SkyMakeVersionAssets/logo/SkyfallenLogoRB.png"; } else { echo "/SkyMakeVersionAssets/logo/SkyfallenLogoSmallWhiteOnly.png"; } ?>" height="20"></a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" href="/dash"><?= _("Home") ?></a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" href="/results"><?= _("Results") ?></a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" href="/logout"><?= _("Log Out") ?></a></li>
                      <?php if($_SESSION["dm"] == "off") { ?>
                      <li class="nav-item" role="presentation"><a class="nav-link" href="?dm=on"><div class="fa fa-moon-o"></div></a></li>
                          <?php } else { ?>
                      <li class="nav-item" role="presentation"><a class="nav-link" href="?dm=off"><div class="fa fa-sun-o"></div></a></li>
                    <?php } ?>
                    <?php } elseif($_SESSION["user_role"] == "unverified" or !isset($_SESSION["user_role"])){ ?>
                      <li class="nav-item" role="presentation"><a class="nav-link" href="/"><img src="<?php if($_SESSION["dm"] == "off"){ echo "/SkyMakeVersionAssets/logo/SkyfallenLogoSmallWhiteOnly.png"; } else { echo "/SkyMakeVersionAssets/logo/SkyfallenLogoSmallWhiteOnly.png"; } ?>" height="20"></a></li>
                      <li class="nav-item" role="presentation"><a class="nav-link" href="#">SkyMake 4 <br><?php echo THIS_VERSION;?></a></li>
                      <li class="nav-item" role="presentation"><a class="nav-link" href="#"><?= _("Your account is not approved.<br> If you think this is a mistake and the admin should have approved you, <br> Please contact Skyfallen Support after you make sure admin has approved you.") ?></a></li>
                      <li class="nav-item" role="presentation"><a class="nav-link" href="/logout"><?= _("Log Out from<br> SkyMake 4?") ?></a></li>
                      <?php if($_SESSION["dm"] == "off") { ?>
                          <li class="nav-item" role="presentation"><a class="nav-link" href="?dm=on"><div class="fa fa-moon-o"></div></a></li>
                      <?php } else { ?>
                          <li class="nav-item" role="presentation"><a class="nav-link" href="?dm=off"><div class="fa fa-sun-o"></div></a></li>
                      <?php } ?>
                      <?php }else{ ?>
                      <li class="nav-item" role="presentation"><a class="nav-link" href="/"><img src="<?php if($_SESSION["dm"] == "off"){ echo "/SkyMakeVersionAssets/logo/SkyfallenLogoRB.png"; } else { echo "/SkyMakeVersionAssets/logo/SkyfallenLogoSmallWhiteOnly.png"; } ?>" height="20"></a></li>
                      <li class="nav-item" role="presentation"><a class="nav-link" href="/home"><?= _("Home") ?></a></li>
                      <li class="nav-item" role="presentation"><a class="nav-link" href="/users"><?= _("Users") ?></a></li>
                      <li class="nav-item" role="presentation"><a class="nav-link" href="/groups"><?= _("Classes") ?></a></li>
                      <li class="nav-item" role="presentation"><a class="nav-link" href="/results"><?= _("Results") ?></a></li>
                      <li class="nav-item" role="presentation"><a class="nav-link" href="/upload"><?= _("Upload") ?></a></li>
                      <li class="nav-item" role="presentation"><a class="nav-link" href="/examcreate"><?= _("Create an Exam") ?></a></li>
                      <li class="nav-item" role="presentation"><a class="nav-link" href="/courses"><?= _("Courses and Lesson Contents") ?></a></li>
                      <li class="nav-item" role="presentation"><a class="nav-link" href="/logout"><?= _("Log Out") ?></a></li>
                      <?php if($_SESSION["dm"] == "off") { ?>
                          <li class="nav-item" role="presentation"><a class="nav-link" href="?dm=on"><div class="fa fa-moon-o"></div></a></li>
                      <?php } else { ?>
                          <li class="nav-item" role="presentation"><a class="nav-link" href="?dm=off"><div class="fa fa-sun-o"></div></a></li>
                      <?php } ?>
                    <?php } //<li class="nav-item" role="presentation"><a class="nav-link" href="/search"><i class="fa fa-search"></i></a></li>
                    ?>
                    <li class="nav-item" role="presentation"></li>
                </ul>
            </div>
        </div>
    </nav>
    <style>.lesson-content{
            border:1px solid gray;
            border-radius: 5px;
            padding:8px;
            text-align:center;
        }
        .footercustom{
            position: fixed;
            left: 0;
            bottom: 0;
            width: 100%;
            background-color: lightgray;
            color: white;
            text-align: center;
            padding: 3px;
        }
    </style>
