/ip firewall raw
rem [find address-list=Connection-Twitch]
add action=add-dst-to-address-list address-list=Connection-Twitch address-list-timeout=1d chain=prerouting content=twitch.tv dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP comment="// CONNECTION TWITCH => {STREAMING}"
add action=add-dst-to-address-list address-list=Connection-Twitch address-list-timeout=1d chain=prerouting content=.twitch. dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP

/ip firewall address-list
rem [find list="Connection-Twitch"]
add address=23.160.0.0/24 list=Connection-Twitch
add address=45.113.128.0/22 list=Connection-Twitch
add address=52.129.128.0/23 list=Connection-Twitch
add address=52.129.130.0/24 list=Connection-Twitch
add address=52.129.131.0/24 list=Connection-Twitch
add address=52.129.132.0/22 list=Connection-Twitch
add address=52.129.136.0/21 list=Connection-Twitch
add address=52.129.144.0/20 list=Connection-Twitch
add address=52.129.160.0/19 list=Connection-Twitch
add address=52.129.192.0/18 list=Connection-Twitch
add address=52.223.192.0/20 list=Connection-Twitch
add address=52.223.216.0/21 list=Connection-Twitch
add address=52.223.224.0/20 list=Connection-Twitch
add address=52.223.240.0/20 list=Connection-Twitch
add address=99.181.64.0/20 list=Connection-Twitch
add address=99.181.80.0/21 list=Connection-Twitch
add address=99.181.88.0/21 list=Connection-Twitch
add address=99.181.96.0/19 list=Connection-Twitch
add address=103.53.48.0/22 list=Connection-Twitch
add address=185.42.204.0/22 list=Connection-Twitch
add address=192.16.64.0/21 list=Connection-Twitch
add address=192.108.239.0/24 list=Connection-Twitch
add address=199.9.248.0/21 list=Connection-Twitch
