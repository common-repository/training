<?php
/**
 * This File for common use.
 * @author	Rudra Innnovative Software 
 * @package	training/views 
 * @version	1.0 
 */
if (!defined("ABSPATH"))
    exit;

global $wpdb;
$post_slug = '';
if (!is_admin()) {
    global $post;
    $post_slug = $post->post_name;
}

if (is_admin() && $post_slug != "training-tool") {
    include_once 'menus.php';
}
if ($post_slug == "training-tool") {
    echo "<style> ul.tabstop{display:none;} </style>";
}
echo "<style> #wpfooter span#footer-thankyou{display:none;} </style>";
?>
<div class="msg" id="rtr-notify-alert">
    <div class="messdv"></div>
</div>

<?php if (is_admin()) { ?>
    <div id="reordermodal" class="rtr-modal bs-modal fade">
        <div class="rtr-modal-dialog rtr-modal-lg">
            <div class="rtr-modal-content">
                <div class="rtr-modal-header">
                    <button type="button" class="rtr-close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title rtr-fs-4 rtr-m-0 reordertitl rtr-fs-4 rtr-m-0">Re-Order </h4>
                </div>
                <div class="rtr-modal-body">
                    <form action="#" method="post" id="reorderrows" name="reorderrows" class="form-horizontal">
                        <div class="loadergif">
                            <img src="<?php echo RTR_WPL_COUNT_PLUGIN_URL; ?>/assets/css/images/loading.gif" />
                        </div>
                    </form>
                </div>
                <div class="rtr-modal-footer">
                    <button type="button" class="rtr-btn rtr-btn-primary reordersave">Save</button>
                    <button type="button" data-dismiss="modal" class="rtr-btn rtr-reorder-cancel">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <?php
}

function sortByOrder($a, $b)
{
    return $a['order'] - $b['order'];
}

function limit_text($text, $limit, $more = true)
{

    $txt = '<button class="rtr-ms-1 more_ifo moreinfo rtr-bg-transparent rtr-p-0 rtr-text-dark">more</button>';
    $text = html_entity_decode(stripslashes($text));
    // $text = TruncateHTML::truncateWords($text, $limit, $ellipsis = '...', $txt, $more);
    $text = wp_trim_words($text, $limit, $txt);
    if ($more == false) {
        $text = strip_tags($text);
    }

    return $text;
}

function get_count_courses()
{
    global $current_user;
    global $wpdb;
    $current_user = wp_get_current_user();
    $user_id = isset($current_user->data->ID) ? $current_user->data->ID : 0;
    $count = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT count(*) FROM " . rtr_wpl_tr_courses() . " WHERE created_by = %d",
            $user_id
        )
    );
    return $count;
}

function limit_text1($text, $limit, $more = true)
{
    $txt = '';
    $text = html_entity_decode(stripslashes($text));

    if (str_word_count($text, 0) > $limit) {
        $words = str_word_count($text, 2);
        $pos = array_keys($words);
        $text = substr($text, 0, $pos[$limit]) . '... ';
        if ($more == true)
            $txt = '<button class="rtr-ms-1 more_ifo moreinfo rtr-bg-transparent rtr-p-0 rtr-text-dark">more</button>';
    }
    if ($more == true) {
        $text = str_replace(array('<div', '</div>', '<p>', '</p>'), array('<span', '</span>', '', ''), $text);
    } else {
        $text = strip_tags($text);
    }
    return $text . $txt;
}

function full_text($text)
{
    $text = stripslashes($text);
    $txt = '<button class="rtr-bg-transparent rtr-p-0 rtr-text-dark more_ifo lessinfo rtr-mb-2">less</button>';
    $text = str_replace(array('<div', '</div>'), array('<span', '</span>'), $text);
    return $text . $txt;
}

function get_project_links($resource_id)
{

    global $wpdb;
    global $current_user;
    $user_id = $current_user->ID;
    $proj_links = $wpdb->get_row(
        $wpdb->prepare
        (
            "SELECT links FROM " . rtr_wpl_tr_projects() . " WHERE resource_id = %d AND user_id = %d",
            $resource_id,
            $user_id
        )
    );
    $links = '';
    if (!empty($proj_links)) {

        $projlinks = explode(",", $proj_links->links);
        if (!empty($projlinks)) {
            foreach ($projlinks as $link) {
                if (empty($link)) {
                    continue;
                }
                $links .= "<a target='_blank' href='$link'>$link</a> <br/>";
            }
        }
    }

    return $links;
}

function get_project_links_back($user_id, $resource_id)
{

    global $wpdb;
    $proj_links = $wpdb->get_row(
        $wpdb->prepare
        (
            "SELECT links FROM " . rtr_wpl_tr_projects() . " WHERE resource_id = %d AND user_id = %d",
            $resource_id,
            $user_id
        )
    );
    $links = '';
    if (!empty($proj_links)) {

        /* $projlinks = explode(",", $proj_links->links);
          if (!empty($projlinks)) {
          foreach ($projlinks as $link) {
          if (empty($link)) {
          continue;
          }
          if (!preg_match("/http/", $link)) {
          $link = "http://" . $link;
          }
          $links .= "<a target='_blank' href='$link'>$link <i class='fa fa-share' aria-hidden='true'></i></a> <br/>";
          }
          } */
        $projlinks = unserialize($proj_links->links);
        $links = '';
        foreach ($projlinks as $lnk) {
            $links .= '<a href="' . $lnk . '" target="_blank">' . $lnk . '</a>';
        }
    }

    return $links;
}

function get_mentor($user_id, $course_id)
{

    global $wpdb;
    $usertbl = $wpdb->prefix . "users";
    $mentor = $wpdb->get_row
    (
        $wpdb->prepare
        (
            "SELECT map.*,m.display_name,m.user_email "
            . "FROM " . TR_mentor_assign() . " map INNER JOIN " . $usertbl . " m ON map.mentor_id = m.ID "
            . "WHERE map.user_id = %d AND map.course_id = %d",
            $user_id,
            $course_id
        )
    );
    return $mentor;
}

function get_next_calldate($user_id, $course_id, $mentor_id)
{
    return '';
}

function get_submissions($user_id, $course_id)
{
    global $wpdb;

    $submits = $wpdb->get_results
    (
        $wpdb->prepare
        (
            "SELECT p.*,r.title as exercise_title,r.course_id,r.module_id,r.lesson_id,r.total_hrs,"
            . "l.title as lesson_title,m.title as mod_title FROM " . rtr_wpl_tr_projects() . " p "
            . " INNER JOIN " . rtr_wpl_tr_resources() . " r ON p.resource_id = r.id INNER JOIN " . rtr_wpl_tr_lessons() . " l ON r.lesson_id = l.id"
            . " INNER JOIN " . rtr_wpl_tr_modules() . " m ON r.module_id = m.id "
            . "WHERE p.user_id = %d AND r.course_id = %d ",
            $user_id,
            $course_id
        )
    );


    return $submits;
}

function get_project($type, $course_id, $chk = '')
{

    return '';
    die;
    global $wpdb;
    global $current_user;
    $user_id = $current_user->ID;
    $col = 'course_id';
    if ($type == 'module')
        $col = 'module_id';

    $current_user = wp_get_current_user();
    $exercise = $wpdb->get_row(
        $wpdb->prepare
        (
            "SELECT id,title,description,total_hrs,created_by,created_dt FROM " . rtr_wpl_tr_project_exercise() . " "
            . "WHERE $col = %d AND type = %s AND status = 1",
            $course_id,
            $type
        )
    );

    if ($chk == 'check') {
        if (!empty($exercise)) {
            $proj_links = $wpdb->get_row(
                $wpdb->prepare
                (
                    "SELECT links FROM " . rtr_wpl_tr_projects() . " WHERE exercise_id = %d AND user_id = %d",
                    $exercise->id,
                    $user_id
                )
            );
            $exercise = (object) array_merge((array) $exercise, (array) $proj_links);
        }
    }

    return $exercise;
}

class TruncateHTML
{

    public static function truncateChars($html, $limit, $ellipsis = '...')
    {

        if ($limit <= 0 || $limit >= strlen(strip_tags($html)))
            return $html;

        $dom = new DOMDocument();
        $dom->loadHTML($html);

        $body = $dom->getElementsByTagName("body")->item(0);

        $it = new DOMLettersIterator($body);

        foreach ($it as $letter) {
            if ($it->key() >= $limit) {
                $currentText = $it->currentTextPosition();
                $currentText[0]->nodeValue = substr($currentText[0]->nodeValue, 0, $currentText[1] + 1);
                self::removeProceedingNodes($currentText[0], $body);
                self::insertEllipsis($currentText[0], $ellipsis);
                break;
            }
        }

        return preg_replace('~<(?:!DOCTYPE|/?(?:html|head|body))[^>]*>\s*~i', '', $dom->saveHTML());
    }

    public static function truncateWords($html, $limit, $txt, $more, $ellipsis = '...')
    {

        if ($limit <= 0 || $limit >= self::countWords(strip_tags($html)))
            return $html;

        $dom = new DOMDocument();
        $dom->loadHTML($html);

        $body = $dom->getElementsByTagName("body")->item(0);

        $it = new DOMWordsIterator($body);

        foreach ($it as $word) {
            if ($it->key() >= $limit) {
                $currentWordPosition = $it->currentWordPosition();
                $curNode = $currentWordPosition[0];
                $offset = $currentWordPosition[1];
                $words = $currentWordPosition[2];

                $curNode->nodeValue = substr($curNode->nodeValue, 0, $words[$offset][1] + strlen($words[$offset][0]));

                self::removeProceedingNodes($curNode, $body);
                self::insertEllipsis($curNode, $ellipsis);
                break;
            }
        }
        if ($more == FALSE)
            $txt = '';

        return preg_replace('~<(?:!DOCTYPE|/?(?:html|head|body))[^>]*>\s*~i', '', $dom->saveHTML()) . $txt;
    }

    private static function removeProceedingNodes(DOMNode $domNode, DOMNode $topNode)
    {
        $nextNode = $domNode->nextSibling;

        if ($nextNode !== NULL) {
            self::removeProceedingNodes($nextNode, $topNode);
            $domNode->parentNode->removeChild($nextNode);
        } else {
            //scan upwards till we find a sibling
            $curNode = $domNode->parentNode;
            while ($curNode !== $topNode) {
                if ($curNode->nextSibling !== NULL) {
                    $curNode = $curNode->nextSibling;
                    self::removeProceedingNodes($curNode, $topNode);
                    $curNode->parentNode->removeChild($curNode);
                    break;
                }
                $curNode = $curNode->parentNode;
            }
        }
    }

    private static function insertEllipsis(DOMNode $domNode, $ellipsis)
    {
        $avoid = array('a', 'strong', 'em', 'h1', 'h2', 'h3', 'h4', 'h5'); //html tags to avoid appending the ellipsis to

        if (in_array($domNode->parentNode->nodeName, $avoid) && $domNode->parentNode->parentNode !== NULL) {
            // Append as text node to parent instead
            $textNode = new DOMText($ellipsis);

            if ($domNode->parentNode->parentNode->nextSibling)
                $domNode->parentNode->parentNode->insertBefore($textNode, $domNode->parentNode->parentNode->nextSibling);
            else
                $domNode->parentNode->parentNode->appendChild($textNode);
        } else {
            // Append to current node
            $domNode->nodeValue = rtrim($domNode->nodeValue) . $ellipsis;
        }
    }

    private static function countWords($text)
    {
        $words = preg_split("/[\n\r\t ]+/", $text, -1, PREG_SPLIT_NO_EMPTY);
        return count($words);
    }

}

final class DOMWordsIterator implements Iterator
{

    private $start, $current;
    private $offset, $key, $words;

    /**
     * expects DOMElement or DOMDocument (see DOMDocument::load and DOMDocument::loadHTML)
     */
    function __construct(DOMNode $el)
    {
        if ($el instanceof DOMDocument)
            $this->start = $el->documentElement;
        elseif ($el instanceof DOMElement)
            $this->start = $el;
        else
            throw new InvalidArgumentException("Invalid arguments, expected DOMElement or DOMDocument");
    }

    /**
     * Returns position in text as DOMText node and character offset.
     * (it's NOT a byte offset, you must use mb_substr() or similar to use this offset properly).
     * node may be NULL if iterator has finished.
     *
     * @return array
     */
    function currentWordPosition()
    {
        return array($this->current, $this->offset, $this->words);
    }

    /**
     * Returns DOMElement that is currently being iterated or NULL if iterator has finished.
     *
     * @return DOMElement
     */
    function currentElement()
    {
        return $this->current ? $this->current->parentNode : NULL;
    }

    // Implementation of Iterator interface
    function key(): mixed
    {
        return $this->key;
    }

    function next(): void
    {
        if (!$this->current) {
            return;
        }

        if ($this->current->nodeType == XML_TEXT_NODE || $this->current->nodeType == XML_CDATA_SECTION_NODE) {
            if ($this->offset == -1) {
                $this->words = preg_split("/[\n\r\t ]+/", $this->current->textContent, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_OFFSET_CAPTURE);
            }
            $this->offset++;

            if ($this->offset < count($this->words)) {
                $this->key++;
                return;
            }
            $this->offset = -1;
        }

        while ($this->current->nodeType == XML_ELEMENT_NODE && $this->current->firstChild) {
            $this->current = $this->current->firstChild;
            if ($this->current->nodeType == XML_TEXT_NODE || $this->current->nodeType == XML_CDATA_SECTION_NODE) {
                $this->next(); // Call without returning
                return;
            }
        }

        while (!$this->current->nextSibling && $this->current->parentNode) {
            $this->current = $this->current->parentNode;
            if ($this->current === $this->start) {
                $this->current = NULL;
                return;
            }
        }

        $this->current = $this->current->nextSibling;

        if ($this->current) {
            $this->next(); // Call without returning
        }
    }

    function current(): mixed
    {
        if ($this->current)
            return $this->words[$this->offset][0];
        return NULL;
    }

    function valid(): bool
    {
        return !!$this->current;
    }

    function rewind(): void
    {
        $this->offset = -1;
        $this->words = array();
        $this->current = $this->start;
        $this->next();
    }

}

final class DOMLettersIterator implements Iterator
{

    private $start, $current;
    private $offset, $key, $letters;

    /**
     * expects DOMElement or DOMDocument (see DOMDocument::load and DOMDocument::loadHTML)
     */
    function __construct(DOMNode $el)
    {
        if ($el instanceof DOMDocument)
            $this->start = $el->documentElement;
        elseif ($el instanceof DOMElement)
            $this->start = $el;
        else
            throw new InvalidArgumentException("Invalid arguments, expected DOMElement or DOMDocument");
    }

    /**
     * Returns position in text as DOMText node and character offset.
     * (it's NOT a byte offset, you must use mb_substr() or similar to use this offset properly).
     * node may be NULL if iterator has finished.
     *
     * @return array
     */
    function currentTextPosition()
    {
        return array($this->current, $this->offset);
    }

    /**
     * Returns DOMElement that is currently being iterated or NULL if iterator has finished.
     *
     * @return DOMElement
     */
    function currentElement()
    {
        return $this->current ? $this->current->parentNode : NULL;
    }

    // Implementation of Iterator interface
    function key(): mixed
    {
        return $this->key;
    }

    function next(): void
    {
        if (!$this->current)
            return;

        if ($this->current->nodeType == XML_TEXT_NODE || $this->current->nodeType == XML_CDATA_SECTION_NODE) {
            if ($this->offset == -1) {
                // fastest way to get individual Unicode chars and does not require mb_* functions
                preg_match_all('/./us', $this->current->textContent, $m);
                $this->letters = $m[0];
            }
            $this->offset++;
            $this->key++;
            if ($this->offset < count($this->letters))
                return;
            $this->offset = -1;
        }

        while ($this->current->nodeType == XML_ELEMENT_NODE && $this->current->firstChild) {
            $this->current = $this->current->firstChild;
            if ($this->current->nodeType == XML_TEXT_NODE || $this->current->nodeType == XML_CDATA_SECTION_NODE)
                $this->next(); // Recursion, no return value
            return; // Ensure we return after recursive call to avoid additional processing
        }

        while (!$this->current->nextSibling && $this->current->parentNode) {
            $this->current = $this->current->parentNode;
            if ($this->current === $this->start) {
                $this->current = NULL;
                return;
            }
        }

        $this->current = $this->current->nextSibling;

        $this->next();
    }

    function current(): mixed
    {
        if ($this->current)
            return $this->letters[$this->offset];
        return NULL;
    }

    function valid(): bool
    {
        return !!$this->current;
    }

    function rewind(): void
    {
        $this->offset = -1;
        $this->letters = array();
        $this->current = $this->start;
        $this->next();
    }

}

if (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) {
    ?>
    <script type="text/javascript">
        var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
    </script>
    <?php
} else {
    ?>
    <script type="text/javascript">
        var ajaxurl = '<?php echo str_replace(array('https', 'HTTPS'), array('http', 'HTTP'), admin_url('admin-ajax.php')); ?>';
    </script>
    <?php
}
?>