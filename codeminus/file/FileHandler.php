<?php

namespace codeminus\file;

use codeminus\util as util;

/**
 * FileHandler
 *
 * @author Wilson Santos <wilson@codeminus.org>
 * @version 1.1
 */
class FileHandler {

  /**
   * Copies all files from a folder to another recursively
   * @param string $src source path to copy from
   * @param string $dst destination path to copy to
   * @param boolean $replaceExistent if TRUE it will replace all existent files
   * @return void
   */
  public static function recursiveCopy($src, $dst, $replaceExistent = false) {
    $dir = opendir($src);
    if (!file_exists($dst)) {
      mkdir($dst);
      util\ClassLog::add(__METHOD__, $dst . ' created');
    } else {
      util\ClassLog::add(__METHOD__, $dst . ' not created. Directory already exists', util\ClassLog::LOG_WARNING);
    }
    while (false !== ( $file = readdir($dir))) {
      if (( $file != '.' ) && ( $file != '..' )) {
        $sourceFilePath = $src . '/' . $file;
        $destinationFilePath = $dst . '/' . $file;
        if (is_dir($sourceFilePath)) {
          self::recursiveCopy($sourceFilePath, $destinationFilePath, $replaceExistent);
        } else {
          if (file_exists($destinationFilePath) && $replaceExistent) {
            self::delete($destinationFilePath);
          }
          if (!file_exists($destinationFilePath)) {
            copy($sourceFilePath, $destinationFilePath);
            util\ClassLog::add(__METHOD__, $destinationFilePath . ' created');
          } else {
            util\ClassLog::add(__METHOD__, $destinationFilePath . ' not created. File already exists', util\ClassLog::LOG_WARNING);
          }
        }
      }
    }
    closedir($dir);
  }

  /**
   * Delete directory of file
   * @param string $mixed path to delete
   * @return boolean TRUE if path deleted with success and FALSE otherwise
   */
  public static function delete($mixed) {
    $deleted = false;
    if (is_dir($mixed)) {
      if(rmdir($mixed)){
        $deleted = true;
      }
    } else {
      if(unlink($mixed)){
        $deleted = true;
      }
    }
    if($deleted){
      util\ClassLog::add(__METHOD__, $mixed . ' deleted', util\ClassLog::LOG_ERROR);
    }
    return $deleted;
  }

  /**
   * Creates a file
   * @param string $fileName complete file path
   * @param string $fileContent Content to be put inside the file
   * method
   * @param boolean $replaceExistent if TRUE it will replace all existent files
   * @return void
   */
  public static function createFile($fileName, $fileContent, $replaceExistent) {
    if($replaceExistent){
      self::delete($fileName);
    }
    if (!file_exists($fileName)) {
      if (!file_put_contents($fileName, $fileContent)) {
        exit('<p>Unable to create ' . $fileName . '</p>');
      } else {
        util\ClassLog::add(__METHOD__, $fileName . ' created');
      }
    } else {
      util\ClassLog::add(__METHOD__, $fileName . ' not created. File already exists', util\ClassLog::LOG_WARNING);
    }
  }

  /**
   * Creates a directory
   * @param string $dir path and name
   * @param octal $mode[optional] the access level desired for the folder
   * @return void;
   */
  public static function createDir($dir, $mode = 0755) {
    if (!file_exists($dir)) {
      if (!mkdir($dir, $mode, true)) {
        exit('<p class="warning">Unable to create ' . $dir . '</p>');
      } else {
        util\ClassLog::add(__METHOD__, $dir . ' created');
      }
    } else {
      util\ClassLog::add(__METHOD__, $dir . ' not created. Directory already exists', util\ClassLog::LOG_WARNING);
    }
  }

  /**
   * File extension
   * @param string $fileName Containing a filename
   * @return string extension
   */
  public static function getFileExtension($fileName) {
    $parts = explode(".", $fileName);
    return strtolower(end($parts));
  }

  /**
   * Validate file extension
   * @param string $filename Containing a filename
   * @param string $validExtensions List of extensions separated by ,(comma).
   * <br/>Example: "gif, jpg, png"
   * @return boolean TRUE if the extension is valid and FALSE otherwise.
   */
  public static function validateExtension($filename, $validExtensions) {
    $fileExtension = self::getFileExtension($filename);
    $validExtensions = explode(',', $validExtensions);
    foreach ($validExtensions as $ext) {
      if ($fileExtension == trim($ext)) {
        return true;
      }
    }
    return false;
  }

}