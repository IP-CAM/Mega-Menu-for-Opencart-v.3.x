<?php
class ControllerExtensionModuleMegaMenuNik extends Controller {
	public function index() {
		$this->load->language('extension/module/mega_menu_nik');

        $this->load->model('setting/setting');
        $this->load->model('extension/module/mega_menu_nik');
        $this->load->model('catalog/category');

        $data = $this->model_setting_setting->getSetting('module_mega_menu_nik');

//        echo "<pre>";
        foreach ($data['module_mega_menu_nik_categories'] as $k => $category) {
            $category_info = $this->model_catalog_category->getCategory($category['category']);
            $category_info['childs'] = $this->model_extension_module_mega_menu_nik->getCategoryChilds($category_info['category_id']);
            foreach ($category_info['childs'] as $kk => $child) {
                $category_child_info = $this->model_extension_module_mega_menu_nik->getCategoryChilds($child['category_id']);
                $category_info['childs'][$kk]['childs'] = $category_child_info;
            }
            $data['module_mega_menu_nik_categories'][$k]['category'] = $category_info;
        }
//        var_dump($data['module_mega_menu_nik_categories']);
//        echo "</pre>";


		return $this->load->view('extension/module/mega_menu_nik', $data);
	}
}