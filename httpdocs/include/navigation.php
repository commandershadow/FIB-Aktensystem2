<nav class="cf">
	<ul id="nav" class="sf-menu">
		<li><a href="home.php?p=start">Home</a>
		</li>
		<li><a href="<?php echo BASE_URL; ?>logout.php">Logout</a>
		</li> 
<br><br>
			
        
        <?php if ($userDetails->rang >= 1){ ?>
		<li>
			<a href="#">B&uuml;rgerdatenbank</a>

			<ul class="sub-menu">
				<li><a href="home.php?p=add_buerger">B&uuml;rger eintragen</a>
				</li>
				<li><a href="home.php?p=add_kennz">Kennzeichen eintragen</a>
				</li>
			</ul>
		</li>
		<? } ?>
		<?php if ($userDetails->rang >= 3){ ?>
				<li>
			<a href="#">Fallakten</a>

			<ul class="sub-menu">
				<li><a href="home.php?p=add_fallakte">Fallakte anlegen</a>
				</li>
				<li><a href="home.php?p=fallakten_liste">Auflistung Fallakten</a>
				</li>
				<li><a href="home.php?p=fallakten_archiv">Fallaktenarchiv</a>
				</li>
			</ul>
		</li>
		<? } ?>
		
		<?php if ($userDetails->rang >= 2){ ?>
		<li>
			<a href="home.php?p=search_pers">Suchen</a>
		</li>
		<? } ?>
		<?php if ($userDetails->rang >= 1){ ?>
		<li>
			<a href="#">Anzeigen</a>

			<ul class="sub-menu">
				<li>
				<a href="home.php?p=karte">&Uuml;bersichtskarte</a>
				</li>
					<?php if ($userDetails->rang >= 4){ ?>
				<li><a href="home.php?p=grunfr_liste">Organisationen</a>
				</li>
				<li><a href="home.php?p=list_buerger">B&uuml;rgerliste</a>
				</li>
				<li><a href="home.php?p=totenliste_buerger">Totenliste</a>
				</li>
						<? } ?>
			</ul>
		</li>
		
			
		<? }?>
	
			
				<?php if ($userDetails->rang >= 6){ ?>
<li>
			<a href="#">Einstellungen</a>

			<ul class="sub-menu">
			<?php if ($userDetails->rang >= 9){ ?>
				<li><a href="home.php?p=user">Personen mit Zugriff</a>
				</li><br>
				<li><a href="home.php?p=zugriff">Zugang erstellen</a>
				</li><br>
				<br>		<? }
				if($userDetails->rang >= 7) {
				?>
				<li><a href="home.php?p=log">Datenbank Log</a>
				</li>
				<? } ?>				
				<li><a href="home.php?p=add_grfrun">Organisation eintragen</a>
				</li>
				<li><a href="home.php?p=add_fahrz">Fahrzeuge eintragen</a>
				</li>
				</ul>				
		</li>
		<? } ?>
		
		</ul>
</nav>