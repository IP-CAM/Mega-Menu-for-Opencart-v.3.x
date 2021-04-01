<?php
class ModelExtensionModuleMegaMenuNik extends Model {
	public function getCategoryChilds($category_id) {
		
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category` c LEFT JOIN `" . DB_PREFIX . "category_description` cd ON (c.category_id = cd.category_id) WHERE c.parent_id = '" . (int)$category_id . "'");
		
		return $query->rows;
		
	}
}