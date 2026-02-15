/ip firewall raw
rem [find address-list=Connection-Instagram]
add action=add-dst-to-address-list address-list=Connection-Instagram address-list-timeout=1d chain=prerouting protocol=tcp tls-host=*instagram.com* dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP comment="// CONNECTION INSTAGRAM => {SOCIAL MEDIA}"
add action=add-dst-to-address-list address-list=Connection-Instagram address-list-timeout=1d chain=prerouting protocol=tcp tls-host=*.instagram.* dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP
add action=add-dst-to-address-list address-list=Connection-Instagram address-list-timeout=1d chain=prerouting protocol=tcp tls-host=*.ig.me* dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP
add action=add-dst-to-address-list address-list=Connection-Instagram address-list-timeout=1d chain=prerouting protocol=tcp tls-host=*.ig.* dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP

/ip firewall address-list
rem [find list="Connection-Instagram"]
add address=129.134.136.0/24 list=Connection-Instagram
add address=157.240.208.0/24 list=Connection-Instagram
add address=157.240.162.0/24 list=Connection-Instagram
add address=157.240.214.0/24 list=Connection-Instagram
add address=36.64.240.0/20 list=Connection-Instagram
add address=36.91.202.0/24 list=Connection-Instagram
add address=45.190.252.0/23 list=Connection-Instagram
add address=200.215.168.0/22 list=Connection-Instagram
add address=93.191.112.0/24 list=Connection-Instagram
