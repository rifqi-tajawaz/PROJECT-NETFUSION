/ip firewall raw
rem [find address-list=Connection-Olx]
add action=add-dst-to-address-list address-list=Connection-Olx address-list-timeout=1d chain=prerouting content=olx.com dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP comment="// CONNECTION OLX => {MARKETPLACE}"
add action=add-dst-to-address-list address-list=Connection-Olx address-list-timeout=1d chain=prerouting content=.olx. dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP

/ip firewall address-list
rem [find list="Connection-Olx"]
add address=3.208.97.147 list=Connection-Olx
add address=52.5.40.136 list=Connection-Olx
add address=23.32.0.0/11 list=Connection-Olx
add address=23.64.0.0/14 list=Connection-Olx
add address=23.58.157.137 list=Connection-Olx
add address=23.58.157.145 list=Connection-Olx
add address=104.16.51.111 list=Connection-Olx
add address=34.192.181.218 list=Connection-Olx
add address=54.145.132.167 list=Connection-Olx
add address=23.54.118.36 list=Connection-Olx
add address=23.54.118.38 list=Connection-Olx
