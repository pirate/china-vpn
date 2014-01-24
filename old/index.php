<head>
	<title>Nick's Free VPN</title>
</head>
<body>

<div style="display:block;border:1px black;width:50%;margin:auto; text-align:center;">
Current VPN Server Status:
<?php 
include_once('simple_html_dom.php');
$html = file_get_html('https://nicksweeting.com:10000/status/');
$conn = file_get_html('https://nicksweeting.com:10000/pptp-server/list_conns.cgi');

$conn = $conn->find('table[0]', 1);

$elem = $html->find('tr[id=row_d_1386916015]', 0);
echo $elem->find('img', 0);
echo "<br>"; echo $conn;
?>
<br>
<pre>

<b>Server Specs:</b>

100mbps/down
100mbps/up
20GB SSD
San Fransisco, CA


<h1 style="font-size:22px;">Free VPN Setup Instructions:</h1>
type: PPTP
server address: vpn.nicksweeting.com

username: (contact me to get one)
password: (contact me to get one)

encryption level: MPPE 128bit (maximum)

<a href="http://techie.org/Blog/pptp-vpn-os-x-leopard/">Step by step mac instructions</a> 

</pre>


Donate $2-6 per month to get <b>priority internet</b>.  I accept Paypal or BTC.<br>
<a class="coinbase-button" data-code="2328106f27a16bb93495ec4cdebc8941" data-button-style="donation_large" href="https://coinbase.com/checkouts/2328106f27a16bb93495ec4cdebc8941">Donate Bitcoins</a><script src="https://coinbase.com/assets/button.js" type="text/javascript"></script>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>




Hits to this page: <div align='center'><img src='http://www.hit-counts.com/counter.php?t=MTI5MzE4Ng==' border='0'></div>
</div>


</body>
