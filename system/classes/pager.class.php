<?php
/**************************************************************************************
 Test task

 Here are defined class CPager.

 Author: Loginov Leonid
 File revision: 1.0.0

 Copyright (c) 2011 Loginov Leonid
***************************************************************************************/
/**************************************************************************************
 "CPager" responsible for the formation of navigation through the pages.
***************************************************************************************/

define('F_PAGER','system/classes/Pager_template/');

class ClassPager
{
    public function __construct($param)
    {
        $this->record_on_page=$param['record_on_page'];
        $this->get_name=$param['get_name'];

        isset($_GET[$this->get_name]) ? $this->current_page=$_GET[$this->get_name] : $this->current_page=1;

        $this->start_record=($this->current_page * $this->record_on_page) - $this->record_on_page;

        $this->limit_href=$param['limit_href'];
        $this->href=$param['href'];

        $this->template = isset($param['template']) ? $param['template'] : "default";
    }

    /*
    * This function responsible for the formation of navigation through the pages.
    */
    public function createPager()
    {
        $this->amount_page=ceil($this->amount_record / $this->record_on_page);
        $page_start=ceil($this->current_page/$this->limit_href) * $this->limit_href - ($this->limit_href-1);
        $page_end=ceil($this->current_page/$this->limit_href) * $this->limit_href;
        if($page_end > $this->amount_page)
            $page_end=$this->amount_page;

        strpos($this->href,'?') ? $delimer = '&' : $delimer = '?';

        for($num_page=$page_start;$num_page<=$page_end;$num_page++)
        {
            if($this->current_page==$num_page)
            {
                $this->pager_list[$num_page]="";
            }
            else
            {
                if($num_page==1)
                    $this->pager_list[$num_page]=$this->href;
                else
                    $this->pager_list[$num_page]=$this->href.$delimer.$this->get_name."=".$num_page;
            }
        }

        $this->pager_data['amount_page']=$this->amount_page;
        $this->pager_data['list']=$this->pager_list;
        $this->pager_data['current_page']=$this->current_page;

        $str_code = file_get_contents(F_PAGER.$this->template.".php");
        $this->pager_data['list']=eval($str_code);
    }

    /*
    * Amount record on page
    */
    public  $record_on_page;

    /*
    * The record id in table of database which will begin selecting records
    */
    public  $start_record;

    /*
    * Namber of current page
    */
    public  $current_page;

    /*
    * Total amount page
    */
    public  $amount_page;

    /*
    * Name of GET parametr which contains number current page
    */
    public  $get_name;

    /*
    * Max amount links in the navigation
    */
    public $limit_href;

    /*
    * String contains list of params  that should be added to 'href'
    */
    public $href;

    /*
    *Array contains list of links for navigation
    */
    public $pager_list=array();

    /*
    * All data about page navigation
    */
    public $pager_data=array();

    /*
    * Name of the template which is responsible for display the navigator
    */
    private $template;

}
