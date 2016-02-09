		<div class="spacer"></div>
        <footer id="page_footer">
            <nav>
            <?php
                
                # Get all tags
                $tag_count = array();
                try {
                    $stmt = $db->prepare("SELECT tag, color FROM tags");
                    $stmt->execute();
                    if ($stmt->rowCount() > 0) { 
                        foreach ($stmt as $row) { $tag_count[$row['tag']] = array($row['color'], 0); }   
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
                                if (isset($tag_count[$tag])) { $tag_count[$tag][1]++; }   
                            }
                        }
                    }
                } catch (PDOException $ex) { die(); }

                # Output tags
                foreach ($tag_count as $k => $v) {
                	echo '	<a class="tag" href="view.php?cat=' . str_replace(' ', '_', $k) . '" style="background-color: ' . $v[0] . ';">'. $k . ' ' . $v[1] . '</a>';
                
                	/*
                    echo '  <div class="tag_overview">
                                <h3 style="background-color: ' . $v[0] . ';">' . $k . '</h3>
                                <div class="tag_group">';
                    for ($x = 0; $x < $v[1]; $x++) { echo '<div class="square" style="background-color: ' . $v[0] . ';"></div>'; }
                    echo '      </div>
                            </div>';
                    */
                }

            ?>
            </nav>
		</footer>
