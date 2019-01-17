<?php

/**
 * 封装CI_Loader
 *
 * @author 潘伟麟 <249865225@qq.com>
 */
class MyLoader extends CI_Loader
{
    protected $_ci_logics      = [];
    protected $_ci_logic_paths = [APPPATH];

    protected $_ci_services      = [];
    protected $_ci_service_paths = [APPPATH];

    protected $_ci_varmap = [];

    public function __construct()
    {
        parent::__construct();
        load_class('Logic', 'core');
        load_class('Service', 'core');
    }

    public function logic($logic, $name = '')
    {
        $path = '';
        // Is the logic in a sub-folder? If so, parse out the filename and path.
        if (($last_slash = strrpos($logic, '/')) !== false) {
            // The path is in front of the last slash
            $path = substr($logic, 0, ++$last_slash);

            // And the logic name behind it
            $logic = substr($logic, $last_slash);
        }

        if (!$name) {
            $name = $logic;
        }

        if (in_array($name, $this->_ci_logics, true)) {
            return $this;
        }

        $CI = &get_instance();
        if (isset($CI->$name)) {
            throw new RuntimeException('The logic name you are loading is the name of a resource that is already being used: ' . $name);
        }

        $base_logic = config_item('subclass_prefix') . 'Logic';
        if (!class_exists($base_logic, false)) {
            $app_path = APPPATH . 'core' . DS;
            if (file_exists($app_path . $base_logic . '.php')) {
                require_once $app_path . $base_logic . '.php';
                if (!class_exists($base_logic, false)) {
                    throw new RuntimeException($app_path . $base_logic . ".php exists, but doesn't declare class " . $base_logic);
                }
            }
        }

        $logic = ucfirst($logic);
        if (!class_exists($logic, false)) {
            foreach ($this->_ci_model_paths as $mod_path) {
                if (!file_exists($mod_path . 'logics/' . $path . $logic . '.php')) {
                    continue;
                }

                require_once $mod_path . 'logics/' . $path . $logic . '.php';
                if (!class_exists($logic, false)) {
                    throw new RuntimeException($mod_path . 'logics/' . $path . $logic . ".php exists, but doesn't declare class " . $logic);
                }

                break;
            }

            if (!class_exists($logic, false)) {
                throw new RuntimeException('Unable to locate the logic you have specified: ' . $logic);
            }
        } elseif (!is_subclass_of($logic, $base_logic)) {
            throw new RuntimeException('Class ' . $logic . " already exists and doesn't extend " . $base_logic);
        }

        $this->_ci_logics[] = $name;
        $CI->$name          = new $logic();

        return $this;
    }

    public function service($service, $name = '')
    {
        $path = '';
        // Is the service in a sub-folder? If so, parse out the filename and path.
        if (($last_slash = strrpos($service, '/')) !== false) {
            // The path is in front of the last slash
            $path = substr($service, 0, ++$last_slash);

            // And the service name behind it
            $service = substr($service, $last_slash);
        }

        if (!$name) {
            $name = $service;
        }

        if (in_array($name, $this->_ci_services, true)) {
            return $this;
        }

        $CI = &get_instance();
        if (isset($CI->$name)) {
            throw new RuntimeException('The service name you are loading is the name of a resource that is already being used: ' . $name);
        }

        $base_service = config_item('subclass_prefix') . 'Service';
        if (!class_exists($base_service, false)) {
            $app_path = APPPATH . 'core' . DS;
            if (file_exists($app_path . $base_service . '.php')) {
                require_once $app_path . $base_service . '.php';
                if (!class_exists($base_service, false)) {
                    throw new RuntimeException($app_path . $base_service . ".php exists, but doesn't declare class " . $base_service);
                }
            }
        }

        $service = ucfirst($service);
        if (!class_exists($service, false)) {
            foreach ($this->_ci_model_paths as $mod_path) {
                if (!file_exists($mod_path . 'services/' . $path . $service . '.php')) {
                    continue;
                }

                require_once $mod_path . 'services/' . $path . $service . '.php';
                if (!class_exists($service, false)) {
                    throw new RuntimeException($mod_path . 'services/' . $path . $service . ".php exists, but doesn't declare class " . $service);
                }

                break;
            }

            if (!class_exists($service, false)) {
                throw new RuntimeException('Unable to locate the service you have specified: ' . $service);
            }
        } elseif (!is_subclass_of($service, $base_service)) {
            throw new RuntimeException('Class ' . $service . " already exists and doesn't extend " . $base_service);
        }

        $this->_ci_services[] = $name;
        $CI->$name            = new $service();

        return $this;
    }

    protected function _ci_load($_ci_data)
    {
        // Set the default data variables
        foreach (['_ci_view', '_ci_vars', '_ci_path', '_ci_return'] as $_ci_val) {
            $$_ci_val = isset($_ci_data[$_ci_val]) ? $_ci_data[$_ci_val] : false;
        }

        $file_exists = false;

        // Set the path to the requested file
        if (is_string($_ci_path) && $_ci_path !== '') {
            $_ci_x    = explode('/', $_ci_path);
            $_ci_file = end($_ci_x);
        } else {
            $_ci_ext  = pathinfo($_ci_view, PATHINFO_EXTENSION);
            $_ci_file = ($_ci_ext === '') ? $_ci_view . '.php' : $_ci_view;

            foreach ($this->_ci_view_paths as $_ci_view_file => $cascade) {
                if (file_exists($_ci_view_file . $_ci_file)) {
                    $_ci_path    = $_ci_view_file . $_ci_file;
                    $file_exists = true;
                    break;
                }

                if (!$cascade) {
                    break;
                }
            }
        }

        if (!$file_exists && !file_exists($_ci_path)) {
            show_error('Unable to load the requested file: ' . $_ci_file);
        }

        // This allows anything loaded using $this->load (views, files, etc.)
        // to become accessible from within the Controller and Model functions.
        $_ci_CI = &get_instance();
        foreach (get_object_vars($_ci_CI) as $_ci_key => $_ci_var) {
            if (!isset($this->$_ci_key)) {
                $this->$_ci_key = &$_ci_CI->$_ci_key;
            }
        }

        empty($_ci_vars) || $this->_ci_cached_vars = array_merge($this->_ci_cached_vars, $_ci_vars);
        extract($this->_ci_cached_vars);

        // 解析视图
        $template = library('Template');
        $obj_file = $template->parseFile($_ci_path);

        ob_start();

        include $obj_file;

        // log_message('info', 'File loaded: ' . $obj_file);

        // Return the file data if requested
        if ($_ci_return === true) {
            $buffer = ob_get_contents();
            @ob_end_clean();
            return $buffer;
        }

        if (ob_get_level() > $this->_ci_ob_level + 1) {
            ob_end_flush();
        } else {
            $_ci_CI->output->append_output(ob_get_contents());
            @ob_end_clean();
        }

        return $this;
    }
}
