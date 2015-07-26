<?php
class createSeoUrl {
	private $page = '';
	private $identifier = '';
	private $identifier2 = '';
		
	public function __construct() {
		return true;
	}
	
	public function outputSeo($p, $i = '', $j = '') {
		$this->page = $p;
		$this->identifier = $i;
		$this->identifier2 = $j;
		switch ($this->page) {
			case 'game':
				return 'game/' . $this->identifier . '/' . $this->identifier2 . '.html';	
				break;
			case 'play':
				return 'play/' . $this->identifier . '/' . $this->identifier2 . '.html';
				break;
			case 'editavatar':
				return $this->page . '/';
				break;
			case 'members':
				return $this->page . '/';
				break;
			case 'shownews':
				return $this->page . '/';
				break;
			case 'favourites':
				return $this->page . '/';
				break;
			case 'showmember':
				return $this->page . '/' . $this->identifier2 . '/';
				break;
			case 'terms':
				return $this->page . '/';
				break;	
			case 'category':
				return $this->identifier . '/games' . $this->identifier2 . '.html';
				break;
			case 'search':
				return $this->page . '/' . $this->identifier . '/' . $this->identifier2 . '/';
				break;
			case 'register':
				return $this->page . '/';
				break;
			case 'topplayers':
				return $this->page . '/';
				break;
			case 'links':
				return $this->page . '/' . $this->identifier . '/';
				break;
			case 'addlink':
				return $this->page . '/';
				break;
			case 'forgotpassword':
				return $this->page . '/';
				break;
			case 'profile':
				return $this->page . '/';
				break;
			case 'popular':
				return $this->page . '/';
				break;
			case 'toprated':
				return $this->page . '/';
				break;
			case 'latest':
				return $this->page . '/';
				break;
			case 'privacy':
				return $this->page . '/';
				break;
			case 'login':
				return $this->page . '/';
				break;	
			case 'contactus':
				return $this->page . '/';
				break;
			case 'download':
                return $this->page . '/';                
                break;
            case 'unsubscribe':
                return $this->page . '/';
				break;
		}
	}
}
?>