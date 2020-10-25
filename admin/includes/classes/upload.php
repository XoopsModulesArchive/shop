<?php

/*
  $Id: upload.php,v 1.1 2006/03/27 09:01:42 mikhail Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

class upload
{
    public $file;

    public $filename;

    public $destination;

    public $permissions;

    public $extensions;

    public $tmp_filename;

    public $message_location;

    public function __construct($file = '', $destination = '', $permissions = '777', $extensions = '')
    {
        $this->set_file($file);

        $this->set_destination($destination);

        $this->set_permissions($permissions);

        $this->set_extensions($extensions);

        $this->set_output_messages('direct');

        if (tep_not_null($this->file) && tep_not_null($this->destination)) {
            $this->set_output_messages('session');

            if ((true === $this->parse()) && (true === $this->save())) {
                return true;
            }

            // self destruct

            $this = null;

            return false;
        }
    }

    public function parse()
    {
        global $messageStack;

        if (isset($_FILES[$this->file])) {
            $file = [
                'name' => $_FILES[$this->file]['name'],
'type' => $_FILES[$this->file]['type'],
'size' => $_FILES[$this->file]['size'],
'tmp_name' => $_FILES[$this->file]['tmp_name'],
            ];
        } elseif (isset($GLOBALS['HTTP_POST_FILES'][$this->file])) {
            global $HTTP_POST_FILES;

            $file = [
                'name' => $HTTP_POST_FILES[$this->file]['name'],
'type' => $HTTP_POST_FILES[$this->file]['type'],
'size' => $HTTP_POST_FILES[$this->file]['size'],
'tmp_name' => $HTTP_POST_FILES[$this->file]['tmp_name'],
            ];
        } else {
            $file = [
                'name' => ($GLOBALS[$this->file . '_name'] ?? ''),
                'type' => ($GLOBALS[$this->file . '_type'] ?? ''),
                'size' => ($GLOBALS[$this->file . '_size'] ?? ''),
                'tmp_name' => ($GLOBALS[$this->file] ?? ''),
            ];
        }

        if (tep_not_null($file['tmp_name']) && ('none' != $file['tmp_name']) && is_uploaded_file($file['tmp_name'])) {
            if (count($this->extensions) > 0) {
                if (!in_array(mb_strtolower(mb_substr($file['name'], mb_strrpos($file['name'], '.') + 1)), $this->extensions, true)) {
                    if ('direct' == $this->message_location) {
                        $messageStack->add(ERROR_FILETYPE_NOT_ALLOWED, 'error');
                    } else {
                        $messageStack->add_session(ERROR_FILETYPE_NOT_ALLOWED, 'error');
                    }

                    return false;
                }
            }

            $this->set_file($file);

            $this->set_filename($file['name']);

            $this->set_tmp_filename($file['tmp_name']);

            return $this->check_destination();
        }

        if ('direct' == $this->message_location) {
            $messageStack->add(WARNING_NO_FILE_UPLOADED, 'warning');
        } else {
            $messageStack->add_session(WARNING_NO_FILE_UPLOADED, 'warning');
        }

        return false;
    }

    public function save()
    {
        global $messageStack;

        if ('/' != mb_substr($this->destination, -1)) {
            $this->destination .= '/';
        }

        if (move_uploaded_file($this->file['tmp_name'], $this->destination . $this->filename)) {
            chmod($this->destination . $this->filename, $this->permissions);

            if ('direct' == $this->message_location) {
                $messageStack->add(SUCCESS_FILE_SAVED_SUCCESSFULLY, 'success');
            } else {
                $messageStack->add_session(SUCCESS_FILE_SAVED_SUCCESSFULLY, 'success');
            }

            return true;
        }

        if ('direct' == $this->message_location) {
            $messageStack->add(ERROR_FILE_NOT_SAVED, 'error');
        } else {
            $messageStack->add_session(ERROR_FILE_NOT_SAVED, 'error');
        }

        return false;
    }

    public function set_file($file)
    {
        $this->file = $file;
    }

    public function set_destination($destination)
    {
        $this->destination = $destination;
    }

    public function set_permissions($permissions)
    {
        $this->permissions = octdec($permissions);
    }

    public function set_filename($filename)
    {
        $this->filename = $filename;
    }

    public function set_tmp_filename($filename)
    {
        $this->tmp_filename = $filename;
    }

    public function set_extensions($extensions)
    {
        if (tep_not_null($extensions)) {
            if (is_array($extensions)) {
                $this->extensions = $extensions;
            } else {
                $this->extensions = [$extensions];
            }
        } else {
            $this->extensions = [];
        }
    }

    public function check_destination()
    {
        global $messageStack;

        if (!is_writable($this->destination)) {
            if (is_dir($this->destination)) {
                if ('direct' == $this->message_location) {
                    $messageStack->add(sprintf(ERROR_DESTINATION_NOT_WRITEABLE, $this->destination), 'error');
                } else {
                    $messageStack->add_session(sprintf(ERROR_DESTINATION_NOT_WRITEABLE, $this->destination), 'error');
                }
            } else {
                if ('direct' == $this->message_location) {
                    $messageStack->add(sprintf(ERROR_DESTINATION_DOES_NOT_EXIST, $this->destination), 'error');
                } else {
                    $messageStack->add_session(sprintf(ERROR_DESTINATION_DOES_NOT_EXIST, $this->destination), 'error');
                }
            }

            return false;
        }

        return true;
    }

    public function set_output_messages($location)
    {
        switch ($location) {
            case 'session':
                $this->message_location = 'session';
                break;
            case 'direct':
            default:
                $this->message_location = 'direct';
                break;
        }
    }
}
