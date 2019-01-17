<?php

class MySmarty extends Smarty
{
    public function __construct()
    {
        parent::__construct();

        $smary_dir = APPPATH . 'views' . DS . 'smarty' . DS;

        $this->setConfigDir($smary_dir . 'config' . DS);
        $this->setCacheDir($smary_dir . 'cache' . DS);
        $this->setCompileDir($smary_dir . 'compile' . DS);
        $this->setForceCompile(ENVIRONMENT != 'production');
        $this->setTemplateDir(APPPATH . 'views');
        $this->left_delimiter  = '{<';
        $this->right_delimiter = '>}';

        // $this->testInstall();
        // $this->debugging = true;
    }
}
