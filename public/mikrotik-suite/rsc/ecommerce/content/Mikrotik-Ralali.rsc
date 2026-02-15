/ip firewall raw
rem [find address-list=Connection-Ralali]
add action=add-dst-to-address-list address-list=Connection-Ralali address-list-timeout=1d chain=prerouting content=ralali.com dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP comment="// CONNECTION RALALI => {MARKETPLACE}"
add action=add-dst-to-address-list address-list=Connection-Ralali address-list-timeout=1d chain=prerouting content=.ralali. dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP

/ip firewall address-list
rem [find list="Connection-Ralali"]
add address=104.26.10.186 list=Connection-Ralali
