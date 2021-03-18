<?php
defined("BASEPATH") OR die("No direct access allowed");

require_once __DIR__ . "/../core/EngineCompiler/EchoCompiler.php";
require_once __DIR__ . "/../core/EngineCompiler/PhpTagCompiler.php";
require_once __DIR__ . "/../core/EngineCompiler/MugenCompiler.php";

class Mugen
{

    use EchoCompiler, PhpTagCompiler, MugenCompiler;

    /**
     * get the view object
     *
     * @param string $view
     * @param array $params
     * @return void
     */
    public function view(string $view, array $params = [])
    {
        $_viewFile = VIEWPATH . "/" . $view . ".ts.php";
        
        /**
         * load key of array as a variable
         */
        if ( !empty($params) ) {
            foreach ($params as $key => $value) {
                ${$key} = $value;
            }
        }

        if ( file_exists($_viewFile) ) {

            // This allows anything loaded using $this->load (views, files, etc.)
            // to become accessible from within the Controller and Model functions.
            $_ci_CI = &get_instance();
            foreach (get_object_vars($_ci_CI) as $_ci_key => $_ci_var) {
                if (!isset($this->$_ci_key)) {
                    $this->$_ci_key = &$_ci_CI->$_ci_key;
                }
            }

            // load content
            $content = file_get_contents($_viewFile);

            // parse view
            $content = $this->render($content);

            echo eval("?>" . $content . "<?php");
        } else {
            show_error("Unable to load the requested file: " . $_viewFile, 404, "404 - File Not Found");
        }
    }

    /**
     * render function
     *
     * @param string $content
     * @return void
     */
    protected function render($content)
    {
        // method loader
        $methods = [
            "CommentCompiler",
            "echoWithEscape",
            "echoWithoutEscape",
            "phpTag",
            "compileStatements",
            "compileConditionalLooping",
            "functionViewCompiler",
        ];

        foreach ($methods as $method) {
            $content = $this->{$method}($content);
        }

        return $content;
    }

}