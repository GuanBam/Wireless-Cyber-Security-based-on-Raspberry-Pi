# Wireless-Cyber-Security-based-on-Raspberry-Pi
Partial wireless cyber security research based on raspberry pi.

## This research included two approaches: DNS Hijack, Capative Protal.

With DNS Hijack, when user visit a certain website (domain), the server (Raspbery Pi) will take user to the local website (phishing site) and collect user input.<br>
With Captaive Protal, when user connected to the network, the server (Raspberry Pi) will request user to login for internet connection, login information could be request for user social informatoin.

## Both approaches required to set the raspberry pi as Wireless Access Point (AP)

### Required tools or softwares for DNS Hijack:
  1. dnsmasq  (configure the devices network information)
  2. hostapd  (configure the devices as AP)
  3. apache2  (configure the devices as Server)
  4. bind9    (configure the DNS server)
  5. PHP      (collect user input)
   
### Required tools or softwares for Captive Protal:
  1. dnsmasq  (configure the devices network information)
  2. hostapd  (configure the devices as AP)
  3. apache2  (configure the devices as Server)
  4. PHP      (coperate with apache2, other programming language can do the samething, such as Python, Java)
  5. MySQL    (databse, other database will also work)
  
Notice: bind9 sometimes will conflict with dnsmasq for the IP address. You may stop both of them and start dnsmasq first then bind9
```shell
#sudo service bind9 stop
#sudo service dnsmasq stop
#sudo service dnsmasq start
#sudo service bind9 start
```
