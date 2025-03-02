<?php
namespace App\Pagination;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Request;

class CustomPaginator extends LengthAwarePaginator
{
    /**
     * Generate pagination elements.
     *
     * @return array
     */
    public function elements()
    {
        return [
            'first' => $this->url(1),
            'prev' => $this->previousPageUrl(),
            'next' => $this->nextPageUrl(),
            'last' => $this->url($this->lastPage()),
            'pages' => $this->getPageLinks(),
        ];
    }

    /**
     * Get an array of page links.
     *
     * @return array
     */
    protected function getPageLinks()
    {
        $pages = [];
        for ($i = 1; $i <= $this->lastPage(); $i++) {
            $pages[] = [
                'page' => $i,
                'url' => $this->url($i),
                'active' => $i == $this->currentPage(),
            ];
        }
        return $pages;
    }
    
    /**
     * Customize pagination links output.
     */
    public function links($view = null, $data = [])
    {
        $view = $view ?: 'pagination.default';
        return view($view, array_merge(['paginator' => $this], $data));
    }
}
