<?php
/*******************************************************************************
 *
 * $Id: SSH2.php 74756 2013-04-26 17:08:54Z rcallaha $
 * $Date: 2013-04-26 13:08:54 -0400 (Fri, 26 Apr 2013) $
 * $Author: rcallaha $
 * $Revision: 74756 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/Util/SSH2.php $
 *
 *******************************************************************************
 */

/**
 *
 * +----------------------------------------------------------------------+
 * | SSH2 1.3                                                             |
 * | Wrapper to use SSH from PHP                                          |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2004-2008                                              |
 * | SEO Egghead, Inc.                                                    |
 * | http: *www.seoegghead.com/                                           |
 * |                                                                      |
 * | This program is free software; you can redistribute it and/or        |
 * | modify it under the terms of the GNU General Public License          |
 * | as published by the Free Software Foundation; either version 2       |
 * | of the License, or (at your option) any later version.               |
 * |                                                                      |
 * | This program is distributed in the hope that it will be useful,      |
 * | but WITHOUT ANY WARRANTY; without even the implied warranty of       |
 * | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the        |
 * | GNU General Public License for more details.                         |
 * |                                                                      |
 * | You should have received a copy of the GNU General Public License    |
 * | along with this program; if not, write to the Free Software          |
 * | Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA        |
 * +----------------------------------------------------------------------+
 *
 */

namespace STS\Util;

class SSH2
{

	private $_host;
	private $_port;

	//private $_username;
	//private $_password;

	private $_c;

	private $_currentStream;

	private $_sftp;

	private $_logReads;
	private $_logWrites;

	private $_logBuf;


	/**
	 * @param       $host
	 * @param int   $port
	 * @param array $callbacks
	 */
	public function __construct($host, $port = 22, $callbacks = array())
	{

		if (!function_exists('ssh2_connect')) {
			echo 'ERROR: PECL ssh2 must be installed!';
			die();
		}

		// initialize our log variables
		$this->_logReads  = false;
		$this->_logWrites = false;
		$this->_logBuf    = "";

		// store our host information and connect
		$this->_host = $host;
		$this->_port = $port;
		$this->_c    = ssh2_connect(
			$this->_host,
			$this->_port,
			array(),
			$callbacks
		);
	} // SSH2

    public function getResource() {
        return $this->_c;
    }

    public function getStream() {
        return $this->_currentStream;
    }

	/**
	 * @param bool $setting
	 */
	public function setLogReads($setting = true)
	{

		$this->_logReads = $setting;
	} // setLogReads()

	/**
	 * @param bool $setting
	 */
	public function setLogWrites($setting = true)
	{

		$this->_logWrites = $setting;
	} // setLogWrites()

	/**
	 * @param $username
	 * @param $password
	 *
	 * @return bool
	 */
	public function loginWithPassword($username, $password)
	{
		return ssh2_auth_password($this->_c, $username, $password);
	} // loginWithPassword()

    /**
     * @param $username
     * @param $publicKeyFile
     * @param $privateKeyFile

     * @return bool
     */
    public function loginWithKey($username, $publicKeyFile, $privateKeyFile) {
        return ssh2_auth_pubkey_file($this->_c, $username, $publicKeyFile, $privateKeyFile);
    }


	/**
	 * WARNING: Blocking only really works as expected if reading data
	 * afterwards.
	 *
	 * @param       $command
	 * @param bool  $setBlocking
	 * @param null  $pty
	 * @param array $env
	 *
	 * @return resource
	 */
	public function execCommand(
		$command,
		$setBlocking = false,
		$pty = null,
		$env = array()
	)
	{

		if (!$pty) {
			$pty = null;
		}
		$stream               = ssh2_exec($this->_c, $command, $pty, $env);
		$this->_currentStream = $stream;
		if ($setBlocking) {
			stream_set_blocking($stream, true);
		}

		return $stream;
	} // execCommand()

	/**
	 * @param        $command
	 * @param bool   $getStdOut
	 * @param bool   $getStdErr
	 * @param string $appendOutput
	 *
	 * @return string
	 */
	public function _generateCommand(
		$command,
		$getStdOut = true,
		$getStdErr = false,
		$appendOutput = ''
	)
	{

		$command = ' ( ' . $command . ' ) ';

		if ($getStdOut && $getStdErr) {
			$command .= ' 2>&1 ';
		}
		elseif ($getStdOut && !$getStdErr) {
			$command .= ' 2>/dev/null ';
		}
		elseif (!$getStdOut && $getStdErr) {
			$command = ' ( ' . $command . ' 1>/dev/null ) 2>&1 ';
		}
		else {
			$command .= ' >/dev/null 2>&1 ';
		}

		$command = ' sh -c ' . escapeshellarg($command);
		if ($appendOutput) {
			$command .= ' ; echo ' .
				escapeshellarg($appendOutput) . ' ; ';
		}

		return $command;
	} // _generateCommand()

	/**
	 * Use this if you want to wait until the command is executed.
	 *
	 * @param       $command
	 * @param bool  $notUsed
	 * @param null  $pty
	 * @param array $env
	 *
	 * @return resource
	 */
	public function execCommandBlockNoOutput(
		$command,
		$notUsed = true,
		$pty = null,
		$env = array()
	)
	{

		$command = SSH2::_generateCommand($command, false, false, '@');
		$stream  = $this->execCommand($command, true, $pty, $env);
		$this->waitPrompt('@');

		return $stream;
	} // execCommandBlockNoOutput()

	/**
	 * Use this if you want to wait until the command is executed and want the
	 * output. This is an old implementation of execCommandBlockING(); it has a
	 * b64encode dependency.
	 *
	 * @param       $command
	 * @param bool  $notUsed
	 * @param null  $pty
	 * @param array $env
	 * @param bool  $getStdErr
	 *
	 * @return string
	 */
	public function execCommandBlock(
		$command,
		$notUsed = true,
		$pty = null,
		$env = array(),
		$getStdErr = false
	)
	{

		$command = SSH2::_generateCommand($command, true, $getStdErr);
		$command .= ' | b64encode - | sed 1d | sed \'$d\' ';
		$command .= ' ; echo \'@\'; ';
		$stream = $this->execCommand($command, true, $pty, $env);
		$this->waitPrompt('@', $_buf);

		return base64_decode($_buf);
	} // execCommandBlock()

	/**
	 * Use this if you want to wait until the command is executed and want the
	 * output.
	 *
	 * @param       $command
	 * @param bool  $notUsed
	 * @param null  $pty
	 * @param array $env
	 * @param bool  $getStdErr
	 *
	 * @return string
	 */
	public function execCommandBlocking(
		$command,
		$notUsed = true,
		$pty = null,
		$env = array(),
		$getStdErr = false
	)
	{

		//        print "generating command\n";
		$command = SSH2::_generateCommand($command, true, $getStdErr);
		//        print "exec-ing command\n";
		$stream = $this->execCommand($command, true, $pty, $env);
		//        print "reading buffer\n";
		$buf = '';

		while ($temp = $this->getStreamOutput()) {
			$buf .= $temp;
		}
		if ($this->_logReads) {
			$this->_logBuf .= $buf;
		}

		return $buf;
	} // execCommandBlocking()

	/**
	 * @param bool   $setBlocking
	 * @param string $termType
	 * @param array  $env
	 * @param null   $width
	 * @param null   $height
	 *
	 * @return resource
	 */
	public function getShell(
		$setBlocking = false,
		$termType = 'vt102',
		$env = array(),
		$width = null,
		$height = null
	)
	{

		$stream               = ssh2_shell(
			$this->_c,
			$termType,
			$env,
			$width,
			$height,
			($width && $height) ? SSH2_TERM_UNIT_CHARS : null
		);
		$this->_currentStream = $stream;
		if ($setBlocking) {
			stream_set_blocking($stream, true);
		}

		return $stream;
	} // getShell()

    /**
     * @param string $promptRegex
     * @param string $buf
     * @param int $timeoutSecs
     * @param int $readBytes
     * @param null $stream
     *
     * @return int
     */
	public function waitPrompt(
		$promptRegex = '> $',
		&$buf = '',
		$timeoutSecs = 0,
        $readBytes = 4096,
		$stream = null
	)
	{

		if (!$stream) {
			$stream = $this->_currentStream;
		}
		if ($timeoutSecs) {

			$_ver = preg_replace('#-.*?$#', '', phpversion('ssh2'));
			if (version_compare($_ver, '0.11.0', '<')) {
				echo "ERROR: Using old version of PECL ssh2 ($_ver); timeouts broken!";
				die();
			}

			$end = time() + $timeoutSecs;

			$saved_meta_info = $this->getMeta($stream);
			stream_set_blocking($stream, false);

			while (!$_r = preg_match("^$promptRegex^", $buf .= fread($stream, $readBytes))) {
				if (time() > $end) {
					break;
				}
				fflush($stream);
				usleep(1);
			}

			stream_set_blocking($stream, $saved_meta_info['blocked']);
		}
		else {
			while (!$_r = preg_match("^$promptRegex^", $buf .= fread($stream, $readBytes))) {
				fflush($stream);
			}
		}

		if ($this->_logReads) {
			$this->_logBuf .= $buf;
		}

		return $_r;
	} // waitPrompt()

	/**
	 * @param      $command
	 * @param bool $addNewline
	 * @param null $stream
	 *
	 * @return int
	 */
	public function writePrompt($command, $addNewline = true, $stream = null)
	{

		if (!$stream) {
			$stream = $this->_currentStream;
		}
		fflush($stream);
		$_command = ($command . ($addNewline ? "\n" : ''));
		$numBytes = fwrite($stream, $_command);
		fflush($stream);
		if ($this->_logWrites) {
			$this->_logBuf .= substr($_command, 0, $numBytes);
		}

		return $numBytes;
	} // writePrompt()

    public function write($command)
   	{

   		fflush($this->_currentStream);
   		$numBytes = fwrite($this->_currentStream, $command, strlen($command));
   		fflush($this->_currentStream);
   		if ($this->_logWrites) {
   			$this->_logBuf .= substr($command, 0, $numBytes);
   		}

   		return $numBytes;
   	} // writePrompt()

	/**
	 * @param int  $length
	 * @param null $stream
	 *
	 * @return string
	 */
	public function getStreamOutput($length = 4096, $stream = null)
	{

		if (!$stream) {
			$stream = $this->_currentStream;
		}
		$buf = fread($stream, $length);
		if ($this->_logReads) {
			$this->_logBuf .= $buf;
		}

		return $buf;
	} // getStreamOutput()

	/**
	 * WARNING: This may not necessarily get all data.
	 *
	 * @param null $stream
	 *
	 * @return string
	 */
	public function getAllStreamOutput($stream = null)
	{

		if (!$stream) {
			$stream = $this->_currentStream;
		}
		$buf = stream_get_contents($stream);
		if ($this->_logReads) {
			$this->_logBuf .= $buf;
		}

		return $buf;
	} // getAllStreamOutput()

	/**
	 * @param null $stream
	 *
	 * @return bool
	 */
	public function closeStream($stream = null)
	{

		if (!$stream) {
			$stream = $this->_currentStream;
		}

		return fclose($stream);
	} // closeStream()

	/**
	 * @param bool $setBlocking
	 * @param null $stream
	 *
	 * @return resource
	 */
	public function fetchSTDERR($setBlocking = false, $stream = null)
	{

		if (!$stream) {
			$stream = $this->_currentStream;
		}
		$errStream = ssh2_fetch_stream($stream, SSH2_STREAM_STDERR);
		if ($setBlocking) {
			stream_set_blocking($errStream, true);
		}

		return $errStream;
	} // fetchSTDERR()

	/**
	 * @param      $localFile
	 * @param      $remoteFile
	 * @param null $createMode
	 *
	 * @return bool
	 */
	public function SCPSend($localFile, $remoteFile, $createMode = null)
	{

		return ssh2_scp_send($this->_c, $localFile, $remoteFile, $createMode);
	} // SCPSend()

	/**
	 * @param      $fileContents
	 * @param      $remoteFile
	 * @param null $createMode
	 * @param bool $setBlocking
	 *
	 * @return int
	 */
	public function sendContents(
		$fileContents,
		$remoteFile,
		$createMode = null,
		$setBlocking = false
	)
	{

		$fp = fopen("ssh2.sftp://" . $this->_sftp . "$remoteFile", $createMode);
		if ($setBlocking) {
			stream_set_blocking($fp, true);
		}

		return fwrite($fp, $fileContents, strlen($fileContents));
	} // sendContents()

	/**
	 * @param      $inputStream
	 * @param      $remoteFile
	 * @param null $createMode
	 * @param bool $setBlocking
	 *
	 * @return int
	 */
	public function sendStream(
		$inputStream,
		$remoteFile,
		$createMode = null,
		$setBlocking = false
	)
	{

		$fp = fopen("ssh2.sftp://" . $this->_sftp . "$remoteFile", $createMode);
		if ($setBlocking) {
			stream_set_blocking($fp, true);
		}
		$bytes = stream_copy_to_stream($inputStream, $fp);
		fclose($fp);

		return $bytes;
	} // sendStream()

	/**
	 * @param $remoteFile
	 * @param $localFile
	 *
	 * @return bool
	 */
	public function SCPReceive($remoteFile, $localFile)
	{

		return ssh2_scp_recv($this->_c, $remoteFile, $localFile);
	} // SCPReceive()

	/**
	 *
	 */
	public function openSFTP()
	{

		$this->_sftp = ssh2_sftp($this->_c);
	} // openSFTP()

	/**
	 * @param $filename
	 *
	 * @return bool
	 */
	public function unlink($filename)
	{

		return ssh2_sftp_unlink($this->_sftp, $filename);
	} // unlink()

	/**
	 * @param null $stream
	 *
	 * @return bool
	 */
	public function FEOF($stream = null)
	{

		if (!$stream) {
			$stream = $this->_currentStream;
		}

		return feof($stream);
	} // feof()

	/**
	 * @param null $stream
	 *
	 * @return array
	 */
	public function getMeta($stream = null)
	{

		if (!$stream) {
			$stream = $this->_currentStream;
		}

		return stream_get_meta_data($stream);
	} // getMeta()

	// Use file wrappers for other public functionality.

}