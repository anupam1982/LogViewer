<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
App::uses('Controller', 'Controller');
App::uses('MylogviewController', 'Controller');

class MylogviewerControllerTest extends ControllerTestCase
{
        
    public function setUp() 
    {
        parent::setUp();
        $Controller = new Controller();
    }
    public function testread_from_file()
    {               
        $data = array(
            'rest_path' =>"C:/Test/proplog.txt",
            'page_no'=> 2
        );
        $result = $this->testAction(
            '/mylogviewer/read_from_file',
            array('data' => $data, 'method' => 'get')
        );
        $result = json_decode($result, true);
        $expected = array(
            'body' => array('result' => "Success", 'msg' => "Attached in DOMElement and using callbacks to live preview the color and adding animation
                                                            Attached to DOMElement and using callbacks to live preview the color and adding animation
                                                            professional software development experience on Microsoft platform.
                                                            professional software development experience on Microsoft platform.
                                                            professional software development experience on Microsoft platform.
                                                            professional software development experience on Microsoft platform.
                                                            professional software development experience on Microsoft platform.
                                                            professional software development experience on Microsoft platform.
                                                            professional software development experience on Microsoft platform.
                                                            professional software development experience on Microsoft platform."),
        );
                
        $this->assertEquals($expected, $result);     
        
    }
    
    public function testmove_to_end()
    {               
        $data = array(
            'rest_path' =>"C:/Test/proplog.txt"
        );
        $result = $this->testAction(
            '/mylogviewer/move_to_end',
            array('data' => $data, 'method' => 'get')
        );
        $result = json_decode($result, true);
        $expected = array(
            'body' => array('result' => "Success", 'msg' => "All these multinodes have single executable named atp-actors
                                                            All these nodes have single executable named atp-actors"),
        );                
        $this->assertEquals($expected, $result);        
    }    
        
    public function testread_log_file()
    {               
        $data = array(
            'rest_path' =>"C:/Test/proplog.txt",
            'page_no'=> 1
        );
        $result = $this->testAction(
            '/mylogviewer/read_log_file',
            array('data' => $data, 'method' => 'get')
        );
        $expected = array(
            'body' => array('result' => "Success", 'msg' => "Anupam
                                                            Anupammukh
                                                            Anupamjjkklliio
                                                            Attached to DOMElement and using callbacks to live preview the color and adding animation.Attached to DOMElement and using callbacks to live preview the color and adding animation.Attached to DOMElement and using callbacks to live preview the color and adding animation.Attached to DOMElement and using callbacks to live preview the color and adding animation
                                                            Attached to DOMElement and using callbacks to live preview the color and adding animation
                                                            Attached to DOMElement and using callbacks to live preview the color and adding animation
                                                            Attached to DOMElement and using callbacks to live preview the color and adding animation
                                                            Attached to DOMElement and using callbacks to live preview the color and adding animation
                                                            Attached to DOMElement and using callbacks to live preview the color and adding animation
                                                            Attached to DOMElement and using callbacks to live preview the color and adding animation"),
        );
                
        $this->assertEquals($expected, $result);  
    }
    
    public function testget_page_count()
    {        
        $result = $this->testAction(
            '/mylogviewer/get_page_count',
            array('method' => 'get')
        );
        $result = json_decode($result, true);
        $expected = array(
            'body' => array('result' => "Success", 'msg' => 4),
        );                
        $this->assertEquals($expected, $result);
    } 
}

