<?php
	/*  $type:
		0 - null
		1 - white
		2 - black
		3 - d white
		4 - d black
	*/
			
	class Chessboard {
		
		public $map;
		
		public function __construct($map) {
			$this->map = $map;
		}
		
		public static function generateMap() {
			$chessboard = [];
			for ($y = 0; $y < 8; $y++) array_push($chessboard, []);
			
			for ($x = 0; $x < 8; $x++) {
				for ($y = 0; $y < 8; $y++) $chessboard[$x][$y] = 0;
			}
			
			$type = true;
			for ($x = 0; $x < count($chessboard); $x++) {
				for ($y = 0; $y < 3; $y++) {
					if ($type) $chessboard[$x][$y] = 0;
					else $chessboard[$x][$y] = 1;
					
					$type = !$type;
					
					if ($type) $chessboard[$x][$y + 5] = 0;
					else $chessboard[$x][$y + 5] = 2;
				}
			}
			
			return $chessboard;
		}

		public function countBlack() {
			$black = 0;
			foreach ($this->map as $mapRow) {
				foreach ($mapRow as $pawn) {
					if ($pawn == 2) $black++;
				}
			}
			return $black;
		}

		public function countWhite() {
			$white = 0;
			foreach ($this->map as $mapRow) {
				foreach ($mapRow as $pawn) {
					if ($pawn == 1) $white++;
				}
			}
			return $white;
		}
		
		public function setPawn($x, $y, $type) {
			if ($type == 2) {
				if ($y == 0) {
					for ($yy = 5; $yy < 8; $yy++) {
						for ($xx = 0; $xx < 8; $xx+=2) {
							if ($xx == 0 && $yy % 2 == 0) $xx++;
							if ($this->map[$xx][$yy] == 0) {
								$this->map[$xx][$yy] = $type;
								$yy = 9;
								break;
							}
						}
					}
				} else $this->map[$x][$y] = $type;
			} else if ($type == 1) {
				if ($y == 7) {
					for ($yy = 0; $yy < 3; $yy++) {
						for ($xx = 0; $xx < 8; $xx+=2) {
							if ($xx == 0 && $yy % 2 == 0) $xx++;
							if ($this->map[$xx][$yy] == 0) {
								$this->map[$xx][$yy] = $type;
								$yy = 9;
								break;
							}
						}
					}
				} else $this->map[$x][$y] = $type;
			} else if ($type == 0)  $this->map[$x][$y] = $type;
		}
		
		public function showMapJSON_PRETTY() {
			echo "<pre>".json_encode($this->map, JSON_PRETTY_PRINT)."</pre>";
		}
		
		public function showMapJSON() {
			return json_encode($this->map);
		}
		
		public function showMapRAW() {
			return $this->map;
		}
		
		public function renderMap() {
			echo "<pre>";
			for ($x = 0; $x < count($this->map); $x++) {
				for ($y = 0; $y < count($this->map[0]); $y++) {
					echo $this->map[$y][$x];
				}
				echo "<br>";
			}
			echo "</pre>";
		}
	}
?>