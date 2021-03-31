<?php
class ModelExtensionModuleMegaMenuNik extends Model {
    public function getMainCategories() {
        $query = $this->db->query("SELECT `category_id` FROM `" . DB_PREFIX . "category` WHERE `parent_id` = '0'");

        return $query->rows;
    }

    public function getCategoryName($category_id) {
        $query = $this->db->query("SELECT `name` FROM `" . DB_PREFIX . "category_description` WHERE `category_id` = '" . (int)$category_id . "'");

        return $query->row;
    }
}
