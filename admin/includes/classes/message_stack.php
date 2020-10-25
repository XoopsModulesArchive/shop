<?php
/*
  $Id: message_stack.php,v 1.1 2006/03/27 09:01:42 mikhail Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License

  Example usage:

  $messageStack = new messageStack();
  $messageStack->add('Error: Error 1', 'error');
  $messageStack->add('Error: Error 2', 'warning');
  if ($messageStack->size > 0) echo $messageStack->output();
*/

class messageStack extends tableBlock
{
public $size = 0;

public function __construct()
{
global $messageToStack;

$this->errors = [];

if (tep_isset(\< ? php
/*
  $Id: message_stack.php,v 1.1 2006/03/27 09:01:42 mikhail Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License

  Example usage:

  $messageStack = new messageStack();
  $messageStack->add('Error: Error 1', 'error');
  $messageStack->add('Error: Error 2', 'warning');
  if ($messageStack->size > 0) echo $messageStack->output();
*/

class messageStack extends tableBlock
{
    public $size = 0;

    public function __construct()
    {
        global $messageToStack;

        $this->errors = [];

        if (tep_session_is_registered('messageToStack')) {
            for ($i = 0, $n = count($messageToStack); $i < $n; $i++) {
                $this->add($messageToStack[$i]['text'], $messageToStack[$i]['type']);
            }
            tep_session_unregister('messageToStack');
        }
    }

    public function add($message, $type = 'error')
    {
        if ($type == 'error') {
            $this->errors[] = ['params' => 'class="messageStackError"', 'text' => tep_image(DIR_WS_ICONS . 'error.gif', ICON_ERROR) . '&nbsp;' . $message];
        } elseif ($type == 'warning') {
            $this->errors[] = ['params' => 'class="messageStackWarning"', 'text' => tep_image(DIR_WS_ICONS . 'warning.gif', ICON_WARNING) . '&nbsp;' . $message];
        } elseif ($type == 'success') {
            $this->errors[] = ['params' => 'class="messageStackSuccess"', 'text' => tep_image(DIR_WS_ICONS . 'success.gif', ICON_SUCCESS) . '&nbsp;' . $message];
        } else {
            $this->errors[] = ['params' => 'class="messageStackError"', 'text' => $message];
        }

        $this->size++;
    }

    public function add_session($message, $type = 'error')
    {
        global $messageToStack;

        if (!tep_session_is_registered('messageToStack')) {
            tep_session_register('messageToStack');
            $messageToStack = [];
        }

        $messageToStack[] = ['text' => $message, 'type' => $type];
    }

    public function reset()
    {
        $this->errors = [];
        $this->size   = 0;
    }

    public function output()
    {
        $this->table_data_parameters = 'class="messageBox"';
        return parent::__construct($this->errors);
    }
}

?>
SESSION['messageToStack'])) {
for ($i = 0, $n = sizeof($messageToStack); $i < $n; $i++) {
$this->add($messageToStack[$i]['text'], $messageToStack[$i]['type']);
}
tep_session_unregister('messageToStack');
}
}

function add($message, $type = 'error')
{
if ($type == 'error') {
$this->errors[] = ['params' => 'class="messageStackError"', 'text' => tep_image(DIR_WS_ICONS . 'error.gif', ICON_ERROR) . '&nbsp;' . $message];
} elseif ($type == 'warning') {
$this->errors[] = ['params' => 'class="messageStackWarning"', 'text' => tep_image(DIR_WS_ICONS . 'warning.gif', ICON_WARNING) . '&nbsp;' . $message];
} elseif ($type == 'success') {
$this->errors[] = ['params' => 'class="messageStackSuccess"', 'text' => tep_image(DIR_WS_ICONS . 'success.gif', ICON_SUCCESS) . '&nbsp;' . $message];
} else {
$this->errors[] = ['params' => 'class="messageStackError"', 'text' => $message];
}

$this->size++;
}

function add_session($message, $type = 'error')
{
global $messageToStack;

if (!tep_isset(\<?php

/*
  $Id: message_stack.php,v 1.1 2006/03/27 09:01:42 mikhail Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License

  Example usage:

  $messageStack = new messageStack();
  $messageStack->add('Error: Error 1', 'error');
  $messageStack->add('Error: Error 2', 'warning');
  if ($messageStack->size > 0) echo $messageStack->output();
*/

class messageStack extends tableBlock
{
    public $size = 0;

    public function __construct()
    {
        global $messageToStack;

        $this->errors = [];

        if (tep_session_is_registered('messageToStack')) {
            for ($i = 0, $n = count($messageToStack); $i < $n; $i++) {
                $this->add($messageToStack[$i]['text'], $messageToStack[$i]['type']);
            }
            tep_session_unregister('messageToStack');
        }
    }

    public function add($message, $type = 'error')
    {
        if ($type == 'error') {
            $this->errors[] = ['params' => 'class="messageStackError"', 'text' => tep_image(DIR_WS_ICONS . 'error.gif', ICON_ERROR) . '&nbsp;' . $message];
        } elseif ($type == 'warning') {
            $this->errors[] = ['params' => 'class="messageStackWarning"', 'text' => tep_image(DIR_WS_ICONS . 'warning.gif', ICON_WARNING) . '&nbsp;' . $message];
        } elseif ($type == 'success') {
            $this->errors[] = ['params' => 'class="messageStackSuccess"', 'text' => tep_image(DIR_WS_ICONS . 'success.gif', ICON_SUCCESS) . '&nbsp;' . $message];
        } else {
            $this->errors[] = ['params' => 'class="messageStackError"', 'text' => $message];
        }

        $this->size++;
    }

    public function add_session($message, $type = 'error')
    {
        global $messageToStack;

        if (!tep_session_is_registered('messageToStack')) {
            tep_session_register('messageToStack');
            $messageToStack = [];
        }

        $messageToStack[] = ['text' => $message, 'type' => $type];
    }

    public function reset()
    {
        $this->errors = [];
        $this->size   = 0;
    }

    public function output()
    {
        $this->table_data_parameters = 'class="messageBox"';
        return parent::__construct($this->errors);
    }
}

?>
SESSION['messageToStack'])) {
tep_session_register('messageToStack');
$messageToStack = [];
}

$messageToStack[] = ['text' => $message, 'type' => $type];
}

function reset()
{
$this->errors = [];
$this->size   = 0;
}

function output()
{
$this->table_data_parameters = 'class="messageBox"';
return $this->tableBlock($this->errors);
}
}

?>
