<?php
$file_path = "../system/log.log";
if(file_exists($file_path)){
$str = file_get_contents($file_path);//将整个文件内容读入到一个字符串中

echo $str;}
echo $str=base64_encode($str);

$file_path = "../system/dev.txt";

if(file_exists($file_path)){
$str2 = file_get_contents($file_path);//将整个文件内容读入到一个字符串中
$str2=iconv( "gb2312", "UTF-8//IGNORE" , "$str2");
echo $str2;}

?>

<?php
$servername = "localhost";
$username = "steel";
$password = "151515";
$dbname = "steel";
 
// 创建连接
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) 
{
    die("连接失败: " . $conn->connect_error);
} 

$time=date("20y-m-d 00:00:00");///获取当前日期

$sql="SELECT * FROM login WHERE time>='$time'";///从数据库调取今日0点后访问信息

$result = $conn->query($sql);///执行

$i=0; ///定义计数变量
$name2="访问者:";///定义访问者统计变量

while($row = $result->fetch_assoc())///枚举数组
{
$name= $row['name'];
$i++;
$name3="$name2";
$name2="$name3\n$name";
}

$time=date("20y-m-d");

/*统计日期： $time 访问人数：$i ; 访问者：$name2 */

echo $i;
echo $name2;
echo $time;

?>

<?php ///插入新数据到datecount
$sql1="INSERT INTO datecount VALUES ('$time', '$i', '$name2')";
if ($conn->query($sql1) === TRUE) {} 
else {echo "Error: " . $sql1 . "<br>" . $conn->error;}


$sql3="SELECT * FROM login WHERE reg=1";///从数据库调取今日0点后访问信息

$result = $conn->query($sql3);///执行
$x=0;
while($row = $result->fetch_assoc())///枚举数组
{$x++;}

echo $x;
$y=$i/$x;
$y=100*$y;
$y="$y%";
echo $y;

?>

<?php
$name="$time\n访问量: $i\n访问率: ";
$email=$y;
$subject="日访问量：$i";
$message="$name2";
$type="$time 站点访问统计";
$content="$name  $email

$message

今日站点更新：
$str2";
$content= base64_encode($content);

$now = date('D, d M Y H:i:s',time());

$myfile = fopen("mailsend.txt", "w") or die("Unable to open file!");
$txt = "x-sender: root@steel15.com
x-receiver: yimian.liu@mail.steel15.club
Received: from [10.158.240.120] ([49.90.158.240]) by 10_186_11_47 with Microsoft SMTPSVC(8.5.9600.16384);
	 $now +0800
Date: $now +0800
Subject: $subject
X-Priority: 3
Message-ID: <ham3njcc9lsj2r9qi5n7iwidfo7wz0ydxsd0gijt1ds1gaok-5gbv7i-6hn97mts2f7c7vyjzj-flh5lz9v02q332el6vktsqazmu7ndf-o0z7pk-qpkidy-e7mhf8-24qvajsxappm4gr5n1tkjdpl.1525541492766@email.android.com>
From: \"$type\" <root@steel15.com>
To: \"Steel Manager\" <steel@mail.steel15.club>
MIME-Version: 1.0
Content-Type: multipart/mixed; boundary=\"--_com.android.email_239912265213381\"
Return-Path: steel@mail.steel15.club
X-OriginalArrivalTime: 27 May 2018 08:36:31.0213 (UTC) FILETIME=[CFFAD1D0:01D3F595]

----_com.android.email_239912265213381
Content-Type: text/plain; charset=utf-8
Content-Transfer-Encoding: base64

$content
----_com.android.email_239912265213381
Content-Type: text/plain;
 name=\"访问日志.txt\"
Content-Transfer-Encoding: base64
Content-Disposition: attachment;
 filename=\"=?UTF-8?B?TEFOMDA06Kej6K+06K+NLnR4dA==?=\";
 size=572

$str

----_com.android.email_239912265213381--


";
fwrite($myfile, $txt);

fclose($myfile);

?>

<?php  

function file2dir($sourcefile, $dir,$filename)
{  
     if( ! file_exists($sourcefile))
	 {  
         return false;  
     }  
     //$filename = basename($sourcefile);  
     return copy($sourcefile, $dir .''. $filename);  
}  
$t=time();
$t=($t . ".eml");

file2dir("mailsend.txt", "../mail/drop/",$t);  //投递邮件

      
$raw_success['text'] = 'success';  
      
unlink("mailsend.txt");//删除临时文件

?>  