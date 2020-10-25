<?php
/*
  $Id: message_stack.php,v 1.1 2006/03/27 09:08:12 mikhail Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License

  Example usage:

  $messageStack = new messageStack();
  $messageStack->add('general', 'Error: Error 1', 'error');
  $messageStack->add('general', 'Error: Error 2', 'warning');
  if ($messageStack->size('general') > 0) echo $messageStack->output('general');
*/

class messageStack extends tableBox
{
// class constructor
public function __construct()
{
global $messageToStack;

$this->messages = [];

if (tep_isset(\< ? php
/*
  $Id: message_stack.php,v 1.1 2006/03/27 09:08:12 mikhail Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License

  Example usage:

  $messageStack = new messageStack();
  $messageStack->add('general', 'Error: Error 1', 'error');
  $messageStack->add('general', 'Error: Error 2', 'warning');
  if ($messageStack->size('general') > 0) echo $messageStack->output('general');
*/

class messageStack extends tableBox
{
    // class constructor
    public function __construct()
    {
        global $messageToStack;

        $this->messages = [];

        if (tep_session_is_registered('messageToStack')) {
            for ($i = 0, $n = count($messageToStack); $i < $n; $i++) {
                $this->add($messageToStack[$i]['class'], $messageToStack[$i]['text'], $messageToStack[$i]['type']);
            }
            tep_session_unregister('messageToStack');
        }
    }

    // class methods
    public function add($class, $message, $type = 'error')
    {
        if ($type == 'error') {
            $this->messages[] = ['params' => 'class="messageStackError"', 'class' => $class, 'text' => tep_image(DIR_WS_ICONS . 'error.gif', ICON_ERROR) . '&nbsp;' . $message];
        } elseif ($type == 'warning') {
            $this->messages[] = ['params' => 'class="messageStackWarning"', 'class' => $class, 'text' => tep_image(DIR_WS_ICONS . 'warning.gif', ICON_WARNING) . '&nbsp;' . $message];
        } elseif ($type == 'success') {
            $this->messages[] = ['params' => 'class="messageStackSuccess"', 'class' => $class, 'text' => tep_image(DIR_WS_ICONS . 'success.gif', ICON_SUCCESS) . '&nbsp;' . $message];
        } else {
            $this->messages[] = ['params' => 'class="messageStackError"', 'class' => $class, 'text' => $message];
        }
    }

    public function add_session($class, $message, $type = 'error')
    {
        global $messageToStack;

        if (!tep_session_is_registered('messageToStack')) {
            tep_session_register('messageToStack');
            $messageToStack = [];
        }

        $messageToStack[] = ['class' => $class, 'text' => $message, 'type' => $type];
    }

    public function reset()
    {
        $this->messages = [];
    }

    public function output($class)
    {
        $this->table_data_parameters = 'class="messageBox"';

        $output = [];
        for ($i = 0, $n = count($this->messages); $i < $n; $i++) {
            if ($this->messages[$i]['class'] == $class) {
                $output[] = $this->messages[$i];
            }
        }

        return parent::__construct($output);
    }

    public function size($class)
    {
        $count = 0;

        for ($i = 0, $n = count($this->messages); $i < $n; $i++) {
            if ($this->messages[$i]['class'] == $class) {
                $count++;
            }
        }

        return $count;
    }
}

?>
SESSION['messageToStack'])) {
for ($i = 0, $n = sizeof($messageToStack); $i < $n; $i++) {
$this->add($messageToStack[$i]['class'], $messageToStack[$i]['text'], $messageToStack[$i]['type']);
}
tep_session_unregister('messageToStack');
}
}

// class methods
function add($class, $message, $type = 'error')
{
if ($type == 'error') {
$this->messages[] = ['params' => 'class="messageStackError"', 'class' => $class, 'text' => tep_image(DIR_WS_ICONS . 'error.gif', ICON_ERROR) . '&nbsp;' . $message];
} elseif ($type == 'warning') {
$this->messages[] = ['params' => 'class="messageStackWarning"', 'class' => $class, 'text' => tep_image(DIR_WS_ICONS . 'warning.gif', ICON_WARNING) . '&nbsp;' . $message];
} elseif ($type == 'success') {
$this->messages[] = ['params' => 'class="messageStackSuccess"', 'class' => $class, 'text' => tep_image(DIR_WS_ICONS . 'success.gif', ICON_SUCCESS) . '&nbsp;' . $message];
} else {
$this->messages[] = ['params' => 'class="messageStackError"', 'class' => $class, 'text' => $message];
}
}

function add_session($class, $message, $type = 'error')
{
global $messageToStack;

if (!tep_isset(\<?php

/*
  $Id: message_stack.php,v 1.1 2006/03/27 09:08:12 mikhail Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License

  Example usage:

  $messageStack = new messageStack();
  $messageStack->add('general', 'Error: Error 1', 'error');
  $messageStack->add('general', 'Error: Error 2', 'warning');
  if ($messageStack->size('general') > 0) echo $messageStack->output('general');
*/

class messageStack extends tableBox
{
    // class constructor
    public function __construct()
    {
        global $messageToStack;

        $this->messages = [];

        if (tep_session_is_registered('messageToStack')) {
            for ($i = 0, $n = count($messageToStack); $i < $n; $i++) {
                $this->add($messageToStack[$i]['class'], $messageToStack[$i]['text'], $messageToStack[$i]['type']);
            }
            tep_session_unregister('messageToStack');
        }
    }

    // class methods
    public function add($class, $message, $type = 'error')
    {
        if ($type == 'error') {
            $this->messages[] = ['params' => 'class="messageStackError"', 'class' => $class, 'text' => tep_image(DIR_WS_ICONS . 'error.gif', ICON_ERROR) . '&nbsp;' . $message];
        } elseif ($type == 'warning') {
            $this->messages[] = ['params' => 'class="messageStackWarning"', 'class' => $class, 'text' => tep_image(DIR_WS_ICONS . 'warning.gif', ICON_WARNING) . '&nbsp;' . $message];
        } elseif ($type == 'success') {
            $this->messages[] = ['params' => 'class="messageStackSuccess"', 'class' => $class, 'text' => tep_image(DIR_WS_ICONS . 'success.gif', ICON_SUCCESS) . '&nbsp;' . $message];
        } else {
            $this->messages[] = ['params' => 'class="messageStackError"', 'class' => $class, 'text' => $message];
        }
    }

    public function add_session($class, $message, $type = 'error')
    {
        global $messageToStack;

        if (!tep_session_is_registered('messageToStack')) {
            tep_session_register('messageToStack');
            $messageToStack = [];
        }

        $messageToStack[] = ['class' => $class, 'text' => $message, 'type' => $type];
    }

    public function reset()
    {
        $this->messages = [];
    }

    public function output($class)
    {
        $this->table_data_parameters = 'class="messageBox"';

        $output = [];
        for ($i = 0, $n = count($this->messages); $i < $n; $i++) {
            if ($this->messages[$i]['class'] == $class) {
                $output[] = $this->messages[$i];
            }
        }

        return parent::__construct($output);
    }

    public function size($class)
    {
        $count = 0;

        for ($i = 0, $n = count($this->messages); $i < $n; $i++) {
            if ($this->messages[$i]['class'] == $class) {
                $count++;
            }
        }

        return $count;
    }
}

?>
SESSION['messageToStack'])) {
tep_session_register('messageToStack');
$messageToStack = [];
}

$messageToStack[] = ['class' => $class, 'text' => $message, 'type' => $type];
}

function reset()
{
$this->messages = [];
}

function output($class)
{
$this->table_data_parameters = 'class="messageBox"';

$output = [];
for ($i = 0, $n = sizeof($this->messages); $i < $n; $i++) {
if ($this->messages[$i]['class'] == $class) {
$output[] = $this->messages[$i];
}
}

return $this->tableBox($output);
}

function size($class)
{
$count = 0;

for ($i = 0, $n = sizeof($this->messages); $i < $n; $i++) {
if ($this->messages[$i]['class'] == $class) {
$count++;
}
}

return $count;
}
}

?>
