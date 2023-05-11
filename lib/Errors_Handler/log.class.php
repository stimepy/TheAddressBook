<?php
/*************************************************************
 *  THE ADDRESS BOOK  :  version 1.2.2
 *
 * Author: stimepy@aodhome.com
 * Last Modified: 5-10-2023
 ****************************************************************
 * Original from F3::Factory/Bong Cosca Copyright (c) 2009-2019.
 * part of the Fat-Free Framework (http://fatfreeframework.com).
 * used under http://www.gnu.org/licenses/
 *
 * Logs in a file OR database pending on your selection.
 *
 *************************************************************/

class Log {

	protected $file;

	/**
	*	Write specified text to log file
	*	@return string
	*	@param $text string
	*	@param $format string
	**/
	function write($text,$format='r') {
		$fw=Base::instance();
		foreach (preg_split('/\r?\n|\r/',trim($text)) as $line)
			$fw->write(
				$this->file,
				date($format).
				(isset($_SERVER['REMOTE_ADDR'])?
					(' ['.$_SERVER['REMOTE_ADDR'].
					(($fwd=filter_var($fw->get('HEADERS.X-Forwarded-For'),
						FILTER_VALIDATE_IP))?(' ('.$fwd.')'):'')
					.']'):'').' '.
				trim($line).PHP_EOL,
				TRUE
			);
	}

	/**
	*	Erase log
	*	@return NULL
	**/
	function erase() {
		@unlink($this->file);
	}

	/**
	*	Instantiate class
	*	@param $file string
	**/
	function __construct($file) {
		$fw=Base::instance();
		if (!is_dir($dir=$fw->LOGS))
			mkdir($dir,Base::MODE,TRUE);
		$this->file=$dir.$file;
	}

    public function WriteDB(){

    }

}
