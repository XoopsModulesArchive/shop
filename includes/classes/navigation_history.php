<?php
/*
  $Id: navigation_history.php,v 1.1 2006/03/27 09:08:12 mikhail Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

class navigationHistory
{
    public $path;

    public $snapshot;

    public function __construct()
    {
        $this->reset();
    }

    public function reset()
    {
        $this->path = [];

        $this->snapshot = [];
    }

    public function add_current_page()
    {
        global $PHP_SELF, $_GET, $_POST, $request_type, $cPath;

        $set = 'true';

        for ($i = 0, $n = count($this->path); $i < $n; $i++) {
            if (($this->path[$i]['page'] == basename($PHP_SELF))) {
                if (isset($cPath)) {
                    if (!isset($this->path[$i]['get']['cPath'])) {
                        continue;
                    }  

                    if ($this->path[$i]['get']['cPath'] == $cPath) {
                        array_splice($this->path, ($i + 1));

                        $set = 'false';

                        break;
                    }  

                    $old_cPath = explode('_', $this->path[$i]['get']['cPath']);

                    $new_cPath = explode('_', $cPath);

                    for ($j = 0, $n2 = count($old_cPath); $j < $n2; $j++) {
                        if ($old_cPath[$j] != $new_cPath[$j]) {
                            array_splice($this->path, ($i));

                            $set = 'true';

                            break 2;
                        }
                    }
                } else {
                    array_splice($this->path, ($i));

                    $set = 'true';

                    break;
                }
            }
        }

        if ('true' == $set) {
            $this->path[] = [
                'page' => basename($PHP_SELF),
                'mode' => $request_type,
                'get' => $_GET,
                'post' => $_POST,
            ];
        }
    }

    public function remove_current_page()
    {
        global $PHP_SELF;

        $last_entry_position = count($this->path) - 1;

        if ($this->path[$last_entry_position]['page'] == basename($PHP_SELF)) {
            unset($this->path[$last_entry_position]);
        }
    }

    public function set_snapshot($page = '')
    {
        global $PHP_SELF, $_GET, $_POST, $request_type;

        if (is_array($page)) {
            $this->snapshot = [
                'page' => $page['page'],
                'mode' => $page['mode'],
                'get' => $page['get'],
                'post' => $page['post'],
            ];
        } else {
            $this->snapshot = [
                'page' => basename($PHP_SELF),
                'mode' => $request_type,
                'get' => $_GET,
                'post' => $_POST,
            ];
        }
    }

    public function clear_snapshot()
    {
        $this->snapshot = [];
    }

    public function set_path_as_snapshot($history = 0)
    {
        $pos = (count($this->path) - 1 - $history);

        $this->snapshot = [
            'page' => $this->path[$pos]['page'],
            'mode' => $this->path[$pos]['mode'],
            'get' => $this->path[$pos]['get'],
            'post' => $this->path[$pos]['post'],
        ];
    }

    public function debug()
    {
        for ($i = 0, $n = count($this->path); $i < $n; $i++) {
            echo $this->path[$i]['page'] . '?';

            while (list($key, $value) = each($this->path[$i]['get'])) {
                echo $key . '=' . $value . '&';
            }

            if (count($this->path[$i]['post']) > 0) {
                echo '<br>';

                while (list($key, $value) = each($this->path[$i]['post'])) {
                    echo '&nbsp;&nbsp;<b>' . $key . '=' . $value . '</b><br>';
                }
            }

            echo '<br>';
        }

        if (count($this->snapshot) > 0) {
            echo '<br><br>';

            echo $this->snapshot['mode'] . ' ' . $this->snapshot['page'] . '?' . tep_array_to_string($this->snapshot['get'], [tep_session_name()]) . '<br>';
        }
    }

    public function unserialize($broken)
    {
        for (reset($broken); $kv = each($broken);) {
            $key = $kv['key'];

            if ('user function' != gettype($this->$key)) {
                $this->$key = $kv['value'];
            }
        }
    }
}
