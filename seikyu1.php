<?php
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: *");
function connect() {
    return new PDO("mysql:dbname=suzukbel52556jp37747_wp", "suzuk_uodon","9k0wa_O1");
  
  }

function makepdf($filename,$type){  
//if (@$_POST['submit']) {
$tax_reduced = 1.08;
$tax_reduced_0 = 0.08;
$tax_standard = 1.1;
$tax_standard_0 = 0.1;
$shop = $_POST['shop'];
$date1 = $_POST['date1'];
$date2_ts = $_POST['date2'];
$date2 = date('Y-m-d', strtotime($_POST['date2'].' +1 day'));
$issue = date("Y年m月d日",strtotime($_POST['issue']));
$kigen = date("Y年m月d日",strtotime($_POST['kigen']));
$noren = $_POST['noren'];
$paso = $_POST['paso'];
$uodon = $_POST['uodon'];
$server = $_POST['server'];
$web = $_POST['web'];
$fee = $_POST['fee'];
$toku = $_POST['toku'];
$toku_price = $_POST['toku_price'];
$toku2 = $_POST['toku2'];
$toku_price2 = $_POST['toku_price2'];
$toku3 = $_POST['toku3'];
$toku_price3 = $_POST['toku_price3'];
$all_others = $noren+$paso+$uodon+$server+$web+$fee+$toku_price+$toku_price2+$toku_price3;

$pdo = connect();
$pdo->query('SET NAMES utf8');
//注文IDと詳細取得
$st = $pdo->query("SELECT ID FROM D0vXvc_usces_order where order_name1='".$shop."'and order_date BETWEEN'".$date1."' AND '".$date2."' ORDER BY ID ASC");
$id_all = $st->fetchAll();
$i = 0;
foreach ($id_all as $value) {
    $id[$i] = $value ;
    $id[$i] = $id[$i]["ID"];
    $st = $pdo->query("SELECT order_id,post_id, item_name, price, quantity  FROM D0vXvc_usces_ordercart where order_id='".$id[$i]."' ORDER BY order_id ASC ");
    $sku[$i] = $st->fetchAll();
    $i++;
    }
//var_dump($sku[0]);
//注文概要取得

$sql='SELECT order_name2, order_email FROM D0vXvc_usces_order WHERE order_name1=:name AND order_date BETWEEN :date1 AND :date2';
$s=$pdo->prepare($sql);
$s->execute(array(':name'=>$shop,':date1'=>$date1,':date2'=>$date2));
$s_data=$s->fetch(PDO::FETCH_ASSOC);
$shop2 = $s_data["order_name2"];
$email = $s_data["order_email"];


$st = $pdo->query("SELECT sum(order_item_total_price) FROM D0vXvc_usces_order where order_name1='".$shop."'  and order_date BETWEEN'".$date1."' AND '".$date2."' ");
$gdata = $st->fetchAll();
$st = $pdo->query("SELECT sum(order_shipping_charge) FROM D0vXvc_usces_order where order_name1='".$shop."'  and order_date BETWEEN'".$date1."' AND '".$date2."' ");
$sdata = $st->fetchAll();
$zbgoukei = $gdata[0]["sum(order_item_total_price)"]+$sdata[0]["sum(order_shipping_charge)"]+$all_others;
$zbgoukei1 = $gdata[0]["sum(order_item_total_price)"]+$sdata[0]["sum(order_shipping_charge)"];

$st = $pdo->query("SELECT sum(order_tax) FROM D0vXvc_usces_order where order_name1='".$shop."'  and order_date BETWEEN'".$date1."' AND '".$date2."' ");
$gdata = $st->fetchAll();
$taxgoukei = $gdata[0]["sum(order_tax)"]+($all_others*$tax_standard_0);
$taxgoukei1 = $gdata[0]["sum(order_tax)"];

$goukei = $zbgoukei+$taxgoukei;

$f_zbgoukei = number_format($zbgoukei);
$f_taxgoukei = number_format($taxgoukei);
$f_goukei = number_format($goukei);

//$zei = number_format($goukei[0]["sum(order_item_total_price)"]*$tax);
//$zgoukei = number_format($goukei[0]["sum(order_item_total_price)"]*(1+$tax));
//$goukei = number_format($goukei[0]["sum(order_item_total_price)"]+$noren+$paso+$uodon+$server);
//$zei = number_format($goukei_all*$tax);
//$zgoukei = number_format($goukei_all*(1+$tax));

//$noren = number_format($noren);
//$paso = number_format($paso);
//$uodon = number_format($uodon);
//$server = number_format($server);
//$web = number_format($web);
//$fee = number_format($fee);
//$toku_price = number_format($toku_price);
//$toku_price2 = number_format($toku_price2);
//$toku_price3 = number_format($toku_price3);
$date2_ts = date("Y年m月d日",strtotime($date2_ts));

//var_dump($goukei);

require_once './TCPDF/tcpdf.php';
$pdf = new TCPDF("P", "mm", "A4", true, "UTF-8" );
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->AddPage();
$pdf->SetFont('kozminproregular', '', 10); //フォントをＩＰＡ Ｐゴシック
$pdf->SetMargins(10, 10, true);
$today = time(); 
//css
$css = '<style>
  	table {
  		text-align: left;
  		width: 100%;"
  	}
	th {
		vertical-align: middle;
		background-color: rgb(153, 153, 153);
        white-space: nowrap;
        width:45px;
		text-align: center;"
	}
	th.con {
		vertical-align: middle;
		background-color: rgb(153, 153, 153);
        white-space: nowrap;
        width:270px;
		text-align: center;"
		
	}
	th.num {
		vertical-align: middle;
		background-color: rgb(153, 153, 153);
        white-space: nowrap;
        width:50px;
		text-align: center;"
	}
	td {
		vertical-align: middle;
        white-space: nowrap;
        width:45px;
		text-align: center;"
		font-size:8;
	}
	td.con {
		vertical-align: middle;
        white-space: nowrap;
        width:270px;
  		text-align: left;"
  		font-size:8;
	}
	td.num {
		vertical-align: middle;
        white-space: nowrap;
        width:50px;
  		text-align: right;"
	}
    td.add1 {
        vertical-align: middle;
        white-space: nowrap;
        width:250px;
        text-align: left;"
    }
    td.add2 {
        vertical-align: middle;
        white-space: nowrap;
        width:250px;
        text-align: right;"
    }
   </style>';
//html content
$html =  '<div style="text-align: center;font-size: 16pt;">&nbsp;&nbsp;&nbsp;&nbsp;ご&nbsp;請&nbsp;求&nbsp;書&nbsp;&nbsp;&nbsp;&nbsp;</div>'
     . '<div align="right">No. '.$today.'</div>'
     . '<div align="left" style="font-size: 14pt;text-decoration: underline;">'.$shop.'&nbsp;&nbsp;御中</div>'
     . '<div align="left">ご担当&nbsp;&nbsp;'.$shop2.'&nbsp;様</div>'
    
     . '<div>'
     . '<table  border="0" cellpadding="0" cellspacing="0">'
     . '<tbody>'
     . '<tr>'
     . '<td class="add1">下記の通りご請求申し上げます。<br />'
     . '概要：'.$date2_ts.'締切分<br />'
     . '⽀払条件： 銀⾏振り込み<br />'
     . '⽀払期限：'.$kigen.'<br />'
     . '振 込 先：足立成和信用金庫 旭町支店<br /> '
     . '口座番号：普通 No.0480457<br /> '
     . '口座名義人：株式会社ベルツリーカンパニー</td>'


     . '<td class="add2">ご請求日&nbsp;&nbsp;'.$issue.'<br />'
     . '株式会社ベルツリーカンパニー<br />'
     . '代表取締役&nbsp;&nbsp;鈴木常隆<br />'
     . '〒120-0026東京都足立区千住旭町11-7<br />'
     . '創業支援施設｢あかつき｣401号室<br />'
     . 'TEL：03-5284-7583<br />'
     . 'FAX：03-5284-7584<br />'
     . 'E-mail：keiri@uodon.jp</td>' 
     . '</tr>'
     . '</tbody></table>'
     . '</div>'
     
      . '<div style="text-align: left;font-size: 13pt;font-weight: bold;text-decoration: underline;">ご請求金額(税込)&nbsp;&nbsp;&nbsp;￥'.$f_goukei.'</div>'
     . '<div style="text-align: center;">'
     . '<table  border="1" cellpadding="0" cellspacing="0">'
     . '<tbody>'
     . '<tr>'
     . '<th>No</th>'
     . '<th class="con">摘要</th>'
     . '<th>数量</th>'
     . '<th class="num">単価</th>'
     . '<th class="num">金額</th>'
     . '<th>税率</th>'
     . '<th class="num">税込</th>'
     . '</tr>';
$GLOBALS['zei8'] = 0;
//$GLOBALS['syoukei8'] = 0;
$GLOBALS['zei10'] = 0;
$GLOBALS['other'] = 0;
//$syoukei10=0 ;
//$zgoukei = 0;
 foreach($sku as $value1 => $key1){
     $i=0;
   foreach($key1 as $key2){
    $i++;
    $count = count($key1);
    $syoukei = number_format($key2["price"]*$key2["quantity"]);
    $price = number_format($key2["price"]);
       $html .='<tr>'
     . '<td>'.$key2["order_id"].'</td>'
     . '<td class="con">'.$key2["item_name"].'</td>'
     . '<td>'.$key2["quantity"].'個</td>'
     . '<td class="num">'.$price.'&nbsp;</td>'
     . '<td class="num">￥'.$syoukei.'&nbsp;</td>'
     ;
     
     //$stmt = $pdo->query("SELECT meta_value FROM D0vXvc_postmeta where post_id='".$key2["post_id"]."'and meta_key=_isku_");
     //$zeihantei = $stmt->fetch(PDO::FETCH_ASSOC);
     
    $id=$key2["post_id"];
    $ps='_isku_';
    $sql='SELECT meta_value FROM D0vXvc_postmeta WHERE post_id=:id AND meta_key=:ps';
    $s=$pdo->prepare($sql);
    $s->execute(array(':id'=>$id,':ps'=>$ps));
    $data=$s->fetch(PDO::FETCH_ASSOC);
     
     //var_dump($data);
     //break;
     if(strpos($data["meta_value"],'reduced') !== false){
     $html.='<td>8%&nbsp;</td>';
     $zeikomi = number_format(round(($key2["price"]*$key2["quantity"])*$tax_reduced));
     
     //$zei8 +=round(($key2["price"]*$key2["quantity"])*$tax_reduced_0);
     $GLOBALS['zei8']+=$key2["price"]*$key2["quantity"];
     }
     else {
     $html.='<td>10%&nbsp;</td>';   
     $zeikomi = number_format(round(($key2["price"]*$key2["quantity"])*$tax_standard));
     
     //$GLOBALS['zei10'] +=round(($key2["price"]*$key2["quantity"])*$tax_standard_0);
     $GLOBALS['zei10']+=$key2["price"]*$key2["quantity"];
     
     }
    $html.='<td class="num">￥'.$zeikomi.'</td>'
     . '</tr>';
    
        if($i===$count){
        $id=$key2['order_id'];
        $sql='SELECT order_shipping_charge FROM D0vXvc_usces_order WHERE ID=:id';
        $s=$pdo->prepare($sql);
        $s->execute(array(':id'=>$id));
        $sp_data=$s->fetch(PDO::FETCH_ASSOC);  
            if($sp_data['order_shipping_charge']>0){
            $f_ship = number_format($sp_data['order_shipping_charge']);
            $zf_ship = number_format($sp_data['order_shipping_charge']*$tax_standard);
            $GLOBALS['zei10']+=$sp_data['order_shipping_charge'];
            $html.='<tr>'
                    . '<td>'.$key2['order_id'].'</td>'
                    . '<td class="con">送料</td>'
                    . '<td>一式</td>'
                    . '<td class="num">'.$f_ship.'</td>'
                    . '<td class="num">￥'.$f_ship.'&nbsp;</td>'
                    . '<td>10%&nbsp;</td>'
                    . '<td class="num">￥'.$zf_ship.'&nbsp;</td>'
                    . '</tr>';
            }
        }
   }
 }
 
 function koteihi($item,$cost,$tax_standard){
    $zkoteihi = number_format($cost*$tax_standard);
    $f_cost = number_format($cost);
    $GLOBALS['other'] +=$cost;
    $GLOBALS['zei10'] +=$cost;
    return 
       '<tr>'
     . '<td>＊＊＊</td>'
     . '<td class="con">'.$item.'</td>'
     . '<td>一式</td>'
     . '<td class="num">'.$f_cost.'</td>'
     . '<td class="num">￥'.$f_cost.'&nbsp;</td>'
     . '<td>10%&nbsp;</td>'
     . '<td class="num">￥'.$zkoteihi.'&nbsp;</td>'
     . '</tr>';
     
   }
 function koteihi_2($item,$cost,$tax_standard){
    $zkoteihi = number_format($cost*$tax_standard);
    $f_cost = number_format($cost);
    $GLOBALS['other2'] +=$cost;
    $GLOBALS['zei10'] +=$cost;
    return 
       '<tr>'
     . '<td>＊＊＊</td>'
     . '<td class="con">'.$item.'</td>'
     . '<td>一式</td>'
     . '<td class="num">'.$f_cost.'</td>'
     . '<td class="num">￥'.$f_cost.'&nbsp;</td>'
     . '<td>10%&nbsp;</td>'
     . '<td class="num">￥'.$zkoteihi.'&nbsp;</td>'
     . '</tr>';
     
   }
if($uodon>0){
$html.= koteihi("魚丼市場利用料",$uodon,$tax_standard);
}
if($fee>0){    
$html.= koteihi("その他送料",$fee,$tax_standard);
}

$zbgoukei1+= $GLOBALS['other'];
$taxgoukei1+= $GLOBALS['other']*$tax_standard_0;
$goukei1 = $zbgoukei1+$taxgoukei1;
$f_zbgoukei1 = number_format($zbgoukei1);
$f_taxgoukei1 = number_format($taxgoukei1);
$f_goukei1 = number_format($goukei1);

$html .= '<tr>'
     . '<td>&nbsp;</td>'
     . '<td class="con">&nbsp;</td>'
     . '<td>&nbsp;</td>'
     . '<td class="num">魚丼市場小計</td>'
     . '<td class="num">￥'.$f_zbgoukei1.'&nbsp;</td>'
     . '<td>￥'.$f_taxgoukei1.'&nbsp;</td>'
     . '<td class="num">￥'.$f_goukei1.'&nbsp;</td>'
     . '</tr>'
     ;
$html .= '<tr>'
     . '<td>&nbsp;</td>'
     . '<td class="con">&nbsp;</td>'
     . '<td>&nbsp;</td>'
     . '<td class="num">&nbsp;</td>'
     . '<td class="num">&nbsp;</td>'
     . '<td>&nbsp;</td>'
     . '<td class="num">&nbsp;</td>'
     . '</tr>'
     ;


if($noren>0){
$html.= koteihi_2("のれん代",$noren,$tax_standard); 
}
if($paso>0){
$html.= koteihi_2("PASOREGI利用料",$paso,$tax_standard);
}
if($server>0){
$html.= koteihi_2("HPサーバー利用料",$server,$tax_standard); 
}
if($web>0){    
$html.= koteihi_2("Web受注システム利用料",$web,$tax_standard); 
}     
if($toku_price>0){    
$html.= koteihi_2($toku,$toku_price,$tax_standard);
}
if($toku_price2>0){    
$html.= koteihi_2($toku2,$toku_price2,$tax_standard);
}
if($toku_price3>0){    
$html.= koteihi_2($toku3,$toku_price3,$tax_standard);
}

$zbgoukei2 = $GLOBALS['other2'];
$taxgoukei2 = $GLOBALS['other2']*$tax_standard_0;
$goukei2 = $zbgoukei2+$taxgoukei2;
$f_zbgoukei2 = number_format($zbgoukei2);
$f_taxgoukei2 = number_format($taxgoukei2);
$f_goukei2 = number_format($goukei2);

$html .= '<tr>'
     . '<td>&nbsp;</td>'
     . '<td class="con">&nbsp;</td>'
     . '<td>&nbsp;</td>'
     . '<td class="num">その他小計</td>'
     . '<td class="num">￥'.$f_zbgoukei2.'&nbsp;</td>'
     . '<td>￥'.$f_taxgoukei2.'&nbsp;</td>'
     . '<td class="num">￥'.$f_goukei2.'&nbsp;</td>'
     . '</tr>'
     ;
$html .= '<tr>'
     . '<td>&nbsp;</td>'
     . '<td class="con">&nbsp;</td>'
     . '<td>&nbsp;</td>'
     . '<td class="num">&nbsp;</td>'
     . '<td class="num">&nbsp;</td>'
     . '<td>&nbsp;</td>'
     . '<td class="num">&nbsp;</td>'
     . '</tr>'
     ;


$f_syoukei8 = number_format($GLOBALS['zei8']);
$f_syoukei10 = number_format($GLOBALS['zei10']);
$f_zei8 = number_format($GLOBALS['zei8']*$tax_reduced_0);
$f_zei10 = number_format($GLOBALS['zei10']*$tax_standard_0);
$f_goukei8 = number_format($GLOBALS['zei8']*$tax_reduced);
$f_goukei10 = number_format($GLOBALS['zei10']*$tax_standard);

 $html .= '<tr>'
     . '<td>&nbsp;</td>'
     . '<td class="con">&nbsp;</td>'
     . '<td>&nbsp;</td>'
     . '<td class="num">税率8%合計</td>'
     . '<td class="num">￥'.$f_syoukei8.'&nbsp;</td>'
     . '<td>￥'.$f_zei8.'&nbsp;</td>'
     . '<td class="num">￥'.$f_goukei8.'&nbsp;</td>'
     . '</tr>'
     . '<tr>'
     . '<td>&nbsp;</td>'
     . '<td class="con">&nbsp;</td>'
     . '<td>&nbsp;</td>'
     . '<td class="num">税率10%合計</td>'
     . '<td class="num">￥'.$f_syoukei10.'&nbsp;</td>'
     . '<td>￥'.$f_zei10.'&nbsp;</td>'
     . '<td class="num">￥'.$f_goukei10.'&nbsp;</td>'
     . '</tr>'
     . '<tr>'
     . '<td>&nbsp;</td>'
     . '<td class="con">&nbsp;</td>'
     . '<td>&nbsp;</td>'
     . '<td class="num">合&nbsp;&nbsp;計</td>'
     . '<td class="num">￥'.$f_zbgoukei.'&nbsp;</td>'
     . '<td>￥'.$f_taxgoukei.'&nbsp;</td>'
     . '<td class="num">￥'.$f_goukei.'&nbsp;</td>'
     . '</tr>'

     . '</tbody></table>'
     . '</div>'
     ;
//output
//echo $html;
$pdf->writeHTML($css . $html, true, 0, true, 0);
ob_end_clean();
$pdf->Output( $filename, $type);

}

if (@$_POST['kakunin']) {
makepdf("test.pdf", "I");
//$pdf->Output("test.pdf", "I");
}

if (@$_POST['send']) {
$filePath = './tmp/' . 'hoge.pdf';
makepdf($filePath,"F");
//$pdf -> Output($filePath,'F');

require_once(dirname(__FILE__)."/PHPMailer/class.phpmailer.php");

      $from = "info@belltree-company.jp";
      $fromname = "uodon-market";
      $to = $email;
      $to1 = "info@belltree-company.jp";
      //$to = "info@belltree-company.jp";
      $subject = "魚丼市場、のれん代等ご請求書送付";
      $attachfile = "tmp/hoge.pdf";
      $body = "店舗オーナー各位\n" 
            ."お世話になります。当月分の請求書を送付しますのでご査収ください。\n\n"
            . "株式会社ベルツリーカンパニー\n"
            . "代表取締役　鈴木常隆"
            ;
      $mail = new PHPMailer();
      $mail->CharSet = "iso-2022-jp";
      $mail->Encoding = "7bit";
 
      $mail->AddAddress($to);
      $mail->From = $from;
      $mail->FromName = mb_convert_encoding($fromname,"JIS","UTF-8");
      $mail->Subject = mb_convert_encoding($subject,"JIS","UTF-8");
      $mail->Body  = mb_convert_encoding($body,"JIS","UTF-8");
 
      //添付ファイル追加
      $mail->AddAttachment($attachfile);
      //$mail->AddAttachment($attachfile2);
      $mail->Send(); //メール送信
      
      $mail->AddAddress($to1);
      $mail->Send(); //メール送信

    header("Location: https://uodon-market.jp/wordpress/wp-admin/admin.php?page=custompage");
}


?>
