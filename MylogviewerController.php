<?php

/**
* Class MylogviewerController is the Controller class for LogView application
* @package  Example
* @author   Anupam Mukherjee (anupam.life@gmail.com)
* @version  $Revision: 0.1 $
*/

App::uses('AppController', 'Controller');


class MylogviewerController extends AppController
{
    var $name = "Mylogviewer";
    public $components = array('RequestHandler');
    public $helpers = array('Html', 'Form', 'Session');
    public $interval  = 10;
    
    
    /**
    * returns the total no of pages based on total file size
    *
    * @param  nil
    * @return total count of pages
    * @access public
    */
    
    public function calculate_total_page_count()
    {
        $this->autoRender = false;
        $no_of_pages = 1;
        $file_pos_array = unserialize($this->Session->read('file_pos'));
        $mod_pages = count($file_pos_array)%($this->interval);        
        if($mod_pages == 0)
        {
            $no_of_pages = floor(count($file_pos_array)/$this->interval);
        }
        else
        {
            $no_of_pages = floor(count($file_pos_array)/$this->interval) + 1;
        }
        return $no_of_pages;
    }
    
     /**
    * returns the json formatted string for total no of pages based on total file size
    *
    * @param  nil
    * @return json string with total count of pages data
    * @access public
    */
    
    public function get_page_count()
    {
        $this->autoRender = false;
        $no_of_pages =  $this->calculate_total_page_count();
        return new CakeResponse(array('body' => json_encode(array("result" => "Success", 
                                                  "msg" => json_encode(($no_of_pages))))));        
    }
    
    /**
    * returns the json formatted string containing the array with
     * the last 10 lines data read from the file
     * The method gets called if user decides to move to the end of the file.
     * @param  Through $_GET[rest_path] file path to the file
     * @param  Through $_GET[page_no] for specifying offset
     * @return json formatted string containing the array with
     * the last 10 elements from the file
    * @access public
    */
    public function move_to_end()
    {
        $file_array = array();
        $file_pos_array = unserialize($this->Session->read('file_pos'));
        $fpath = $_GET['rest_path'];
        $no_of_pages = $this->calculate_total_page_count();
        $pos = ($no_of_pages - 1)* $this->interval;
        if(!file_exists($fpath))
        {
            $ret = "File does not exist.";
            return new CakeResponse(array('body' => json_encode(array("result" => "Failed", 
                                              "msg" => json_encode(($ret))))));
        }
        $fp = fopen($fpath, "r");
        if(!$fp)
        {
                $ret = "File cannot be opened";
                return new CakeResponse(array('body' => json_encode(array("result" => "Failed", 
                                              "msg" => json_encode(($ret))))));
        }
        fseek($fp,$file_pos_array[$pos]);
        for ($i = 0; $i < $this->interval; $i++)
        {
           if(!feof($fp))
           {
                $buf = fgets($fp);                
                if(strlen($buf) <= 2)
                {
                   $interval = $interval + 1;
                   continue;
                }
                array_push($file_array,$buf);
           }
        }
        fclose($fp);
        return new CakeResponse(array('body' => json_encode(array("result" => "Success", 
                                                  "msg" => json_encode(($file_array))))));
    }   
    /**
    * returns the array with
     * the 10 lines data read from the file
     * @param  Through $_GET[rest_path] file path to the file
     * @param  Through $_GET[page_no] for specifying offset
     * @return the array with
     * the 10 lines data read from the file from specified offset
    * @access public
    */

    public function read_log_file()
        {        
            $this->autoRender = false; 
            $file_pos_array = unserialize($this->Session->read('file_pos'));
            $file_array = array();
            $fpath = $_GET['rest_path'];
            $pg_no = $_GET['page_no'];
            $pos = ($pg_no - 1)* $this->interval;
            if(!file_exists($fpath))
            {
                $ret = "File does not exists.";
                return new CakeResponse(array('body' => json_encode(array("result" => "Failed", 
                                                  "msg" => json_encode(($ret))))));
            }
            $fp = fopen($fpath, "r");
            if(!$fp)
            {
                    $ret = "File cannot be opened";
                    return new CakeResponse(array('body' => json_encode(array("result" => "Failed", 
                                                  "msg" => json_encode(($ret))))));
            }
            $interval = $this->interval;
            fseek($fp,$file_pos_array[$pos]);
            for ($i = 0; $i < $interval; $i++)
            {
                if(!feof($fp))
                {
                    $buf = fgets($fp);                
                    if(strlen($buf) <= 2)
                    {
                       $interval = $interval + 1;
                       continue;
                    }
                    array_push($file_array,$buf);
                }
            }
            fclose($fp);
            return $file_array;

        }
        /**
         * Does the initial reading of the file and populating the
         * data structure to store the file positions and the length of the characters
         * in each line for future referal
        * returns the json formatted string containing the array with
         * the 10 lines data read from the file from specified offset
         * @param  Through $_GET[rest_path] file path to the file
         * @param  Through $_GET[page_no] for specifying offset
         * @return the json formatted string containing the array with
         * the 10 lines data read from the file from specified offset
        * @access public
        */
        public function read_from_file()
        {
            $this->autoRender = false;
            if($this->request->is('get'))
            {
                $msg = $_GET['rest_path'];
                if(!file_exists($msg))
                {
                    $ret = "File does not exist.";
                    return new CakeResponse(array('body' => json_encode(array("result" => "Failed", 
                                                      "msg" => json_encode(($ret))))));
                }
                $file_array = array();
                $fp = fopen($msg, "r");                
                if(!$fp)
                {
                    $ret = "File cannot be opened";
                    return new CakeResponse(array('body' => json_encode(array("result" => "Failed", 
                                                  "msg" => json_encode(($ret))))));
                }
                    
                $count = 0;
                $start_pos = 0;
                while(!feof($fp))
                {
                    $buf = fgets($fp);
                    if(strlen($buf) <= 2)
                    {
                        $start_pos += strlen($buf);
                        continue;
                    }
                    $file_array[$count] = $start_pos;
                    $start_pos += strlen($buf);
                    $count++;
                }
                fclose($fp);
                //var_dump($_SERVER['PHP_SELF']);
                $this->Session->write('file_pos',  serialize($file_array));
                $ret = $this->read_log_file();
                //print_r($ret);
                if(is_array($ret) && !empty($ret))
                {
                    return new CakeResponse(array('body' => json_encode(array("result" => "Success", 
                                                  "msg" => json_encode(($ret))))));
                }
                elseif ((is_string ($ret)) && (stripos("Error",$ret)!== FALSE))
                {
                    return new CakeResponse(array('body' => json_encode(array("result" => "Failed", 
                                                  "msg" => json_encode(($ret))))));

                }
            }
        }
        
         /**
        * Initialise the view for the user
        * Deletes the session initially.
        * @param  nil
        * @return nil
        * @access public
        */
        
        public function view_logs()
        {
           //ini_set('max_execution_time', 0);
           //ini_set('memory_limit', '2560M');
           $this->Session->delete('file_pos');
        }
}