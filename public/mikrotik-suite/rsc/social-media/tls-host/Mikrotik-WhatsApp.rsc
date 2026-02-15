/ip firewall raw
rem [find address-list=Connection-Whatsapp]
add action=add-dst-to-address-list address-list=Connection-Whatsapp address-list-timeout=1d chain=prerouting protocol=tcp tls-host=*whatsapp.com* dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP comment="// CONNECTION WHATSAPP => {SOCIAL MEDIA}"
add action=add-dst-to-address-list address-list=Connection-Whatsapp address-list-timeout=1d chain=prerouting protocol=tcp tls-host=*.whatsapp.* dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP
add action=add-dst-to-address-list address-list=Connection-Whatsapp address-list-timeout=1d chain=prerouting protocol=tcp tls-host=*wa.me* dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP
add action=add-dst-to-address-list address-list=Connection-Whatsapp address-list-timeout=1d chain=prerouting protocol=tcp tls-host=*.wa.* dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP

/ip firewall address-list
rem [find list="Connection-Whatsapp"]
add address=66.111.48.0/22 list=Connection-Whatsapp
