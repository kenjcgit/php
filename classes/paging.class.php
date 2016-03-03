<?php
class vmPageNav {
        /** @var int The record number to start dislpaying from */
        var $limitstart = null;
        /** @var int Number of rows to display per page */
        var $limit = null;
        /** @var int Total number of rows */
        var $total = null;
		
		
//        $this->_page = $page;
        //echo count($page);


        // Constructor for initializing the values
        function vmPageNav( $total, $limitstart, $limit, $form ) {

                $this->total = intval( $total );
                $this->form = $form ;
                $this->limitstart = max( $limitstart, 0 );
                $this->limit = max( $limit, 1 );
                if ($this->limit > $this->total) {
                        $this->limitstart = 0;
                }
                if (($this->limit-1)*$this->limitstart > $this->total) {
                        $this->limitstart -= $this->limitstart % $this->limit;
                }
        }
        /**
        * @return string The html for the limit # dropdown box
        */
		
        function getLimitBox () {
				
				global $lang;
				
                $html = "<font class='pagenav'>Record Per Page : </font> ";
                //$html .= "<select name='pageno' class='combo' onchange='document.$this->form.submit();' size='1'>";
				$html .= "<select name='pageno' class='combo' onchange='changelimitstart()' size='1'>";
                for ($i=5; $i <= 30; $i+=5) {
                        if ($this->limit == $i){
                                $selected = "selected = 'selected'";
                        }
                        else{
                                $selected = '';
                        }
                        $html .= "<option value='$i' ".$selected." >$i</option>" ;
                }
                if ($this->limit == 50)
                        $selected50 = "selected = 'selected'";
                else
                        $selected50 = "";

                if ($this->limit == 100)
                        $selected100 = "selected = 'selected'";
                else
                        $selected100 = "";

                $html .= "<option value='50' ".$selected50." >50</option>" ;
                $html .= "<option value='100' ".$selected100." >100</option>" ;
                $html .= "</select>";
                $html .= "\n<input type=\"hidden\" name=\"limitstart\" value=\"$this->limitstart\" />";
                return $html;
        }
        /**
        * Display the html limit # dropdown box
        */
        function writeLimitBox () {
                echo $this->getLimitBox();
        }
        function writePagesCounter() {
                echo $this->getPagesCounter();
        }
        /**
        * @return string The html for the pages counter, eg, Results 1-10 of x
        */
        function getPagesCounter() {
                global $lang;
            $html = '';
                $from_result = $this->limitstart+1;
                if ($this->limitstart + $this->limit < $this->total) {
                        $to_result = $this->limitstart + $this->limit;
                } else {
                        $to_result = $this->total;
                }
                if ($this->total > 0) {
                        $html .= "Results ".$from_result." - ".$to_result." Results of ".$this->total;
                } else {
                        //$html .= "\nNo records found.";
                }
                return $html;
        }
        /**
        * Writes the html for the pages counter, eg, Results 1-10 of x
        */
        function writePagesLinks() {
                //global $page;
            echo $this->getPagesLinks();
        }
        /**
        * @return string The html links for pages, eg, previous, next, 1 2 3 ... x
        */
        function getPagesLinks() {
                global $lang;
            $html = '';
                $displayed_pages = 10;
                $total_pages = ceil( $this->total / $this->limit );
                $this_page = ceil( ($this->limitstart+1) / $this->limit );
                $start_loop = (floor(($this_page-1)/$displayed_pages))*$displayed_pages+1;
                if ($start_loop + $displayed_pages - 1 < $total_pages) {
                        $stop_loop = $start_loop + $displayed_pages - 1;
                } else {
                        $stop_loop = $total_pages;
                }

                if ($this_page > 1) {
                        $page = ($this_page - 2) * $this->limit;
                        $html .= "\n<a href=\"#beg\" title=\"first page\" onclick=\"javascript: document.$this->form.limitstart.value=0; document.$this->form.submit();return false;\" style='font-size:12px;' class='footer-phone-start1'>&lt;&lt; First</a>";
                        $html .= "\n<a href=\"#prev\"  title=\"previous page\" onclick=\"javascript: document.$this->form.limitstart.value=$page; document.$this->form.submit();return false;\" style='font-size:12px;' class='footer-phone-start1'>&lt; Prev</a>";
                } else {
                        $html .= "\n<span class=\"pagenav\" style=\"font-size:12px;\">&lt;&lt; First</span>";
                        $html .= "\n<span class=\"pagenav\" style=\"font-size:12px;\">&lt; Prev</span>";
                }

                for ($j=$start_loop; $j <= $stop_loop; $j++) {
                        $page = ($j - 1) * $this->limit;
                        if ($j == $this_page) {
                                $html .= "\n<span class=\"pagenav\" style=\"font-size:12px;\"> $j </span>";
                        } else {
                                $html .= "\n<a href=\"#$j\" onclick=\"javascript: document.$this->form.limitstart.value=$page; document.$this->form.submit();return false;\" style='font-size:12px;' class='footer-phone-start1'><strong>$j</strong></a>";
                        }
                }

                if ($this_page < $total_pages) {
                        $page = $this_page * $this->limit;
                        $end_page = ($total_pages-1) * $this->limit;
                        $html .= "\n<a href=\"#next\"  title=\"next page\" onclick=\"javascript: document.$this->form.limitstart.value=$page; document.$this->form.submit();return false;\" style='font-size:12px;' class='footer-phone-start1'> Next &gt;</a>";
                        $html .= "\n<a href=\"#end\"  title=\"end page\" onclick=\"javascript: document.$this->form.limitstart.value=$end_page; document.$this->form.submit();return false;\" style='font-size:12px;' class='footer-phone-start1'> Last &gt;&gt;</a>";
                } else {
                        $html .= "\n<span class=\"pagenav\" style=\"font-size:12px;\">Next &gt;</span>";
                        $html .= "\n<span class=\"pagenav\" style=\"font-size:12px;\">Last&gt;&gt;</span>";
                }
                return $html;
        }

        function getListFooter() {
            $html = '<table class="adminlist">';
            if( $this->total > $this->limit || $this->limitstart > 0) {

                    $html .= '<tr><th colspan="3">';

                        $html .= $this->getPagesLinks();
                        $html .= '</th></tr>';
            }
                $html .= '<tr><td nowrap="true" width="48%" align="right">'.DISPLAY.'</td>';
                $html .= '<td>' .$this->getLimitBox() . '</td>';
                $html .= '<td nowrap="true" width="48%" align="left">' . $this->getPagesCounter() . '</td>';
                $html .= '</tr></table>';
                  return $html;
        }
/**
* @param int The row index
* @return int
*/
        function rowNumber( $i ) {
                return $i + 1 + $this->limitstart;
        }
/**
* @param int The row index
* @param string The task to fire
* @param string The alt text for the icon
* @return string
*/
        function orderUpIcon( $i, $condition=true, $task='orderup', $alt='Move Up', $page, $func ) {
                global $mosConfig_live_site;
                if (($i > 0 || ($i+$this->limitstart > 0)) && $condition) {
                    return '<a href="#reorder" onclick="return vm_listItemTask(\'cb'.$i.'\',\''.$task.'\', \'$this->form\', \''.$page.'\', \''.$func.'\')" title="'.$alt.'" style=\'font-size:12px;\' class=\'footer-phone-start1\'>
                                <img src="'.$mosConfig_live_site.'/administrator/images/uparrow.png" width="12" height="12" border="0" alt="'.$alt.'" />
                        </a>';
                  } else {
                      return '&nbsp;';
                }
        }
/**
* @param int The row index
* @param int The number of items in the list
* @param string The task to fire
* @param string The alt text for the icon
* @return string
*/
        function orderDownIcon( $i, $n, $condition=true, $task='orderdown', $alt='Move Down', $page, $func ) {
                global $mosConfig_live_site;
                if (($i < $n-1 || $i+$this->limitstart < $this->total-1) && $condition) {
                        return '<a href="#reorder" onclick="return vm_listItemTask(\'cb'.$i.'\',\''.$task.'\', \'$this->form\', \''.$page.'\', \''.$func.'\')" title="'.$alt.'" style=\'font-size:12px;\' class=\'footer-phone-start1\'>
                                <img src="'.$mosConfig_live_site.'/administrator/images/downarrow.png" width="12" height="12" border="0" alt="'.$alt.'" />
                        </a>';
                  } else {
                      return '&nbsp;';
                }
        }
}
?>