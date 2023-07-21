<?php
/*******************************************************************************
 *
 * $Id$
 * $Date$
 * $Author$
 * $Revision$
 * $HeadURL$
 *
 *******************************************************************************
*/

namespace STS\SystemsDirector;


class SysDirPhysical extends SysDirServer
{

	public static function cast(SysDirPhysical $object) {
		return $object;
	}

	public function __toString() {
		$return = "";
		foreach (SysDirPhysicalTable::getColumnNames() as $prop) {
			$return .= sprintf("%-25s => %s\n", $prop, $this->$prop);
		}
		return $return;
	}


	// *******************************************************************************
	// * Getters and Setters
	// *****************************************************************************


}