# Wireless-Cyber-Security-based-on-Raspberry-Pi
Partial wireless cyber security research based on raspberry pi.

## This research included two approaches: DNS Hijack, Capative Protocal.

With DNS Hijack, when user visit a certain website (domain), the server (Raspbery Pi) will take user to the local website (phishing site) and collect user input.
With Captaive Protocal, when user connected to the network, the server (Raspberry Pi) will request user to login for internet connection, login information could be request for user social informatoin.

## Both approaches required to set the raspberry pi as Wireless Access Point (AP)

### Required tools or softwares for DNS Hijack:
  1. dnsmasq  (configure the devices network information)
  2. hostapd  (configure the devices as AP)
  3. apache2  (configure the devices as Server)
  4. bind9    (configure the DNS server)
  5. PHP      (collect user input)
   
### Required tools or softwares for Captive Protocal:
  1. dnsmasq
  2. hostapd
  3. apache2
  4. PHP      (coperate with apache2)
  5. MySQL    (collect user information)
  
