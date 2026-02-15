/ip firewall raw
rem [find address-list=Connection-Amazon]
add action=add-dst-to-address-list address-list=Connection-Amazon address-list-timeout=1d chain=prerouting content=amazon.com dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP comment="// CONNECTION AMAZON => {MARKETPLACE}"
add action=add-dst-to-address-list address-list=Connection-Amazon address-list-timeout=1d chain=prerouting content=.amazon. dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP

/ip firewall address-list
rem [find list="Connection-Amazon"]
add address=3.0.0.0/10 list=Connection-Amazon
add address=3.128.0.0/10 list=Connection-Amazon
add address=34.192.0.0/10 list=Connection-Amazon
add address=52.0.0.0/11 list=Connection-Amazon
add address=54.192.0.0/12 list=Connection-Amazon
add address=35.112.0.0/12 list=Connection-Amazon
