<?php

namespace Fuel\Tasks;

use Fuel\Core\Cli;
use Fuel\Core\DB;
use Fuel\Core\DBUtil;
use Fuel\Core\Format;

/**
 * Dbfixt Tasks *
 * @packageFuel
 * @authorKenji Suzuki
 * @copyright2011 Kenji Suzuki * @linkhttps://github.com/kenjis
 */
class Dbfixt
{
  /**
   * This method gets ran when a valid method name is not used in the command.
   *
   * Usage (from command line):
   *
   * php oil r Dbfixt *
   * @return string
   */
  public static function run()
  {
    return static::help();
  }

  public static function help()
  {
    return <<<HELP

Usage:
 php oil r dbfixt:generate [-n=5] [-o=/tmp] <table1> [<table2> [...]]

Runtime options:
 -n  number of rows in fixtures (default: 5)
 -o  output directory

Description:
 This command creates yaml files from database table data.
 yaml files are placed in APPPATH/tests/fixture/ in default.
HELP;
  }

  /**
   * Create yaml files from database data.
   *
   * Usage (from command line):
   *
   * php oil r dbfixt:generate [-n=5] [-o=/tmp] <table1> [<table2> [...]]
   *
   * @return string */
  public static function generate()
  {
    $num = Cli::option('n') ? (int) Cli::option('n') : 5;
    
    $dir = Cli::option('o');
    if (is_null($dir)) {
      $dir = APPPATH . 'tests/fixture';
      if ( ! is_dir($dir))
        {
          mkdir($dir);
        }
    }
    else if ( ! is_dir($dir))
      {
        return Cli::color('No such directory: ' . $dir, 'red');
      }
    
    $args = func_get_args();
    
    foreach ($args as $table)
      {
        if (DBUtil::table_exists($table))
          {
            $result =  DB::select('*')->from($table)->limit($num)->execute();
            $data = $result->as_array();
            
            $file = $dir . '/' . $table . '_fixt.yml';
            $data = Format::forge($data)->to_yaml();

            if (file_exists($file))
              {
                rename($file, $file . '.old');
                echo 'Backed-up: ' . $file . PHP_EOL;
              }
            file_put_contents($file, $data);
            echo '  Created: ' . $file . PHP_EOL;
          }
        else{
          echo Cli::color('No such table: ' . $table, 'red') . PHP_EOL;
        }
      }
  }
}

?>