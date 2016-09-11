<div class="clearfix" id="NTAKK">
	<p>Šiame puslapyje pateikiami Seimo narių įvertinimai alkoholio ir tabako kontrolės srityje. Šie įvertinimai yra ruošiami Nacionalinės Alkoholio ir Tabako Kontrolės Koalicijos (NTAKK).  Išsamią jų vertinimų metodologiją rasi <a href='http://www.ntakk.lt/politiku-reitingai-2012-2016/'>NTAKK svetainėje</a>. Labiausiai už kontrolę pasisakę (t.y. atitinkamai balsavę) Seimo nariai gauna aukščiausius įvertinimus; balsavę priešingai - mažiausius (juos gali lengvai pamatyti rūšiuodamas lentelę žemiau).</p>
	<br />
	<p>NTAKK skelbiamus įvertinimus skelbiame Seime.lt ne todėl, kad manome, jog už alkoholio ar tabako kontrolę balsuoti yra geriau nei atvirkščiai. Tai darome todėl, kad NTAKK renkami duomenys yra unikalūs ir suteikiantys įžvalgų visiems, kad ir kokios yra jų pažiūros šiais klausimais.</p>
<br />
<p>Seime.lt pateikiami įvertinimai šiek tiek skiriasi nuo NTAKK skelbiamų rezultatų. Pirma, dėl techninių priežasčių, Seime.lt svetainėje nėra skaičiuojami alternatyvūs balsavimai (tokie balsavimai sudaro apie 5% visų vertintų balsavimų). Antra, žemiau skelbiami įvertinimai normuojami atsižvelgiant į didžiausią/mažiausią teoriškai galimą įvertinimą (t.y. 10 balų reiškia, kad Seimo narys (-ė) visus kartus pasisakė už kontrolės išlaikymą ar padidinimą). NTAKK skelbiami vertinimai yra nuormuojami atsižvelgiant į kitų Seimo narių įvertinimus (t.y. 10 balų reiškia, kad Seimo narys (-ė) labiau nei visi kiti pasisakė už kontrolės išlaikymą ar padidinimą).</p>
	
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
	<br />
	<p><em>Paskutinis NTAKK vertintas posėdis vyko <?php echo getLastNTAKKUpdate(); ?>.</em></p>
</div>

<script type="text/javascript" src="frontend/resources/js/search.js"></script>
<script type="text/javascript" src="frontend/resources/js/sort.js"></script>
