/ip firewall raw
rem [find address-list=Connection-Etsy]
add action=add-dst-to-address-list address-list=Connection-Etsy address-list-timeout=1d chain=prerouting protocol=tcp tls-host=*etsy.com* dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP comment="// CONNECTION ETSY => {MARKETPLACE}"
add action=add-dst-to-address-list address-list=Connection-Etsy address-list-timeout=1d chain=prerouting protocol=tcp tls-host=*.etsy.* dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP

/ip firewall address-list
rem [find list="Connection-Etsy"]
add address=192.147.0.0/23 list=Connection-Etsy
add address=35.190.25.237 list=Connection-Etsy
add address=151.101.128.0/22 list=Connection-Etsy
