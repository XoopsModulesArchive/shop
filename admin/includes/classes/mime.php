<?php

/*
  $Id: mime.php,v 1.1 2006/03/27 09:01:42 mikhail Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  mime.php - a class to assist in building mime-HTML eMails

  The original class was made by Richard Heyes <richard@phpguru.org>
  and can be found here: http://www.phpguru.org

  Renamed and Modified by Jan Wildeboer for osCommerce
*/

class mime
{
    public $_encoding;

    public $_subparts;

    public $_encoded;

    public $_headers;

    public $_body;

    /**
     * Constructor.
     *
     * Sets up the object.
     *
     * @param $body     - The body of the mime part if any.
     * @param $params   - An associative array of parameters:
     *                  content_type - The content type for this part eg multipart/mixed
     *                  encoding     - The encoding to use, 7bit, base64, or quoted-printable
     *                  cid          - Content ID to apply
     *                  disposition  - Content disposition, inline or attachment
     *                  dfilename    - Optional filename parameter for content disposition
     *                  description  - Content description
     */
    public function __construct($body, $params = '')
    {
        if ('' == $params) {
            $params = [];
        }

        // Make sure we use the correct linfeed sequence

        if (EMAIL_LINEFEED == 'CRLF') {
            $this->lf = "\r\n";
        } else {
            $this->lf = "\n";
        }

        reset($params);

        while (list($key, $value) = each($params)) {
            switch ($key) {
                case 'content_type':
                    $headers['Content-Type'] = $value . (isset($charset) ? '; charset="' . $charset . '"' : '');
                    break;
                case 'encoding':
                    $this->_encoding = $value;
                    $headers['Content-Transfer-Encoding'] = $value;
                    break;
                case 'cid':
                    $headers['Content-ID'] = '<' . $value . '>';
                    break;
                case 'disposition':
                    $headers['Content-Disposition'] = $value . (isset($dfilename) ? '; filename="' . $dfilename . '"' : '');
                    break;
                case 'dfilename':
                    if (isset($headers['Content-Disposition'])) {
                        $headers['Content-Disposition'] .= '; filename="' . $value . '"';
                    } else {
                        $dfilename = $value;
                    }
                    break;
                case 'description':
                    $headers['Content-Description'] = $value;
                    break;
                case 'charset':
                    if (isset($headers['Content-Type'])) {
                        $headers['Content-Type'] .= '; charset="' . $value . '"';
                    } else {
                        $charset = $value;
                    }
                    break;
            }
        }

        // Default content-type

        if (!isset($_headers['Content-Type'])) {
            $_headers['Content-Type'] = 'text/plain';
        }

        // Assign stuff to member variables

        $this->_encoded = [];

        /* HPDL PHP3 */

        //      $this->_headers  =& $headers;

        $this->_headers = $headers;

        $this->_body = $body;
    }

    /**
     * encode()
     *
     * Encodes and returns the email. Also stores
     * it in the encoded member variable
     *
     * @return An associative array containing two elements,
     *         body and headers. The headers element is itself
     *         an indexed array.
     */
    public function encode()
    {
        /* HPDL PHP3 */

        //      $encoded =& $this->_encoded;

        $encoded = $this->_encoded;

        if (tep_not_null($this->_subparts)) {
            $boundary = '=_' . md5(uniqid(tep_rand()) . microtime());

            $this->_headers['Content-Type'] .= ';' . $this->lf . chr(9) . 'boundary="' . $boundary . '"';

            // Add body parts to $subparts

            for ($i = 0, $iMax = count($this->_subparts); $i < $iMax; $i++) {
                $headers = [];

                /* HPDL PHP3 */

                //          $tmp = $this->_subparts[$i]->encode();

                $_subparts = $this->_subparts[$i];

                $tmp = $_subparts->encode();

                reset($tmp['headers']);

                while (list($key, $value) = each($tmp['headers'])) {
                    $headers[] = $key . ': ' . $value;
                }

                $subparts[] = implode($this->lf, $headers) . $this->lf . $this->lf . $tmp['body'];
            }

            $encoded['body'] = '--' . $boundary . $this->lf . implode('--' . $boundary . $this->lf, $subparts) . '--' . $boundary . '--' . $this->lf;
        } else {
            $encoded['body'] = $this->_getEncodedData($this->_body, $this->_encoding) . $this->lf;
        }

        // Add headers to $encoded

        /* HPDL PHP3 */

        //      $encoded['headers'] =& $this->_headers;

        $encoded['headers'] = $this->_headers;

        return $encoded;
    }

    /**
     * &addSubPart()
     *
     * Adds a subpart to current mime part and returns
     * a reference to it
     *
     * @param The $body   body of the subpart, if any.
     * @param The $params parameters for the subpart, same
     *                as the $params argument for constructor.
     * @return A reference to the part you just added. It is
     *                crucial if using multipart/* in your subparts that
     *                you use =& in your script when calling this function,
     *                otherwise you will not be able to add further subparts.
     */

    /* HPDL PHP3 */

    //    function &addSubPart($body, $params) {

    public function addSubPart($body, $params)
    {
        $this->_subparts[] = new self($body, $params);

        return $this->_subparts[count($this->_subparts) - 1];
    }

    /**
     * _getEncodedData()
     *
     * Returns encoded data based upon encoding passed to it
     *
     * @param The $data     data to encode.
     * @param The $encoding encoding type to use, 7bit, base64,
     *                  or quoted-printable.
     * @return string|\The
     * @return string|\The
     */
    public function _getEncodedData($data, $encoding)
    {
        switch ($encoding) {
            case '7bit':
                return $data;
                break;
            case 'quoted-printable':
                return $this->_quotedPrintableEncode($data);
                break;
            case 'base64':
                return rtrim(chunk_preg_split(base64_encode($data), 76, $this->lf));
                break;
        }
    }

    /**
     * quoteadPrintableEncode()
     *
     * Encodes data to quoted-printable standard.
     *
     * @param The      $input    data to encode
     * @param Optional $line_max max line length. Should
     *                           not be more than 76 chars
     * @return string
     * @return string
     */
    public function _quotedPrintableEncode($input, $line_max = 76)
    {
        $lines = preg_preg_split("/\r\n|\r|\n/", $input);

        $eol = $this->lf;

        $escape = '=';

        $output = '';

        while (list(, $line) = each($lines)) {
            $linlen = mb_strlen($line);

            $newline = '';

            for ($i = 0; $i < $linlen; $i++) {
                $char = mb_substr($line, $i, 1);

                $dec = ord($char);

                // convert space at eol only

                if ((32 == $dec) && ($i == ($linlen - 1))) {
                    $char = '=20';
                } elseif (9 == $dec) {
                    // Do nothing if a tab.
                } elseif ((61 == $dec) || ($dec < 32) || ($dec > 126)) {
                    $char = $escape . mb_strtoupper(sprintf('%02s', dechex($dec)));
                }

                // $this->lf is not counted

                if ((mb_strlen($newline) + mb_strlen($char)) >= $line_max) {
                    // soft line break; " =\r\n" is okay

                    $output .= $newline . $escape . $eol;

                    $newline = '';
                }

                $newline .= $char;
            }

            $output .= $newline . $eol;
        }

        // Don't want last crlf

        $output = mb_substr($output, 0, -1 * mb_strlen($eol));

        return $output;
    }
}
