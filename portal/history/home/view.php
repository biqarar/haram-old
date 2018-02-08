<?php
/**
* @author reza mohiti rm.biqarar@gmail.com
*/
class view extends main_view {

	public function config() {
		//------------------------------ global
		$this->global->page_title='تاریخچه';
		$table = $this->xuId('table');
		$id = $this->xuId('id');
		if($table && $id)
		{
			$x = $this->sql("#history", $table, $id);
			foreach ($x as $key => $value)
			{
				$temp = [];
				$temp['list'] = $x[$key];
				if(isset($value[0]))
				{
					$temp['header'] = array_map(function($x){return _($x);}, array_keys($value[0]));
				}
				$x[$key] = $temp;

			}
			$this->data->history = $x;
		}
		// var_dump($x);exit();
	}
}
?>