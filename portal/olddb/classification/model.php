<?php 
/**
* 
*/
class model extends main_model
{
	public function sql_oldclasses_detail($oldclasses) {
		//------------------- check branch
		$this->sql(".branch.olddb", "oldclasses", $this->xuId());
		$detail = $this->db("SELECT * FROM `quran_hadith_old`.`oldclasses` WHERE `code` LIKE '$oldclasses' LIMIT 0,1");

		$detail = $detail->assoc();
		
		return $detail['dore'] . " , " . $detail['maqta'] . " , " . $detail['teacher'];

	}
}
?>