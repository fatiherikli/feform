<?php


class WidgetBuilder {
    /*
     *  Widget build from array  
     * */

    private $widget = NULL;

    function widget_factory($field_name, $klass) {
        return new $klass($field_name);
    }

    function __construct($field_name, $field) {
        $this->widget = $this->widget_factory($field_name, $field["widget"]);
        $this->build_widget($field);
    }

    function build_widget($field) {
        #set label
        if (array_key_exists("label", $field))
            $this->widget->label = $field["label"];

        #widget choices
        if (array_key_exists("choices", $field))
            $this->widget->choices = $field["choices"];

        # validators
        if (array_key_exists("validators", $field))
            foreach ($field["validators"] as $validator)
               $this->widget->add_validator($validator);

        # custom attributes
        if (array_key_exists("attrs", $field))
            foreach ($field["attrs"] as $attr => $value)
               $this->widget->set_attr($attr, $value);
    }

    function get_widget() {
        return $this->widget;
    }
}

abstract class Widget {
    /*  
     *  Base widget class
     *  This is abstract, must be extend.
     * */
    
   public $name;
   public $value;
   public $label;
   public $type;
   public $choices = Array();
   public $attrs = Array();
   public $validators = Array();
   public $string_filters = Array("strip_tags", "trim");
   public $error_text;

   function __construct($name) {
       $this->name = $name;
       $this->label = $this->label_from_name();
       $this->validators = array_merge($this->string_filters, $this->validators);
       $this->set_attr("name", $this->name);
       $this->set_attr("class", $this->type);
   }

   function get_attrs() {
       if (empty($this->attrs)) return "";
       $attrs = Array();
       foreach ($this->attrs as $key=>$value) {
           $attrs[] = "$key=\"$value\"";
       }
       return join(" ", $attrs);
   }

   function set_attr($attr, $value) {
       $this->attrs[$attr] = $value;
   }

   function add_validator($validator) {
       $this->validators[] = $validator;
   }

   function is_valid() {
      if (!$this->validators)
         return True;

      foreach ($this->validators as $validator) {
          try {
              if (is_array($this->value)) // if multiple selection
                  $this->value = explode(",",call_user_func($validator, join(",", $this->value)));
              else
                  $this->value = call_user_func($validator, $this->value);
          }
          catch (Exception $e) {
              $this->error_text = $e->getMessage();
              return False;
          }
      }
      return True;
   }

   function label_from_name() {
         return ucwords(str_replace("_", " ", $this->name));
   }

   function render_label() {
       return "<label>$this->label</label>";
   }

   function render_error() {
        return "<span class=\"error\">$this->error_text</span>";
   }

   function render_widget() {
     $attrs = $this->get_attrs();
     return "<input type=\"$this->type\" id=\"id_$this->name\"  value=\"$this->value\" $attrs/>";
   }

   function render() {
       $render = array();
       $render[] = "\n<p>";
       $render[] = $this->render_label();
       $render[] = $this->render_widget();
       if ($this->error_text)
           $render[] = $this->render_error();
       $render[] = "</p>\n";
       return join("\n", $render);
   }

   function is_checked_or_selected($value) {
       if (is_array($this->value))
           return $this->value && in_array($value, $this->value);
       else
           return $this->value == (string)$value;
   }

}

 

class HiddenInput extends Widget {
    public $type="hidden";
    function render() {
        return render_widget();
    }
}

class TextInput extends Widget {
    public $type="text";
}

class TextArea extends TextInput {
    public $type="textarea";
	public $attrs = Array("rows"=>"5", "cols" => "40");
    function render_widget() {
       $attrs = $this->get_attrs();
       return "<textarea id=\"id_$this->name\"  $attrs>$this->value</textarea>";
     }
}

class PasswordInput extends TextInput {
    public  $type="password";
}

class EmailInput extends TextInput {
    public  $validators = Array("email_validator");
}

class Select extends Widget {
    public  $type="select";
    function render_widget() {
        $render_html = Array();
        $attrs = $this->get_attrs();
        $render_html[] =  "<select id=\"id_$this->name\"  $attrs>";
        foreach ($this->choices as $value=>$label) {
            $selected = $this->is_checked_or_selected($value) ? "selected=\"selected\"" : "";
            $render_html[] =  "<option value=\"$value\" $selected>$label</option>";
        }
        $render_html[]="</select>";
        return join($render_html, "\n");
    }
}


class SelectMultiple extends Select {
    public  $attrs = Array("multiple"=>"multiple", "size"=>4);
	public  $type = "multipleselect";

    function __construct($name) {
        parent::__construct($name);
        $this->set_attr("name", "$this->name[]");
    }
}

class Checkbox extends Widget {
    public $type="checkbox";

    function __construct($name) {
        parent::__construct($name);
        $this->set_attr("name", "$this->name[]");
    }
        
    function render_widget() {
         $render_html = Array();
         foreach ($this->choices as $value=>$label) {
            $checked = $this->is_checked_or_selected($value) ? "checked=\"checked\"" : "";
            $attrs = $this->get_attrs();
            $render_html[] = "<input type=\"$this->type\" value=\"$value\"  $attrs $checked> $label";
        }
        return join($render_html, "\n");
    }
}


class Radio extends Widget {
    public  $type="radio";
    function render_widget() {
         $render_html = Array();
         foreach ($this->choices as $value=>$label) {
            $checked = $this->is_checked_or_selected($value) ? "checked=\"checked\"" : "";
            $attrs = $this->get_attrs();
            $render_html[] = "<input type=\"$this->type\" value=\"$value\"  $attrs $checked> $label";
        }
        return join($render_html, "\n");
    }
}


?>