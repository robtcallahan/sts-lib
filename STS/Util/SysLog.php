<?php
/*******************************************************************************
 *
 * $Id: SysLog.php 81814 2013-12-09 19:40:39Z rcallaha $
 * $Date: 2013-12-09 14:40:39 -0500 (Mon, 09 Dec 2013) $
 * $Author: rcallaha $
 * $Revision: 81814 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/Util/SysLog.php $
 *
 *******************************************************************************
 */

namespace STS\Util;

class SysLog
{
    const EMERG   = 0;
    const ALERT   = 1;
    const CRIT    = 2;
    const ERR     = 3;
    const WARNING = 4;
    const NOTICE  = 5;
    const INFO    = 6;
    const DEBUG   = 7;

    protected $processName;
    protected $logLevel = SysLog::NOTICE;
    protected $user;

    // Hold an instance of the class
    private static $instance;
    private $config;
    private $severityCodes;

    public function __construct($processName = null, $logLevel = self::NOTICE)
    {
        $this->processName = $processName ? $processName : 'Util\SysLog';
        $this->logLevel = $logLevel;
        $this->user        = array_key_exists("REMOTE_USER", $_SERVER) ? $_SERVER["REMOTE_USER"] : "stsuser";

        $this->severityCodes = array(
            "EMERG",
            "ALERT",
            "CRIT",
            "ERR",
            "WARNING",
            "NOTICE",
            "INFO",
            "DEBUG"
        );

        openlog($this->processName, LOG_PID, LOG_LOCAL1);
    }

    /**
     * @param $processName
     * @return SysLog
     */
    public static function singleton($processName)
    {
        if (!isset(self::$instance)) {
            self::$instance = new SysLog($processName);
        }
        return self::$instance;
    }

    public function close()
    {
        closelog();
    }

    /**
     * @param $level
     * @return mixed
     */
    public function setLogLevel($level)
    {
        $logLevel       = $this->logLevel;
        $this->logLevel = $level;
        return $logLevel;
    }

    /**
     * @return mixed
     */
    public function getLogLevel()
    {
        return $this->logLevel;
    }

    public function setProcessName($processName)
    {
        $this->close();
        $this->processName = $processName;
        openlog($this->processName, LOG_PID, LOG_LOCAL1);
    }

    public function getProcessName()
    {
        return $this->processName;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }

    /**
     * This is helper method to make testing possible. It is overwritten by the StubSysLog class
     * @param $sev
     * @param $msg
     * @return bool
     */
    protected function syslog($sev, $msg)
    {
        return \syslog($sev, $msg);
    }

    protected function callSysLog($sev, $caller, $msg)
    {
        if ($this->logLevel < $sev) return false;

        // replace all occurances of carriage returns in msg string with a space
        $msg = preg_replace("/\n/", ' ', $msg);

        $severityText = $this->severityCodes[$sev];
        return $this->syslog($sev, "Severity={$severityText}; User={$this->user}; {$caller}; {$msg}");
    }

    /**
     * @param int $sev
     * @param string $msg
     * @return bool
     */
    public function log($sev, $msg = "")
    {
        $caller = $this->getCaller();
        return $this->callSysLog($sev, $caller, $msg);
    }

    /**
     * @param string $msg
     * @return bool
     */
    public function debug($msg = "")
    {
        $caller = $this->getCaller();
        return $this->callSysLog(LOG_DEBUG, $caller, $msg);
    }

    /**
     * @param string $msg
     * @return bool
     */
    public function info($msg = "")
    {
        $caller = $this->getCaller();
        return $this->callSysLog(LOG_INFO, $caller, $msg);
    }

    /**
     * @param string $msg
     * @return bool
     */
    public function notice($msg = "")
    {
        $caller = $this->getCaller();
        return $this->callSysLog(LOG_NOTICE, $caller, $msg);
    }

    /**
     * @param string $msg
     * @return bool
     */
    public function warning($msg = "")
    {
        $caller = $this->getCaller();
        return $this->callSysLog(LOG_WARNING, $caller, $msg);
    }

    /**
     * @param string $msg
     * @return bool
     */
    public function error($msg = "")
    {
        $caller = $this->getCaller();
        return $this->callSysLog(LOG_ERR, $caller, $msg);
    }

    /**
     * @param \ErrorException $e
     * @param string $msg
     * @return bool
     */
    public function errorWithException(\ErrorException $e, $msg = "")
    {
        if ($this->logLevel < self::ERR) return false;

        // replace all occurances of carriage returns in msg string with a space
        $msg = preg_replace("/\n/", ' ', $msg);

        $severityText = $this->severityCodes[LOG_ERR];
        return $this->syslog(LOG_ERR, "Severity={$severityText}; User={$this->user}; File=" . basename($e->getFile()) . "; Line={$e->getLine()}; Error={$e->getMessage()}; {$msg}");
    }

    /**
     * @param string $msg
     * @return bool
     */
    public function crit($msg = "")
    {
        $caller = $this->getCaller();
        return $this->callSysLog(LOG_CRIT, $caller, $msg);
    }

    /**
     * @param string $msg
     * @return bool
     */
    public function alert($msg = "")
    {
        $caller = $this->getCaller();
        return $this->callSysLog(LOG_ALERT, $caller, $msg);
    }

    /**
     * @param string $msg
     * @return bool
     */
    public function emerg($msg = "")
    {
        $caller = $this->getCaller();
        return $this->callSysLog(LOG_EMERG, $caller, $msg);
    }

    /**
     * @param $script
     * @return bool
     */
    public function logMemUsage($script)
    {
        $memUsage     = memory_get_usage(true);
        $severityText = $this->severityCodes[LOG_INFO];

        if ($memUsage < 1024) {
            return $this->syslog(LOG_INFO, "Severity={$severityText}; Type=MemUsage; User={$this->user}; Script={$script}; MemUsage={$memUsage} bytes;");
        } elseif ($memUsage < 1048576) {
            $memUsage = round($memUsage / 1024, 2);
            return $this->syslog(LOG_INFO, "Severity={$severityText}; Type=MemUsage; User={$this->user}; Script={$script}; MemUsage={$memUsage} KB;");
        } else {
            $memUsage = round($memUsage / 1048576, 2);
            return $this->syslog(LOG_INFO, "Severity={$severityText}; Type=MemUsage; User={$this->user}; Script={$script}; MemUsage={$memUsage} MB;");
        }
    }

    /**
     * Get information about the caller (of our caller).
     *
     * @return string The caller information in string format:
     *                  class::method(file:line) or function(file:line)
     * @author DaveWhittle
     **/
    private function getCaller()
    {
        $stack = debug_backtrace();

        if (count($stack) <= 1) {
            # Really this should never be possible, but you never know...
            $caller = "Unknown Function";
        } else if (count($stack) == 2) {
            # This is being called from the first level of the main file/STDIN
            if (preg_match("/-$/", $stack[1]['file'])) {
                $file = "STDIN";
            } else {
                $file = basename(dirname($stack[1]['file'])) . "/" . basename($stack[1]['file']);
            }
            $caller = "Function=main; Caller={$file}:{$stack[1]['line']}";
        } else {
            $function = $stack[2]['function'];
            if (preg_match("/^include|^require/", $function)) {
                # Being called from the top level of an included file.
                $file = basename(dirname($stack[2]['args'][0])) . "/" . basename($stack[2]['args'][0]);
                $line = $stack[2]['line'];
            } else if (array_key_exists('file', $stack[2])) {
                $file = basename(dirname($stack[2]['file'])) . "/" . basename($stack[2]['file']);
                $line = $stack[2]['line'];
            } else if (array_key_exists('file', $stack[1])) {
                $file = basename(dirname($stack[1]['file'])) . "/" . basename($stack[1]['file']);
                $line = $stack[1]['line'];
            } else {
                $file = "unknown";
                $line = "unknown";
            }

            $caller = "Function=";

            if (array_key_exists('class', $stack[2])) {
                $caller = "Function={$stack[2]['class']}::";
            } else if (array_key_exists('class', $stack[1])) {
                $caller = "Function={$stack[1]['class']}::";
            }

            $caller .= "{$function}; Caller={$file}:{$line}";
        }
        return ($caller);
    }
}
