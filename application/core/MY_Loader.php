<?php
class MY_Loader extends CI_Loader {
	public function common($template_name, $vars = array(), $return = FALSE) {
		if ($return) {
			$content = $this->view ( 'common/header', $vars, $return );
			$content .= $this->view ( 'common/menu', $vars, $return );
			$content .= $this->view ( $template_name, $vars, $return );
			$content .= $this->view ( 'common/footer', $vars, $return );
			
			return $content;
		} else {
			$this->view ( 'common/header', $vars );
			$this->view ( 'common/menu', $vars );
			$this->view ( $template_name, $vars );
			$this->view ( 'common/footer', $vars );
		}
	}
}
