<div class="clearfix" id="NTAKK">
	<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
	
	<br />
	
	<p>Sed ut <a href="http://">perspiciatis</a> unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?</p>
	
	<br />
	
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
