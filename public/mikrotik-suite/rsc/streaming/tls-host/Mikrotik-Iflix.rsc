/ip firewall raw
rem [find address-list=Connection-Iflix]
add action=add-dst-to-address-list address-list=Connection-Iflix address-list-timeout=1d chain=prerouting protocol=tcp tls-host=*iflix.com* dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP comment="// CONNECTION IFLIX => {STREAMING}"
add action=add-dst-to-address-list address-list=Connection-Iflix address-list-timeout=1d chain=prerouting protocol=tcp tls-host=*.iflix.* dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP

/ip firewall address-list
rem [find list="Connection-Iflix"]
add address=103.209.104.0/24 list=Connection-Iflix
add address=18.164.0.0/15 list=Connection-Iflix
add address=43.155.124.23 list=Connection-Iflix
add address=43.154.240.111 list=Connection-Iflix
add address=43.135.105.98 list=Connection-Iflix
