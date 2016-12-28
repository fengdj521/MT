<?php
App::uses('Xml', 'Utility');

class RssComponent extends Component{
	public function read($feed, $items = 10) {

        //アメブロのフィードだったら、URLを変換する
        if(strstr($feed,'rssblog.ameba.jp')){
         //ユーザーID抜き出し
         preg_match('@^(?:http://rssblog\.ameba\.jp/)?([^/]+)@i', $feed, $matches);
         $user_id = $matches[1];
         $feed = "http://feedblog.ameba.jp/rss/ameblo/".$user_id. "/rss20.xml";
        }

        try{
        	//RSSフィードをリード
        	$xmlObject = Xml::build($feed);
        }catch(XmlException $e) {
        	throw new InternalErrorException();
        }

        $output = [];
        for($i = 0;$i < $items;$i++){
         if (is_object( $xmlObject->channel->item->$i)) {
           $output[] = $xmlObject->channel->item->$i;
         }elseif (is_object($xmlObject->item->$i)) {
           $output[] = $xmlObject->item->$i;
         }
        }
        return $output;
	}
}