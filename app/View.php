<?php

class View
{
    protected $app;

    const TEMPLATES_PATH = 'app/views/';
    const LAYOUT = "page";
    const DATA_PLACEHOLDER_IN_TEMPLATE = "%%var_name%%";

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function make(string $template_name, array $view_data, $use_layout = true): string
    {
        // Base app URL with trailing /
        $CFG['base_url'] = $this->app->router()::baseUrl();

        // User instance if user is authenticated
        $USER = $this->app->session()->get('user');

        // Flash message
        $msg = $this->app->session()->get('msg');
        $this->app->session()->forget('msg');

        $content_template_file = self::templateFile($template_name);
        if ($use_layout) {
            $template_file = self::templateFile(self::LAYOUT);
        } else {
            $template_file = $content_template_file;
        }

        extract($view_data);
        ob_start();
            include $template_file;
            $HTML = ob_get_contents();
        ob_end_clean();

        return $HTML;
    }

    protected static function templateFile($template)
    {
        $file_name = self::TEMPLATES_PATH . $template . ".php";
        if (!file_exists($file_name)) {
            throw new Exception('Template "' . $template . "' is not defined.");
        }

        return $file_name;
    }
}
