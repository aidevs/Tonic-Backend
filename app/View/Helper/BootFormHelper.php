<?php
/**
 * Boot Form Helper
 *
 * PHP version 5
 *
 * @category Helper
 * @package  CakePHP
 * @version  1.0
 * @author   Sonu Verma
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * 
 */
class BootFormHelper  extends FormHelper{

    
    /**
 * Creates an HTML link, but access the URL using the method you specify (defaults to POST).
 * Requires javascript to be enabled in browser.
 *
 * This method creates a `<form>` element. So do not use this method inside an existing form.
 * Instead you should add a submit button using FormHelper::submit()
 *
 * ### Options:
 *
 * - `data` - Array with key/value to pass in input hidden
 * - `method` - Request method to use. Set to 'delete' to simulate HTTP/1.1 DELETE request. Defaults to 'post'.
 * - `confirm` - Can be used instead of $confirmMessage.
 * - `inline` - Whether or not the associated form tag should be output inline.
 *   Set to false to have the form tag appended to the 'postLink' view block.
 *   Defaults to true.
 * - `block` - Choose a custom block to append the form tag to. Using this option
 *   will override the inline option.
 * - Other options are the same of HtmlHelper::link() method.
 * - The option `onclick` will be replaced.
 *
 * @param string $title The content to be wrapped by <a> tags.
 * @param string|array $url Cake-relative URL or array of URL parameters, or external URL (starts with http://)
 * @param array $options Array of HTML attributes.
 * @param bool|string $confirmMessage JavaScript confirmation message. This
 *   argument is deprecated as of 2.6. Use `confirm` key in $options instead.
 * @return string An `<a />` element.
 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/form.html#FormHelper::postLink
 */
	public function postLink($title, $url = null, $options = array(), $confirmMessage = false) {
		$options = (array)$options + array('inline' => true, 'block' => null);
		if (!$options['inline'] && empty($options['block'])) {
			$options['block'] = __FUNCTION__;
		}
		unset($options['inline']);

		$requestMethod = 'POST';
		if (!empty($options['method'])) {
			$requestMethod = strtoupper($options['method']);
			unset($options['method']);
		}
		if (!empty($options['confirm'])) {
			$confirmMessage = $options['confirm'];
			unset($options['confirm']);
		}

		$formName = str_replace('.', '', uniqid('post_', true));
		$formUrl = $this->url($url);
		$formOptions = array(
			'name' => $formName,
			'id' => $formName,
			'style' => 'display:none;',
			'method' => 'post',
		);
		if (isset($options['target'])) {
			$formOptions['target'] = $options['target'];
			unset($options['target']);
		}

		$this->_lastAction($url);

		$out = $this->Html->useTag('form', $formUrl, $formOptions);
		$out .= $this->Html->useTag('hidden', '_method', array(
			'value' => $requestMethod
		));
		$out .= $this->_csrfField();

		$fields = array();
		if (isset($options['data']) && is_array($options['data'])) {
			foreach (Hash::flatten($options['data']) as $key => $value) {
				$fields[$key] = $value;
				$out .= $this->hidden($key, array('value' => $value, 'id' => false));
			}
			unset($options['data']);
		}
		$out .= $this->secure($fields);
		$out .= $this->Html->useTag('formend');

		if ($options['block']) {
			$this->_View->append($options['block'], $out);
			$out = '';
		}
		unset($options['block']);

		$url = '#';
		$onClick = 'document.' . $formName . '.submit();';
		if ($confirmMessage) {
			$options['onclick'] = $this->_bootConfirm($confirmMessage, $onClick, '', $options);
		} else {
			$options['onclick'] = $onClick . ' ';
		}
		$options['onclick'] .= 'event.returnValue = false; return false;';

		$out .= $this->Html->link($title, $url, $options);
		return $out;
	}
        /**
 * Creates an HTML link.
 *
 * If $url starts with "http://" this is treated as an external link. Else,
 * it is treated as a path to controller/action and parsed with the
 * HtmlHelper::url() method.
 *
 * If the $url is empty, $title is used instead.
 *
 * ### Options
 *
 * - `escape` Set to false to disable escaping of title and attributes.
 * - `escapeTitle` Set to false to disable escaping of title. (Takes precedence over value of `escape`)
 * - `confirm` JavaScript confirmation message.
 *
 * @param string $title The content to be wrapped by <a> tags.
 * @param string|array $url Cake-relative URL or array of URL parameters, or external URL (starts with http://)
 * @param array $options Array of options and HTML attributes.
 * @param string $confirmMessage JavaScript confirmation message. This
 *   argument is deprecated as of 2.6. Use `confirm` key in $options instead.
 * @return string An `<a />` element.
 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/html.html#HtmlHelper::link
 */
	public function link($title, $url = null, $options = array(), $confirmMessage = false) { 
		$escapeTitle = true;
		if ($url !== null) {
			$url = $this->url($url);
		} else {
			$url = $this->url($title);
			$title = htmlspecialchars_decode($url, ENT_QUOTES);
			$title = h(urldecode($title));
			$escapeTitle = false;
		}

		if (isset($options['escapeTitle'])) {
			$escapeTitle = $options['escapeTitle'];
			unset($options['escapeTitle']);
		} elseif (isset($options['escape'])) {
			$escapeTitle = $options['escape'];
		}

		if ($escapeTitle === true) {
			$title = h($title);
		} elseif (is_string($escapeTitle)) {
			$title = htmlentities($title, ENT_QUOTES, $escapeTitle);
		}

		if (!empty($options['confirm'])) {
			$confirmMessage = $options['confirm'];
			unset($options['confirm']);
		}
		if (!empty($options['alert'])) {
			$confirmMessage = $options['alert'];
                        $alertMessage=$confirmMessage;
			unset($options['alert']);
		}
		if ($confirmMessage) {
                        if(isset($alertMessage)){
                           $options['onclick'] = $this->_bootAlert($confirmMessage, 'return true;', 'return false;', $options); 
                        }else{
                           $options['onclick'] = $this->_bootConfirm($confirmMessage, 'return true;', 'return false;', $options);
                        }
			
		} elseif (isset($options['default']) && !$options['default']) {
			if (isset($options['onclick'])) {
				$options['onclick'] .= ' ';
			} else {
				$options['onclick'] = '';
			}
			$options['onclick'] .= 'event.returnValue = false; return false;';
			unset($options['default']);
		}
                $out='';
		$out .= $this->Html->link($title, $url, $options);
		return $out;
	}
/**
 * Returns a string to be used as onclick handler for confirm dialogs.
 *
 * @param string $message Message to be displayed
 * @param string $okCode Code to be executed after user chose 'OK'
 * @param string $cancelCode Code to be executed after user chose 'Cancel'
 * @param array $options Array of options
 * @return string onclick JS code
 */
	protected function _bootConfirm($message, $okCode, $cancelCode = '', $options = array()) {
                $confirm = "bootbox.confirm('{$message}', function(result) {                     
                 if (result){
                     {$okCode}
                         }
                 });";
		if (isset($options['escape']) && $options['escape'] === false) {
			$confirm = h($confirm);
		}
		return $confirm;
                
	}
	protected function _bootAlert($message, $okCode, $cancelCode = '', $options = array()) {
                $confirm = "bootbox.alert('{$message}', function() {                     
                 
                 });";
		if (isset($options['escape']) && $options['escape'] === false) {
			$confirm = h($confirm);
		}
		return $confirm;
                
	}
}
?>