/ip firewall raw
rem [find address-list=Connection-Wish]
add action=add-dst-to-address-list address-list=Connection-Wish address-list-timeout=1d chain=prerouting content=wish.com dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP comment="// CONNECTION WISH => {MARKETPLACE}"
add action=add-dst-to-address-list address-list=Connection-Wish address-list-timeout=1d chain=prerouting content=.wish. dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP

/ip firewall address-list
rem [find list="Connection-Wish"]
add address=104.18.42.20 list=Connection-Wish
add address=172.64.145.236 list=Connection-Wish
add address=104.16.0.0/12 list=Connection-Wish
add address=18.160.153.28 list=Connection-Wish
add address=108.138.114.142 list=Connection-Wish
add address=13.249.23.20 list=Connection-Wish
add address=185.17.20.0/22 list=Connection-Wish
add address=185.144.120.0/22 list=Connection-Wish
