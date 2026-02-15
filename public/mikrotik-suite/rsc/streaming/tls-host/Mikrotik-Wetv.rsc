/ip firewall raw
rem [find address-list=Connection-Wetv]
add action=add-dst-to-address-list address-list=Connection-Wetv address-list-timeout=1d chain=prerouting protocol=tcp tls-host=*wetv.vip* dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP comment="// CONNECTION WETV => {STREAMING}"
add action=add-dst-to-address-list address-list=Connection-Wetv address-list-timeout=1d chain=prerouting protocol=tcp tls-host=*.wetv.* dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP

/ip firewall address-list
rem [find list="Connection-Wetv"]
add address=18.245.124.52 list=Connection-Wetv
add address=92.123.239.51 list=Connection-Wetv
add address=183.47.102.118 list=Connection-Wetv
add address=101.33.46.28 list=Connection-Wetv
add address=13.249.9.97 list=Connection-Wetv
add address=129.226.103.196 list=Connection-Wetv
add address=43.155.124.23 list=Connection-Wetv
add address=3.164.240.112 list=Connection-Wetv
add address=43.156.222.16 list=Connection-Wetv
