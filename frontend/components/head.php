<head>
	<meta charset="UTF-8">
	<meta name=description content="Seime.lt aiškiai, patogiai ir patraukliai atvaizduoja svarbiausią su Seimo nariais susijusią statistiką: jų lankomumą bei balsavimo tendencijas." />
	<meta name=keywords content="Seimas, LRS, Seimo nariai, Lietuvos Respublikos seimas, statistika, balsavimas, lankomumas" />
	<meta name=author content="Ernesta ir Aurimas" />
	<base href="<?php echo Initialisator::getBaseURL(); ?>" />

	<title><?php echo getPageTitle(); ?></title>

	<link rel="shortcut icon" href="favicon.ico" />
	<link rel="icon" type="image/png" href="frontend/resources/images/favicons/favicon.png" />
	<link rel="apple-touch-icon" href="frontend/resources/images/favicons/favicon-apple-57.png" />
	<link rel="apple-touch-icon" sizes="72x72" href="frontend/resources/images/favicons/favicon-apple-72.png" />
	<link rel="apple-touch-icon" sizes="114x114" href="frontend/resources/images/favicons/favicon-apple-114.png" />

	<meta property="og:title" content="<?php echo getPageTitle(); ?>" />
	<meta property="og:type" content="<?php echo getPageType(); ?>" />
	<meta property="og:url" content="<?php echo getCurrentURL(); ?>" />
	<meta property="og:description" content="Seime.lt aiškiai, patogiai ir patraukliai atvaizduoja svarbiausią su Seimo nariais susijusią statistiką: jų lankomumą bei balsavimo tendencijas." />
	<meta property="og:image" content="<?php echo getPageImage(); ?>" />
	<meta property="fb:admins" content="833735472,516139298" />
	
	<meta itemprop="name" content="Seime.lt">
	<meta itemprop="description" content="Seime.lt aiškiai, patogiai ir patraukliai atvaizduoja svarbiausią su Seimo nariais susijusią statistiką: jų lankomumą bei balsavimo tendencijas.">
	<meta itemprop="image" content="http://seime.lt/images/seime.png">
	
	<link rel="stylesheet" type="text/css" href="frontend/resources/style.css?ver=<?php echo date('Y-m-d', filemtime(dirname(__FILE__) . '/style.css')); ?>" />
	
	<!-- jQuery -->
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
	<!-- Chosen -->
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.6.2/chosen.jquery.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.6.2/chosen.min.css" />
	<!-- QuickSearch -->
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.quicksearch/2.3.0/jquery.quicksearch.min.js"></script>
	<!-- Fancybox -->
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css" />
	<!-- Tangerine -->
	<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Tangerine" >
	
	<!--<script type="text/javascript" src="frontend/resources/js/highcharts.js"></script>-->
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/highcharts/4.2.6/highcharts.js"></script>
	<script type="text/javascript" src="frontend/resources/js/charts.js"></script>
	<script type="text/javascript" src="frontend/resources/js/charts/intro-charts.js"></script>
	<script type="text/javascript" src="frontend/resources/js/charts/intro-sitting.js"></script>
	<script type="text/javascript" src="frontend/resources/js/charts/stats-attendance.js"></script>
	<script type="text/javascript" src="frontend/resources/js/charts/full-attendance.js"></script>

	<!--[if lt IE 9]>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
	<![endif]-->

	<script type="text/javascript">
		var _gaq = _gaq || [];
		_gaq.push(["_setAccount", "UA-19315846-11"]);
		_gaq.push(["_trackPageview"]);

		(function() {
			var ga = document.createElement("script");
			ga.type = "text/javascript";
			ga.async = true;
			ga.src = ("https:" == document.location.protocol ? "https://ssl" : "http://www") + ".google-analytics.com/ga.js";

			var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(ga, s);
		})();
	</script>
</head>