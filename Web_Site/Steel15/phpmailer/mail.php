<?php
$name=$_POST['name'];
$email=$_POST['email'];
$subject=$_POST['subject'];
$message=$_POST['message'];
$type=$_POST['type'];
$content="$name  $email

$message";
$content= base64_encode($content);

$now = date('D, d M Y H:i:s',time());

$myfile = fopen("mailsend.txt", "w") or die("Unable to open file!");
$txt = "x-sender: root@steel15.com
x-receiver: yimian.liu@mail.steel15.club
Received: from [10.158.240.120] ([49.90.158.240]) by 10_186_11_47 with Microsoft SMTPSVC(8.5.9600.16384);
	 $now +0800
Date: $now +0800
Subject: $subject
X-Priority: 1
Message-ID: <ham3njcc9lsj2r9qi5n7iwidfo7wz0ydxsd0gijt1ds1gaok-5gbv7i-6hn97mts2f7c7vyjzj-flh5lz9v02q332el6vktsqazmu7ndf-o0z7pk-qpkidy-e7mhf8-24qvajsxappm4gr5n1tkjdpl.1525541492766@email.android.com>
From: \"$type\" <root@steel15.com>
To: \"Steel Manager\" <steel@mail.steel15.club>
MIME-Version: 1.0
Content-Type: text/plain; charset=utf-8
Content-Transfer-Encoding: base64
Return-Path: steel@mail.steel15.club
X-OriginalArrivalTime: 05 May 2018 17:46:55.0692 (UTC) FILETIME=[0F03E8C0:01D3E499]

$content";
fwrite($myfile, $txt);

fclose($myfile);

?>

<?php  

//复制图片  
function file2dir($sourcefile, $dir,$filename){  
     if( ! file_exists($sourcefile)){  
         return false;  
     }  
     //$filename = basename($sourcefile);  
     return copy($sourcefile, $dir .''. $filename);  
}  
$t=time();
$t=($t . ".eml");

file2dir("mailsend.txt", "../mail/drop/",$t);  

      
    $raw_success['text'] = 'success';  
      
      unlink("mailsend.txt");

      
      
echo json_encode($raw_success);

?>  