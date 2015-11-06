<?php
namespace Trungtnm\Backend\Utility;

use Illuminate\Support\Facades\View;

class HtmlMaker{
	/**
	 *     default values
	 *    
	 */
	protected $seperator = 0;
	protected $suffix = '';
	protected $prefix = '';

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
	 *     make
	 *     @param string $type  type of content
	 *     @param string $field  field of content
	 *     @param string $id  	id of content  
	 *     @param string $value value of content
	 */
	public static function make($object, $field, $info){
		if ( !empty($field) && ( $field == 'icon') ) {
			$value_icon = ( isset($info['alias']) ) ? $object->{$info['alias']} : $object->{$field};
			$value = '<span class="'.$value_icon.'"></span>';
		}else{
			//relational attribute
			if(!empty($info['alias'])){
				if(strpos($info['alias'], '.') !== FALSE){
					$tmp = explode('.', $info['alias']);
					$value = !empty($object->{$tmp[0]}->{$tmp[1]}) ? $object->{$tmp[0]}->{$tmp[1]} : '';
				}
				else{
					$value = $object->{$info['alias']};
				}
			}
			else{
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

	public function init($id, $field, $value, $info){
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

	public function render(){
		return $this->{$this->type}();
	}

	/**
	 *     Get type text content
	 *     @return string HTML
	 */
	public function text(){
		return "<td>".$this->value."</td>";
	}

	public function link(){
		return '<td><a target="_blank" href="'.$this->value.'">'. Str::limit($this->value, 20) .'</a></td>';
	}

	/**
	 *     Get type date content
	 *     @return string HTML
	 */
	public function date(){
		if(!empty($this->value)){
			return "<td>".$this->value->format('H:i d-m-Y')."</td>";
		}
		else{
			return "<td></td>";
		}
	}

	/**
	 *     Get type text content
	 *     @return string HTML
	 */
	public function number(){
		$suffix = !empty($this->suffix) ? $this->suffix : '';
		$seperator = !empty($this->seperator) ? $this->seperator : 0;
		return "<td>".(numberFormat( $this->value, 'vn', $seperator)). $suffix ."</td>";
	}

	/**
	 *     Get type boolean content
	 *     @return [type] [description]
	 */
	public function boolean(){
		if(!empty($this->noScript)){
			$onClick = "";
		}
		else{
			$onClick = "toggleBoolean({$this->id}, {$this->value},'{$this->field}')";
		}
		return '<td class="'.$this->field.'-'.$this->id.'"><a href="javascript:;" onclick="'.$onClick.'"><i class="'.$this->getClassTypeBoolean($this->value).'"></i></a></td>';
	}

	public function image(){
		return '<td><a class="fancy" href="'.url($this->value).'"><img src="'.url($this->value).'" width="120"
		alt=""></a></td>';
	}

	/**
	 *     Helper get class of boolean type
	 *     @return [type] [description]
	 */
	public static function getClassTypeBoolean($value) {
    	return ($value) ? "fa fa-check fa-check-right" : "fa fa-times fa-times-wrong";
    }

    public static function makeInput($field, $options, $value = null, $data = [] ){
    	$view = "TrungtnmBackend::general.input".ucfirst(strtolower($options['type']));
    	$helpText =
            !empty($options['help'])
            ? view('TrungtnmBackend::general.helpText', ['helpText' => $options['help'] ] )->render()
            : "";

    	return view($view, [
            'field' => $field,
            'value' => $value,
            'options' => $options,
            'data' => $data,
            'helpText' => $helpText
        ])->render();
    }

}