<?php
/**
* @author reza mohiti rm.biqarar@gmail.com
*/
class model extends main_model
{
	public function sql_history($_table, $_id)
	{
		$result = [];
		$query =
		"
			SELECT
				quran_hadith_log.history.*,
				quran_hadith.person.name,
				quran_hadith.person.family,
				quran_hadith.person.father

			FROM
				quran_hadith_log.history
			LEFT JOIN quran_hadith.person ON quran_hadith.person.users_id = quran_hadith_log.history.users_id
			WHERE quran_hadith_log.history.table = '$_table'
			AND quran_hadith_log.history.record_id = '$_id'

		";
		$result['history'] = $this->db($query)->allAssoc();
		$where = null;
		if($_table === 'class')
		{
			$_table = 'classes';
			$where = " AND quran_hadith_log.update_log.field <> 'count' ";
		}

		$query =
		"
			SELECT
				quran_hadith_log.update_log.*,
				quran_hadith.person.name,
				quran_hadith.person.family,
				quran_hadith.person.father

			FROM
				quran_hadith_log.update_log
			LEFT JOIN quran_hadith.person ON quran_hadith.person.users_id = quran_hadith_log.update_log.users_id
			WHERE quran_hadith_log.update_log.table = '$_table'
			AND quran_hadith_log.update_log.record_id = '$_id' $where
		";
// var_dump($query);exit();
		$result['update'] = $this->db($query)->allAssoc();



		$query =
		"
			SELECT
				quran_hadith_log.trash.*,
				quran_hadith.person.name,
				quran_hadith.person.family,
				quran_hadith.person.father

			FROM
				quran_hadith_log.trash
			LEFT JOIN quran_hadith.person ON quran_hadith.person.users_id = quran_hadith_log.trash.users_id
			WHERE quran_hadith_log.trash.tables = '$_table'
			AND quran_hadith_log.trash.record_id = '$_id'
		";

		$result['trash'] = $this->db($query)->allAssoc();

		return $result;
	}
}
?>