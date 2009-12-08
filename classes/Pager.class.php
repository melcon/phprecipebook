<?php
require_once("classes/DBUtils.class.php");
/*
 * This class provides a pager to go forward and backwards in the results of a query.
 */
class Pager {

	var $page_limit;
	var $max_pages;
	var $page;
	var $sql;
	var $sql_count;
	var $total_results;
	var $dbResults;

	/*
		Constructor the pager.  This sets up the limit for the query and returns
		the data.
	*/
	function Pager($sql_count, $sql, $current_page)
	{
		global $g_rb_database_type, $g_rb_pagerLimit, $DB_LINK;
		$this->page_limit = $g_rb_pagerLimit;
		$this->page = $current_page;
		$this->sql = $sql;

		if ($g_rb_database_type=="postgres")
		{
			$sql .= " LIMIT " . $this->limit;
			if ($this->page > 0)
			{
				$sql .= " OFFSET " . (($this->page-1) * $this->page_limit);
			}
		}
		else if ($g_rb_database_type=="mysql")
		{
			$sql .= " LIMIT ";
			if ($this->page > 0)
			{
				$sql .=  (($this->page-1) * $this->page_limit) . ", ";
			}
			$sql .= $this->page_limit;
		}

		// Get the count
		$rc = $DB_LINK->Execute( $sql_count );
		DBUtils::checkResult($rc, NULL, NULL, $sql_count);
		$this->total_results = $rc->fields[0];

		// Make the query and set the results
		$this->dbResults = $DB_LINK->Execute( $sql );
		DBUtils::checkResult($this->dbResults, NULL, NULL, $sql);

		// Compute the max number of pages
		$this->max_pages = ceil($this->total_results/$this->page_limit);
	}

	function getPagerScript($php_page)
	{
	?>
	<script language="JavaScript">
		/*
			Go forward one page
		*/
		function nextPage()
		{
			document.location = "<?php echo $php_page;?>" + "&page=<?php echo ($this->page+1);?>";
		}

		/*
			Go back one page
		*/
		function previousPage()
		{
			document.location = "<?php echo $php_page;?>" + "&page=<?php echo ($this->page-1);?>";
		}

		/*
			Goto a specific page in the search results
		*/
		function gotoPage(pageNum)
		{
			document.location = "<?php echo $php_page;?>" + "&page=" + pageNum;
		}
		</script>
	<?php
	}

	function showPager($current_page)
	{
		global $g_rb_theme;

		if ($this->total_results > $this->page_limit)
		{?>
			<table width="400" cellspacing="0" cellpadding="0" border="0">
			<tr>
			<td align="center"><b></b><br>
			<table border=0 cellpadding="0" cellspacing="2">
			<tr>
			<?php
			// Show back buttons if necessary
			if ($current_page > 1)
			{?>
				<td valign="middle">
					<a href="JavaScript:gotoPage(1);" onMouseOver="window.status='First Page'; return true;" onMouseout="window.status=' '; return true"><img src="themes/<?php echo $g_rb_theme;?>/images/first.gif" border="0" alt="First Page"></a>
				</td>
				<td>
					<a href="JavaScript:previousPage();" onMouseOver="window.status='Previous Page'; return true;" onMouseout="window.status=' '; return true"><img src="themes/<?php echo $g_rb_theme;?>/images/previous.gif" border="0" alt="Previous Page"></a>
				</td>
		<?php  }

			$page_span = 1;
			$total_pages = $this->max_pages;

			// We have more pages then can be shown on the pager, wrap them around
			if ($this->max_pages > 10 && $current_page <= 5)
			{
				$total_pages = 10;
			}
			else if ($this->max_pages > 10 && $current_page > 5)
			{
				$total_pages = $current_page + 4;
				$page_span = $current_page - 4;
			}

			if ($total_pages > $this->max_pages)
				$total_pages = $this->max_pages;

			echo '<td align="center" style="padding-left:20px;padding-right:20px;">' . "\n";
			for ($page_span; $page_span <= $total_pages; $page_span++)
			{
				if ($page_span == $current_page)
				{
					echo $page_span . " ";
				}
				else
				{
					echo '<a href="JavaScript:gotoPage(' . $page_span .
						')" onMouseOver="window.status=\'Page ' . $page_span . '\'; return true;"' .
						' onMouseout="window.status=\' \'; return true">' .
						$page_span .'</a> ';
				}
				echo "\n";
			}
			echo "</td>\n";

			if ($current_page < $total_pages)
			{?>
				<td>
					<a href="JavaScript:nextPage();" onMouseOver="window.status='Next Page'; return true;" onMouseout="window.status=' '; return true"><img src="themes/<?php echo $g_rb_theme;?>/images/next.gif" border="0" alt="Next Page"></a>
				</td>
				<td valign="middle">
					<a href="JavaScript:gotoPage(<?php echo $this->max_pages;?>);" onMouseOver="JavaScript:window.status='Last Page'; return true;"  onMouseout="window.status=' '; return true"><img src="themes/<?php echo $g_rb_theme;?>/images/last.gif" border="0" alt="Last Page"></a>
				</td>
		<?php   }?>
			</tr>
		</table>
		</td>
	</tr>
	</table>
<?php   }
    }
}
?>
