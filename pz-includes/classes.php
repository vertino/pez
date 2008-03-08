<?php
if (!defined('ABSPATH'))
	die('unable to continue...');

class Persistent
{
	var $filename;
	
	function Persistent($filename)
	{
		$this->filename = $filename;
		if (!file_exists($this->filename)) $this->save();
	}
	
	function save()
	{
		if ($f = @fopen($this->filename, 'w'))
		{
			if (@fwrite($f, serialize(get_object_vars($this))))
			{
				@fclose($f);
			}
			else die('Could not write to file ' . $this->filename . ' at Persistant::save');
		}
		else die('Could not open file ' . $this->filename . ' for writing, at Persistant::save');
		
	}
	
	function open()
	{
		$vars = unserialize(file_get_contents($this->filename));
		foreach ($vars as $key=>$val)
		{           
			eval("$" . "this->$key = $" . "vars['" . "$key'];");
		}
	}
}

class Profile extends Persistent
{
	var $first_name;
	var $last_name;
	var $blurb;
	var $location;
	var $email;
	var $dob = array();
	var $gender;
	var $gravatar;
	
	function Profile( $filename = '' )
	{
		if ($filename == '')
			$filename = ABSPATH . '/pz-admin/data/profile.php';
		
		$this->Persistent($filename);
	}
}

class WebDataSources extends Persistent
{
	var $profiles = array();
	var $sources = array();
	
	function WebDataSources( $filename = '' )
	{
		if ($filename == '')
			$filename = ABSPATH . '/pz-admin/data/social-networks.php';
		
		$this->Persistent($filename);
	}
}
?>