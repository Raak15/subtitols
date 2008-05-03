<?php 
	/*
    This file is part of wikisubtitles.

    wikisubtitles is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    wikisubtitles is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Foobar.  If not, see <http://www.gnu.org/licenses/>.
*/
	class RssReader { 
	    var $url; 
	    var $data; 
	    
	    function RssReader ($url){ 
	        $this->url; 
	        $this->data = implode ("", file ($url)); 
	    } 
	    
	    function get_items (){ 
	        preg_match_all ("/<item .*>.*<\/item>/xsmUi", $this->data, $matches); 
	        $items = array (); 
	        foreach ($matches[0] as $match){ 
	            $items[] = new RssItem ($match); 
	        } 
	        return $items; 
	    } 
	} 
	
	class RssItem { 
	    var $title, $url, $description; 
	    
	    function RssItem ($xml){ 
	        $this->populate ($xml); 
	    } 
	    
	    function populate ($xml){ 
	        preg_match ("/<title> (.*) <\/title>/xsmUi", $xml, $matches); 
	        $this->title = $matches[1]; 
	        preg_match ("/<link> (.*) <\/link>/xsmUi", $xml, $matches); 
	        $this->url = $matches[1]; 
	        preg_match ("/<description> (.*) <\/description>/xsmUi", $xml, $matches); 
	        $this->description = $matches[1]; 
    } 
	    
    function get_title (){ 
        return $this->title; 
	    } 
	
	    function get_url (){ 
        return $this->url; 
	    } 
	    
	    function get_description (){ 
	        return $this->description; 
	    } 
	}
?>