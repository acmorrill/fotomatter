<?php
class ModelShell extends Shell {
    
    function generate_all() {
        $this->generate_model();
        $this->generate_model_test();
        $this->generate_test_helper();
        $this->generate_validate_test();
    }
    
    function remove_all() {
        $this->remove_model();
        $this->remove_model_test();
        $this->remove_test_helper();
        $this->remove_validate_test();
    }
    
    function generate_model() {
        if (empty($this->args[0])) {
            $this->error("You need to specify a model.");
            exit();
        }
        
        $model_name = $this->args[0];
        $table_name = Inflector::tableize($this->args[0]);
        $file_name = Inflector::singularize($table_name) . '.php';
        
        $full_file_path = ROOT . '/app/models/' . $file_name;
        
        if (is_file($full_file_path)) {
            return;
        }
        
        touch($full_file_path);
        shell_exec("git add $full_file_path");
        
        $file_contents = '';
        
        $file_contents .= "<?php\n";
        $file_contents .= "class $model_name extends AppModel {\n";
        $file_contents .= "}";
        
        file_put_contents($full_file_path, $file_contents);
    }
    
    function remove_model() {
        if (empty($this->args[0])) {
            $this->error("You need to specify a model.");
            exit();
        }
        
        $model_name = $this->args[0];
        $table_name = Inflector::tableize($this->args[0]);
        $file_name = Inflector::singularize($table_name) . '.php';
        
        $full_file_path = ROOT . '/app/models/' . $file_name;
        shell_exec("git rm -f $full_file_path");
    }
    
    function generate_model_test() {
         if (empty($this->args[0])) {
            $this->error("You need to specify a model.");
            exit();
        }
        
        $model_name = $this->args[0] . 'TestCase';
        $table_name = Inflector::tableize($this->args[0]);
        $file_name = Inflector::singularize($table_name) . '.test.php';
        $full_file_path = ROOT . '/app/tests/cases/models/' . $file_name;
        $require_path_for_helper = "ROOT . '/app/tests/model_helpers/$file_name'";
        $class_name_for_helper = $model_name . 'Helper';
        
        
        if (is_file($full_file_path)) {
            return;
        }
        
        touch($full_file_path);
        shell_exec("git add $full_file_path");
        
        $file_contents = '';
        $file_contents .= "<?php\n";
        $file_contents .= "require_once(ROOT . '/app/tests/fototestcase.php');\n";
        $file_contents .= "class $model_name extends fototestcase {\n";
        $file_contents .= "\n";
        $file_contents .= "\tfunction start() {\n";
        $file_contents .= "\t\trequire_once($require_path_for_helper);\n";
        $file_contents .= "\t\t".'$this->helper = new '."$class_name_for_helper();\n";
        $file_contents .= "\t\t".'$this->'."$model_name = ClassRegistry::init('$model_name');\n";
        $file_contents .= "\t\t".'$this->_run_validate_functions($this->helper);'."\n";
        $file_contents .= "\t}\n";
        $file_contents .= "\n}";
        
        file_put_contents($full_file_path, $file_contents);
    }
    
    function remove_model_test() {
        if (empty($this->args[0])) {
            $this->error("You need to specify a model.");
            exit();
        }
        
        $model_name = $this->args[0] . 'TestCase';
        $table_name = Inflector::tableize($this->args[0]);
        $file_name = Inflector::singularize($table_name) . '.test.php';
        $full_file_path = ROOT . '/app/tests/cases/models/' . $file_name;
       
        shell_exec("git rm -f $full_file_path");
    }
    
    function generate_validate_test() {
        if (empty($this->args[0])) {
            $this->error("You need to specify a model.");
            exit();
        }
        
        $class_name = 'DBCheck' . $this->args[0] . 'TestCase';
        $model_name = $this->args[0];
        $table_name = Inflector::tableize($this->args[0]);
        $file_name = 'dbcheck_'.Inflector::singularize($table_name) . '.test.php';
        $full_file_path = ROOT . '/app/tests/cases/validate/' . $file_name;
        $require_path_for_helper = "ROOT . '/app/tests/model_helpers/".Inflector::singularize($table_name) . ".test.php'";
        $class_name_for_helper = $model_name . 'TestCaseHelper';
        
        if (is_file($full_file_path)) {
            return;
        }
        
        touch($full_file_path);
        shell_exec("git add $full_file_path");
        
        $file_contents = '';
        $file_contents .= "<?php\n";
        $file_contents .= "require_once(ROOT . '/app/tests/fototestcase.php');\n";
        $file_contents .= "class $class_name extends fototestcase {\n";
        $file_contents .= "\n";
        $file_contents .= "\tfunction start() {\n";
        $file_contents .= "\t\trequire_once($require_path_for_helper);\n";
        $file_contents .= "\t\t".'$this->helper = new '."$class_name_for_helper();\n";
        $file_contents .= "\t\t".'$this->'."$model_name = ClassRegistry::init('$model_name');\n";
        $file_contents .= "\t\t".'$this->_run_validate_functions($this->helper);'."\n";
        $file_contents .= "\t}\n";
        $file_contents .= "\n}";
        
        file_put_contents($full_file_path, $file_contents);
    }
    
    function remove_validate_test() {
         if (empty($this->args[0])) {
            $this->error("You need to specify a model.");
            exit();
        }
        
        $class_name = 'DBCheck' . $this->args[0] . 'TestCase';
        $model_name = $this->args[0];
        $table_name = Inflector::tableize($this->args[0]);
        $file_name = 'dbcheck_'.Inflector::singularize($table_name) . '.test.php';
        $full_file_path = ROOT . '/app/tests/cases/validate/' . $file_name;
        shell_exec("git rm -f $full_file_path");
    }
    
    function generate_test_helper() {
        if (empty($this->args[0])) {
            $this->error("You need to specify a model.");
            exit();
        }
        
        $class_name =  $this->args[0] . 'TestCaseHelper';
        $model_name = $this->args[0];
        $table_name = Inflector::tableize($this->args[0]);
        $file_name = Inflector::singularize($table_name) . '.test.php';
        $full_file_path = ROOT . '/app/tests/model_helpers/' . $file_name;
        
        if (is_file($full_file_path)) {
            return;
        }
        
        touch($full_file_path);
        shell_exec("git add $full_file_path");
        
        $file_contents = '';
        $file_contents .= "<?php\n";
        $file_contents .= "class $class_name {\n";
        $file_contents .= "\n";
        $file_contents .= "\tfunction __construct() {\n";
        $file_contents .= "\t\t".'$this->'."$model_name = ClassRegistry::init('$model_name');\n";
        $file_contents .= "\t}\n";
        $file_contents .= "\n}";
        
        file_put_contents($full_file_path, $file_contents);
    }
    
    function remove_test_helper() {
         if (empty($this->args[0])) {
            $this->error("You need to specify a model.");
            exit();
        }
        
        $class_name =  $this->args[0] . 'TestCaseHelper';
        $model_name = $this->args[0];
        $table_name = Inflector::tableize($this->args[0]);
        $file_name = Inflector::singularize($table_name) . '.test.php';
        $full_file_path = ROOT . '/app/tests/model_helpers/' . $file_name;
        shell_exec("git rm -f $full_file_path");
    }
 
}