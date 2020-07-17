<?php 
function singlewidget($lesson,$teacher,$time,$topic,$unit,$backgorundimage){
return "<!--End single SkyMake Widget-->
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
				  <span class=\"temp\">Unit <span class=\"temp-type\">".$unit."</span></span>
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
function doublewidget($lesson,$teacher,$time,$topic,$unit,$backgorundimage,$lesson1,$teacher1,$time1,$topic1,$unit1,$backgorundimage1){
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
									 <span class=\"temp\">Unit <span class=\"temp-type\">".$unit."</span></span>
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
									 <span class=\"temp\">Unit <span class=\"temp-type\">".$unit1."</span></span>
								 </span>
								 </li>
							 </ul>
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
    <link rel="stylesheet" href="nps/widgets/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="nps/widgets/assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="nps/widgets/assets/css/Animated-Type-Heading.css">
    <link rel="stylesheet" href="nps/widgets/assets/css/styles.css">
    <link rel="stylesheet" href="nps/widgets/assets/css/Widgets.css">
</head>

<body>
    <nav class="navbar navbar-dark navbar-expand-md fixed-top bg-dark">
        <div class="container"><button data-toggle="collapse" class="navbar-toggler" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navcol-1">
                <ul class="nav navbar-nav flex-grow-1 justify-content-between">
                    <li class="nav-item" role="presentation"><a class="nav-link" href="#"><img src="nps/widgets/assets/img/SkyfallenLogoSmallWhiteOnly.png" height="20"></a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" href="/course">Courses</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" href="/dash">Dashboard</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" href="/oes">Online Examination</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" href="/liveclass">Live Class</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" href="/grades">My Grades</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" href="/report">Report</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" href="/profile">My Profile</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" href="/search"><i class="fa fa-search"></i></a></li>
                    <li class="nav-item" role="presentation"></li>
                </ul>
            </div>
        </div>
    </nav>				<div class="caption v-middle text-center">
					<h1 class="cd-headline clip">
			            <span class="blc">Welcome to the new dashboard, <?php echo($_SESSION["username"]);?></span><br>
			            <span class="cd-words-wrapper">
			              <b class="is-visible">Here are your courses.</b>
			              <b>Here are your grades.</b>
			              <b>Here are your online exams.</b>
			            </span>
	          		</h1>
				</div>
				  <?php  //echo(doublewidget("Geography","The Teacher","18.00","Earth","9","https://kaleela.com/wp-content/uploads/2019/08/Geography-Terms-In-Arabic-Language.jpg","Maths","Your Teacher","19.00","Multiplication","5","https://d2r55xnwy6nx47.cloudfront.net/uploads/2019/09/Multiplication_1200_Social.jpg"));
				  ?>
				  <?php  //echo(singlewidget("Maths","Your Teacher","19.00","Multiplication","5","https://d2r55xnwy6nx47.cloudfront.net/uploads/2019/09/Multiplication_1200_Social.jpg"));
				  ?>
    <script src="nps/widgets/assets/js/jquery.min.js"></script>
    <script src="nps/widgets/assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="nps/widgets/assets/js/Animated-Type-Heading.js"></script>
</body>

</html>
