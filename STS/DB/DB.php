<?php
/*******************************************************************************
 *
 * $Id: DB.php 76808 2013-07-15 19:16:25Z rcallaha $
 * $Date: 2013-07-15 15:16:25 -0400 (Mon, 15 Jul 2013) $
 * $Author: rcallaha $
 * $Revision: 76808 $
 * $HeadURL: https://svn.ultradns.net/svn/sts_tools/sts-lib/trunk/STS/DB/DB.php $
 *
 *******************************************************************************
 */

namespace STS\DB;

interface DB
{
	public function connect();
    public function error();
    public function errno();
    public function escape_string($string);
    public function query($query);
    public function fetch_array($result);
    public function fetch_row($result);
    public function fetch_assoc($result);
    public function fetch_object($result);
    public function num_rows($result);
    public function getInsertId();
    public function close();
}
