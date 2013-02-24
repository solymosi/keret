<?php

	class Template
	{
	
		// A renderelendő template neve
		protected $file = null;
		
		// A template-nek megadott paraméterek
		protected $params = array();
		
		// Konstruktor - itt kell megadni a nevet és a paramétereket, amit aztán eltárolunk az osztályban
		// Használat #1:  $v = new Template("index");
		// Használat #2:  $v = new Template("index", array("title" => "Főoldal", "akarmi" => "jöhet ide"));
		public function __construct($file, $params = array())
		{
			$this->file = $file;
			$this->setParams($params);
		}
		
		// Beállít egy paramétert
		// Használat:           $v->set("title", "Legfőbb oldal");
		// A template-n belül:  $this->set("title", "Legfőbb oldal");
		public function set($key, $value)
		{
			$this->params[$key] = $value;
		}
		
		// Speciális PHP "magic" függvény, ami automatikusan a $this->set-et hívja meg, amikor egy változót beállítunk.
		// Hosszú írásmód:  $this->set("valtozo", "ertek");
		// Magic írásmód:   $this->valtozo = "ertek";
		public function __set($key, $value)
		{
			$this->set($key, $value);
		}
		
		// Lekér egy paramétert (a template-n belül is használható)
		// Használat:           print $v->get("title");
		// A template-n belül:  print $this->get("title");
		public function get($key)
		{
			return isset($this->params[$key]) ? $this->params[$key] : null;
		}
		
		// Speciális PHP "magic" függvény, ami automatikusan a $this->get-et hívja meg, amikor egy változót kiolvasunk.
		// Hosszú írásmód:  print $this->get("valtozo");
		// Magic írásmód:   print $this->valtozo;
		public function __get($key)
		{
			return $this->get($key);
		}
		
		public function __isset($key)
		{
			return isset($this->params[$key]);
		}
		
		// Lecseréli a paramétereket a megadott tömbre
		public function setParams($params)
		{
			if(!is_array($params))
			{
				throw new Exception("The variable passed to setParams() must be an array");
			}
			$this->params = $params;
		}
		
		// Visszaadja az összes paramétert
		public function getParams()
		{
			return $this->params;
		}
		
		// Betölti a beállított template fájlt, lefuttatja, és a generált kimenetet visszaadja egy változóban
		public function getContent()
		{
			ob_start();
			include("templates/" . $this->file . ".php");
			$content = ob_get_contents();
			ob_end_clean();
			return $content;
		}
		
		// Ugyanúgy renderel egy template-t mint a View::render(), azzal a különbséggel, hogy az aktuális template-ben lévő
		// paraméterek is elérhetők lesznek. Ideális arra, hogy egy template-n belül beszúrjunk egy másikat.
		// A View osztályon meghívva:           View::render("tablazat", array("rows" => 2));
		//                                          => elérhető paraméterek a tablazat-on belül: rows = 2
		// Az aktuális osztályból meghívva:     $this->insert("tablazat", array("rows => 2));
		//                                          => elérhető paraméterek a tablazat-on belül: rows = 2, title = "Főoldal"
		public function insert($file, $params = array())
		{
			View::renderWithoutLayout($file, array_merge($this->params, $params));
		}
	}

?>