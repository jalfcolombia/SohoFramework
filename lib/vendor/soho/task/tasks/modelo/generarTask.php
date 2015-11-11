<?php

use soho\myConfig as config;
use soho\shCamelCase as camelCase;

$model = sfYaml::load('config/model.yml');
foreach ($model['schema'] as $table => $attributes) {
  $nameTable = "  const _TABLE = '$table';\n";
  $tableClass = (isset($attributes['name']) === true) ? $attributes['name'] : $table;
  $nameFileBase = camelCase::getInstance()->camelCase($tableClass . 'BaseTable');
  $nameFileTable = camelCase::getInstance()->camelCase($tableClass . 'Table');
  $constructHead = "\n  public function __construct(";
  $constructHeadItems = '';
  $constructBody = '';
  $fieldsAll = '';
  $deleted_at = '';
  $created_at = '';
  $saveFields = '';
  $saveValues = '';
  $saveParams = '';
  $setUpdate = '';
  $setUpdateID = '';
  $updateParams = '';
  $deleteUpdate = '';
  $deleteTemplate = '';
  $fileTable = <<<TABLE
<?php

use soho\myConfig as config;

require_once config::getPathAbsolute() . 'model/base/$nameFileBase.class.php';

use soho\model\base\\$nameFileBase;

/**
 * Description of $nameFileTable
 *
 * @author nombre completo <su@correo.com>
 * @package soho
 * @subpackage model
 * @subpackage table
 * @version 1.0.0
 */
class $nameFileTable extends $nameFileBase {

  static public function getAll() {
    \$conn = self::getConnection();
    \$sql = 'SELECT %fieldsAll% FROM $table %deleted_at%ORDER BY %created_at% ASC';
    \$answer = \$conn->prepare(\$sql);
    \$answer->execute();
    return (\$answer->rowCount() > 0) ? \$answer->fetchAll(PDO::FETCH_OBJ) : false;
  }

  static public function getById(\$id) {
    \$conn = self::getConnection();
    \$sql = 'SELECT %fieldsAll% '
            . 'FROM $table %deleted_at%'
            . 'AND id = :id';
    \$params = array(
        ':id' => \$id
    );
    \$answer = \$conn->prepare(\$sql);
    \$answer->execute(\$params);
    return (\$answer->rowCount() > 0) ? \$answer->fetchAll(PDO::FETCH_OBJ) : false;
  }

  public function save() {
    \$conn = self::getConnection();
    \$sql = 'INSERT INTO $table (%saveFields%) VALUES (%saveValues%)';
    \$params = array(
%saveParams%
    );
    \$answer = \$conn->prepare(\$sql);
    \$answer->execute(\$params);
    return \$conn->lastInsertId(self::_SEQUENCE);
  }

  public function update() {
    \$conn = self::getConnection();
    \$sql = 'UPDATE $table SET %setUpdate% WHERE %setUpdateID%';
    \$params = array(
%updateParams%
    );
    \$answer = \$conn->prepare(\$sql);
    \$answer->execute(\$params);
    return true;
  }

%deleteTemplate%

}\n
TABLE;
  $fileBase = <<<BASE
<?php

namespace soho\model\base;

use soho\shModel as model;

/**
 * Description of $nameFileBase
 *
 * @author nombre completo <su@correo.com>
 * @package soho
 * @subpackage model
 * @subpackage base
 * @version 1.0.0
 */
class $nameFileBase extends model {\n

BASE;
  $const = '';
  $attr = '';
  $sequence = '';
  $get = '';
  $set = '';
  foreach ($attributes as $item => $attribute) {
    if ($item === 'columns') {
      foreach ($attribute as $nameAttribute => $content) {
        $nameAttributeShort = (isset($content['name']) === true) ? $content['name'] : $nameAttribute;
        $nameAttributeOriginal = $nameAttribute;
        $fieldsAll .= ($nameAttributeOriginal === $nameAttributeShort) ? "$nameAttributeOriginal, " : "$nameAttributeOriginal AS $nameAttributeShort, ";
        $upper = strtoupper($nameAttributeShort);
        $getFunction = camelCase::getInstance()->camelCase('get_' . $nameAttributeShort);
        $setFunction = camelCase::getInstance()->camelCase('set_' . $nameAttributeShort);
        $attr .= "  private \$$nameAttributeShort;\n";
        $const .= "  const $upper = '$nameAttributeOriginal';\n";
        $get .= "\n  public function $getFunction() {\n    return \$this->$nameAttributeShort;\n  }\n";
        $set .= "\n  public function $setFunction(\$$nameAttributeShort) {\n    \$this->$nameAttributeShort = \$$nameAttributeShort;\n  }\n";
        $constructHeadItems .= "\$$nameAttributeShort = ";
        $constructBody .= "    \$this->$nameAttributeShort = \$$nameAttributeShort;\n";
        if (preg_match('/^((\w{3})\_(deleted_at))|^deleted_at/', $nameAttributeOriginal) === 1) {
          $deleted_at = "WHERE $nameAttributeOriginal IS NULL ";
          $deleteUpdate = "$nameAttributeOriginal = now()";
          $deleteTemplate = <<<DELETE
  public function delete(\$deleteLogical = true) {
    \$conn = self::getConnection();
    \$params = array(
%deleteID%
    );
    switch (\$deleteLogical) {
      case true:
        \$sql = 'UPDATE $table SET %deleteUpdate% WHERE %setUpdateID%';
        break;
      case false:
        \$sql = 'DELETE FROM $table WHERE %setUpdateID%';
        break;
      default:
        throw new PDOException('Por favor indique un dato coherente para el borrado lógico o físico');
    }
    \$answer = \$conn->prepare(\$sql);
    \$answer->execute(\$params);
    return true;
  }
DELETE;
        } else if (preg_match('/^((\w{3})\_(created_at))|^created_at/', $nameAttributeOriginal) === 1) {
          $created_at = $nameAttributeOriginal;
        } else if (preg_match('/^((\w{3})\_(id))|^id/', $nameAttributeOriginal) === 1) {
          $setUpdateID = "$nameAttributeOriginal = :$nameAttributeShort";
          $updateParams = "        ':$nameAttributeShort' => \$this->$getFunction()";
        } else if (preg_match('/^((\w{3})\_(id))|^id/', $nameAttributeOriginal) === 0 and preg_match('/^((\w{3})\_(updated_at))|^updated_at/', $nameAttributeOriginal) === 0 and preg_match('/^((\w{3})\_(created_at))|^created_at/', $nameAttributeOriginal) === 0 and preg_match('/^((\w{3})\_(deleted_at))|^deleted_at/', $nameAttributeOriginal) === 0) {
          $saveFields .= $nameAttributeOriginal . ', ';
          $saveValues .= ':' . $nameAttributeShort . ', ';
          $saveParams .= "        ':$nameAttributeShort' => \$this->$getFunction(),\n";
          $setUpdate .= "$nameAttributeOriginal = :$nameAttributeShort, ";
        }

        if (preg_match('/^((\w{3})\_(deleted_at))|^deleted_at/', $nameAttributeOriginal) === 0) {
          $deleteTemplate = <<<DELETE
  public function delete() {
    \$conn = self::getConnection();
    \$sql = 'DELETE FROM $table WHERE %setUpdateID%';
    \$params = array(
%deleteID%
    );
    \$answer = \$conn->prepare(\$sql);
    \$answer->execute(\$params);
    return true;
  }
DELETE;
        }
        foreach ($content as $key => $value) {
          $flagDefault = true;
          switch ($key) {
            case 'length':
              $const .= "  const " . $upper . "_LENGTH = $value;\n";
              break;
            case 'sequence':
              $sequence = "  const _SEQUENCE = '$value';\n";
              break;
            case 'encrypted':
              $set = str_replace(
                      "\n  public function $setFunction(\$$nameAttributeShort) {\n    \$this->$nameAttributeShort = \$$nameAttributeShort;\n  }\n", "\n  public static function $setFunction(\$$nameAttributeShort) {\n    \$this->$nameAttributeShort = hash('$value', \$$nameAttributeShort);\n  }\n", $set);
              break;
            case 'default':
              $flagDefault = false;
              if ($value === true) {
                $constructHeadItems .= "true, ";
              } else if ($value === false) {
                $constructHeadItems .= "false, ";
              } else if (is_numeric($value) === true) {
                $constructHeadItems .= "$value, ";
              } else if (is_array($value) === true) {
                $constructHeadItems .= "null, ";
                if ($value['value'] === 'NOW') {
                  $constructBody = substr($constructBody, 0, ((strlen($nameAttributeShort) * -1) + -3));
                  $constructBody .= "(\$$nameAttributeShort === null) ? date('" . $value['format'] . "') : \$$nameAttributeShort;\n";
                }
              } else {
                $constructHeadItems .= "'$value', ";
              }
              break;
          }
          # echo "$table -> $nameAttribute -> $key -> $value\n";
        }
        if ($flagDefault) {
          $constructHeadItems .= "null, ";
        }
      }
    }
  }
  $constructHeadItems = substr($constructHeadItems, 0, -2);
  $constructBody = substr($constructBody, 0, -1);
  $construct = $constructHead . $constructHeadItems . ") {\n" . $constructBody . "\n  }\n";
  $fileBase .= $const . $sequence . $nameTable . "\n" . $attr . $construct . $get . $set . "\n}\n";

  # echo $base;
  $file = fopen(config::getPathAbsolute() . "model/base/$nameFileBase.class.php", "w");
  fwrite($file, $fileBase);
  fclose($file);

  $file = config::getPathAbsolute() . "model/$nameFileTable.class.php";
  $fileTable = strtr($fileTable, array(
      '%fieldsAll%' => substr($fieldsAll, 0, -2),
      '%deleted_at%' => $deleted_at,
      '%created_at%' => $created_at,
      '%saveFields%' => substr($saveFields, 0, -2),
      '%saveValues%' => substr($saveValues, 0, -2),
      '%saveParams%' => substr($saveParams, 0, -2),
      '%setUpdate%' => substr($setUpdate, 0, -2),
      '%setUpdateID%' => $setUpdateID,
      '%updateParams%' => $saveParams . $updateParams,
      '%deleteID%' => $updateParams,
      '%deleteUpdate%' => $deleteUpdate,
      '%deleteTemplate%' => strtr($deleteTemplate, array(
          '%setUpdateID%' => $setUpdateID,
          '%deleteID%' => $updateParams,
          '%deleteUpdate%' => $deleteUpdate
      ))
  ));

  if (file_exists($file) === false) {
    $file = fopen($file, "w");
    fwrite($file, $fileTable);
    fclose($file);
  }
}