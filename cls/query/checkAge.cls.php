<?php
class query_checkAge_cls extends query_cls
{

	public function config($_user_id, $_classes_id)
	{
		if(!$_user_id || !$_classes_id || !is_numeric($_user_id) || !is_numeric($_classes_id))
		{
			//return false; !!! must be save the classification
			return true;
		}

		$query = "SELECT person.birthday AS `birthday` FROM person WHERE person.users_id = $_user_id LIMIT 1";

		$user_birth_date = $this->db($query)->assoc('birthday');

		if(!$user_birth_date)
		{
			return true;
		}

		$age_title = null;
		$age = age::get_age($user_birth_date);
		if(isset($age))
		{
			$age_title = age::get_range_title($age);
		}
		else
		{
			return true;
		}

		if(!$age_title)
		{
			return true;
		}

		$query = "SELECT classes.age_range AS `age_range` FROM classes WHERE classes.id = $_classes_id LIMIT 1";

		$classes_age_range = $this->db($query)->assoc('age_range');

		if(!$classes_age_range)
		{
			return true;
		}

		if($classes_age_range === $age_title)
		{
			return true;
		}
		else
		{
			if(global_cls::superperson())
			{
				return true;
			}
			else
			{
				return false;
			}
		}
	}
}


/**
 * Class for age.
 */
class age
{

	/**
	 * get birthday and return age
	 *
	 * @param      <type>  $_brithday  The brithday
	 */
    public static function get_age($_brithday)
    {
        if($_brithday == null)
        {
            return null;
        }

        $brith_year  = date("Y", strtotime($_brithday));
        $brith_month = date("m", strtotime($_brithday));
        $brith_day   = date("d", strtotime($_brithday));
        // to convert the jalali date to gregorian date
        if(intval($brith_year) > 1300 && intval($brith_year) < 1400)
        {
            list($brith_year, $brith_month, $brith_day) = jTime_lib::toGregorian($brith_year, $brith_month, $brith_day);
            if($brith_month < 10)
            {
                $brith_month = "0". $brith_month;
            }
            if($brith_day < 10)
            {
                $brith_day = "0". $brith_day;
            }
        }
        // get date diff
        $date1 = new \DateTime($brith_year. $brith_month. $brith_day);
        $date2 = new \DateTime("now");
        $age   = $date1->diff($date2);
        $age   = $age->y;
		return $age;
    }


    /**
     * Gets the range.
     *
     * @param      integer  $_age   The age
     *
     * @return     string   The range.
     */
    public static function get_range($_age)
    {
        $range = null;
        $_age = intval($_age);

        switch ($_age) {
            case $_age <= 13 :
                $range = '-13';
                break;

            case $_age >= 14 && $_age <= 17 :
                $range = '14-17';
                break;

            case $_age >= 18 && $_age <= 24 :
                $range = '18-24';
                break;

            case $_age >= 25 && $_age <= 30 :
                $range = '25-30';
                break;

            case $_age >= 31 && $_age < 44 :
                $range = '31-44';
                break;

            case $_age >= 45 && $_age <= 59 :
                $range = '45-59';
                break;

            case $_age >= 60:
                $range = '60+';
                break;
        }
        return $range;
    }


    /**
     * Gets the range title.
     *
     * @param      integer  $_age   The age
     *
     * @return     string   The range title.
     */
    public static function get_range_title($_age)
    {
    	// 'child', 'teen', 'young', 'adult'
        $range_title = null;
        $_age = intval($_age);

        switch ($_age)
        {
            // case $_age <= 10 :
            case $_age >= 8 && $_age <= 12 :
                $range_title = 'child';
                break;

            case $_age >= 13 && $_age <= 18 :
                $range_title = 'teen';
                break;

            case $_age >= 19 && $_age <= 25 :
                $range_title = 'young';
                break;

            case $_age >= 26:
                $range_title = 'adult';
                break;

        }
        return $range_title;
    }
}
?>