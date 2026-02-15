/ip firewall raw
rem [find address-list=Connection-Pinterest]
add action=add-dst-to-address-list address-list=Connection-Pinterest address-list-timeout=1d chain=prerouting protocol=tcp tls-host=*pinterest.com* dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP comment="// CONNECTION PINTEREST => {SOCIAL MEDIA}"
add action=add-dst-to-address-list address-list=Connection-Pinterest address-list-timeout=1d chain=prerouting protocol=tcp tls-host=*.pinterest.* dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP

/ip firewall address-list
rem [find list="Connection-Pinterest"]
add list=pinterest-ip address=141.193.88.0/22 list=Connection-Pinterest
add list=pinterest-ip address=54.204.48.0/24 list=Connection-Pinterest
add list=pinterest-ip address=54.236.1.0/24  list=Connection-Pinterest
add list=pinterest-ip address=64.45.163.0/24 list=Connection-Pinterest
add list=pinterest-ip address=141.193.90.0/24 list=Connection-Pinterest
add list=pinterest-ip address=192.34.198.0/24 list=Connection-Pinterest
add list=pinterest-ip address=192.94.66.0/24 list=Connection-Pinterest
add list=pinterest-ip address=50.196.152.64/29 list=Connection-Pinterest
add list=pinterest-ip address=209.249.149.104/29 list=Connection-Pinterest
