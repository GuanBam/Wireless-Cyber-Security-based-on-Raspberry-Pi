# Configuration Steps
## 1. install required tools, dnsmasq and hostapd
```
#sudo apt install dnsmasq hostapd
```
## 2. stop the service with systemctl or service command depends on you.
```
#sudo systemctl stop dnsmasq
#sudo systemctl stop hostapd
```
or
```
#sudo service dnsmasq stop
#sudo service stop hostapd
```
## 3. configure static IP and DHCP server for the wireless interface.
a. run command to get wireless interface name (Usually will be "wlan0")
```
#iwconfig
```
b. configure the IP in /etc/dhcpcd.conf 
```
#sudo nano /etc/dhcpcd.conf
```
add below information at the end of the file.
"wlan0" should be replaced with your wireless extension name
"192.168.2.1/24" should be replaced with IP you expected.
```
interface wlan0
static ip_address=192.168.2.1/24
nohook wpa_supplicant
```
c. configure the DHCP server (dnsmasq)
copy the original dnsmasq.conf for back up, then modify it.
```
#sudo mv /etc/dnsmasq.conf /etc/dnsmasq.conf.orig
#sudo nano /etc/dnsmasq.conf
```
add below information to the file. 
"wlan0" should replaced with your wireless extension name.
dhcp-range= "starting IP for connecter","ending IP for connecter","subnetmask","expeired time"
```
interface=wlan0
dhcp-range=192.168.2.2,192.168.2.20,255.255.255.0,24h
```
d. start dnsmasq service or reload it if if it's on
```
#sudo systemctl start dnsmasq
```
## 4. Configure Host Information (hostapd)
a. configure hostapd basic configruation
```
#sudo nano /etc/hostapd/hostapd.conf
```
add below information to the configuration file
```
interface=wlan0
driver=nl80211
ssid=CyberSecurity
hw_mode=g
channel=7
wmm_enabled=0
macaddr_acl=0
```
b. configure hostapd starting path
```
#sudo nano /etc/default/hostapd
```
find DAEMON_CONF uncomment it and set the path as the one we just configured
```
DAEMON_CONF="/etc/hostapd/hostapd.conf"
```
c. start hostapd service
```
#sudo systemctl unmask hostapd
#sudo systemctl enable hostapd
#sudo systemctl start hostapd
```
## 5. Enable IP Forward
this step is necessary if you want the AP have access to the Internet
```
#sudo nano /etc/sysctl.conf
```
find and uncomment "net.ipv4.ip_forward=1"
```
net.ipv4.ip_forward=1
```
## 6. Configure iptables for traffic rules
"eth0" should be replaced with the network interface which connected to internet.
The command shows below will enable the network interface retransfer traffic. (So the AP can connect to Internet with this Interface)
```
#sudo iptables -t nat -A POSTROUTING -o eth0 -j MASQUERADE
```
This command will be flushed once the devices reboot. To make it permanet.
```
#sudo sh -c "iptables-save>/etc/iptables.ipv4.nat"
#sudo nano /etc/rc.local
```
add below statement just above "exit 0"
```
iptables-restore < /etc/iptables.ipv4.nat
```
