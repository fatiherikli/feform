<?php
/*
 *  FEForm - Simple Form Manipulation
 *  author: Fatih ERİKLİ
 *  www.fatiherikli.com
 *  fatiherikli@gmail.com 
 *
 * */
require_once("widgets.php");
require_once("validators.php");


class Form {
    public $fieldsets = Array();
    public $fields = Array();
    public $initial = Array();
    public $widgets = Array();
    public $errors = Array();
    public $cleaned_data = Array();

    function __construct($initial=Array()) {
       $this->initial = $initial;
       $this->load_widgets();
       $this->load_values();
    }
    
    function load_widgets() {
        foreach ($this->fields as $field_name=>$field)
           {
               $widget = new WidgetBuilder($field_name, $field);
               $this->widgets[$field_name] = $widget->get_widget();
               if ($field["fieldset"])
                   $this->fieldsets[$field["fieldset"]]["fields"][] = $field_name;
           }
    }

    function load_values() {
        foreach ($this->widgets as $name => $widget) {
            if (array_key_exists($name, $this->initial))
                $widget->value = $this->initial[$widget->name];
        }
    }

    function is_valid() {
        foreach($this->widgets as $name => $widget) {
            if (!$widget->is_valid()) {
                $this->errors[] = "$widget->label : $widget->error_text";
            }
            $this->cleaned_data[$name] = $widget->value;
        }
        return (bool)!$this->errors;
    }

    function render_errors() {
        if (!$this->errors) return "";
        $errors = Array();
        $errors[] = "<ul class=\"error_list\">";
        foreach ($this->errors as $error) {
            $errors[] = "<li>$error</li>" ;
        }
        $errors[] = "</ul>";
        echo join("\n",$errors);
    }

    function clean_form() {
        foreach($this->widgets as $name => $widget) {
            $widget->value = "";
        }
    }

    function render_form() {
        $render = Array();
        foreach($this->widgets as $name => $widget) {
           $render[] = $widget->render();
       }
       return join("\n",$render);
    }

    function render_fieldsets() {
        $render = Array();
        foreach($this->fieldsets as $name => $fieldset) {
            $render[] = "<fieldset>";
            $render[] = "<legend>" . $fieldset["label"] . "</legend>";
            if ($fieldset["fields"])
                foreach($fieldset["fields"] as $field)
                    $render[] = $this->widgets[$field]->render();
            $render[] = "</fieldset>";
        }
        return join("\n",$render);
    }

    function render() {
        if ($this->fieldsets)
            echo $this->render_fieldsets();
        else
            echo $this->render_form();
    }
}

 

?>