<?php
/**
 * Created by PhpStorm.
 * User: mlecz
 * Date: 18.09.2016
 * Time: 22:38
 */

namespace Admin\View\Helper;


use Zend\View\Helper\AbstractHelper;

/**
 * Description of PaginationHelper
 *
 * @author mawo
 */
class PaginationHelper extends AbstractHelper
{

    private $resultsPerPage;
    private $totalResults;
    private $results;
    private $baseUrl;
    private $paging;
    private $page;

    public function __invoke($pagedResults, $page, $baseUrl, $resultsPerPage = 10)
    {
        $this->resultsPerPage = $resultsPerPage;
        try {
            if (method_exists($pagedResults, "count")) {
                $this->totalResults = $pagedResults->count();
            }
        } catch (Exception $e) {
            $this->totalResults = count($pagedResults);
        }
        $this->results = $pagedResults;
        $this->baseUrl = $baseUrl;
        $this->page = $page;

        if ($this->resultsPerPage == 0) {
            $this->resultsPerPage = 1;
        }

        return $this->generatePaging();
    }

    /**
     * Generate paging html
     */
    private function generatePaging()
    {
        # Get total page count
        $pages = ceil($this->totalResults / $this->resultsPerPage);

        $this->paging = '<ul class="pagination">';
        # Don't show pagination if there's only one page
        if ($pages == 0 || $pages == 1) {
            $this->paging .= '</ul>';
            return $this->paging;
        }


        # Show back to first page if not first page
        if ($this->page != 1) {
            $this->paging .= "<li><a href='" . $this->baseUrl . "page/1'><<</a></li>";
        }

        # Create a link for each page
        //max 3 page prev and 3 next


        $pageCount = 1;
        while ($pageCount <= $pages) {
            if ($pageCount - 3 <= $this->page && $pageCount + 3 >= $this->page) {
                $class = "";
                if ($pageCount == $this->page) {
                    $class = "active";
                }
                $this->paging .= "<li class='$class'><a href='" . $this->baseUrl . "page/" . $pageCount . "'>" . $pageCount . "</a></li>";
            }

            $pageCount++;
        }

        # Show go to last page option if not the last page
        if ($this->page != $pages) {
            $this->paging .= "<li><a href='" . $this->baseUrl . "page/" . $pages . "'>>></a></li>";
        }

        $this->paging .= '</ul>';

        return $this->paging;
    }

}
