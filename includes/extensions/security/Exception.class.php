<?php
class CMSException extends Exception {
    public function __construct($message, $code=0) {
        parent::__construct($message, $code);
    }
    public function __toString() {
        // Vars
        $TheTrace = $this->getTrace();
        $TraceArray = array();
        $indent = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        // HTML Return
        $TheReturn = '<strong style="color:red">' . $this->message . '</strong>';
        $TheReturn .= '<br /><strong style="color:red">Exception thrown on line ' . $this->line . '</strong>';
        $TheReturn .= '<br /><strong style="color:red">In file ' . $this->file . '</strong>';
        $TheReturn .= '<br /><strong style="color:red">Call Stack:</strong><br />';
        // Loop through stack
        foreach ($TheTrace[0] as $k => $v) {
            // Make sure none of the values are arrays
            if (!is_array($k) && !is_array($v)) {
                $TraceArray[$k] = $v; // return $TraceArray
            }
        }
        // Return $TraceArray (indented)
        if (isset($TraceArray['file'])) {
            $TheReturn .= $indent . "<strong>File:</strong> " . $TraceArray['file'] . '<br />';
        }
        if (isset($TraceArray['line'])) {
            $TheReturn .= $indent . "<strong>Line:</strong> " . $TraceArray['line'] . '<br />';
        }
        $TheReturn .= $indent . "<strong>Class:</strong> " . $TraceArray['class'] . '<br />';
        $TheReturn .= $indent . "<strong>Function:</strong> " . $TraceArray['function'] . '<br />';
        // Return
        exit($TheReturn); // Let's stop all processing on exception
    }
}
?>
