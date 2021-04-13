<?php
class ControllerExtensionModuleMegaMenuNik extends Controller {
	public function index() {
		$this->load->language('extension/module/mega_menu_nik');

        $this->load->model('setting/setting');
        $this->load->model('extension/module/mega_menu_nik');
        $this->load->model('catalog/category');
        $this->load->model('tool/image');

        $data = $this->model_setting_setting->getSetting('module_mega_menu_nik');

        foreach ($data['module_mega_menu_nik_categories'] as $k => $category) {

            $category_info = $this->model_catalog_category->getCategory($category['category']);
            $category_info['thumb'] = $this->model_tool_image->resize($category_info['image'], 250, 250);
            $category_info['childs'] = $this->model_extension_module_mega_menu_nik->getCategoryChilds($category_info['category_id']);
//            var_dump($category_info);
            foreach ($category_info['childs'] as $kk => $child) {
                $child['href'] = $this->url->link('product/category', 'path=' . $child['category_id']);
                $category_info['childs'][$kk] = $child;
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

		return $this->load->view('extension/module/mega_menu_nik', $data);
	}
}