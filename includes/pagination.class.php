<?php
	class pagination {
	
	public $adjacents = 3; // How many adjacent pages should be shown on each side?
	public $limit = 1;
	public $url = '';
	public $numpages = 1;
	public $seo = 'no';
	public $start = 0;
	public $page = 1;
	public $pi = 'page';
	public function __construct( $n, $s, $pi='', $l = 25, $a=3, $u = '') { // number items, seo yes/no, page identifier(optional), limit per page(optional), adjacent pages(optional), $url(optional)
		if($u == '') {
			$this->url = $this->removeQuerystringVar($this->currentUrl(), 'page');
		} else {
			$this->url = $u;
		}
		$this->numpages = $n;
		$this->adjacents = $a;
		$this->limit = $l;		
		$this->seo = $s;
		$this->pi = $pi;
		if ($s == 'no') {
			$this->page = $this->removeQuerystringVar($this->currentUrl(), 'page', true);
		} else {
			$this->page = $this->getSeoPage();
		}
		if (empty($this->page) || $this->page < 1) $this->page = 1;
		$this->start = ($this->page - 1) * $this->limit;
		return true;
	}	
	public function removeQuerystringVar($url, $key, $getvariable = false) { 
		if ($getvariable === true) {
			$url_parsed = parse_url($url);
			parse_str($url_parsed['query'], $url_parts);
			if (isset($url_parts[$key])) {
				return $url_parts[$key];
			} else {
				return 1;
			}
		} else {	
			$url = preg_replace('/(.*)(\?|&)' . $key . '=[^&]+?(&)(.*)/i', '$1$2$4', $url . '&'); 
			$url = substr($url, 0, -1); 
			return $url;
		}
	}
	public function currentUrl() {
		$pageURL = 'http';
		if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
			$pageURL .= "s";
		}
		$pageURL .= "://";
		if ($_SERVER["SERVER_PORT"] != "80") {
			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		} else {
			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
		return $pageURL;
	}
	public function showPagination() {
		/* Setup page vars for display. */
		if ($this->page < 1) $this->page = 1;					//if no page var is given, default to 1.
		$prev = $this->page - 1;							//previous page is page - 1
		$next = $this->page + 1;							//next page is page + 1
		$lastpage = ceil($this->numpages/$this->limit);		//lastpage is = total pages / items per page, rounded up.
		$lpm1 = $lastpage - 1;						//last page minus 1
		$pagination = "";
		if($lastpage > 1)
		{	
			$pagination .= '<div class="pagination">';
			//previous button
			if ($this->page > 1) {
				if ($this->seo == 'no') {
					$pagination.= '<a href="' . $this->url .  '&page=' . $prev . '">&laquo; previous</a>';
				} else {
					$pagination.= '<a href="' . $this->newSeoUrl($prev) . '">&laquo; previous</a>';
				}
			} else {
				$pagination.= "<span class=\"disabled\">&laquo; previous</span>";	
			}
			//pages	
			if ($lastpage < 7 + ($this->adjacents * 2))	//not enough pages to bother breaking it up
			{	
				for ($counter = 1; $counter <= $lastpage; $counter++)
				{
					if ($counter == $this->page) {
						$pagination.= '<span class="current">' . $counter . '</span>';
					} else {
						if ($this->seo == 'no') {
							$pagination.= '<a href="' . $this->url . '&page=' . $counter . '">' . $counter . '</a>';					
						} else {
							$pagination.= '<a href="' . $this->newSeoUrl($counter) . '">' . $counter . '</a>';
						}
					}
				}
			}
			elseif($lastpage > 5 + ($this->adjacents * 2)) //enough pages to hide some
			{
				//close to beginning; only hide later pages
				if($this->page < 1 + ($this->adjacents * 2))		
				{
					for ($counter = 1; $counter < 4 + ($this->adjacents * 2); $counter++)
					{
						if ($counter == $this->page) {
							$pagination.= '<span class="current">' . $counter . '</span>';
						} else {
							if ($this->seo == 'no') {
								$pagination.= '<a href="' . $this->url . '&page=' . $counter . '">' . $counter . '</a>';					
							} else {
								$pagination.= '<a href="' . $this->newSeoUrl($counter) . '">' . $counter . '</a>';
							}
						}
					}
					$pagination.= '...';
					if ($this->seo == 'no') {
						$pagination.= '<a href="' . $this->url . '&page=' . $lpm1 . '">' . $lpm1 . '</a>';
						$pagination.= '<a href="' . $this->url . '&page=' . $lastpage . '">' . $lastpage . '</a>';		
					} else {
						$pagination.= '<a href="' . $this->newSeoUrl($lpm1) . '">' . $lpm1 . '</a>';
						$pagination.= '<a href="' . $this->newSeoUrl($lastpage) . '">' . $lastpage . '</a>';
					}
				}
				//in middle; hide some front and some back
				elseif($lastpage - ($this->adjacents * 2) > $this->page && $this->page > ($this->adjacents * 2)) {
					if ($this->seo == 'no') {
						$pagination.= '<a href="' . $this->url . '&page=1">1</a>';
						$pagination.= '<a href="' . $this->url . '&page=2">2</a>';
					} else {
						$pagination.= '<a href="' . $this->newSeoUrl('1') . '">1</a>';
						$pagination.= '<a href="' . $this->newSeoUrl('2') . '">2</a>';
					}
					$pagination.= '...';
					for ($counter = $this->page - $this->adjacents; $counter <= $this->page + $this->adjacents; $counter++)
					{
						if ($counter == $this->page) {
							$pagination.= '<span class="current">' . $counter . '</span>';
						} else {
							if ($this->seo == 'no') {
								$pagination.= '<a href="' . $this->url . '&page=' . $counter . '">' . $counter . '</a>';					
							} else {
								$pagination.= '<a href="' . $this->newSeoUrl($counter) . '">' . $counter . '</a>';
							}
						}
					}
					$pagination.= '...';
					if ($this->seo == 'no') {
						$pagination.= '<a href="' . $this->url . '&page=' . $lpm1 . '">' . $lpm1 . '</a>';
						$pagination.= '<a href="' . $this->url . '&page=' . $lastpage . '">' . $lastpage . '</a>';		
					} else {
						$pagination.= '<a href="' . $this->newSeoUrl($lpm1) . '">' . $lpm1 . '</a>';
						$pagination.= '<a href="' . $this->newSeoUrl($lastpage) . '">' . $lastpage . '</a>';
					}
					//close to end; only hide early pages
				} else {
					if ($this->seo == 'no') {
						$pagination.= '<a href="' . $this->url . '&page=1">1</a>';
						$pagination.= '<a href="' . $this->url . '&page=2">2</a>';
					} else {
						$pagination.= '<a href="' . $this->newSeoUrl('1') . '">1</a>';
						$pagination.= '<a href="' . $this->newSeoUrl('2') . '">2</a>';
					}	
					$pagination.= "...";
					for ($counter = $lastpage - (2 + ($this->adjacents * 2)); $counter <= $lastpage; $counter++)
					{
						if ($counter == $this->page) {
							$pagination.= '<span class="current">' . $counter . '</span>';
						} else {
							if ($this->seo == 'no') {
								$pagination.= '<a href="' . $this->url . '&page=' . $counter . '">' . $counter . '</a>';					
							} else {
								$pagination.= '<a href="' . $this->newSeoUrl($counter) . '">' . $counter . '</a>';
							}
						}
					}
				}
			}
			
			//next button
			if ($this->page < $counter - 1) {
				if ($this->seo == 'no') {
					$pagination.= '<a href="' . $this->url . '&page=' . $next . '">next &raquo;</a>';
				} else {
					$pagination.= '<a href="' . $this->newSeoUrl($next) . '">next &raquo;</a>';
				}
			} else {
				$pagination.= '<span class="disabled">next &raquo;</span>';
			}
			$pagination.= "</div>";		
		}
		return $pagination;
	}
	private function newSeoUrl ($newpage) {
		if ($this->pi == '') {
			$pos = strrpos($this->url, $this->page);
			if($pos === false)
			{
				return $url;
			} else {
				return substr_replace($this->url, $newpage, $pos, strlen($this->page));
			}
		} else {	
			$pos = strrpos($this->url, $this->pi);
			if($pos === false) {
				return $this->url;
			} else {
				list($ret1, $ret2) = explode($this->pi, $this->url, 2);
				return $ret1.  $this->pi . '/' . $newpage . '.html';
			}
		}
	}
	private function getSeoPage () {
		if ($this->pi == '') {
			preg_match_all('/[0-9]+/', $this->url, $matches);
			$count = count($matches[0]);
			if ($count > 0) {
				return $matches[0][$count-1];
			} else {
				return 1;
			}
		} else {
			$pos = strrpos($this->url, $this->pi);
			if($pos === false) {
				return 1;
			} else {
				list($ret1, $ret2) = explode($this->pi, $this->url, 2);
				preg_match_all('/[0-9]+/', $ret2, $matches);
				$count = count($matches[0]);
				if ($count > 0) {
					return $matches[0][$count-1];
				} else {
					return 1;
				}
			}
		}
	}
} 
 ?>