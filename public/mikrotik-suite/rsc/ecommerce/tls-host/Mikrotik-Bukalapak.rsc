/ip firewall raw
rem [find address-list=Connection-Bukalapak]
add action=add-dst-to-address-list address-list=Connection-Bukalapak address-list-timeout=1d chain=prerouting protocol=tcp tls-host=*bukalapak.com*  dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP comment="// CONNECTION BUKALAPAK => {MARKETPLACE}"
add action=add-dst-to-address-list address-list=Connection-Bukalapak address-list-timeout=1d chain=prerouting protocol=tcp tls-host=*.bukalapak.* dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP

/ip firewall address-list
rem [find list="Connection-Bukalapak"]
add address=103.117.82.0/24 list=Connection-Bukalapak
add address=103.117.83.0/24 list=Connection-Bukalapak
add address=103.64.14.0/24 list=Connection-Bukalapak
add address=103.97.251.0/24 list=Connection-Bukalapak
add address=139.5.40.0/22 list=Connection-Bukalapak
add address=45.122.52.0/22 list=Connection-Bukalapak
add address=45.125.72.0/22 list=Connection-Bukalapak
add address=125.62.92.0/24 list=Connection-Bukalapak
add address=125.62.93.0/24 list=Connection-Bukalapak
add address=125.62.94.0/24 list=Connection-Bukalapak
add address=125.62.95.0/24 list=Connection-Bukalapak
