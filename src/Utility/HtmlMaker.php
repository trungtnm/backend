<?php
namespace Trungtnm\Backend\Utility;

use Illuminate\Support\Facades\View;

class HtmlMaker
{
	/**
	 *     default values
	 *    
	 */
	protected $seperator = 0;
	protected $suffix = '';
	protected $prefix = '';
	protected $locale;

    /**
     * @var $instance Singleton
     */
	private static $instance;

	/**
	 *     Type of content
	 *     @var string
	 */
	protected $type;

	/**
	 *     Value of content
	 *     @var string
	 */
	protected $value;

	/**
	 * @param $locale
	 * @return $this
	 */
	public function setLocale($locale)
	{
		$this->locale = $locale;
		return $this;
	}

	/**
	 * @param $languages
	 * @return mixed
	 */
	public function languagesSwitcher($languages) {
		return view(
			'TrungtnmBackend::general.languages',
			['langs' => $languages]
		)->render();
	}

	/**
	 *     make
	 *     @param string $type  type of content
	 *     @param string $field  field of content
	 *     @param string $id  	id of content  
	 *     @param string $value value of content
	 */
	public function make($object, $field, $info)
	{
		if ( !empty($field) && ( $field == 'icon') ) {
			if (isset($info['alias'])) {
				$field = $info['alias'];
				$value_icon = $object->{$info['alias']};
			} else {
				$value_icon = $object->{$field};
			}
			$value = '<span class="'.$value_icon.' fa-lg"></span>';
		}else{
			if(!empty($info['alias'])) {
				$value = $this->aliasFieldValue($info['alias'], $object);
				$field = $info['alias'];
			} else {
				$value = $object->{$field};
			}
		}

		if ( is_null( self::$instance ) )
	    {
			self::$instance = new self();
	    }
		$obj = self::$instance;
		$obj->init($object->id, $field, $value, $info);
		
		return $obj->render();

	}

	/**
	 * @param $alias
	 * @param $object
	 * @return string
	 */
	public function aliasFieldValue($alias, $object)
	{
		//relational attribute
		if(strpos($alias, '.') !== FALSE){
			$tmp = explode('.', $alias);
			$value = !empty($object->{$tmp[0]}->{$tmp[1]}) ? $object->{$tmp[0]}->{$tmp[1]} : '';
		}
		else{
			$value = $object->{$alias};
		}

		return $value;
	}

	public function init($id, $field, $value, $info)
	{
		$this->id = $id;
		$this->field = $field;
		$this->value = $value;
		if(is_array($info)){
			foreach ($info as $key => $value) {
				$this->{$key} = $value;
			}
		}
		return true;
	}

	public function render()
	{
		return $this->{$this->type}();
	}

	/**
	 *     Get type text content
	 *     @return string HTML
	 */
	public function text()
	{
		return "<td>".$this->value."</td>";
	}

	public function link()
	{
		return '<td><a target="_blank" href="'.url($this->value).'">'. str_limit(explode_end('/', $this->value), 20) .'</a></td>';
	}

	/**
	 *     Get type date content
	 *     @return string HTML
	 */
	public function date()
	{
		if(!empty($this->value)){
			return "<td>".$this->value."</td>";
		}
		else{
			return "<td></td>";
		}
	}

	/**
	 *     Get type text content
	 *     @return string HTML
	 */
	public function number()
	{
		$suffix = !empty($this->suffix) ? $this->suffix : '';
		$decimal = !empty($this->seperator) ? $this->seperator : 2;
		return "<td>".(numberFormat( $this->value, '', $decimal)). $suffix ."</td>";
	}

	/**
	 *     Get type boolean content
	 *     @return [type] [description]
	 */
	public function boolean()
	{
		if(!empty($this->noScript)){
			$onClick = "";
		}
		else{
			$onClick = "toggleBoolean({$this->id}, ".intval($this->value).",'{$this->field}')";
		}
		return '<td class="'.$this->field.'-'.$this->id.'"><a href="javascript:;" onclick="'.$onClick.'"><i class="'.$this->getClassTypeBoolean($this->value).'"></i></a></td>';
	}

	public function image()
	{
		return '<td><a class="fancy" href="'.url($this->value).'"><img src="'.url($this->value).'" width="120"
		alt=""></a></td>';
	}

	/**
	 *     Helper get class of boolean type
	 *     @return [type] [description]
	 */
	public function getClassTypeBoolean($value)
	{
    	return ($value) ? "fa fa-check fa-check-right" : "fa fa-times fa-times-wrong";
    }

	/**
	 * return xeditable field
	 * @return string
	 */
	public function editable()
	{
		$defaultText = !empty($this->default) ? $this->default : 'None';
		return '<td><a class="xeditable" data-type="select" data-pk="'.$this->id.'"
		data-value="'.$this->value.'" data-source="'.route($this->source, [$this->id, $this->field]).'"
		data-url="'.route($this->source, [$this->id, $this->field]).'"
		data-title="'.$this->label.'"
		data-name="'.$this->field.'">'. ($this->value ? $this->value : $defaultText).'</a></td>';
	}

    /**
     * render input field
     *
     * @param $field
     * @param $options
     * @param null $value
     * @param array $data
     * @return string
     */
    public function makeInput($field, $options, $value = null, $data = [] )
	{
		$inputNameSuffix = $this->locale ? '_' . $this->locale : '';
    	$view = "TrungtnmBackend::general.input".ucfirst(strtolower($options['type']));
    	$helpText =
            !empty($options['help'])
            ? view('TrungtnmBackend::general.helpText', ['helpText' => $options['help'] ] )->render()
            : "";

    	return view(
            $view,
            [
                'field' => $field . $inputNameSuffix,
                'value' => $value,
                'options' => $options,
                'data' => $data,
                'helpText' => $helpText,
            ]
        )->render();

    }

    /**
     * @param $fieldName
     * @return string
     */
    public function showError($validate, $fieldName)
    {
		$inputNameSuffix = $this->locale ? '_' . $this->locale : '';
        return view(
            'TrungtnmBackend::general.error',
            [
				'validate' => $validate,
				'fieldName' => $fieldName . $inputNameSuffix,
			]
        )->render();
    }
}