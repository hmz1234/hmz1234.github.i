<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

/**
 * Title.php
 * Author     : Hran
 * Date       : 2016/12/10
 * Version    :
 * Description:
 */
class CollapseTitle extends Typecho_Widget_Helper_Form_Element
{
    private $labelValue;
    private $options;
    private $descriptionValue;
    public function label($value) {
        $this->labelValue = $value;
        return $this;
    }

    public function input($name = NULL, array $options = NULL) {

        if (is_array($options)) {
            $this->options = $options;
        } else {
            $this->options = array("open", "close");
        }

        return NULL;
    }

    public function description($description) {
        $this->descriptionValue = $description;
    }

    protected function _value($value) {}

    public function render() {
        if (in_array('close', $this->options)) {
            echo <<<HTML
            </div>
        </div>
HTML;

        }
        if (in_array('open', $this->options)) {
            echo <<<HTML
        <div class="collapse-block">
            <div class="collapse-header" data-toggle="collapse-block" data-target="#{$this->name}">
                <h2 class="title">{$this->labelValue}</h2>
            </div>
            <div id="{$this->name}" class="collapse-content collapse">
HTML;

            if (!empty($this->descriptionValue)) {
                echo <<<HTML
                    <div class="description">{$this->descriptionValue}</div>
HTML;
            }
        }

    }

}