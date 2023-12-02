<?php
/**
 * Pager
 */
class PagingViewHelper {
	
	public static function paging($url, $total, $current)
	{
		if ($total <= 1)
			return '';
		if ($total <= 5)
		{
			$contentBlock = array();
			for ($i = 1; $i <= $total; $i++)
			{
				$link = str_replace(':page:', $i, $url);
				if ($i == $current)
				{
					$contentBlock[] = '<a>'.$i.'</a>';
				} else {
					$contentBlock[] = '<a data-page="'.$i.'" href="'.$link.'">'.$i.'</a>';
				}
			}
			$content = join('&nbsp;&nbsp;', $contentBlock);
			$content = preg_replace('#(</p>&nbsp;&nbsp;&nbsp;\|)#is','</p>',$content);
			$content = preg_replace('#(\|&nbsp;&nbsp;&nbsp;<p)#is','<p',$content);
			return $content;
		}

		if ($current == 1)
		{
			$contentBlock = array();
			for ($i = 1; $i <= 5; $i++)
			{
				$link = str_replace(':page:', $i, $url);
				if ($i == $current)
				{
					$contentBlock[] = '<a>'.$i.'</a>';
				} else {
					$contentBlock[] = '<a  data-page="'.$i.'" href="'.$link.'">'.$i.'</a>';
				}
			}
			$link = str_replace(':page:', $current + 1, $url);
			$contentBlock[] = '<a href="'.$link.'"  data-page="'.($current + 1).'">&raquo;</a>';
			$content = join('&nbsp;&nbsp;', $contentBlock);
			$content = preg_replace('#(</p>&nbsp;&nbsp;&nbsp;\|)#is','</p>',$content);
			$content = preg_replace('#(\|&nbsp;&nbsp;&nbsp;<p)#is','<p',$content);
			return $content;
		}

		if ($total - $current == 0)
		{
			$contentBlock = array();
			$link = str_replace(':page:', $current - 1, $url);
			$contentBlock[] = '<a href="'.$link.'" data-page="'.($current - 1).'">&laquo;</a>';
			for ($i = $total - 4; $i <= $total; $i++)
			{
				$link = str_replace(':page:', $i, $url);
				if ($i == $current)
				{
					$contentBlock[] = '<a>'.$i.'</a>';
				} else {
					$contentBlock[] = '<a href="'.$link.'" data-page="'.($i).'">'.$i.'</a>';
				}
			}
			$content = join('&nbsp;&nbsp;', $contentBlock);
			$content = preg_replace('#(</p>&nbsp;&nbsp;&nbsp;\|)#is','</p>',$content);
			$content = preg_replace('#(\|&nbsp;&nbsp;&nbsp;<p)#is','<p',$content);
			return $content;
		}
		
		$contentBlock = array();
		$link = str_replace(':page:', $current - 1, $url);
		$contentBlock[] = '<a href="'.$link.'" data-page="'.($current - 1).'">&laquo;</a>';
		for ($i = $current - 2; $i <= (($current == 2) ? ($current + 3) : ($current + 2)); $i++)
		{
			if ($i <= $total && $i > 0)
			{
				$link = str_replace(':page:', $i, $url);
				if ($i == $current)
				{
					$contentBlock[] = '<a >'.$i.'</a>';
				} else {
					$contentBlock[] = '<a href="'.$link.'" data-page="'.($i).'">'.$i.'</a>';
				}
			}
		}
		$link = str_replace(':page:', $current + 1, $url);
		$contentBlock[] = '<a href="'.$link.'" data-page="'.($current + 1).'">&raquo;</a>';
		$content = join('&nbsp;&nbsp;', $contentBlock);
		$content = preg_replace('#(</p>&nbsp;&nbsp;&nbsp;\|)#is','</p>',$content);
		$content = preg_replace('#(\|&nbsp;&nbsp;&nbsp;<p)#is','<p',$content);
		return $content;
	}

    public function diseasePaging($url, $total, $current)
    {
        if ($total <= 1)
            return '';
        if ($total <= 5)
        {
            $contentBlock = array();
            for ($i = 1; $i <= $total; $i++)
            {
                $link = str_replace(':page:', $i, $url);
                if ($i == $current)
                {
                    $contentBlock[] = '<li class="current" ><a>'.$i.'</a></li>';
                } else {
                    $contentBlock[] = '<li><a href="'.$link.'">'.$i.'</a></li>';
                }
            }
            $content = join('&nbsp;&nbsp;', $contentBlock);
            $content = preg_replace('#(</p>&nbsp;&nbsp;&nbsp;\|)#is','</p>',$content);
            $content = preg_replace('#(\|&nbsp;&nbsp;&nbsp;<p)#is','<p',$content);
            return $content;
        }

        if ($current == 1)
        {
            $contentBlock = array();
            for ($i = 1; $i <= 5; $i++)
            {
                $link = str_replace(':page:', $i, $url);
                if ($i == $current)
                {
                    $contentBlock[] = '<li class="current"><a>'.$i.'</a></li>';
                } else {
                    $contentBlock[] = '<li><a href="'.$link.'">'.$i.'</a></li>';
                }
            }
            $link = str_replace(':page:', $current + 1, $url);
            $contentBlock[] = '<li><a href="'.$link.'">&raquo;</a></li>';
            $content = join('&nbsp;&nbsp;', $contentBlock);
            $content = preg_replace('#(</p>&nbsp;&nbsp;&nbsp;\|)#is','</p>',$content);
            $content = preg_replace('#(\|&nbsp;&nbsp;&nbsp;<p)#is','<p',$content);
            return $content;
        }

        if ($total - $current == 0)
        {
            $contentBlock = array();
            $link = str_replace(':page:', $current - 1, $url);
            $contentBlock[] = '<li><a href="'.$link.'">&laquo;</a></li>';
            for ($i = $total - 4; $i <= $total; $i++)
            {
                $link = str_replace(':page:', $i, $url);
                if ($i == $current)
                {
                    $contentBlock[] = '<li class="current"><a>'.$i.'</a></li>';
                } else {
                    $contentBlock[] = '<li><a href="'.$link.'">'.$i.'</a></li>';
                }
            }
            $content = join('&nbsp;&nbsp;', $contentBlock);
            $content = preg_replace('#(</p>&nbsp;&nbsp;&nbsp;\|)#is','</p>',$content);
            $content = preg_replace('#(\|&nbsp;&nbsp;&nbsp;<p)#is','<p',$content);
            return $content;
        }

        $contentBlock = array();
        $link = str_replace(':page:', $current - 1, $url);
        $contentBlock[] = '<li><a href="'.$link.'">&laquo;</a></li>';
        for ($i = $current - 2; $i <= $current + 2; $i++)
        {
            if ($i <= $total && $i > 0)
            {
                $link = str_replace(':page:', $i, $url);
                if ($i == $current)
                {
                    $contentBlock[] = '<li class="current"><a>'.$i.'</a></li>';
                } else {
                    $contentBlock[] = '<li><a href="'.$link.'">'.$i.'</a></li>';
                }
            }
        }
        $link = str_replace(':page:', $current + 1, $url);
        $contentBlock[] = '<li><a href="'.$link.'">&raquo;</a><li>';
        $content = join('&nbsp;&nbsp;', $contentBlock);
        $content = preg_replace('#(</p>&nbsp;&nbsp;&nbsp;\|)#is','</p>',$content);
        $content = preg_replace('#(\|&nbsp;&nbsp;&nbsp;<p)#is','<p',$content);
        return $content;
    }
}