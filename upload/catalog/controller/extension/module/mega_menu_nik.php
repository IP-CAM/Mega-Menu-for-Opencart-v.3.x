<?php
class ControllerExtensionModuleMegaMenuNik extends Controller {
	public function index() {
		$this->load->language('extension/module/mega_menu_nik');

        $this->load->model('setting/setting');
        $this->load->model('extension/module/mega_menu_nik');
        $this->load->model('catalog/category');

        $data = $this->model_setting_setting->getSetting('module_mega_menu_nik');

//        echo "<pre>";
//        var_dump($data);
        foreach ($data['module_mega_menu_nik_categories'] as $k => $category) {

            $category_info = $this->model_catalog_category->getCategory($category['category']);
            $category_info['childs'] = $this->model_extension_module_mega_menu_nik->getCategoryChilds($category_info['category_id']);
            foreach ($category_info['childs'] as $kk => $child) {
                $category_child_info = $this->model_extension_module_mega_menu_nik->getCategoryChilds($child['category_id']);
                foreach ($category_child_info as $kkk => $category_child_child) {
                    $category_child_info[$kkk]['href'] = $this->url->link('product/category', 'path=' . $category_child_child['category_id']);
                }
                $child['href'] = $this->url->link('product/category', 'path=' . $child['category_id']);
                $category_info['childs'][$kk] = $child;
                $category_info['childs'][$kk]['childs'] = $category_child_info;
            }

            if (!empty($category['modules'])) {
                $modules = array();
                foreach ($category['modules'] as $module) {
                    $part = explode('.', $module);

                    if (isset($part[0]) && $this->config->get('module_' . $part[0] . '_status')) {
                        $module_data = $this->load->controller('extension/module/' . $part[0]);

                        if ($module_data) {
                            $modules[] = $module_data;
                        }
                    }

                    if (isset($part[1])) {
                        $setting_info = $this->model_setting_module->getModule($part[1]);

                        if ($setting_info && $setting_info['status']) {
                            $output = $this->load->controller('extension/module/' . $part[0], $setting_info);

                            if ($output) {
                                $modules[] = $output;
                            }
                        }
                    }
                }

                $category_info['modules'] = $modules;
            }

            $data['module_mega_menu_nik_categories'][$k]['category'] = $category_info;
        }
//        var_dump($data['module_mega_menu_nik_categories']);
//        echo "</pre>";


		return $this->load->view('extension/module/mega_menu_nik', $data);
	}
}