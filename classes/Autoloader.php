<?php

class Autoloader
{
	public static function register()
	{
		spl_autoload_register(function ($class) {
            $file = "./classes/{$class}.php";
			if (file_exists($file)) {
                if ($class == 'Autoloader') {
                    return true;
                }
				require $file;
				return true;
			}
			return false;
		});
	}
}