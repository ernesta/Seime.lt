<div class="clearfix" id="NTAKK">
	<?php require_once('frontend/components/search.php'); ?>
	
	<ul id="NTAKKList">
		<li class="listHeader clearfix">
			<div class="member">
				<h3 id="one" data-sorted="1">Seimo narys <span class="glyphicon glyphicon-sort" aria-hidden="true"></span></h3></div>
			<div class="ratings">
				<h3 id="two" data-sorted="0">Alkoholio temų įvertinimas <span class="glyphicon glyphicon-sort" aria-hidden="true"></span></h3>
				<h3 id="three" data-sorted="0">Tabako temų įvertinimas <span class="glyphicon glyphicon-sort" aria-hidden="true"></span></h3>
				<h3 id="four" data-sorted="0">Bendras įvertinimas <span class="glyphicon glyphicon-sort" aria-hidden="true"></span></h3>
			</div>
		</li>
		
		<?php echo getNTAKKList(); ?>
	</ul>
</div>

<script type="text/javascript" src="frontend/resources/js/search.js"></script>
<script type="text/javascript" src="frontend/resources/js/sort.js"></script>
