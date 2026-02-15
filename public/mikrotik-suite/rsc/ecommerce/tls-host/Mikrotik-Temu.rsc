/ip firewall raw
rem [find address-list=Connection-Temu]
add action=add-dst-to-address-list address-list=Connection-Temu address-list-timeout=1d chain=prerouting protocol=tcp tls-host=*temu.com* dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP comment="// CONNECTION TEMU => {MARKETPLACE}"
add action=add-dst-to-address-list address-list=Connection-Temu address-list-timeout=1d chain=prerouting protocol=tcp tls-host=*.temu.* dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP

/ip firewall address-list
rem [find list="Connection-Temu"]
add address=104.18.43.206 list=Connection-Temu
add address=172.64.144.50 list=Connection-Temu
add address=151.101.2.58 list=Connection-Temu
add address=151.101.66.58 list=Connection-Temu
add address=20.223.39.110 list=Connection-Temu
add address=20.237.25.25 list=Connection-Temu
add address=20.83.143.240 list=Connection-Temu
add address=191.235.45.72 list=Connection-Temu
