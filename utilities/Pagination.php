<?php

class Pagination 
{
    public $itemsperpages = 10;
    public $numberofpages;
    public $page;
    public $firstresult;

    public function computeNumberOfPages($totalResultsCount)
    {
        $this->numberofpages = ceil($totalResultsCount/$this->itemsperpages);
    }

    public function numberofPages()
    {
        return $this->numberofpages;
    }

    public function pageValidator($numberofpages)
    {
        if(!isset($_GET['page'])) {
			$page = 1;
		} else {
			$page = $_GET['page'];
			if($page < 1) {
				$page = 1;
			} else if($page > $numberofpages) {
				$page = $numberofpages;
			} else if(!is_numeric($page)) {
				$page = 1;
			} else {
				$page = $_GET['page'];
            }
        }
        $this->page = $page;
    }

    public function pageNumber()
    {
        return $this->page;
    }

    public function computefirstResult($page, $itemsperpages) 
    {
        $this->firstresult = ($page - 1) * $itemsperpages;
    }

    public function firstResult()
    {
        return $this->firstresult;
    }

    public function paginate($numberofpages, $page, $totalResultsCount, $keyword, $type)
    {
        if($numberofpages > 1) {
            $pagination = '';

            echo "<p style='margin-top:20px;'>Showing $totalResultsCount results</p>";
            echo "<p>Page: $page of $numberofpages</p>";
            
            if($page > 1) {
				$previous = $page - 1;
				$pagination .= '<a href="?q='.$keyword.'&type='.$type.'&page='.$previous.'">Previous</a>&nbsp;';

				for($i = $page - 3; $i < $page; $i++) {
					if($i > 0) {
						$pagination .= '<a href="?q='.$keyword.'&type='.$type.'&page='.$i.'">'.$i.'</a>&nbsp;';
					}
				}
            }
            
            $pagination .= ''.$page.'&nbsp;';

			for($i = $page + 1; $i <= $numberofpages; $i++) {
				$pagination .= '<a href="?q='.$keyword.'&type='.$type.'&page='.$i.'">'.$i.'</a>&nbsp;';
				if($i >= $page + 3) {
					break;
				}
			}

			if($page != $numberofpages) {
				$next = $page + 1;
				$pagination .= '<a href="?q='.$keyword.'&type='.$type.'&page='.$next.'">Next</a>&nbsp;';	
            }
            
            echo "<div class='pagination'>".$pagination."</div>";
        }
    }
}

?>