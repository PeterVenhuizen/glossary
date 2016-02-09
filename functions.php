<?php

    # http://stackoverflow.com/questions/1416697/converting-timestamp-to-time-ago-in-php-e-g-1-day-ago-2-days-ago
    function time_elapsed_string($datetime, $full = false) {
	    date_default_timezone_set('Europe/Amsterdam');
        $now = new DateTime();
        $ago = new DateTime($datetime, new DateTimeZone('CET'));
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }

    function remove_lonely_tags($db) {
        
        # Check for lonely tags
        # Get all tags from the tags table
        $tag_count = array();
        try { 
            $stmt = $db->prepare("SELECT tag FROM tags");
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                foreach ($stmt as $row) { $tag_count[$row['tag']] = 0; }
            }
        } catch (PDOException $ex) { die(); }

        # Count tags
        try {
            $stmt = $db->prepare("SELECT tags FROM glossary");
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                foreach ($stmt as $row) {
                    $tags = explode(';', $row['tags']);
                    foreach ($tags as &$tag) {
                        if (isset($tag_count[$tag])) {
                            $tag_count[$tag]++; 
                        }
                    }
                }
            }
        } catch (PDOException $ex) { die(); }

        # Check for tags with zero counts
        foreach ($tag_count as $k => $v) {
            if ($v == 0) {
                try {
                    $stmt = $db->prepare("DELETE FROM tags WHERE tag = :tag");
                    $stmt->bindValue('tag', $k);
                    $stmt->execute();
                } catch (PDOException $ex) { die(); }
            }
        }
    }
    
    # https://designschool.canva.com/blog/100-color-combinations/ (24)
    $colors = array('#F98866', '#FF420E', '#80DB9E', '#89DA59', '#90AFC5', '#336B87', '#2A3132', '#763626', '#505160', '#68829E', 
                    '#AEBD38', '#598234', '#003B46', '#07575B', '#66A5AD', '#C4DFE6', '#2E4600', '#486B00', '#A2C523', '#375E97', 
                    '#FB6542', '#FFBB00', '#3F681C', '#F18D9E', '#324851', '#86AC41', '#34675C', '#7DA3A1', '#4CB5F5', '#B7B8B6', 
                    '#34675C', '#B3C100', '#F4CC70', '#DE7A22', '#20948B', '#6AB187', '#C99E10', '#1E434C', '#F1F1F2', '#BCBABE', 
                    '#A1D6E2', '#1995AD', '#9A9EAB', '#5D535E', '#EC96A4', '#DFE166', '#011A27', '#063852', '#F0810F', '#E6DF44',
                    '#75B1A9', '#D9B44A', '#4F6457', '#ACD0C0', '#EB8A44', '#F9DC24', '#4B7447', '#8EBA43', '#363237', '#2D4262',
                    '#73605B', '#D09683', '#F52549', '#FA6775', '#FFD64D', '#9BC01C', '#34888C', '#7CAA2D', '#F5E356', '#CB6318');
                    
	# https://gist.github.com/alexkingorg/2158428
	function cf_sort_hex_colors($colors) {
		$map = array(
			'0' => 0,
			'1' => 1,
			'2' => 2,
			'3' => 3,
			'4' => 4,
			'5' => 5,
			'6' => 6,
			'7' => 7,
			'8' => 8,
			'9' => 9,
			'a' => 10,
			'b' => 11,
			'c' => 12,
			'd' => 13,
			'e' => 14,
			'f' => 15,
		);
		$c = 0;
		$sorted = array();
		foreach ($colors as $color) {
			$color = strtolower(str_replace('#', '', $color));
			if (strlen($color) == 6) {
				$condensed = '';
				$i = 0;
				foreach (preg_split('//', $color, -1, PREG_SPLIT_NO_EMPTY) as $char) {
					if ($i % 2 == 0) {
						$condensed .= $char;
					}
					$i++;
				}
				$color_str = $condensed;
			}
			$value = 0;
			foreach (preg_split('//', $color_str, -1, PREG_SPLIT_NO_EMPTY) as $char) {
				$value += intval($map[$char]);
			}
			$value = str_pad($value, 5, '0', STR_PAD_LEFT);
			$sorted['_'.$value.$c] = '#'.$color;
			$c++;
		}
		ksort($sorted);
	return $sorted;
}

?>
