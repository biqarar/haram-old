<?php 
class query_findListCertification_cls extends query_cls {

	private $classification_id = false;

	public function config($usersid = false) {

		//------------ check branch
		$this->sql(".branch.users", $usersid);

		$certification = $this->sql()->tableClassification();
		if($this->classification_id) {
			$certification->whereId($this->classification_id);
		}else{
			$certification->whereUsers_id($usersid);
		}
		$certification->condition("and", "certification.classification_id", "IS", "NULL")
			->fieldId()->fieldMark();
		$certification->joinClasses()->whereId("#classification.classes_id")->fieldId("classesid");
		$certification->joinPlan()->whereId("#classes.plan_id")
			->condition("and", "##classification.mark", ">=" , "#plan.mark")
			->fieldName("planname")->fieldId("planid");
		$certification->joinCertification("LEFT OUTER")
			->whereClassification_id("=" , "#classification.id");
			// var_dump($certification->select()->assoc());exit();
		$ready = $certification->select()->allAssoc();

		$duplicate = $this->sql()->tableCertification();
		$duplicate->joinClassification()->whereId("#certification.classification_id")->andUsers_id($usersid);
		$duplicate->joinClasses()->whereId("#classification.classes_id");
		$q = $duplicate->joinPlan()->whereId("#classes.plan_id");
		foreach ($ready as $key => $value) {
			$q->andId("=", $value['planid']);
		}
		// echo $duplicate->select()->string();exit();
		$duplicate = $duplicate->select()->num();
		// var_dump($duplicate);exit();
		return ($duplicate == 0) ? $ready : array();
		
	}

	function classes($classification_id = false) {
		$this->classification_id = $classification_id;
		return $this->config();
	}
}
?>
