/ip firewall raw
rem [find address-list=Connection-Blibli]
add action=add-dst-to-address-list address-list=Connection-Blibli address-list-timeout=1d chain=prerouting content=blibli.com dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP comment="// CONNECTION BLIBLI => {MARKETPLACE}"
add action=add-dst-to-address-list address-list=Connection-Blibli address-list-timeout=1d chain=prerouting content=.blibli. dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP

/ip firewall address-list
rem [find list="Connection-Blibli"]
add address=103.229.76.0/24 list=Connection-Blibli
add address=149.72.131.0/24 list=Connection-Blibli
add address=149.72.151.0/24 list=Connection-Blibli
add address=149.72.155.0/24 list=Connection-Blibli
add address=149.72.163.0/24 list=Connection-Blibli
add address=149.72.180.0/24 list=Connection-Blibli
add address=149.72.184.0/24 list=Connection-Blibli
add address=149.72.206.0/24 list=Connection-Blibli
add address=34.143.194.0/24 list=Connection-Blibli
