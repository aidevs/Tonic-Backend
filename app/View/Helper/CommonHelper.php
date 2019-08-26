<?php

App::uses('Helper', 'View');

/**

 * Application helper

 *

 * Add your application-wide methods in the class below, your helpers

 * will inherit them.

 *

 * @package       app.View.Helper

 */
class CommonHelper extends Helper {

    var $helpers = array('Session','Html');

    
    public function sessionFlash() {
        $messages = $this->Session->read('Message');
		if (!empty($messages)) {
            $output = '';
            if (is_array($messages)) {
                foreach (array_keys($messages) AS $key) {
                    $output .= $this->Session->flash($key);
                }
            }
            $class = (isset($messages['flash']['params']['class'])) ? $messages['flash']['params']['class'] : 'error';
            $msg = (isset($messages['flash']['message'])) ? $messages['flash']['message'] : $messages['auth']['message'];
			//echo $class;die;
            if ($class == 'success') {
                ?>
                <div class="alert alert-success fade in"><button aria-hidden="true" data-dismiss="alert" class="close" type="button"></button><i class="fa-lg fa fa-check"></i> <?php echo $msg; ?></div> 
            <?php } elseif ($class == 'info') { ?>
                <div class="alert alert-info fade in"><button aria-hidden="true" data-dismiss="alert" class="close" type="button"></button><i class="fa-lg fa fa-warning "></i> <?php echo $msg; ?></div>

            <?php } else { ?>
                <div class="alert alert-danger fade in"><button aria-hidden="true" data-dismiss="alert" class="close" type="button"></button><i class="fa-lg fa fa-warning"></i> <?php echo $msg; ?></div> 
            <?php
            }
        }
    }
	
	public function sessionNoty() {
        $messages = $this->Session->read('Message');//pr($messages);die;
        if(!empty($messages)){
        $output = '';
        if( is_array($messages) ) {
            foreach(array_keys($messages) AS $key) {
                $output .= $this->Session->flash($key);
            }
        }
        
        $class=(isset($messages['flash']['params']['class']))?$messages['flash']['params']['class']:'error';
        $msg=(isset($messages['flash']['message']))?$messages['flash']['message']:$messages['auth']['message'];
        //return $output;
            echo '<script>
            $(function()
            {
            noty({text: "' . $msg . '" ,timeout:5000,type:"' . $class . '",killer: true});
            });
            </script>';
            }
       }
       
    function loadJsClass($class=null) {
         $this->Html->scriptStart(array('inline' => false));
         echo ' $(document).ready(function(){ '.$class.'.init();})';
         $this->Html->scriptEnd();
     }
    function getUserImage($img=null,$w=100,$h=100,$crop=1,$type='front') {
       if($img!='' && file_exists(WWW_ROOT.'uploads'.DS.'users'.DS.$img)){ 
           $img=SITE_URL."thumbnail/thumbnail.php?file=../uploads/users/{$img}&w={$w}&h={$h}&el=0&gd=2&color=FFFFFF&crop={$crop}&tp=1";
       }else{
           $img=SITE_URL."thumbnail/thumbnail.php?file=../img/no-user.png&w={$w}&h={$h}&el=0&gd=2&color=FFFFFF&crop={$crop}&tp=1";
           if($type=='admin'){
           $img=SITE_URL.'img/no-image.png';    
           }
       }
       return $img;
    }
}
?>