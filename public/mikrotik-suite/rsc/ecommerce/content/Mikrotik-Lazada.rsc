/ip firewall raw
rem [find address-list=Connection-Lazada]
add action=add-dst-to-address-list address-list=Connection-Lazada address-list-timeout=1d chain=prerouting content=lazada.co.id dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP  comment="// CONNECTION LAZADA => {MARKETPLACE}"
add action=add-dst-to-address-list address-list=Connection-Lazada address-list-timeout=1d chain=prerouting content=.lazada. dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP

/ip firewall address-list
rem [find list="Connection-Lazada"]
add address=47.236.0.0/14 list=Connection-Lazada
add address=47.240.0.0/14 list=Connection-Lazada
add address=47.244.0.0/15 list=Connection-Lazada
add address=47.246.0.0/16 list=Connection-Lazada
add address=47.246.164.0/22 list=Connection-Lazada
