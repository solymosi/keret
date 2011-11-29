<?php

	require_once("include/all.php");
	
	// Ha az URI nem tartalmazza az index.php-t, akkor átirányítunk oda (a .htaccess korlátozások miatt szükséges)
	Helpers::ensureCorrectBaseUri();
	
	// Kimeneti gyorsítótár engedélyezése (nem küldi ki azonnal a print-elt szövegeket a böngészőnek, hanem csak akkor, amikor mi mondjuk neki)
	ob_start();
	
	// Az egész folyamatot hibakezeléssel vesszük körbe
	try
	{
		// Kapcsolódunk az adatbázishoz
		DB::connect();
		
		// Inicializáljuk a session-t
		Session::initialize();
		
		// Ha egyikkel sem egyezett, az oldal nem található...
		Helpers::render("notFound");
	}
	catch(ProcessingFinished $e)
	{
		// A futtatott controller befejezte a futását, így kilépünk mindenből
	}
	catch(Exception $e)
	{
		// Valamilyen hiba történt, elvetünk minden eddigi kiprintelt tartalmat
		ob_clean();
		
		// Megjelenítjük a hiba oldalt
		require_once("include/templates/errorMessage.php");
	}

	// Most már kiküldhetjük a gyorsítótárban összegyűlt tartalmat a böngészőnek
	ob_end_flush();

?>