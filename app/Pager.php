<?php
class Pager
{
    protected $iterator;
    protected $current;
    protected $pages_count;        // number of pages
    protected $base_url;
    protected $url_params;

    public function __construct($base_url, $items_count, $items_per_page, $current_page, array $url_params=array(), $width = 10)
    {
        $min_width = 7;
        if ($width < $min_width) {
            throw new Exception("Pager width should be equal or more than 7. You pass '" . $width . "'.");
        }
        if ($current_page < 1) {
            $current_page = 1;
        }

        $n = ceil($items_count / $items_per_page); // number of pages total

        $iterator = array();

        if ($items_count > $items_per_page) {
            $iterator[] = (int) $current_page;

            // Wings
            $l = $current_page-1;
            $r = $current_page+1;
            while (count($iterator) < min($width, $n)) {
                if ($l>=1) {
                    array_unshift($iterator, $l--);
                    if (count($iterator) == min($width, $n)) {
                        break;
                    };
                };
                if ($r<=$n) {
                    array_push($iterator, $r++);
                }
            };

            // Right end
            if ($iterator[count($iterator)-2] != $n-1) {
                $iterator[count($iterator)-2] = "...";
            };
            $iterator[count($iterator)-1] = $n;

            // Left end
            if ($iterator[1] > 2) {
                $iterator[1] = "...";
            };
            $iterator[0] = 1;
        };


        $this->iterator   = $iterator;
        $this->items_count = $items_count;
        $this->pages_count   = $n;
        $this->current = $current_page;
        $this->url_template = $base_url;
        $this->url_params   = $url_params;
    }

    public function links(): array
    {
        $links = array();
        // previous link
        if ($this->current > 1) {
            $links[$this->current - 1] = $this->url($this->current - 1);
        };
        // visible pages links
        foreach ($this->iterator as $p) {
            if (is_numeric($p)) {
                $links[$p] = $this->url($p);
            };
        };
        // next link
        if ($this->current < $this->pages_count) {
            $links[$this->current + 1] = $this->url($this->current + 1);
        };

        return $links;
    }

    public function __get($key)
    {
        if (isset($this->$key)) {
            return $this->$key;
        }

        throw new Exception('Class Pager has no property "' . $key . '".');
    }

    private function url(int $page): string
    {
        $data = $this->url_params;
        $data["page"] = $page;

        $url = $this->base_url . '?' . http_build_query($data);

        return $url;
    }


}
